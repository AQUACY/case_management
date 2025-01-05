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




Route::group([
    'middleware' => 'api',
    'prefix' => 'auth',

], function($router){
    // Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::put('account/update', [AuthController::class, 'updateAccount']);
    Route::put('account/password', [AuthController::class, 'updatePassword']);
    Route::delete('account/delete', [AuthController::class, 'deleteAccount']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api');
    Route::get('/refresh', [AuthController::class, 'refresh'])->middleware('auth:api');
    Route::get('/profile', [AuthController::class, 'profile'])->middleware('auth:api');
    Route::get('/announcements', [AnnouncementController::class, 'index']); // Available to all users
    Route::patch('/user/{id}/subscribe', [AnnouncementController::class, 'updateSubscription'])->middleware('auth:api');
    Route::post('/addmessages', [MessageController::class, 'store']); // Create a new message
    Route::post('/caseaddmessages', [MessageController::class, 'sendMessageToUser']); // Create a new message
    Route::post('/caserespondmessages/{id}/respond', [MessageController::class, 'respondToCaseMessage']); // Respond to case manager case
    Route::post('/messages/{id}/respond', [MessageController::class, 'respondToMessage']); // Respond to a message
    Route::patch('/messages/{id}/rate', [MessageController::class, 'rateResponse']); // Rate a response
    Route::post('/cases/{caseId}/questionnaire', [CaseQuestionnaireController::class, 'store']);
    Route::post('/questionnaires/{id}/request-review', [CaseQuestionnaireController::class, 'requestReview']);
    Route::post('/cases/{caseId}/publication-records', [PublicationRecordController::class, 'updateOrCreate']);
    Route::get('cases/{caseId}/publication-records', [PublicationRecordController::class, 'getPublicationRecord']);

});

// admin middle ware
Route::middleware(['auth:api', 'role:administrator'])->group(function () {
    Route::post('/admin/register', [AuthController::class, 'register']);
    Route::post('/admin/createcase', [CaseManagerController::class, 'store']);
    Route::patch('/admin/updatecase/{id}', [CaseManagerController::class, 'update']);
    Route::patch('/admin/archivecase/{id}', [CaseManagerController::class, 'archive']);
    Route::get('/admin/viewallcase', [CaseManagerController::class, 'index']);
    Route::get('/admin/viewcase/{caseId}', [CaseManagerController::class, 'show']);
    Route::post('/admin/update/{caseId}/contractfile', [CaseManagerController::class, 'uploadContractFile']);
    Route::post('/admin/assign-case-manager/{caseId}', [CaseManagerController::class, 'assignCaseManager']);
    Route::post('/admin/document-categories/add', [DocumentController::class, 'addCategory']);
    Route::post('/admin/announcements', [AnnouncementController::class, 'create']); // Admin-only
    Route::patch('/admin/announcements/{id}', [AnnouncementController::class, 'update']);
    Route::delete('/admin/announcements/{id}', [AnnouncementController::class, 'destroy']);
    Route::post('/admin/addmessagecategory', [MessageController::class, 'createMessageCategory']);
    Route::delete('/admin/deletemessagecategory/{id}', [MessageController::class, 'deleteMessageCategory']);
    Route::delete('/admin/cases/{caseId}/publication-records', [PublicationRecordController::class, 'destroyAll']);
});

// case middle
Route::prefix('cases/{caseId}')->group(function () {
    Route::post('/profile', [CaseProfileController::class, 'store']); // Add/Update case profile
    Route::get('/profile', [CaseProfileController::class, 'show']);  // Retrieve case profile
    Route::post('/recommenders', [RecommenderController::class, 'store']); // Add a new recommender
    Route::get('/recommenders', [RecommenderController::class, 'index']); // List recommenders for a case
    Route::put('/recommenders/{id}', [RecommenderController::class, 'update']); // Update a recommender
    Route::post('/recommenders/bulk', [RecommenderController::class, 'addBulkRecommenders']);
    Route::post('/documents/upload', [DocumentController::class, 'upload']);
    Route::get('/documents', [DocumentController::class, 'viewDocuments']);
});

Route::post('password/forgot', [AuthController::class, 'forgotPassword']);
Route::post('password/reset', [AuthController::class, 'resetPassword']);



// Route::get('/user', function (Request $request) {
//     return $request->user();

// })->middleware('auth:sanctum');
