<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Str;

class TutorJob extends Model
{
    use HasFactory;

    protected $fillable = [
        'tutor_id',
        'title',
        'description',
        'subjects',
        'hourly_rate',
        'country',
        'state',
        'district',
        'place',
        'landmark',
        'ward_no',
        'postal_code',
        'teaching_mode',
        'preferred_times',
        'gender_preference',
        'student_level',
        'gallery',
        'requirements',
        'max_students',
        'session_type',
        'status',
        'is_featured',
        'expires_at',
        'views',
        'inquiries',
    ];

    protected $casts = [
        'subjects' => 'array',
        'preferred_times' => 'array',
        'gallery' => 'array',
        'hourly_rate' => 'decimal:2',
        'is_featured' => 'boolean',
        'expires_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the tutor that owns the job.
     */
    public function tutor()
    {
        return $this->belongsTo(Tutor::class);
    }

    /**
     * Scope to get active jobs.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active')
                    ->where(function($q) {
                        $q->whereNull('expires_at')
                          ->orWhere('expires_at', '>', now());
                    });
    }

    /**
     * Scope to get featured jobs.
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope to filter by location.
     */
    public function scopeByLocation($query, $country = null, $state = null, $district = null)
    {
        if ($country) {
            $query->where('country', $country);
        }
        if ($state) {
            $query->where('state', $state);
        }
        if ($district) {
            $query->where('district', $district);
        }
        return $query;
    }

    /**
     * Scope to filter by teaching mode.
     */
    public function scopeByTeachingMode($query, $mode)
    {
        return $query->where('teaching_mode', $mode);
    }

    /**
     * Scope to filter by subjects.
     */
    public function scopeBySubject($query, $subject)
    {
        return $query->whereJsonContains('subjects', $subject);
    }

    /**
     * Increment views count.
     */
    public function incrementViews()
    {
        $this->increment('views');
    }

    /**
     * Increment inquiries count.
     */
    public function incrementInquiries()
    {
        $this->increment('inquiries');
    }

    /**
     * Check if job is expired.
     */
    public function isExpired()
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    /**
     * Get formatted location.
     */
    public function getFormattedLocationAttribute()
    {
        $location = $this->place . ', ' . $this->district . ', ' . $this->state;
        if ($this->landmark) {
            $location = $this->landmark . ', ' . $location;
        }
        return $location;
    }

    /**
     * Get status badge class.
     */
    public function getStatusBadgeClassAttribute()
    {
        return match($this->status) {
            'active' => 'badge-success',
            'inactive' => 'badge-secondary',
            'paused' => 'badge-warning',
            default => 'badge-secondary'
        };
    }

    /**
     * Get teaching mode label.
     */
    public function getTeachingModeLabel()
    {
        return match($this->teaching_mode) {
            'home' => 'Home Tuition',
            'online' => 'Online Classes',
            'institute' => 'Institute Classes',
            'any' => 'Flexible Location',
            default => ucfirst($this->teaching_mode)
        };
    }

    /**
     * Get the URL for this job.
     */
    public function getUrlAttribute()
    {
        $tutorName = Str::slug($this->tutor->name);
        return route('jobs.show', ['tutorName' => $tutorName, 'jobId' => $this->id]);
    }
}
