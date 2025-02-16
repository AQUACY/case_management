<?php

namespace App\Http\Controllers;

use App\Models\SpeakingEngagement;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class SpeakingEngagementController extends Controller
{
    public function index($caseId)
    {
        try {
            $engagements = SpeakingEngagement::where('case_id', $caseId)
                ->orderBy('engagement_date', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $engagements
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving speaking engagements',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'case_id' => 'required|exists:cases,id',
                'conference_name' => 'required|string|max:255',
                'engagement_date' => 'required|date',
                'details' => 'required|string'
            ]);

            $engagement = SpeakingEngagement::create($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Speaking engagement created successfully',
                'data' => $engagement
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating speaking engagement',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $engagement = SpeakingEngagement::findOrFail($id);

            $validatedData = $request->validate([
                'conference_name' => 'sometimes|string|max:255',
                'engagement_date' => 'sometimes|date',
                'details' => 'sometimes|string'
            ]);

            $engagement->update($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Speaking engagement updated successfully',
                'data' => $engagement
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Speaking engagement not found'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating speaking engagement',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $engagement = SpeakingEngagement::findOrFail($id);
            $engagement->delete();

            return response()->json([
                'success' => true,
                'message' => 'Speaking engagement deleted successfully'
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Speaking engagement not found'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting speaking engagement',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
