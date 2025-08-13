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
     * Get live search suggestions for subjects (removed predefined - backend only)
     */
    public function getSubjectSuggestions(Request $request)
    {
        $query = $request->get('q');
        
        if (strlen($query) < 2) {
            return response()->json([]);
        }

        // Get subjects ONLY from backend data
        $subjects = collect();
        
        // From tutor profiles (skills column)
        $tutorSkills = TutorProfile::whereNotNull('skills')
            ->get()
            ->pluck('skills')
            ->flatten()
            ->filter(function($skill) use ($query) {
                return $this->flexibleMatch($skill, $query);
            });
        
        // From student vacancies
        $vacancySubjects = StudentVacancy::where('subject', 'LIKE', "%{$query}%")
            ->pluck('subject')
            ->filter(function($subject) use ($query) {
                return $this->flexibleMatch($subject, $query);
            });
        
        // From tutor jobs
        $jobSubjects = TutorJob::where('subject', 'LIKE', "%{$query}%")
            ->pluck('subject')
            ->filter(function($subject) use ($query) {
                return $this->flexibleMatch($subject, $query);
            });

        $subjects = $subjects->merge($tutorSkills)
                           ->merge($vacancySubjects)
                           ->merge($jobSubjects)
                           ->unique()
                           ->values()
                           ->take(10);

        return response()->json($subjects);
    }

    /**
     * Ultra-flexible matching that handles typos, partial words, and variations
     */
    private function flexibleMatch($text, $query)
    {
        if (empty($text) || empty($query)) {
            return false;
        }
        
        $text = strtolower(trim($text));
        $query = strtolower(trim($query));
        
        // Exact match
        if (strpos($text, $query) !== false) {
            return true;
        }
        
        // Handle common variations and typos
        $variations = $this->getQueryVariations($query);
        foreach ($variations as $variation) {
            if (strpos($text, $variation) !== false) {
                return true;
            }
        }
        
        // Partial word matching (for words > 3 chars)
        if (strlen($query) > 3) {
            $partialLength = max(3, (int)(strlen($query) * 0.7));
            $partial = substr($query, 0, $partialLength);
            if (strpos($text, $partial) !== false) {
                return true;
            }
        }
        
        // Character overlap for typo tolerance
        if (strlen($query) >= 4) {
            $words = explode(' ', $text);
            foreach ($words as $word) {
                if ($this->calculateSimilarity($word, $query) > 0.7) {
                    return true;
                }
            }
        }
        
        return false;
    }
    
    /**
     * Get query variations including common typos and abbreviations
     */
    private function getQueryVariations($query)
    {
        $variations = [$query];
        
        // Common subject abbreviations and variations
        $commonVariations = [
            'math' => ['mathematics', 'maths'],
            'mathematics' => ['math', 'maths'],
            'maths' => ['math', 'mathematics'],
            'eng' => ['english'],
            'english' => ['eng'],
            'sci' => ['science'],
            'science' => ['sci'],
            'phy' => ['physics'],
            'physics' => ['phy'],
            'chem' => ['chemistry'],
            'chemistry' => ['chem'],
            'bio' => ['biology'],
            'biology' => ['bio'],
            'comp' => ['computer'],
            'computer' => ['comp'],
            'cs' => ['computer science'],
            'kathmandu' => ['ktm', 'ktm valley'],
            'ktm' => ['kathmandu'],
            'pokhara' => ['pkr'],
            'pkr' => ['pokhara'],
        ];
        
        if (isset($commonVariations[$query])) {
            $variations = array_merge($variations, $commonVariations[$query]);
        }
        
        // Add reverse lookup
        foreach ($commonVariations as $key => $values) {
            if (in_array($query, $values) && !in_array($key, $variations)) {
                $variations[] = $key;
            }
        }
        
        return array_unique($variations);
    }
    
    /**
     * Calculate similarity between two strings (0-1 scale)
     */
    private function calculateSimilarity($str1, $str2)
    {
        $str1 = strtolower(trim($str1));
        $str2 = strtolower(trim($str2));
        
        if ($str1 === $str2) return 1.0;
        if (strlen($str1) < 2 || strlen($str2) < 2) return 0.0;
        
        $len1 = strlen($str1);
        $len2 = strlen($str2);
        $maxLen = max($len1, $len2);
        $minLen = min($len1, $len2);
        
        // Too different in length
        if (($maxLen - $minLen) > 3) return 0.0;
        
        // Count matching characters
        $matches = 0;
        $shorter = $len1 <= $len2 ? $str1 : $str2;
        $longer = $len1 > $len2 ? $str1 : $str2;
        
        for ($i = 0; $i < strlen($shorter); $i++) {
            if (strpos($longer, $shorter[$i]) !== false) {
                $matches++;
            }
        }
        
        return $matches / strlen($shorter);
    }

    /**
     * Get live search suggestions for locations (removed predefined - backend only)
     */
    public function getLocationSuggestions(Request $request)
    {
        $query = $request->get('q');
        
        if (strlen($query) < 2) {
            return response()->json([]);
        }

        // Get locations ONLY from backend data
        $locations = collect();
        
        // From student vacancies
        $vacancyLocations = StudentVacancy::where('address', 'LIKE', "%{$query}%")
            ->pluck('address')
            ->filter()
            ->map(function($address) use ($query) {
                // Extract city/area from address and check if it matches flexibly
                $parts = explode(',', $address);
                foreach ($parts as $part) {
                    $part = trim($part);
                    if ($this->flexibleMatch($part, $query) && strlen($part) > 2) {
                        return $part;
                    }
                }
                return null;
            })
            ->filter();

        $locations = $locations->merge($vacancyLocations)
                              ->unique()
                              ->values()
                              ->take(10);

        return response()->json($locations);
    }

    /**
     * Get live search suggestions for districts (removed predefined - backend only)
     */
    public function getDistrictSuggestions(Request $request)
    {
        $query = $request->get('q');
        
        if (strlen($query) < 2) {
            return response()->json([]);
        }

        // Get districts ONLY from backend data
        $districts = collect();
        
        // From tutor jobs
        $jobDistricts = TutorJob::where('district', 'LIKE', "%{$query}%")
            ->whereNotNull('district')
            ->pluck('district')
            ->filter(function($district) use ($query) {
                return $this->flexibleMatch($district, $query);
            });
        
        // From student vacancies - extract district from address
        $vacancyDistricts = StudentVacancy::where('address', 'LIKE', "%{$query}%")
            ->pluck('address')
            ->filter()
            ->map(function($address) use ($query) {
                // Try to extract district-like parts from address
                $parts = explode(',', $address);
                foreach ($parts as $part) {
                    $part = trim($part);
                    if ($this->flexibleMatch($part, $query) && strlen($part) > 3) {
                        return $part;
                    }
                }
                return null;
            })
            ->filter();

        $districts = $districts->merge($jobDistricts)
                              ->merge($vacancyDistricts)
                              ->unique()
                              ->values()
                              ->take(10);

        return response()->json($districts);
    }

    /**
     * Get live search suggestions for places (removed predefined - backend only)
     */
    public function getPlaceSuggestions(Request $request)
    {
        $query = $request->get('q');
        
        if (strlen($query) < 2) {
            return response()->json([]);
        }

        // Get places ONLY from backend data
        $places = collect();
        
        // From tutor jobs
        $jobPlaces = TutorJob::where(function($q) use ($query) {
                $q->where('place', 'LIKE', "%{$query}%")
                  ->orWhere('landmark', 'LIKE', "%{$query}%")
                  ->orWhere('state', 'LIKE', "%{$query}%");
            })
            ->get()
            ->flatMap(function($job) use ($query) {
                $places = collect([$job->place, $job->landmark, $job->state])->filter();
                return $places->filter(function($place) use ($query) {
                    return $this->flexibleMatch($place, $query);
                });
            });
        
        // From student vacancies - extract specific places from address
        $vacancyPlaces = StudentVacancy::where('address', 'LIKE', "%{$query}%")
            ->pluck('address')
            ->filter()
            ->flatMap(function($address) use ($query) {
                // Split address and find parts that match query
                $parts = preg_split('/[,\-\s]+/', $address);
                return collect($parts)->filter(function($part) use ($query) {
                    $part = trim($part);
                    return strlen($part) > 2 && $this->flexibleMatch($part, $query);
                });
            });

        $places = $places->merge($jobPlaces)
                         ->merge($vacancyPlaces)
                         ->unique()
                         ->values()
                         ->take(10);

        return response()->json($places);
    }

    /**
     * Ultra-flexible search with priority-based ranking:
     * Priority 1: Location + Keyword match (100+ points)
     * Priority 2: Keyword only (partial/zigzag matching like "eng" for "english") (50-90 points)
     * Priority 3: Location only (20-40 points)
     * Handles typos, abbreviations, and partial matches
     */
    public function searchTutors(Request $request)
    {
        $keyword = trim($request->input('keyword'));
        $district = trim($request->input('district'));
        $place = trim($request->input('place'));
        
        // Keep backward compatibility with old 'location' parameter
        $location = trim($request->input('location'));
        if ($location && !$district && !$place) {
            $district = $location;
        }
        
        // Base query with active tutors only
        $baseQuery = TutorJob::query()
            ->with(['tutor.profile', 'tutor.kyc'])
            ->whereHas('tutor', function($tutorQuery) {
                $tutorQuery->where('status', 'active')
                          ->whereNotNull('email_verified_at');
            })
            ->where('status', 'active')
            ->where(function($query) {
                $query->whereNull('expires_at')
                      ->orWhere('expires_at', '>', now());
            });
        
        // If no search terms, return recent jobs
        if (empty($keyword) && empty($district) && empty($place)) {
            $jobs = $baseQuery->orderBy('created_at', 'desc')->paginate(12);
            $subject = null;
            return view('search.tutors', compact('jobs', 'keyword', 'district', 'place', 'location', 'subject'));
        }
        
        // Get all potential matches with ultra-flexible criteria
        $allJobs = $baseQuery->get();
        $scoredResults = collect();
        
        // Score each job based on priority system
        foreach ($allJobs as $job) {
            $score = $this->calculateFlexibleScore($job, $keyword, $district, $place);
            
            if ($score > 0) {
                $job->search_score = $score;
                $job->search_reason = $this->getMatchReason($job, $keyword, $district, $place);
                $scoredResults->push($job);
            }
        }
        
        // Sort by priority score (highest first)
        $sortedResults = $scoredResults->sortByDesc('search_score');
        
        // If we have very few high-priority results, add more flexible matches
        if ($sortedResults->where('search_score', '>=', 50)->count() < 5) {
            $extraJobs = $this->getVeryFlexibleMatches($baseQuery, $keyword, $district, $place, $sortedResults->pluck('id'));
            foreach ($extraJobs as $job) {
                $job->search_score = 15; // Very low priority score
                $job->search_reason = 'Broad match';
                $sortedResults->push($job);
            }
            $sortedResults = $sortedResults->sortByDesc('search_score');
        }
        
        // Convert to paginated results
        $currentPage = (int) $request->get('page', 1);
        $perPage = 12;
        $slicedResults = $sortedResults->forPage($currentPage, $perPage);
        
        $jobs = new \Illuminate\Pagination\LengthAwarePaginator(
            $slicedResults,
            $sortedResults->count(),
            $perPage,
            $currentPage,
            [
                'path' => request()->url(),
                'query' => request()->query()
            ]
        );
        
        $subject = null; // For backward compatibility
        return view('search.tutors', compact('jobs', 'keyword', 'district', 'place', 'location', 'subject'));
    }
    
    /**
     * Calculate flexible score based on priority system
     */
    private function calculateFlexibleScore($job, $keyword, $district, $place)
    {
        $keywordScore = 0;
        $locationScore = 0;
        $hasKeywordMatch = false;
        $hasLocationMatch = false;
        
        // KEYWORD SCORING (0-90 points) - Very flexible matching
        if (!empty($keyword)) {
            $keywordScore = $this->scoreFlexibleKeyword($job, $keyword);
            $hasKeywordMatch = $keywordScore > 0;
        }
        
        // LOCATION SCORING (0-40 points) - District + Place combined
        if (!empty($district) || !empty($place)) {
            $locationScore = $this->scoreFlexibleLocation($job, $district, $place);
            $hasLocationMatch = $locationScore > 0;
        }
        
        // PRIORITY-BASED FINAL SCORING
        $finalScore = 0;
        
        // PRIORITY 1: Both keyword and location match (100+ points)
        if ($hasKeywordMatch && $hasLocationMatch) {
            $finalScore = 100 + $keywordScore + $locationScore;
            
            // Extra bonus for strong matches
            if ($keywordScore > 60 && $locationScore > 20) {
                $finalScore += 20; // Premium match bonus
            }
        }
        // PRIORITY 2: Keyword only (50-90 points)
        elseif ($hasKeywordMatch && !$hasLocationMatch) {
            $finalScore = 50 + $keywordScore;
        }
        // PRIORITY 3: Location only (20-40 points)
        elseif (!$hasKeywordMatch && $hasLocationMatch) {
            $finalScore = 20 + $locationScore;
        }
        
        // QUALITY BONUSES
        if ($finalScore > 0) {
            // Freshness bonus
            $daysSinceCreated = now()->diffInDays($job->created_at);
            if ($daysSinceCreated <= 7) {
                $finalScore += 5;
            }
            
            // Tutor verification bonus
            if ($job->tutor && $job->tutor->kyc && $job->tutor->kyc->status === 'approved') {
                $finalScore += 3;
            }
        }
        
        return max(0, $finalScore);
    }
    
    /**
     * Score keyword matches with ultra-flexible matching (handles typos, abbreviations, zigzag patterns)
     */
    private function scoreFlexibleKeyword($job, $keyword)
    {
        $score = 0;
        $keyword = strtolower(trim($keyword));
        
        // Generate all possible variations of the keyword
        $keywordVariations = $this->generateKeywordVariations($keyword);
        
        // Count how many words from the search query are found
        $searchWords = explode(' ', $keyword);
        $matchedWords = 0;
        
        // Title matches (highest priority - 30 points max)
        $titleMatches = 0;
        foreach ($keywordVariations as $variation) {
            if ($this->ultraFlexibleMatch($job->title, $variation)) {
                $titleMatches++;
                $score += 25;
                if ($this->isExactMatch($job->title, $variation)) {
                    $score += 5; // Exact match bonus
                }
                
                // Count matched words for multi-word bonus
                foreach ($searchWords as $searchWord) {
                    if ($this->ultraFlexibleMatch($job->title, $searchWord)) {
                        $matchedWords++;
                    }
                }
                break; // Only count once per field
            }
        }
        
        // Special bonus for programming-related titles
        if (strpos($keyword, 'programming') !== false || strpos($keyword, 'coding') !== false) {
            if (strpos(strtolower($job->title), 'computer') !== false && strpos(strtolower($job->title), 'programming') !== false) {
                $score += 15; // Big bonus for "Computer Programming" matches
            }
        }
        
        // Subject matches (high priority - 25 points max)
        if ($job->subjects) {
            $subjectsText = is_array($job->subjects) ? implode(' ', $job->subjects) : $job->subjects;
            foreach ($keywordVariations as $variation) {
                if ($this->ultraFlexibleMatch($subjectsText, $variation)) {
                    $score += 20;
                    if ($this->isExactMatch($subjectsText, $variation)) {
                        $score += 5; // Exact match bonus
                    }
                    
                    // Count matched words
                    foreach ($searchWords as $searchWord) {
                        if ($this->ultraFlexibleMatch($subjectsText, $searchWord)) {
                            $matchedWords++;
                        }
                    }
                    break;
                }
            }
        }
        
        // Description matches (medium priority - 20 points max)
        foreach ($keywordVariations as $variation) {
            if ($this->ultraFlexibleMatch($job->description, $variation)) {
                $score += 15;
                if ($this->isExactMatch($job->description, $variation)) {
                    $score += 5; // Exact match bonus
                }
                
                // Count matched words
                foreach ($searchWords as $searchWord) {
                    if ($this->ultraFlexibleMatch($job->description, $searchWord)) {
                        $matchedWords++;
                    }
                }
                break;
            }
        }
        
        // Requirements matches (medium priority - 15 points max)
        if ($job->requirements) {
            foreach ($keywordVariations as $variation) {
                if ($this->ultraFlexibleMatch($job->requirements, $variation)) {
                    $score += 10;
                    if ($this->isExactMatch($job->requirements, $variation)) {
                        $score += 5; // Exact match bonus
                    }
                    break;
                }
            }
        }
        
        // Tutor name matches (15 points max)
        if ($job->tutor && $job->tutor->name) {
            foreach ($keywordVariations as $variation) {
                if ($this->ultraFlexibleMatch($job->tutor->name, $variation)) {
                    $score += 12;
                    break;
                }
            }
        }
        
        // Tutor bio matches (10 points max)
        if ($job->tutor && $job->tutor->bio) {
            foreach ($keywordVariations as $variation) {
                if ($this->ultraFlexibleMatch($job->tutor->bio, $variation)) {
                    $score += 8;
                    break;
                }
            }
        }
        
        // Tutor skills matches (12 points max)
        if ($job->tutor && $job->tutor->profile && $job->tutor->profile->skills) {
            $skillsText = is_array($job->tutor->profile->skills) ? implode(' ', $job->tutor->profile->skills) : $job->tutor->profile->skills;
            foreach ($keywordVariations as $variation) {
                if ($this->ultraFlexibleMatch($skillsText, $variation)) {
                    $score += 10;
                    break;
                }
            }
        }
        
        // Multi-word match bonus (big bonus for matching multiple words from search)
        if (count($searchWords) > 1) {
            $matchPercentage = $matchedWords / count($searchWords);
            if ($matchPercentage >= 0.5) { // At least 50% of words match
                $score += 20 * $matchPercentage; // Up to 20 points bonus
            }
        }
        
        return min($score, 90); // Cap at 90 points
    }
    
    /**
     * Score location matches (district + place combined)
     */
    private function scoreFlexibleLocation($job, $district, $place)
    {
        $score = 0;
        
        // District matching (25 points max)
        if (!empty($district)) {
            $districtVariations = $this->generateLocationVariations($district);
            if ($job->district) {
                foreach ($districtVariations as $variation) {
                    if ($this->ultraFlexibleMatch($job->district, $variation)) {
                        $score += 20;
                        if ($this->isExactMatch($job->district, $variation)) {
                            $score += 5; // Exact match bonus
                        }
                        break;
                    }
                }
            }
        }
        
        // Place matching (25 points max)
        if (!empty($place)) {
            $placeVariations = $this->generateLocationVariations($place);
            
            // Check place field
            if ($job->place) {
                foreach ($placeVariations as $variation) {
                    if ($this->ultraFlexibleMatch($job->place, $variation)) {
                        $score += 15;
                        if ($this->isExactMatch($job->place, $variation)) {
                            $score += 3; // Exact match bonus
                        }
                        break;
                    }
                }
            }
            
            // Check landmark field
            if ($job->landmark) {
                foreach ($placeVariations as $variation) {
                    if ($this->ultraFlexibleMatch($job->landmark, $variation)) {
                        $score += 10;
                        break;
                    }
                }
            }
            
            // Check state field
            if ($job->state) {
                foreach ($placeVariations as $variation) {
                    if ($this->ultraFlexibleMatch($job->state, $variation)) {
                        $score += 8;
                        break;
                    }
                }
            }
        }
        
        return min($score, 40); // Cap at 40 points
    }

    /**
     * Calculate comprehensive relevance score for a job based on search criteria
     * Uses sophisticated scoring algorithm with multiple factors
     */
    private function calculateJobRelevanceScore($job, $keyword, $district, $place)
    {
        $score = 0;
        $matchCount = 0;
        
        // KEYWORD SCORING (0-60 points)
        if (!empty($keyword)) {
            $keywordScore = $this->scoreKeywordMatch($job, $keyword);
            $score += $keywordScore;
            if ($keywordScore > 0) $matchCount++;
        }
        
        // DISTRICT SCORING (0-25 points)
        if (!empty($district)) {
            $districtScore = $this->scoreDistrictMatch($job, $district);
            $score += $districtScore;
            if ($districtScore > 0) $matchCount++;
        }
        
        // PLACE SCORING (0-25 points)
        if (!empty($place)) {
            $placeScore = $this->scorePlaceMatch($job, $place);
            $score += $placeScore;
            if ($placeScore > 0) $matchCount++;
        }
        
        // COMBINATION BONUS (0-20 points)
        if ($matchCount >= 2) {
            $score += 10; // Two criteria match
        }
        if ($matchCount >= 3) {
            $score += 10; // All three criteria match
        }
        
        // FRESHNESS BONUS (0-5 points)
        $daysSinceCreated = now()->diffInDays($job->created_at);
        if ($daysSinceCreated <= 7) {
            $score += 5;
        } elseif ($daysSinceCreated <= 30) {
            $score += 3;
        }
        
        // TUTOR QUALITY BONUS (0-5 points)
        if ($job->tutor && $job->tutor->kyc && $job->tutor->kyc->status === 'approved') {
            $score += 5;
        }
        
        return max(0, $score);
    }
    
    /**
     * Score keyword matches with fuzzy matching and synonyms
     */
    private function scoreKeywordMatch($job, $keyword)
    {
        $score = 0;
        $keywordLower = strtolower($keyword);
        $words = $this->extractFlexibleKeywords($keyword);
        
        // Title matches (highest priority)
        foreach ($words as $word) {
            if ($this->fuzzyMatch($job->title, $word)) {
                $score += 15;
            }
        }
        if ($this->fuzzyMatch($job->title, $keywordLower)) {
            $score += 20; // Exact phrase bonus
        }
        
        // Subject matches (high priority)
        if ($job->subjects) {
            $subjectsText = is_array($job->subjects) ? implode(' ', $job->subjects) : $job->subjects;
            foreach ($words as $word) {
                if ($this->fuzzyMatch($subjectsText, $word)) {
                    $score += 12;
                }
            }
        }
        
        // Description matches (medium priority)
        foreach ($words as $word) {
            if ($this->fuzzyMatch($job->description, $word)) {
                $score += 8;
            }
        }
        if ($this->fuzzyMatch($job->description, $keywordLower)) {
            $score += 10; // Exact phrase bonus
        }
        
        // Requirements matches (medium priority)
        if ($job->requirements) {
            foreach ($words as $word) {
                if ($this->fuzzyMatch($job->requirements, $word)) {
                    $score += 6;
                }
            }
        }
        
        // Tutor name matches
        if ($job->tutor && $job->tutor->name) {
            foreach ($words as $word) {
                if ($this->fuzzyMatch($job->tutor->name, $word)) {
                    $score += 10;
                }
            }
        }
        
        // Tutor bio/profile matches
        if ($job->tutor && $job->tutor->bio) {
            foreach ($words as $word) {
                if ($this->fuzzyMatch($job->tutor->bio, $word)) {
                    $score += 5;
                }
            }
        }
        
        // Tutor profile skills matches
        if ($job->tutor && $job->tutor->profile && $job->tutor->profile->skills) {
            $skillsText = is_array($job->tutor->profile->skills) ? implode(' ', $job->tutor->profile->skills) : $job->tutor->profile->skills;
            foreach ($words as $word) {
                if ($this->fuzzyMatch($skillsText, $word)) {
                    $score += 8;
                }
            }
        }
        
        return min($score, 60); // Cap at 60 points
    }
    
    /**
     * Score district matches with flexible matching
     */
    private function scoreDistrictMatch($job, $district)
    {
        $score = 0;
        $districtWords = $this->extractFlexibleKeywords($district);
        
        if ($job->district) {
            foreach ($districtWords as $word) {
                if ($this->fuzzyMatch($job->district, $word)) {
                    $score += 12;
                }
            }
            if ($this->fuzzyMatch($job->district, strtolower($district))) {
                $score += 15; // Exact match bonus
            }
        }
        
        return min($score, 25); // Cap at 25 points
    }
    
    /**
     * Score place matches with flexible matching
     */
    private function scorePlaceMatch($job, $place)
    {
        $score = 0;
        $placeWords = $this->extractFlexibleKeywords($place);
        
        // Check place field
        if ($job->place) {
            foreach ($placeWords as $word) {
                if ($this->fuzzyMatch($job->place, $word)) {
                    $score += 10;
                }
            }
            if ($this->fuzzyMatch($job->place, strtolower($place))) {
                $score += 12; // Exact match bonus
            }
        }
        
        // Check landmark field
        if ($job->landmark) {
            foreach ($placeWords as $word) {
                if ($this->fuzzyMatch($job->landmark, $word)) {
                    $score += 8;
                }
            }
        }
        
        // Check state field
        if ($job->state) {
            foreach ($placeWords as $word) {
                if ($this->fuzzyMatch($job->state, $word)) {
                    $score += 6;
                }
            }
        }
        
        return min($score, 25); // Cap at 25 points
    }
    
    /**
     * Extract flexible keywords with synonyms and variations
     */
    private function extractFlexibleKeywords($input)
    {
        $keywords = [];
        $words = preg_split('/\s+/', strtolower(trim($input)));
        
        foreach ($words as $word) {
            $word = trim($word, '.,!?";:()[]{}');
            if (strlen($word) > 1) {
                $keywords[] = $word;
                
                // Add synonyms
                $synonyms = $this->getExtendedSynonyms();
                if (isset($synonyms[$word])) {
                    $keywords = array_merge($keywords, $synonyms[$word]);
                }
                
                // Add partial words for very flexible matching
                if (strlen($word) > 3) {
                    $keywords[] = substr($word, 0, 3); // First 3 characters
                    $keywords[] = substr($word, -3);   // Last 3 characters
                }
            }
        }
        
        return array_unique($keywords);
    }
    
    /**
     * Fuzzy matching function that supports partial matches and typos
     */
    private function fuzzyMatch($haystack, $needle)
    {
        if (empty($haystack) || empty($needle)) {
            return false;
        }
        
        $haystack = strtolower($haystack);
        $needle = strtolower($needle);
        
        // Exact match
        if (strpos($haystack, $needle) !== false) {
            return true;
        }
        
        // Partial match (at least 70% of the needle should match)
        if (strlen($needle) >= 4) {
            $partialLength = max(3, (int)(strlen($needle) * 0.7));
            $partial = substr($needle, 0, $partialLength);
            if (strpos($haystack, $partial) !== false) {
                return true;
            }
        }
        
        // Check if words are similar (simple Levenshtein-like check)
        if (strlen($needle) >= 4) {
            $words = explode(' ', $haystack);
            foreach ($words as $word) {
                if ($this->isSimilar($word, $needle)) {
                    return true;
                }
            }
        }
        
        return false;
    }
    
    /**
     * Check if two words are similar (handles typos)
     */
    private function isSimilar($word1, $word2)
    {
        $word1 = strtolower(trim($word1));
        $word2 = strtolower(trim($word2));
        
        if (strlen($word1) < 3 || strlen($word2) < 3) {
            return $word1 === $word2;
        }
        
        // Calculate similarity
        $maxLen = max(strlen($word1), strlen($word2));
        $minLen = min(strlen($word1), strlen($word2));
        
        // If length difference is too big, not similar
        if (($maxLen - $minLen) > 2) {
            return false;
        }
        
        // Simple character overlap check
        $overlap = 0;
        $shorter = strlen($word1) <= strlen($word2) ? $word1 : $word2;
        $longer = strlen($word1) > strlen($word2) ? $word1 : $word2;
        
        for ($i = 0; $i < strlen($shorter); $i++) {
            if (strpos($longer, $shorter[$i]) !== false) {
                $overlap++;
            }
        }
        
        // At least 70% characters should overlap
        return ($overlap / strlen($shorter)) >= 0.7;
    }
    
    /**
     * Get extended synonyms for better matching
     */
    private function getExtendedSynonyms()
    {
        return [
            // Math related
            'math' => ['mathematics', 'maths', 'arithmetic', 'algebra', 'calculus'],
            'mathematics' => ['math', 'maths', 'arithmetic'],
            'maths' => ['math', 'mathematics', 'arithmetic'],
            'algebra' => ['math', 'mathematics'],
            'calculus' => ['math', 'mathematics'],
            'geometry' => ['math', 'mathematics'],
            
            // Science related
            'science' => ['sci', 'physics', 'chemistry', 'biology'],
            'sci' => ['science'],
            'physics' => ['phy', 'science'],
            'phy' => ['physics', 'science'],
            'chemistry' => ['chem', 'science'],
            'chem' => ['chemistry', 'science'],
            'biology' => ['bio', 'science'],
            'bio' => ['biology', 'science'],
            
            // Language related
            'english' => ['eng', 'language', 'grammar'],
            'eng' => ['english', 'language'],
            'nepali' => ['nep', 'language'],
            'nep' => ['nepali', 'language'],
            
            // Education levels
            'grade' => ['class', 'level', 'standard'],
            'class' => ['grade', 'level', 'standard'],
            'level' => ['grade', 'class', 'standard'],
            'standard' => ['grade', 'class', 'level'],
            
            // Teaching related
            'teacher' => ['tutor', 'instructor', 'educator', 'mentor'],
            'tutor' => ['teacher', 'instructor', 'educator', 'mentor'],
            'instructor' => ['teacher', 'tutor', 'educator'],
            'educator' => ['teacher', 'tutor', 'instructor'],
            'mentor' => ['teacher', 'tutor', 'guide'],
            
            // Experience related
            'experienced' => ['expert', 'skilled', 'professional', 'qualified'],
            'expert' => ['experienced', 'skilled', 'professional'],
            'skilled' => ['experienced', 'expert', 'qualified'],
            'professional' => ['experienced', 'expert', 'qualified'],
            'qualified' => ['experienced', 'professional', 'certified'],
            
            // School types
            'school' => ['education', 'academic', 'institution'],
            'college' => ['university', 'institution', 'higher education'],
            'university' => ['college', 'institution', 'higher education'],
            
            // Grade numbers and words
            '1' => ['one', 'i', 'first'],
            '2' => ['two', 'ii', 'second'],
            '3' => ['three', 'iii', 'third'],
            '4' => ['four', 'iv', 'fourth'],
            '5' => ['five', 'v', 'fifth'],
            '6' => ['six', 'vi', 'sixth'],
            '7' => ['seven', 'vii', 'seventh'],
            '8' => ['eight', 'viii', 'eighth'],
            '9' => ['nine', 'ix', 'ninth'],
            '10' => ['ten', 'x', 'tenth'],
            '11' => ['eleven', 'xi', 'eleventh'],
            '12' => ['twelve', 'xii', 'twelfth'],
            
            // Common education terms
            'primary' => ['elementary', 'basic', 'lower'],
            'elementary' => ['primary', 'basic', 'lower'],
            'secondary' => ['high', 'higher', 'upper'],
            'higher' => ['secondary', 'advanced', 'upper'],
            'advanced' => ['higher', 'expert', 'professional'],
        ];
    }
    
    /**
     * Get fallback matches when primary search yields few results
     */
    private function getFallbackMatches($baseQuery, $keyword, $district, $place, $excludeIds)
    {
        $fallbackJobs = collect();
        
        // Try broader keyword matching
        if (!empty($keyword) && strlen($keyword) > 3) {
            $broadKeywords = $this->getBroadKeywords($keyword);
            foreach ($broadKeywords as $broadKeyword) {
                $jobs = (clone $baseQuery)
                    ->where(function($query) use ($broadKeyword) {
                        $query->where('title', 'LIKE', "%{$broadKeyword}%")
                              ->orWhere('description', 'LIKE', "%{$broadKeyword}%")
                              ->orWhere('subjects', 'LIKE', "%{$broadKeyword}%");
                    })
                    ->whereNotIn('id', $excludeIds)
                    ->limit(5)
                    ->get();
                
                $fallbackJobs = $fallbackJobs->merge($jobs);
                if ($fallbackJobs->count() >= 10) break;
            }
        }
        
        // Try broader location matching
        if (!empty($district) || !empty($place)) {
            $locationTerm = $district ?: $place;
            if (strlen($locationTerm) > 2) {
                $shortLocation = substr($locationTerm, 0, 3);
                $jobs = (clone $baseQuery)
                    ->where(function($query) use ($shortLocation) {
                        $query->where('district', 'LIKE', "%{$shortLocation}%")
                              ->orWhere('place', 'LIKE', "%{$shortLocation}%")
                              ->orWhere('state', 'LIKE', "%{$shortLocation}%");
                    })
                    ->whereNotIn('id', $excludeIds)
                    ->whereNotIn('id', $fallbackJobs->pluck('id'))
                    ->limit(5)
                    ->get();
                
                $fallbackJobs = $fallbackJobs->merge($jobs);
            }
        }
        
        return $fallbackJobs->take(10);
    }
    
    /**
     * Get broader keywords for fallback searching
     */
    private function getBroadKeywords($keyword)
    {
        $broad = [];
        $words = explode(' ', strtolower($keyword));
        
        foreach ($words as $word) {
            if (strlen($word) > 3) {
                $broad[] = substr($word, 0, 4); // First 4 characters
                $broad[] = substr($word, 0, 3); // First 3 characters
            }
        }
        
        // Add related subject terms
        $subjectKeywords = [
            'math' => ['algebra', 'geometry', 'arithmetic'],
            'sci' => ['physics', 'chemistry', 'biology'],
            'eng' => ['grammar', 'literature', 'writing'],
        ];
        
        foreach ($words as $word) {
            if (isset($subjectKeywords[$word])) {
                $broad = array_merge($broad, $subjectKeywords[$word]);
            }
        }
        
        return array_unique($broad);
    }
    
    /**
     * Apply ultra-flexible keyword search across multiple fields
     * Supports partial word matching and multiple words
     */
    private function applyFlexibleKeywordSearch($query, $keyword)
    {
        // Split keyword into individual words for flexible matching
        $words = array_filter(explode(' ', strtolower($keyword)));
        $synonyms = $this->getKeywordSynonyms();
        
        // Add synonyms for each word
        $allWords = $words;
        foreach ($words as $word) {
            if (isset($synonyms[$word])) {
                $allWords = array_merge($allWords, $synonyms[$word]);
            }
        }
        $allWords = array_unique($allWords);
        
        $query->where(function($mainQuery) use ($allWords, $keyword) {
            // Search each word individually for maximum flexibility
            foreach ($allWords as $word) {
                if (strlen($word) > 1) {
                    $mainQuery->orWhere(function($wordQuery) use ($word) {
                        // Search in job fields with partial matching
                        $wordQuery->where('title', 'LIKE', "%{$word}%")
                                 ->orWhere('description', 'LIKE', "%{$word}%")
                                 ->orWhere('requirements', 'LIKE', "%{$word}%");
                        
                        // Search in JSON subjects array with partial matching
                        $wordQuery->orWhereRaw("JSON_UNQUOTE(JSON_EXTRACT(subjects, '$[*]')) LIKE ?", ["%{$word}%"]);
                        
                        // Search in tutor information
                        $wordQuery->orWhereHas('tutor', function($tutorQuery) use ($word) {
                            $tutorQuery->where('name', 'LIKE', "%{$word}%")
                                     ->orWhere('bio', 'LIKE', "%{$word}%");
                            
                            // Search in tutor subjects if available
                            $tutorQuery->orWhereRaw("JSON_UNQUOTE(JSON_EXTRACT(subjects, '$[*]')) LIKE ?", ["%{$word}%"]);
                        });
                        
                        // Search in tutor profile
                        $wordQuery->orWhereHas('tutor.profile', function($profileQuery) use ($word) {
                            $profileQuery->where('bio', 'LIKE', "%{$word}%");
                            
                            // Search in skills if available
                            $profileQuery->orWhereRaw("JSON_UNQUOTE(JSON_EXTRACT(skills, '$[*]')) LIKE ?", ["%{$word}%"]);
                        });
                    });
                }
            }
            
            // Also search for the complete phrase for exact matches
            if (strlen($keyword) > 3) {
                $mainQuery->orWhere(function($phraseQuery) use ($keyword) {
                    $phraseQuery->where('title', 'LIKE', "%{$keyword}%")
                               ->orWhere('description', 'LIKE', "%{$keyword}%")
                               ->orWhere('requirements', 'LIKE', "%{$keyword}%");
                });
            }
        });
    }
    
    /**
     * Apply flexible place search with partial word matching
     */
    private function applyFlexiblePlaceSearch($query, $place)
    {
        // Split place into individual words for flexible matching
        $words = array_filter(explode(' ', strtolower($place)));
        
        $query->where(function($mainQuery) use ($words, $place) {
            // Search each word individually
            foreach ($words as $word) {
                if (strlen($word) > 1) {
                    $mainQuery->orWhere(function($wordQuery) use ($word) {
                        $wordQuery->where('place', 'LIKE', "%{$word}%")
                                 ->orWhere('landmark', 'LIKE', "%{$word}%")
                                 ->orWhere('state', 'LIKE', "%{$word}%");
                    });
                }
            }
            
            // Also search for the complete phrase
            if (count($words) > 1) {
                $mainQuery->orWhere(function($phraseQuery) use ($place) {
                    $phraseQuery->where('place', 'LIKE', "%{$place}%")
                               ->orWhere('landmark', 'LIKE', "%{$place}%")
                               ->orWhere('state', 'LIKE', "%{$place}%");
                });
            }
        });
    }
    
    /**
     * Apply flexible district search with partial word matching
     */
    private function applyFlexibleDistrictSearch($query, $district)
    {
        // Split district into individual words for flexible matching
        $words = array_filter(explode(' ', strtolower($district)));
        
        $query->where(function($mainQuery) use ($words, $district) {
            // Search each word individually
            foreach ($words as $word) {
                if (strlen($word) > 1) {
                    $mainQuery->orWhere('district', 'LIKE', "%{$word}%");
                }
            }
            
            // Also search for the complete phrase
            if (count($words) > 1) {
                $mainQuery->orWhere('district', 'LIKE', "%{$district}%");
            }
        });
    }
    
    /**
     * Get keyword synonyms for flexible matching
     */
    private function getKeywordSynonyms()
    {
        return [
            'math' => ['mathematics', 'maths'],
            'mathematics' => ['math', 'maths'],
            'maths' => ['math', 'mathematics'],
            'sci' => ['science'],
            'science' => ['sci'],
            'eng' => ['english'],
            'english' => ['eng'],
            'physics' => ['phy'],
            'phy' => ['physics'],
            'chemistry' => ['chem'],
            'chem' => ['chemistry'],
            'biology' => ['bio'],
            'bio' => ['biology'],
            'computer' => ['comp', 'cs'],
            'grade' => ['class', 'level'],
            'class' => ['grade', 'level'],
            'level' => ['grade', 'class'],
            'teacher' => ['tutor', 'instructor', 'educator'],
            'tutor' => ['teacher', 'instructor', 'educator'],
            'instructor' => ['teacher', 'tutor', 'educator'],
            'experienced' => ['expert', 'skilled', 'professional'],
            'expert' => ['experienced', 'skilled', 'professional'],
            'high' => ['higher', 'secondary'],
            'school' => ['education', 'academic'],
            'primary' => ['elementary', 'basic'],
            'elementary' => ['primary', 'basic'],
            '11' => ['eleven', 'xi', 'eleventh'],
            '12' => ['twelve', 'xii', 'twelfth'],
            '10' => ['ten', 'x', 'tenth'],
            '9' => ['nine', 'ix', 'ninth'],
            '8' => ['eight', 'viii', 'eighth'],
        ];
    }
    
    /**
     * Extract and normalize search keywords for flexible matching
     */
    private function extractSearchKeywords($keyword)
    {
        // Convert to lowercase and split by spaces
        $words = explode(' ', strtolower(trim($keyword)));
        $keywords = [];
        
        // Add original words
        foreach ($words as $word) {
            $word = trim($word);
            if (strlen($word) > 1) {
                $keywords[] = $word;
            }
        }
        
        // Add common synonyms and variations
        $synonyms = [
            'math' => ['mathematics', 'maths'],
            'mathematics' => ['math', 'maths'],
            'maths' => ['math', 'mathematics'],
            'sci' => ['science'],
            'science' => ['sci'],
            'eng' => ['english'],
            'english' => ['eng'],
            'physics' => ['phy'],
            'phy' => ['physics'],
            'chemistry' => ['chem'],
            'chem' => ['chemistry'],
            'biology' => ['bio'],
            'bio' => ['biology'],
            'computer' => ['comp', 'cs'],
            'grade' => ['class', 'level'],
            'class' => ['grade', 'level'],
            'level' => ['grade', 'class'],
            'teacher' => ['tutor', 'instructor', 'educator'],
            'tutor' => ['teacher', 'instructor', 'educator'],
            'instructor' => ['teacher', 'tutor', 'educator'],
            'experienced' => ['expert', 'skilled', 'professional'],
            'expert' => ['experienced', 'skilled', 'professional'],
            'high school' => ['secondary', 'higher secondary'],
            'secondary' => ['high school', 'higher secondary'],
            'primary' => ['elementary', 'basic'],
            'elementary' => ['primary', 'basic'],
        ];
        
        // Add synonyms for each word
        $originalKeywords = $keywords;
        foreach ($originalKeywords as $word) {
            if (isset($synonyms[$word])) {
                foreach ($synonyms[$word] as $synonym) {
                    if (!in_array($synonym, $keywords)) {
                        $keywords[] = $synonym;
                    }
                }
            }
        }
        
        // Add grade number variations (e.g., "11" should match "eleven", "XI")
        $gradeNumbers = [
            '1' => ['one', 'i', 'first'],
            '2' => ['two', 'ii', 'second'], 
            '3' => ['three', 'iii', 'third'],
            '4' => ['four', 'iv', 'fourth'],
            '5' => ['five', 'v', 'fifth'],
            '6' => ['six', 'vi', 'sixth'],
            '7' => ['seven', 'vii', 'seventh'],
            '8' => ['eight', 'viii', 'eighth'],
            '9' => ['nine', 'ix', 'ninth'],
            '10' => ['ten', 'x', 'tenth'],
            '11' => ['eleven', 'xi', 'eleventh'],
            '12' => ['twelve', 'xii', 'twelfth'],
        ];
        
        foreach ($originalKeywords as $word) {
            if (isset($gradeNumbers[$word])) {
                foreach ($gradeNumbers[$word] as $variation) {
                    if (!in_array($variation, $keywords)) {
                        $keywords[] = $variation;
                    }
                }
            }
        }
        
        return array_unique($keywords);
    }
    
    /**
     * Calculate relevance score for keyword matches
     */
    private function calculateKeywordRelevanceScore($job, $keyword)
    {
        $score = 70; // Base score
        $keywords = $this->extractSearchKeywords($keyword);
        
        // Higher score for title matches
        foreach ($keywords as $word) {
            if (stripos($job->title, $word) !== false) {
                $score += 15;
            }
        }
        if (stripos($job->title, $keyword) !== false) {
            $score += 20;
        }
        
        // Medium score for description matches
        foreach ($keywords as $word) {
            if (stripos($job->description, $word) !== false) {
                $score += 8;
            }
        }
        if (stripos($job->description, $keyword) !== false) {
            $score += 10;
        }
        
        // Score for subject matches
        if ($job->subjects) {
            $subjectsString = is_array($job->subjects) ? implode(' ', $job->subjects) : $job->subjects;
            foreach ($keywords as $word) {
                if (stripos($subjectsString, $word) !== false) {
                    $score += 12;
                }
            }
        }
        
        // Score for tutor name/bio matches
        if ($job->tutor) {
            foreach ($keywords as $word) {
                if (stripos($job->tutor->name, $word) !== false) {
                    $score += 10;
                }
                if ($job->tutor->bio && stripos($job->tutor->bio, $word) !== false) {
                    $score += 8;
                }
            }
        }
        
        // Cap the maximum score
        return min($score, 95);
    }
    
    /**
     * Apply location search across location fields
     */
    private function applyLocationSearch($query, $location)
    {
        $query->where(function($q) use ($location) {
            $q->where('district', 'LIKE', "%{$location}%")
              ->orWhere('state', 'LIKE', "%{$location}%")
              ->orWhere('place', 'LIKE', "%{$location}%")
              ->orWhere('landmark', 'LIKE', "%{$location}%")
              ->orWhere('country', 'LIKE', "%{$location}%");
        });
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
    
    /**
     * Generate keyword variations for ultra-flexible matching
     */
    private function generateKeywordVariations($keyword)
    {
        $variations = [$keyword];
        $words = explode(' ', strtolower(trim($keyword)));
        
        foreach ($words as $word) {
            $variations[] = $word;
            
            // Add common abbreviations and synonyms
            $synonyms = [
                'c' => ['computer', 'coding', 'programming'],
                'programming' => ['coding', 'development', 'software', 'computer', 'prog'],
                'coding' => ['programming', 'development', 'software', 'computer'],
                'development' => ['programming', 'coding', 'software', 'dev'],
                'software' => ['programming', 'coding', 'development', 'computer'],
                'computer' => ['programming', 'coding', 'software', 'comp', 'cs'],
                'math' => ['mathematics', 'maths'],
                'mathematics' => ['math', 'maths'],
                'maths' => ['math', 'mathematics'],
                'eng' => ['english'],
                'english' => ['eng'],
                'sci' => ['science'],
                'science' => ['sci'],
                'phy' => ['physics'],
                'physics' => ['phy'],
                'chem' => ['chemistry'],
                'chemistry' => ['chem'],
                'bio' => ['biology'],
                'biology' => ['bio'],
                'comp' => ['computer', 'programming'],
                'cs' => ['computer science', 'programming', 'computer'],
                'teacher' => ['tutor', 'instructor', 'educator'],
                'tutor' => ['teacher', 'instructor', 'educator'],
                'grade' => ['class', 'level'],
                'class' => ['grade', 'level'],
                'level' => ['grade', 'class'],
            ];
            
            if (isset($synonyms[$word])) {
                $variations = array_merge($variations, $synonyms[$word]);
            }
            
            // Add partial matches for longer words
            if (strlen($word) > 4) {
                $variations[] = substr($word, 0, 3); // First 3 chars
                $variations[] = substr($word, 0, 4); // First 4 chars
            }
        }
        
        // Special handling for programming-related searches
        if (strpos($keyword, 'programming') !== false || strpos($keyword, 'coding') !== false) {
            $variations[] = 'computer';
            $variations[] = 'software';
            $variations[] = 'development';
        }
        
        if (strpos($keyword, 'c programming') !== false) {
            $variations[] = 'computer science';
            $variations[] = 'computer programming';
            $variations[] = 'programming';
            $variations[] = 'computer';
        }
        
        return array_unique($variations);
    }
    
    /**
     * Generate location variations for flexible matching
     */
    private function generateLocationVariations($location)
    {
        $variations = [$location];
        $words = explode(' ', strtolower(trim($location)));
        
        foreach ($words as $word) {
            $variations[] = $word;
            
            // Add common location abbreviations
            $locationSynonyms = [
                'kathmandu' => ['ktm', 'ktm valley'],
                'ktm' => ['kathmandu'],
                'pokhara' => ['pkr'],
                'pkr' => ['pokhara'],
                'chitwan' => ['cht'],
                'lalitpur' => ['patan'],
                'patan' => ['lalitpur'],
            ];
            
            if (isset($locationSynonyms[$word])) {
                $variations = array_merge($variations, $locationSynonyms[$word]);
            }
            
            // Add partial matches for longer location names
            if (strlen($word) > 4) {
                $variations[] = substr($word, 0, 3);
                $variations[] = substr($word, 0, 4);
            }
        }
        
        return array_unique($variations);
    }
    
    /**
     * Ultra-flexible text matching with typo tolerance and enhanced programming terms
     */
    private function ultraFlexibleMatch($haystack, $needle)
    {
        if (empty($haystack) || empty($needle)) {
            return false;
        }
        
        $haystack = strtolower($haystack);
        $needle = strtolower($needle);
        
        // Direct substring match
        if (strpos($haystack, $needle) !== false) {
            return true;
        }
        
        // Special handling for programming-related searches
        if ($needle === 'c' && (strpos($haystack, 'computer') !== false || strpos($haystack, 'programming') !== false)) {
            return true;
        }
        
        if ($needle === 'programming' && (strpos($haystack, 'computer') !== false || strpos($haystack, 'software') !== false)) {
            return true;
        }
        
        if ($needle === 'computer' && strpos($haystack, 'programming') !== false) {
            return true;
        }
        
        // Word boundary matching
        $haystackWords = preg_split('/\s+/', $haystack);
        foreach ($haystackWords as $word) {
            // Exact word match
            if ($word === $needle) {
                return true;
            }
            
            // Partial word match (70% similarity)
            if (strlen($needle) >= 3 && strlen($word) >= 3) {
                if ($this->calculateSimilarity($word, $needle) >= 0.7) {
                    return true;
                }
            }
            
            // Start-of-word match
            if (strlen($needle) >= 3 && strpos($word, $needle) === 0) {
                return true;
            }
            
            // Enhanced programming term matching
            if ($this->isProgrammingTermMatch($word, $needle)) {
                return true;
            }
        }
        
        // Zigzag pattern matching (e.g., "eng" matches "english")
        if (strlen($needle) >= 3) {
            foreach ($haystackWords as $word) {
                if ($this->zigzagMatch($word, $needle)) {
                    return true;
                }
            }
        }
        
        return false;
    }
    
    /**
     * Enhanced programming term matching
     */
    private function isProgrammingTermMatch($word, $needle)
    {
        $programmingTerms = [
            'c' => ['computer', 'coding', 'programming'],
            'programming' => ['computer', 'coding', 'software', 'development'],
            'coding' => ['computer', 'programming', 'software'],
            'computer' => ['programming', 'coding', 'software'],
            'software' => ['programming', 'computer', 'coding'],
            'development' => ['programming', 'software', 'coding'],
        ];
        
        if (isset($programmingTerms[$needle])) {
            return in_array($word, $programmingTerms[$needle]);
        }
        
        if (isset($programmingTerms[$word])) {
            return in_array($needle, $programmingTerms[$word]);
        }
        
        return false;
    }
    
    /**
     * Check for zigzag pattern matches (abbreviations)
     */
    private function zigzagMatch($word, $pattern)
    {
        $word = strtolower($word);
        $pattern = strtolower($pattern);
        
        if (strlen($pattern) > strlen($word)) {
            return false;
        }
        
        $wordIndex = 0;
        $patternIndex = 0;
        
        while ($wordIndex < strlen($word) && $patternIndex < strlen($pattern)) {
            if ($word[$wordIndex] === $pattern[$patternIndex]) {
                $patternIndex++;
            }
            $wordIndex++;
        }
        
        // Pattern fully matched
        return $patternIndex === strlen($pattern);
    }
    
    /**
     * Check if it's an exact match (case insensitive)
     */
    private function isExactMatch($haystack, $needle)
    {
        return stripos($haystack, $needle) !== false;
    }
    
    /**
     * Get match reason for debugging/display
     */
    private function getMatchReason($job, $keyword, $district, $place)
    {
        $reasons = [];
        
        if (!empty($keyword) && !empty($district)) {
            $reasons[] = 'Keyword + Location';
        } elseif (!empty($keyword)) {
            $reasons[] = 'Keyword';
        } elseif (!empty($district) || !empty($place)) {
            $reasons[] = 'Location';
        }
        
        return implode(', ', $reasons);
    }
    
    /**
     * Get very flexible matches when primary search yields few results
     */
    private function getVeryFlexibleMatches($baseQuery, $keyword, $district, $place, $excludeIds)
    {
        $broadMatches = collect();
        
        // Very broad keyword matching
        if (!empty($keyword) && strlen($keyword) > 2) {
            $broadKeyword = substr($keyword, 0, 3);
            $jobs = (clone $baseQuery)
                ->where(function($query) use ($broadKeyword) {
                    $query->where('title', 'LIKE', "%{$broadKeyword}%")
                          ->orWhere('description', 'LIKE', "%{$broadKeyword}%")
                          ->orWhere('subjects', 'LIKE', "%{$broadKeyword}%");
                })
                ->whereNotIn('id', $excludeIds)
                ->limit(5)
                ->get();
            
            $broadMatches = $broadMatches->merge($jobs);
        }
        
        // Very broad location matching
        if (!empty($district) || !empty($place)) {
            $locationTerm = $district ?: $place;
            if (strlen($locationTerm) > 2) {
                $broadLocation = substr($locationTerm, 0, 3);
                $jobs = (clone $baseQuery)
                    ->where(function($query) use ($broadLocation) {
                        $query->where('district', 'LIKE', "%{$broadLocation}%")
                              ->orWhere('place', 'LIKE', "%{$broadLocation}%");
                    })
                    ->whereNotIn('id', $excludeIds)
                    ->whereNotIn('id', $broadMatches->pluck('id'))
                    ->limit(5)
                    ->get();
                
                $broadMatches = $broadMatches->merge($jobs);
            }
        }
        
        return $broadMatches->take(8);
    }
}
