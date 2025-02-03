<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CaseStatus;
use App\Models\Cases;
use Illuminate\Validation\ValidationException;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CaseStatusController extends Controller
{
    // Get all case statuses for a case
    public function index($caseId)
    {
        try {
            $statuses = CaseStatus::where('case_id', $caseId)
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $statuses
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving case statuses',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Create a new case status
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'case_id' => 'required|exists:cases,id',
                'service_type' => 'required|string|max:255',
                'receipt_number' => 'required|string|max:255|unique:case_statuses',
                'date_of_filing' => 'nullable|date',
                'date_of_decision' => 'nullable|date',
            ]);

            $caseStatus = CaseStatus::create($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Case status added successfully',
                'data' => $caseStatus
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error saving case status',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Show a specific case status
    public function show($id)
    {
        try {
            $caseStatus = CaseStatus::findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $caseStatus
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Case status not found'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving case status',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Update a case status
    public function update(Request $request, $id)
    {
        try {
            $caseStatus = CaseStatus::findOrFail($id);

            $validatedData = $request->validate([
                'service_type' => 'sometimes|string|max:255',
                'receipt_number' => 'sometimes|string|max:255|unique:case_statuses,receipt_number,'.$id,
                'date_of_filing' => 'nullable|date',
                'date_of_decision' => 'nullable|date',
            ]);

            $caseStatus->update($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Case status updated successfully',
                'data' => $caseStatus
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Case status not found'
            ], 404);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating case status',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Delete a case status
    public function destroy($id)
    {
        try {
            $caseStatus = CaseStatus::findOrFail($id);
            $caseStatus->delete();

            return response()->json([
                'success' => true,
                'message' => 'Case status deleted successfully'
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Case status not found'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting case status',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
