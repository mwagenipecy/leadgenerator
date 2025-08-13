<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class ApplicationLenderSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'application_id', 'lender_id', 'loan_product_id', 'status',
        'submission_data', 'submitted_at', 'reviewed_at', 'decision_at',
        'lender_response', 'offered_amount', 'offered_interest_rate',
        'offered_tenure_months', 'offered_terms', 'rejection_reason','booking_fee','booked_at','cancelled_at','cancellation_reason','notes'
    ];

    protected $casts = [
        'submission_data' => 'array',
        'lender_response' => 'array',
        'offered_terms' => 'array',
        'submitted_at' => 'timestamp',
        'reviewed_at' => 'timestamp',
        'decision_at' => 'timestamp',
        'offered_amount' => 'decimal:2',
        'offered_interest_rate' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }

    public function lender(): BelongsTo
    {
        return $this->belongsTo(Lender::class);
    }

    public function loanProduct(): BelongsTo
    {
        return $this->belongsTo(LoanProduct::class);
    }

    // Get status badge color
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'pending' => 'gray',
            'submitted' => 'blue',
            'under_review' => 'yellow',
            'approved' => 'green',
            'rejected' => 'red',
            'withdrawn' => 'gray',
            'expired' => 'red',
            default => 'gray'
        };
    }

    // Check if can be withdrawn
    public function canBeWithdrawn(): bool
    {
        return in_array($this->status, ['pending', 'submitted', 'under_review']);
    }
}
