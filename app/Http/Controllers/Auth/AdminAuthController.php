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