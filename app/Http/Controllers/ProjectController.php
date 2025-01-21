<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Exception;

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
}
