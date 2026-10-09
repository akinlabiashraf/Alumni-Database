<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\ContactInquiry;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    /**
     * Store a public contact inquiry.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function submit(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:3000'],
        ], [
            'name.required' => 'Please provide your name.',
            'email.required' => 'Please provide your email address.',
            'subject.required' => 'Please specify the inquiry subject.',
            'message.required' => 'Please enter your message.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors(),
            ], 422);
        }

        $inquiry = ContactInquiry::create([
            'name' => trim($request->string('name')),
            'email' => strtolower(trim($request->string('email'))),
            'phone' => $request->input('phone'),
            'subject' => trim($request->string('subject')),
            'message' => trim($request->string('message')),
            'ip_address' => $request->ip(),
            'status' => 'new',
        ]);

        AuditLog::create([
            'user_id' => $request->user()?->id,
            'action' => 'contact_inquiry_submitted',
            'description' => "Public inquiry submitted by {$inquiry->name} ({$inquiry->email}): '{$inquiry->subject}'",
            'ip_address' => $request->ip(),
            'created_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Thank you for reaching out! Your message has been delivered to the Summit University Alumni Directorate. We will respond promptly.',
            'inquiry_id' => $inquiry->id,
        ], 201);
    }
}
