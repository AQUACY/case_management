<?php

namespace App\Http\Controllers;

use App\Models\CaseWorkExperience;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CaseWorkExperienceController extends Controller
{
    public function index($caseId)
    {
        try {
            $experiences = CaseWorkExperience::where('case_id', $caseId)
                ->orderBy('start_date', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $experiences
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving work experiences',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'case_id' => 'required|exists:cases,id',
                'employer_name' => 'required|string|max:255',
                'address_1' => 'required|string|max:255',
                'address_2' => 'nullable|string|max:255',
                'city' => 'required|string|max:255',
                'state' => 'required|string|max:255',
                'country' => 'required|string|max:255',
                'postal_code' => 'required|string|max:20',
                'business_type' => 'required|string|max:255',
                'job_title' => 'required|string|max:255',
                'start_date' => 'required|date',
                'end_date' => 'nullable|date|after:start_date',
                'hours_worked' => 'required|integer|min:1',
                'job_details' => 'required|string'
            ]);

            $experience = CaseWorkExperience::create($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Work experience added successfully',
                'data' => $experience
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error saving work experience',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $experience = CaseWorkExperience::findOrFail($id);

            $validatedData = $request->validate([
                'employer_name' => 'sometimes|string|max:255',
                'address_1' => 'sometimes|string|max:255',
                'address_2' => 'nullable|string|max:255',
                'city' => 'sometimes|string|max:255',
                'state' => 'sometimes|string|max:255',
                'country' => 'sometimes|string|max:255',
                'postal_code' => 'sometimes|string|max:20',
                'business_type' => 'sometimes|string|max:255',
                'job_title' => 'sometimes|string|max:255',
                'start_date' => 'sometimes|date',
                'end_date' => 'nullable|date|after:start_date',
                'hours_worked' => 'sometimes|integer|min:1',
                'job_details' => 'sometimes|string'
            ]);

            $experience->update($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Work experience updated successfully',
                'data' => $experience
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Work experience not found'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating work experience',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $experience = CaseWorkExperience::findOrFail($id);
            $experience->delete();

            return response()->json([
                'success' => true,
                'message' => 'Work experience deleted successfully'
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Work experience not found'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting work experience',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
