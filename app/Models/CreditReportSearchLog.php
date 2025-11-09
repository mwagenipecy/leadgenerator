<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CreditReportSearchLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'search_full_name',
        'search_id_number',
        'search_phone_number',
        'search_id_number_type',
        'search_criteria',
        'results_count',
        'status',
        'error_message',
        'ip_address',
        'user_agent',
        'searched_at',
    ];

    protected $casts = [
        'searched_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that performed the search
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope for successful searches
     */
    public function scopeSuccessful($query)
    {
        return $query->where('status', 'success');
    }

    /**
     * Scope for failed searches
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    /**
     * Scope for searches with no results
     */
    public function scopeNoResults($query)
    {
        return $query->where('status', 'no_results');
    }
}
