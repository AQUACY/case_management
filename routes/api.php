<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CaseManagerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


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
});

Route::middleware(['auth:api', 'role:administrator'])->group(function () {
    Route::post('/admin/register', [AuthController::class, 'register']);
    Route::post('/admin/createcase', [CaseManagerController::class, 'store']);
    Route::post('/admin/assign-case-manager/{caseId}', [CaseManagerController::class, 'assignCaseManager']);
});


Route::post('password/forgot', [AuthController::class, 'forgotPassword']);
Route::post('password/reset', [AuthController::class, 'resetPassword']);



// Route::get('/user', function (Request $request) {
//     return $request->user();

// })->middleware('auth:sanctum');
