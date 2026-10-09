<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Faculty;
use App\Models\GraduateRecord;
use Illuminate\Http\JsonResponse;

class AcademicController extends Controller
{
    /**
     * Get faculties and nested departments for dropdowns.
     *
     * @return JsonResponse
     */
    public function getAcademicData(): JsonResponse
    {
        $faculties = Faculty::with([
            'departments' => function ($q) {
                $q->select('id', 'faculty_id', 'name', 'code', 'degree_title')->orderBy('name');
            },
        ])->orderBy('name')->get(['id', 'name', 'code', 'description']);

        $graduationYears = GraduateRecord::distinct()
            ->pluck('graduation_year')
            ->filter()
            ->sortDesc()
            ->values();

        return response()->json([
            'success' => true,
            'faculties' => $faculties,
            'graduation_years' => $graduationYears,
        ]);
    }
}
