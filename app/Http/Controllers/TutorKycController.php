<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\TutorKyc;
use App\Models\Tutor;

class TutorKycController extends Controller
{
    public function show()
    {
        $tutor = Auth::guard('tutor')->user();
        $kyc = $tutor->kyc;

        return view('tutor.kyc.show', compact('kyc'));
    }

    public function create()
    {
        $tutor = Auth::guard('tutor')->user();
        
        // Check if KYC already exists
        if ($tutor->kyc) {
            return redirect()->route('tutor.kyc.show')->with('info', 'You have already submitted your KYC application.');
        }

        $subjects = [
            'Mathematics',
            'Physics',
            'Chemistry',
            'Biology',
            'English',
            'Computer Science',
            'History',
            'Geography',
            'Economics',
            'Accounting',
            'Business Studies',
            'Psychology',
            'Art',
            'Music',
            'Physical Education',
            'Foreign Languages',
            'Social Studies',
            'Statistics',
            'Philosophy',
            'Engineering'
        ];

        return view('tutor.kyc.create', compact('subjects'));
    }

    public function store(Request $request)
    {
        $tutor = Auth::guard('tutor')->user();
        
        // Check if KYC already exists
        if ($tutor->kyc) {
            return redirect()->route('tutor.kyc.show')->with('error', 'You have already submitted your KYC application.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'description' => 'required|string|min:50',
            'profile_photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'hourly_rate' => 'required|numeric|min:1',
            'citizenship_front' => 'required|image|mimes:jpeg,png,jpg,pdf|max:2048',
            'citizenship_back' => 'required|image|mimes:jpeg,png,jpg,pdf|max:2048',
            'qualification' => 'required|string|max:255',
            'qualification_proof' => 'required|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'has_certificate' => 'required|boolean',
            'certificate_file' => 'required_if:has_certificate,1|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'subjects_expertise' => 'required|array|min:1',
            'subjects_expertise.*' => 'string',
            'exact_location' => 'required|string|max:255',
        ]);

        $data = $request->all();
        $data['tutor_id'] = $tutor->id;
        $data['status'] = 'pending';
        $data['submitted_at'] = now();

        // Handle file uploads
        if ($request->hasFile('profile_photo')) {
            $data['profile_photo'] = $request->file('profile_photo')->store('kyc/profile_photos', 'public');
        }

        if ($request->hasFile('citizenship_front')) {
            $data['citizenship_front'] = $request->file('citizenship_front')->store('kyc/citizenship', 'public');
        }

        if ($request->hasFile('citizenship_back')) {
            $data['citizenship_back'] = $request->file('citizenship_back')->store('kyc/citizenship', 'public');
        }

        if ($request->hasFile('qualification_proof')) {
            $data['qualification_proof'] = $request->file('qualification_proof')->store('kyc/qualifications', 'public');
        }

        if ($request->hasFile('certificate_file') && $request->has_certificate) {
            $data['certificate_file'] = $request->file('certificate_file')->store('kyc/certificates', 'public');
        }

        TutorKyc::create($data);

        return redirect()->route('tutor.kyc.show')->with('success', 'Your KYC application has been submitted successfully. We will review it within 2-3 business days.');
    }

    public function edit()
    {
        $tutor = Auth::guard('tutor')->user();
        $kyc = $tutor->kyc;

        if (!$kyc) {
            return redirect()->route('tutor.kyc.create');
        }

        if ($kyc->status === 'approved') {
            return redirect()->route('tutor.kyc.show')->with('info', 'Your KYC is already approved. You cannot edit it.');
        }

        $subjects = [
            'Mathematics',
            'Physics',
            'Chemistry',
            'Biology',
            'English',
            'Computer Science',
            'History',
            'Geography',
            'Economics',
            'Accounting',
            'Business Studies',
            'Psychology',
            'Art',
            'Music',
            'Physical Education',
            'Foreign Languages',
            'Social Studies',
            'Statistics',
            'Philosophy',
            'Engineering'
        ];

        return view('tutor.kyc.edit', compact('kyc', 'subjects'));
    }

    public function update(Request $request)
    {
        $tutor = Auth::guard('tutor')->user();
        $kyc = $tutor->kyc;

        if (!$kyc) {
            return redirect()->route('tutor.kyc.create');
        }

        if ($kyc->status === 'approved') {
            return redirect()->route('tutor.kyc.show')->with('error', 'Your KYC is already approved. You cannot edit it.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'description' => 'required|string|min:50',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'hourly_rate' => 'required|numeric|min:1',
            'citizenship_front' => 'nullable|image|mimes:jpeg,png,jpg,pdf|max:2048',
            'citizenship_back' => 'nullable|image|mimes:jpeg,png,jpg,pdf|max:2048',
            'qualification' => 'required|string|max:255',
            'qualification_proof' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'has_certificate' => 'required|boolean',
            'certificate_file' => 'required_if:has_certificate,1|nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'subjects_expertise' => 'required|array|min:1',
            'subjects_expertise.*' => 'string',
            'exact_location' => 'required|string|max:255',
        ]);

        $data = $request->all();
        $data['status'] = 'pending';
        $data['submitted_at'] = now();
        $data['reviewed_at'] = null;
        $data['rejection_reason'] = null;

        // Handle file uploads
        if ($request->hasFile('profile_photo')) {
            if ($kyc->profile_photo) {
                Storage::disk('public')->delete($kyc->profile_photo);
            }
            $data['profile_photo'] = $request->file('profile_photo')->store('kyc/profile_photos', 'public');
        }

        if ($request->hasFile('citizenship_front')) {
            if ($kyc->citizenship_front) {
                Storage::disk('public')->delete($kyc->citizenship_front);
            }
            $data['citizenship_front'] = $request->file('citizenship_front')->store('kyc/citizenship', 'public');
        }

        if ($request->hasFile('citizenship_back')) {
            if ($kyc->citizenship_back) {
                Storage::disk('public')->delete($kyc->citizenship_back);
            }
            $data['citizenship_back'] = $request->file('citizenship_back')->store('kyc/citizenship', 'public');
        }

        if ($request->hasFile('qualification_proof')) {
            if ($kyc->qualification_proof) {
                Storage::disk('public')->delete($kyc->qualification_proof);
            }
            $data['qualification_proof'] = $request->file('qualification_proof')->store('kyc/qualifications', 'public');
        }

        if ($request->hasFile('certificate_file') && $request->has_certificate) {
            if ($kyc->certificate_file) {
                Storage::disk('public')->delete($kyc->certificate_file);
            }
            $data['certificate_file'] = $request->file('certificate_file')->store('kyc/certificates', 'public');
        } elseif (!$request->has_certificate) {
            if ($kyc->certificate_file) {
                Storage::disk('public')->delete($kyc->certificate_file);
            }
            $data['certificate_file'] = null;
        }

        $kyc->update($data);

        return redirect()->route('tutor.kyc.show')->with('success', 'Your KYC application has been updated successfully.');
    }
}
