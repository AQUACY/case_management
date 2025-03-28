<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CaseManagerController;
use App\Http\Controllers\CaseProfileController;
use App\Http\Controllers\RecommenderController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\MessageController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CaseQuestionnaireController;
use App\Http\Controllers\PublicationRecordController;
use App\Http\Controllers\ProposedEmploymentEndavor;
use App\Http\Controllers\ClientRecordController;
use App\Http\Controllers\BackgroundInformationController;
use App\Http\Controllers\AchievementController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Broadcast;
use App\Http\Controllers\CaseStatusController;
use App\Http\Controllers\CaseEducationController;
use App\Http\Controllers\CaseWorkExperienceController;
use App\Http\Controllers\CaseResearchSummaryController;
use App\Http\Controllers\ExtraordinaryAbilityController;
use App\Http\Controllers\AwardController;
use App\Http\Controllers\MembershipController;
use App\Http\Controllers\MediaCoverageController;
use App\Http\Controllers\SpeakingEngagementController;
use App\Http\Controllers\LeadershipRoleController;
use App\Http\Controllers\PersonalStatementController;

// Move this route outside of any groups and add cors middleware
Route::middleware(['api', 'cors'])->post('/broadcasting/auth', function (Request $request) {
    return Broadcast::auth($request);
})->middleware('auth:api');

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth',
], function($router){
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
});

Route::group([
    'middleware' => 'auth:api',
    'prefix' => 'auth',
], function($router){
    // Route::post('/register', [AuthController::class, 'register']);
    // Route::post('/login', [AuthController::class, 'login']);
    Route::put('account/update', [AuthController::class, 'updateAccount']);
    Route::put('account/password', [AuthController::class, 'updatePassword']);
    Route::delete('account/delete', [AuthController::class, 'deleteAccount']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api');
    Route::get('/refresh', [AuthController::class, 'refresh'])->middleware('auth:api');
    Route::get('/profile', [AuthController::class, 'profile'])->middleware('auth:api');
    Route::get('/announcements', [AnnouncementController::class, 'index']); // Available to all users
    Route::patch('/user/{id}/subscribe', [AnnouncementController::class, 'updateSubscription'])->middleware('auth:api');
    Route::post('cases/{caseId}/addmessages', [MessageController::class, 'store']); // Create a new message
    Route::post('/caseaddmessages', [MessageController::class, 'sendMessageToUser']); // Create a new message
    Route::post('/caserespondmessages/{id}/respond', [MessageController::class, 'respondToCaseMessage']); // Respond to case manager case
    Route::post('/messages/{id}/respond', [MessageController::class, 'respondToMessage']); // Respond to a message
    Route::patch('/messages/{id}/rate', [MessageController::class, 'rateResponse']); // Rate a response
    Route::post('/cases/{caseId}/questionnaire', [CaseQuestionnaireController::class, 'store']);
    Route::post('/questionnaires/{id}/request-review', [CaseQuestionnaireController::class, 'requestReview']);
    Route::post('/cases/{caseId}/questionnaire/respond', [CaseQuestionnaireController::class, 'respondToReview']);
    Route::get('/cases/questionnaire/{caseId}', [CaseQuestionnaireController::class, 'view']);
    Route::post('/cases/{caseId}/publication-records', [PublicationRecordController::class, 'updateOrCreate']);
    Route::get('/cases/{caseId}/publication-records', [PublicationRecordController::class, 'getPublicationRecord']);
    Route::post('/cases/{caseId}/addendavorrecords', [ProposedEmploymentEndavor::class, 'storeOrUpdate']);
    Route::get('/cases/{caseId}/getendavorrecords', [ProposedEmploymentEndavor::class, 'get']);
    Route::get('/cases/questionnaire', [CaseQuestionnaireController::class, 'index']);
    Route::get('/cases/proposed-employment-endavors', [ProposedEmploymentEndavor::class, 'index']);
    Route::post('/request-review/{caseId}/request-review', [ProposedEmploymentEndavor::class, 'requestReview']);
    Route::post('/cases/{caseId}/review-response', [ProposedEmploymentEndavor::class, 'respondToReview']);
    Route::get('/client-records/{caseId}', [ClientRecordController::class, 'index']);
    Route::delete('/client-records/{clientRecordId}/dependents/{dependentId}', [ClientRecordController::class, 'deleteDependent']);
    Route::post('/client-records/{caseId}/add', [ClientRecordController::class, 'store']);
    Route::get('/cases/mycase/{userId}', [CaseManagerController::class, 'showByUserId']);
    Route::delete('/case-questionnaires/case/{caseId}', [CaseQuestionnaireController::class, 'deleteCaseQuestionnaireByCaseId']);
    Route::delete('/case-questionnaires/case/{caseQuestionnaireId}/{familyMemberId}/delete', [CaseQuestionnaireController::class, 'deleteFamilyMember']);
    Route::get('/background-information', [BackgroundInformationController::class, 'index']);
    Route::get('/background-information/{caseId}', [BackgroundInformationController::class, 'show']);
    Route::post('/background-information/{caseId}', [BackgroundInformationController::class, 'storeOrUpdate']);
    Route::post('/background-information/{caseId}/respond-review', [BackgroundInformationController::class, 'respondToReview']);
    Route::get('/background-information/{caseId}/review-comments', [BackgroundInformationController::class, 'getReviewComments']);

    // additional qualifications (achievements)
    Route::get('/case/additional-qualification', [AchievementController::class, 'index']);
    Route::post('/additional-qualification/{caseId}', [AchievementController::class, 'store']);
    Route::get('/additional-qualification/{caseId}', [AchievementController::class, 'show']);
    Route::post('/additional-qualification/{caseId}/request-review', [AchievementController::class, 'requestReview']);
    Route::post('/additional-qualification/{caseId}/respond-review', [AchievementController::class, 'respondToReview']);
    Route::get('/additional-qualification/{caseId}/review-comments', [AchievementController::class, 'getReviewComments']);

    // contributions
    Route::get('/case/contributions', [ProjectController::class, 'index']);
    Route::post('/case/contributions/{caseId}', [ProjectController::class, 'store']);
    Route::get('/case/contributions/{caseId}', [ProjectController::class, 'show']);
    Route::post('/case/contributions/{caseId}/request-review', [ProjectController::class, 'requestReview']);
    Route::post('/case/contributions/{caseId}/respond-review', [ProjectController::class, 'respondToReview']);
    Route::get('/case/contributions/{caseId}/review-comments', [ProjectController::class, 'getReviewComments']);

    // payments
    Route::get('/payment/{caseId}', [PaymentController::class, 'getPaymentsByCaseId']);
    Route::post('/payment/{caseId}', [PaymentController::class, 'processPayment']);
    Route::post('/process-payment/{caseId}', [PaymentController::class, 'paymentCallback']);
    Route::get('/payment/{caseId}/success', [PaymentController::class, 'success']);
    Route::get('/payment/{caseId}/fail', [PaymentController::class, 'fail']);

    // cases
    Route::get('/casemanager/viewallcase', [CaseManagerController::class, 'index']);
    Route::get('/viewcase/{caseId}', [CaseManagerController::class, 'show']);
    Route::get('/cases/order/{orderNumber}', [CaseManagerController::class, 'showByOrderNumber']);

    // respond review route
    Route::post('/background-information/{caseId}/request-review', [BackgroundInformationController::class, 'requestReview']);


    // messging converstation
    Route::post('/messages/{messageId}/reply', [MessageController::class, 'replyToMessage']);
    Route::get('/messages/{messageId}/conversation', [MessageController::class, 'getMessageConversation']);
    Route::get('/messages/unread-count', [MessageController::class, 'getUnreadMessageCount']);
    Route::get('/messages/categories', [MessageController::class, 'getMessageCategories']);

    // Add these new real-time chat routes
    Route::get('/messages/{messageId}/updates', [MessageController::class, 'getConversationUpdates']);
    Route::get('/messages/conversations', [MessageController::class, 'getActiveConversations']);
    Route::post('/messages/conversation/{conversationId}/mark-read', [MessageController::class, 'markConversationMessageAsRead']);

    // Add this new route
    Route::post('/messages/{messageId}/read', [MessageController::class, 'markAsRead']);

    // case status routes
    Route::get('/case/{caseId}/statuses', [CaseStatusController::class, 'index']); // Get all statuses for a case
    Route::post('/case/statuses', [CaseStatusController::class, 'store']); // Create a case status
    Route::get('/case/statuses/{id}', [CaseStatusController::class, 'show']); // Show a specific status
    Route::put('/case/statuses/{id}', [CaseStatusController::class, 'update']); // Update a status
    Route::delete('/case/statuses/{id}', [CaseStatusController::class, 'destroy']); // Delete a status

        // Case Education Routes
    Route::get('/cases/{caseId}/education', [CaseEducationController::class, 'index']);
    Route::post('/cases/education', [CaseEducationController::class, 'store']);
    Route::patch('/cases/education/{id}', [CaseEducationController::class, 'update']);
    Route::delete('/cases/education/{id}', [CaseEducationController::class, 'destroy']);

    // Case Work Experience Routes
    Route::get('/cases/{caseId}/work-experience', [CaseWorkExperienceController::class, 'index']);
    Route::post('/cases/work-experience', [CaseWorkExperienceController::class, 'store']);
    Route::patch('/cases/work-experience/{id}', [CaseWorkExperienceController::class, 'update']);
    Route::delete('/cases/work-experience/{id}', [CaseWorkExperienceController::class, 'destroy']);

    // Case Research Summary Routes
    Route::get('/cases/{caseId}/research-summary', [CaseResearchSummaryController::class, 'show']);
    Route::post('/cases/research-summary', [CaseResearchSummaryController::class, 'store']);
    Route::patch('/cases/{caseId}/research-summary', [CaseResearchSummaryController::class, 'update']);
    Route::delete('/cases/{caseId}/research-summary', [CaseResearchSummaryController::class, 'destroy']);

    // Extraordinary Ability Routes
    Route::get('/cases/{caseId}/extraordinary-ability', [ExtraordinaryAbilityController::class, 'show']);
    Route::post('/cases/extraordinary-ability', [ExtraordinaryAbilityController::class, 'store']);
    Route::patch('/cases/{caseId}/extraordinary-ability', [ExtraordinaryAbilityController::class, 'update']);
    Route::delete('/cases/{caseId}/extraordinary-ability', [ExtraordinaryAbilityController::class, 'destroy']);

    // Awards
    Route::get('/cases/{caseId}/awards', [AwardController::class, 'index']);
    Route::post('/cases/awards', [AwardController::class, 'store']);
    Route::patch('/cases/awards/{id}', [AwardController::class, 'update']);
    Route::delete('/cases/awards/{id}', [AwardController::class, 'destroy']);

    // Memberships
    Route::get('/cases/{caseId}/memberships', [MembershipController::class, 'index']);
    Route::post('/cases/memberships', [MembershipController::class, 'store']);
    Route::patch('/cases/memberships/{id}', [MembershipController::class, 'update']);
    Route::delete('/cases/memberships/{id}', [MembershipController::class, 'destroy']);

    // Media Coverage
    Route::get('/cases/{caseId}/media-coverage', [MediaCoverageController::class, 'index']);
    Route::post('/cases/media-coverage', [MediaCoverageController::class, 'store']);
    Route::patch('/cases/media-coverage/{id}', [MediaCoverageController::class, 'update']);
    Route::delete('/cases/media-coverage/{id}', [MediaCoverageController::class, 'destroy']);

    // Speaking Engagements
    Route::get('/cases/{caseId}/speaking-engagements', [SpeakingEngagementController::class, 'index']);
    Route::post('/cases/speaking-engagements', [SpeakingEngagementController::class, 'store']);
    Route::patch('/cases/speaking-engagements/{id}', [SpeakingEngagementController::class, 'update']);
    Route::delete('/cases/speaking-engagements/{id}', [SpeakingEngagementController::class, 'destroy']);

    // Leadership Roles
    Route::get('/cases/{caseId}/leadership-roles', [LeadershipRoleController::class, 'index']);
    Route::post('/cases/leadership-roles', [LeadershipRoleController::class, 'store']);
    Route::patch('/cases/leadership-roles/{id}', [LeadershipRoleController::class, 'update']);
    Route::delete('/cases/leadership-roles/{id}', [LeadershipRoleController::class, 'destroy']);

    // personal statement
    Route::get('/cases/{caseId}/personal-statement', [PersonalStatementController::class, 'show']);
    Route::post('/cases/personal-statement', [PersonalStatementController::class, 'store']);
    Route::patch('/cases/{caseId}/personal-statement', [PersonalStatementController::class, 'update']);
    Route::delete('/cases/{caseId}/personal-statement', [PersonalStatementController::class, 'destroy']);

    // onboarding form
    // Route::get('/onboarding-form/{filename}', [DocumentController::class, 'getOnboardingForm']);

    // Make the route publicly accessible without auth middleware

});
Route::get('onboarding-form/{filename}', [DocumentController::class, 'getOnboardingForm'])
->name('onboarding-form.get')
->withoutMiddleware(['auth']); // Remove auth middleware for this route

Route::get('sample-documents/{folder}', [DocumentController::class, 'sampleDocuments'])
->name('sample-documents.get')
->withoutMiddleware(['auth']); // Remove auth middleware for this route

Route::options('download-sample-document/{folder}/{filename}', function() {
    return response('', 200)
        ->header('Access-Control-Allow-Origin', '*')
        ->header('Access-Control-Allow-Methods', 'GET, OPTIONS')
        ->header('Access-Control-Allow-Headers', 'Origin, Content-Type, Accept, Authorization, X-Request-With');
});

Route::get('download-sample-document/{folder}/{filename}', [DocumentController::class, 'downloadSampleDocument'])
    ->name('download-sample-document.download')
    ->withoutMiddleware(['auth']);



// admin middle ware
Route::middleware(['auth:api'])->group(function () {
    Route::post('/admin/register', [AuthController::class, 'register']);
    Route::post('/admin/createcase', [CaseManagerController::class, 'store']);
    Route::patch('/admin/updatecase/{id}', [CaseManagerController::class, 'update']);
    Route::patch('/admin/archivecase/{id}', [CaseManagerController::class, 'archive']);
    Route::patch('/admin/activatecase/{id}', [CaseManagerController::class, 'activate']);
    Route::get('/admin/viewallcase', [CaseManagerController::class, 'index']);
    Route::get('/admin/viewcase/{caseId}', [CaseManagerController::class, 'show']);
    Route::post('/admin/update/{caseId}/contractfile', [CaseManagerController::class, 'uploadContractFile']);
    Route::post('/admin/assign-case-manager/{caseId}', [CaseManagerController::class, 'assignCaseManager']);
    Route::post('/admin/document-categories/add', [DocumentController::class, 'addCategory']);
    Route::delete('/admin/document-categories/{id}', [DocumentController::class, 'deleteCategory']);
    Route::patch('/admin/document-categories/{id}', [DocumentController::class, 'updateCategory']);
    Route::get('/admin/document-categories', [DocumentController::class, 'getAllCategories']);
    Route::post('/admin/announcements', [AnnouncementController::class, 'create']); // Admin-only
    Route::patch('/admin/announcements/{id}', [AnnouncementController::class, 'update']);
    Route::delete('/admin/announcements/{id}', [AnnouncementController::class, 'destroy']);
    Route::post('/admin/addmessagecategory', [MessageController::class, 'createMessageCategory']);
    Route::delete('/admin/deletemessagecategory/{id}', [MessageController::class, 'deleteMessageCategory']);
    Route::patch('/admin/updatemessagecategory/{id}', [MessageController::class, 'updateMessageCategory']);
    Route::get('/admin/messagecategories', [MessageController::class, 'getMessageCategories']);
    Route::delete('/admin/cases/{caseId}/publication-records', [PublicationRecordController::class, 'destroyAll']);
    Route::delete('/admin/cases/{caseId}/deleteendavorrecords', [ProposedEmploymentEndavor::class, 'delete']);
    Route::delete('/admin/client-records/{caseId}', [ClientRecordController::class, 'deleteClientRecord']);
    Route::delete('/admin/additional-qualification/{caseId}', [AchievementController::class, 'destroy']);
    Route::delete('/admin/background-information/{caseId}', [BackgroundInformationController::class, 'destroy']);
    Route::get('/admin/users', [AuthController::class, 'listUsers']);
    Route::delete('/admin/users/{user_id}', [AuthController::class, 'deleteUserAsAdmin']);
    Route::patch('/admin/users/{user_id}', [AuthController::class, 'updateUserAsAdmin']);
});

// case middle
Route::middleware(['auth:api'])->prefix('cases/{caseId}')->group(function () {
    Route::post('/profile', [CaseProfileController::class, 'store']); // Add/Update case profile
    Route::get('/profile', [CaseProfileController::class, 'show']);  // Retrieve case profile
    Route::post('/recommenders', [RecommenderController::class, 'store']); // Add a new recommender
    Route::get('/recommenders', [RecommenderController::class, 'index']); // List recommenders for a case
    Route::patch('/recommenders/{id}', [RecommenderController::class, 'update']); // Update a recommender
    Route::post('/recommenders/bulk', [RecommenderController::class, 'addBulkRecommenders']);
    Route::post('/documents/upload', [DocumentController::class, 'upload']);
    Route::get('/documents', [DocumentController::class, 'viewDocuments']);
    Route::delete('/documents/{documentId}', [DocumentController::class, 'deleteDocument']);
    Route::get('/documents/{documentId}/download', [DocumentController::class, 'downloadDocument']);
    Route::get('/messages', [MessageController::class, 'getMessagesByCaseId']);

});

Route::post('password/forgot', [AuthController::class, 'forgotPassword']);
Route::post('password/reset', [AuthController::class, 'resetPassword']);
// Route::post('/register', [AuthController::class, 'register']);

// Route::get('/user', function (Request $request) {
//     return $request->user();

// })->middleware('auth:sanctum');

// Message routes
Route::middleware('auth:sanctum')->group(function () {
    // Existing message routes
    // ... existing code ...


});


