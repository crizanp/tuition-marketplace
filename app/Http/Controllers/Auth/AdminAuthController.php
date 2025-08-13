<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;

class AdminAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.admin.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Check if a student or tutor is already logged in
        if (Auth::guard('web')->check()) {
            return back()->withErrors(['email' => 'A student is currently logged in. Please <a href="' . route('logout.all') . '" onclick="event.preventDefault(); document.getElementById(\'logout-all-form\').submit();" style="color: #dc3545; text-decoration: underline;">logout all accounts</a> first before logging in as an admin.'])
                ->with('logout_all_form', true);
        }
        
        if (Auth::guard('tutor')->check()) {
            return back()->withErrors(['email' => 'A tutor is currently logged in. Please <a href="' . route('logout.all') . '" onclick="event.preventDefault(); document.getElementById(\'logout-all-form\').submit();" style="color: #dc3545; text-decoration: underline;">logout all accounts</a> first before logging in as an admin.'])
                ->with('logout_all_form', true);
        }

        if (Auth::guard('admin')->attempt($request->only('email', 'password'), $request->remember)) {
            return redirect()->intended('/admin/dashboard');
        }

        return back()->withErrors(['email' => 'Invalid credentials']);
    }

    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect('/admin/login');
    }

    public function logoutAll()
    {
        // Logout from all guards
        Auth::guard('web')->logout();
        Auth::guard('tutor')->logout();
        Auth::guard('admin')->logout();
        
        // Clear session
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        
        return redirect('/')->with('message', 'You have been logged out from all accounts.');
    }

    public function dashboard()
    {
        $stats = [
            'total_students' => \App\Models\User::count(),
            'total_tutors' => \App\Models\Tutor::count(),
            'active_tutors' => \App\Models\Tutor::where('status', 'active')->count(),
            'pending_tutors' => \App\Models\Tutor::where('status', 'pending')->count(),
            'total_jobs' => \App\Models\TutorJob::count(),
            'active_jobs' => \App\Models\TutorJob::where('status', 'active')->count(),
            'featured_jobs' => \App\Models\TutorJob::where('is_featured', true)->count(),
            'total_vacancies' => \App\Models\StudentVacancy::count(),
            'pending_vacancies' => \App\Models\StudentVacancy::where('status', 'pending')->count(),
            'approved_vacancies' => \App\Models\StudentVacancy::where('status', 'approved')->count(),
            'total_applications' => \App\Models\VacancyApplication::count(),
            'pending_applications' => \App\Models\VacancyApplication::where('status', 'pending')->count(),
            'approved_applications' => \App\Models\VacancyApplication::where('status', 'approved')->count(),
            'total_messages' => \App\Models\ContactMessage::count(),
            'unread_messages' => \App\Models\ContactMessage::where('status', 'unread')->count(),
            'pending_kyc' => \App\Models\TutorKyc::where('status', 'pending')->count(),
            'total_ratings' => \App\Models\Rating::count(),
        ];
        
        // Recent activities
        $recentStudents = \App\Models\User::latest()->take(5)->get();
        $recentTutors = \App\Models\Tutor::latest()->take(5)->get();
        $recentJobs = \App\Models\TutorJob::with('tutor')->latest()->take(5)->get();
        $recentVacancies = \App\Models\StudentVacancy::with('student')->latest()->take(5)->get();
        
        return view('admin.dashboard', compact('stats', 'recentStudents', 'recentTutors', 'recentJobs', 'recentVacancies'));
    }
}