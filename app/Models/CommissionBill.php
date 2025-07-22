<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CommissionBill extends Model
{
    use HasFactory;

    protected $fillable = [
        'bill_number',
        'application_id',
        'lender_id',
        'commission_type',
        'commission_rate',
        'loan_amount',
        'commission_amount',
        'tax_amount',
        'total_amount',
        'status',
        'due_date',
        'sent_at',
        'paid_at',
        'payment_method',
        'payment_reference',
        'notes',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'loan_amount' => 'decimal:2',
        'commission_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'commission_rate' => 'decimal:2',
        'due_date' => 'date',
        'sent_at' => 'datetime',
        'paid_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($bill) {
            if (empty($bill->bill_number)) {
                $bill->bill_number = static::generateBillNumber();
            }
        });
    }

    public static function generateBillNumber(): string
    {
        $prefix = 'BILL';
        $year = now()->year;
        $month = now()->format('m');
        
        $lastBill = static::whereYear('created_at', $year)
            ->whereMonth('created_at', now()->month)
            ->orderBy('id', 'desc')
            ->first();

        $sequence = $lastBill ? (int) substr($lastBill->bill_number, -4) + 1 : 1;
        
        return sprintf('%s-%d%s-%04d', $prefix, $year, $month, $sequence);
    }

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }

    public function lender(): BelongsTo
    {
        return $this->belongsTo(Lender::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(CommissionPayment::class);
    }

    public function getTotalPaidAttribute(): float
    {
        return $this->payments->sum('amount');
    }

    public function getBalanceAttribute(): float
    {
        return $this->total_amount - $this->total_paid;
    }

    public function getIsOverdueAttribute(): bool
    {
        return $this->status !== 'paid' && $this->due_date < now()->toDateString();
    }

    public function getDaysOverdueAttribute(): int
    {
        if (!$this->is_overdue) {
            return 0;
        }

        return now()->diffInDays($this->due_date);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', '!=', 'paid')
            ->where('due_date', '<', now()->toDateString());
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopeForLender($query, $lenderId)
    {
        return $query->where('lender_id', $lenderId);
    }

    public function markAsPaid($paymentMethod = null, $paymentReference = null): void
    {
        $this->update([
            'status' => 'paid',
            'paid_at' => now(),
            'payment_method' => $paymentMethod,
            'payment_reference' => $paymentReference,
        ]);
    }

    public function markAsOverdue(): void
    {
        if ($this->status !== 'paid' && $this->due_date < now()->toDateString()) {
            $this->update(['status' => 'overdue']);
        }
    }
}