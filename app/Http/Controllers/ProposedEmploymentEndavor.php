<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProposedEmploymentEndavorRecord;
// use Log;
use Exception;

class ProposedEmploymentEndavor extends Controller
{
 /**
     * Store or update a record for a given case ID.
     */
    public function storeOrUpdate(Request $request, $caseId)
    {
        try {
        $data = $request->validate([
            'type' => 'nullable|array',
            'selection' => 'nullable|in:yes,no',
            'proposed_endavor_field_1' => 'nullable|string',
            'proposed_endavor_field_2' => 'nullable|string',
            'proposed_endavor_field_3' => 'nullable|string',
            'past_experience' => 'nullable|string',
            'publication_plans' => 'nullable|string',
            'status' => 'nullable|in:pending,review,finalized,rejected',
            'currently_student_us_niw' => 'nullable|in:yes,no',
            'currently_employed_academic_niw' => 'nullable|in:yes,no',
            'currently_employed_postdoctoral_niw' => 'nullable|in:yes,no',
            'received_promotion_notice_niw' => 'nullable|in:yes,no',
            'not_promoted_notice_niw' => 'nullable|in:yes,no',
            'currently_medical_resident_niw' => 'nullable|in:yes,no',
            'currently_visiting_scholar_niw' => 'nullable|in:yes,no',
            'currently_employed_us_business_niw' => 'nullable|in:yes,no',
            'currently_employed_outside_us_niw' => 'nullable|in:yes,no',
            'currently_unemployed_niw' => 'nullable|in:yes,no',
            'currently_student_outside_us_niw' => 'nullable|in:yes,no',
            'currently_intern_part_time_niw' => 'nullable|in:yes,no',
            'currently_employed_visa_expiring_niw' => 'nullable|in:yes,no',
            'currently_intern_student_niw' => 'nullable|in:yes,no',
            'currently_unemployed_with_offer_niw' => 'nullable|in:yes,no',
            'other_niw' => 'nullable|in:yes,no',
            'currently_student_us_eb1a' => 'nullable|in:yes,no',
            'currently_employed_academic_eb1a' => 'nullable|in:yes,no',
            'currently_employed_postdoctoral_eb1a' => 'nullable|in:yes,no',
            'received_promotion_notice_eb1a' => 'nullable|in:yes,no',
            'not_promoted_notice_eb1a' => 'nullable|in:yes,no',
            'currently_medical_resident_eb1a' => 'nullable|in:yes,no',
            'currently_visiting_scholar_eb1a' => 'nullable|in:yes,no',
            'currently_employed_us_business_eb1a' => 'nullable|in:yes,no',
            'currently_employed_outside_us_eb1a' => 'nullable|in:yes,no',
            'currently_unemployed_eb1a' => 'nullable|in:yes,no',
            'currently_student_outside_us_eb1a' => 'nullable|in:yes,no',
            'currently_intern_part_time_eb1a' => 'nullable|in:yes,no',
            'currently_employed_visa_expiring_eb1a' => 'nullable|in:yes,no',
            'currently_intern_student_eb1a' => 'nullable|in:yes,no',
            'currently_unemployed_with_offer_eb1a' => 'nullable|in:yes,no',
            'other_eb1a' => 'nullable|in:yes,no',
            'currently_student_us_eb1b' => 'nullable|in:yes,no',
            'currently_employed_academic_eb1b' => 'nullable|in:yes,no',
            'currently_employed_postdoctoral_eb1b' => 'nullable|in:yes,no',
            'received_promotion_notice_eb1b' => 'nullable|in:yes,no',
            'not_promoted_notice_eb1b' => 'nullable|in:yes,no',
            'currently_medical_resident_eb1b' => 'nullable|in:yes,no',
            'currently_visiting_scholar_eb1b' => 'nullable|in:yes,no',
            'currently_employed_us_business_eb1b' => 'nullable|in:yes,no',
            'currently_employed_outside_us_eb1b' => 'nullable|in:yes,no',
            'currently_unemployed_eb1b' => 'nullable|in:yes,no',
            'currently_student_outside_us_eb1b' => 'nullable|in:yes,no',
            'currently_intern_part_time_eb1b' => 'nullable|in:yes,no',
            'currently_employed_visa_expiring_eb1b' => 'nullable|in:yes,no',
            'currently_intern_student_eb1b' => 'nullable|in:yes,no',
            'currently_unemployed_with_offer_eb1b' => 'nullable|in:yes,no',
            'other_eb1b' => 'nullable|in:yes,no',
            'currently_student_us_o1' => 'nullable|in:yes,no',
            'currently_employed_academic_o1' => 'nullable|in:yes,no',
            'currently_employed_postdoctoral_o1' => 'nullable|in:yes,no',
            'received_promotion_notice_o1' => 'nullable|in:yes,no',
            'not_promoted_notice_o1' => 'nullable|in:yes,no',
            'currently_medical_resident_o1' => 'nullable|in:yes,no',
            'currently_visiting_scholar_o1' => 'nullable|in:yes,no',
            'currently_employed_us_business_o1' => 'nullable|in:yes,no',
            'currently_employed_outside_us_o1' => 'nullable|in:yes,no',
            'currently_unemployed_o1' => 'nullable|in:yes,no',
            'currently_student_outside_us_o1' => 'nullable|in:yes,no',
            'currently_intern_part_time_o1' => 'nullable|in:yes,no',
            'currently_employed_visa_expiring_o1' => 'nullable|in:yes,no',
            'currently_intern_student_o1' => 'nullable|in:yes,no',
            'currently_unemployed_with_offer_o1' => 'nullable|in:yes,no',
            'other_o1' => 'nullable|in:yes,no',
        ]);

        $data['type'] = json_encode($data['type']);

        // Find existing record or create a new one
        $record = ProposedEmploymentEndavorRecord::firstOrNew(['case_id' => $caseId]);

        // Update the record with the validated data
        $record->fill($data);
        $record->save();

        return response()->json(['message' => 'Record saved successfully', 'record' => $record], 200);
    }catch (Exception $e) {
        // Log the error for debugging
        // Log::error('Error saving record: ' . $e->getMessage());

        return response()->json(['message' => 'Error saving record', 'error' => $e->getMessage()], 500);
    }
    }

    /**
     * Get a record by case ID.
     */
    public function get($caseId)
    {
        $record = ProposedEmploymentEndavorRecord::where('case_id', $caseId)->first();

        if (!$record) {
            return response()->json(['message' => 'Record not found'], 404);
        }

        return response()->json(['record' => $record], 200);
    }

    /**
     * Delete a record by case ID.
     */
    public function delete($caseId)
    {
        $record = ProposedEmploymentEndavorRecord::where('case_id', $caseId)->first();

        if (!$record) {
            return response()->json(['message' => 'Record not found'], 404);
        }

        $record->delete();

        return response()->json(['message' => 'Record deleted successfully'], 200);
    }


}
