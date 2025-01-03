<?php

// app/Http/Controllers/DocumentController.php
namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\DocumentCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    // view document
    public function viewDocuments($case_id)
    {
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
    }

    // upload document
    public function upload(Request $request)
    {
        // Validate input
        $request->validate([
            'category_id' => 'required|exists:document_categories,id',
            'case_id' => 'required|exists:cases,id',  // Ensure the case exists
            'document' => 'required|file|mimes:pdf,doc,docx,ppt,pptx|max:20480', // 20 MB max size
        ]);

        // Get the uploaded document
        $file = $request->file('document');

        // Store the file in a specific folder
        $filePath = $file->storeAs('documents', $file->getClientOriginalName(), 'local');

        // Create a new document entry and associate it with the case
        Document::create([
            'category_id' => $request->category_id,
            'case_id' => $request->case_id,
            'file_path' => $filePath,
            'original_name' => $file->getClientOriginalName(),
        ]);

        return response()->json([
            'message' => 'Document uploaded successfully.',
            'file_path' => $filePath,
        ], 201);
    }

    // add category
    public function addCategory(Request $request)
{
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
}

}
