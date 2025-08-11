<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\TutorJob;
use App\Models\Tutor;

class TutorJobController extends Controller
{
    /**
     * Display the tutor's jobs listing page.
     */
    public function index()
    {
        $tutor = Auth::guard('tutor')->user();
        $jobs = $tutor->jobs()->latest()->paginate(10);
        
        return view('tutor.jobs.index', compact('jobs'));
    }

    /**
     * Show the form for creating a new job.
     */
    public function create()
    {
        $tutor = Auth::guard('tutor')->user();
        $kyc = $tutor->kyc;
        
        // Check if tutor has approved KYC
        if (!$kyc || $kyc->status !== 'approved') {
            return redirect()->route('tutor.kyc.show')
                ->with('error', 'Please complete and get your KYC approved before posting jobs.');
        }

        // Available subjects
        $subjects = [
            'Mathematics', 'Physics', 'Chemistry', 'Biology', 'English',
            'Computer Science', 'History', 'Geography', 'Economics', 'Accounting',
            'Business Studies', 'Psychology', 'Art', 'Music', 'Physical Education',
            'Foreign Languages', 'Social Studies', 'Statistics', 'Philosophy', 'Engineering'
        ];

        return view('tutor.jobs.create', compact('subjects', 'kyc'));
    }

    /**
     * Store a newly created job in storage.
     */
    public function store(Request $request)
    {
        $tutor = Auth::guard('tutor')->user();
        
        // Check if tutor has approved KYC
        if (!$tutor->kyc || $tutor->kyc->status !== 'approved') {
            return back()->with('error', 'Please complete and get your KYC approved before posting jobs.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:50',
            'subjects' => 'required|array|min:1',
            'subjects.*' => 'required|string',
            'hourly_rate' => 'required|numeric|min:1',
            'country' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'district' => 'required|string|max:100',
            'place' => 'required|string|max:100',
            'landmark' => 'nullable|string|max:255',
            'ward_no' => 'nullable|string|max:50',
            'postal_code' => 'nullable|string|max:20',
            'teaching_mode' => 'required|in:home,online,institute,any',
            'preferred_times' => 'nullable|array',
            'gender_preference' => 'required|in:male,female,any',
            'student_level' => 'nullable|string|max:100',
            'requirements' => 'nullable|string|max:1000',
            'max_students' => 'required|integer|min:1|max:50',
            'session_type' => 'required|in:individual,group,both',
            'gallery' => 'nullable|array|max:5',
            'gallery.*' => 'image|mimes:jpeg,png,jpg|max:2048',
            'expires_at' => 'nullable|date|after:today',
        ]);

        $jobData = $request->except(['gallery']);
        $jobData['tutor_id'] = $tutor->id;
        $jobData['status'] = 'active';

        // Handle gallery upload
        if ($request->hasFile('gallery')) {
            $galleryPaths = [];
            foreach ($request->file('gallery') as $file) {
                $path = $file->store('jobs/gallery', 'public');
                $galleryPaths[] = $path;
            }
            $jobData['gallery'] = $galleryPaths;
        }

        TutorJob::create($jobData);

        return redirect()->route('tutor.jobs.index')
            ->with('success', 'Job posted successfully!');
    }

    /**
     * Display the specified job.
     */
    public function show(TutorJob $job)
    {
        // Check if the job belongs to the authenticated tutor
        if ($job->tutor_id !== Auth::guard('tutor')->id()) {
            abort(403);
        }

        return view('tutor.jobs.show', compact('job'));
    }

    /**
     * Show the form for editing the specified job.
     */
    public function edit(TutorJob $job)
    {
        // Check if the job belongs to the authenticated tutor
        if ($job->tutor_id !== Auth::guard('tutor')->id()) {
            abort(403);
        }

        $subjects = [
            'Mathematics', 'Physics', 'Chemistry', 'Biology', 'English',
            'Computer Science', 'History', 'Geography', 'Economics', 'Accounting',
            'Business Studies', 'Psychology', 'Art', 'Music', 'Physical Education',
            'Foreign Languages', 'Social Studies', 'Statistics', 'Philosophy', 'Engineering'
        ];

        return view('tutor.jobs.edit', compact('job', 'subjects'));
    }

    /**
     * Update the specified job in storage.
     */
    public function update(Request $request, TutorJob $job)
    {
        // Check if the job belongs to the authenticated tutor
        if ($job->tutor_id !== Auth::guard('tutor')->id()) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:50',
            'subjects' => 'required|array|min:1',
            'subjects.*' => 'required|string',
            'hourly_rate' => 'required|numeric|min:1',
            'country' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'district' => 'required|string|max:100',
            'place' => 'required|string|max:100',
            'landmark' => 'nullable|string|max:255',
            'ward_no' => 'nullable|string|max:50',
            'postal_code' => 'nullable|string|max:20',
            'teaching_mode' => 'required|in:home,online,institute,any',
            'preferred_times' => 'nullable|array',
            'gender_preference' => 'required|in:male,female,any',
            'student_level' => 'nullable|string|max:100',
            'requirements' => 'nullable|string|max:1000',
            'max_students' => 'required|integer|min:1|max:50',
            'session_type' => 'required|in:individual,group,both',
            'gallery' => 'nullable|array|max:5',
            'gallery.*' => 'image|mimes:jpeg,png,jpg|max:2048',
            'expires_at' => 'nullable|date|after:today',
            'status' => 'required|in:active,inactive,paused',
        ]);

        $jobData = $request->except(['gallery']);

        // Handle gallery upload
        if ($request->hasFile('gallery')) {
            // Delete old gallery images
            if ($job->gallery) {
                foreach ($job->gallery as $oldPath) {
                    Storage::disk('public')->delete($oldPath);
                }
            }

            $galleryPaths = [];
            foreach ($request->file('gallery') as $file) {
                $path = $file->store('jobs/gallery', 'public');
                $galleryPaths[] = $path;
            }
            $jobData['gallery'] = $galleryPaths;
        }

        $job->update($jobData);

        return redirect()->route('tutor.jobs.index')
            ->with('success', 'Job updated successfully!');
    }

    /**
     * Remove the specified job from storage.
     */
    public function destroy(TutorJob $job)
    {
        // Check if the job belongs to the authenticated tutor
        if ($job->tutor_id !== Auth::guard('tutor')->id()) {
            abort(403);
        }

        // Delete gallery images
        if ($job->gallery) {
            foreach ($job->gallery as $path) {
                Storage::disk('public')->delete($path);
            }
        }

        $job->delete();

        return redirect()->route('tutor.jobs.index')
            ->with('success', 'Job deleted successfully!');
    }

    /**
     * Toggle job status.
     */
    public function toggleStatus(TutorJob $job)
    {
        // Check if the job belongs to the authenticated tutor
        if ($job->tutor_id !== Auth::guard('tutor')->id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $newStatus = $job->status === 'active' ? 'paused' : 'active';
        $job->update(['status' => $newStatus]);

        return response()->json([
            'success' => true,
            'status' => $newStatus,
            'message' => 'Job status updated successfully!'
        ]);
    }

    /**
     * Get job statistics.
     */
    public function getStats()
    {
        $tutor = Auth::guard('tutor')->user();
        
        $stats = [
            'total_jobs' => $tutor->jobs()->count(),
            'active_jobs' => $tutor->jobs()->where('status', 'active')->count(),
            'total_views' => $tutor->jobs()->sum('views'),
            'total_inquiries' => $tutor->jobs()->sum('inquiries'),
        ];

        return response()->json($stats);
    }
}
