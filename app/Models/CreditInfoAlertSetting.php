<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CreditInfoAlertSetting extends Model
{
    protected $fillable = [
        'user_id',
        'alert_when_credit_info_searched',
        'alert_when_score_changed',
        'alert_when_report_retrieved',
        'alert_when_lender_can_find_loan',
        'alert_when_reach_visible_notify_email',
        'notify_via_email',
        'subscription_periods',
    ];

    protected $casts = [
        'alert_when_credit_info_searched' => 'boolean',
        'alert_when_score_changed' => 'boolean',
        'alert_when_report_retrieved' => 'boolean',
        'alert_when_lender_can_find_loan' => 'boolean',
        'alert_when_reach_visible_notify_email' => 'boolean',
        'notify_via_email' => 'boolean',
        'subscription_periods' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get subscription start and end date for a service (by slug).
     * Returns ['started_at' => Carbon, 'ends_at' => Carbon] or null if not set.
     */
    public function getSubscriptionPeriod(string $slug): ?array
    {
        $periods = $this->subscription_periods ?? [];
        $data = $periods[$slug] ?? null;
        if (!$data || empty($data['started_at'])) {
            return null;
        }
        return [
            'started_at' => Carbon::parse($data['started_at']),
            'ends_at' => !empty($data['ends_at']) ? Carbon::parse($data['ends_at']) : null,
        ];
    }
}
