<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LenderCommissionSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'lender_id',
        'commission_type',
        'commission_percentage',
        'commission_fixed_amount',
        'minimum_amount',
        'maximum_amount',
        'special_terms',
        'is_active',
        'updated_by',
    ];

    protected $casts = [
        'lender_id' => 'integer',
        'commission_percentage' => 'decimal:2',
        'commission_fixed_amount' => 'decimal:2',
        'minimum_amount' => 'decimal:2',
        'maximum_amount' => 'decimal:2',
        'is_active' => 'boolean',
        'updated_by' => 'integer',
    ];

    /**
     * Get the lender that this commission setting belongs to
     */
    public function lender()
    {
        return $this->belongsTo(Lender::class);
    }

    /**
     * Get the user who last updated this setting
     */
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Calculate commission for a given loan amount
     */
    public function calculateCommission($loanAmount)
    {
        if ($this->commission_type === 'percentage') {
            $commission = ($loanAmount * $this->commission_percentage) / 100;
        } else {
            $commission = $this->commission_fixed_amount;
        }

        // Apply minimum limit
        if ($this->minimum_amount && $commission < $this->minimum_amount) {
            $commission = $this->minimum_amount;
        }

        // Apply maximum limit
        if ($this->maximum_amount && $commission > $this->maximum_amount) {
            $commission = $this->maximum_amount;
        }

        return $commission;
    }

    /**
     * Get commission setting for a specific lender
     */
    public static function getForLender($lenderId)
    {
        return static::where('lender_id', $lenderId)
            ->where('is_active', true)
            ->first();
    }

    /**
     * Get default commission settings from system settings
     */
    public static function getDefaultSettings()
    {
        return [
            'commission_type' => SystemSetting::getValue('default_commission_type', 'percentage'),
            'commission_percentage' => (float) SystemSetting::getValue('default_commission_percentage', 5.0),
            'commission_fixed_amount' => (float) SystemSetting::getValue('default_commission_fixed_amount', 0),
            'minimum_amount' => (float) SystemSetting::getValue('minimum_commission_amount', 100),
            'maximum_amount' => SystemSetting::getValue('maximum_commission_amount') ? (float) SystemSetting::getValue('maximum_commission_amount') : null,
        ];
    }

    /**
     * Calculate commission using either lender-specific or default settings
     */
    public static function calculateForLender($lenderId, $loanAmount)
    {
        $lenderSetting = static::getForLender($lenderId);
        
        if ($lenderSetting) {
            return $lenderSetting->calculateCommission($loanAmount);
        }

        // Use default settings
        $defaults = static::getDefaultSettings();
        
        if ($defaults['commission_type'] === 'percentage') {
            $commission = ($loanAmount * $defaults['commission_percentage']) / 100;
        } else {
            $commission = $defaults['commission_fixed_amount'];
        }

        // Apply minimum limit
        if ($defaults['minimum_amount'] && $commission < $defaults['minimum_amount']) {
            $commission = $defaults['minimum_amount'];
        }

        // Apply maximum limit
        if ($defaults['maximum_amount'] && $commission > $defaults['maximum_amount']) {
            $commission = $defaults['maximum_amount'];
        }

        return $commission;
    }
}