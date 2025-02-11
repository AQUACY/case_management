<?php

namespace App\Http\Controllers;

use App\Models\LeadershipRole;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class LeadershipRoleController extends Controller
{
    public function index($caseId)
    {
        try {
            $roles = LeadershipRole::where('case_id', $caseId)
                ->orderBy('service_start_date', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $roles
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving leadership roles',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'case_id' => 'required|exists:cases,id',
                'role_position' => 'required|string|max:255',
                'organization_name' => 'required|string|max:255',
                'service_start_date' => 'required|date',
                'service_end_date' => 'nullable|date|after:service_start_date',
                'organization_prestige' => 'required|string',
                'role_summary' => 'required|string'
            ]);

            $role = LeadershipRole::create($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Leadership role created successfully',
                'data' => $role
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating leadership role',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $role = LeadershipRole::findOrFail($id);

            $validatedData = $request->validate([
                'role_position' => 'sometimes|string|max:255',
                'organization_name' => 'sometimes|string|max:255',
                'service_start_date' => 'sometimes|date',
                'service_end_date' => 'nullable|date|after:service_start_date',
                'organization_prestige' => 'sometimes|string',
                'role_summary' => 'sometimes|string'
            ]);

            $role->update($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Leadership role updated successfully',
                'data' => $role
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Leadership role not found'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating leadership role',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $role = LeadershipRole::findOrFail($id);
            $role->delete();

            return response()->json([
                'success' => true,
                'message' => 'Leadership role deleted successfully'
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Leadership role not found'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting leadership role',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
