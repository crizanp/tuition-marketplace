<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TutorJob;
use App\Models\Tutor;

class JobController extends Controller
{
    /**
     * Display a listing of active jobs.
     */
    public function index(Request $request)
    {
        $query = TutorJob::with(['tutor', 'tutor.kyc'])->active();

        // Apply filters
        if ($request->filled('subject')) {
            $query->bySubject($request->subject);
        }

        if ($request->filled('teaching_mode')) {
            $query->byTeachingMode($request->teaching_mode);
        }

        if ($request->filled('country') || $request->filled('state') || $request->filled('district')) {
            $query->byLocation($request->country, $request->state, $request->district);
        }

        if ($request->filled('min_rate')) {
            $query->where('hourly_rate', '>=', $request->min_rate);
        }

        if ($request->filled('max_rate')) {
            $query->where('hourly_rate', '<=', $request->max_rate);
        }

        if ($request->filled('gender_preference')) {
            $query->where('gender_preference', $request->gender_preference);
        }

        if ($request->filled('session_type')) {
            $query->where('session_type', $request->session_type);
        }

        // Sorting
        $sortBy = $request->get('sort', 'created_at');
        $sortOrder = $request->get('order', 'desc');
        
        if (in_array($sortBy, ['created_at', 'hourly_rate', 'views'])) {
            $query->orderBy($sortBy, $sortOrder);
        }

        $jobs = $query->paginate(12);

        // Get filter options
        $subjects = TutorJob::active()
            ->get()
            ->pluck('subjects')
            ->flatten()
            ->unique()
            ->sort()
            ->values();

        $locations = TutorJob::active()
            ->select('country', 'state', 'district')
            ->distinct()
            ->get()
            ->groupBy('country');

        return view('jobs.index', compact('jobs', 'subjects', 'locations'));
    }

    /**
     * Display the specified job.
     */
    public function show(TutorJob $job)
    {
        // Check if job is active
        if ($job->status !== 'active' || $job->isExpired()) {
            abort(404);
        }

        // Increment views
        $job->incrementViews();

        // Load relationships
        $job->load(['tutor', 'tutor.kyc']);

        // Get related jobs from the same tutor
        $relatedJobs = TutorJob::where('tutor_id', $job->tutor_id)
            ->where('id', '!=', $job->id)
            ->active()
            ->limit(3)
            ->get();

        return view('jobs.show', compact('job', 'relatedJobs'));
    }

    /**
     * Show contact form for inquiring about a job.
     */
    public function contact(TutorJob $job)
    {
        // Check if job is active
        if ($job->status !== 'active' || $job->isExpired()) {
            abort(404);
        }

        $job->load(['tutor', 'tutor.kyc']);

        return view('jobs.contact', compact('job'));
    }

    /**
     * Send inquiry to the tutor.
     */
    public function sendInquiry(Request $request, TutorJob $job)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'message' => 'required|string|min:10|max:1000',
        ]);

        // Increment inquiries count
        $job->incrementInquiries();

        // Here you would typically send an email to the tutor
        // For now, we'll just show a success message

        return redirect()->route('jobs.show', $job)
            ->with('success', 'Your inquiry has been sent successfully! The tutor will contact you soon.');
    }

    /**
     * Search jobs with autocomplete.
     */
    public function search(Request $request)
    {
        $query = $request->get('q');
        
        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $jobs = TutorJob::active()
            ->where(function($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%")
                  ->orWhereJsonContains('subjects', $query)
                  ->orWhere('place', 'like', "%{$query}%")
                  ->orWhere('district', 'like', "%{$query}%");
            })
            ->with(['tutor'])
            ->limit(10)
            ->get()
            ->map(function($job) {
                return [
                    'id' => $job->id,
                    'title' => $job->title,
                    'tutor_name' => $job->tutor->name,
                    'location' => $job->formatted_location,
                    'rate' => number_format((float)$job->hourly_rate, 2),
                    'url' => route('jobs.show', $job),
                ];
            });

        return response()->json($jobs);
    }
}
