<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TutorProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'tutor_id',
        'bio',
        'skills',
        'education',
        'languages',
        'introduction_video',
        'availability_schedule',
        'hourly_availability',
        'availability_status',
        'unavailable_until',
        'portfolio_items',
        'additional_certifications',
        'profile_views',
        'rating',
        'total_ratings',
        'total_students',
        'total_hours',
        'is_featured',
    ];

    protected $casts = [
        'skills' => 'array',
        'education' => 'array',
        'languages' => 'array',
        'availability_schedule' => 'array',
        'hourly_availability' => 'array',
        'portfolio_items' => 'array',
        'additional_certifications' => 'array',
        'unavailable_until' => 'datetime',
        'rating' => 'decimal:2',
        'is_featured' => 'boolean',
    ];

    /**
     * Get the tutor that owns the profile.
     */
    public function tutor()
    {
        return $this->belongsTo(Tutor::class);
    }

    /**
     * Get profile completion percentage.
     */
    public function getCompletionPercentage()
    {
        $fields = [
            'bio' => !empty($this->bio),
            'languages' => !empty($this->languages),
            'introduction_video' => !empty($this->introduction_video),
            'availability_schedule' => !empty($this->availability_schedule),
            'portfolio_items' => !empty($this->portfolio_items),
        ];

        $completed = array_filter($fields);
        return round((count($completed) / count($fields)) * 100);
    }

    /**
     * Increment profile views.
     */
    public function incrementViews()
    {
        $this->increment('profile_views');
    }

    /**
     * Get formatted availability schedule.
     */
    public function getFormattedSchedule()
    {
        if (!$this->availability_schedule) {
            return [];
        }

        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        $schedule = [];

        foreach ($days as $day) {
            $dayKey = strtolower($day);
            if (isset($this->availability_schedule[$dayKey])) {
                $daySchedule = $this->availability_schedule[$dayKey];
                $schedule[] = [
                    'day' => $day,
                    'start' => $daySchedule['start'] ?? null,
                    'end' => $daySchedule['end'] ?? null,
                    'off' => $daySchedule['off'] ?? false,
                ];
            }
        }

        return $schedule;
    }

    /**
     * Get formatted hourly availability for each day.
     */
    public function getFormattedHourlyAvailability()
    {
        $daysOfWeek = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        $timeSlots = [];
        
        // Generate 24-hour time slots (4 AM to 10 PM)
        for ($hour = 4; $hour <= 22; $hour++) {
            $timeSlots[] = [
                'value' => $hour,
                'label' => date('g:i A', mktime($hour, 0, 0))
            ];
        }

        $availability = [];
        
        foreach ($daysOfWeek as $day) {
            $dayKey = strtolower($day);
            $dayAvailability = [
                'day' => $day,
                'slots' => []
            ];
            
            if ($this->hourly_availability && isset($this->hourly_availability[$dayKey])) {
                $daySlots = $this->hourly_availability[$dayKey];
                foreach ($timeSlots as $slot) {
                    $dayAvailability['slots'][] = [
                        'hour' => $slot['value'],
                        'label' => $slot['label'],
                        'available' => in_array($slot['value'], $daySlots)
                    ];
                }
            } else {
                // Default: available for some hours (9 AM to 6 PM on weekdays, 10 AM to 4 PM on Saturday, unavailable on Sunday)
                $defaultSlots = [];
                if ($day === 'Sunday') {
                    $defaultSlots = [];
                } elseif ($day === 'Saturday') {
                    $defaultSlots = [10, 11, 12, 13, 14, 15, 16]; // 10 AM to 4 PM
                } else {
                    $defaultSlots = [9, 10, 11, 12, 13, 14, 15, 16, 17, 18]; // 9 AM to 6 PM
                }
                
                foreach ($timeSlots as $slot) {
                    $dayAvailability['slots'][] = [
                        'hour' => $slot['value'],
                        'label' => $slot['label'],
                        'available' => in_array($slot['value'], $defaultSlots)
                    ];
                }
            }
            
            $availability[] = $dayAvailability;
        }

        return $availability;
    }

    /**
     * Get time slots for display.
     */
    public function getTimeSlots()
    {
        $timeSlots = [];
        
        // Generate 24-hour time slots (4 AM to 10 PM)
        for ($hour = 4; $hour <= 22; $hour++) {
            $timeSlots[] = [
                'value' => $hour,
                'label' => date('g:i A', mktime($hour, 0, 0))
            ];
        }

        return $timeSlots;
    }
}
