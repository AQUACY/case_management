<?php

namespace App\Http\Controllers;

use App\Models\CaseEducation;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CaseEducationController extends Controller
{
    public function index($caseId)
    {
        try {
            $education = CaseEducation::where('case_id', $caseId)
                ->orderBy('completion_year', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $education
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving education records',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'case_id' => 'required|exists:cases,id',
                'university_name' => 'required|string|max:255',
                'completion_year' => 'required|digits:4|integer|min:1900|max:'.(date('Y')+10),
            ]);

            $education = CaseEducation::create($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Education record added successfully',
                'data' => $education
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error saving education record',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $education = CaseEducation::findOrFail($id);

            $validatedData = $request->validate([
                'university_name' => 'sometimes|string|max:255',
                'completion_year' => 'sometimes|digits:4|integer|min:1900|max:'.(date('Y')+10),
            ]);

            $education->update($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Education record updated successfully',
                'data' => $education
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Education record not found'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating education record',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $education = CaseEducation::findOrFail($id);
            $education->delete();

            return response()->json([
                'success' => true,
                'message' => 'Education record deleted successfully'
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Education record not found'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting education record',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
