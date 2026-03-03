<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\CreditInfoAlertSetting;
use App\Models\CreditInfoService;
use App\Jobs\SendCreditInfoUnsubscribeEmailJob;
use App\Services\LogService;

class CreditInfoPricing extends Component
{
    /** @var bool */
    public $showPaymentModal = false;

    /** @var int|null */
    public $paymentServiceId = null;

    /** @var string */
    public $selectedNetwork = '';

    /** @var string */
    public $paymentPhone = '';

    /** @var int Number of months to subscribe (1, 3, 6, 12). */
    public $paymentMonths = 1;

    /** @var bool Unsubscribe confirmation modal */
    public $showUnsubscribeModal = false;

    /** @var int|null Service to unsubscribe from */
    public $unsubscribeServiceId = null;

    /** @var string Password confirmation */
    public $unsubscribePassword = '';

    public function mount()
    {
        if (!Auth::check()) {
            redirect()->route('login');
        }
    }

    /**
     * Open payment modal when user clicks Subscribe (do not subscribe yet).
     */
    public function openPaymentModal(int $serviceId): void
    {
        $this->paymentServiceId = $serviceId;
        $this->selectedNetwork = '';
        $this->paymentPhone = '';
        $this->paymentMonths = 1;
        $this->showPaymentModal = true;
    }

    public function closePaymentModal(): void
    {
        $this->showPaymentModal = false;
        $this->paymentServiceId = null;
        $this->selectedNetwork = '';
        $this->paymentPhone = '';
        $this->paymentMonths = 1;
        $this->resetValidation();
    }

    /**
     * After user selects network and clicks "Check and continue" — validate and subscribe.
     */
    public function confirmPayment(): void
    {
        $this->validate([
            'selectedNetwork' => 'required|string|in:mpesa,tigo_pesa,airtel_money,halotel,zantel,tpesa',
            'paymentPhone' => 'nullable|string|max:20',
            'paymentMonths' => 'required|integer|in:1,3,6,12',
        ], [
            'selectedNetwork.required' => __('creditinfo_alert.network_required'),
        ]);

        if ($this->paymentServiceId) {
            $this->subscribe($this->paymentServiceId, (int) $this->paymentMonths);
        }
        $this->closePaymentModal();
    }

    /**
     * Subscribe to a service (set the corresponding alert setting to true and store period if months given).
     */
    public function subscribe(int $serviceId, int $months = 1): void
    {
        $service = CreditInfoService::active()->findOrFail($serviceId);
        $setting = CreditInfoAlertSetting::firstOrCreate(
            ['user_id' => Auth::id()],
            [
                'alert_when_credit_info_searched' => false,
                'alert_when_score_changed' => false,
                'alert_when_report_retrieved' => false,
                'alert_when_lender_can_find_loan' => false,
                'alert_when_reach_visible_notify_email' => false,
                'notify_via_email' => true,
            ]
        );
        $setting->{$service->slug} = true;
        $periods = $setting->subscription_periods ?? [];
        $periods[$service->slug] = [
            'started_at' => now()->toDateString(),
            'ends_at' => now()->addMonths($months)->toDateString(),
        ];
        $setting->subscription_periods = $periods;
        $setting->save();
        session()->flash('message', __('creditinfo_alert.subscribed_success', ['name' => $service->name]));
    }

    /**
     * Open unsubscribe confirmation modal (password required).
     */
    public function openUnsubscribeModal(int $serviceId): void
    {
        $this->unsubscribeServiceId = $serviceId;
        $this->unsubscribePassword = '';
        $this->showUnsubscribeModal = true;
        $this->resetValidation();
    }

    public function closeUnsubscribeModal(): void
    {
        $this->showUnsubscribeModal = false;
        $this->unsubscribeServiceId = null;
        $this->unsubscribePassword = '';
        $this->resetValidation();
    }

    /**
     * Confirm unsubscribe: validate password, then unsubscribe, send email job, and log.
     */
    public function confirmUnsubscribe(): void
    {
        $this->validate([
            'unsubscribePassword' => 'required|string',
        ], [
            'unsubscribePassword.required' => __('creditinfo_alert.password_required'),
        ]);

        $user = Auth::user();
        if (!Hash::check($this->unsubscribePassword, $user->password)) {
            $this->addError('unsubscribePassword', __('creditinfo_alert.password_incorrect'));
            return;
        }

        if (!$this->unsubscribeServiceId) {
            $this->closeUnsubscribeModal();
            return;
        }

        $service = CreditInfoService::active()->findOrFail($this->unsubscribeServiceId);
        $setting = $user->creditInfoAlertSetting;
        if ($setting) {
            $setting->{$service->slug} = false;
            $setting->save();
        }

        SendCreditInfoUnsubscribeEmailJob::dispatch($user, $service->name);

        LogService::logCreditInfoServiceUnsubscribed($user, $service->name, $service, [
            'service_id' => $service->id,
            'service_slug' => $service->slug,
        ]);

        session()->flash('message', __('creditinfo_alert.unsubscribed_success', ['name' => $service->name]));
        $this->closeUnsubscribeModal();
    }

    public function render()
    {
        $user = Auth::user();
        $setting = $user->creditInfoAlertSetting;
        $services = CreditInfoService::active()->ordered()->get();

        return view('livewire.credit-info-pricing', [
            'services' => $services,
            'setting' => $setting,
        ])->layout('components.layouts.app');
    }
}
