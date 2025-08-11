<?php

if (!function_exists('job_url')) {
    /**
     * Generate URL for a job using the new structure
     */
    function job_url($job) {
        $tutorName = \Illuminate\Support\Str::slug($job->tutor->name);
        return route('jobs.show', ['tutorName' => $tutorName, 'jobId' => $job->id]);
    }
}
