<?php

namespace App\Http\Controllers;

use App\Models\Achievement;
use Illuminate\Http\Request;
use Exception;
use App\Models\AchievementReviewComment;
use App\Mail\AchievementReviewMail;
use Illuminate\Support\Facades\Mail;
use App\Models\Cases;

class AchievementController extends Controller
{
    public function index()
    {
        try{
        $achievements = Achievement::all();
        return response()->json($achievements);
    } catch (Exception $e) {
        // Handle any errors
        return response()->json([
            'message' => 'Error viewing records',
            'error' => $e->getMessage()
        ], 500);
    }
    }

    public function store(Request $request, $caseId)
    {
        try{
        $data = $request->validate([
           'nationally_internationally_recognized_awards' => 'required|boolean',
            'award_1_name' => 'required_if:nationally_internationally_recognized_awards,true|string',
            'award_1_recipient' => 'required_if:nationally_internationally_recognized_awards,true|string',
            'award_1_institution' => 'required_if:nationally_internationally_recognized_awards,true|string',
            'who_is_eligible_to_compete_1' => 'required_if:nationally_internationally_recognized_awards,true|string',
            'number_of_competitors_winners_1' => 'required_if:nationally_internationally_recognized_awards,true|integer',
            'selection_criteria_1' => 'required_if:nationally_internationally_recognized_awards,true|string',
            'who_are_judges_1' => 'required_if:nationally_internationally_recognized_awards,true|string',

            'award_2_name' => 'required_if:nationally_internationally_recognized_awards,true|string',
            'award_2_recipient' => 'nullable|string',
            'award_2_institution' => 'nullable|string',
            'who_is_eligible_to_compete_2' => 'nullable|string',
            'number_of_competitors_winners_2' => 'nullable|integer',
            'selection_criteria_2' => 'nullable|string',
            'who_are_judges_2' => 'nullable|string',

            'award_3_name' => 'required_if:nationally_internationally_recognized_awards,true|string',
            'award_3_recipient' => 'nullable|string',
            'award_3_institution' => 'nullable|string',
            'who_is_eligible_to_compete_3' => 'nullable|string',
            'number_of_competitors_winners_3' => 'nullable|integer',
            'selection_criteria_3' => 'nullable|string',
            'who_are_judges_3' => 'nullable|string',
            // Repeat validation for Award 2 and 3...

            'peer_review' => 'required|boolean',
            'name_of_organization_reviewed' => 'required_if:peer_review,true|string',
            'number_of_reviews_completed' => 'required_if:peer_review,true|string',

            'phd_committee' => 'required|boolean',
            'grant_review' => 'required|boolean',

            'leadership_roles' => 'required|boolean',
            'name_of_leadership_roles' => 'required_if:leadership_roles,true|string',
            'name_of_organization_in_leadership' => 'required_if:leadership_roles,true|string',
            'date_of_service' => 'required_if:leadership_roles,true|date',
            'summary_of_organization_reputation' => 'required_if:leadership_roles,true|string',
            'summary_of_role_and_responsibilities' => 'required_if:leadership_roles,true|string',

            'notable_memberships' => 'required|boolean',
            'name_of_organization_in_membership' => 'required_if:notable_memberships,true|string',
            'level_of_membership' => 'required_if:notable_memberships,true|string',
            'requirements_for_membership' => 'required_if:notable_memberships,true|string',
            'who_judges_membership_eligibility' => 'required_if:notable_memberships,true|string',

            'notable_media_coverage' => 'required|boolean',
            'title_of_article' => 'required_if:notable_media_coverage,true|string',
            'date_published' => 'required_if:notable_media_coverage,true|date',
            'author' => 'required_if:notable_media_coverage,true|string',
            'magazine_newspaper_website' => 'required_if:notable_media_coverage,true|string',
            'circulation' => 'required_if:notable_media_coverage,true|string',
            'summary_of_article_focus' => 'required_if:notable_media_coverage,true|string',
            'relevance_to_original_work' => 'required_if:notable_media_coverage,true|string',

            'invitations' => 'required|boolean',
            'conference_title_1' => 'required_if:invitations,true|string',
            'conference_month_year_1' => 'required_if:invitations,true|date',
            'details_of_invitation_1' => 'required_if:invitations,true|string',

            'conference_title_2' => 'nullable|string',
            'conference_month_year_2' => 'nullable|date',
            'details_of_invitation_2' => 'nullable|string',

            'conference_title_3' => 'nullable|string',
            'conference_month_year_3' => 'nullable|date',
            'details_of_invitation_3' => 'nullable|string',
            // Repeat for other conference entries...

            'filing_eb1a' => 'required|boolean',
            'total_combined_salary' => 'required_if:filing_eb1a,true|numeric',
            'field_for_filing' => 'required_if:filing_eb1a,true|string',
            'no_achievement_for_eb1a' => 'required|boolean',
        ]);

        // Check if an achievement already exists for the given case (assuming 'case_id' is a field in the request)
        $achievements = Achievement::updateOrCreate(
            ['case_id' => $caseId],
            $data
        );
        return response()->json(['message' => 'Record saved successfully', 'data' => $achievements]);
} catch (Exception $e) {
    // Handle any errors
    return response()->json([
        'message' => 'Error saving records',
        'error' => $e->getMessage()
    ], 500);
}
    }

    public function show($caseId)
    {
        try {
        $achievement = Achievement::where('case_id', $caseId)->first();

        if (!$achievement) {
            return response()->json(['message' => 'Record not found'], 404);
        }

        return response()->json($achievement);
    }catch (Exception $e) {
        // Log error and return response
        return response()->json(['message' => 'Error showing record', 'error' => $e->getMessage()], 500);
    }
    }

    public function destroy($caseId)
{
    try {
        // Find the achievement by case_id
        $achievement = Achievement::where('case_id', $caseId)->first();

        if (!$achievement) {
            return response()->json(['message' => 'Achievement not found'], 404);
        }

        // Delete the achievement
        $achievement->delete();

        return response()->json(['message' => 'Achievement deleted successfully']);
    } catch (Exception $e) {
        // Handle any errors
        return response()->json([
            'message' => 'Error deleting achievement',
            'error' => $e->getMessage()
        ], 500);
    }
}

public function requestReview($caseId)
{
    try {
        $achievement = Achievement::where('case_id', $caseId)->first();

        if (!$achievement) {
            return response()->json(['message' => 'Achievement not found'], 404);
        }

        // Retrieve the case by ID
        $case = Cases::find($caseId);

        if (!$case) {
            return response()->json(['message' => 'Case not found'], 404);
        }

        // Access the case manager's email through the relationship
        $caseManager = $case->caseManager;

        if (!$caseManager) {
            return response()->json(['message' => 'Case Manager not found'], 404);
        }

        // Update status and save
        $achievement->status = 'review';
        $achievement->save();

        // Send email notification
        Mail::to($caseManager->email)->send(new AchievementReviewMail(
            $achievement,
            'review',
            null
        ));

        return response()->json(['message' => 'Review request sent successfully']);
    } catch (Exception $e) {
        return response()->json([
            'message' => 'Error sending review request',
            'error' => $e->getMessage()
        ], 500);
    }
}

public function respondToReview(Request $request, $caseId)
{
    try {
        $validatedData = $request->validate([
            'response' => 'required|in:approved,pending,review',
            'comment' => 'required_if:response,pending|string|nullable',
        ]);

        $record = Achievement::where('case_id', $caseId)->first();

        if (!$record) {
            return response()->json(['message' => 'Record not found'], 404);
        }

        $case = Cases::find($caseId);

        if (!$case) {
            return response()->json(['message' => 'Case not found'], 404);
        }

        $assignedUser = $case->user;
        $caseManager = $case->caseManager;

        if (!$assignedUser) {
            return response()->json(['message' => 'Assigned user not found'], 404);
        }

        // Store the review comment if provided
        if (isset($validatedData['comment'])) {
            AchievementReviewComment::create([
                'achievement_id' => $record->id,
                'comment' => $validatedData['comment'],
                'status' => $validatedData['response'],
                'commented_by' => $caseManager->id,
            ]);
        }

        // Update status based on response
        $record->status = $validatedData['response'];
        $record->save();

        // Send email notification to the assigned user
        Mail::to($assignedUser->email)->send(new AchievementReviewMail(
            $record,
            $validatedData['response'],
            $validatedData['comment'] ?? null
        ));

        return response()->json([
            'message' => 'Response submitted successfully',
            'status' => $validatedData['response'],
            'comment' => $validatedData['comment'] ?? null
        ]);
    } catch (Exception $e) {
        return response()->json([
            'message' => 'Error submitting response',
            'error' => $e->getMessage()
        ], 500);
    }
}

public function getReviewComments($caseId)
{
    try {
        $record = Achievement::where('case_id', $caseId)->first();

        if (!$record) {
            return response()->json(['message' => 'Record not found'], 404);
        }

        $comments = AchievementReviewComment::where('achievement_id', $record->id)
            ->with('commentedBy:id,name')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'comments' => $comments
        ]);

    } catch (Exception $e) {
        return response()->json([
            'message' => 'Error retrieving comments',
            'error' => $e->getMessage()
        ], 500);
    }
}

}


