<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\GraduateRecord;
use App\Models\ManualVerification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class VerificationController extends Controller
{
    /**
     * Verify a matriculation number against Summit University graduate records.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function verifyMatric(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'matric_number' => ['required', 'string', 'max:50'],
            'graduation_year' => ['nullable', 'integer', 'digits:4'],
            'department_id' => ['nullable', 'integer', 'exists:departments,id'],
        ], [
            'matric_number.required' => 'Please provide your Summit University matriculation number.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors(),
            ], 422);
        }

        // Normalize matric number (trim, uppercase, remove accidental double slashes)
        $rawMatric = (string) $request->input('matric_number');
        $normalizedMatric = strtoupper(trim(preg_replace('#/{2,}#', '/', str_replace('\\', '/', $rawMatric))));

        // Query the master graduate records
        $query = GraduateRecord::with(['faculty', 'department'])
            ->where('matric_number', $normalizedMatric);

        if ($request->filled('graduation_year')) {
            $query->where('graduation_year', $request->integer('graduation_year'));
        }

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->integer('department_id'));
        }

        $record = $query->first();

        // 1. Record not found
        if (!$record) {
            return response()->json([
                'success' => false,
                'status' => 'not_found',
                'message' => "Matriculation number '{$normalizedMatric}' was not found in the Summit University graduate registry.",
                'hint' => 'Please check for typos (e.g. 22/CONAS/CSC/025). If your record is missing, you may request manual verification.',
                'can_request_manual' => true,
            ], 404);
        }

        // 2. Already claimed
        if ($record->status === 'claimed' || $record->claimed_by_user_id !== null) {
            return response()->json([
                'success' => false,
                'status' => 'already_claimed',
                'message' => "This matriculation number ({$normalizedMatric}) has already been registered and claimed by a verified alumnus.",
                'support_email' => 'alumni@summituniversity.edu.ng',
                'can_dispute' => true,
            ], 409);
        }

        // 3. Disputed record
        if ($record->status === 'disputed') {
            return response()->json([
                'success' => false,
                'status' => 'disputed',
                'message' => 'This record is currently under administrative dispute review. Please contact the Alumni Directorate.',
            ], 409);
        }

        // 4. Record found and unclaimed -> generate HMAC signature for registration security
        $appKey = config('app.key') ?: 'summit-secret';
        $signaturePayload = "{$record->id}|{$record->matric_number}|{$record->graduation_year}";
        $verificationToken = hash_hmac('sha256', $signaturePayload, $appKey);

        return response()->json([
            'success' => true,
            'status' => 'verified',
            'message' => 'Graduate record successfully verified! Please complete your account details.',
            'verification_token' => $verificationToken,
            'graduate' => [
                'id' => $record->id,
                'matric_number' => $record->matric_number,
                'first_name' => $record->first_name,
                'last_name' => $record->last_name,
                'full_name' => $record->full_name,
                'masked_name' => $record->first_name . ' ' . substr($record->last_name, 0, 1) . '***',
                'degree' => $record->degree,
                'class_of_degree' => $record->class_of_degree,
                'graduation_year' => $record->graduation_year,
                'faculty' => $record->faculty?->name,
                'faculty_code' => $record->faculty?->code,
                'department' => $record->department?->name,
                'department_code' => $record->department?->code,
            ],
        ], 200);
    }

    /**
     * Submit a manual verification request when a record is missing or unmatched.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function submitManualVerification(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'full_name' => ['required', 'string', 'max:255'],
            'matric_number' => ['required', 'string', 'max:50'],
            'graduation_year' => ['required', 'integer', 'between:2016,2030'],
            'department_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'id_document' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'], // 10MB max
        ], [
            'full_name.required' => 'Please provide your full legal name as recorded during your study.',
            'matric_number.required' => 'Please provide your matriculation number.',
            'graduation_year.required' => 'Please enter your graduation year.',
            'department_name.required' => 'Please specify your department.',
            'email.required' => 'Please provide a valid contact email address.',
            'id_document.max' => 'Document size must not exceed 10MB.',
            'id_document.mimes' => 'Document must be a PDF, JPG, or PNG file.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors(),
            ], 422);
        }

        $documentPath = null;
        if ($request->hasFile('id_document')) {
            $documentPath = $request->file('id_document')->store('verification_docs', 'public');
        }

        $normalizedMatric = strtoupper(trim($request->string('matric_number')));

        $manual = ManualVerification::create([
            'user_id' => $request->user()?->id,
            'full_name' => $request->string('full_name'),
            'matric_number' => $normalizedMatric,
            'graduation_year' => $request->integer('graduation_year'),
            'department_name' => $request->string('department_name'),
            'email' => strtolower(trim($request->string('email'))),
            'phone' => $request->input('phone'),
            'id_document_path' => $documentPath,
            'notes' => $request->input('notes'),
            'status' => 'pending',
        ]);

        // Audit log
        AuditLog::create([
            'user_id' => $request->user()?->id,
            'action' => 'manual_verification_submitted',
            'description' => "Manual verification requested for matric {$normalizedMatric} by {$manual->full_name} ({$manual->email})",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'metadata' => [
                'ticket_id' => $manual->id,
                'has_document' => !empty($documentPath),
            ],
            'created_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Your manual verification request has been submitted successfully.',
            'ticket_id' => $manual->id,
            'reference_number' => 'SUM-VRF-' . str_pad((string) $manual->id, 5, '0', STR_PAD_LEFT),
            'instructions' => 'The Summit University Alumni Directorate will review your submission against academic records within 24–48 hours.',
        ], 201);
    }
}
