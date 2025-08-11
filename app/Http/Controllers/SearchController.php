<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tutor;
use App\Models\TutorProfile;
use App\Models\StudentVacancy;
use App\Models\TutorJob;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    /**
     * Get live search suggestions for subjects
     */
    public function getSubjectSuggestions(Request $request)
    {
        $query = $request->get('q');
        
        if (strlen($query) < 2) {
            return response()->json([]);
        }

        // Get subjects from various sources
        $subjects = collect();
        
        // From tutor profiles (skills column)
        $tutorSkills = TutorProfile::whereNotNull('skills')
            ->get()
            ->pluck('skills')
            ->flatten()
            ->filter(function($skill) use ($query) {
                return stripos($skill, $query) !== false;
            });
        
        // From student vacancies
        $vacancySubjects = StudentVacancy::where('subject', 'LIKE', "%{$query}%")
            ->pluck('subject');
        
        // From tutor jobs
        $jobSubjects = TutorJob::where('subject', 'LIKE', "%{$query}%")
            ->pluck('subject');
        
        // Predefined subjects
        $predefinedSubjects = collect([
            'Mathematics', 'Physics', 'Chemistry', 'Biology', 'English', 
            'Nepali', 'Science', 'Social Studies', 'Computer Science', 
            'Accountancy', 'Economics', 'Business Studies', 'History', 
            'Geography', 'Psychology', 'Sociology', 'Philosophy',
            'Statistics', 'Management', 'Law', 'Engineering', 'Medicine'
        ])->filter(function($subject) use ($query) {
            return stripos($subject, $query) !== false;
        });

        $subjects = $subjects->merge($tutorSkills)
                           ->merge($vacancySubjects)
                           ->merge($jobSubjects)
                           ->merge($predefinedSubjects)
                           ->unique()
                           ->values()
                           ->take(10);

        return response()->json($subjects);
    }

    /**
     * Get live search suggestions for locations
     */
    public function getLocationSuggestions(Request $request)
    {
        $query = $request->get('q');
        
        if (strlen($query) < 2) {
            return response()->json([]);
        }

        // Get locations from student vacancy addresses
        $locations = collect();
        
        // From student vacancies
        $vacancyLocations = StudentVacancy::where('address', 'LIKE', "%{$query}%")
            ->pluck('address')
            ->filter()
            ->map(function($address) {
                // Extract city/area from address
                $parts = explode(',', $address);
                return trim(end($parts));
            });
        
        // Predefined locations (major cities in Nepal)
        $predefinedLocations = collect([
            'Kathmandu', 'Lalitpur', 'Bhaktapur', 'Pokhara', 'Chitwan', 'Butwal',
            'Dharan', 'Biratnagar', 'Janakpur', 'Nepalgunj', 'Hetauda', 'Itahari',
            'Dhulikhel', 'Gorkha', 'Narayanghat', 'Tandi', 'Bandipur', 'Ghandruk',
            'Banke', 'Kanchanpur', 'Jhapa', 'Morang', 'Sunsari', 'Siraha'
        ])->filter(function($location) use ($query) {
            return stripos($location, $query) !== false;
        });

        $locations = $locations->merge($vacancyLocations)
                              ->merge($predefinedLocations)
                              ->unique()
                              ->values()
                              ->take(10);

        return response()->json($locations);
    }

    /**
     * Search tutor jobs based on criteria - Flexible search returning jobs as results
     */
    public function searchTutors(Request $request)
    {
        $keyword = $request->input('keyword');
        $subject = $request->input('subject');
        $location = $request->input('location');
        $experience = $request->input('experience');
        $rating = $request->input('rating');
        $hourlyRate = $request->input('hourly_rate');
        
        $query = TutorJob::query()
            ->with(['tutor.profile', 'tutor.kyc'])
            ->whereHas('tutor', function($tutorQuery) {
                $tutorQuery->where('status', 'active')
                          ->whereNotNull('email_verified_at');
            });
        
        // Apply flexible filters - if any criteria matches, include the job
        if ($keyword || $subject || $location) {
            $query->where(function($mainQuery) use ($keyword, $subject, $location) {
                
                // Search in job info
                if ($keyword) {
                    $mainQuery->orWhere(function($q) use ($keyword) {
                        $q->where('title', 'LIKE', "%{$keyword}%")
                          ->orWhere('description', 'LIKE', "%{$keyword}%")
                          ->orWhere('requirements', 'LIKE', "%{$keyword}%")
                          ->orWhereJsonContains('subjects', $keyword);
                    });
                }
                
                // Search by subject in job subjects
                if ($subject) {
                    $mainQuery->orWhereJsonContains('subjects', $subject);
                }
                
                // Search by location in job location fields
                if ($location) {
                    $mainQuery->orWhere(function($q) use ($location) {
                        $q->where('state', 'LIKE', "%{$location}%")
                          ->orWhere('district', 'LIKE', "%{$location}%")
                          ->orWhere('place', 'LIKE', "%{$location}%")
                          ->orWhere('landmark', 'LIKE', "%{$location}%")
                          ->orWhere('country', 'LIKE', "%{$location}%");
                    });
                }
                
                // Search in tutor info if keyword provided
                if ($keyword) {
                    $mainQuery->orWhereHas('tutor', function($tutorQuery) use ($keyword) {
                        $tutorQuery->where('name', 'LIKE', "%{$keyword}%")
                                 ->orWhere('email', 'LIKE', "%{$keyword}%")
                                 ->orWhere('bio', 'LIKE', "%{$keyword}%")
                                 ->orWhereJsonContains('subjects', $keyword);
                    });
                }
                
                // Search in tutor profile if keyword provided
                if ($keyword) {
                    $mainQuery->orWhereHas('tutor.profile', function($profileQuery) use ($keyword) {
                        $profileQuery->where('bio', 'LIKE', "%{$keyword}%")
                                   ->orWhereJsonContains('skills', $keyword);
                    });
                }
                
                // Search by subject in tutor profile skills or tutor subjects
                if ($subject) {
                    $mainQuery->orWhereHas('tutor', function($tutorQuery) use ($subject) {
                        $tutorQuery->whereJsonContains('subjects', $subject);
                    });
                    
                    $mainQuery->orWhereHas('tutor.profile', function($profileQuery) use ($subject) {
                        $profileQuery->whereJsonContains('skills', $subject);
                    });
                }
            });
        }
        
        // Additional filters (optional)
        if ($rating) {
            $query->whereHas('tutor.profile', function($profileQuery) use ($rating) {
                $profileQuery->where('rating', '>=', $rating);
            });
        }
        
        if ($hourlyRate) {
            $query->where('hourly_rate', '<=', $hourlyRate);
        }
        
        // Order by created_at desc to show newest jobs first
        $jobs = $query->orderBy('created_at', 'desc')->paginate(12);
        
        return view('search.tutors', compact(
            'jobs', 'keyword', 'subject', 'location', 
            'experience', 'rating', 'hourlyRate'
        ));
    }
    
    public function searchVacancies(Request $request)
    {
        $keyword = $request->input('keyword');
        $subject = $request->input('subject');
        $location = $request->input('location');
        $grade = $request->input('grade');
        $budget = $request->input('budget');
        
        $query = StudentVacancy::query()
            ->with(['student'])
            ->where('status', 'approved');
        
        // Apply filters
        if ($keyword) {
            $query->where(function($q) use ($keyword) {
                $q->where('title', 'LIKE', "%{$keyword}%")
                  ->orWhere('description', 'LIKE', "%{$keyword}%")
                  ->orWhere('subject', 'LIKE', "%{$keyword}%")
                  ->orWhereHas('student', function($studentQuery) use ($keyword) {
                      $studentQuery->where('name', 'LIKE', "%{$keyword}%");
                  });
            });
        }
        
        if ($subject) {
            $query->where('subject', 'LIKE', "%{$subject}%");
        }
        
        if ($location) {
            $query->where(function($q) use ($location) {
                $q->where('address', 'LIKE', "%{$location}%")
                  ->orWhere('location_type', 'LIKE', "%{$location}%");
            });
        }
        
        if ($grade) {
            $query->where('grade_level', 'LIKE', "%{$grade}%");
        }
        
        if ($budget) {
            $query->where('budget_max', '>=', $budget);
        }
        
        $vacancies = $query->orderBy('created_at', 'desc')->paginate(12);
        
        if ($request->ajax()) {
            return response()->json([
                'vacancies' => $vacancies->items(),
                'pagination' => [
                    'current_page' => $vacancies->currentPage(),
                    'last_page' => $vacancies->lastPage(),
                    'total' => $vacancies->total()
                ]
            ]);
        }
        
        return view('search.vacancies', compact(
            'vacancies', 'keyword', 'subject', 'location', 
            'grade', 'budget'
        ));
    }
}
