<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CommissionPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_number',
        'commission_bill_id',
        'amount',
        'payment_method',
        'payment_reference',
        'payment_date',
        'notes',
        'payment_details',
        'recorded_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'date',
        'payment_details' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($payment) {
            if (empty($payment->payment_number)) {
                $payment->payment_number = static::generatePaymentNumber();
            }
        });
    }

    public static function generatePaymentNumber(): string
    {
        $prefix = 'PAY';
        $year = now()->year;
        $month = now()->format('m');
        
        $lastPayment = static::whereYear('created_at', $year)
            ->whereMonth('created_at', now()->month)
            ->orderBy('id', 'desc')
            ->first();

        $sequence = $lastPayment ? (int) substr($lastPayment->payment_number, -4) + 1 : 1;
        
        return sprintf('%s-%d%s-%04d', $prefix, $year, $month, $sequence);
    }

    public function commissionBill(): BelongsTo
    {
        return $this->belongsTo(CommissionBill::class);
    }

    public function recordedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}



