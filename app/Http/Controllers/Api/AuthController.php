<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\GraduateRecord;
use App\Models\PrivacySetting;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    /**
     * Register a new alumnus with a verified matriculation record.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'matric_number' => ['required', 'string', 'max:50'],
            'verification_token' => ['required', 'string'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:30'],
            'password' => ['required', 'string', Password::min(8)->letters()->numbers(), 'confirmed'],
            'ndpa_consent' => ['required', 'accepted'],
        ], [
            'matric_number.required' => 'Matriculation number is required.',
            'verification_token.required' => 'Verification token is required. Please verify your matriculation number first.',
            'email.unique' => 'This email address is already registered.',
            'password.min' => 'Password must be at least 8 characters long.',
            'password.confirmed' => 'Password confirmation does not match.',
            'ndpa_consent.accepted' => 'You must consent to the Nigeria Data Protection Act (NDPA) privacy terms to continue.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors(),
            ], 422);
        }

        $normalizedMatric = strtoupper(trim($request->string('matric_number')));
        $appKey = config('app.key') ?: 'summit-secret';

        try {
            $user = DB::transaction(function () use ($request, $normalizedMatric, $appKey) {
                // Find and lock the graduate record for atomic claim
                $record = GraduateRecord::where('matric_number', $normalizedMatric)
                    ->lockForUpdate()
                    ->first();

                if (!$record) {
                    abort(404, 'Graduate record not found.');
                }

                if ($record->status === 'claimed' || $record->claimed_by_user_id !== null) {
                    abort(409, 'This record has already been claimed by another alumnus.');
                }

                // Verify the HMAC token matches this record
                $expectedPayload = "{$record->id}|{$record->matric_number}|{$record->graduation_year}";
                $expectedToken = hash_hmac('sha256', $expectedPayload, $appKey);

                if (!hash_equals($expectedToken, (string) $request->input('verification_token'))) {
                    abort(403, 'Invalid or expired verification token. Please re-verify your matriculation number.');
                }

                // Create the user
                $newUser = User::create([
                    'name' => trim($request->string('name')),
                    'email' => strtolower(trim($request->string('email'))),
                    'matric_number' => $record->matric_number,
                    'phone' => $request->input('phone'),
                    'password' => Hash::make($request->string('password')),
                    'role' => 'alumnus',
                    'status' => 'active',
                    'graduate_record_id' => $record->id,
                    'ndpa_consent' => true,
                    'ndpa_consent_at' => now(),
                    'email_verified_at' => now(),
                ]);

                // Mark the record as claimed
                $record->update([
                    'status' => 'claimed',
                    'claimed_by_user_id' => $newUser->id,
                    'claimed_at' => now(),
                ]);

                // Initialize member profile with pre-filled education history
                $degreeText = $record->degree;
                if (!empty($record->class_of_degree)) {
                    $degreeText .= " ({$record->class_of_degree})";
                }

                Profile::create([
                    'user_id' => $newUser->id,
                    'headline' => "Summit University Alumnus — Class of {$record->graduation_year}",
                    'country' => 'Nigeria',
                    'education_history' => [
                        [
                            'school' => 'Summit University Offa',
                            'degree' => $degreeText,
                            'year' => (string) $record->graduation_year,
                        ],
                    ],
                ]);

                // Initialize NDPA 2023 compliant privacy settings
                PrivacySetting::create([
                    'user_id' => $newUser->id,
                    'profile_visibility' => 'public',
                    'email_visibility' => 'alumni_only',
                    'phone_visibility' => 'private',
                    'work_visibility' => 'public',
                    'location_visibility' => 'alumni_only',
                    'allow_messaging' => true,
                ]);

                // Audit log
                AuditLog::create([
                    'user_id' => $newUser->id,
                    'action' => 'alumnus_registered',
                    'description' => "Alumnus {$newUser->name} registered and claimed matric {$record->matric_number}.",
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'created_at' => now(),
                ]);

                return $newUser;
            });

            // Issue Sanctum token
            $token = $user->createToken('summit_auth_token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Registration successful! Welcome to the Summit University Alumni Network.',
                'token' => $token,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'matric_number' => $user->matric_number,
                    'role' => $user->role,
                    'status' => $user->status,
                ],
                'redirect' => '/dashboard',
            ], 201);

        } catch (\Symfony\Component\HttpKernel\Exception\HttpException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], $e->getStatusCode());
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Registration failed due to a server error. Please try again.',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Authenticate a user via email or matriculation number.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'login.required' => 'Please provide your email address or matriculation number.',
            'password.required' => 'Please enter your password.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors(),
            ], 422);
        }

        $loginInput = trim($request->string('login'));

        // Match either email or normalized matriculation number
        $user = User::where('email', strtolower($loginInput))
            ->orWhere('matric_number', strtoupper($loginInput))
            ->first();

        if (!$user || !Hash::check($request->string('password'), $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials. Please verify your email/matric number and password.',
            ], 401);
        }

        // Account status checks
        if ($user->status === 'suspended') {
            return response()->json([
                'success' => false,
                'message' => 'Your alumni account has been temporarily suspended. Please contact the Alumni Directorate.',
            ], 403);
        }

        if ($user->status === 'pending_verification') {
            return response()->json([
                'success' => false,
                'message' => 'Your account is pending verification by the Alumni Directorate. Please check back shortly.',
            ], 403);
        }

        // Create API token
        $token = $user->createToken('summit_auth_token')->plainTextToken;

        // Audit log
        AuditLog::create([
            'user_id' => $user->id,
            'action' => 'user_login',
            'description' => "User {$user->name} logged in.",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'created_at' => now(),
        ]);

        $redirect = $user->isAdmin() ? '/admin/dashboard' : '/dashboard';

        return response()->json([
            'success' => true,
            'message' => 'Logged in successfully.',
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'matric_number' => $user->matric_number,
                'phone' => $user->phone,
                'role' => $user->role,
                'status' => $user->status,
                'avatar' => $user->profile?->avatar,
            ],
            'redirect' => $redirect,
        ], 200);
    }

    /**
     * Get the authenticated user's profile and settings.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function me(Request $request): JsonResponse
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated.'], 401);
        }

        $user->load([
            'graduateRecord.faculty',
            'graduateRecord.department',
            'profile',
            'privacySetting',
        ]);

        return response()->json([
            'success' => true,
            'user' => $user,
        ], 200);
    }

    /**
     * Logout and invalidate the current access token.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()?->currentAccessToken()?->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully.',
        ], 200);
    }

    /**
     * Change user password.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function changePassword(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'current_password' => ['required', 'string'],
            'new_password' => ['required', 'string', Password::min(8)->letters()->numbers(), 'confirmed'],
        ], [
            'new_password.confirmed' => 'New password confirmation does not match.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = $request->user();

        if (!Hash::check($request->string('current_password'), $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'The current password provided is incorrect.',
            ], 422);
        }

        $user->update([
            'password' => Hash::make($request->string('new_password')),
        ]);

        AuditLog::create([
            'user_id' => $user->id,
            'action' => 'password_changed',
            'description' => "User {$user->name} updated their account password.",
            'ip_address' => $request->ip(),
            'created_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Password updated successfully.',
        ], 200);
    }

    /**
     * Update user privacy settings (NDPA compliance).
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function updatePrivacy(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'profile_visibility' => ['required', 'in:public,alumni_only,private'],
            'email_visibility' => ['required', 'in:public,alumni_only,private'],
            'phone_visibility' => ['required', 'in:public,alumni_only,private'],
            'work_visibility' => ['required', 'in:public,alumni_only,private'],
            'location_visibility' => ['required', 'in:public,alumni_only,private'],
            'allow_messaging' => ['boolean'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = $request->user();

        $privacy = PrivacySetting::updateOrCreate(
            ['user_id' => $user->id],
            $validator->validated()
        );

        AuditLog::create([
            'user_id' => $user->id,
            'action' => 'privacy_updated',
            'description' => "User {$user->name} modified NDPA privacy preferences.",
            'ip_address' => $request->ip(),
            'created_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Privacy settings updated successfully.',
            'privacy_settings' => $privacy,
        ], 200);
    }

    /**
     * Export all personal data stored on the platform (NDPA 2023 Right to Data Portability).
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function exportPersonalData(Request $request): JsonResponse
    {
        $user = $request->user()->load(['graduateRecord.faculty', 'graduateRecord.department', 'profile', 'privacySetting', 'auditLogs']);

        $exportData = [
            'compliance' => 'Nigeria Data Protection Act (NDPA) 2023 — Right to Data Portability',
            'university' => 'Summit University, Offa, Kwara State, Nigeria',
            'exported_at' => now()->toIso8601String(),
            'account' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'matric_number' => $user->matric_number,
                'phone' => $user->phone,
                'role' => $user->role,
                'status' => $user->status,
                'ndpa_consent' => $user->ndpa_consent,
                'ndpa_consent_at' => $user->ndpa_consent_at?->toIso8601String(),
                'registered_at' => $user->created_at?->toIso8601String(),
            ],
            'academic_record' => $user->graduateRecord ? [
                'matric_number' => $user->graduateRecord->matric_number,
                'degree' => $user->graduateRecord->degree,
                'class_of_degree' => $user->graduateRecord->class_of_degree,
                'graduation_year' => $user->graduateRecord->graduation_year,
                'faculty' => $user->graduateRecord->faculty?->name,
                'department' => $user->graduateRecord->department?->name,
                'claimed_at' => $user->graduateRecord->claimed_at?->toIso8601String(),
            ] : null,
            'public_profile' => $user->profile ? [
                'headline' => $user->profile->headline,
                'bio' => $user->profile->bio,
                'current_job_title' => $user->profile->current_job_title,
                'current_company' => $user->profile->current_company,
                'industry' => $user->profile->industry,
                'work_location' => $user->profile->work_location,
                'city' => $user->profile->city,
                'state' => $user->profile->state,
                'country' => $user->profile->country,
                'linkedin_url' => $user->profile->linkedin_url,
                'github_url' => $user->profile->github_url,
                'twitter_url' => $user->profile->twitter_url,
                'skills' => $user->profile->skills,
                'career_history' => $user->profile->career_history,
                'education_history' => $user->profile->education_history,
            ] : null,
            'privacy_settings' => $user->privacySetting ? [
                'profile_visibility' => $user->privacySetting->profile_visibility,
                'email_visibility' => $user->privacySetting->email_visibility,
                'phone_visibility' => $user->privacySetting->phone_visibility,
                'work_visibility' => $user->privacySetting->work_visibility,
                'location_visibility' => $user->privacySetting->location_visibility,
                'allow_messaging' => $user->privacySetting->allow_messaging,
            ] : null,
            'activity_audit_logs' => $user->auditLogs->map(fn ($log) => [
                'action' => $log->action,
                'description' => $log->description,
                'timestamp' => $log->created_at?->toIso8601String(),
            ]),
        ];

        AuditLog::create([
            'user_id' => $user->id,
            'action' => 'data_exported',
            'description' => "User {$user->name} downloaded full personal data archive under NDPA 2023.",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'created_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Personal data archive compiled successfully under NDPA 2023.',
            'data' => $exportData,
        ]);
    }

    /**
     * Erase user account and personal details (NDPA 2023 Right to Erasure).
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteAccount(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'password' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $user = $request->user();

        if (!Hash::check($request->string('password'), $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Incorrect password. Account deletion aborted.',
            ], 422);
        }

        // Prevent sole administrator from deleting own account
        if ($user->role === 'admin') {
            $adminCount = User::where('role', 'admin')->count();
            if ($adminCount <= 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete the only administrator account.',
                ], 422);
            }
        }

        DB::beginTransaction();
        try {
            // Unlink graduate record so it returns to unclaimed status in the university database
            if ($user->graduate_record_id) {
                $record = GraduateRecord::find($user->graduate_record_id);
                if ($record) {
                    $record->update([
                        'status' => 'unclaimed',
                        'claimed_by_user_id' => null,
                        'claimed_at' => null,
                    ]);
                }
            }

            // Log erasure event before deleting user
            AuditLog::create([
                'user_id' => null,
                'action' => 'account_erased_ndpa',
                'description' => "Account for {$user->name} ({$user->email}) was erased per NDPA 2023 Right to Erasure.",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'created_at' => now(),
            ]);

            // Revoke tokens and delete profile & privacy settings
            $user->tokens()->delete();
            $user->profile()->delete();
            $user->privacySetting()->delete();
            $user->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Your account and personal data have been completely erased from the platform in accordance with the NDPA 2023.',
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to process account deletion: ' . $e->getMessage(),
            ], 500);
        }
    }
}
