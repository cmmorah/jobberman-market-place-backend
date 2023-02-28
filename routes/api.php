<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\SendEmailController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/product/read', [ProductController::class, 'read']);
Route::get('send-emails', [SendEmailController::class, 'index']);
Route::get('/product/read-by-id/{product_id}', [ProductController::class, 'readById']);
//todo auth route
Route::post('/authentication/initiate-enrollment', [UserController::class, 'initiateEnrollment']);
Route::post('/authentication/complete-enrollment', [UserController::class, 'completeEnrollment']);
Route::post('/authentication/login', [UserController::class, 'login']);
Route::post('/authentication/initiate-password-reset', [UserController::class, 'initiatePasswordReset']);
Route::post('/authentication/complete-password-reset', [UserController::class, 'completePasswordReset']);
Route::post('/authentication/resend-otp', [UserController::class, 'resendOtp']);
Route::get('/authentication/expired-session', [UserController::class, 'expiredSession'])->name("expiredSession");

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/authentication/user-details', [UserController::class, 'userDetails']);
    Route::get('/authentication/logout', [UserController::class, 'logout']);
    Route::get('/product/read-by-user-id', [ProductController::class, 'readByUserId']);
    Route::get('/product/delete/{product_id}', [ProductController::class, 'delete']);
    Route::post('/product/create', [ProductController::class, 'create']);
});
