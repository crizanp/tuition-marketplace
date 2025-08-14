<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TutorJob;
use App\Models\Tutor;
use App\Models\ContactMessage;
use App\Models\Rating;
use Illuminate\Support\Str;

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

        // Default: Hide unverified tutors unless show_all is requested
        $showAll = $request->get('show_all', false);
        
        if (!$showAll && !$request->filled('verification_status')) {
            // By default, only show verified tutors
            $query->whereHas('tutor.kyc', function($q) {
                $q->where('status', 'approved');
            });
        }

        // Verification Status Filter (overrides default)
        if ($request->filled('verification_status')) {
            if ($request->verification_status === 'verified') {
                $query->whereHas('tutor.kyc', function($q) {
                    $q->where('status', 'approved');
                });
            } elseif ($request->verification_status === 'non_verified') {
                $query->whereDoesntHave('tutor.kyc', function($q) {
                    $q->where('status', 'approved');
                });
            }
        }

        // Views Sort Filter
        if ($request->filled('views_sort')) {
            if ($request->views_sort === 'highest') {
                $query->orderBy('views', 'desc');
            } elseif ($request->views_sort === 'lowest') {
                $query->orderBy('views', 'asc');
            }
        }

        // Default Sorting (only apply if views_sort is not set)
        if (!$request->filled('views_sort')) {
            $sortBy = $request->get('sort', 'created_at');
            $sortOrder = $request->get('order', 'desc');
            
            if (in_array($sortBy, ['created_at', 'hourly_rate', 'views'])) {
                $query->orderBy($sortBy, $sortOrder);
            }
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

        // Get wishlist job IDs for the logged-in user
        $wishlistJobIds = [];
        if (auth()->check()) {
            $wishlistJobIds = auth()->user()->wishlist()->pluck('tutor_job_id')->toArray();
        }

        return view('jobs.index', compact('jobs', 'subjects', 'locations', 'showAll', 'wishlistJobIds'));
    }

    /**
     * Display the specified job.
     */
    public function show($tutorName, $jobId)
    {
        // Find job by ID and verify tutor name matches
        $job = TutorJob::with(['tutor', 'tutor.kyc', 'tutor.profile'])->findOrFail($jobId);
        
        // Create URL-friendly tutor name and compare
        $urlFriendlyName = Str::slug($job->tutor->name);
        if ($urlFriendlyName !== $tutorName) {
            abort(404);
        }

        // Check if job is active
        if ($job->status !== 'active' || $job->isExpired()) {
            abort(404);
        }

        // Increment views
        $job->incrementViews();

        // Get related jobs from the same tutor
        $relatedJobs = TutorJob::where('tutor_id', $job->tutor_id)
            ->where('id', '!=', $job->id)
            ->active()
            ->with('tutor')
            ->limit(3)
            ->get();

        // Get ratings for this specific job
        $ratings = Rating::where('job_id', $job->id)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Calculate average rating for this specific job
        $averageRating = Rating::where('job_id', $job->id)->avg('rating');
        $totalRatings = Rating::where('job_id', $job->id)->count();

        // Check if current user has already rated this specific job
        $userHasRated = false;
        $userRating = null;
        if (auth()->check()) {
            $userRating = Rating::where('user_id', auth()->id())
                ->where('job_id', $job->id)
                ->first();
            $userHasRated = $userRating ? true : false;
        }

        return view('jobs.show', compact('job', 'relatedJobs', 'ratings', 'averageRating', 'totalRatings', 'userHasRated', 'userRating'));
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

        // Check if user is authenticated and has already sent a message for this job
        if (auth()->check()) {
            $existingMessage = ContactMessage::where('student_id', auth()->id())
                ->where('job_id', $job->id)
                ->first();
                
            if ($existingMessage) {
                return redirect()->back()
                    ->with('error', 'You have already sent a message for this job. Please wait for the response.');
            }
        } else {
            // For guest users, check by email
            $existingMessage = ContactMessage::where('email', $request->email)
                ->where('job_id', $job->id)
                ->first();
                
            if ($existingMessage) {
                return redirect()->back()
                    ->with('error', 'A message has already been sent from this email address for this job.');
            }
        }

        // Save the contact message to database
        ContactMessage::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'message' => $request->message,
            'job_id' => $job->id,
            'tutor_id' => $job->tutor_id,
            'student_id' => auth()->id(), // null if guest
            'type' => 'job_inquiry',
            'status' => 'unread'
        ]);

        // Increment inquiries count
        $job->incrementInquiries();

        return redirect()->route('jobs.show', [
            'tutorName' => Str::slug($job->tutor->name),
            'jobId' => $job->id
        ])->with('success', 'Your inquiry has been sent successfully! The admin will review your message and forward it to the tutor.');
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

    /**
     * Toggle job in user's wishlist
     */
    public function toggleWishlist(Request $request, TutorJob $job)
    {
        try {
            $user = auth()->user();
            
            // Check if job is already in wishlist
            $wishlistItem = $user->wishlist()->where('tutor_job_id', $job->id)->first();
            
            if ($wishlistItem) {
                // Remove from wishlist
                $wishlistItem->delete();
                return response()->json([
                    'success' => true,
                    'added' => false,
                    'message' => 'Removed from wishlist'
                ]);
            } else {
                // Add to wishlist
                $user->wishlist()->create([
                    'tutor_job_id' => $job->id
                ]);
                return response()->json([
                    'success' => true,
                    'added' => true,
                    'message' => 'Added to wishlist'
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating wishlist: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display user's wishlist
     */
    public function wishlist()
    {
        $user = auth()->user();
        $wishlistJobs = TutorJob::whereHas('wishlistedBy', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })->with(['tutor', 'tutor.kyc'])->paginate(12);

        return view('wishlist.index', compact('wishlistJobs'));
    }

    /**
     * Store a rating for a job
     */
    public function storeRating(Request $request, TutorJob $job)
    {
        if (!auth()->check()) {
            return redirect()->route('student.login')->with('error', 'Please login to submit a rating.');
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:1000'
        ]);

        $user = auth()->user();

        // Check if user has already rated this specific job
        $existingJobRating = Rating::where('user_id', $user->id)
            ->where('job_id', $job->id)
            ->first();

        if ($existingJobRating) {
            // Update the existing rating for this job
            $existingJobRating->update([
                'rating' => $request->rating,
                'review' => $request->review
            ]);

            return redirect()->back()
                ->with('success', 'Your rating has been updated for this job!');
        } else {
            // Create new rating for this job
            Rating::create([
                'user_id' => $user->id,
                'tutor_id' => $job->tutor_id,
                'job_id' => $job->id,
                'rating' => $request->rating,
                'review' => $request->review
            ]);

            return redirect()->back()
                ->with('success', 'Thank you for your rating! Your feedback has been submitted.');
        }
    }
}
