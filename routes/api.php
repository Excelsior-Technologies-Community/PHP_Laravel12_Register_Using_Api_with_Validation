<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Public Users List (Search + Pagination + Sorting)
Route::get('/users', [UserController::class, 'index']);

// Protected Routes
Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/profile', [UserController::class, 'profile']);

    // Update Profile
    Route::post('/profile/update', [UserController::class, 'updateProfile']);

    Route::post('/change-password', [UserController::class, 'changePassword']);

    Route::get('/login-history', [UserController::class, 'loginHistory']);
    
    // Delete User
    Route::delete('/users/{id}', [UserController::class, 'destroy']);

    // Dashboard Statistics
    Route::get('/dashboard-stats', [UserController::class, 'dashboardStats']);
});
