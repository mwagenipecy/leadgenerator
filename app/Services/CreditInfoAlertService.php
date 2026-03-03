<?php

namespace App\Services;

use App\Jobs\SendCreditInfoAlertsJob;

class CreditInfoAlertService
{
    /**
     * Queue CreditInfo alert notifications for all users who requested this alert type.
     * Notifications are always queued (job + ShouldQueue notification); never sent synchronously.
     */
    public static function queueAlert(
        string $alertType,
        string $title,
        string $message,
        ?string $link = null
    ): void {
        SendCreditInfoAlertsJob::dispatch($alertType, $title, $message, $link);
    }

    /**
     * Queue "credit info searched" alert.
     */
    public static function queueCreditInfoSearchedAlert(?string $link = null): void
    {
        self::queueAlert(
            SendCreditInfoAlertsJob::ALERT_CREDIT_INFO_SEARCHED,
            __('creditinfo_alert.alert_when_credit_info_searched'),
            __('creditinfo_alert.message_credit_info_searched'),
            $link ?? route('credit.report')
        );
    }

    /**
     * Queue "score changed" alert.
     */
    public static function queueScoreChangedAlert(string $message, ?string $link = null): void
    {
        self::queueAlert(
            SendCreditInfoAlertsJob::ALERT_SCORE_CHANGED,
            __('creditinfo_alert.alert_when_score_changed'),
            $message,
            $link ?? route('credit.report')
        );
    }

    /**
     * Queue "report retrieved" alert.
     */
    public static function queueReportRetrievedAlert(?string $link = null): void
    {
        self::queueAlert(
            SendCreditInfoAlertsJob::ALERT_REPORT_RETRIEVED,
            __('creditinfo_alert.alert_when_report_retrieved'),
            __('creditinfo_alert.message_report_retrieved'),
            $link ?? route('credit.report')
        );
    }

    /**
     * Queue "lender can find loan" alert.
     */
    public static function queueLenderCanFindLoanAlert(?string $link = null): void
    {
        self::queueAlert(
            SendCreditInfoAlertsJob::ALERT_LENDER_CAN_FIND_LOAN,
            __('creditinfo_alert.alert_when_lender_can_find_loan'),
            __('creditinfo_alert.message_lender_can_find_loan'),
            $link ?? route('dashboard')
        );
    }

    /**
     * Queue "reach visible - notify via email" alert.
     */
    public static function queueReachVisibleNotifyEmailAlert(?string $link = null): void
    {
        self::queueAlert(
            SendCreditInfoAlertsJob::ALERT_REACH_VISIBLE_NOTIFY_EMAIL,
            __('creditinfo_alert.alert_when_reach_visible_notify_email'),
            __('creditinfo_alert.message_reach_visible'),
            $link ?? route('dashboard')
        );
    }
}
