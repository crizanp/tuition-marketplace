<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'tutor_id',
        'job_id',
        'rating',
        'review'
    ];

    protected $casts = [
        'rating' => 'integer'
    ];

    /**
     * Get the user that gave the rating.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the tutor that was rated.
     */
    public function tutor()
    {
        return $this->belongsTo(Tutor::class);
    }

    /**
     * Get the job associated with the rating.
     */
    public function job()
    {
        return $this->belongsTo(TutorJob::class, 'job_id');
    }
}
