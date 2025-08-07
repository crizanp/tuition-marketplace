<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Auth\TutorAuthController;
use App\Http\Controllers\Auth\StudentAuthController;

// Home route
Route::get('/', function () {
    return view('welcome');
});

// CSS Test Route (for development)
Route::get('/css-test', function () {
    return view('test.css-test');
});

// Admin Routes
Route::prefix('admin')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [AdminAuthController::class, 'login']);
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
    
    Route::middleware('admin')->group(function () {
        Route::get('/dashboard', [AdminAuthController::class, 'dashboard'])->name('admin.dashboard');
    });
});

// Tutor Routes
Route::prefix('tutor')->group(function () {
    // Authentication routes
    Route::get('/login', [TutorAuthController::class, 'showLoginForm'])->name('tutor.login');
    Route::post('/login', [TutorAuthController::class, 'login']);
    Route::get('/register', [TutorAuthController::class, 'showRegisterForm'])->name('tutor.register');
    Route::post('/register', [TutorAuthController::class, 'register']);
    Route::post('/logout', [TutorAuthController::class, 'logout'])->name('tutor.logout');
    
    // Email verification routes (accessible without middleware)
    Route::get('/email/verify', [TutorAuthController::class, 'showEmailVerificationForm'])->name('tutor.verification.notice');
    Route::get('/email/verify/{id}/{hash}', [TutorAuthController::class, 'verifyEmail'])
        ->middleware(['signed'])
        ->name('tutor.verification.verify');
    Route::post('/email/verification-notification', [TutorAuthController::class, 'resendEmailVerification'])->name('tutor.verification.send');
    
    // Password reset routes
    Route::get('/forgot-password', [TutorAuthController::class, 'showForgotPasswordForm'])->name('tutor.password.request');
    Route::post('/forgot-password', [TutorAuthController::class, 'sendResetLinkEmail'])->name('tutor.password.email');
    Route::get('/password/reset/{token}', [TutorAuthController::class, 'showResetForm'])->name('tutor.password.reset');
    Route::post('/password/reset', [TutorAuthController::class, 'reset'])->name('tutor.password.update');
    
    Route::middleware(['tutor', 'verified:tutor'])->group(function () {
        Route::get('/dashboard', [TutorAuthController::class, 'dashboard'])->name('tutor.dashboard');
    });
});

// Student Routes
Route::prefix('student')->group(function () {
    // Authentication routes
    Route::get('/login', [StudentAuthController::class, 'showLoginForm'])->name('student.login');
    Route::post('/login', [StudentAuthController::class, 'login']);
    Route::get('/register', [StudentAuthController::class, 'showRegisterForm'])->name('student.register');
    Route::post('/register', [StudentAuthController::class, 'register']);
    Route::post('/logout', [StudentAuthController::class, 'logout'])->name('student.logout');
    
    // Email verification routes (accessible without middleware)
    Route::get('/email/verify', [StudentAuthController::class, 'showEmailVerificationForm'])->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', [StudentAuthController::class, 'verifyEmail'])
        ->middleware(['signed'])
        ->name('verification.verify');
    Route::post('/email/verification-notification', [StudentAuthController::class, 'resendEmailVerification'])->name('verification.send');
    
    // Password reset routes
    Route::get('/forgot-password', [StudentAuthController::class, 'showForgotPasswordForm'])->name('password.request');
    Route::post('/forgot-password', [StudentAuthController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/password/reset/{token}', [StudentAuthController::class, 'showResetForm'])->name('password.reset');
    Route::post('/password/reset', [StudentAuthController::class, 'reset'])->name('password.update');
    
    Route::middleware(['auth', 'verified:web'])->group(function () {
        Route::get('/dashboard', [StudentAuthController::class, 'dashboard'])->name('student.dashboard');
    });
});