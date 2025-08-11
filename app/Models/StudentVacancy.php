<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentVacancy extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'subject',
        'grade_level',
        'budget_min',
        'budget_max',
        'schedule_days',
        'schedule_times',
        'duration_hours',
        'location_type',
        'address',
        'urgency',
        'requirements',
        'status',
        'admin_notes',
        'approved_at',
        'rejected_at',
    ];

    protected $casts = [
        'schedule_days' => 'array',
        'schedule_times' => 'array',
        'requirements' => 'array',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];

    /**
     * Relationship with User (Student)
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Scope for pending vacancies
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for approved vacancies
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope for rejected vacancies
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    /**
     * Get formatted budget range
     */
    public function getBudgetRangeAttribute()
    {
        return "Rs. {$this->budget_min} - Rs. {$this->budget_max}";
    }

    /**
     * Get formatted schedule
     */
    public function getFormattedScheduleAttribute()
    {
        if (!$this->schedule_days || !$this->schedule_times) {
            return 'Schedule not specified';
        }

        $days = implode(', ', $this->schedule_days);
        $times = implode(', ', $this->schedule_times);
        
        return "{$days} | {$times}";
    }
}
