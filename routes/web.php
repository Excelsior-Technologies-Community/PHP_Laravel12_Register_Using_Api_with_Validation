<?php

use App\Http\Controllers\WebController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/login');
});

// Disable session middleware for auth pages
Route::middleware(['guest'])->group(function () {
    Route::get('/login', [WebController::class, 'loginPage'])->name('login')->withoutMiddleware(['web']);
    Route::get('/register', [WebController::class, 'registerPage'])->name('register')->withoutMiddleware(['web']);
});

Route::get('/dashboard', [WebController::class, 'dashboard'])->name('dashboard');