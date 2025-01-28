<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Exception;
use App\Models\ProjectReviewComment;
use App\Mail\ProjectReviewMail;
use Illuminate\Support\Facades\Mail;
use App\Models\Cases;

class ProjectController extends Controller
{
    public function index()
    {
        try{
        $achievements = Project::all();
        return response()->json($achievements);
    } catch (Exception $e) {
        // Handle any errors
        return response()->json([
            'message' => 'Error viewing records',
            'error' => $e->getMessage()
        ], 500);
    }
    }
    public function show($caseId)
    {
        try {
            $projects = Project::where('case_id', $caseId)->get();

            if ($projects->isEmpty()) {
                return response()->json(['message' => 'No records found'], 404);
            }

            return response()->json($projects);
        } catch (Exception $e) {
            // Log error and return response
            return response()->json(['message' => 'Error showing records', 'error' => $e->getMessage()], 500);
        }
    }

    // Store or update a project
    public function store(Request $request, $caseId)
{
    try{
    $data = $request->validate([
        'id' => 'nullable|integer|exists:projects,id',
        'title_of_project' => 'required|string',
        'date_of_initiation_from' => 'required|date',
        'date_of_initiation_to' => 'required|date',
        'resulting_publications_1' => 'nullable|string',
        'resulting_publications_2' => 'nullable|string',
        'resulting_publications_3' => 'nullable|string',
        'funding_sources_1' => 'nullable|string',
        'funding_sources_2' => 'nullable|string',
        'funding_sources_3' => 'nullable|string',
        'summary_of_work' => 'required|string',
        'niw_project_description' => 'nullable|string',
        'alignment_with_section_i' => 'required|in:yes,no',
        'citation_1' => 'nullable|string',
        'citation_2' => 'nullable|string',
        'citation_3' => 'nullable|string',
        'citation_4' => 'nullable|string',
    ]);

    if (isset($data['id'])) {
        // Update the existing project
        $project = Project::findOrFail($data['id']);
        $project->update($data);
    } else {
        // Create a new project
        $data['case_id'] = $caseId;
        $project = Project::create($data);
    }

    return response()->json(['message' => 'Contribution saved successfully', 'data' => $project]);
}catch (Exception $e) {
    // Log error and return response
    return response()->json(['message' => 'Error showing record', 'error' => $e->getMessage()], 500);
}
}


    // Delete a project by ID
    public function destroy($id)
    {
        try {
            $project = Project::findOrFail($id);
            $project->delete();

            return response()->json(['message' => 'Project deleted successfully']);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error deleting project',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function requestReview($caseId)
    {
        try {
            $project = Project::where('case_id', $caseId)->first();

            if (!$project) {
                return response()->json(['message' => 'Project not found'], 404);
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
            $project->status = 'review';
            $project->save();

            // Send email notification
            Mail::to($caseManager->email)->send(new ProjectReviewMail(
                $project,
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

            $record = Project::where('case_id', $caseId)->first();

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
                ProjectReviewComment::create([
                    'project_id' => $record->id,
                    'comment' => $validatedData['comment'],
                    'status' => $validatedData['response'],
                    'commented_by' => $caseManager->id,
                ]);
            }

            // Update status based on response
            $record->status = $validatedData['response'];
            $record->save();

            // Send email notification to the assigned user
            Mail::to($assignedUser->email)->send(new ProjectReviewMail(
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
            $record = Project::where('case_id', $caseId)->first();

            if (!$record) {
                return response()->json(['message' => 'Record not found'], 404);
            }

            $comments = ProjectReviewComment::where('project_id', $record->id)
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
