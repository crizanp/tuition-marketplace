<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\StudentVacancy;

class AdminStudentController extends Controller
{
    /**
     * Display a listing of students
     */
    public function index(Request $request)
    {
        $query = User::query();
        
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
        
        // Filter by grade level
        if ($request->has('grade_level') && $request->grade_level !== '') {
            $query->where('grade_level', $request->grade_level);
        }
        
        $students = $query->withCount('vacancies')
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        return view('admin.students.index', compact('students'));
    }
    
    /**
     * Display the specified student
     */
    public function show($id)
    {
        $student = User::with([
            'vacancies' => function($query) {
                $query->orderBy('created_at', 'desc')->with(['applications' => function($q) {
                    $q->orderBy('applied_at', 'desc')->with('tutor');
                }]);
            },
            // Ratings given by this student
            'ratings' => function($q) {
                $q->orderBy('created_at', 'desc')->with(['tutor', 'job']);
            }
        ])->findOrFail($id);
        
        return view('admin.students.show', compact('student'));
    }
    
    /**
     * Update student status
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:active,suspended,banned',
            'reason' => 'required_if:status,suspended,banned|nullable|string|max:500'
        ]);
        
        $student = User::findOrFail($id);
        $student->update([
            'status' => $request->status,
            'status_reason' => $request->reason,
            'status_updated_at' => now()
        ]);
        
        return redirect()->route('admin.students.show', $id)
            ->with('success', 'Student status updated successfully.');
    }
    
    /**
     * Permanently delete student account
     */
    public function destroy($id)
    {
        $student = User::findOrFail($id);
        
        // Delete all related data
        $student->vacancies()->delete();
        $student->ratings()->delete();
        $student->wishlists()->delete();
        
        $student->delete();
        
        return redirect()->route('admin.students.index')
            ->with('success', 'Student account has been permanently deleted.');
    }
    
    /**
     * Export students data
     */
    public function export(Request $request)
    {
        $students = User::all();
        
        $filename = 'students_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($students) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Name', 'Email', 'Phone', 'Grade Level', 'Status', 'Created At', 'Vacancies Count']);
            
            foreach ($students as $student) {
                fputcsv($file, [
                    $student->id,
                    $student->name,
                    $student->email,
                    $student->phone,
                    $student->grade_level,
                    $student->status,
                    $student->created_at,
                    $student->vacancies()->count()
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}
