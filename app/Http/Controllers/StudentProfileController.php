<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class StudentProfileController extends Controller
{
    /**
     * Display the student profile page
     */
    public function index()
    {
        $student = Auth::user();
        return view('student.profile.index', compact('student'));
    }

    /**
     * Show the form for editing the student profile
     */
    public function edit()
    {
        $student = Auth::user();
        return view('student.profile.edit', compact('student'));
    }

    /**
     * Update the student profile
     */
    public function update(Request $request)
    {
        $student = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($student->id),
            ],
            'phone' => 'nullable|string|max:20',
            'grade_level' => 'nullable|string|max:50',
            'preferred_subjects' => 'nullable|array',
            'preferred_subjects.*' => 'string|max:100',
        ]);

        $student->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'grade_level' => $request->grade_level,
            'preferred_subjects' => $request->preferred_subjects ?? [],
        ]);

        return redirect()
            ->route('student.profile.index')
            ->with('success', 'Profile updated successfully!');
    }

    /**
     * Show the form for changing password
     */
    public function showChangePasswordForm()
    {
        return view('student.profile.change-password');
    }

    /**
     * Update the student password
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $student = Auth::user();

        if (!Hash::check($request->current_password, $student->password)) {
            return back()->withErrors(['current_password' => 'The provided password does not match your current password.']);
        }

        $student->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()
            ->route('student.profile.index')
            ->with('success', 'Password updated successfully!');
    }
}
