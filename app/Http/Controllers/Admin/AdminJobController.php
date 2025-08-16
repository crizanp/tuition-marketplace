<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TutorJob;

class AdminJobController extends Controller
{
    /**
     * Display a listing of job posts
     */
    public function index(Request $request)
    {
        $query = TutorJob::with(['tutor']);
        
        // Search functionality
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('subjects', 'like', "%{$search}%")
                  ->orWhereHas('tutor', function($tutorQuery) use ($search) {
                      $tutorQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }
        
        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }
        
        // Filter by subject
        if ($request->has('subject') && $request->subject !== '') {
            $query->whereJsonContains('subjects', $request->subject);
        }
        
        // Filter by featured
        if ($request->has('featured')) {
            $query->where('is_featured', true);
        }
        
        $jobs = $query->orderBy('created_at', 'desc')->paginate(15);
        
        // Get unique subjects for filter (since subjects is JSON, we need to process it differently)
        $allJobs = TutorJob::select('subjects')->get();
        $subjects = collect();
        foreach ($allJobs as $job) {
            if ($job->subjects && is_array($job->subjects)) {
                $subjects = $subjects->merge($job->subjects);
            }
        }
        $subjects = $subjects->unique()->filter()->sort()->values();
        
        return view('admin.jobs.index', compact('jobs', 'subjects'));
    }
    
    /**
     * Display the specified job
     */
    public function show($id)
    {
        $job = TutorJob::with(['tutor', 'tutor.kyc', 'tutor.profile'])->findOrFail($id);
        
        return view('admin.jobs.show', compact('job'));
    }
    
    /**
     * Update job status
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:active,inactive,pending,expired',
            'reason' => 'nullable|string|max:500'
        ]);
        
        $job = TutorJob::findOrFail($id);
        $data = [
            'status' => $request->status,
            'admin_updated_at' => now()
        ];

        // Only update/append admin notes when a reason is provided.
        if ($request->filled('reason')) {
            $actor = auth()->user()->name ?? 'Admin';
            $timestamp = now()->format('Y-m-d H:i');
            $entry = "[{$actor} @ {$timestamp}] " . trim($request->reason);

            $existing = $job->admin_notes ? trim($job->admin_notes) : '';
            if ($existing !== '') {
                $newNotes = $existing . "\n\n" . $entry;
            } else {
                $newNotes = $entry;
            }

            $data['admin_notes'] = $newNotes;
        }

        $job->update($data);
        
        return redirect()->route('admin.jobs.show', $id)
            ->with('success', 'Job status updated successfully.');
    }
    
    /**
     * Toggle featured status
     */
    public function toggleFeatured($id)
    {
        $job = TutorJob::findOrFail($id);
        $job->update([
            'is_featured' => !$job->is_featured,
            'featured_at' => $job->is_featured ? null : now()
        ]);
        
        $message = $job->is_featured ? 'Job marked as featured.' : 'Job removed from featured.';
        
        return redirect()->route('admin.jobs.show', $id)
            ->with('success', $message);
    }
    
    /**
     * Bulk update job statuses
     */
    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'job_ids' => 'required|array',
            'job_ids.*' => 'exists:tutor_jobs,id',
            'action' => 'required|in:activate,deactivate,feature,unfeature,delete'
        ]);
        
        $jobs = TutorJob::whereIn('id', $request->job_ids);
        
        switch ($request->action) {
            case 'activate':
                $jobs->update(['status' => 'active']);
                $message = 'Selected jobs have been activated.';
                break;
            case 'deactivate':
                $jobs->update(['status' => 'inactive']);
                $message = 'Selected jobs have been deactivated.';
                break;
            case 'feature':
                $jobs->update(['is_featured' => true, 'featured_at' => now()]);
                $message = 'Selected jobs have been featured.';
                break;
            case 'unfeature':
                $jobs->update(['is_featured' => false, 'featured_at' => null]);
                $message = 'Selected jobs have been unfeatured.';
                break;
            case 'delete':
                $jobs->delete();
                $message = 'Selected jobs have been deleted.';
                break;
        }
        
        return redirect()->route('admin.jobs.index')
            ->with('success', $message);
    }
    
    /**
     * Delete a job
     */
    public function destroy($id)
    {
        $job = TutorJob::findOrFail($id);
        $job->delete();
        
        return redirect()->route('admin.jobs.index')
            ->with('success', 'Job has been deleted successfully.');
    }
    
    /**
     * Export jobs data
     */
    public function export(Request $request)
    {
        $jobs = TutorJob::with('tutor')->get();
        
        $filename = 'jobs_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($jobs) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Title', 'Tutor', 'Subject', 'Grade Level', 'Status', 'Featured', 'Rate', 'Views', 'Inquiries', 'Created At']);
            
            foreach ($jobs as $job) {
                fputcsv($file, [
                    $job->id,
                    $job->title,
                    $job->tutor->name,
                    is_array($job->subjects) ? implode(', ', $job->subjects) : $job->subjects,
                    $job->grade_level,
                    $job->status,
                    $job->is_featured ? 'Yes' : 'No',
                    $job->hourly_rate,
                    $job->views_count,
                    $job->inquiries_count,
                    $job->created_at
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}
