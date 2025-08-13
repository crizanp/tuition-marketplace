<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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
        
        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
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
        $request->validate([
            'reason' => 'required|string|max:500'
        ]);
        
        $vacancy = StudentVacancy::findOrFail($id);
        $vacancy->update([
            'status' => 'rejected',
            'rejected_at' => now(),
            'admin_notes' => $request->reason
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
            'status' => 'required|in:pending,approved,rejected',
            'admin_notes' => 'nullable|string|max:500'
        ]);
        
        $vacancy = StudentVacancy::findOrFail($id);
        
        $updateData = [
            'status' => $request->status,
            'admin_notes' => $request->admin_notes
        ];
        
        if ($request->status === 'approved') {
            $updateData['approved_at'] = now();
            $updateData['rejected_at'] = null;
        } elseif ($request->status === 'rejected') {
            $updateData['rejected_at'] = now();
            $updateData['approved_at'] = null;
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
