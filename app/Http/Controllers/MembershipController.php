<?php

namespace App\Http\Controllers;

use App\Models\Membership;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class MembershipController extends Controller
{
    public function index($caseId)
    {
        try {
            $memberships = Membership::where('case_id', $caseId)->get();

            return response()->json([
                'success' => true,
                'data' => $memberships
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving memberships',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'case_id' => 'required|exists:cases,id',
                'membership_level' => 'required|string|max:255',
                'membership_requirements' => 'required|string',
                'fee_and_subscription_details' => 'required|string'
            ]);

            $membership = Membership::create($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Membership created successfully',
                'data' => $membership
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating membership',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $membership = Membership::findOrFail($id);

            $validatedData = $request->validate([
                'membership_level' => 'sometimes|string|max:255',
                'membership_requirements' => 'sometimes|string',
                'fee_and_subscription_details' => 'sometimes|string'
            ]);

            $membership->update($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Membership updated successfully',
                'data' => $membership
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Membership not found'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating membership',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $membership = Membership::findOrFail($id);
            $membership->delete();

            return response()->json([
                'success' => true,
                'message' => 'Membership deleted successfully'
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Membership not found'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting membership',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
