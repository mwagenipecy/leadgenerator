<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\SystemSetting;
use App\Models\LenderCommissionSetting;
use App\Models\Lender;
use Illuminate\Support\Facades\Auth;

class SettingsManagement extends Component
{
    // Tab Management
    public $activeTab = 'commission';

    // General Billing Settings
    public $default_commission_type = 'percentage'; // percentage or fixed
    public $default_commission_percentage = 5.0;
    public $default_commission_fixed_amount = 0;
    public $commission_calculation_base = 'loan_amount'; // loan_amount, interest_amount, monthly_payment
    public $commission_collection_frequency = 'per_loan'; // per_loan, monthly, quarterly, annually
    public $auto_collection_enabled = true;
    public $minimum_commission_amount = 100;
    public $maximum_commission_amount = null;

    // Payment Settings
    public $payment_due_days = 30;
    public $late_payment_penalty_percentage = 2.0;
    public $grace_period_days = 7;
    public $payment_methods = ['bank_transfer', 'mobile_money', 'cash'];
    public $selected_payment_methods = [];

    // Notification Settings
    public $send_commission_notifications = true;
    public $send_payment_reminders = true;
    public $reminder_days_before_due = 7;
    public $send_overdue_notices = true;
    public $notification_email = '';
    public $sms_notifications_enabled = false;

    // System Settings
    public $system_currency = 'TZS';
    public $tax_rate = 18.0; // VAT rate
    public $business_name = 'Loan Lead Generator';
    public $business_address = '';
    public $business_phone = '';
    public $business_email = '';
    public $business_website = '';
    public $business_registration_number = '';
    public $tax_identification_number = '';

    // Lender-Specific Settings
    public $selectedLender = null;
    public $showLenderModal = false;
    public $lender_commission_type = 'percentage';
    public $lender_commission_percentage = 5.0;
    public $lender_commission_fixed_amount = 0;
    public $lender_minimum_amount = 100;
    public $lender_maximum_amount = null;
    public $lender_special_terms = '';
    public $lender_is_active = true;

    public $lenders;
    public $lenderCommissions;

    protected $rules = [
        'default_commission_percentage' => 'required|numeric|min:0|max:100',
        'default_commission_fixed_amount' => 'required|numeric|min:0',
        'minimum_commission_amount' => 'required|numeric|min:0',
        'maximum_commission_amount' => 'nullable|numeric|min:0',
        'payment_due_days' => 'required|integer|min:1|max:365',
        'late_payment_penalty_percentage' => 'required|numeric|min:0|max:50',
        'grace_period_days' => 'required|integer|min:0|max:30',
        'tax_rate' => 'required|numeric|min:0|max:100',
        'business_name' => 'required|string|max:255',
        'business_email' => 'nullable|email',
        'business_phone' => 'nullable|string|max:255',
        'business_website' => 'nullable|url',
        'notification_email' => 'nullable|email',
        'reminder_days_before_due' => 'required|integer|min:1|max:30',
    ];

    public function mount()
    {
        $this->loadSettings();
        $this->loadLenders();
        $this->loadLenderCommissions();
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function loadSettings()
    {
        // Load system settings from database
        $settings = SystemSetting::pluck('value', 'key');

        $this->default_commission_type = $settings['default_commission_type'] ?? 'percentage';
        $this->default_commission_percentage = (float) ($settings['default_commission_percentage'] ?? 5.0);
        $this->default_commission_fixed_amount = (float) ($settings['default_commission_fixed_amount'] ?? 0);
        $this->commission_calculation_base = $settings['commission_calculation_base'] ?? 'loan_amount';
        $this->commission_collection_frequency = $settings['commission_collection_frequency'] ?? 'per_loan';
        $this->auto_collection_enabled = (bool) ($settings['auto_collection_enabled'] ?? true);
        $this->minimum_commission_amount = (float) ($settings['minimum_commission_amount'] ?? 100);
      //  $this->maximum_commission_amount = $settings['maximum_commission_amount'] ? (float) $settings['maximum_commission_amount'] : null;

        $this->payment_due_days = (int) ($settings['payment_due_days'] ?? 30);
        $this->late_payment_penalty_percentage = (float) ($settings['late_payment_penalty_percentage'] ?? 2.0);
        $this->grace_period_days = (int) ($settings['grace_period_days'] ?? 7);
        $this->selected_payment_methods = json_decode($settings['payment_methods'] ?? '["bank_transfer","mobile_money","cash"]', true);

        $this->send_commission_notifications = (bool) ($settings['send_commission_notifications'] ?? true);
        $this->send_payment_reminders = (bool) ($settings['send_payment_reminders'] ?? true);
        $this->reminder_days_before_due = (int) ($settings['reminder_days_before_due'] ?? 7);
        $this->send_overdue_notices = (bool) ($settings['send_overdue_notices'] ?? true);
        $this->notification_email = $settings['notification_email'] ?? '';
        $this->sms_notifications_enabled = (bool) ($settings['sms_notifications_enabled'] ?? false);

        $this->system_currency = $settings['system_currency'] ?? 'TZS';
        $this->tax_rate = (float) ($settings['tax_rate'] ?? 18.0);
        $this->business_name = $settings['business_name'] ?? 'Loan Lead Generator';
        $this->business_address = $settings['business_address'] ?? '';
        $this->business_phone = $settings['business_phone'] ?? '';
        $this->business_email = $settings['business_email'] ?? '';
        $this->business_website = $settings['business_website'] ?? '';
        $this->business_registration_number = $settings['business_registration_number'] ?? '';
        $this->tax_identification_number = $settings['tax_identification_number'] ?? '';
    }

    public function loadLenders()
    {
        $this->lenders = Lender::where('status', 'approved')->get();
    }

    public function loadLenderCommissions()
    {
        $this->lenderCommissions = LenderCommissionSetting::with('lender')->get();
    }

    public function updatedDefaultCommissionType()
    {
        if ($this->default_commission_type === 'percentage') {
            $this->default_commission_fixed_amount = 0;
        } else {
            $this->default_commission_percentage = 0;
        }
    }

    public function updatedLenderCommissionType()
    {
        if ($this->lender_commission_type === 'percentage') {
            $this->lender_commission_fixed_amount = 0;
        } else {
            $this->lender_commission_percentage = 0;
        }
    }

    public function saveGeneralSettings()
    {
        $this->validate();

        $settings = [
            'default_commission_type' => $this->default_commission_type,
            'default_commission_percentage' => $this->default_commission_percentage,
            'default_commission_fixed_amount' => $this->default_commission_fixed_amount,
            'commission_calculation_base' => $this->commission_calculation_base,
            'commission_collection_frequency' => $this->commission_collection_frequency,
            'auto_collection_enabled' => $this->auto_collection_enabled,
            'minimum_commission_amount' => $this->minimum_commission_amount,
            'maximum_commission_amount' => $this->maximum_commission_amount,
        ];

        foreach ($settings as $key => $value) {
            SystemSetting::updateOrCreate(
                ['key' => $key],
                ['value' => $value, 'updated_by' => Auth::id()]
            );
        }

        session()->flash('message', 'Commission settings saved successfully!');
    }

    public function savePaymentSettings()
    {
        $this->validate([
            'payment_due_days' => 'required|integer|min:1|max:365',
            'late_payment_penalty_percentage' => 'required|numeric|min:0|max:50',
            'grace_period_days' => 'required|integer|min:0|max:30',
            'notification_email' => 'nullable|email',
            'reminder_days_before_due' => 'required|integer|min:1|max:30',
        ]);

        $settings = [
            'payment_due_days' => $this->payment_due_days,
            'late_payment_penalty_percentage' => $this->late_payment_penalty_percentage,
            'grace_period_days' => $this->grace_period_days,
            'payment_methods' => json_encode($this->selected_payment_methods),
            'send_commission_notifications' => $this->send_commission_notifications,
            'send_payment_reminders' => $this->send_payment_reminders,
            'reminder_days_before_due' => $this->reminder_days_before_due,
            'send_overdue_notices' => $this->send_overdue_notices,
            'notification_email' => $this->notification_email,
            'sms_notifications_enabled' => $this->sms_notifications_enabled,
        ];

        foreach ($settings as $key => $value) {
            SystemSetting::updateOrCreate(
                ['key' => $key],
                ['value' => $value, 'updated_by' => Auth::id()]
            );
        }

        session()->flash('message', 'Payment settings saved successfully!');
    }

    public function saveBusinessSettings()
    {
        $this->validate([
            'business_name' => 'required|string|max:255',
            'business_email' => 'nullable|email',
            'business_phone' => 'nullable|string|max:255',
            'business_website' => 'nullable|url',
            'tax_rate' => 'required|numeric|min:0|max:100',
        ]);

        $settings = [
            'system_currency' => $this->system_currency,
            'tax_rate' => $this->tax_rate,
            'business_name' => $this->business_name,
            'business_address' => $this->business_address,
            'business_phone' => $this->business_phone,
            'business_email' => $this->business_email,
            'business_website' => $this->business_website,
            'business_registration_number' => $this->business_registration_number,
            'tax_identification_number' => $this->tax_identification_number,
        ];

        foreach ($settings as $key => $value) {
            SystemSetting::updateOrCreate(
                ['key' => $key],
                ['value' => $value, 'updated_by' => Auth::id()]
            );
        }

        session()->flash('message', 'Business settings saved successfully!');
    }

    public function openLenderModal($lenderId = null)
    {
        if ($lenderId) {
            $this->selectedLender = $lenderId;
            $setting = LenderCommissionSetting::where('lender_id', $lenderId)->first();
            
            if ($setting) {
                $this->lender_commission_type = $setting->commission_type;
                $this->lender_commission_percentage = $setting->commission_percentage;
                $this->lender_commission_fixed_amount = $setting->commission_fixed_amount;
                $this->lender_minimum_amount = $setting->minimum_amount;
                $this->lender_maximum_amount = $setting->maximum_amount;
                $this->lender_special_terms = $setting->special_terms;
                $this->lender_is_active = $setting->is_active;
            } else {
                $this->resetLenderForm();
            }
        } else {
            $this->selectedLender = null;
            $this->resetLenderForm();
        }
        
        $this->showLenderModal = true;
    }

    public function resetLenderForm()
    {
        $this->lender_commission_type = 'percentage';
        $this->lender_commission_percentage = 5.0;
        $this->lender_commission_fixed_amount = 0;
        $this->lender_minimum_amount = 100;
        $this->lender_maximum_amount = null;
        $this->lender_special_terms = '';
        $this->lender_is_active = true;
    }

    public function saveLenderSetting()
    {
        $this->validate([
            'selectedLender' => 'required|exists:lenders,id',
            'lender_commission_percentage' => 'required|numeric|min:0|max:100',
            'lender_commission_fixed_amount' => 'required|numeric|min:0',
            'lender_minimum_amount' => 'required|numeric|min:0',
            'lender_maximum_amount' => 'nullable|numeric|min:0',
        ]);

        LenderCommissionSetting::updateOrCreate(
            ['lender_id' => $this->selectedLender],
            [
                'commission_type' => $this->lender_commission_type,
                'commission_percentage' => $this->lender_commission_percentage,
                'commission_fixed_amount' => $this->lender_commission_fixed_amount,
                'minimum_amount' => $this->lender_minimum_amount,
                'maximum_amount' => $this->lender_maximum_amount,
                'special_terms' => $this->lender_special_terms,
                'is_active' => $this->lender_is_active,
                'updated_by' => Auth::id(),
            ]
        );

        $this->loadLenderCommissions();
        $this->showLenderModal = false;
        session()->flash('message', 'Lender commission setting saved successfully!');
    }

    public function deleteLenderSetting($lenderId)
    {
        LenderCommissionSetting::where('lender_id', $lenderId)->delete();
        $this->loadLenderCommissions();
        session()->flash('message', 'Lender commission setting deleted successfully!');
    }

    public function toggleLenderStatus($lenderId)
    {
        $setting = LenderCommissionSetting::where('lender_id', $lenderId)->first();
        if ($setting) {
            $setting->update(['is_active' => !$setting->is_active]);
            $this->loadLenderCommissions();
            session()->flash('message', 'Lender commission status updated successfully!');
        }
    }

    public function calculateCommissionPreview($loanAmount, $commissionType, $percentage, $fixedAmount)
    {
        if ($commissionType === 'percentage') {
            return ($loanAmount * $percentage) / 100;
        } else {
            return $fixedAmount;
        }
    }

    public function render()
    {
        return view('livewire.admin.settings-management');
    }
}