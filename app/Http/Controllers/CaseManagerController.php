<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Cases;
use App\Models\User;
use Illuminate\Support\Str;

class CaseManagerController extends Controller
{
    // view all cases
    public function index(Request $request)
    {
        // Define how many cases per page
        $perPage = $request->get('per_page', 10); // Default to 10 cases per page if not specified

        // Fetch paginated cases
        $cases = Cases::with('caseManager', 'user')
            ->paginate($perPage);

        return response()->json([
            'success' => true,
            'message' => 'Cases retrieved successfully.',
            'data' => $cases
        ], 200);
    }


// view case by ID
public function show($id)
{
    // Fetch the case by ID, including the case manager and user
    $case = Cases::with('caseManager', 'user')->find($id);

    // Check if the case exists
    if (!$case) {
        return response()->json([
            'error' => 'Case not found.'
        ], 404);
    }

    return response()->json([
        'success' => true,
        'message' => 'Case retrieved successfully.',
        'data' => $case
    ], 200);
}

// create and assign case
    public function store(Request $request)
    {
        // Validate request data
        $request->validate([
            'bill' => 'required|numeric',
            'case_manager_id' => 'required|exists:users,id',
            'user_id' => 'required|exists:users,id',  // Validate the user for whom the case is being created
            'description' => 'required|string',
        ]);

        // Generate unique order number
        $orderNumber = 'CASE-' . strtoupper(Str::random(8));

        // Create the case
        $case = Cases::create([
            'order_number' => $orderNumber,
            'bill' => $request->bill,
            'case_manager_id' => $request->case_manager_id,
            'user_id' => $request->user_id,  // Assign the user to the case
            'description' => $request->description,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Case created successfully',
            'data' => $case,
        ], 201);
    }


    // // Assign the case to a case manager
    // public function assignCaseManager(Request $request, $caseId)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'manager_id' => 'required|exists:users,id',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json(['error' => $validator->errors()], 422);
    //     }

    //     $case = Cases::findOrFail($caseId);
    //     $case->assigned_to = $request->manager_id;
    //     $case->save();

    //     return response()->json(['success' => 'Case manager assigned successfully']);
    // }

}
