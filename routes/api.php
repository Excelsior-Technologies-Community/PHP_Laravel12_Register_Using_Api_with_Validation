<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

// Public Routes with Strict Rate Limiting / Bot Protection
Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:3,1');
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');

// Public Users List (Search + Pagination + Sorting)
Route::get('/users', [UserController::class, 'index']);

// Protected Routes
Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/profile', [UserController::class, 'profile']);

    // Update Profile
    Route::post('/profile/update', [UserController::class, 'updateProfile']);

    // Avatar Upload API
    Route::post('/profile/avatar', [UserController::class, 'uploadAvatar']);

    Route::post('/change-password', [UserController::class, 'changePassword']);

    Route::get('/login-history', [UserController::class, 'loginHistory']);
    
    // Delete User
    Route::delete('/users/{id}', [UserController::class, 'destroy']);

    // Role & Status Control (Admin Only)
    Route::post('/users/{id}/toggle-status', [UserController::class, 'toggleStatus']);
    Route::post('/users/{id}/change-role', [UserController::class, 'changeRole']);

    // Personal Access Tokens Management (API Vault)
    Route::get('/tokens', [UserController::class, 'listTokens']);
    Route::post('/tokens', [UserController::class, 'createToken']);
    Route::delete('/tokens/{id}', [UserController::class, 'revokeToken']);

    // Dashboard Statistics
    Route::get('/dashboard-stats', [UserController::class, 'dashboardStats']);
});
