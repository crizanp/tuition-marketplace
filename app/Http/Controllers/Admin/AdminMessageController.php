<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ContactMessage;

class AdminMessageController extends Controller
{
    /**
     * Display a listing of contact messages
     */
    public function index(Request $request)
    {
        $query = ContactMessage::with(['job', 'tutor.kyc', 'student']);
        
        // Search functionality
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%")
                  ->orWhereHas('tutor', function($tutorQuery) use ($search) {
                      $tutorQuery->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('job', function($jobQuery) use ($search) {
                      $jobQuery->where('title', 'like', "%{$search}%");
                  });
            });
        }
        
        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }
        
        // Filter by type
        if ($request->has('type') && $request->type !== '') {
            $query->where('type', $request->type);
        }
        
        // Filter by date range
        if ($request->has('date_from') && $request->date_from !== '') {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->has('date_to') && $request->date_to !== '') {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        $messages = $query->orderBy('created_at', 'desc')->paginate(20);
        
        return view('admin.messages.index', compact('messages'));
    }
    
    /**
     * Display the specified message
     */
    public function show(Request $request, $id)
    {
        $message = ContactMessage::with(['job', 'tutor.kyc', 'student'])->findOrFail($id);

        // Mark as read if not already
        if ($message->status === 'unread') {
            $message->markAsRead();
        }

        // If the request expects JSON (AJAX from the index page), return JSON payload
        if ($request->wantsJson() || $request->ajax() || str_contains($request->header('Accept', ''), 'application/json')) {
            // Build quick related data
            $studentData = null;
            if ($message->student) {
                $studentData = [
                    'id' => $message->student->id,
                    'name' => $message->student->name,
                    'email' => $message->student->email,
                    'profile_url' => route('admin.students.show', $message->student->id),
                    'profile_picture' => $message->student->profile_picture ? asset('storage/' . $message->student->profile_picture) : null,
                    'bio' => $message->student->bio ?? null,
                    'grade_level' => $message->student->grade_level ?? null,
                    'location_place' => $message->student->location_place ?? null,
                ];
            }

            $tutorData = null;
            if ($message->tutor) {
                $tutorData = [
                    'id' => $message->tutor->id,
                    'name' => $message->tutor->name,
                    'profile_url' => route('admin.tutors.show', $message->tutor->id),
                    'profile_picture' => ($message->tutor->kyc && $message->tutor->kyc->profile_photo) ? asset('storage/' . $message->tutor->kyc->profile_photo) : null,
                    'hourly_rate' => $message->tutor->hourly_rate ?? null,
                    'subjects' => $message->tutor->subjects ?? null,
                    'bio' => optional($message->tutor->profile)->bio ?? $message->tutor->bio ?? null,
                ];
            }

            $jobData = null;
            if ($message->tutor_job_id) {
                $job = $message->job;
                $jobData = [
                    'id' => $message->tutor_job_id,
                    'title' => $job ? $job->title : ('Job #' . $message->tutor_job_id),
                    'url' => $job ? route('admin.jobs.show', $message->tutor_job_id) : null,
                ];
            }

            return response()->json([
                'id' => $message->id,
                'name' => $message->name,
                'email' => $message->email,
                'phone' => $message->phone,
                'subject' => $message->subject,
                'message' => $message->message,
                'type' => $message->type,
                'is_read' => $message->status === 'read',
                'admin_response' => $message->admin_response,
                'responded_at' => $message->responded_at ? $message->responded_at->format('M d, Y H:i') : null,
                'job' => $jobData,
                'tutor' => $tutorData,
                'student' => $studentData,
                'created_at' => $message->created_at->format('M d, Y H:i'),
            ]);
        }

        return view('admin.messages.show', compact('message'));
    }
    
    /**
     * Mark message as responded
     */
    public function respond(Request $request, $id)
    {
        $request->validate([
            'response' => 'required|string|max:1000'
        ]);
        
        $message = ContactMessage::findOrFail($id);
        $message->markAsResponded($request->response);
        
        // Here you would typically send an email to the original sender
        // with the admin response
        
        return redirect()->route('admin.messages.show', $id)
            ->with('success', 'Response sent successfully.');
    }
    
    /**
     * Bulk update message statuses
     */
    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'message_ids' => 'required|array',
            'message_ids.*' => 'exists:contact_messages,id',
            'action' => 'required|in:mark_read,mark_unread,delete'
        ]);
        
        $messages = ContactMessage::whereIn('id', $request->message_ids);
        
        switch ($request->action) {
            case 'mark_read':
                $messages->update(['status' => 'read']);
                $message = 'Selected messages marked as read.';
                break;
            case 'mark_unread':
                $messages->update(['status' => 'unread']);
                $message = 'Selected messages marked as unread.';
                break;
            case 'delete':
                $messages->delete();
                $message = 'Selected messages deleted.';
                break;
        }
        
        return redirect()->route('admin.messages.index')
            ->with('success', $message);
    }
    
    /**
     * Delete a message
     */
    public function destroy($id)
    {
        $message = ContactMessage::findOrFail($id);
        $message->delete();
        
        return redirect()->route('admin.messages.index')
            ->with('success', 'Message deleted successfully.');
    }
    
    /**
     * Export messages data
     */
    public function export(Request $request)
    {
        $messages = ContactMessage::with(['job', 'tutor'])->get();
        
        $filename = 'messages_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($messages) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Name', 'Email', 'Phone', 'Job Title', 'Tutor', 'Type', 'Status', 'Created At']);
            
            foreach ($messages as $message) {
                fputcsv($file, [
                    $message->id,
                    $message->name,
                    $message->email,
                    $message->phone,
                    $message->job ? $message->job->title : 'N/A',
                    $message->tutor ? $message->tutor->name : 'N/A',
                    $message->type,
                    $message->status,
                    $message->created_at
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
    
    /**
     * Get message statistics
     */
    public function getStats()
    {
        $stats = [
            'total' => ContactMessage::count(),
            'unread' => ContactMessage::unread()->count(),
            'read' => ContactMessage::read()->count(),
            'responded' => ContactMessage::responded()->count(),
            'today' => ContactMessage::whereDate('created_at', today())->count(),
            'this_week' => ContactMessage::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'this_month' => ContactMessage::whereMonth('created_at', now()->month)->count(),
        ];
        
        return response()->json($stats);
    }
}
