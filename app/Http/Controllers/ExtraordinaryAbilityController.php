<?php

namespace App\Http\Controllers;

use App\Models\ExtraordinaryAbility;
use App\Models\Award;
use App\Models\Membership;
use App\Models\MediaCoverage;
use App\Models\SpeakingEngagement;
use App\Models\LeadershipRole;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ExtraordinaryAbilityController extends Controller
{
    public function show($caseId)
    {
        try {
            $ability = ExtraordinaryAbility::with([
                'awards',
                'memberships',
                'mediaCoverages',
                'speakingEngagements',
                'leadershipRoles'
            ])->where('case_id', $caseId)->firstOrFail();

            return response()->json([
                'success' => true,
                'data' => $ability
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Extraordinary ability record not found'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving extraordinary ability record',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'case_id' => 'required|exists:cases,id',
                'has_awards' => 'required|boolean',
                'has_memberships' => 'required|boolean',
                'has_media_coverage' => 'required|boolean',
                'has_speaking_engagements' => 'required|boolean',
                'has_leadership_roles' => 'required|boolean',

                // Conditional validations based on boolean flags
                'awards' => 'required_if:has_awards,true|array',
                'memberships' => 'required_if:has_memberships,true|array',
                'media_coverages' => 'required_if:has_media_coverage,true|array',
                'speaking_engagements' => 'required_if:has_speaking_engagements,true|array',
                'leadership_roles' => 'required_if:has_leadership_roles,true|array'
            ]);

            $ability = ExtraordinaryAbility::create($validatedData);

            // Process related data if provided
            if ($request->has('awards') && $validatedData['has_awards']) {
                foreach ($request->awards as $award) {
                    Award::create([
                        'case_id' => $validatedData['case_id'],
                        ...$award
                    ]);
                }
            }

            if ($request->has('memberships') && $validatedData['has_memberships']) {
                foreach ($request->memberships as $membership) {
                    Membership::create([
                        'case_id' => $validatedData['case_id'],
                        ...$membership
                    ]);
                }
            }

            if ($request->has('media_coverages') && $validatedData['has_media_coverage']) {
                foreach ($request->media_coverages as $coverage) {
                    MediaCoverage::create([
                        'case_id' => $validatedData['case_id'],
                        ...$coverage
                    ]);
                }
            }

            if ($request->has('speaking_engagements') && $validatedData['has_speaking_engagements']) {
                foreach ($request->speaking_engagements as $engagement) {
                    SpeakingEngagement::create([
                        'case_id' => $validatedData['case_id'],
                        ...$engagement
                    ]);
                }
            }

            if ($request->has('leadership_roles') && $validatedData['has_leadership_roles']) {
                foreach ($request->leadership_roles as $role) {
                    LeadershipRole::create([
                        'case_id' => $validatedData['case_id'],
                        ...$role
                    ]);
                }
            }

            // Reload with relationships
            $ability->load([
                'awards',
                'memberships',
                'mediaCoverages',
                'speakingEngagements',
                'leadershipRoles'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Extraordinary ability record created successfully',
                'data' => $ability
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating extraordinary ability record',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $caseId)
    {
        try {
            $ability = ExtraordinaryAbility::where('case_id', $caseId)->firstOrFail();

            $validatedData = $request->validate([
                'has_awards' => 'sometimes|boolean',
                'has_memberships' => 'sometimes|boolean',
                'has_media_coverage' => 'sometimes|boolean',
                'has_speaking_engagements' => 'sometimes|boolean',
                'has_leadership_roles' => 'sometimes|boolean'
            ]);

            $ability->update($validatedData);

            // Update related records if provided
            if ($request->has('awards')) {
                Award::where('case_id', $caseId)->delete();
                foreach ($request->awards as $award) {
                    Award::create([
                        'case_id' => $caseId,
                        ...$award
                    ]);
                }
            }

            if ($request->has('memberships')) {
                Membership::where('case_id', $caseId)->delete();
                foreach ($request->memberships as $membership) {
                    Membership::create([
                        'case_id' => $caseId,
                        ...$membership
                    ]);
                }
            }

            if ($request->has('media_coverages')) {
                MediaCoverage::where('case_id', $caseId)->delete();
                foreach ($request->media_coverages as $coverage) {
                    MediaCoverage::create([
                        'case_id' => $caseId,
                        ...$coverage
                    ]);
                }
            }

            if ($request->has('speaking_engagements')) {
                SpeakingEngagement::where('case_id', $caseId)->delete();
                foreach ($request->speaking_engagements as $engagement) {
                    SpeakingEngagement::create([
                        'case_id' => $caseId,
                        ...$engagement
                    ]);
                }
            }

            if ($request->has('leadership_roles')) {
                LeadershipRole::where('case_id', $caseId)->delete();
                foreach ($request->leadership_roles as $role) {
                    LeadershipRole::create([
                        'case_id' => $caseId,
                        ...$role
                    ]);
                }
            }

            // Reload with relationships
            $ability->load([
                'awards',
                'memberships',
                'mediaCoverages',
                'speakingEngagements',
                'leadershipRoles'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Extraordinary ability record updated successfully',
                'data' => $ability
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Extraordinary ability record not found'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating extraordinary ability record',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($caseId)
    {
        try {
            $ability = ExtraordinaryAbility::where('case_id', $caseId)->firstOrFail();

            // Delete related records (can also be handled by database cascade)
            Award::where('case_id', $caseId)->delete();
            Membership::where('case_id', $caseId)->delete();
            MediaCoverage::where('case_id', $caseId)->delete();
            SpeakingEngagement::where('case_id', $caseId)->delete();
            LeadershipRole::where('case_id', $caseId)->delete();

            $ability->delete();

            return response()->json([
                'success' => true,
                'message' => 'Extraordinary ability record deleted successfully'
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Extraordinary ability record not found'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting extraordinary ability record',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
