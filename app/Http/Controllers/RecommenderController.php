<?php

namespace App\Http\Controllers;

use App\Models\Recommender;
use Illuminate\Http\Request;
use App\Models\Cases;

class RecommenderController extends Controller
{

    // add recommenders in bulk
    public function addBulkRecommenders(Request $request, $caseId)
{
    // Validate the input
    $request->validate([
        'recommenders' => 'required|array',
        'recommenders.*.name' => 'required|string|max:255',
        'recommenders.*.dependent_or_independent' => 'required|string|in:dependent,independent',
        'recommenders.*.title' => 'required|string|max:255',
        'recommenders.*.institution' => 'required|string|max:255',
        'recommenders.*.country' => 'required|string|max:255',
        'recommenders.*.biography_link' => 'nullable|url',
        'recommenders.*.scholar_profile_link' => 'nullable|url',
        'recommenders.*.relation' => 'nullable|string',
        'recommenders.*.discuss_projects' => 'nullable|string',
        'recommenders.*.has_cited_project' => 'required|boolean',
        'recommenders.*.cited_project_details' => 'nullable|array',
        'recommenders.*.status' => 'required|string|in:finalized,pending',
    ]);

    $recommendersData = $request->input('recommenders');

    // Validate if the case exists
    $case = Cases::findOrFail($caseId);

    // Create recommenders in bulk
    $recommenders = [];
    foreach ($recommendersData as $recommender) {
        $recommenders[] = [
            'case_id' => $caseId,
            'name' => $recommender['name'],
            'dependent_or_independent' => $recommender['dependent_or_independent'],
            'title' => $recommender['title'],
            'institution' => $recommender['institution'],
            'country' => $recommender['country'],
            'biography_link' => $recommender['biography_link'],
            'scholar_profile_link' => $recommender['scholar_profile_link'],
            'relation' => $recommender['relation'],
            'discuss_projects' => $recommender['discuss_projects'],
            'has_cited_project' => $recommender['has_cited_project'],
            'cited_project_details' => json_encode($recommender['cited_project_details']), // Convert to JSON
            'status' => $recommender['status'],
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    // Bulk insert
    Recommender::insert($recommenders);

    return response()->json([
        'message' => 'Recommenders added successfully.',
        'data' => $recommenders,
    ], 201);
}

    // Store a new recommender for a case
    public function store(Request $request, $caseId)
    {
        // Validate request data
        $request->validate([
            'name' => 'required|string|max:255',
            'dependent_or_independent' => 'nullable|in:dependent,independent',
            'title' => 'nullable|string|max:255',
            'institution' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'faculty_biography_link' => 'nullable|url',
            'google_scholar_link' => 'nullable|url',
            'relationship' => 'nullable|string',
            'projects_discussed' => 'nullable|string',
            'cited_project' => 'nullable|boolean',
            'cited_project_details' => 'nullable|string',
            'status' => 'nullable|in:pending,finalized',
        ]);

        // Create a new recommender
        $recommender = Recommender::create([
            'case_id' => $caseId,
            'name' => $request->name,
            'dependent_or_independent' => $request->dependent_or_independent,
            'title' => $request->title,
            'institution' => $request->institution,
            'country' => $request->country,
            'faculty_biography_link' => $request->faculty_biography_link,
            'google_scholar_link' => $request->google_scholar_link,
            'relationship' => $request->relationship,
            'projects_discussed' => $request->projects_discussed,
            'cited_project' => $request->cited_project,
            'cited_project_details' => $request->cited_project_details,
            'status' => $request->status ?? 'pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Recommender added successfully',
            'data' => $recommender,
        ], 201);
    }

    // Update recommender details
    public function update(Request $request, $id)
    {
        // Find the recommender
        $recommender = Recommender::findOrFail($id);

        // Validate request data
        $request->validate([
            'name' => 'nullable|string|max:255',
            'dependent_or_independent' => 'nullable|in:dependent,independent',
            'title' => 'nullable|string|max:255',
            'institution' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'faculty_biography_link' => 'nullable|url',
            'google_scholar_link' => 'nullable|url',
            'relationship' => 'nullable|string',
            'projects_discussed' => 'nullable|string',
            'cited_project' => 'nullable|boolean',
            'cited_project_details' => 'nullable|string',
            'status' => 'nullable|in:pending,finalized',
        ]);

        // Update recommender details
        $recommender->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Recommender updated successfully',
            'data' => $recommender,
        ]);
    }

    // List recommenders for a case
    public function index($caseId)
    {
        $recommenders = Recommender::where('case_id', $caseId)->get();

        return response()->json([
            'success' => true,
            'data' => $recommenders,
        ]);
    }
}
