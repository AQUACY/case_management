<?php

namespace App\Http\Controllers;

use App\Models\CaseQuestionnaire;
use App\Models\Cases;
use Illuminate\Http\Request;
use Log;

class CaseQuestionnaireController extends Controller
{
    public function store(Request $request, $caseId)
    {
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
            'dual_citizenship' => 'nullable|boolean',
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
             'family_members.*.family_name' => 'required|string|max:255',
             'family_members.*.given_name' => 'required|string|max:255',
             'family_members.*.relationship' => 'required|in:Spouse,Child',
             'family_members.*.dob' => 'required|date',
             'family_members.*.birth_country' => 'required|string|max:255',
        ]);

        $case = Cases::findOrFail($caseId);
        // $caseQuestionnaire = $case->questionnaire()->updateOrCreate($validatedData);

        $caseQuestionnaire = CaseQuestionnaire::where('case_id', $caseId)->first();

    // If it exists, update it, otherwise create a new one
    if ($caseQuestionnaire) {
        // Update or create the case questionnaire data excluding family_members
        $caseQuestionnaire->update($request->except('family_members'));

        // Add family members if provided
        if ($request->has('family_members')) {
            foreach ($request->family_members as $familyMemberData) {
                $caseQuestionnaire->familyMembers()->create($familyMemberData);
            }
        }

        // Return the response with case questionnaire and family members
        return response()->json([
            'message' => 'Case Questionnaire updated successfully',
            'data' => $caseQuestionnaire->load('familyMembers')  // Load family members
        ]);
    } else {
        // If no record exists, create a new one
        $caseQuestionnaire = CaseQuestionnaire::create($validatedData);

        // Add family members if provided
        if ($request->has('family_members')) {
            foreach ($request->family_members as $familyMemberData) {
                $caseQuestionnaire->familyMembers()->create($familyMemberData);
            }
        }

        // Return the response with case questionnaire and family members
        return response()->json([
            'message' => 'Case Questionnaire created successfully',
            'data' => $caseQuestionnaire->load('familyMembers')  // Load family members
        ]);
    }
    }

    public function show($id)
    {
        $caseQuestionnaire = CaseQuestionnaire::with('case')->findOrFail($id);
        return response()->json($caseQuestionnaire);
    }
}
