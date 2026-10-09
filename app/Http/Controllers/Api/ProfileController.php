<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\GraduateRecord;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    /**
     * Update the authenticated user's profile and contact details.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Request $request): JsonResponse
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'name' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'headline' => ['nullable', 'string', 'max:255'],
            'bio' => ['nullable', 'string', 'max:3000'],
            'current_job_title' => ['nullable', 'string', 'max:255'],
            'current_company' => ['nullable', 'string', 'max:255'],
            'industry' => ['nullable', 'string', 'max:255'],
            'work_location' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:100'],
            'state' => ['nullable', 'string', 'max:100'],
            'country' => ['nullable', 'string', 'max:100'],
            'linkedin_url' => ['nullable', 'url', 'max:255'],
            'twitter_url' => ['nullable', 'url', 'max:255'],
            'github_url' => ['nullable', 'url', 'max:255'],
            'portfolio_url' => ['nullable', 'url', 'max:255'],
            'skills' => ['nullable'],
            'career_history' => ['nullable', 'array'],
            'education_history' => ['nullable', 'array'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors(),
            ], 422);
        }

        // Update User attributes if provided
        $userUpdates = [];
        if ($request->filled('name')) {
            $userUpdates['name'] = trim($request->string('name'));
        }
        if ($request->has('phone')) {
            $userUpdates['phone'] = $request->input('phone');
        }
        if (!empty($userUpdates)) {
            $user->update($userUpdates);
        }

        // Parse skills: can be an array or comma-separated string
        $skills = $request->input('skills');
        if (is_string($skills)) {
            $skills = array_values(array_filter(array_map('trim', explode(',', $skills))));
        } elseif (is_array($skills)) {
            $skills = array_values(array_filter(array_map('trim', $skills)));
        } else {
            $skills = [];
        }

        // Prepare profile payload
        $profileData = [
            'headline' => $request->input('headline'),
            'bio' => $request->input('bio'),
            'current_job_title' => $request->input('current_job_title'),
            'current_company' => $request->input('current_company'),
            'industry' => $request->input('industry'),
            'work_location' => $request->input('work_location'),
            'city' => $request->input('city'),
            'state' => $request->input('state'),
            'country' => $request->input('country', 'Nigeria'),
            'linkedin_url' => $request->input('linkedin_url'),
            'twitter_url' => $request->input('twitter_url'),
            'github_url' => $request->input('github_url'),
            'portfolio_url' => $request->input('portfolio_url'),
            'skills' => $skills,
        ];

        if ($request->has('career_history')) {
            $profileData['career_history'] = $request->input('career_history') ?: [];
        }

        if ($request->has('education_history')) {
            $profileData['education_history'] = $request->input('education_history') ?: [];
        }

        $profile = Profile::updateOrCreate(
            ['user_id' => $user->id],
            $profileData
        );

        // Audit Log
        AuditLog::create([
            'user_id' => $user->id,
            'action' => 'profile_updated',
            'description' => "User {$user->name} updated their alumni profile details.",
            'ip_address' => $request->ip(),
            'created_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully.',
            'profile' => $profile,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'matric_number' => $user->matric_number,
            ],
        ], 200);
    }

    /**
     * Upload and update user avatar photo.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function uploadAvatar(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'avatar' => ['required', 'image', 'mimes:jpeg,png,jpg,webp', 'max:3072'],
        ], [
            'avatar.required' => 'Please select an image file to upload.',
            'avatar.image' => 'The uploaded file must be an image.',
            'avatar.mimes' => 'Avatar must be a JPEG, PNG, JPG, or WEBP file.',
            'avatar.max' => 'Image size cannot exceed 3MB.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = $request->user();
        $file = $request->file('avatar');

        // Store file in storage/app/public/avatars
        $path = $file->store('avatars', 'public');
        $avatarUrl = Storage::url($path);

        $profile = Profile::firstOrCreate(['user_id' => $user->id]);
        $profile->avatar = $avatarUrl;
        $profile->save();

        AuditLog::create([
            'user_id' => $user->id,
            'action' => 'avatar_updated',
            'description' => "User {$user->name} uploaded a new profile avatar.",
            'ip_address' => $request->ip(),
            'created_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Profile photo updated successfully!',
            'avatar_url' => $avatarUrl,
        ], 200);
    }

    /**
     * Get dashboard metrics and suggested classmates for the authenticated user.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getDashboardStats(Request $request): JsonResponse
    {
        $user = $request->user();
        $user->load(['graduateRecord.faculty', 'graduateRecord.department', 'profile', 'privacySetting']);

        $profile = $user->profile;
        $record = $user->graduateRecord;

        // Calculate Profile Completion Percentage
        $score = 20; // Base score for verified account
        if (!empty($profile?->avatar)) $score += 15;
        if (!empty($profile?->headline)) $score += 15;
        if (!empty($profile?->bio)) $score += 15;
        if (!empty($profile?->current_job_title) || !empty($profile?->current_company)) $score += 15;
        if (!empty($profile?->skills) && count($profile->skills) > 0) $score += 10;
        if (!empty($profile?->city) || !empty($profile?->state)) $score += 5;
        if (!empty($profile?->linkedin_url) || !empty($profile?->twitter_url) || !empty($profile?->github_url)) $score += 5;
        $completionPercentage = min(100, $score);

        // Community metrics
        $gradYear = $record?->graduation_year;
        $deptId = $record?->department_id;

        $classmatesInSet = $gradYear
            ? GraduateRecord::where('graduation_year', $gradYear)->count()
            : 0;

        $registeredClassmates = $gradYear
            ? User::whereHas('graduateRecord', fn($q) => $q->where('graduation_year', $gradYear))
                ->where('status', 'active')
                ->count()
            : 0;

        $departmentAlumni = $deptId
            ? User::whereHas('graduateRecord', fn($q) => $q->where('department_id', $deptId))
                ->where('status', 'active')
                ->count()
            : 0;

        $totalAlumni = User::where('role', 'alumnus')
            ->where('status', 'active')
            ->count();

        // Suggested classmates (peers from same year or department)
        $suggestedQuery = User::with(['graduateRecord.department', 'profile'])
            ->where('role', 'alumnus')
            ->where('status', 'active')
            ->where('id', '!=', $user->id);

        if ($gradYear) {
            $suggestedQuery->whereHas('graduateRecord', function ($q) use ($gradYear, $deptId) {
                $q->where('graduation_year', $gradYear)
                  ->orWhere('department_id', $deptId);
            });
        }

        $suggestedClassmates = $suggestedQuery->limit(4)->get()->map(function ($peer) {
            return [
                'id' => $peer->id,
                'name' => $peer->name,
                'avatar' => $peer->profile?->avatar,
                'headline' => $peer->profile?->headline,
                'current_job_title' => $peer->profile?->current_job_title,
                'current_company' => $peer->profile?->current_company,
                'degree' => $peer->graduateRecord?->degree,
                'graduation_year' => $peer->graduateRecord?->graduation_year,
                'department' => $peer->graduateRecord?->department?->name,
            ];
        });

        // Official University Updates & Announcements
        $announcements = [
            [
                'id' => 1,
                'title' => 'Annual Alumni Homecoming & Reunion 2026',
                'category' => 'Event',
                'date' => 'November 14, 2026',
                'summary' => 'Reconnect with fellow Summit graduates at the Offa permanent campus. Keynote address by the Vice-Chancellor.',
                'link' => '/features',
            ],
            [
                'id' => 2,
                'title' => 'Summit Alumni Career & Mentorship Network',
                'category' => 'Initiative',
                'date' => 'Ongoing',
                'summary' => 'Volunteer to mentor graduating students or recent graduates seeking career acceleration in tech, business, and sciences.',
                'link' => '/directory',
            ],
            [
                'id' => 3,
                'title' => 'NDPA 2023 Annual Privacy Affirmation',
                'category' => 'Compliance',
                'date' => 'Active',
                'summary' => 'Review and manage your data visibility settings at any time in accordance with Nigeria Data Protection guidelines.',
                'link' => '/privacy-settings',
            ],
        ];

        return response()->json([
            'success' => true,
            'stats' => [
                'completion_percentage' => $completionPercentage,
                'classmates_in_set' => $classmatesInSet,
                'registered_classmates' => $registeredClassmates,
                'department_alumni' => $departmentAlumni,
                'total_alumni' => $totalAlumni,
                'suggested_classmates' => $suggestedClassmates,
                'announcements' => $announcements,
            ],
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'matric_number' => $user->matric_number,
                'email' => $user->email,
                'phone' => $user->phone,
                'role' => $user->role,
                'status' => $user->status,
                'profile' => $profile,
                'academic' => [
                    'degree' => $record?->degree,
                    'class_of_degree' => $record?->class_of_degree,
                    'graduation_year' => $record?->graduation_year,
                    'faculty' => $record?->faculty?->name,
                    'department' => $record?->department?->name,
                ],
            ],
        ], 200);
    }
}
