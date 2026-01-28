<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Promotion extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'message',
        'target_audience',
        'send_email',
        'send_notification',
        'status',
        'scheduled_at',
        'sent_at',
        'total_recipients',
        'emails_sent',
        'notifications_sent',
        'emails_failed',
        'notifications_failed',
        'error_message',
        'created_by',
    ];

    protected $casts = [
        'target_audience' => 'array',
        'send_email' => 'boolean',
        'send_notification' => 'boolean',
        'scheduled_at' => 'datetime',
        'sent_at' => 'datetime',
    ];

    /**
     * Get the user who created this promotion.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Scope for draft promotions.
     */
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    /**
     * Scope for scheduled promotions.
     */
    public function scopeScheduled($query)
    {
        return $query->where('status', 'scheduled');
    }

    /**
     * Scope for sent promotions.
     */
    public function scopeSent($query)
    {
        return $query->where('status', 'sent');
    }
}
