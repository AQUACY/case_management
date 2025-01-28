<?php

namespace App\Http\Controllers;

use App\Models\BackgroundInformation;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReviewPendingMail;
use App\Mail\ReviewSoc;
use App\Models\Cases;
use App\Mail\ReviewApprovedMail;
use App\Models\BackgroundReviewComment;
use Illuminate\Support\Facades\Auth;
use App\Mail\BackgroundReviewMail;

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

public function requestReview($caseId)
{
    try {
        $backgroundInformation = BackgroundInformation::where('case_id', $caseId)->first();

        if (!$backgroundInformation) {
            return response()->json(['message' => 'Background Information not found'], 404);
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

        // Trigger Email
        $backgroundInformation->status = 'pending';
        $backgroundInformation->save();

        Mail::to($caseManager->email)->send(new ReviewSoc($backgroundInformation));

        // Trigger Notification
        // $caseManager->notify(new ReviewRequestNotification($backgroundInformation));

        return response()->json(['message' => 'Review request sent successfully']);
    } catch (Exception $e) {
        // Log the error for debugging
        // Log::error('Error sending review request: ' . $e->getMessage());

        return response()->json(['message' => 'Error sending review request', 'error' => $e->getMessage()], 500);
    }
}

public function respondToReview(Request $request, $caseId)
{
    try {
        $validatedData = $request->validate([
            'response' => 'required|in:approved,pending',
            'comment' => 'required_if:response,pending|string|nullable',
        ]);

        $record = BackgroundInformation::where('case_id', $caseId)->first();

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
            BackgroundReviewComment::create([
                'background_information_id' => $record->id,
                'comment' => $validatedData['comment'],
                'status' => $validatedData['response'],
                'commented_by' => $caseManager->id,
            ]);
        }

        // Update status based on response
        if ($validatedData['response'] === 'approved') {
            $record->status = 'approved';
        } else {
            $record->status = 'pending';
        }
        $record->save();

        // Send email notification to the assigned user
        Mail::to($assignedUser->email)->send(new BackgroundReviewMail(
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

// Add a new method to get review comments history
public function getReviewComments($caseId)
{
    try {
        $record = BackgroundInformation::where('case_id', $caseId)->first();

        if (!$record) {
            return response()->json(['message' => 'Record not found'], 404);
        }

        $comments = BackgroundReviewComment::where('background_information_id', $record->id)
            ->with('commentedBy:id,name') // Assuming you have a relationship set up
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
