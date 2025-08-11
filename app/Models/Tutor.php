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
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'subjects' => 'array',
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
}