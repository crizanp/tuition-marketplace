<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TutorKyc extends Model
{
    use HasFactory;

    protected $table = 'tutor_kyc';

    protected $fillable = [
        'tutor_id',
        'name',
        'email',
        'phone',
        'description',
        'profile_photo',
        'hourly_rate',
        'citizenship_front',
        'citizenship_back',
        'qualification',
        'qualification_proof',
        'has_certificate',
        'certificate_file',
        'subjects_expertise',
        'exact_location',
        'status',
        'rejection_reason',
        'submitted_at',
        'reviewed_at',
    ];

    protected $casts = [
        'subjects_expertise' => 'array',
        'has_certificate' => 'boolean',
        'hourly_rate' => 'decimal:2',
        'submitted_at' => 'datetime',
        'reviewed_at' => 'datetime',
    ];

    /**
     * Get the tutor that owns the KYC data.
     */
    public function tutor()
    {
        return $this->belongsTo(Tutor::class);
    }
}