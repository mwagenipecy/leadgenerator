<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class IntegrationLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'integration_id',
        'application_id',
        'trigger_event',
        'status',
        'request_payload',
        'request_url',
        'request_headers',
        'response_body',
        'response_status',
        'response_headers',
        'error_message',
        'retry_count',
        'last_attempt_at',
        'next_retry_at',
        'response_time_ms',
    ];

    protected $casts = [
        'request_payload' => 'array',
        'request_headers' => 'array',
        'response_headers' => 'array',
        'last_attempt_at' => 'datetime',
        'next_retry_at' => 'datetime',
        'response_time_ms' => 'decimal:2',
    ];

    /**
     * Get the integration
     */
    public function integration()
    {
        return $this->belongsTo(Integration::class);
    }

    /**
     * Get the application
     */
    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    /**
     * Check if this log can be retried
     */
    public function canRetry()
    {
        return $this->status === 'failed' && 
               $this->retry_count < $this->integration->retry_attempts &&
               ($this->next_retry_at === null || $this->next_retry_at <= now());
    }

    /**
     * Mark log for retry
     */
    public function markForRetry()
    {
        $this->update([
            'status' => 'retrying',
            'next_retry_at' => now()->addMinutes(pow(2, $this->retry_count)), // Exponential backoff
        ]);
    }
}
