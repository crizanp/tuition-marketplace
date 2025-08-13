<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContactMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'message',
        'job_id',
        'tutor_id',
        'student_id',
        'type',
        'status',
        'admin_response',
        'responded_at'
    ];

    protected $casts = [
        'responded_at' => 'datetime',
    ];

    /**
     * Relationship with TutorJob
     */
    public function job(): BelongsTo
    {
        return $this->belongsTo(TutorJob::class, 'job_id');
    }

    /**
     * Relationship with Tutor
     */
    public function tutor(): BelongsTo
    {
        return $this->belongsTo(Tutor::class);
    }

    /**
     * Relationship with User (Student)
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    /**
     * Scope for unread messages
     */
    public function scopeUnread($query)
    {
        return $query->where('status', 'unread');
    }

    /**
     * Scope for read messages
     */
    public function scopeRead($query)
    {
        return $query->where('status', 'read');
    }

    /**
     * Scope for responded messages
     */
    public function scopeResponded($query)
    {
        return $query->where('status', 'responded');
    }

    /**
     * Mark message as read
     */
    public function markAsRead()
    {
        $this->update(['status' => 'read']);
    }

    /**
     * Mark message as responded
     */
    public function markAsResponded($response = null)
    {
        $this->update([
            'status' => 'responded',
            'admin_response' => $response,
            'responded_at' => now()
        ]);
    }
}
