<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AlumniController extends Controller
{
    /**
     * Get paginated alumni directory with faceted filters and NDPA privacy masking.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $viewer = $request->user('sanctum');
        $isAuthAlumnus = $viewer !== null;
        $isAdmin = $viewer && $viewer->isAdmin();

        $query = User::with([
            'graduateRecord.faculty',
            'graduateRecord.department',
            'profile',
            'privacySetting',
        ])
        ->where('role', 'alumnus')
        ->where('status', 'active');

        // Privacy guard for unauthenticated public viewers:
        // Hide profiles whose profile_visibility is 'private' or 'alumni_only' if guest
        if (!$isAuthAlumnus && !$isAdmin) {
            $query->whereHas('privacySetting', function ($q) {
                $q->where('profile_visibility', 'public');
            });
        }

        // 1. Keyword search (name, matric, headline, company, industry, skills)
        if ($request->filled('search')) {
            $term = trim($request->string('search'));
            $query->where(function ($q) use ($term) {
                $q->where('name', 'LIKE', "%{$term}%")
                  ->orWhere('matric_number', 'LIKE', "%{$term}%")
                  ->orWhereHas('profile', function ($p) use ($term) {
                      $p->where('headline', 'LIKE', "%{$term}%")
                        ->orWhere('current_job_title', 'LIKE', "%{$term}%")
                        ->orWhere('current_company', 'LIKE', "%{$term}%")
                        ->orWhere('industry', 'LIKE', "%{$term}%")
                        ->orWhere('city', 'LIKE', "%{$term}%");
                  })
                  ->orWhereHas('graduateRecord', function ($g) use ($term) {
                      $g->where('degree', 'LIKE', "%{$term}%");
                  });
            });
        }

        // 2. Filter by Graduating Set / Year
        if ($request->filled('year')) {
            $year = $request->integer('year');
            $query->whereHas('graduateRecord', function ($q) use ($year) {
                $q->where('graduation_year', $year);
            });
        }

        // 3. Filter by Faculty
        if ($request->filled('faculty_id')) {
            $facultyId = $request->integer('faculty_id');
            $query->whereHas('graduateRecord', function ($q) use ($facultyId) {
                $q->where('faculty_id', $facultyId);
            });
        }

        // 4. Filter by Department
        if ($request->filled('department_id')) {
            $deptId = $request->integer('department_id');
            $query->whereHas('graduateRecord', function ($q) use ($deptId) {
                $q->where('department_id', $deptId);
            });
        }

        // 5. Filter by Industry
        if ($request->filled('industry')) {
            $industry = trim($request->string('industry'));
            $query->whereHas('profile', function ($q) use ($industry) {
                $q->where('industry', 'LIKE', "%{$industry}%");
            });
        }

        // Sorting
        $sort = $request->input('sort', 'year_desc');
        if ($sort === 'name_asc') {
            $query->orderBy('name', 'asc');
        } elseif ($sort === 'name_desc') {
            $query->orderBy('name', 'desc');
        } else {
            // Default: Most recent graduating sets first
            $query->orderBy(
                \App\Models\GraduateRecord::select('graduation_year')
                    ->whereColumn('graduate_records.id', 'users.graduate_record_id'),
                'desc'
            )->orderBy('name', 'asc');
        }

        $perPage = min(50, max(6, $request->integer('per_page', 12)));
        $paginator = $query->paginate($perPage);

        // Transform results and apply NDPA privacy masking
        $transformed = $paginator->getCollection()->map(function ($alumnus) use ($isAuthAlumnus, $isAdmin) {
            return $this->transformAlumnus($alumnus, $isAuthAlumnus, $isAdmin);
        });

        return response()->json([
            'success' => true,
            'current_page' => $paginator->currentPage(),
            'last_page' => $paginator->lastPage(),
            'per_page' => $paginator->perPage(),
            'total' => $paginator->total(),
            'data' => $transformed,
        ]);
    }

    /**
     * Get a single alumnus detailed profile with NDPA privacy shielding.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function show(Request $request, int $id): JsonResponse
    {
        $viewer = $request->user('sanctum');
        $isAuthAlumnus = $viewer !== null;
        $isAdmin = $viewer && $viewer->isAdmin();

        $alumnus = User::with([
            'graduateRecord.faculty',
            'graduateRecord.department',
            'profile',
            'privacySetting',
        ])
        ->where('role', 'alumnus')
        ->where('status', 'active')
        ->find($id);

        if (!$alumnus) {
            return response()->json([
                'success' => false,
                'message' => 'Alumnus profile not found or inactive.',
            ], 404);
        }

        // Check overall profile visibility
        $visibility = $alumnus->privacySetting?->profile_visibility ?? 'alumni_only';
        if ($visibility === 'private' && !$isAdmin && (!$viewer || $viewer->id !== $alumnus->id)) {
            return response()->json([
                'success' => false,
                'message' => 'This alumnus has chosen to keep their profile private.',
            ], 403);
        }

        if ($visibility === 'alumni_only' && !$isAuthAlumnus && !$isAdmin) {
            return response()->json([
                'success' => false,
                'message' => 'This profile is visible only to verified Summit University alumni. Please sign in.',
                'requires_auth' => true,
            ], 403);
        }

        return response()->json([
            'success' => true,
            'alumnus' => $this->transformAlumnus($alumnus, $isAuthAlumnus, $isAdmin, true),
        ]);
    }

    /**
     * Apply per-field NDPA 2023 privacy filters to alumnus data.
     *
     * @param User $alumnus
     * @param bool $isAuthAlumnus
     * @param bool $isAdmin
     * @param bool $fullDetails
     * @return array
     */
    private function transformAlumnus(User $alumnus, bool $isAuthAlumnus, bool $isAdmin, bool $fullDetails = false): array
    {
        $privacy = $alumnus->privacySetting;
        $profile = $alumnus->profile;
        $record = $alumnus->graduateRecord;

        // Email visibility
        $email = null;
        $emailVisibility = $privacy?->email_visibility ?? 'alumni_only';
        if ($isAdmin || $emailVisibility === 'public' || ($isAuthAlumnus && $emailVisibility === 'alumni_only')) {
            $email = $alumnus->email;
        }

        // Phone visibility
        $phone = null;
        $phoneVisibility = $privacy?->phone_visibility ?? 'private';
        if ($isAdmin || $phoneVisibility === 'public' || ($isAuthAlumnus && $phoneVisibility === 'alumni_only')) {
            $phone = $alumnus->phone;
        }

        // Work visibility
        $jobTitle = $profile?->current_job_title;
        $company = $profile?->current_company;
        $industry = $profile?->industry;
        $workVisibility = $privacy?->work_visibility ?? 'public';
        if (!$isAdmin && $workVisibility === 'private') {
            $jobTitle = null;
            $company = null;
        }

        // Location visibility
        $city = $profile?->city;
        $state = $profile?->state;
        $country = $profile?->country ?? 'Nigeria';
        $locationVisibility = $privacy?->location_visibility ?? 'alumni_only';
        if (!$isAdmin && $locationVisibility === 'private') {
            $city = null;
            $state = null;
        }

        $data = [
            'id' => $alumnus->id,
            'name' => $alumnus->name,
            'matric_number' => $isAdmin || $isAuthAlumnus ? $alumnus->matric_number : null,
            'email' => $email,
            'email_hidden' => empty($email),
            'phone' => $phone,
            'phone_hidden' => empty($phone),
            'avatar' => $profile?->avatar,
            'headline' => $profile?->headline,
            'current_job_title' => $jobTitle,
            'current_company' => $company,
            'industry' => $industry,
            'city' => $city,
            'state' => $state,
            'country' => $country,
            'location_display' => array_filter([$city, $state, $country]) ? implode(', ', array_filter([$city, $state, $country])) : 'Nigeria',
            'degree' => $record?->degree,
            'class_of_degree' => $record?->class_of_degree,
            'graduation_year' => $record?->graduation_year,
            'faculty' => $record?->faculty?->name,
            'faculty_code' => $record?->faculty?->code,
            'department' => $record?->department?->name,
            'department_code' => $record?->department?->code,
            'skills' => $profile?->skills ?? [],
            'is_verified' => true,
        ];

        if ($fullDetails) {
            $data['bio'] = $profile?->bio;
            $data['linkedin_url'] = $profile?->linkedin_url;
            $data['twitter_url'] = $profile?->twitter_url;
            $data['github_url'] = $profile?->github_url;
            $data['portfolio_url'] = $profile?->portfolio_url;
            $data['education_history'] = $profile?->education_history ?? [];
            $data['career_history'] = $profile?->career_history ?? [];
            $data['allow_messaging'] = (bool) ($privacy?->allow_messaging ?? true);
        }

        return $data;
    }
}
