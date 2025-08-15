<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\StudentVacancy;

class AdminVacancyController extends Controller
{
    /**
     * Display a listing of student vacancies
     */
    public function index(Request $request)
    {
        $query = StudentVacancy::with(['student']);
        
        // Search functionality
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%")
                  ->orWhereHas('student', function($studentQuery) use ($search) {
                      $studentQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }
        
        // Filter by status (map 'filled' UI value to DB 'completed')
        if ($request->has('status') && $request->status !== '') {
            $statusFilter = $request->status === 'filled' ? 'completed' : $request->status;
            $query->where('status', $statusFilter);
        }
        
        // Filter by subject
        if ($request->has('subject') && $request->subject !== '') {
            $query->where('subject', $request->subject);
        }
        
        // Filter by urgency
        if ($request->has('urgency') && $request->urgency !== '') {
            $query->where('urgency', $request->urgency);
        }
        
        $vacancies = $query->orderBy('created_at', 'desc')->paginate(15);
        
        // Get unique subjects for filter
        $subjects = StudentVacancy::distinct('subject')->pluck('subject')->filter()->sort();
        
        return view('admin.vacancies.index', compact('vacancies', 'subjects'));
    }
    
    /**
     * Show form for admin to create a vacancy
     */
    public function create()
    {
        return view('admin.vacancies.create');
    }

    /**
     * Store a vacancy posted by admin (approved immediately)
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

        $vacancy = StudentVacancy::create([
            // Admin posts are not tied to a student user; leave user_id null
            'user_id' => null,
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
            // Mark admin-posted vacancies as VIP
            'is_vip' => true,
            'status' => 'approved',
            'approved_at' => now(),
        ]);

        return redirect()->route('admin.vacancies.show', $vacancy->id)
            ->with('success', 'Vacancy posted and approved successfully.');
    }
    
    /**
     * Display the specified vacancy
     */
    public function show($id)
    {
        $vacancy = StudentVacancy::with(['student'])
            ->withCount('applications')
            ->findOrFail($id);
        
        return view('admin.vacancies.show', compact('vacancy'));
    }
    
    /**
     * Approve a vacancy
     */
    public function approve($id)
    {
        $vacancy = StudentVacancy::findOrFail($id);
        $vacancy->update([
            'status' => 'approved',
            'approved_at' => now(),
            'admin_notes' => null
        ]);
        
        return redirect()->route('admin.vacancies.show', $id)
            ->with('success', 'Vacancy approved successfully.');
    }
    
    /**
     * Reject a vacancy
     */
    public function reject(Request $request, $id)
    {
        // Debug log: capture incoming method and payload to investigate unexpected PATCH requests
        \Log::info('AdminVacancyController@reject called', [
            'method' => $request->method(),
            'path' => $request->path(),
            'payload' => $request->all()
        ]);
        $request->validate([
            'reason' => 'nullable|string|max:500'
        ]);

        $vacancy = StudentVacancy::findOrFail($id);
        $vacancy->update([
            'status' => 'rejected',
            'rejected_at' => now(),
            'admin_notes' => $request->reason ?? 'Rejected by admin'
        ]);
        
        return redirect()->route('admin.vacancies.show', $id)
            ->with('success', 'Vacancy rejected successfully.');
    }
    
    /**
     * Update vacancy status
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            // accept 'filled' from the UI and map it to the DB's 'completed' status below
            'status' => 'required|in:pending,approved,rejected,completed,filled',
            'admin_notes' => 'nullable|string|max:500'
        ]);

        $vacancy = StudentVacancy::findOrFail($id);

        // normalize status values: UI may send 'filled' but DB uses 'completed'
        $inputStatus = $request->status;
        $statusToSave = $inputStatus === 'filled' ? 'completed' : $inputStatus;

        $updateData = [
            'status' => $statusToSave,
            'admin_notes' => $request->admin_notes
        ];

        // Manage timestamp fields sensibly depending on the chosen status
        if ($statusToSave === 'approved') {
            $updateData['approved_at'] = now();
            $updateData['rejected_at'] = null;
        } elseif ($statusToSave === 'rejected') {
            $updateData['rejected_at'] = now();
            $updateData['approved_at'] = null;
        } elseif ($statusToSave === 'completed') {
            // marking as filled/completed: preserve approved_at if present, clear rejected_at
            if (!$vacancy->approved_at) {
                $updateData['approved_at'] = now();
            }
            $updateData['rejected_at'] = null;
        } else {
            $updateData['approved_at'] = null;
            $updateData['rejected_at'] = null;
        }

        $vacancy->update($updateData);

        return redirect()->route('admin.vacancies.show', $id)
            ->with('success', 'Vacancy status updated successfully.');
    }
    
    /**
     * Bulk update vacancy statuses
     */
    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'vacancy_ids' => 'required|array',
            'vacancy_ids.*' => 'exists:student_vacancies,id',
            'action' => 'required|in:approve,reject,delete'
        ]);
        
        $vacancies = StudentVacancy::whereIn('id', $request->vacancy_ids);
        
        switch ($request->action) {
            case 'approve':
                $vacancies->update([
                    'status' => 'approved',
                    'approved_at' => now(),
                    'admin_notes' => null
                ]);
                $message = 'Selected vacancies have been approved.';
                break;
            case 'reject':
                $vacancies->update([
                    'status' => 'rejected',
                    'rejected_at' => now(),
                    'admin_notes' => 'Bulk rejection by admin'
                ]);
                $message = 'Selected vacancies have been rejected.';
                break;
            case 'delete':
                $vacancies->delete();
                $message = 'Selected vacancies have been deleted.';
                break;
        }
        
        return redirect()->route('admin.vacancies.index')
            ->with('success', $message);
    }
    
    /**
     * Delete a vacancy
     */
    public function destroy($id)
    {
        $vacancy = StudentVacancy::findOrFail($id);
        $vacancy->delete();
        
        return redirect()->route('admin.vacancies.index')
            ->with('success', 'Vacancy has been deleted successfully.');
    }

    /**
     * Export vacancies data
     */
    public function export(Request $request)
    {
        $vacancies = StudentVacancy::with('student')->get();
        
        $filename = 'vacancies_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($vacancies) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Title', 'Student', 'Subject', 'Grade Level', 'Status', 'Urgency', 'Budget Range', 'Created At']);
            
            foreach ($vacancies as $vacancy) {
                fputcsv($file, [
                    $vacancy->id,
                    $vacancy->title,
                    $vacancy->student->name,
                    $vacancy->subject,
                    $vacancy->grade_level,
                    $vacancy->status,
                    $vacancy->urgency,
                    "Rs. {$vacancy->budget_min} - Rs. {$vacancy->budget_max}",
                    $vacancy->created_at
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}
