<?php

namespace App\Http\Controllers;

use App\Models\PersonalStatement;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PersonalStatementController extends Controller
{
    public function show($caseId)
    {
        try {
            $statement = PersonalStatement::where('case_id', $caseId)->firstOrFail();

            return response()->json([
                'success' => true,
                'data' => $statement
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Personal statement not found'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving personal statement',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'case_id' => 'required|exists:cases,id',
                'personal_name' => 'required|string|max:255',
                'proposed_endeavor' => 'required|string',
                'background_information' => 'required|string',
                'future_intentions' => 'required|string',
                'future_research' => 'nullable|string'
            ]);

            $statement = PersonalStatement::create($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Personal statement created successfully',
                'data' => $statement
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating personal statement',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $caseId)
    {
        try {
            $statement = PersonalStatement::where('case_id', $caseId)->firstOrFail();

            $validatedData = $request->validate([
                'personal_name' => 'sometimes|string|max:255',
                'proposed_endeavor' => 'sometimes|string',
                'background_information' => 'sometimes|string',
                'future_intentions' => 'sometimes|string',
                'future_research' => 'nullable|string'
            ]);

            $statement->update($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Personal statement updated successfully',
                'data' => $statement
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Personal statement not found'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating personal statement',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($caseId)
    {
        try {
            $statement = PersonalStatement::where('case_id', $caseId)->firstOrFail();
            $statement->delete();

            return response()->json([
                'success' => true,
                'message' => 'Personal statement deleted successfully'
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Personal statement not found'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting personal statement',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
