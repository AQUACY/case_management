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
            'petition_type' => 'nullable|in:EB-1A,EB-1B,EB-2 NIW',
            'petitioner' => 'nullable|in:Employer,Self',
            'family_name' => 'nullable|string|max:255',
            'given_name' => 'nullable|string|max:255',
            'full_middle_name' => 'nullable|string|max:255',
            'uses_roman_alphabet' => 'nullable|boolean',
            'street_number_name' => 'nullable|string|max:255',
            'street_type' => 'nullable|in:None,Apt,Ste,Flr',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'zip_code' => 'nullable|string|max:10',
            'province' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'dob' => 'nullable|date',
            'birth_city' => 'nullable|string|max:255',
            'birth_state' => 'nullable|string|max:255',
            'birth_country' => 'nullable|string|max:255',
            'citizenship_country' => 'nullable|string|max:255',
            'dual_citizenship' => 'nullable|in:Yes,No',
            'secondary_country' => 'nullable|string|max:255',
            'social_security_number' => 'nullable|string|max:15',
            'alien_registration_number' => 'nullable|string|max:15',
            'arrival_date' => 'nullable|date',
            'i94_record_number' => 'nullable|string|max:20',
            'passport_number' => 'nullable|string|max:20',
            'passport_country' => 'nullable|string|max:255',
            'passport_expiration_date' => 'nullable|date',
            'admission_class' => 'nullable|string|max:10',
            'admit_until_date' => 'nullable|date',
            'occupation' => 'nullable|string|max:255',
            'annual_income' => 'nullable|numeric|min:0',
            'job_title' => 'nullable|string|max:255',
            'soc_code' => 'nullable|string|max:10',
            'job_description' => 'nullable|string',
            'is_full_time' => 'nullable|boolean',
            'hours_per_week' => 'nullable|integer|min:1|max:168',
            'is_permanent_position' => 'nullable|boolean',
            'is_new_position' => 'nullable|boolean',
            'wages' => 'nullable|numeric|min:0',
            'pay_period' => 'nullable|in:hour,week,month,year',
            'worksite_location_type' => 'nullable|in:Business premises,Employer\'s private household,Own private residence,Multiple locations',
            'worksite_street' => 'nullable|string|max:255',
            'worksite_street_type' => 'nullable|in:None,Apt,Ste,Flr',
            'worksite_city' => 'nullable|string|max:255',
            'worksite_state' => 'nullable|string|max:255',
            'worksite_county' => 'nullable|string|max:255',
            'worksite_zip_code' => 'nullable|string|max:10',
            'apply_for_immigrant_visa' => 'nullable|boolean',
            'processing_country' => 'nullable|string|max:255',
            'processing_city' => 'nullable|string|max:255',
            'file_i485' => 'nullable|boolean',
            'current_residence_country' => 'nullable|string|max:255',
            'foreign_street' => 'nullable|string|max:255',
            'foreign_street_type' => 'nullable|in:None,Apt,Ste,Flr',
            'foreign_city' => 'nullable|string|max:255',
            'foreign_state' => 'nullable|string|max:255',
            'foreign_province' => 'nullable|string|max:255',
            'foreign_postal_code' => 'nullable|string|max:10',
            'foreign_country' => 'nullable|string|max:255',
            'simultaneous_petitions' => 'nullable|boolean',
            'simultaneous_petitions_details' => 'nullable|string',
            'previous_visa_petitions' => 'nullable|boolean',
            'previous_visa_petitions_details' => 'nullable|string',
            'in_removal_proceedings' => 'nullable|boolean',
            'removal_proceedings_details' => 'nullable|string',
            'daytime_phone' => 'nullable|string|max:15',
            'mobile_phone' => 'nullable|string|max:15',
            'email' => 'nullable|email|max:255',
             // Family rules
             'family_members.*.id' => 'nullable|exists:family_members,id',
             'family_members.*.family_name' => 'required|string|max:255',
             'family_members.*.given_name' => 'required|string|max:255',
             'family_members.*.relationship' => 'required|in:Spouse,Child',
             'family_members.*.dob' => 'required|date',
             'family_members.*.birth_country' => 'required|string|max:255',
        ]);

        $caseQuestionnaire = CaseQuestionnaire::where('case_id', $caseId)->first();

        if ($caseQuestionnaire) {
            // Update existing case questionnaire
            $caseQuestionnaire->update($request->except('family_members'));

            // Update or create family members
            if ($request->has('family_members')) {
                foreach ($request->family_members as $familyMemberData) {
                    if (isset($familyMemberData['id'])) {
                        // Update existing family member
                        $familyMember = $caseQuestionnaire->familyMembers()->find($familyMemberData['id']);
                        if ($familyMember) {
                            $familyMember->update($familyMemberData);
                        }
                    } else {
                        // Create new family member
                        $caseQuestionnaire->familyMembers()->create($familyMemberData);
                    }
                }
            }

            return response()->json([
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
                'message' => 'Case Questionnaire created successfully',
                'data' => $caseQuestionnaire->load('familyMembers')
            ]);
        }
    } catch (Exception $e) {
        // Handle any errors
        return response()->json([
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
