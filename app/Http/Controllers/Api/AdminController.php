<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Department;
use App\Models\Faculty;
use App\Models\GraduateRecord;
use App\Models\ManualVerification;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminController extends Controller
{
    /**
     * Get aggregate statistics and metrics for the Admin Dashboard.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function dashboard(Request $request): JsonResponse
    {
        $totalRecords = GraduateRecord::count();
        $claimedRecords = GraduateRecord::whereNotNull('claimed_by_user_id')->count();
        $unclaimedRecords = $totalRecords - $claimedRecords;
        $claimRate = $totalRecords > 0 ? round(($claimedRecords / $totalRecords) * 100, 1) : 0;

        $totalUsers = User::count();
        $alumniUsers = User::where('role', 'alumnus')->count();
        $adminUsers = User::where('role', 'admin')->count();
        $moderatorUsers = User::where('role', 'moderator')->count();

        $pendingVerifications = ManualVerification::where('status', 'pending')->count();
        $approvedVerifications = ManualVerification::where('status', 'approved')->count();
        $rejectedVerifications = ManualVerification::where('status', 'rejected')->count();

        // Breakdown by Graduation Year / Set
        $setStats = GraduateRecord::select('graduation_year', DB::raw('count(*) as total'), DB::raw('count(claimed_by_user_id) as claimed'))
            ->groupBy('graduation_year')
            ->orderBy('graduation_year', 'asc')
            ->get()
            ->map(function ($row) {
                $total = (int) $row->total;
                $claimed = (int) $row->claimed;
                $rate = $total > 0 ? round(($claimed / $total) * 100, 1) : 0;
                return [
                    'year' => (int) $row->graduation_year,
                    'total' => $total,
                    'claimed' => $claimed,
                    'claim_rate' => $rate,
                ];
            });

        // Breakdown by Faculty
        $facultyStats = Faculty::withCount(['graduateRecords as total_records', 'graduateRecords as claimed_records' => function ($q) {
            $q->whereNotNull('claimed_by_user_id');
        }])->get()->map(function ($f) {
            $total = (int) $f->total_records;
            $claimed = (int) $f->claimed_records;
            $rate = $total > 0 ? round(($claimed / $total) * 100, 1) : 0;
            return [
                'id' => $f->id,
                'name' => $f->name,
                'code' => $f->code,
                'total' => $total,
                'claimed' => $claimed,
                'claim_rate' => $rate,
            ];
        });

        // Recent claims
        $recentClaims = GraduateRecord::whereNotNull('claimed_by_user_id')
            ->with(['claimedByUser:id,name,email,matric_number', 'department:id,name', 'faculty:id,name'])
            ->orderBy('claimed_at', 'desc')
            ->limit(5)
            ->get();

        // Recent manual verification submissions
        $recentVerifications = ManualVerification::with('user:id,name,email')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Recent audit logs
        $recentAuditLogs = AuditLog::with('user:id,name,email,role')
            ->orderBy('created_at', 'desc')
            ->limit(8)
            ->get();

        return response()->json([
            'success' => true,
            'metrics' => [
                'total_records' => $totalRecords,
                'claimed_records' => $claimedRecords,
                'unclaimed_records' => $unclaimedRecords,
                'claim_rate' => $claimRate,
                'total_users' => $totalUsers,
                'alumni_users' => $alumniUsers,
                'admin_users' => $adminUsers,
                'moderator_users' => $moderatorUsers,
                'pending_verifications' => $pendingVerifications,
                'approved_verifications' => $approvedVerifications,
                'rejected_verifications' => $rejectedVerifications,
            ],
            'set_distribution' => $setStats,
            'faculty_distribution' => $facultyStats,
            'recent_claims' => $recentClaims,
            'recent_verifications' => $recentVerifications,
            'recent_audit_logs' => $recentAuditLogs,
        ]);
    }

    /**
     * Get paginated master graduate records with filtering and search.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getRecords(Request $request): JsonResponse
    {
        $query = GraduateRecord::with(['faculty:id,name,code', 'department:id,name,code', 'claimedByUser:id,name,email,matric_number']);

        if ($request->filled('search')) {
            $search = trim($request->string('search'));
            $query->where(function ($q) use ($search) {
                $q->where('matric_number', 'LIKE', "%{$search}%")
                  ->orWhere('first_name', 'LIKE', "%{$search}%")
                  ->orWhere('last_name', 'LIKE', "%{$search}%")
                  ->orWhere('full_name', 'LIKE', "%{$search}%");
            });
        }

        if ($request->filled('faculty_id')) {
            $query->where('faculty_id', $request->integer('faculty_id'));
        }

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->integer('department_id'));
        }

        if ($request->filled('graduation_year')) {
            $query->where('graduation_year', $request->integer('graduation_year'));
        }

        if ($request->filled('status')) {
            $status = (string) $request->string('status');
            if ($status === 'claimed') {
                $query->whereNotNull('claimed_by_user_id');
            } elseif ($status === 'unclaimed') {
                $query->whereNull('claimed_by_user_id');
            }
        }

        $perPage = min(max($request->integer('per_page', 20), 10), 100);
        $records = $query->orderBy('graduation_year', 'desc')
            ->orderBy('matric_number', 'asc')
            ->paginate($perPage);

        return response()->json([
            'success' => true,
            'records' => $records->items(),
            'pagination' => [
                'current_page' => $records->currentPage(),
                'last_page' => $records->lastPage(),
                'per_page' => $records->perPage(),
                'total' => $records->total(),
            ],
        ]);
    }

    /**
     * Create a single graduate record manually.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function storeRecord(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'matric_number' => ['required', 'string', 'max:50', 'unique:graduate_records,matric_number'],
            'first_name' => ['required', 'string', 'max:100'],
            'middle_name' => ['nullable', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'faculty_id' => ['required', 'exists:faculties,id'],
            'department_id' => ['required', 'exists:departments,id'],
            'degree' => ['required', 'string', 'max:150'],
            'class_of_degree' => ['nullable', 'string', 'max:100'],
            'graduation_year' => ['required', 'integer', 'min:2015', 'max:2035'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors(),
            ], 422);
        }

        $matricNumber = strtoupper(trim($request->string('matric_number')));
        $firstName = trim($request->string('first_name'));
        $middleName = trim($request->string('middle_name'));
        $lastName = trim($request->string('last_name'));
        $fullName = trim(preg_replace('/\s+/', ' ', "{$firstName} {$middleName} {$lastName}"));

        $record = GraduateRecord::create([
            'matric_number' => $matricNumber,
            'first_name' => $firstName,
            'middle_name' => $middleName ?: null,
            'last_name' => $lastName,
            'full_name' => $fullName,
            'faculty_id' => $request->integer('faculty_id'),
            'department_id' => $request->integer('department_id'),
            'degree' => trim($request->string('degree')),
            'class_of_degree' => trim($request->string('class_of_degree')),
            'graduation_year' => $request->integer('graduation_year'),
            'status' => 'unclaimed',
        ]);

        AuditLog::create([
            'user_id' => $request->user()->id,
            'action' => 'record_created',
            'description' => "Admin {$request->user()->name} added record for {$record->matric_number} ({$record->full_name}).",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'created_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => "Graduate record {$record->matric_number} created successfully.",
            'record' => $record->load(['faculty:id,name,code', 'department:id,name,code']),
        ], 201);
    }

    /**
     * Update an existing graduate record.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function updateRecord(Request $request, int $id): JsonResponse
    {
        $record = GraduateRecord::find($id);
        if (!$record) {
            return response()->json(['success' => false, 'message' => 'Graduate record not found.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'matric_number' => ['required', 'string', 'max:50', "unique:graduate_records,matric_number,{$id}"],
            'first_name' => ['required', 'string', 'max:100'],
            'middle_name' => ['nullable', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'faculty_id' => ['required', 'exists:faculties,id'],
            'department_id' => ['required', 'exists:departments,id'],
            'degree' => ['required', 'string', 'max:150'],
            'class_of_degree' => ['nullable', 'string', 'max:100'],
            'graduation_year' => ['required', 'integer', 'min:2015', 'max:2035'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors(),
            ], 422);
        }

        $matricNumber = strtoupper(trim($request->string('matric_number')));
        $firstName = trim($request->string('first_name'));
        $middleName = trim($request->string('middle_name'));
        $lastName = trim($request->string('last_name'));
        $fullName = trim(preg_replace('/\s+/', ' ', "{$firstName} {$middleName} {$lastName}"));

        $record->update([
            'matric_number' => $matricNumber,
            'first_name' => $firstName,
            'middle_name' => $middleName ?: null,
            'last_name' => $lastName,
            'full_name' => $fullName,
            'faculty_id' => $request->integer('faculty_id'),
            'department_id' => $request->integer('department_id'),
            'degree' => trim($request->string('degree')),
            'class_of_degree' => trim($request->string('class_of_degree')),
            'graduation_year' => $request->integer('graduation_year'),
        ]);

        AuditLog::create([
            'user_id' => $request->user()->id,
            'action' => 'record_updated',
            'description' => "Admin {$request->user()->name} updated record {$record->matric_number}.",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'created_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => "Graduate record {$record->matric_number} updated successfully.",
            'record' => $record->load(['faculty:id,name,code', 'department:id,name,code', 'claimedByUser:id,name,email']),
        ]);
    }

    /**
     * Delete an unclaimed graduate record.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function deleteRecord(Request $request, int $id): JsonResponse
    {
        $record = GraduateRecord::find($id);
        if (!$record) {
            return response()->json(['success' => false, 'message' => 'Graduate record not found.'], 404);
        }

        if ($record->claimed_by_user_id) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete a record that has already been claimed by an active user.',
            ], 422);
        }

        $matric = $record->matric_number;
        $record->delete();

        AuditLog::create([
            'user_id' => $request->user()->id,
            'action' => 'record_deleted',
            'description' => "Admin {$request->user()->name} deleted record {$matric}.",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'created_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => "Record {$matric} deleted successfully.",
        ]);
    }

    /**
     * Bulk import graduate records via CSV.
     *
     * Expected columns:
     * matric_number, first_name, middle_name, last_name, faculty_code, department_code, degree, class_of_degree, graduation_year
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function importCsv(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'file' => ['required', 'file', 'mimes:csv,txt', 'max:10240'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors(),
            ], 422);
        }

        $file = $request->file('file');
        $handle = fopen($file->getRealPath(), 'r');
        if (!$handle) {
            return response()->json(['success' => false, 'message' => 'Unable to read uploaded CSV file.'], 422);
        }

        // Cache lookups for faculties and departments
        $facultiesByCode = Faculty::all()->keyBy(fn ($f) => strtoupper($f->code));
        $departmentsByCode = Department::all()->keyBy(fn ($d) => strtoupper($d->code));

        $header = fgetcsv($handle);
        if (!$header) {
            fclose($handle);
            return response()->json(['success' => false, 'message' => 'The uploaded CSV file is empty.'], 422);
        }

        // Clean UTF-8 BOM and trim header keys
        $header = array_map(function ($col) {
            return strtolower(trim(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $col)));
        }, $header);

        $requiredCols = ['matric_number', 'first_name', 'last_name', 'faculty_code', 'department_code', 'degree', 'graduation_year'];
        foreach ($requiredCols as $col) {
            if (!in_array($col, $header)) {
                fclose($handle);
                return response()->json([
                    'success' => false,
                    'message' => "Missing required CSV column: '{$col}'. Please use the official CSV template.",
                ], 422);
            }
        }

        $colIndices = array_flip($header);
        $importedCount = 0;
        $duplicateCount = 0;
        $errorCount = 0;
        $errors = [];
        $rowNumber = 1;

        DB::beginTransaction();
        try {
            while (($row = fgetcsv($handle)) !== false) {
                $rowNumber++;
                // Skip empty lines
                if (empty(array_filter($row))) {
                    continue;
                }

                $matric = isset($colIndices['matric_number']) && isset($row[$colIndices['matric_number']])
                    ? strtoupper(trim($row[$colIndices['matric_number']]))
                    : '';
                
                if (empty($matric)) {
                    $errorCount++;
                    $errors[] = "Row {$rowNumber}: Missing matriculation number.";
                    continue;
                }

                // Duplicate check against existing records
                if (GraduateRecord::where('matric_number', $matric)->exists()) {
                    $duplicateCount++;
                    continue;
                }

                $firstName = isset($colIndices['first_name']) ? trim($row[$colIndices['first_name']] ?? '') : '';
                $middleName = isset($colIndices['middle_name']) ? trim($row[$colIndices['middle_name']] ?? '') : null;
                $lastName = isset($colIndices['last_name']) ? trim($row[$colIndices['last_name']] ?? '') : '';
                $facultyCode = strtoupper(isset($colIndices['faculty_code']) ? trim($row[$colIndices['faculty_code']] ?? '') : '');
                $deptCode = strtoupper(isset($colIndices['department_code']) ? trim($row[$colIndices['department_code']] ?? '') : '');
                $degree = isset($colIndices['degree']) ? trim($row[$colIndices['degree']] ?? '') : '';
                $classOfDegree = isset($colIndices['class_of_degree']) ? trim($row[$colIndices['class_of_degree']] ?? '') : null;
                $gradYear = isset($colIndices['graduation_year']) ? (int) trim($row[$colIndices['graduation_year']] ?? 0) : 0;

                if (empty($firstName) || empty($lastName)) {
                    $errorCount++;
                    $errors[] = "Row {$rowNumber} ({$matric}): First name and last name are required.";
                    continue;
                }

                $faculty = $facultiesByCode->get($facultyCode);
                if (!$faculty) {
                    $errorCount++;
                    $errors[] = "Row {$rowNumber} ({$matric}): Unknown faculty code '{$facultyCode}'.";
                    continue;
                }

                $department = $departmentsByCode->get($deptCode);
                if (!$department) {
                    $errorCount++;
                    $errors[] = "Row {$rowNumber} ({$matric}): Unknown department code '{$deptCode}'.";
                    continue;
                }

                if ($gradYear < 2015 || $gradYear > 2035) {
                    $errorCount++;
                    $errors[] = "Row {$rowNumber} ({$matric}): Invalid graduation year '{$gradYear}'.";
                    continue;
                }

                GraduateRecord::create([
                    'matric_number' => $matric,
                    'first_name' => $firstName,
                    'middle_name' => $middleName ?: null,
                    'last_name' => $lastName,
                    'full_name' => trim("{$firstName} {$middleName} {$lastName}"),
                    'faculty_id' => $faculty->id,
                    'department_id' => $department->id,
                    'degree' => $degree,
                    'class_of_degree' => $classOfDegree ?: null,
                    'graduation_year' => $gradYear,
                    'status' => 'unclaimed',
                ]);

                $importedCount++;
            }

            fclose($handle);
            DB::commit();

            AuditLog::create([
                'user_id' => $request->user()->id,
                'action' => 'bulk_csv_imported',
                'description' => "Admin {$request->user()->name} imported {$importedCount} records via CSV ({$duplicateCount} duplicates skipped, {$errorCount} errors).",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'created_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => "CSV Import finished. {$importedCount} records imported successfully.",
                'summary' => [
                    'imported_count' => $importedCount,
                    'duplicate_count' => $duplicateCount,
                    'error_count' => $errorCount,
                    'errors' => array_slice($errors, 0, 15), // Show first 15 errors if any
                ],
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();
            if (is_resource($handle)) {
                fclose($handle);
            }
            return response()->json([
                'success' => false,
                'message' => 'Failed to process CSV file: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Download the official CSV template for graduate records.
     *
     * @return StreamedResponse
     */
    public function downloadTemplateCsv(): StreamedResponse
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="summit_graduate_records_template.csv"',
        ];

        return response()->stream(function () {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, [
                'matric_number',
                'first_name',
                'middle_name',
                'last_name',
                'faculty_code',
                'department_code',
                'degree',
                'class_of_degree',
                'graduation_year',
            ]);

            fputcsv($handle, [
                'SU/24/NAS/001',
                'Adebayo',
                'Kabir',
                'Lawal',
                'FNAS',
                'CSC',
                'B.Sc. Computer Science',
                'First Class Honours',
                '2024',
            ]);

            fputcsv($handle, [
                'SU/24/MSS/012',
                'Fatima',
                'Zahra',
                'Aliyu',
                'FMSS',
                'ACC',
                'B.Sc. Accounting',
                'Second Class Honours (Upper Division)',
                '2024',
            ]);

            fclose($handle);
        }, 200, $headers);
    }

    /**
     * Get manual verification requests queue.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getVerifications(Request $request): JsonResponse
    {
        $query = ManualVerification::with(['user:id,name,email,matric_number', 'reviewedBy:id,name']);

        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        if ($request->filled('search')) {
            $search = trim($request->string('search'));
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'LIKE', "%{$search}%")
                  ->orWhere('matric_number', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        $verifications = $query->orderBy('created_at', 'desc')->paginate(20);

        return response()->json([
            'success' => true,
            'verifications' => $verifications->items(),
            'pagination' => [
                'current_page' => $verifications->currentPage(),
                'last_page' => $verifications->lastPage(),
                'total' => $verifications->total(),
            ],
        ]);
    }

    /**
     * Approve a manual verification request.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function approveVerification(Request $request, int $id): JsonResponse
    {
        $verification = ManualVerification::find($id);
        if (!$verification) {
            return response()->json(['success' => false, 'message' => 'Verification request not found.'], 404);
        }

        if ($verification->status !== 'pending') {
            return response()->json(['success' => false, 'message' => "Request is already {$verification->status}."], 422);
        }

        $user = $verification->user;
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Associated user account not found.'], 404);
        }

        DB::beginTransaction();
        try {
            // Find or create matching graduate record
            $record = GraduateRecord::where('matric_number', $verification->matric_number)->first();
            if ($record) {
                if ($record->claimed_by_user_id && $record->claimed_by_user_id !== $user->id) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => 'This matriculation number is already claimed by another user.',
                    ], 409);
                }
                $record->update([
                    'status' => 'claimed',
                    'claimed_by_user_id' => $user->id,
                    'claimed_at' => now(),
                ]);
            } else {
                // Find matching department by name if possible
                $dept = Department::where('name', 'LIKE', "%{$verification->department_name}%")->first();
                $facultyId = $dept?->faculty_id ?? Faculty::first()?->id;

                $nameParts = explode(' ', trim($verification->full_name));
                $firstName = $nameParts[0] ?? 'Alumnus';
                $lastName = count($nameParts) > 1 ? end($nameParts) : 'Member';
                $middleName = count($nameParts) > 2 ? $nameParts[1] : null;

                $record = GraduateRecord::create([
                    'matric_number' => $verification->matric_number,
                    'first_name' => $firstName,
                    'middle_name' => $middleName,
                    'last_name' => $lastName,
                    'full_name' => $verification->full_name,
                    'faculty_id' => $facultyId,
                    'department_id' => $dept?->id,
                    'degree' => $dept ? $dept->degree_title : 'B.Sc. Degree',
                    'graduation_year' => $verification->graduation_year,
                    'status' => 'claimed',
                    'claimed_by_user_id' => $user->id,
                    'claimed_at' => now(),
                ]);
            }

            // Link user
            $user->update([
                'status' => 'active',
                'role' => 'alumnus',
                'matric_number' => $verification->matric_number,
                'graduate_record_id' => $record->id,
            ]);

            // Update verification status
            $verification->update([
                'status' => 'approved',
                'reviewed_by_user_id' => $request->user()->id,
                'reviewed_at' => now(),
            ]);

            AuditLog::create([
                'user_id' => $request->user()->id,
                'action' => 'verification_approved',
                'description' => "Admin {$request->user()->name} approved manual verification #{$verification->id} for {$user->name} ({$verification->matric_number}).",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'created_at' => now(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Manual verification approved! Alumnus {$user->name} is now verified and linked.",
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Approval failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Reject a manual verification request.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function rejectVerification(Request $request, int $id): JsonResponse
    {
        $verification = ManualVerification::find($id);
        if (!$verification) {
            return response()->json(['success' => false, 'message' => 'Verification request not found.'], 404);
        }

        if ($verification->status !== 'pending') {
            return response()->json(['success' => false, 'message' => "Request is already {$verification->status}."], 422);
        }

        $validator = Validator::make($request->all(), [
            'rejection_reason' => ['required', 'string', 'min:5', 'max:500'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors(),
            ], 422);
        }

        $reason = trim($request->string('rejection_reason'));

        $verification->update([
            'status' => 'rejected',
            'rejection_reason' => $reason,
            'reviewed_by_user_id' => $request->user()->id,
            'reviewed_at' => now(),
        ]);

        AuditLog::create([
            'user_id' => $request->user()->id,
            'action' => 'verification_rejected',
            'description' => "Admin {$request->user()->name} rejected verification #{$verification->id} ({$verification->matric_number}). Reason: {$reason}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'created_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Manual verification request has been rejected.',
        ]);
    }

    /**
     * Get paginated users list with search and filter.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getUsers(Request $request): JsonResponse
    {
        $query = User::with(['graduateRecord:id,degree,graduation_year', 'profile:id,user_id,headline,city,state']);

        if ($request->filled('search')) {
            $search = trim($request->string('search'));
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('matric_number', 'LIKE', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->string('role'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(20);

        return response()->json([
            'success' => true,
            'users' => $users->items(),
            'pagination' => [
                'current_page' => $users->currentPage(),
                'last_page' => $users->lastPage(),
                'total' => $users->total(),
            ],
        ]);
    }

    /**
     * Update user role (Superadmin only).
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function updateUserRole(Request $request, int $id): JsonResponse
    {
        if (!$request->user()->isAdmin()) {
            return response()->json(['success' => false, 'message' => 'Only Super Administrators can change user roles.'], 403);
        }

        $user = User::find($id);
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not found.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'role' => ['required', 'in:admin,moderator,alumnus,student'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors(),
            ], 422);
        }

        $newRole = $request->string('role')->toString();

        // Prevent self-demotion if the user is the only admin
        if ($user->id === $request->user()->id && $newRole !== 'admin') {
            $adminCount = User::where('role', 'admin')->count();
            if ($adminCount <= 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot demote the only remaining administrator on the platform.',
                ], 422);
            }
        }

        $oldRole = $user->role;
        $user->update(['role' => $newRole]);

        AuditLog::create([
            'user_id' => $request->user()->id,
            'action' => 'user_role_changed',
            'description' => "Admin {$request->user()->name} changed role of user {$user->name} from '{$oldRole}' to '{$newRole}'.",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'created_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => "Role for {$user->name} updated to {$newRole}.",
            'user' => $user,
        ]);
    }

    /**
     * Update user account status (active / suspended).
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function updateUserStatus(Request $request, int $id): JsonResponse
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not found.'], 404);
        }

        if ($user->id === $request->user()->id) {
            return response()->json(['success' => false, 'message' => 'You cannot change your own account status.'], 422);
        }

        $validator = Validator::make($request->all(), [
            'status' => ['required', 'in:active,suspended,pending_verification'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $newStatus = $request->string('status')->toString();
        $user->update(['status' => $newStatus]);

        AuditLog::create([
            'user_id' => $request->user()->id,
            'action' => 'user_status_changed',
            'description' => "Admin {$request->user()->name} changed status of {$user->name} to '{$newStatus}'.",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'created_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => "Status for {$user->name} updated to {$newStatus}.",
            'user' => $user,
        ]);
    }

    /**
     * Get system audit logs.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getAuditLogs(Request $request): JsonResponse
    {
        $query = AuditLog::with('user:id,name,email,role');

        if ($request->filled('action')) {
            $query->where('action', $request->string('action'));
        }

        if ($request->filled('search')) {
            $search = trim($request->string('search'));
            $query->where('description', 'LIKE', "%{$search}%");
        }

        $logs = $query->orderBy('created_at', 'desc')->paginate(25);

        return response()->json([
            'success' => true,
            'logs' => $logs->items(),
            'pagination' => [
                'current_page' => $logs->currentPage(),
                'last_page' => $logs->lastPage(),
                'total' => $logs->total(),
            ],
        ]);
    }
}
