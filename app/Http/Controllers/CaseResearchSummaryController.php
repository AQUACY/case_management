<?php

namespace App\Http\Controllers;

use App\Models\CaseResearchSummary;
use App\Models\CaseResearchProject;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CaseResearchSummaryController extends Controller
{
    public function show($caseId)
    {
        try {
            $summary = CaseResearchSummary::with('projects')
                ->where('case_id', $caseId)
                ->firstOrFail();

            return response()->json([
                'success' => true,
                'data' => $summary
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Research summary not found'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving research summary',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'case_id' => 'required|exists:cases,id',
                'field_description' => 'required|string|max:255',
                'expertise_description' => 'required|string',
                'work_impact' => 'required|string',
                'projects' => 'required|array|min:1',
                'projects.*.project_description' => 'required|string'
            ]);

            // Create research summary
            $summary = CaseResearchSummary::create([
                'case_id' => $validatedData['case_id'],
                'field_description' => $validatedData['field_description'],
                'expertise_description' => $validatedData['expertise_description'],
                'work_impact' => $validatedData['work_impact']
            ]);

            // Create projects
            foreach ($validatedData['projects'] as $index => $project) {
                CaseResearchProject::create([
                    'case_id' => $validatedData['case_id'],
                    'project_description' => $project['project_description'],
                    'order' => $index + 1
                ]);
            }

            // Reload with projects
            $summary->load('projects');

            return response()->json([
                'success' => true,
                'message' => 'Research summary created successfully',
                'data' => $summary
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating research summary',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $caseId)
    {
        try {
            $summary = CaseResearchSummary::where('case_id', $caseId)->firstOrFail();

            $validatedData = $request->validate([
                'field_description' => 'sometimes|string|max:255',
                'expertise_description' => 'sometimes|string',
                'work_impact' => 'sometimes|string',
                'projects' => 'sometimes|array|min:1',
                'projects.*.project_description' => 'required_with:projects|string',
                'projects.*.id' => 'sometimes|exists:case_research_projects,id'
            ]);

            // Update summary
            $summary->update($validatedData);

            // Update projects if provided
            if (isset($validatedData['projects'])) {
                // Delete existing projects
                CaseResearchProject::where('case_id', $caseId)->delete();

                // Create new projects
                foreach ($validatedData['projects'] as $index => $project) {
                    CaseResearchProject::create([
                        'case_id' => $caseId,
                        'project_description' => $project['project_description'],
                        'order' => $index + 1
                    ]);
                }
            }

            // Reload with projects
            $summary->load('projects');

            return response()->json([
                'success' => true,
                'message' => 'Research summary updated successfully',
                'data' => $summary
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Research summary not found'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating research summary',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($caseId)
    {
        try {
            $summary = CaseResearchSummary::where('case_id', $caseId)->firstOrFail();

            // Delete projects first (can also be handled by database cascade)
            CaseResearchProject::where('case_id', $caseId)->delete();

            // Delete summary
            $summary->delete();

            return response()->json([
                'success' => true,
                'message' => 'Research summary deleted successfully'
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Research summary not found'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting research summary',
                'error' => $e->getMessage()
            ], 500);
        }
    }
} 
