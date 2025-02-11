<?php

namespace App\Http\Controllers;

use App\Models\CaseQuestionnaire;
use App\Models\Cases;
use Illuminate\Http\Request;
use App\Mail\ReviewRequestMail;
use Illuminate\Support\Facades\Mail;
use Exception;
use ModelNotFoundException;
use App\Models\FamilyMember;
use App\Mail\ReviewApprovedMail;
use App\Mail\ReviewPendingMail;
use Illuminate\Support\Facades\Log;



class CaseQuestionnaireController extends Controller
{

    public function index()
    {
        try {
            // Retrieve all case questionnaires with their family members
            $caseQuestionnaires = CaseQuestionnaire::with(['familyMembers', 'case'])->get();

            return response()->json([
                'success' => true,
                'data' => $caseQuestionnaires
            ]);

        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error retrieving case questionnaires',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    // get questionnaire
    public function view($caseId)
{
    try {
        // Retrieve the case questionnaire by case_id
        $caseQuestionnaire = CaseQuestionnaire::where('case_id', $caseId)->with('familyMembers')->first();

        // Check if the questionnaire exists
        if (!$caseQuestionnaire) {
            return response()->json([
                'message' => 'Case Questionnaire not found'
            ], 404);
        }

        // Return the case questionnaire along with its family members
        return response()->json([
            'success' => true,
            'data' => $caseQuestionnaire
        ]);
    } catch (Exception $e) {
        // Handle any errors
        return response()->json([
            'message' => 'Error fetching Case Questionnaire',
            'error' => $e->getMessage()
        ], 500);
    }
}

public function store(Request $request, $caseId)
{
    try {
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'case_id' => 'required|exists:cases,id',

            // basic information
            'petition_type' => 'nullable|string|max:255',
            'petitioner' => 'nullable|string|max:255',

            // personal information
            'family_name' => 'nullable|string|max:255',
            'given_name' => 'nullable|string|max:255',
            'full_middle_name' => 'nullable|string|max:255',
            'native_alphabet' => 'nullable|string|max:255',
            'dob' => 'nullable|date',
            'city_town_village_of_birth' => 'nullable|string|max:255',
            'state_of_birth' => 'nullable|string|max:255',
            'country_of_birth' => 'nullable|string|max:255',
            'nationality' => 'nullable|string|max:255',
            'alien_registration_number' => 'nullable|string|max:255',
            'ssn' => 'nullable|string|max:255',

            // mail address
            'street_number_name' => 'nullable|string|max:255',
            'type' => 'nullable|string|max:255',
            'type_detail' => 'nullable|string|max:255',
            'city_town' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'zip_code' => 'nullable|string|max:255',
            'province' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'mobile_telephone' => 'nullable|string|max:255',
            'email_address' => 'nullable|email|max:255',

            // information about last arrival
            'last_arrival_date' => 'nullable|date',
            'i_94_arrival_record_number' => 'nullable|string|max:255',
            'expiration_date' => 'nullable|date',
            'status_on_form_i_94' => 'nullable|string|max:255',
            'passport_number' => 'nullable|string|max:255',
            'travel_document_number' => 'nullable|string|max:255',
            'country_of_issuance' => 'nullable|string|max:255',
            'expiration_date_for_passport' => 'nullable|date',

            // visa processing
            'alien_will_apply_for_visa_abroad' => 'nullable|boolean',
            'visa_processing_city_town' => 'nullable|string|max:255',
            'visa_processing_country' => 'nullable|string|max:255',
            'alien_in_us' => 'nullable|boolean',
            'if_now_in_the_us' => 'nullable|string|max:255',
            'foreign_street_number_name' => 'nullable|string|max:255',
            'foreign_address_type' => 'nullable|string|max:255',
            'foreign_city_town' => 'nullable|string|max:255',
            'foreign_state_province' => 'nullable|string|max:255',
            'foreign_postal_code' => 'nullable|string|max:255',
            'foreign_country' => 'nullable|string|max:255',

            // employment information
            'current_employer_name' => 'nullable|string|max:255',
            'job_title' => 'nullable|string|max:255',
            'full_time_position' => 'nullable|boolean',
            'permanent_position' => 'nullable|boolean',
            'occupation' => 'nullable|string|max:255',
            'annual_income' => 'nullable|numeric',
            'soc_code' => 'nullable|string|max:255',
            'nontechnical_job_description' => 'nullable|string',
            'hours_per_week' => 'nullable|integer',
            'new_position' => 'nullable|boolean',
            'wages' => 'nullable|numeric',
            'wages_per' => 'nullable|string|max:255',
            'worksite_type' => 'nullable|string|max:255',
            'worksite_street_number_name' => 'nullable|string|max:255',
            'work_building_type' => 'nullable|string|max:255',
            'work_site_additional_details' => 'nullable|string',
            'work_city_town' => 'nullable|string|max:255',
            'work_state' => 'nullable|string|max:255',
            'work_county_township' => 'nullable|string|max:255',
            'work_zip_code' => 'nullable|string|max:255',

            // Updated Family member validation rules
            'family_members' => 'nullable|array',
            'family_members.*.id' => 'nullable|exists:family_members,id',
            'family_members.*.family_name_last_name' => 'required_with:family_members|string|max:255',
            'family_members.*.given_name_first_name' => 'required_with:family_members|string|max:255',
            'family_members.*.relationship' => 'required_with:family_members|string|in:Spouse,Child',
            'family_members.*.dob' => 'required_with:family_members|date',
            'family_members.*.birth_country' => 'required_with:family_members|string|max:255'
        ]);

        // Define boolean fields
        $booleanFields = [
            'alien_will_apply_for_visa_abroad',
            'alien_in_us',
            'full_time_position',
            'permanent_position',
            'new_position'
        ];

        // Convert boolean values explicitly
        foreach ($booleanFields as $field) {
            if (isset($validatedData[$field])) {
                $validatedData[$field] = filter_var($validatedData[$field], FILTER_VALIDATE_BOOLEAN);
            }
        }

        $caseQuestionnaire = CaseQuestionnaire::where('case_id', $caseId)->first();

        if ($caseQuestionnaire) {
            // Update existing case questionnaire
            $updateData = $request->except('family_members');

            // Convert boolean values in update data
            foreach ($booleanFields as $field) {
                if (isset($updateData[$field])) {
                    $updateData[$field] = filter_var($updateData[$field], FILTER_VALIDATE_BOOLEAN);
                }
            }

            $caseQuestionnaire->update($updateData);

            // Update or create family members
            if ($request->has('family_members')) {
                foreach ($request->family_members as $familyMemberData) {
                    // Updated field names in the creation array
                    $newFamilyMember = [
                        'case_questionnaire_id' => $caseQuestionnaire->id,
                        'family_name_last_name' => $familyMemberData['family_name_last_name'] ?? null,
                        'given_name_first_name' => $familyMemberData['given_name_first_name'] ?? null,
                        'relationship' => $familyMemberData['relationship'] ?? null,
                        'dob' => $familyMemberData['dob'] ?? null,
                        'birth_country' => $familyMemberData['birth_country'] ?? null
                    ];

                    if (isset($familyMemberData['id'])) {
                        $familyMember = $caseQuestionnaire->familyMembers()->find($familyMemberData['id']);
                        if ($familyMember) {
                            $familyMember->update($newFamilyMember);
                        }
                    } else {
                        $caseQuestionnaire->familyMembers()->create($newFamilyMember);
                    }
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Case Questionnaire updated successfully',
                'data' => $caseQuestionnaire->load('familyMembers')
            ]);
        } else {
            // Set the status to 'pending'
            $validatedData['status'] = 'pending';

            // Create new case questionnaire
            $caseQuestionnaire = CaseQuestionnaire::create($validatedData);

            // Add family members if provided
            if ($request->has('family_members')) {
                foreach ($request->family_members as $familyMemberData) {
                    $caseQuestionnaire->familyMembers()->create($familyMemberData);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Case Questionnaire created successfully',
                'data' => $caseQuestionnaire->load('familyMembers')
            ]);
        }
    } catch (Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error updating Case Questionnaire',
            'error' => $e->getMessage()
        ], 500);
    }
}

    public function show($id)
    {
        $caseQuestionnaire = CaseQuestionnaire::with('case')->findOrFail($id);
        return response()->json($caseQuestionnaire);
    }

    // request review function
    public function requestReview($id)
    {
        $caseQuestionnaire = CaseQuestionnaire::with('case.caseManager')->findOrFail($id);

        if (!$caseQuestionnaire->case || !$caseQuestionnaire->case->caseManager) {
            return response()->json([
                'message' => 'Case or Case Manager not found.'
            ], 404);
        }

        $caseQuestionnaire->status = 'review_requested';
        $caseQuestionnaire->save();

        $caseManagerEmail = $caseQuestionnaire->case->caseManager->email;

        Mail::to($caseManagerEmail)->send(new ReviewRequestMail($caseQuestionnaire));

        return response()->json([
            'message' => 'Review request has been sent to the case manager.'
        ], 200);
    }

    // respond to review
    public function respondToReview(Request $request, $caseId)
    {
        try {
            $validatedData = $request->validate([
                'response' => 'required|in:approved,pending',
            ]);

            $caseQuestionnaire = CaseQuestionnaire::where('case_id', $caseId)->first();

            if (!$caseQuestionnaire) {
                return response()->json([
                    'message' => 'Case Questionnaire not found',
                    'error' => 'Case Questionnaire not found'
                ], 404);
            }

            $caseQuestionnaire->status = $validatedData['response'];
            $caseQuestionnaire->save();

            return response()->json([
                'message' => 'Review response has been saved.',
            ], 200);
            // Get the case and assigned user
            if (!$case || !$case->user) {
                return response()->json(['message' => 'Case or assigned user not found'], 404);
            }

            // Send appropriate email based on response
            if ($validatedData['response'] === 'approved') {
                // Get user name and order number for the email
                $emailData = [
                    'client_name' => $case->user->name,
                    'case_id' => $case->order_number
                ];
                Mail::to($case->user->email)->send(new ReviewApprovedMail($emailData));
                Log::info('Sending approval email to: ' . $case->user->email);
            } else {
                $emailData = [
                    'client_name' => $case->user->name,
                    'case_id' => $case->order_number
                ];
                Mail::to($case->user->email)->send(new ReviewPendingMail($emailData));
                Log::info('Sending pending email to: ' . $case->user->email);
            }

        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error validating request',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    // delete casequestionnaire
    public function deleteCaseQuestionnaireByCaseId($caseId)
    {
        try {
            // Find the CaseQuestionnaire by case_id
            $caseQuestionnaire = CaseQuestionnaire::where('case_id', $caseId)->firstOrFail();

            // Delete the CaseQuestionnaire
            $caseQuestionnaire->delete();

            // Return a success response
            return response()->json([
                'message' => 'CaseQuestionnaire deleted successfully.'
            ], 200);
        } catch (Exception $e) {
            // Return a not found response
            return response()->json([
                'message' => 'CaseQuestionnaire not found.'
            ], 404);
        } catch (Exception $e) {
            // Return a general error response
            return response()->json([
                'message' => 'Error deleting CaseQuestionnaire',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function deleteFamilyMember($caseQuestionnaireId, $familyMemberId)
    {
        try {
            // Find the family member by ID and check if it belongs to the given caseQuestionnaireId
            $familyMember = FamilyMember::where('id', $familyMemberId)
                ->where('case_questionnaire_id', $caseQuestionnaireId)
                ->firstOrFail();

            // Delete the family member
            $familyMember->delete();

            // Return a success response
            return response()->json([
                'message' => 'Family member deleted successfully.'
            ], 200);
        } catch (Exception $e) {
            // Return a not found response
            return response()->json([
                'message' => 'Family member not found or does not belong to the specified case questionnaire.'
            ], 404);
        } catch (Exception $e) {
            // Return a general error response
            return response()->json([
                'message' => 'Error deleting family member',
                'error' => $e->getMessage()
            ], 500);
        }
    }



}
