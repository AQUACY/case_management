<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Cases;
use App\Models\User;
use Illuminate\Support\Str;

class CaseManagerController extends Controller
{
    <?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Case;
use App\Models\User;
use Illuminate\Support\Str;

class CaseController extends Controller
{

    public function store(Request $request)
    {
        // Validate request data
        $request->validate([
            'bill' => 'required|numeric',
            'case_manager_id' => 'required|exists:users,id',
            'description' => 'required|string',
        ]);

        // Generate unique order number
        $orderNumber = 'CASE-' . strtoupper(Str::random(8));

        // Create the case
        $case = Cases::create([
            'order_number' => $orderNumber,
            'bill' => $request->bill,
            'case_manager_id' => $request->case_manager_id,
            'description' => $request->description,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Case created successfully',
            'data' => $case,
        ], 201);
    }

    public function store(Request $request)
    {
        // Validate request data
        $request->validate([
            'bill' => 'required|numeric',
            'case_manager_id' => 'required|exists:users,id',
            'description' => 'required|string',
        ]);

        // Generate unique order number
        $orderNumber = 'CASE-' . strtoupper(Str::random(8));

        // Create the case
        $case = Case::create([
            'order_number' => $orderNumber,
            'bill' => $request->bill,
            'case_manager_id' => $request->case_manager_id,
            'description' => $request->description,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Case created successfully',
            'data' => $case,
        ], 201);
    }
}

    // Assign the case to a case manager
    public function assignCaseManager(Request $request, $caseId)
    {
        $validator = Validator::make($request->all(), [
            'manager_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $case = CaseModel::findOrFail($caseId);
        $case->assigned_to = $request->manager_id;
        $case->save();

        return response()->json(['success' => 'Case manager assigned successfully']);
    }

}
