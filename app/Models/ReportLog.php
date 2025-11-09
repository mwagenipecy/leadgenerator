<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReportLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'creditinfo_id',
        'report_url',
        'search_full_name',
        'search_id_number',
        'search_phone_number',
        'individual_full_name',
        'individual_national_id',
        'individual_date_of_birth',
        'search_criteria',
        'ip_address',
        'user_agent',
        'status',
        'error_message',
        'retrieved_at',
    ];

    protected $casts = [
        'retrieved_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that retrieved the report
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope a query to only include successful reports
     */
    public function scopeSuccessful($query)
    {
        return $query->where('status', 'success');
    }

    /**
     * Scope a query to only include failed reports
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    /**
     * Scope a query to filter by user
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope a query to filter by date range
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('retrieved_at', [$startDate, $endDate]);
    }
}
