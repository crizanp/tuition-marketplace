<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Auth\TutorAuthController;
use App\Http\Controllers\Auth\StudentAuthController;
use App\Http\Controllers\TutorKycController;
use App\Http\Controllers\TutorProfileController;
use App\Http\Controllers\Admin\AdminKycController;
use App\Http\Controllers\TutorJobController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\VacancyController;
use App\Http\Controllers\Admin\AdminStudentController;
use App\Http\Controllers\Admin\AdminTutorController;
use App\Http\Controllers\Admin\AdminJobController;
use App\Http\Controllers\Admin\AdminVacancyController;
use App\Http\Controllers\Admin\AdminVacancyApplicationController;
use App\Http\Controllers\Admin\AdminMessageController;
use App\Http\Controllers\Admin\AdminAnalyticsController;
use App\Http\Controllers\SearchController;

// Home route
Route::get('/', function () {
    return view('welcome');
});

// Generic login route used by middleware when no specific guard route is available
// Redirects to student login by default. Adjust if you prefer a different default.
Route::get('/login', function () {
    return redirect()->route('student.login');
})->name('login');

// Global logout route
Route::post('/logout-all', function () {
    Auth::guard('web')->logout();
    Auth::guard('tutor')->logout();
    Auth::guard('admin')->logout();
    
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    
    return redirect('/')->with('message', 'You have been logged out from all accounts.');
})->name('logout.all');

// Global logout route - accessible from any guard
Route::post('/logout-all', [StudentAuthController::class, 'logoutAll'])->name('logout.all');

// Search Routes
Route::get('/search/tutors', [SearchController::class, 'searchTutors'])->name('search.tutors');
Route::get('/search/vacancies', [SearchController::class, 'searchVacancies'])->name('search.vacancies');
Route::get('/api/search/subjects', [SearchController::class, 'getSubjectSuggestions'])->name('api.search.subjects');
Route::get('/api/search/locations', [SearchController::class, 'getLocationSuggestions'])->name('api.search.locations');
Route::get('/api/search/districts', [SearchController::class, 'getDistrictSuggestions'])->name('api.search.districts');
Route::get('/api/search/places', [SearchController::class, 'getPlaceSuggestions'])->name('api.search.places');

// Public Job Routes
Route::get('/jobs', [JobController::class, 'index'])->name('jobs.index');
Route::get('/jobs/{tutorName}/{jobId}', [JobController::class, 'show'])->name('jobs.show')->where(['tutorName' => '[a-zA-Z0-9\-]+', 'jobId' => '[0-9]+']);
Route::get('/jobs/{job}/contact', [JobController::class, 'contact'])->name('jobs.contact');
Route::post('/jobs/{job}/inquiry', [JobController::class, 'sendInquiry'])->name('jobs.inquiry');
Route::get('/search/jobs', [JobController::class, 'search'])->name('jobs.search');

// Public Vacancy Routes
Route::get('/vacancies', [VacancyController::class, 'index'])->name('vacancies.index');
Route::get('/vacancies/{id}', [VacancyController::class, 'show'])->name('vacancies.show');
Route::post('/vacancies/{id}/apply', [VacancyController::class, 'apply'])->name('vacancies.apply');
Route::get('/search/vacancies', [VacancyController::class, 'search'])->name('vacancies.search');

// Wishlist Routes (require authentication)
Route::middleware(['auth'])->group(function () {
    Route::post('/jobs/{job}/wishlist', [JobController::class, 'toggleWishlist'])->name('jobs.wishlist.toggle');
    Route::get('/wishlist', [JobController::class, 'wishlist'])->name('wishlist.index');
});

// Rating Routes (require authentication)
Route::middleware(['auth'])->group(function () {
    Route::post('/tutor/{tutor}/rate', [TutorProfileController::class, 'rate'])->name('tutor.rate');
    Route::post('/jobs/{job}/rate', [JobController::class, 'storeRating'])->name('jobs.rate');
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
    Route::post('/logout-all', [AdminAuthController::class, 'logoutAll'])->name('admin.logout.all');
    
    Route::middleware('admin')->group(function () {
        Route::get('/dashboard', [AdminAuthController::class, 'dashboard'])->name('admin.dashboard');
        
        // Student Management Routes
        Route::prefix('students')->group(function () {
            Route::get('/', [AdminStudentController::class, 'index'])->name('admin.students.index');
            Route::get('/{id}', [AdminStudentController::class, 'show'])->name('admin.students.show');
            Route::put('/{id}/status', [AdminStudentController::class, 'updateStatus'])->name('admin.students.status');
            Route::delete('/{id}', [AdminStudentController::class, 'destroy'])->name('admin.students.destroy');
            Route::get('/export/csv', [AdminStudentController::class, 'export'])->name('admin.students.export');
        });
        
        // Tutor Management Routes
        Route::prefix('tutors')->group(function () {
            Route::get('/', [AdminTutorController::class, 'index'])->name('admin.tutors.index');
            Route::get('/{id}', [AdminTutorController::class, 'show'])->name('admin.tutors.show');
            Route::put('/{id}/status', [AdminTutorController::class, 'updateStatus'])->name('admin.tutors.status');
            Route::post('/{id}/approve', [AdminTutorController::class, 'approve'])->name('admin.tutors.approve');
            Route::post('/bulk-update', [AdminTutorController::class, 'bulkUpdate'])->name('admin.tutors.bulkUpdate');
            Route::delete('/{id}', [AdminTutorController::class, 'destroy'])->name('admin.tutors.destroy');
            Route::get('/export/csv', [AdminTutorController::class, 'export'])->name('admin.tutors.export');
        });
        
        // Job Management Routes
        Route::prefix('jobs')->group(function () {
            Route::get('/', [AdminJobController::class, 'index'])->name('admin.jobs.index');
            Route::get('/{id}', [AdminJobController::class, 'show'])->name('admin.jobs.show');
            Route::put('/{id}/status', [AdminJobController::class, 'updateStatus'])->name('admin.jobs.status');
            Route::post('/{id}/toggle-featured', [AdminJobController::class, 'toggleFeatured'])->name('admin.jobs.toggleFeatured');
            Route::post('/bulk-update', [AdminJobController::class, 'bulkUpdate'])->name('admin.jobs.bulkUpdate');
            Route::delete('/{id}', [AdminJobController::class, 'destroy'])->name('admin.jobs.destroy');
            Route::get('/export/csv', [AdminJobController::class, 'export'])->name('admin.jobs.export');
        });
        
        // Vacancy Management Routes
        Route::prefix('vacancies')->group(function () {
            Route::get('/create', [AdminVacancyController::class, 'create'])->name('admin.vacancies.create');
            Route::post('/store', [AdminVacancyController::class, 'store'])->name('admin.vacancies.store');
            Route::get('/', [AdminVacancyController::class, 'index'])->name('admin.vacancies.index');
            Route::get('/{id}', [AdminVacancyController::class, 'show'])->name('admin.vacancies.show');
            Route::post('/{id}/approve', [AdminVacancyController::class, 'approve'])->name('admin.vacancies.approve');
            Route::match(['post', 'patch'], '/{id}/reject', [AdminVacancyController::class, 'reject'])->name('admin.vacancies.reject');
            Route::put('/{id}/status', [AdminVacancyController::class, 'updateStatus'])->name('admin.vacancies.status');
            Route::post('/bulk-update', [AdminVacancyController::class, 'bulkUpdate'])->name('admin.vacancies.bulkUpdate');
            Route::delete('/{id}', [AdminVacancyController::class, 'destroy'])->name('admin.vacancies.destroy');
            Route::get('/export/csv', [AdminVacancyController::class, 'export'])->name('admin.vacancies.export');
        });
        
        // Vacancy Application Management Routes
        Route::prefix('vacancy-applications')->group(function () {
            Route::get('/', [AdminVacancyApplicationController::class, 'index'])->name('admin.vacancy-applications.index');
            Route::get('/{id}', [AdminVacancyApplicationController::class, 'show'])->name('admin.vacancy-applications.show');
            Route::post('/{id}/approve', [AdminVacancyApplicationController::class, 'approve'])->name('admin.vacancy-applications.approve');
            Route::post('/{id}/reject', [AdminVacancyApplicationController::class, 'reject'])->name('admin.vacancy-applications.reject');
            Route::put('/{id}/status', [AdminVacancyApplicationController::class, 'updateStatus'])->name('admin.vacancy-applications.status');
            Route::post('/bulk-update', [AdminVacancyApplicationController::class, 'bulkUpdate'])->name('admin.vacancy-applications.bulkUpdate');
            Route::delete('/{id}', [AdminVacancyApplicationController::class, 'destroy'])->name('admin.vacancy-applications.destroy');
            Route::get('/export/csv', [AdminVacancyApplicationController::class, 'export'])->name('admin.vacancy-applications.export');
        });
        
        // Message Management Routes
        Route::prefix('messages')->group(function () {
            Route::get('/', [AdminMessageController::class, 'index'])->name('admin.messages.index');
            Route::get('/{id}', [AdminMessageController::class, 'show'])->name('admin.messages.show');
            Route::post('/{id}/respond', [AdminMessageController::class, 'respond'])->name('admin.messages.respond');
            Route::post('/bulk-update', [AdminMessageController::class, 'bulkUpdate'])->name('admin.messages.bulkUpdate');
            Route::delete('/{id}', [AdminMessageController::class, 'destroy'])->name('admin.messages.destroy');
            Route::get('/export/csv', [AdminMessageController::class, 'export'])->name('admin.messages.export');
            Route::get('/stats/json', [AdminMessageController::class, 'getStats'])->name('admin.messages.stats');
        });
        
        // Analytics Routes
        Route::prefix('analytics')->group(function () {
            Route::get('/', [AdminAnalyticsController::class, 'index'])->name('admin.analytics.index');
            Route::get('/export', [AdminAnalyticsController::class, 'export'])->name('admin.analytics.export');
        });
        
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
    Route::post('/logout-all', [TutorAuthController::class, 'logoutAll'])->name('tutor.logout.all');
    
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
        
        // Job Management Routes
        Route::get('/jobs', [TutorJobController::class, 'index'])->name('tutor.jobs.index');
        Route::get('/jobs/create', [TutorJobController::class, 'create'])->name('tutor.jobs.create');
        Route::post('/jobs', [TutorJobController::class, 'store'])->name('tutor.jobs.store');
        Route::get('/jobs/{job}', [TutorJobController::class, 'show'])->name('tutor.jobs.show');
        Route::get('/jobs/{job}/edit', [TutorJobController::class, 'edit'])->name('tutor.jobs.edit');
        Route::put('/jobs/{job}', [TutorJobController::class, 'update'])->name('tutor.jobs.update');
        Route::delete('/jobs/{job}', [TutorJobController::class, 'destroy'])->name('tutor.jobs.destroy');
        Route::post('/jobs/{job}/toggle-status', [TutorJobController::class, 'toggleStatus'])->name('tutor.jobs.toggle-status');
        Route::get('/jobs-stats', [TutorJobController::class, 'getStats'])->name('tutor.jobs.stats');
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
    Route::post('/logout-all', [StudentAuthController::class, 'logoutAll'])->name('student.logout.all');
    
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
        
        // Profile routes
        Route::prefix('profile')->group(function () {
            Route::get('/', [\App\Http\Controllers\StudentProfileController::class, 'index'])->name('student.profile.index');
            Route::get('/edit', [\App\Http\Controllers\StudentProfileController::class, 'edit'])->name('student.profile.edit');
            Route::put('/update', [\App\Http\Controllers\StudentProfileController::class, 'update'])->name('student.profile.update');
            Route::get('/change-password', [\App\Http\Controllers\StudentProfileController::class, 'showChangePasswordForm'])->name('student.profile.change-password');
            Route::post('/change-password', [\App\Http\Controllers\StudentProfileController::class, 'updatePassword'])->name('student.profile.update-password');
        });
        
        // Vacancy routes
        Route::prefix('vacancies')->group(function () {
            Route::get('/', [\App\Http\Controllers\StudentVacancyController::class, 'index'])->name('student.vacancies.index');
            Route::get('/create', [\App\Http\Controllers\StudentVacancyController::class, 'create'])->name('student.vacancies.create');
            Route::post('/store', [\App\Http\Controllers\StudentVacancyController::class, 'store'])->name('student.vacancies.store');
            Route::get('/{vacancy}', [\App\Http\Controllers\StudentVacancyController::class, 'show'])->name('student.vacancies.show');
            Route::get('/{vacancy}/edit', [\App\Http\Controllers\StudentVacancyController::class, 'edit'])->name('student.vacancies.edit');
            Route::put('/{vacancy}', [\App\Http\Controllers\StudentVacancyController::class, 'update'])->name('student.vacancies.update');
            Route::delete('/{vacancy}', [\App\Http\Controllers\StudentVacancyController::class, 'destroy'])->name('student.vacancies.destroy');
        });
    });
});