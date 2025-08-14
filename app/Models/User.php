<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\StudentResetPasswordNotification;
use App\Notifications\StudentEmailVerificationNotification;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'grade_level',
    'qualification',
    'institution',
    'location_district',
    'location_place',
    'location_landmark',
    'whatsapp',
    'profile_picture',
    'bio',
        'preferred_subjects',
        'status',
        'status_reason',
        'status_updated_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'preferred_subjects' => 'array',
    'qualification' => 'string',
    'institution' => 'string',
    'location_district' => 'string',
    'location_place' => 'string',
    'location_landmark' => 'string',
    'whatsapp' => 'string',
        'profile_picture' => 'string',
        'bio' => 'string',
        'status_updated_at' => 'datetime',
    ];

    /**
     * Calculate profile completion percentage based on presence of key fields.
     */
    public function profileCompletionPercentage()
    {
        $fields = [
            'name', 'email', 'phone', 'grade_level', 'preferred_subjects',
            'qualification', 'institution', 'location_district', 'location_place', 'whatsapp',
            'profile_picture', 'bio'
        ];

        $total = count($fields);
        $filled = 0;
        foreach ($fields as $f) {
            $val = $this->{$f} ?? null;
            if (is_array($val)) {
                if (count($val)) $filled++;
            } else {
                if (!empty($val)) $filled++;
            }
        }

        return intval(($filled / $total) * 100);
    }

    public function isProfileVerified()
    {
        return $this->profileCompletionPercentage() >= 80;
    }

    /**
     * Send the password reset notification.
     *
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new StudentResetPasswordNotification($token));
    }

    /**
     * Send the email verification notification.
     *
     * @return void
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new StudentEmailVerificationNotification);
    }

    /**
     * Relationship with StudentVacancy
     */
    public function vacancies()
    {
        return $this->hasMany(\App\Models\StudentVacancy::class);
    }

    /**
     * Relationship with Wishlist
     */
    public function wishlist()
    {
        return $this->hasMany(Wishlist::class);
    }

    /**
     * Relationship with Wishlist (plural form for compatibility)
     */
    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    /**
     * Get wishlisted jobs through the wishlist pivot table
     */
    public function wishlistedJobs()
    {
        return $this->belongsToMany(TutorJob::class, 'wishlists', 'user_id', 'tutor_job_id')->withTimestamps();
    }

    /**
     * Relationship with Ratings
     */
    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    /**
     * Get the user's contact messages.
     */
    public function contactMessages()
    {
        return $this->hasMany(ContactMessage::class, 'student_id');
    }

    /**
     * Check if user is active
     */
    public function isActive()
    {
        return $this->status === 'active';
    }

    /**
     * Check if user is suspended
     */
    public function isSuspended()
    {
        return $this->status === 'suspended';
    }

    /**
     * Check if user is banned
     */
    public function isBanned()
    {
        return $this->status === 'banned';
    }
}