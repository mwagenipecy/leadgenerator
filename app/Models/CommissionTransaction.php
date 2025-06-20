<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class CommissionTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_number',
        'application_id',
        'lender_id',
        'loan_amount',
        'commission_amount',
        'commission_type',
        'commission_rate',
        'status',
        'due_date',
        'paid_date',
        'penalty_amount',
        'payment_method',
        'payment_reference',
        'notes',
        'metadata',
        'created_by',
    ];

    protected $casts = [
        'application_id' => 'integer',
        'lender_id' => 'integer',
        'loan_amount' => 'decimal:2',
        'commission_amount' => 'decimal:2',
        'commission_rate' => 'decimal:2',
        'penalty_amount' => 'decimal:2',
        'due_date' => 'date',
        'paid_date' => 'date',
        'metadata' => 'array',
        'created_by' => 'integer',
    ];

    /**
     * Get the application this commission belongs to
     */
    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    /**
     * Get the lender this commission belongs to
     */
    public function lender()
    {
        return $this->belongsTo(Lender::class);
    }

    /**
     * Get the user who created this transaction
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Generate unique transaction number
     */
    public static function generateTransactionNumber()
    {
        $prefix = 'COM';
        $date = now()->format('Ymd');
        $sequence = str_pad(static::whereDate('created_at', now())->count() + 1, 4, '0', STR_PAD_LEFT);
        return $prefix . $date . $sequence;
    }

    /**
     * Calculate total amount including penalties
     */
    public function getTotalAmountAttribute()
    {
        return $this->commission_amount + $this->penalty_amount;
    }

    /**
     * Check if transaction is overdue
     */
    public function getIsOverdueAttribute()
    {
        return $this->status === 'pending' && $this->due_date < now()->toDateString();
    }

    /**
     * Calculate penalty amount based on days overdue
     */
    public function calculatePenalty()
    {
        if ($this->status !== 'pending' || $this->due_date >= now()->toDateString()) {
            return 0;
        }

        $gracePeriod = (int) SystemSetting::getValue('grace_period_days', 7);
        $penaltyRate = (float) SystemSetting::getValue('late_payment_penalty_percentage', 2.0);
        
        $daysOverdue = Carbon::parse($this->due_date)->diffInDays(now()) - $gracePeriod;
        
        if ($daysOverdue <= 0) {
            return 0;
        }

        return ($this->commission_amount * $penaltyRate / 100) * $daysOverdue;
    }

    /**
     * Mark transaction as paid
     */
    public function markAsPaid($paymentMethod = null, $paymentReference = null)
    {
        $this->update([
            'status' => 'paid',
            'paid_date' => now(),
            'payment_method' => $paymentMethod,
            'payment_reference' => $paymentReference,
        ]);
    }

    /**
     * Get overdue transactions
     */
    public static function getOverdueTransactions()
    {
        return static::where('status', 'pending')
            ->where('due_date', '<', now()->toDateString())
            ->with(['lender', 'application']);
    }
}