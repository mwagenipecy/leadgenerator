<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\CreditInfoAlertSetting;
use App\Models\CreditInfoService;
use App\Notifications\CreditInfoAlertNotification;

class CreditInfoAlert extends Component
{
    public $alert_when_credit_info_searched = false;
    public $alert_when_score_changed = false;
    public $alert_when_report_retrieved = false;
    public $alert_when_lender_can_find_loan = false;
    public $alert_when_reach_visible_notify_email = false;
    public $notify_via_email = true;

    public function mount()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $setting = Auth::user()->creditInfoAlertSetting;
        if ($setting) {
            $this->alert_when_credit_info_searched = (bool) $setting->alert_when_credit_info_searched;
            $this->alert_when_score_changed = (bool) $setting->alert_when_score_changed;
            $this->alert_when_report_retrieved = (bool) $setting->alert_when_report_retrieved;
            $this->alert_when_lender_can_find_loan = (bool) $setting->alert_when_lender_can_find_loan;
            $this->alert_when_reach_visible_notify_email = (bool) $setting->alert_when_reach_visible_notify_email;
            $this->notify_via_email = (bool) $setting->notify_via_email;
        }
    }

    public function save()
    {
        $this->validate([
            'alert_when_credit_info_searched' => 'boolean',
            'alert_when_score_changed' => 'boolean',
            'alert_when_report_retrieved' => 'boolean',
            'alert_when_lender_can_find_loan' => 'boolean',
            'alert_when_reach_visible_notify_email' => 'boolean',
            'notify_via_email' => 'boolean',
        ]);

        CreditInfoAlertSetting::updateOrCreate(
            ['user_id' => Auth::id()],
            [
                'alert_when_credit_info_searched' => $this->alert_when_credit_info_searched,
                'alert_when_score_changed' => $this->alert_when_score_changed,
                'alert_when_report_retrieved' => $this->alert_when_report_retrieved,
                'alert_when_lender_can_find_loan' => $this->alert_when_lender_can_find_loan,
                'alert_when_reach_visible_notify_email' => $this->alert_when_reach_visible_notify_email,
                'notify_via_email' => $this->notify_via_email,
            ]
        );

        session()->flash('message', __('creditinfo_alert.saved_success'));
    }

    public function render()
    {
        $user = Auth::user();
        $setting = $user->creditInfoAlertSetting;

        $services = CreditInfoService::active()->ordered()->get();
        $subscribedServices = $services->filter(fn (CreditInfoService $s) => $s->isSubscribedFor($setting));

        $activities = $user->notifications()
            ->where('type', CreditInfoAlertNotification::class)
            ->latest()
            ->take(20)
            ->get();

        return view('livewire.credit-info-alert', [
            'services' => $services,
            'subscribedServices' => $subscribedServices,
            'activities' => $activities,
            'setting' => $setting,
        ])->layout('components.layouts.app');
    }
}
