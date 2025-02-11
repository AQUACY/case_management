<?php

namespace App\Http\Controllers;

use App\Models\Award;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AwardController extends Controller
{
    public function index($caseId)
    {
        try {
            $awards = Award::where('case_id', $caseId)->get();

            return response()->json([
                'success' => true,
                'data' => $awards
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving awards',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'case_id' => 'required|exists:cases,id',
                'award_name' => 'required|string|max:255',
                'award_recipient' => 'required|string|max:255',
                'awarding_institution' => 'required|string|max:255',
                'award_criteria' => 'required|string',
                'award_significance' => 'required|string',
                'number_of_recipients' => 'required|integer|min:1',
                'competitor_limitations' => 'required|string'
            ]);

            $award = Award::create($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Award created successfully',
                'data' => $award
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating award',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $award = Award::findOrFail($id);

            $validatedData = $request->validate([
                'award_name' => 'sometimes|string|max:255',
                'award_recipient' => 'sometimes|string|max:255',
                'awarding_institution' => 'sometimes|string|max:255',
                'award_criteria' => 'sometimes|string',
                'award_significance' => 'sometimes|string',
                'number_of_recipients' => 'sometimes|integer|min:1',
                'competitor_limitations' => 'sometimes|string'
            ]);

            $award->update($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Award updated successfully',
                'data' => $award
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Award not found'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating award',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $award = Award::findOrFail($id);
            $award->delete();

            return response()->json([
                'success' => true,
                'message' => 'Award deleted successfully'
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Award not found'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting award',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
