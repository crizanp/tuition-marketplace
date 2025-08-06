<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class StudentAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.student.login');
    }

    public function showRegisterForm()
    {
        return view('auth.student.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string',
            'grade_level' => 'nullable|string',
            'preferred_subjects' => 'nullable|array',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'grade_level' => $request->grade_level,
            'preferred_subjects' => $request->preferred_subjects,
        ]);

        Auth::login($user);
        return redirect('/student/dashboard');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('email', 'password'), $request->remember)) {
            return redirect()->intended('/student/dashboard');
        }

        return back()->withErrors(['email' => 'Invalid credentials']);
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/student/login');
    }

    public function dashboard()
    {
        return view('student.dashboard');
    }
}