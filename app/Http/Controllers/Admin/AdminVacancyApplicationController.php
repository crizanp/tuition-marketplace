<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VacancyApplication;
use Illuminate\Http\Request;

class AdminVacancyApplicationController extends Controller
{
    /**
     * Display a listing of vacancy applications
     */
    public function index(Request $request)
    {
        $query = VacancyApplication::with(['tutor', 'vacancy', 'vacancy.student']);
        
        // Search functionality
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('tutor', function($tutorQuery) use ($search) {
                    $tutorQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                })->orWhereHas('vacancy', function($vacancyQuery) use ($search) {
                    $vacancyQuery->where('title', 'like', "%{$search}%")
                        ->orWhere('subject', 'like', "%{$search}%");
                });
            });
        }
        
        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }
        
        // Filter by subject
        if ($request->has('subject') && $request->subject !== '') {
            $query->whereHas('vacancy', function($q) use ($request) {
                $q->where('subject', $request->subject);
            });
        }
        
        $applications = $query->orderBy('created_at', 'desc')->paginate(15);
        
        // Get unique subjects for filter
        $subjects = VacancyApplication::with('vacancy')
            ->get()
            ->pluck('vacancy.subject')
            ->filter()
            ->unique()
            ->sort();
        
        return view('admin.vacancy-applications.index', compact('applications', 'subjects'));
    }
    
    /**
     * Display the specified application
     */
    public function show($id)
    {
        $application = VacancyApplication::with(['tutor.kyc', 'tutor.profile', 'vacancy.student'])
            ->findOrFail($id);
        
        return view('admin.vacancy-applications.show', compact('application'));
    }
    
    /**
     * Approve an application
     */
    public function approve($id)
    {
        $application = VacancyApplication::findOrFail($id);
        $application->update([
            'status' => 'approved',
            'reviewed_at' => now()
        ]);
        
        return redirect()->route('admin.vacancy-applications.show', $id)
            ->with('success', 'Application approved successfully.');
    }
    
    /**
     * Reject an application
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required|string|max:500'
        ]);
        
        $application = VacancyApplication::findOrFail($id);
        $application->update([
            'status' => 'rejected',
            'admin_notes' => $request->reason,
            'reviewed_at' => now()
        ]);
        
        return redirect()->route('admin.vacancy-applications.show', $id)
            ->with('success', 'Application rejected successfully.');
    }
    
    /**
     * Update application status
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected',
            'admin_notes' => 'nullable|string|max:500'
        ]);
        
        $application = VacancyApplication::findOrFail($id);
        
        $updateData = [
            'status' => $request->status,
            'admin_notes' => $request->admin_notes,
            'reviewed_at' => now()
        ];
        
        $application->update($updateData);
        
        return redirect()->route('admin.vacancy-applications.show', $id)
            ->with('success', 'Application status updated successfully.');
    }
    
    /**
     * Bulk update applications
     */
    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'application_ids' => 'required|array',
            'application_ids.*' => 'exists:vacancy_applications,id',
            'action' => 'required|in:approve,reject,delete'
        ]);
        
        $applications = VacancyApplication::whereIn('id', $request->application_ids);
        
        switch ($request->action) {
            case 'approve':
                $applications->update([
                    'status' => 'approved',
                    'reviewed_at' => now(),
                    'admin_notes' => 'Bulk approval by admin'
                ]);
                $message = 'Selected applications have been approved.';
                break;
            case 'reject':
                $applications->update([
                    'status' => 'rejected',
                    'reviewed_at' => now(),
                    'admin_notes' => 'Bulk rejection by admin'
                ]);
                $message = 'Selected applications have been rejected.';
                break;
            case 'delete':
                $applications->delete();
                $message = 'Selected applications have been deleted.';
                break;
        }
        
        return redirect()->route('admin.vacancy-applications.index')
            ->with('success', $message);
    }
    
    /**
     * Delete an application
     */
    public function destroy($id)
    {
        $application = VacancyApplication::findOrFail($id);
        $application->delete();
        
        return redirect()->route('admin.vacancy-applications.index')
            ->with('success', 'Application has been deleted successfully.');
    }
    
    /**
     * Export applications data
     */
    public function export(Request $request)
    {
        $applications = VacancyApplication::with(['tutor', 'vacancy'])->get();
        
        $filename = 'vacancy_applications_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($applications) {
            $file = fopen('php://output', 'w');
            
            // Headers
            fputcsv($file, [
                'ID', 'Tutor Name', 'Tutor Email', 'Vacancy Title', 'Subject',
                'Proposed Rate', 'Experience Years', 'Status', 'Applied At', 'Reviewed At'
            ]);
            
            // Data
            foreach ($applications as $application) {
                fputcsv($file, [
                    $application->id,
                    $application->tutor->name ?? 'N/A',
                    $application->tutor->email ?? 'N/A',
                    $application->vacancy->title ?? 'N/A',
                    $application->vacancy->subject ?? 'N/A',
                    $application->proposed_rate ?? 'N/A',
                    $application->experience_years,
                    ucfirst($application->status),
                    $application->applied_at->format('Y-m-d H:i:s'),
                    $application->reviewed_at ? $application->reviewed_at->format('Y-m-d H:i:s') : 'N/A'
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}
