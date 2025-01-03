<?php

namespace App\Http\Controllers;

use App\Models\CaseProfile;
use App\Models\Cases;
use Illuminate\Http\Request;

class CaseProfileController extends Controller
{
    // Store a new case profile
    public function store(Request $request, $caseId)
    {
        // Validate input
        $request->validate([
            'academic_degree' => 'nullable|string',
            'citation_database_link' => 'nullable|string',
            'current_us_position' => 'nullable|string',
            'proposed_employment_us' => 'nullable|string',
            'same_or_similar_field' => 'nullable|in:yes,no',
            'alternative_field_1' => 'nullable|string|required_if:same_or_similar_field,no',
            'alternative_field_2' => 'nullable|string|required_if:same_or_similar_field,no',
            'conduct_research' => 'nullable|in:yes,no',
            'ongoing_project_1' => 'nullable|string|required_if:conduct_research,yes',
            'ongoing_project_2' => 'nullable|string|required_if:conduct_research,yes',
            'number_of_papers_reviewed' => 'nullable|string',
            'editor_role' => 'nullable|in:yes,no',
        ]);

        // Ensure the case exists
        $case = Cases::findOrFail($caseId);

        // Create or update the profile
        $profile = CaseProfile::updateOrCreate(
            ['case_id' => $case->id], // Match existing profile by case_id
            $request->only([
                'academic_degree',
                'citation_database_link',
                'current_us_position',
                'proposed_employment_us',
                'same_or_similar_field',
                'alternative_field_1',
                'alternative_field_2',
                'conduct_research',
                'ongoing_project_1',
                'ongoing_project_2',
                'number_of_papers_reviewed',
                'editor_role',
            ])
        );

        return response()->json([
            'success' => true,
            'message' => 'Case profile saved successfully.',
            'data' => $profile,
        ], 201);
    }

    // Retrieve a case profile
    public function show($caseId)
    {
        $profile = CaseProfile::where('case_id', $caseId)->first();

        if (!$profile) {
            return response()->json([
                'success' => false,
                'message' => 'Profile not found for the given case.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $profile,
        ]);
    }
}
