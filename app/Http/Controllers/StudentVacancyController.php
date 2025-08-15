<?php

namespace App\Http\Controllers;

use App\Models\StudentVacancy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentVacancyController extends Controller
{
    /**
     * Display a listing of student's vacancies
     */
    public function index()
    {
        $vacancies = StudentVacancy::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('student.vacancies.index', compact('vacancies'));
    }

    /**
     * Show the form for creating a new vacancy
     */
    public function create()
    {
        // Only allow students with verified profiles (80% completion) to post vacancies
        $user = Auth::user();
        if (!$user || !method_exists($user, 'isProfileVerified') || !$user->isProfileVerified()) {
            return redirect()
                ->route('student.dashboard')
                ->with('error', 'To post a vacancy you must complete at least 80% of your profile.');
        }

        return view('student.vacancies.create');
    }

    /**
     * Store a newly created vacancy
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'subject' => 'required|string|max:100',
            'subject_other' => 'required_if:subject,other|nullable|string|max:100',
            'grade_level' => 'required|string|max:50',
            'grade_level_other' => 'required_if:grade_level,other|nullable|string|max:50',
            'budget_min' => 'required|numeric|min:0',
            'budget_max' => 'required|numeric|min:0|gte:budget_min',
            'schedule_days' => 'required|array|min:1',
            'schedule_days.*' => 'in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'schedule_times' => 'required|array|min:1',
            // allow fractional durations (e.g., 1.5) when user specifies Other
            'duration_hours' => 'required',
            'duration_hours_other' => 'required_if:duration_hours,other|nullable|numeric|min:0.25|max:8',
            'location_type' => 'required|in:online,home,tutor_place,flexible',
            'address' => 'required_if:location_type,home|nullable|string',
            'urgency' => 'required|in:low,medium,high',
            'requirements' => 'nullable|array',
            'requirements.*' => 'string|max:255',
        ]);
        // Normalize fields if 'other' was selected
        $subject = $request->subject;
        if ($subject === 'other' && $request->filled('subject_other')) {
            $subject = $request->subject_other;
        }

        $grade = $request->grade_level;
        if ($grade === 'other' && $request->filled('grade_level_other')) {
            $grade = $request->grade_level_other;
        }

        $duration = $request->duration_hours;
        if ($duration === 'other' && $request->filled('duration_hours_other')) {
            $duration = $request->duration_hours_other;
        }

        StudentVacancy::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'subject' => $subject,
            'grade_level' => $grade,
            'budget_min' => $request->budget_min,
            'budget_max' => $request->budget_max,
            'schedule_days' => $request->schedule_days,
            'schedule_times' => $request->schedule_times,
            'duration_hours' => $duration,
            'location_type' => $request->location_type,
            'address' => $request->address,
            'urgency' => $request->urgency,
            'requirements' => $request->requirements ?? [],
            'status' => 'pending',
        ]);

        return redirect()
            ->route('student.vacancies.index')
            ->with('success', 'Vacancy posted successfully! It will be reviewed by our admin team.')
            ->with('vacancy_posted', true);
    }

    /**
     * Display the specified vacancy
     */
    public function show(StudentVacancy $vacancy)
    {
        // Ensure the vacancy belongs to the authenticated student
        if ($vacancy->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this vacancy.');
        }

        return view('student.vacancies.show', compact('vacancy'));
    }

    /**
     * Show the form for editing the specified vacancy
     */
    public function edit(StudentVacancy $vacancy)
    {
        // Ensure the vacancy belongs to the authenticated student
        if ($vacancy->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this vacancy.');
        }

        // Only allow editing if vacancy is pending or rejected
        if (!in_array($vacancy->status, ['pending', 'rejected'])) {
            return redirect()
                ->route('student.vacancies.show', $vacancy)
                ->with('error', 'You can only edit pending or rejected vacancies.');
        }

        return view('student.vacancies.edit', compact('vacancy'));
    }

    /**
     * Update the specified vacancy
     */
    public function update(Request $request, StudentVacancy $vacancy)
    {
        // Ensure the vacancy belongs to the authenticated student
        if ($vacancy->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this vacancy.');
        }

        // Only allow updating if vacancy is pending or rejected
        if (!in_array($vacancy->status, ['pending', 'rejected'])) {
            return redirect()
                ->route('student.vacancies.show', $vacancy)
                ->with('error', 'You can only edit pending or rejected vacancies.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'subject' => 'required|string|max:100',
            'subject_other' => 'required_if:subject,other|nullable|string|max:100',
            'grade_level' => 'required|string|max:50',
            'grade_level_other' => 'required_if:grade_level,other|nullable|string|max:50',
            'budget_min' => 'required|numeric|min:0',
            'budget_max' => 'required|numeric|min:0|gte:budget_min',
            'schedule_days' => 'required|array|min:1',
            'schedule_days.*' => 'in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'schedule_times' => 'required|array|min:1',
            'duration_hours' => 'required',
            'duration_hours_other' => 'required_if:duration_hours,other|nullable|numeric|min:0.25|max:8',
            'location_type' => 'required|in:online,home,tutor_place,flexible',
            'address' => 'required_if:location_type,home|nullable|string',
            'urgency' => 'required|in:low,medium,high',
            'requirements' => 'nullable|array',
            'requirements.*' => 'string|max:255',
        ]);
        // Normalize fields if 'other' was selected
        $subject = $request->subject;
        if ($subject === 'other' && $request->filled('subject_other')) {
            $subject = $request->subject_other;
        }

        $grade = $request->grade_level;
        if ($grade === 'other' && $request->filled('grade_level_other')) {
            $grade = $request->grade_level_other;
        }

        $duration = $request->duration_hours;
        if ($duration === 'other' && $request->filled('duration_hours_other')) {
            $duration = $request->duration_hours_other;
        }

        $vacancy->update([
            'title' => $request->title,
            'description' => $request->description,
            'subject' => $subject,
            'grade_level' => $grade,
            'budget_min' => $request->budget_min,
            'budget_max' => $request->budget_max,
            'schedule_days' => $request->schedule_days,
            'schedule_times' => $request->schedule_times,
            'duration_hours' => $duration,
            'location_type' => $request->location_type,
            'address' => $request->address,
            'urgency' => $request->urgency,
            'requirements' => $request->requirements ?? [],
            'status' => 'pending', // Reset to pending for re-review
            'admin_notes' => null, // Clear previous admin notes
            'rejected_at' => null,
        ]);

        return redirect()
            ->route('student.vacancies.show', $vacancy)
            ->with('success', 'Vacancy updated successfully! It will be reviewed again by our admin team.')
            ->with('vacancy_posted', true);
    }

    /**
     * Remove the specified vacancy
     */
    public function destroy(StudentVacancy $vacancy)
    {
        // Ensure the vacancy belongs to the authenticated student
        if ($vacancy->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this vacancy.');
        }

        // Only allow deletion if vacancy is not approved
        if ($vacancy->status === 'approved') {
            return redirect()
                ->route('student.vacancies.index')
                ->with('error', 'You cannot delete an approved vacancy. Please contact admin.');
        }

        $vacancy->delete();

        return redirect()
            ->route('student.vacancies.index')
            ->with('success', 'Vacancy deleted successfully!');
    }
}
