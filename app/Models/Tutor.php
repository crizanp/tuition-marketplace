<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\TutorResetPasswordNotification;
use App\Notifications\TutorEmailVerificationNotification;

class Tutor extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'bio',
        'subjects',
        'hourly_rate',
        'status',
        'status_reason',
        'status_updated_at',
        'approved_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'subjects' => 'array',
        'status_updated_at' => 'datetime',
        'approved_at' => 'datetime',
    ];

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new TutorResetPasswordNotification($token));
    }

    /**
     * Send the email verification notification.
     *
     * @return void
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new TutorEmailVerificationNotification);
    }

    /**
     * Get the tutor's KYC data.
     */
    public function kyc()
    {
        return $this->hasOne(TutorKyc::class);
    }

    /**
     * Get the tutor's profile data.
     */
    public function profile()
    {
        return $this->hasOne(TutorProfile::class);
    }

    /**
     * Get the tutor's jobs.
     */
    public function jobs()
    {
        return $this->hasMany(TutorJob::class);
    }

    /**
     * Alias for jobs relationship (used in admin controllers)
     */
    public function tutorJobs()
    {
        return $this->hasMany(TutorJob::class);
    }

    /**
     * Get the tutor's ratings.
     */
    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    /**
     * Get the tutor's vacancy applications.
     */
    public function vacancyApplications()
    {
        return $this->hasMany(VacancyApplication::class);
    }

    /**
     * Get the tutor's contact messages.
     */
    public function contactMessages()
    {
        return $this->hasMany(ContactMessage::class);
    }

    /**
     * Check if tutor is active
     */
    public function isActive()
    {
        return $this->status === 'active';
    }

    /**
     * Check if tutor is pending
     */
    public function isPending()
    {
        return $this->status === 'pending';
    }

    /**
     * Check if tutor is suspended
     */
    public function isSuspended()
    {
        return $this->status === 'suspended';
    }

    /**
     * Check if tutor is banned
     */
    public function isBanned()
    {
        return $this->status === 'banned';
    }

    /**
     * Average rating accessor (returns rounded value or null)
     */
    public function getAverageRatingAttribute()
    {
        // Prefer eager-loaded aggregate if present
        $avg = $this->ratings_avg_rating ?? null;

        if ($avg === null) {
            $avg = $this->ratings()->avg('rating');
        }

        return $avg ? round($avg, 1) : null;
    }
}