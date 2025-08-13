<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tutor;
use App\Models\TutorJob;

class AdminTutorController extends Controller
{
    /**
     * Display a listing of tutors
     */
    public function index(Request $request)
    {
        $query = Tutor::with(['kyc', 'profile']);
        
        // Search functionality
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }
        
        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }
        
        // Filter by KYC status
        if ($request->has('kyc_status') && $request->kyc_status !== '') {
            $query->whereHas('kyc', function($q) use ($request) {
                $q->where('status', $request->kyc_status);
            });
        }
        
        $tutors = $query->withCount(['jobs', 'ratings'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        return view('admin.tutors.index', compact('tutors'));
    }
    
    /**
     * Display the specified tutor
     */
    public function show($id)
    {
        $tutor = Tutor::with([
            'kyc', 
            'profile', 
            'jobs' => function($query) {
                $query->orderBy('created_at', 'desc');
            },
            'ratings' => function($query) {
                $query->orderBy('created_at', 'desc');
            }
        ])->findOrFail($id);
        
        return view('admin.tutors.show', compact('tutor'));
    }
    
    /**
     * Update tutor status
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,active,suspended,banned',
            'reason' => 'required_if:status,suspended,banned|nullable|string|max:500'
        ]);
        
        $tutor = Tutor::findOrFail($id);
        $tutor->update([
            'status' => $request->status,
            'status_reason' => $request->reason,
            'status_updated_at' => now()
        ]);
        
        // If suspending or banning, deactivate all jobs
        if (in_array($request->status, ['suspended', 'banned'])) {
            $tutor->jobs()->update(['status' => 'inactive']);
        }
        
        return redirect()->route('admin.tutors.show', $id)
            ->with('success', 'Tutor status updated successfully.');
    }
    
    /**
     * Approve tutor (shortcut for active status)
     */
    public function approve($id)
    {
        $tutor = Tutor::findOrFail($id);
        $tutor->update([
            'status' => 'active',
            'approved_at' => now()
        ]);
        
        return redirect()->route('admin.tutors.show', $id)
            ->with('success', 'Tutor approved successfully.');
    }
    
    /**
     * Permanently delete tutor account
     */
    public function destroy($id)
    {
        $tutor = Tutor::findOrFail($id);
        
        // Delete all related data
        $tutor->jobs()->delete();
        $tutor->ratings()->delete();
        if ($tutor->kyc) {
            $tutor->kyc->delete();
        }
        if ($tutor->profile) {
            $tutor->profile->delete();
        }
        
        $tutor->delete();
        
        return redirect()->route('admin.tutors.index')
            ->with('success', 'Tutor account has been permanently deleted.');
    }
    
    /**
     * Bulk update tutors
     */
    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'tutor_ids' => 'required|array',
            'tutor_ids.*' => 'exists:tutors,id',
            'action' => 'required|in:activate,suspend,ban,delete'
        ]);

        $tutorIds = $request->tutor_ids;
        $action = $request->action;
        $affectedCount = 0;

        foreach ($tutorIds as $tutorId) {
            $tutor = Tutor::find($tutorId);
            if ($tutor) {
                switch ($action) {
                    case 'activate':
                        $tutor->update(['status' => 'active']);
                        $affectedCount++;
                        break;
                    case 'suspend':
                        $tutor->update(['status' => 'suspended']);
                        $affectedCount++;
                        break;
                    case 'ban':
                        $tutor->update(['status' => 'banned']);
                        $affectedCount++;
                        break;
                    case 'delete':
                        // Delete all related data
                        $tutor->jobs()->delete();
                        $tutor->ratings()->delete();
                        if ($tutor->kyc) {
                            $tutor->kyc->delete();
                        }
                        if ($tutor->profile) {
                            $tutor->profile->delete();
                        }
                        $tutor->delete();
                        $affectedCount++;
                        break;
                }
            }
        }

        return redirect()->route('admin.tutors.index')
            ->with('success', "Bulk action completed successfully. {$affectedCount} tutors were affected.");
    }

    /**
     * Export tutors data
     */
    public function export(Request $request)
    {
        $tutors = Tutor::with(['kyc', 'profile'])->get();
        
        $filename = 'tutors_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($tutors) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Name', 'Email', 'Phone', 'Status', 'KYC Status', 'Hourly Rate', 'Jobs Count', 'Created At']);
            
            foreach ($tutors as $tutor) {
                fputcsv($file, [
                    $tutor->id,
                    $tutor->name,
                    $tutor->email,
                    $tutor->phone,
                    $tutor->status,
                    $tutor->kyc ? $tutor->kyc->status : 'Not submitted',
                    $tutor->hourly_rate,
                    $tutor->jobs()->count(),
                    $tutor->created_at
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}
