<?php

use App\Http\Controllers\{
    AuthController, 
    TaskController, 
    PasswordController,
    VerificationController
};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => 'token'], function () {
   
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('me', [AuthController::class, 'me'])->middleware(['verified']);
    Route::post('/resend_email', [VerificationController::class, 'resendEmail']);
    

    Route::post('/tasks', [TaskController::class, 'index'])->middleware(['permission:documentacion']);

});

Route::post('password_reset_link', [PasswordController::class, 'sendEmail']);
Route::post('reset_password', [PasswordController::class, 'resetPassword']);
Route::post('/verification', [VerificationController::class, 'verification']);

Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);

//Route::post('/verify_email', [PasswordController::class, 'verify_email']);
