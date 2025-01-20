<?php

namespace App\Http\Controllers;

use App\Models\BackgroundInformation;
use Illuminate\Http\Request;
use Exception;

class BackgroundInformationController extends Controller
{
    // Fetch all background information records
    public function index()
    {
        try{
        $backgroundInformation = BackgroundInformation::all();
        return response()->json($backgroundInformation);
    }catch (Exception $e) {
        // Log error and return response
        return response()->json(['message' => 'Error getting request', 'error' => $e->getMessage()], 500);
    }
    }

    // Get a single background information record by case ID
    public function show($caseId)
    {
        try {
        $backgroundInformation = BackgroundInformation::where('case_id', $caseId)->first();

        if (!$backgroundInformation) {
            return response()->json(['message' => 'Record not found'], 404);
        }

        return response()->json($backgroundInformation);
    }catch (Exception $e) {
        // Log error and return response
        return response()->json(['message' => 'Error showing record', 'error' => $e->getMessage()], 500);
    }
    }

    // Create or update a background information record
    public function storeOrUpdate(Request $request, $caseId)
    {
        try{
        $validatedData = $request->validate([
            'main_academic_field' => 'required|string',
            'specialization' => 'required|string',
            'unique_skillset' => 'required|string',
            'filing_niw' => 'required|in:yes,no',
            'critical_discussion_1' => 'required|string',
            'critical_discussion_2' => 'required|string',
            'critical_discussion_3' => 'required|string',
            'key_issue_1' => 'required|string',
            'key_issue_2' => 'required|string',
            'key_issue_2_discussion_field_1' => 'required|string',
            'key_issue_2_discussion_field_2' => 'required|string',
            'key_issue_3' => 'required|string',
            'key_issue_3_discussion_field_1' => 'required|string',
            'key_issue_3_discussion_field_2' => 'required|string',
            'benefit_us_issue_1' => 'required|string',
            'benefit_us_issue_1_discussion_field_1' => 'required|string',
            'benefit_us_issue_1_discussion_field_2' => 'required|string',
            'benefit_us_issue_2' => 'required|string',
            'benefit_us_issue_2_discussion_field_1' => 'required|string',
            'benefit_us_issue_2_discussion_field_2' => 'required|string',
            'benefit_us_issue_3' => 'required|string',
            'benefit_us_issue_3_discussion_field_1' => 'required|string',
            'benefit_us_issue_3_discussion_field_2' => 'required|string',
        ]);

        $backgroundInformation = BackgroundInformation::updateOrCreate(
            ['case_id' => $caseId],
            $validatedData
        );

        return response()->json(['message' => 'Record saved successfully', 'data' => $backgroundInformation]);
    }catch (Exception $e) {
        // Log error and return response
        return response()->json(['message' => 'Error saving record', 'error' => $e->getMessage()], 500);
    }
    }

    public function destroy($caseId)
{
    try {
        // Find the achievement by case_id
        $achievement = BackgroundInformation::where('case_id', $caseId)->first();

        if (!$achievement) {
            return response()->json(['message' => 'Background Information not found'], 404);
        }

        // Delete the achievement
        $achievement->delete();

        return response()->json(['message' => 'Background Information deleted successfully']);
    } catch (Exception $e) {
        // Handle any errors
        return response()->json([
            'message' => 'Error deleting record',
            'error' => $e->getMessage()
        ], 500);
    }
}


}
