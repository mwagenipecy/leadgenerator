<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CreditInfoService extends Model
{
    public const PRICE_INTERVAL_MONTHLY = 'monthly';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'currency',
        'price_interval',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /** Slug values that match CreditInfoAlertSetting columns. */
    public const SLUG_CREDIT_INFO_SEARCHED = 'alert_when_credit_info_searched';
    public const SLUG_SCORE_CHANGED = 'alert_when_score_changed';
    public const SLUG_REPORT_RETRIEVED = 'alert_when_report_retrieved';
    public const SLUG_LENDER_CAN_FIND_LOAN = 'alert_when_lender_can_find_loan';
    public const SLUG_REACH_VISIBLE_NOTIFY_EMAIL = 'alert_when_reach_visible_notify_email';

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    /**
     * Check if the current user (or given settings) is subscribed to this service.
     */
    public function isSubscribedFor(?CreditInfoAlertSetting $settings): bool
    {
        if (!$settings) {
            return false;
        }
        return (bool) ($settings->{$this->slug} ?? false);
    }

    public function formattedPrice(): string
    {
        $amount = number_format((float) $this->price, 0) . ' ' . ($this->currency ?? 'TZS');
        $interval = ($this->price_interval ?? self::PRICE_INTERVAL_MONTHLY) === self::PRICE_INTERVAL_MONTHLY
            ? '/' . __('creditinfo_alert.month')
            : '';
        return $amount . $interval;
    }

    public function isMonthly(): bool
    {
        return ($this->price_interval ?? self::PRICE_INTERVAL_MONTHLY) === self::PRICE_INTERVAL_MONTHLY;
    }
}
