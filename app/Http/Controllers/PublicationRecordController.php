<?php

namespace App\Http\Controllers;

use App\Models\PublicationRecord;
use Illuminate\Http\Request;
use App\Models\Cases;
use Log;
use Exception;

class PublicationRecordController extends Controller
{
    public function updateOrCreate(Request $request, $caseId)
    {
        try{
        $validatedData = $request->validate([
            'peer_reviewed_journal_articles' => 'nullable|integer|min:0',
            'notes_peer_reviewed_journal' => 'nullable|string',
            'peer_reviewed_conference_articles' => 'nullable|integer|min:0',
            'notes_peer_reviewed_conference' => 'nullable|string',
            'conference_abstracts' => 'nullable|integer|min:0',
            'notes_conference_abstracts' => 'nullable|string',
            'pre_prints' => 'nullable|integer|min:0',
            'notes_pre_prints' => 'nullable|string',
            'book_chapters' => 'nullable|integer|min:0',
            'notes_book_chapters' => 'nullable|string',
            'books' => 'nullable|integer|min:0',
            'notes_books' => 'nullable|string',
            'technical_reports' => 'nullable|integer|min:0',
            'notes_technical_reports' => 'nullable|string',
            'granted_patents' => 'nullable|integer|min:0',
            'notes_granted_patents' => 'nullable|string',
            'others' => 'nullable|string',
            'in_preparation_manuscripts' => 'nullable|string',
            'research_topic' => 'nullable|string',
            'significance' => 'nullable|string',
            'funding_sources' => 'nullable|string',
            'citation_database_link' => 'nullable|string',
            'editor_role' => 'nullable|string',
            'editor_journals' => 'nullable|string',
            'number_of_peer_reviews' => 'nullable|integer|min:0',
            'served_on_phd_dissertation_committee' => 'nullable|string',
            'performed_grant_application_for_government_agencies' => 'nullable|string',
            'grant_application_agency' => 'nullable|string',
        ]);

        $publicationRecord = PublicationRecord::updateOrCreate(
            ['case_id' => $caseId],
            $validatedData
        );

        return response()->json([
            'message' => 'Publication record updated successfully',
            'data' => $publicationRecord
        ]);
    }catch (Exception $e) {
        // Log error and return response
        return response()->json(['message' => 'Error saving record', 'error' => $e->getMessage()], 500);
}
    }

    // delete the records
    public function destroyAll($caseId)
{
    try{
    $case = Cases::findOrFail($caseId);

    $case->publicationRecord()->delete();

    return response()->json([
        'message' => 'All publication records have been deleted successfully.'
    ], 200);
}catch (Exception $e) {
    // Log error and return response
    return response()->json(['message' => 'Error saving record', 'error' => $e->getMessage()], 500);
}
}

// get publication records
public function getPublicationRecord($caseId)
{
    try{
    // Find the case by ID
    $case = Cases::findOrFail($caseId);

    // Get the publication record associated with the case
    $publicationRecord = $case->publicationRecord;

    // Return the publication record as a JSON response
    return response()->json([
        'message' => 'Publication record fetched successfully',
        'data' => $publicationRecord
    ]);
}catch (Exception $e) {
    // Log error and return response
    return response()->json(['message' => 'Error saving record', 'error' => $e->getMessage()], 500);
}
}
}
