<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Models\Cases;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Exception;
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
            'user_id' => 'required|exists:users,id',
            'description' => 'required|string',
            'contract_file' => 'nullable|file|mimes:pdf|max:2048',
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
            'contract_file' => $contractFilePath ?? null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Case created successfully',
            'data' => [
                    'case' => $case,
                    'contract_file_url' => asset('storage/' . $case->contract_file),
                ],
        ], 201);
    }


    // upload contract file for the case
    public function uploadContractFile(Request $request, $id)
    {
        try{
        // Validate the file input
        $request->validate([
            'contract_file' => 'required|file|mimes:pdf|max:2048', // Only PDF, max size 2 MB
        ]);

        // Find the case by ID
        $case = Cases::findOrFail($id);

        // Define the case-specific folder
        $caseFolder = "contracts/case_{$case->id}";

        // Create a folder for the case if it doesn't exist
        if (!Storage::disk('local')->exists($caseFolder)) {
            Storage::disk('local')->makeDirectory($caseFolder);
        }

        // Store the contract file in the case's folder
        $fileName = $request->file('contract_file')->getClientOriginalName();
        $filePath = $request->file('contract_file')->storeAs($caseFolder, $fileName, 'local');

        // Update the case record with the file path
        $case->update([
            'contract_file' => $filePath,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Contract file uploaded successfully.',
            'file_path' => $filePath,
        ], 201);}
        catch (Exception $e) {
            // Log the error to the Laravel log file
            Log::error('Slide upload error: ' . $e->getMessage());

            // Return an error response to the client
            return response()->json([
                'message' => 'An error occurred while uploading the slide.',
                'error' => $e->getMessage(),
            ], 500);
        }
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
