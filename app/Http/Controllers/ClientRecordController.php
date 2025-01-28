<?php

namespace App\Http\Controllers;

use App\Models\ClientRecord;
use App\Models\Dependent;
use Illuminate\Http\Request;
use App\Models\Cases;
use Log;
use Exception;

class ClientRecordController extends Controller
{

    public function store(Request $request, $caseId)
{
    try {
        // Validate the request data
        $validatedData = $request->validate([
            'case_id' => 'required|integer',
            'petition_types' => 'required|array',
            'petition_category' => 'required|string',
            'filing_plan_eb1' => 'required|string',
            'filing_plan_eb2' => 'required|string',
            'previous_visa_filing' => 'required|string',
            'i485_filing_plan' => 'required|string',
            'last_name' => 'required|string',
            'first_name' => 'required|string',
            'middle_name' => 'nullable|string',
            'gender' => 'required|string',
            'title' => 'required|string',
            'date_of_birth' => 'required|date',
            'country_of_birth' => 'required|string',
            'country_of_citizenship' => 'required|string',
            'in_us' => 'required|boolean',
            'visa_status' => 'required|string',
            'ds2019_expiration' => 'nullable|date',
            'visa_expiration' => 'nullable|date',
            'passport_expiration' => 'nullable|date',
            'no_passport_applied' => 'required|boolean',
            'admit_until_date' => 'nullable|date',
            'applying_new_visa' => 'required|boolean',
            'visa_type' => 'nullable|string',
            'latest_entry_date' => 'nullable|date',
            'latest_visa_status' => 'nullable|string',
            'j_visa_status' => 'required|boolean',
            'communist_party_member' => 'required|boolean',
            'employer_name' => 'required|string',
            'job_title' => 'required|string',
            'proposed_employment_field' => 'required|string',
            'company_name' => 'required|string',
            'job_description' => 'required|string',
            'full_time' => 'required|boolean',
            'permanent_position' => 'required|boolean',
            'worksite_city' => 'required|string',
            'worksite_state' => 'required|string',
            'paper_publication_year' => 'required|string',
            'asylum_applied' => 'required|boolean',
            'street_address' => 'required|string',
            'address_line_2' => 'nullable|string',
            'city' => 'required|string',
            'state' => 'required|string',
            'zip_code' => 'required|string',
            'country' => 'required|string',
            'email' => 'required|email',
            'phone_number' => 'required|string',
            'referral_source' => 'required|string',
            'social_media_source' => 'nullable|string',
            'has_dependents' => 'required|boolean',
            'marital_status' => 'required|string',
            // 'dependents' => 'nullable|array',
            'dependents.*.id' => 'nullable|integer',
            'dependents.*.last_name' => 'required|string',
            'dependents.*.first_name' => 'required|string',
            'dependents.*.middle_name' => 'nullable|string',
            'dependents.*.relation' => 'required|string',
            'dependents.*.country_of_birth' => 'required|string',
            'dependents.*.date_of_birth' => 'required|date',
            'dependents.*.passport_expiration' => 'nullable|date',
            'dependents.*.no_passport_applied' => 'required|boolean',
            'dependents.*.gender' => 'required|string',
            'dependents.*.address' => 'nullable|string',
            'dependents.*.visa_processing_option' => 'required|string',
            'dependents.*.processing_country' => 'required|string',
        ]);

        // Ensure the case exists
        $case = Cases::findOrFail($caseId);
        $validatedData['petition_types'] = json_encode($validatedData['petition_types']);

        $caseClientRecords = ClientRecord::where('case_id', $caseId)->first();

        if ($caseClientRecords) {
            // Update existing client record
            $caseClientRecords->update($request->except('dependents'));

            // Update or create dependents
            if ($request->has('dependents')) {
                foreach ($request->dependents as $dependentData) {
                    if (isset($dependentData['id'])) {
                        // Update existing dependent
                        $dependent = $caseClientRecords->dependents()->find($dependentData['id']);
                        if ($dependent) {
                            $dependent->update($dependentData);
                        }
                    } else {
                        // Create new dependent
                        $caseClientRecords->dependents()->create($dependentData);
                    }
                }
            }

            // Return response
            return response()->json([
                'message' => 'Client Records updated successfully',
                'data' => $caseClientRecords->load('dependents')
            ]);
        } else {
            // Create new client record
            $caseClientRecords = ClientRecord::create($validatedData);

            // Add new dependents
            if ($request->has('dependents')) {
                foreach ($request->dependents as $dependentData) {
                    $caseClientRecords->dependents()->create($dependentData);
                }
            }

            // Return response
            return response()->json([
                'message' => 'Client Records created successfully',
                'data' => $caseClientRecords->load('dependents')
            ]);
        }
    } catch (Exception $e) {
        // Log error and return response
        return response()->json(['message' => 'Error saving record', 'error' => $e->getMessage()], 500);
    }
}

public function index($caseId)
{
    try{
    // Retrieve the ClientRecord along with its related case and dependents using case_id
    $caseClientRecords = ClientRecord::with('case', 'dependents')->where('case_id', $caseId)->firstOrFail();

    return response()->json($caseClientRecords);
}catch (Exception $e) {
    // Log error and return response
    return response()->json(['message' => 'Error viewing record', 'error' => $e->getMessage()], 500);
}
}


public function deleteDependent($clientRecordId, $dependentId)
{
    try {
        // Fetch the specific dependent for the given client record
        $dependent = Dependent::where('client_record_id', $clientRecordId)
                              ->where('id', $dependentId)
                              ->first();

        if (!$dependent) {
            return response()->json(['message' => 'Dependent not found.'], 404);
        }

        // Delete the dependent
        $dependent->delete();

        return response()->json(['message' => 'Dependent deleted successfully.'], 200);
    } catch (Exception $e) {
        // Log error and return response
        return response()->json(['message' => 'Error deleting dependent', 'error' => $e->getMessage()], 500);
    }
}



public function deleteClientRecord($caseId)
{
    try{
        $clientRecord = ClientRecord::where('case_id', $caseId)->firstOrFail();
        $clientRecord->delete();
        return response()->json(['message' => 'Client Record deleted successfully.'], 200);
    }catch (Exception $e) {
        // Log error and return response
        return response()->json(['message' => 'Error saving record', 'error' => $e->getMessage()], 500);
    }

}

}
