<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Tutor;

class TutorAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.tutor.login');
    }

    public function showRegisterForm()
    {
        return view('auth.tutor.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:tutors',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string',
            'bio' => 'nullable|string',
            'subjects' => 'nullable|array',
            'hourly_rate' => 'nullable|numeric|min:0',
        ]);

        $tutor = Tutor::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'bio' => $request->bio,
            'subjects' => $request->subjects,
            'hourly_rate' => $request->hourly_rate,
        ]);

        Auth::guard('tutor')->login($tutor);
        return redirect('/tutor/dashboard');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('tutor')->attempt($request->only('email', 'password'), $request->remember)) {
            return redirect()->intended('/tutor/dashboard');
        }

        return back()->withErrors(['email' => 'Invalid credentials']);
    }

    public function logout()
    {
        Auth::guard('tutor')->logout();
        return redirect('/tutor/login');
    }

    public function dashboard()
    {
        return view('tutor.dashboard');
    }
}