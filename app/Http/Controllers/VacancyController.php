<?php

namespace App\Http\Controllers;

use App\Models\StudentVacancy;
use App\Models\VacancyApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VacancyController extends Controller
{
    /**
     * Display a listing of approved vacancies
     */
    public function index(Request $request)
    {
        $query = StudentVacancy::approved()->with(['student', 'applications'])->withCount('applications');
        
        // Search functionality
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%");
            });
        }
        
        // Filter by subject
        if ($request->has('subject') && $request->subject !== '') {
            $query->where('subject', $request->subject);
        }
        
        // Filter by grade level
        if ($request->has('grade_level') && $request->grade_level !== '') {
            $query->where('grade_level', $request->grade_level);
        }
        
        // Filter by location type
        if ($request->has('location_type') && $request->location_type !== '') {
            $query->where('location_type', $request->location_type);
        }
        
        // Filter by urgency
        if ($request->has('urgency') && $request->urgency !== '') {
            $query->where('urgency', $request->urgency);
        }
        
        // Filter by budget range
        if ($request->has('budget_min') && $request->budget_min !== '') {
            $query->where('budget_max', '>=', $request->budget_min);
        }
        
        if ($request->has('budget_max') && $request->budget_max !== '') {
            $query->where('budget_min', '<=', $request->budget_max);
        }
        
        $vacancies = $query->orderBy('created_at', 'desc')->paginate(12);
        
        // Get filter options
        $subjects = StudentVacancy::approved()->distinct('subject')->pluck('subject')->filter()->sort();
        $gradeLevels = StudentVacancy::approved()->distinct('grade_level')->pluck('grade_level')->filter()->sort();
        
        return view('vacancies.index', compact('vacancies', 'subjects', 'gradeLevels'));
    }
    
    /**
     * Display the specified vacancy
     */
    public function show($id)
    {
        $vacancy = StudentVacancy::approved()
            ->with(['student', 'applications' => function($query) {
                $query->approved()->with('tutor');
            }])
            ->findOrFail($id);
            
        // Check if current tutor has already applied
        $hasApplied = false;
        $userApplication = null;
        
        if (Auth::guard('tutor')->check()) {
            $userApplication = VacancyApplication::where('tutor_id', Auth::guard('tutor')->id())
                ->where('vacancy_id', $vacancy->id)
                ->first();
            $hasApplied = $userApplication !== null;
        }
        
        return view('vacancies.show', compact('vacancy', 'hasApplied', 'userApplication'));
    }
    
    /**
     * Apply for a vacancy
     */
    public function apply(Request $request, $id)
    {
        if (!Auth::guard('tutor')->check()) {
            return redirect()->route('tutor.login')
                ->with('error', 'Please login as a tutor to apply for vacancies.');
        }
        
        $tutor = Auth::guard('tutor')->user();
        $vacancy = StudentVacancy::approved()->findOrFail($id);
        
        // Check if tutor has KYC approved
        if (!$tutor->kyc || $tutor->kyc->status !== 'approved') {
            return redirect()->route('tutor.kyc.show')
                ->with('error', 'You must complete and have your KYC approved before applying for vacancies.');
        }
        
        // Check if already applied
        $existingApplication = VacancyApplication::where('tutor_id', $tutor->id)
            ->where('vacancy_id', $vacancy->id)
            ->first();
            
        if ($existingApplication) {
            return redirect()->back()
                ->with('error', 'You have already applied for this vacancy.');
        }
        
        $request->validate([
            'cover_letter' => 'required|string|min:50|max:1000',
            'proposed_rate' => 'nullable|numeric|min:0',
            'experience_years' => 'required|integer|min:0|max:50'
        ]);
        
        VacancyApplication::create([
            'tutor_id' => $tutor->id,
            'vacancy_id' => $vacancy->id,
            'cover_letter' => $request->cover_letter,
            'proposed_rate' => $request->proposed_rate,
            'experience_years' => $request->experience_years,
            'status' => 'pending',
            'applied_at' => now()
        ]);
        
        return redirect()->back()
            ->with('success', 'Your application has been submitted successfully! The student will review it and get back to you.');
    }
    
    /**
     * Search vacancies
     */
    public function search(Request $request)
    {
        $query = StudentVacancy::approved()->with(['student']);
        
        if ($request->has('q') && $request->q !== '') {
            $search = $request->q;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%");
            });
        }
        
        $vacancies = $query->orderBy('created_at', 'desc')->paginate(12);
        
        return view('vacancies.search', compact('vacancies'));
    }
}
