<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\CallController;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
Route::get('reset-password', [AuthController::class, 'showResetPasswordForm'])->name('password.reset');
Route::post('reset-password', [AuthController::class, 'resetPassword'])->name('password.reset.post');

Route::middleware('auth:sanctum')->group(function () {
    Route::delete('logout', [AuthController::class, 'logout']);
    Route::get('calls', [CallController::class, 'index']);
    Route::get('me', [AuthController::class, 'me']);
    Route::get('users', [UserController::class, 'index']);
});

Route::get('token', [CallController::class, 'token']);
Route::post('voice', [CallController::class, 'voice']);
Route::post('callback', [CallController::class, 'callback']);
