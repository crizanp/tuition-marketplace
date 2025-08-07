<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Auth\TutorAuthController;
use App\Http\Controllers\Auth\StudentAuthController;
use App\Http\Controllers\TutorKycController;
use App\Http\Controllers\TutorProfileController;
use App\Http\Controllers\Admin\AdminKycController;

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
        
        // KYC Management Routes
        Route::get('/kyc', [AdminKycController::class, 'index'])->name('admin.kyc.index');
        Route::get('/kyc/{id}', [AdminKycController::class, 'show'])->name('admin.kyc.show');
        Route::post('/kyc/{id}/approve', [AdminKycController::class, 'approve'])->name('admin.kyc.approve');
        Route::post('/kyc/{id}/reject', [AdminKycController::class, 'reject'])->name('admin.kyc.reject');
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
        
        // Profile Routes
        Route::get('/profile', [TutorProfileController::class, 'index'])->name('tutor.profile.index');
        Route::post('/profile/personal', [TutorProfileController::class, 'updatePersonal'])->name('tutor.profile.personal');
        Route::post('/profile/about', [TutorProfileController::class, 'updateAbout'])->name('tutor.profile.about');
        Route::post('/profile/skills', [TutorProfileController::class, 'updateSkills'])->name('tutor.profile.skills');
        Route::post('/profile/education', [TutorProfileController::class, 'updateEducation'])->name('tutor.profile.education');
        Route::post('/profile/languages', [TutorProfileController::class, 'updateLanguages'])->name('tutor.profile.languages');
        Route::post('/profile/video', [TutorProfileController::class, 'uploadVideo'])->name('tutor.profile.video');
        Route::post('/profile/availability', [TutorProfileController::class, 'updateAvailability'])->name('tutor.profile.availability');
        Route::post('/profile/portfolio', [TutorProfileController::class, 'addPortfolio'])->name('tutor.profile.portfolio');
        Route::post('/profile/certifications', [TutorProfileController::class, 'addCertification'])->name('tutor.profile.certifications');
        Route::get('/profile/share', [TutorProfileController::class, 'share'])->name('tutor.profile.share');
        Route::get('/profile/preview', [TutorProfileController::class, 'preview'])->name('tutor.profile.preview');
        Route::get('/profile/stats', [TutorProfileController::class, 'getStats'])->name('tutor.profile.stats');
        
        // Debug route for testing
        Route::get('/profile/debug', function() {
            $tutor = Auth::guard('tutor')->user();
            $profile = $tutor->profile;
            $kyc = $tutor->kyc;
            
            return response()->json([
                'tutor' => $tutor ? $tutor->toArray() : null,
                'profile' => $profile ? $profile->toArray() : null,
                'kyc' => $kyc ? $kyc->toArray() : null,
            ]);
        })->name('tutor.profile.debug');
        
        // KYC Routes
        Route::get('/kyc', [TutorKycController::class, 'show'])->name('tutor.kyc.show');
        Route::get('/kyc/create', [TutorKycController::class, 'create'])->name('tutor.kyc.create');
        Route::post('/kyc', [TutorKycController::class, 'store'])->name('tutor.kyc.store');
        Route::get('/kyc/edit', [TutorKycController::class, 'edit'])->name('tutor.kyc.edit');
        Route::put('/kyc', [TutorKycController::class, 'update'])->name('tutor.kyc.update');
    });
});

// Public Profile Route
Route::get('/tutor/{id}/profile', [TutorProfileController::class, 'publicProfile'])->name('tutor.profile.public');

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