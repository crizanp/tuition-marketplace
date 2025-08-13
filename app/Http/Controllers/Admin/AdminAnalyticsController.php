<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Tutor;
use App\Models\TutorJob;
use App\Models\StudentVacancy;
use App\Models\ContactMessage;
use App\Models\TutorKyc;
use App\Models\Rating;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdminAnalyticsController extends Controller
{
    /**
     * Display analytics dashboard
     */
    public function index()
    {
        // Get overview statistics
        $stats = $this->getOverviewStats();
        
        // Get chart data
        $userRegistrations = $this->getUserRegistrationChart();
        $jobPostings = $this->getJobPostingChart();
        $vacancyStats = $this->getVacancyChart();
        $messageStats = $this->getMessageChart();
        
        // Get recent activities
        $recentActivities = $this->getRecentActivities();
        
        // Get top performing data
        $topTutors = $this->getTopTutors();
        $topSubjects = $this->getTopSubjects();
        
        return view('admin.analytics.index', compact(
            'stats', 
            'userRegistrations', 
            'jobPostings', 
            'vacancyStats', 
            'messageStats',
            'recentActivities',
            'topTutors',
            'topSubjects'
        ));
    }
    
    /**
     * Get overview statistics
     */
    private function getOverviewStats()
    {
        return [
            'total_students' => User::count(),
            'total_tutors' => Tutor::count(),
            'active_tutors' => Tutor::where('status', 'active')->count(),
            'pending_tutors' => Tutor::where('status', 'pending')->count(),
            'total_jobs' => TutorJob::count(),
            'active_jobs' => TutorJob::where('status', 'active')->count(),
            'featured_jobs' => TutorJob::where('is_featured', true)->count(),
            'total_vacancies' => StudentVacancy::count(),
            'pending_vacancies' => StudentVacancy::where('status', 'pending')->count(),
            'approved_vacancies' => StudentVacancy::where('status', 'approved')->count(),
            'total_messages' => ContactMessage::count(),
            'unread_messages' => ContactMessage::where('status', 'unread')->count(),
            'pending_kyc' => TutorKyc::where('status', 'pending')->count(),
            'total_ratings' => Rating::count(),
            'avg_rating' => Rating::avg('rating') ?? 0,
            
            // Growth statistics (compared to last month)
            'students_growth' => $this->getGrowthPercentage(User::class),
            'tutors_growth' => $this->getGrowthPercentage(Tutor::class),
            'jobs_growth' => $this->getGrowthPercentage(TutorJob::class),
            'vacancies_growth' => $this->getGrowthPercentage(StudentVacancy::class),
        ];
    }
    
    /**
     * Calculate growth percentage compared to last month
     */
    private function getGrowthPercentage($model)
    {
        $thisMonth = $model::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        
        $lastMonth = $model::whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->count();
        
        if ($lastMonth == 0) {
            return $thisMonth > 0 ? 100 : 0;
        }
        
        return round((($thisMonth - $lastMonth) / $lastMonth) * 100, 1);
    }
    
    /**
     * Get user registration chart data (last 30 days)
     */
    private function getUserRegistrationChart()
    {
        $days = collect(range(0, 29))->map(function ($i) {
            return now()->subDays($i)->format('Y-m-d');
        })->reverse();
        
        $students = User::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->whereBetween('created_at', [now()->subDays(29), now()])
            ->groupBy('date')
            ->pluck('count', 'date');
        
        $tutors = Tutor::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->whereBetween('created_at', [now()->subDays(29), now()])
            ->groupBy('date')
            ->pluck('count', 'date');
        
        return [
            'labels' => $days->map(function($date) { return Carbon::parse($date)->format('M d'); })->values(),
            'students' => $days->map(function($date) use ($students) { return $students->get($date, 0); })->values(),
            'tutors' => $days->map(function($date) use ($tutors) { return $tutors->get($date, 0); })->values(),
        ];
    }
    
    /**
     * Get job posting chart data (last 30 days)
     */
    private function getJobPostingChart()
    {
        $days = collect(range(0, 29))->map(function ($i) {
            return now()->subDays($i)->format('Y-m-d');
        })->reverse();
        
        $jobs = TutorJob::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->whereBetween('created_at', [now()->subDays(29), now()])
            ->groupBy('date')
            ->pluck('count', 'date');
        
        return [
            'labels' => $days->map(function($date) { return Carbon::parse($date)->format('M d'); })->values(),
            'jobs' => $days->map(function($date) use ($jobs) { return $jobs->get($date, 0); })->values(),
        ];
    }
    
    /**
     * Get vacancy statistics
     */
    private function getVacancyChart()
    {
        $statuses = ['pending', 'approved', 'rejected'];
        $data = [];
        
        foreach ($statuses as $status) {
            $data[$status] = StudentVacancy::where('status', $status)->count();
        }
        
        return $data;
    }
    
    /**
     * Get message statistics
     */
    private function getMessageChart()
    {
        $statuses = ['unread', 'read', 'responded'];
        $data = [];
        
        foreach ($statuses as $status) {
            $data[$status] = ContactMessage::where('status', $status)->count();
        }
        
        return $data;
    }
    
    /**
     * Get recent activities
     */
    private function getRecentActivities()
    {
        $activities = collect();
        
        // Recent student registrations
        User::latest()->take(5)->get()->each(function($student) use ($activities) {
            $activities->push([
                'type' => 'student_registration',
                'message' => "New student registered: {$student->name}",
                'time' => $student->created_at,
                'icon' => 'fas fa-user-plus',
                'color' => 'success'
            ]);
        });
        
        // Recent tutor registrations
        Tutor::latest()->take(5)->get()->each(function($tutor) use ($activities) {
            $activities->push([
                'type' => 'tutor_registration',
                'message' => "New tutor registered: {$tutor->name}",
                'time' => $tutor->created_at,
                'icon' => 'fas fa-chalkboard-teacher',
                'color' => 'info'
            ]);
        });
        
        // Recent job posts
        TutorJob::latest()->take(5)->get()->each(function($job) use ($activities) {
            $activities->push([
                'type' => 'job_posted',
                'message' => "New job posted: {$job->title}",
                'time' => $job->created_at,
                'icon' => 'fas fa-briefcase',
                'color' => 'primary'
            ]);
        });
        
        // Recent vacancy posts
        StudentVacancy::latest()->take(5)->get()->each(function($vacancy) use ($activities) {
            $activities->push([
                'type' => 'vacancy_posted',
                'message' => "New vacancy posted: {$vacancy->title}",
                'time' => $vacancy->created_at,
                'icon' => 'fas fa-search',
                'color' => 'warning'
            ]);
        });
        
        return $activities->sortByDesc('time')->take(20)->values();
    }
    
    /**
     * Get top performing tutors
     */
    private function getTopTutors()
    {
        return Tutor::withCount(['jobs', 'ratings'])
            ->withAvg('ratings', 'rating')
            ->having('jobs_count', '>', 0)
            ->orderByDesc('ratings_avg_rating')
            ->orderByDesc('jobs_count')
            ->take(10)
            ->get();
    }
    
    /**
     * Get top subjects
     */
    private function getTopSubjects()
    {
        // For tutor jobs - subjects is JSON, so we need to process differently
        $tutorJobs = TutorJob::select('subjects')->get();
        $jobSubjects = collect();
        foreach ($tutorJobs as $job) {
            if ($job->subjects && is_array($job->subjects)) {
                foreach ($job->subjects as $subject) {
                    $jobSubjects->push($subject);
                }
            }
        }
        $jobSubjectCounts = $jobSubjects->countBy()->sortDesc()->take(10);
        
        // For student vacancies - check if subject column exists
        $vacancySubjects = collect();
        try {
            $studentVacancies = StudentVacancy::select('subject')->get();
            $vacancySubjects = $studentVacancies->pluck('subject')->countBy()->sortDesc()->take(10);
        } catch (\Exception $e) {
            // If subject column doesn't exist, return empty collection
            $vacancySubjects = collect();
        }
        
        return [
            'jobs' => $jobSubjectCounts,
            'vacancies' => $vacancySubjects
        ];
    }
    
    /**
     * Export analytics data
     */
    public function export(Request $request)
    {
        $filename = "analytics_overview_" . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() {
            $file = fopen('php://output', 'w');
            $this->exportOverviewData($file);
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
    
    private function exportOverviewData($file)
    {
        fputcsv($file, ['Metric', 'Value', 'Growth %']);
        $stats = $this->getOverviewStats();
        
        foreach ($stats as $key => $value) {
            if (strpos($key, '_growth') === false) {
                $growth = $stats[$key . '_growth'] ?? 'N/A';
                fputcsv($file, [ucfirst(str_replace('_', ' ', $key)), $value, $growth]);
            }
        }
    }
}
