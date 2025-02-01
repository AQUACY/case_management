<?php

// app/Http/Controllers/DocumentController.php
namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\DocumentCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Cases;
use Log;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;

class DocumentController extends Controller
{
    // view document
    public function viewDocuments($case_id)
    {
        try{
        // Retrieve documents for the specified case
        $documents = Document::where('case_id', $case_id)->get();

        // Check if documents exist for the given case
        if ($documents->isEmpty()) {
            return response()->json([
                'message' => 'No documents found for this case.'
            ], 404);
        }

        return response()->json([
            'documents' => $documents
        ], 200);
    }catch (Exception $e) {
        // Log error and return response
        return response()->json(['message' => 'Error saving record', 'error' => $e->getMessage()], 500);
}
    }

    // upload document
    public function upload(Request $request, $case_id)
    {
        try {
            // Validate input
            $request->validate([
                'name' => 'required|string|max:255',
                'category_id' => 'required|exists:document_categories,id',
                'document' => 'required|file|mimes:pdf,doc,docx,ppt,pptx|max:20480', // 20 MB max size
                'additional_notes' => 'nullable|string',
            ]);

            // Check if the case exists
            $case = Cases::findOrFail($case_id); // Assuming you have a Case model

            // Get the uploaded document
            $file = $request->file('document');

            // Store the file in a specific folder
            $filePath = $file->storeAs('documents', $file->getClientOriginalName(), 'local');

            // Create a new document entry and associate it with the case
            Document::create([
                'category_id' => $request->category_id,
                'case_id' => $case->id,
                'file_path' => $filePath,
                'original_name' => $file->getClientOriginalName(),
                'name' => $request->name,
                'additional_notes' => $request->additional_notes,
            ]);

            return response()->json([
                'message' => 'Document uploaded successfully.',
                'file_path' => $filePath,
            ], 201);
        } catch (Exception $e) {
            // Log error and return response
            return response()->json(['message' => 'Error saving record', 'error' => $e->getMessage()], 500);
        }
    }


    // add category
    public function addCategory(Request $request)
{
    try{
    // Validate the category name
    $request->validate([
        'name' => 'required|string|max:255|unique:document_categories',
    ]);

    // Create the category
    $category = DocumentCategory::create([
        'name' => $request->name,
    ]);

    return response()->json([
        'message' => 'Category created successfully.',
        'category' => $category,
    ], 201);
}catch (Exception $e) {
    // Log error and return response
    return response()->json(['message' => 'Error saving record', 'error' => $e->getMessage()], 500);
}
}

// delete a category
public function deleteCategory($id)
{
    try{
    $category = DocumentCategory::findOrFail($id);
    $category->delete();
    return response()->json(['message' => 'Category deleted successfully'], 200);
}catch (Exception $e) {
    // Log error and return response
    return response()->json(['message' => 'Error deleting record', 'error' => $e->getMessage()], 500);
}
}

// update a category
public function updateCategory($id, Request $request)
{
    try{
    $category = DocumentCategory::findOrFail($id);
    $category->update($request->all());
    return response()->json(['message' => 'Category updated successfully'], 200);
}catch (Exception $e) {
    // Log error and return response
    return response()->json(['message' => 'Error updating record', 'error' => $e->getMessage()], 500);
}
}

// get all document categories
public function getAllCategories()
{
    try {
        $categories = DocumentCategory::all();
        return response()->json([
            'success' => true,
            'data' => $categories
        ], 200);
    } catch (Exception $e) {
        return response()->json([
            'message' => 'Error fetching categories',
            'error' => $e->getMessage()
        ], 500);
    }
}


// delete a document
public function deleteDocument($caseId, $documentId)
{
    try {
        // Find the document by case_id and document_id
        $document = Document::where('case_id', $caseId)->where('id', $documentId)->firstOrFail();

        // Delete the document file from storage
        if (Storage::exists($document->file_path)) {
            Storage::delete($document->file_path);
        }

        // Delete the document record from the database
        $document->delete();

        return response()->json([
            'success' => true,
            'message' => 'Document deleted successfully.'
        ], 200);
    } catch (ModelNotFoundException $e) {
        return response()->json([
            'error' => 'Document not found for the provided case ID and document ID.'
        ], 404);
    } catch (Exception $e) {
        return response()->json([
            'error' => 'An error occurred while deleting the document.',
            'message' => $e->getMessage()
        ], 500);
    }
}

// download a document
public function downloadDocument($caseId, $documentId)
{
    try {
        // Find the document by case_id and document_id
        $document = Document::where('case_id', $caseId)->where('id', $documentId)->firstOrFail();

        // Get the file path
        $filePath = $document->file_path;

        // Check if file exists
        if (!Storage::exists($filePath)) {
            return response()->json([
                'error' => 'File not found.'
            ], 404);
        }

        // Return the file as a response for download
        return Storage::download($filePath, $document->original_name);
    } catch (ModelNotFoundException $e) {
        return response()->json([
            'error' => 'Document not found for the provided case ID and document ID.'
        ], 404);
    } catch (Exception $e) {
        return response()->json([
            'error' => 'An error occurred while downloading the document.',
            'message' => $e->getMessage()
        ], 500);
    }
}


}
