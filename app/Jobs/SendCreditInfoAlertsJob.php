<?php

namespace App\Jobs;

use App\Models\CreditInfoAlertSetting;
use App\Models\User;
use App\Notifications\CreditInfoAlertNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendCreditInfoAlertsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** Alert type must match CreditInfoAlertSetting column name. */
    public const ALERT_CREDIT_INFO_SEARCHED = 'alert_when_credit_info_searched';
    public const ALERT_SCORE_CHANGED = 'alert_when_score_changed';
    public const ALERT_REPORT_RETRIEVED = 'alert_when_report_retrieved';
    public const ALERT_LENDER_CAN_FIND_LOAN = 'alert_when_lender_can_find_loan';
    public const ALERT_REACH_VISIBLE_NOTIFY_EMAIL = 'alert_when_reach_visible_notify_email';

    public $tries = 3;
    public $timeout = 120;

    public function __construct(
        public string $alertType,
        public string $title,
        public string $message,
        public ?string $link = null
    ) {
        // Uses default queue so notifications are always processed asynchronously
    }

    public function handle(): void
    {
        if (!in_array($this->alertType, [
            self::ALERT_CREDIT_INFO_SEARCHED,
            self::ALERT_SCORE_CHANGED,
            self::ALERT_REPORT_RETRIEVED,
            self::ALERT_LENDER_CAN_FIND_LOAN,
            self::ALERT_REACH_VISIBLE_NOTIFY_EMAIL,
        ], true)) {
            Log::warning('SendCreditInfoAlertsJob: unknown alert type', ['alert_type' => $this->alertType]);
            return;
        }

        $settings = CreditInfoAlertSetting::where($this->alertType, true)
            ->with('user')
            ->get();

        foreach ($settings as $setting) {
            $user = $setting->user;
            if (!$user || !$user->email) {
                continue;
            }
            try {
                $user->notify(new CreditInfoAlertNotification(
                    $this->alertType,
                    $this->title,
                    $this->message,
                    $this->link,
                    (bool) $setting->notify_via_email
                ));
            } catch (\Throwable $e) {
                Log::error('SendCreditInfoAlertsJob: failed to queue notification for user', [
                    'user_id' => $user->id,
                    'alert_type' => $this->alertType,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }
}
