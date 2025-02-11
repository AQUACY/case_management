<?php

namespace App\Http\Controllers;

use App\Models\MediaCoverage;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class MediaCoverageController extends Controller
{
    public function index($caseId)
    {
        try {
            $coverages = MediaCoverage::where('case_id', $caseId)
                ->orderBy('date_published', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $coverages
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving media coverages',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'case_id' => 'required|exists:cases,id',
                'media_name' => 'required|string|max:255',
                'date_published' => 'required|date',
                'author' => 'required|string|max:255',
                'outlet_name' => 'required|string|max:255',
                'circulation_count' => 'required|integer|min:0',
                'article_summary' => 'required|string',
                'work_relevance' => 'required|string'
            ]);

            $coverage = MediaCoverage::create($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Media coverage created successfully',
                'data' => $coverage
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating media coverage',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $coverage = MediaCoverage::findOrFail($id);

            $validatedData = $request->validate([
                'media_name' => 'sometimes|string|max:255',
                'date_published' => 'sometimes|date',
                'author' => 'sometimes|string|max:255',
                'outlet_name' => 'sometimes|string|max:255',
                'circulation_count' => 'sometimes|integer|min:0',
                'article_summary' => 'sometimes|string',
                'work_relevance' => 'sometimes|string'
            ]);

            $coverage->update($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Media coverage updated successfully',
                'data' => $coverage
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Media coverage not found'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating media coverage',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $coverage = MediaCoverage::findOrFail($id);
            $coverage->delete();

            return response()->json([
                'success' => true,
                'message' => 'Media coverage deleted successfully'
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Media coverage not found'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting media coverage',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
