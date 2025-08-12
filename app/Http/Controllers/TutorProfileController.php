<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Models\Tutor;
use App\Models\TutorProfile;
use App\Models\TutorKyc;

class TutorProfileController extends Controller
{
    /**
     * Display the tutor's profile page.
     */
    public function index()
    {
        $tutor = Auth::guard('tutor')->user();
        $profile = $tutor->profile;
        $kyc = $tutor->kyc;

        // Create profile if it doesn't exist
        if (!$profile) {
            $profile = TutorProfile::create([
                'tutor_id' => $tutor->id,
            ]);
        }

        return view('tutor.profile.index', compact('tutor', 'profile', 'kyc'));
    }

    /**
     * Update personal information.
     */
    public function updatePersonal(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255|unique:tutors,email,' . Auth::guard('tutor')->id(),
                'phone' => 'nullable|string|max:20',
                'hourly_rate' => 'nullable|numeric|min:0',
            ]);

            $tutor = Auth::guard('tutor')->user();
            $tutor->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'hourly_rate' => $request->hourly_rate,
            ]);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            \Log::error('Personal info update error: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Update about section.
     */
    public function updateAbout(Request $request)
    {
        try {
            $request->validate([
                'bio' => 'required|string|max:1000',
            ]);

            $tutor = Auth::guard('tutor')->user();
            $tutor->update(['bio' => $request->bio]);

            // Also update in profile
            $profile = $tutor->profile ?: TutorProfile::create(['tutor_id' => $tutor->id]);
            $profile->update(['bio' => $request->bio]);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            \Log::error('About update error: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Update skills and subjects.
     */
    public function updateSkills(Request $request)
    {
        try {
            $request->validate([
                'skills' => 'required|array|min:1',
                'skills.*' => 'required|string|max:100',
            ]);

            $tutor = Auth::guard('tutor')->user();
            $profile = $tutor->profile;
            if (!$profile) {
                $profile = TutorProfile::create(['tutor_id' => $tutor->id]);
            }

            // Filter out empty skills
            $skills = array_filter($request->skills, function($skill) {
                return !empty(trim($skill));
            });
            
            $profile->update([
                'skills' => array_values($skills),
            ]);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            \Log::error('Skills update error: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Update education information.
     */
    public function updateEducation(Request $request)
    {
        try {
            $request->validate([
                'qualification' => 'required|string|max:255',
                'institution' => 'nullable|string|max:255',
                'year' => 'nullable|integer|min:1950|max:' . date('Y'),
            ]);

            $tutor = Auth::guard('tutor')->user();
            $profile = $tutor->profile;
            if (!$profile) {
                $profile = TutorProfile::create(['tutor_id' => $tutor->id]);
            }

            $educationData = [
                'qualification' => $request->qualification,
            ];
            
            if ($request->has('institution')) {
                $educationData['institution'] = $request->institution;
            }
            if ($request->has('year')) {
                $educationData['graduation_year'] = $request->year;
            }
            
            $profile->update(['education' => $educationData]);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            \Log::error('Education update error: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Update languages.
     */
    public function updateLanguages(Request $request)
    {
        try {
            $request->validate([
                'languages' => 'required|array|min:1',
                'languages.*.name' => 'required|string|max:100',
                'languages.*.level' => 'required|in:Basic,Intermediate,Advanced,Fluent,Native',
            ]);

            $tutor = Auth::guard('tutor')->user();
            $profile = $tutor->profile;
            if (!$profile) {
                $profile = TutorProfile::create(['tutor_id' => $tutor->id]);
            }

            $languages = [];
            $languagesData = $request->input('languages', []);
            foreach ($languagesData as $language) {
                if (!empty(trim($language['name']))) {
                    $languages[] = [
                        'name' => trim($language['name']),
                        'level' => $language['level'],
                    ];
                }
            }

            $profile->update(['languages' => $languages]);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            \Log::error('Languages update error: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Upload introduction video.
     */
    public function uploadVideo(Request $request)
    {
        try {
            $request->validate([
                'video' => 'required|file|mimes:mp4,avi,mov,wmv|max:5120', // 5MB max
            ]);

            $tutor = Auth::guard('tutor')->user();
            $profile = $tutor->profile ?: TutorProfile::create(['tutor_id' => $tutor->id]);

            // Delete old video if exists
            if ($profile->introduction_video) {
                Storage::disk('public')->delete($profile->introduction_video);
            }

            $videoPath = $request->file('video')->store('profile/videos', 'public');
            $profile->update(['introduction_video' => $videoPath]);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            \Log::error('Video upload error: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Update availability.
     */
    public function updateAvailability(Request $request)
    {
        try {
            $request->validate([
                'status' => 'required|in:available,unavailable',
                'unavailable_until' => 'nullable|date_format:Y-m-d\TH:i',
                'schedule' => 'nullable|array',
                'hourly_availability' => 'nullable|array',
            ]);

            $tutor = Auth::guard('tutor')->user();
            $profile = $tutor->profile;
            if (!$profile) {
                $profile = TutorProfile::create(['tutor_id' => $tutor->id]);
            }

            $updateData = [
                'availability_status' => $request->status,
                'unavailable_until' => $request->status === 'unavailable' ? $request->unavailable_until : null,
            ];

            if ($request->has('schedule')) {
                $updateData['availability_schedule'] = $request->schedule;
            }

            if ($request->has('hourly_availability')) {
                $updateData['hourly_availability'] = $request->hourly_availability;
            }

            $profile->update($updateData);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            \Log::error('Availability update error: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Add portfolio item.
     */
    public function addPortfolio(Request $request)
    {
        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string|max:1000',
                'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'url' => 'nullable|url',
            ]);

            $tutor = Auth::guard('tutor')->user();
            $profile = $tutor->profile ?: TutorProfile::create(['tutor_id' => $tutor->id]);

            $portfolioItems = $profile->portfolio_items ?: [];

            $portfolioItem = [
                'id' => uniqid(),
                'title' => $request->title,
                'description' => $request->description,
                'url' => $request->url,
                'created_at' => now()->toISOString(),
            ];

            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('profile/portfolio', 'public');
                $portfolioItem['image'] = $imagePath;
            }

            $portfolioItems[] = $portfolioItem;
            $profile->update(['portfolio_items' => $portfolioItems]);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            \Log::error('Portfolio add error: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Add certification.
     */
    public function addCertification(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'issuer' => 'nullable|string|max:255',
                'date' => 'nullable|date',
                'file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            ]);

            $tutor = Auth::guard('tutor')->user();
            $profile = $tutor->profile ?: TutorProfile::create(['tutor_id' => $tutor->id]);

            $certifications = $profile->additional_certifications ?: [];

            $certification = [
                'id' => uniqid(),
                'name' => $request->name,
                'issuer' => $request->issuer,
                'date' => $request->date,
                'created_at' => now()->toISOString(),
            ];

            if ($request->hasFile('file')) {
                $filePath = $request->file('file')->store('profile/certifications', 'public');
                $certification['file'] = $filePath;
            }

            $certifications[] = $certification;
            $profile->update(['additional_certifications' => $certifications]);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            \Log::error('Certification add error: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Share profile URL.
     */
    public function share()
    {
        $tutor = Auth::guard('tutor')->user();
        $url = route('tutor.profile.public', $tutor->id);

        return response()->json([
            'success' => true,
            'url' => $url,
        ]);
    }

    /**
     * Preview profile.
     */
    public function preview()
    {
        $tutor = Auth::guard('tutor')->user();
        $profile = $tutor->profile;
        $kyc = $tutor->kyc;
        
        return view('tutor.profile.preview', compact('tutor', 'profile', 'kyc'));
    }

    /**
     * Display public profile.
     */
    public function publicProfile($id)
    {
        $tutor = Tutor::with(['kyc', 'profile', 'jobs' => function($query) {
            $query->active()->latest()->limit(6);
        }])->findOrFail($id);

        // Increment profile views
        if ($tutor->profile) {
            $tutor->profile->increment('profile_views');
        }

        $tutorJobs = $tutor->jobs;
        return view('tutor.profile.public', compact('tutor', 'tutorJobs'));
    }

    /**
     * Get profile statistics.
     */
    public function getStats()
    {
        $tutor = Auth::guard('tutor')->user();
        $profile = $tutor->profile;
        $kyc = $tutor->kyc;

        $stats = [
            'profile_completion' => $this->calculateProfileCompletion($tutor, $profile, $kyc),
            'profile_views' => $profile ? $profile->profile_views : 0,
            'total_students' => $profile ? $profile->total_students : 0,
            'total_hours' => $profile ? $profile->total_hours : 0,
            'rating' => $profile ? $profile->rating : 0,
            'total_ratings' => $profile ? $profile->total_ratings : 0,
        ];

        return response()->json($stats);
    }

    /**
     * Calculate profile completion percentage.
     */
    private function calculateProfileCompletion($tutor, $profile, $kyc)
    {
        $fields = [
            'basic_info' => !empty($tutor->name) && !empty($tutor->email),
            'phone' => !empty($tutor->phone),
            'hourly_rate' => !empty($tutor->hourly_rate),
            'bio' => !empty($tutor->bio),
            'kyc_approved' => $kyc && $kyc->status === 'approved',
            'profile_photo' => $kyc && !empty($kyc->profile_photo),
            'subjects' => $kyc && !empty($kyc->subjects_expertise),
            'qualification' => $kyc && !empty($kyc->qualification),
            'languages' => $profile && !empty($profile->languages),
            'availability' => $profile && !empty($profile->availability_schedule),
        ];

        $completed = array_filter($fields);
        return round((count($completed) / count($fields)) * 100);
    }

    /**
     * Rate a tutor
     */
    public function rate(Request $request, Tutor $tutor)
    {
        try {
            $request->validate([
                'rating' => 'required|integer|min:1|max:5',
                'review' => 'nullable|string|max:1000',
                'job_id' => 'nullable|exists:tutor_jobs,id'
            ]);

            $user = auth()->user();
            
            // Check if user has already rated this tutor
            $existingRating = $user->ratings()->where('tutor_id', $tutor->id)->first();
            
            if ($existingRating) {
                // Update existing rating
                $existingRating->update([
                    'rating' => $request->rating,
                    'review' => $request->review,
                    'job_id' => $request->job_id
                ]);
                $message = 'Rating updated successfully';
            } else {
                // Create new rating
                $user->ratings()->create([
                    'tutor_id' => $tutor->id,
                    'rating' => $request->rating,
                    'review' => $request->review,
                    'job_id' => $request->job_id
                ]);
                $message = 'Rating submitted successfully';
            }

            // Update tutor's profile with new average rating
            $this->updateTutorRating($tutor);

            return response()->json([
                'success' => true,
                'message' => $message
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error submitting rating: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update tutor's average rating
     */
    private function updateTutorRating(Tutor $tutor)
    {
        $ratings = $tutor->ratings;
        $averageRating = $ratings->avg('rating');
        $totalRatings = $ratings->count();

        $profile = $tutor->profile;
        if ($profile) {
            $profile->update([
                'rating' => round($averageRating, 1),
                'total_ratings' => $totalRatings
            ]);
        }
    }
}
