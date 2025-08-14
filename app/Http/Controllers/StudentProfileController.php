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
            'grade_level' => 'nullable|string|max:100',
            'qualification' => 'nullable|string|max:100',
            'institution' => 'nullable|string|max:150',
            'location_district' => 'nullable|string|max:100',
            'location_place' => 'nullable|string|max:100',
            'location_landmark' => 'nullable|string|max:150',
            'whatsapp' => 'nullable|string|max:30',
            'preferred_subjects' => 'nullable|array',
            'preferred_subjects.*' => 'string|max:100',
            'bio' => 'nullable|string|max:100',
            'profile_picture' => 'nullable|image|max:2048',
        ]);

        // Use provided grade_level directly (JS may have replaced select with hidden input)
        $gradeLevel = $request->input('grade_level');

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'grade_level' => $gradeLevel,
            'qualification' => $request->qualification,
            'institution' => $request->institution,
            'location_district' => $request->location_district,
            'location_place' => $request->location_place,
            'location_landmark' => $request->location_landmark,
            'whatsapp' => $request->whatsapp,
            'preferred_subjects' => $request->preferred_subjects ?? [],
            'bio' => $request->bio,
        ];

        // handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            $file = $request->file('profile_picture');
            $path = $file->store('profile_pictures', 'public');
            $data['profile_picture'] = $path;
        }

        $student->update($data);

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
