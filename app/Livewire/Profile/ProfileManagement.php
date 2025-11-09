<?php

namespace App\Livewire\Profile;

use App\Models\UserProfile;
use Livewire\Component;
use Livewire\Attributes\Rule;
use Illuminate\Support\Facades\Auth;

class ProfileManagement extends Component
{
    public $currentStep = 'overview'; // overview, personal, address, employment, financial, bank, emergency
    public $profile;
    public $completionPercentage = 0;

    // Personal Information (some fields are read-only from users table)
    public $first_name = ''; // Read-only from users table
    public $last_name = '';  // Read-only from users table
    public $national_id = ''; // Read-only from users table (nida_number)

    #[Rule('nullable|string|max:255')]
    public $middle_name = '';

    #[Rule('nullable|date|before:today')]
    public $date_of_birth = null;

    #[Rule('nullable|in:male,female,other')]
    public $gender = null;

    #[Rule('nullable|in:single,married,divorced,widowed,separated')]
    public $marital_status = null;

    #[Rule('required|string|min:10|max:20')]
    public $phone_number = '';

    #[Rule('required|email')]
    public $email = '';

    // Address Information
    #[Rule('required|string')]
    public $current_address = '';

    #[Rule('required|string')]
    public $current_city = '';

    #[Rule('required|string')]
    public $current_region = '';

    #[Rule('nullable|string')]
    public $current_postal_code = '';

    #[Rule('required|integer|min:0|max:50')]
    public $years_at_current_address = 0;

    public $is_permanent_same_as_current = true;
    public $permanent_address = '';
    public $permanent_city = '';
    public $permanent_region = '';

    // Employment Information
    #[Rule('nullable|in:employed,self_employed,unemployed,retired,student')]
    public $employment_status = null;

    public $employer_name = '';
    public $job_title = '';
    public $employment_sector = '';
    public $years_of_employment = 0;
    public $months_with_current_employer = 0;

    // Business Information
    public $business_name = '';
    public $business_type = '';
    public $business_registration_number = '';
    public $years_in_business = 0;
    public $business_address = '';

    // Financial Information
    #[Rule('required|numeric|min:0')]
    public $monthly_salary = 0;

    #[Rule('nullable|numeric|min:0')]
    public $other_monthly_income = 0;

    #[Rule('nullable|numeric|min:0')]
    public $monthly_business_income = 0;

    #[Rule('required|numeric|min:0')]
    public $total_monthly_income = 0;

    #[Rule('required|numeric|min:0')]
    public $monthly_expenses = 0;

    #[Rule('nullable|numeric|min:0')]
    public $existing_loan_payments = 0;

    #[Rule('nullable|integer|min:300|max:850')]
    public $credit_score = null;

    public $has_bad_credit_history = false;

    // Bank Information
    public $has_bank_account = true;
    public $bank_name = '';
    public $account_number = '';
    public $account_name = '';
    public $account_type = null;
    public $years_with_bank = 0;

    // Emergency Contact
    #[Rule('required|string')]
    public $emergency_contact_name = '';

    #[Rule('required|string')]
    public $emergency_contact_relationship = '';

    #[Rule('required|string')]
    public $emergency_contact_phone = '';

    public $emergency_contact_address = '';
    public $preferred_disbursement_method = 'bank_transfer';

    public function mount()
    {
        $this->profile = Auth::user()->profile ?? new UserProfile(['user_id' => Auth::id()]);
        $this->loadProfileData();
        $this->calculateCompletion();
    }

    public function render()
    {
        return view('livewire.profile.profile-management', [
            'maritalStatuses' => $this->getMaritalStatuses(),
            'employmentSectors' => $this->getEmploymentSectors(),
            'businessTypes' => $this->getBusinessTypes(),
        ]);
    }

    private function loadProfileData()
    {
        $user = Auth::user();
        
        // Always load read-only fields from users table
        $this->first_name = $user->first_name ?? $user->name ?? '';
        $this->last_name = $user->last_name ?? '';
        $this->national_id = $user->nida_number ?? '';
        $this->email = $user->email ?? '';
        $this->phone_number = $user->phone ?? '';
        $this->date_of_birth = $user->date_of_birth ?? null;

        // Load profile data if exists
        if ($this->profile->exists) {
            $profileData = $this->profile->toArray();
            
            // Fill all fields from profile, including read-only ones for consistency
            $allFields = [
                'middle_name', 'gender', 'marital_status',
                'current_address', 'current_city', 'current_region', 'current_postal_code',
                'years_at_current_address', 'is_permanent_same_as_current',
                'permanent_address', 'permanent_city', 'permanent_region',
                'employment_status', 'employer_name', 'job_title', 'employment_sector',
                'years_of_employment', 'months_with_current_employer',
                'business_name', 'business_type', 'business_registration_number',
                'years_in_business', 'business_address',
                'monthly_salary', 'other_monthly_income', 'monthly_business_income',
                'total_monthly_income', 'monthly_expenses', 'existing_loan_payments',
                'credit_score', 'has_bad_credit_history',
                'has_bank_account', 'bank_name', 'account_number', 'account_name',
                'account_type', 'years_with_bank',
                'emergency_contact_name', 'emergency_contact_relationship',
                'emergency_contact_phone', 'emergency_contact_address',
                'preferred_disbursement_method'
            ];

            foreach ($allFields as $field) {
                if (isset($profileData[$field])) {
                    // For enum fields, ensure we don't set empty strings
                    if (in_array($field, ['gender', 'marital_status', 'employment_status', 'account_type'])) {
                        $this->$field = $profileData[$field] ?: null;
                    } else {
                        $this->$field = $profileData[$field] ?? null;
                    }
                } else {
                    // Set default values for enum fields
                    if (in_array($field, ['gender', 'marital_status', 'employment_status', 'account_type'])) {
                        $this->$field = null;
                    }
                }
            }
            
            // Override read-only fields with fresh user data
            $this->first_name = $user->first_name ?? $user->name ?? '';
            $this->last_name = $user->last_name ?? '';
            $this->national_id = $user->nida_number ?? '';
            $this->email = $user->email ?? '';
            $this->phone_number = $user->phone ?? '';
            if ($user->date_of_birth) {
                $this->date_of_birth = $user->date_of_birth;
            }
        } else {
            // Initialize enum fields as null if profile doesn't exist
            $this->gender = null;
            $this->marital_status = null;
            $this->employment_status = null;
            $this->account_type = null;
        }
    }

    public function goToStep($step)
    {
        // Save current step before moving if we have data
        if ($this->currentStep !== 'overview' && $this->hasDataToSave()) {
            try {
                $this->saveStep($this->currentStep);
            } catch (\Exception $e) {
                // Don't block navigation if save fails, just show error
                session()->flash('error', 'Could not save current step: ' . $e->getMessage());
            }
        }
        
        $this->currentStep = $step;
    }
    
    /**
     * Check if current step has data to save
     */
    private function hasDataToSave(): bool
    {
        switch ($this->currentStep) {
            case 'personal':
                return !empty($this->middle_name) || !empty($this->gender) || !empty($this->marital_status);
            case 'address':
                return !empty($this->current_address) || !empty($this->current_city) || !empty($this->current_region);
            case 'employment':
                return !empty($this->employment_status);
            case 'financial':
                return $this->monthly_salary > 0 || $this->monthly_business_income > 0 || $this->other_monthly_income > 0 || $this->monthly_expenses > 0;
            case 'bank':
                return !empty($this->bank_name) || !empty($this->account_number);
            case 'emergency':
                return !empty($this->emergency_contact_name) || !empty($this->emergency_contact_phone);
            default:
                return false;
        }
    }

    public function saveStep($step = null)
    {
        $stepToSave = $step ?? $this->currentStep;
        
        // Don't validate overview step
        if ($stepToSave !== 'overview') {
            try {
                // Validate current step
                $this->validateCurrentStep($stepToSave);
            } catch (\Illuminate\Validation\ValidationException $e) {
                session()->flash('error', 'Please fill in all required fields correctly.');
                return;
            }
        }
        
        // Update total income
        $this->total_monthly_income = $this->monthly_salary + $this->other_monthly_income + $this->monthly_business_income;
        
        // Handle permanent address logic
        if ($this->is_permanent_same_as_current) {
            $this->permanent_address = $this->current_address;
            $this->permanent_city = $this->current_city;
            $this->permanent_region = $this->current_region;
        }
        
        // Clean up enum fields - convert empty strings to null
        $this->cleanEnumFields();
        
        // Save to database
        $data = $this->getProfileData();
        
        try {
            if ($this->profile->exists) {
                $this->profile->update($data);
            } else {
                $this->profile = UserProfile::create(array_merge($data, ['user_id' => Auth::id()]));
            }
            
            $this->calculateCompletion();
            
            session()->flash('message', 'Profile section saved successfully!');
            
            // Refresh the component to show updated data
            $this->loadProfileData();
            
        } catch (\Exception $e) {
            \Log::error('Profile save error: ' . $e->getMessage());
            session()->flash('error', 'Error saving profile: ' . $e->getMessage());
        }
    }

    private function cleanEnumFields()
    {
        // Convert empty strings to null for enum fields
        if ($this->gender === '') {
            $this->gender = null;
        }
        if ($this->marital_status === '') {
            $this->marital_status = null;
        }
        if ($this->employment_status === '') {
            $this->employment_status = null;
        }
        if ($this->account_type === '') {
            $this->account_type = null;
        }
    }

    public function saveAndContinue($nextStep)
    {
        $this->saveStep();
        $this->currentStep = $nextStep;
    }

    private function validateCurrentStep($step)
    {
        switch ($step) {
            case 'personal':
                $this->validate([
                    'middle_name' => 'nullable|string|max:255',
                    'date_of_birth' => 'nullable|date|before:today',
                    'gender' => 'nullable|in:male,female,other',
                    'marital_status' => 'nullable|in:single,married,divorced,widowed,separated',
                    'phone_number' => 'required|string|min:10|max:20',
                    'email' => 'required|email',
                ]);
                break;
            case 'address':
                $this->validate([
                    'current_address' => 'nullable|string',
                    'current_city' => 'nullable|string',
                    'current_region' => 'nullable|string',
                    'years_at_current_address' => 'nullable|integer|min:0|max:50',
                ]);
                break;
            case 'employment':
                $this->validate([
                    'employment_status' => 'nullable|in:employed,self_employed,unemployed,retired,student',
                ]);
                break;
            case 'financial':
                $this->validate([
                    'total_monthly_income' => 'nullable|numeric|min:0',
                    'monthly_expenses' => 'nullable|numeric|min:0',
                ]);
                break;
            case 'emergency':
                $this->validate([
                    'emergency_contact_name' => 'nullable|string',
                    'emergency_contact_relationship' => 'nullable|string',
                    'emergency_contact_phone' => 'nullable|string',
                ]);
                break;
        }
    }

    private function getProfileData(): array
    {
        // Helper function to convert empty strings to null
        $nullIfEmpty = function($value) {
            if ($value === '' || $value === null || (is_string($value) && trim($value) === '')) {
                return null;
            }
            return $value;
        };

        // Handle empty string dates - convert to null
        $dateOfBirth = $this->date_of_birth;
        if (empty($dateOfBirth) || $dateOfBirth === '') {
            $dateOfBirth = null;
        }

        // Prepare data array
        $data = [
            // Store read-only fields for reference, but they come from users table
            'first_name' => $nullIfEmpty($this->first_name),
            'last_name' => $nullIfEmpty($this->last_name),
            'national_id' => $nullIfEmpty($this->national_id),
            'email' => $nullIfEmpty($this->email),
            'phone_number' => $nullIfEmpty($this->phone_number),
            'date_of_birth' => $dateOfBirth,
            
            // Editable profile fields - convert empty strings to null for nullable fields
            'middle_name' => $nullIfEmpty($this->middle_name),
            'gender' => $nullIfEmpty($this->gender), // Enum field - must be null or valid enum value
            'marital_status' => $nullIfEmpty($this->marital_status), // Enum field - must be null or valid enum value
            'current_address' => $nullIfEmpty($this->current_address),
            'current_city' => $nullIfEmpty($this->current_city),
            'current_region' => $nullIfEmpty($this->current_region),
            'current_postal_code' => $nullIfEmpty($this->current_postal_code),
            'years_at_current_address' => $this->years_at_current_address ?? 0,
            'is_permanent_same_as_current' => $this->is_permanent_same_as_current ?? true,
            'permanent_address' => $nullIfEmpty($this->permanent_address),
            'permanent_city' => $nullIfEmpty($this->permanent_city),
            'permanent_region' => $nullIfEmpty($this->permanent_region),
            'employment_status' => $nullIfEmpty($this->employment_status), // Enum field
            'employer_name' => $nullIfEmpty($this->employer_name),
            'job_title' => $nullIfEmpty($this->job_title),
            'employment_sector' => $nullIfEmpty($this->employment_sector),
            'years_of_employment' => $this->years_of_employment ?? 0,
            'months_with_current_employer' => $this->months_with_current_employer ?? 0,
            'business_name' => $nullIfEmpty($this->business_name),
            'business_type' => $nullIfEmpty($this->business_type),
            'business_registration_number' => $nullIfEmpty($this->business_registration_number),
            'years_in_business' => $this->years_in_business ?? 0,
            'business_address' => $nullIfEmpty($this->business_address),
            'monthly_salary' => $this->monthly_salary ?? 0,
            'other_monthly_income' => $this->other_monthly_income ?? 0,
            'monthly_business_income' => $this->monthly_business_income ?? 0,
            'total_monthly_income' => $this->total_monthly_income ?? 0,
            'monthly_expenses' => $this->monthly_expenses ?? 0,
            'existing_loan_payments' => $this->existing_loan_payments ?? 0,
            'credit_score' => $this->credit_score,
            'has_bad_credit_history' => $this->has_bad_credit_history ?? false,
            'has_bank_account' => $this->has_bank_account ?? true,
            'bank_name' => $nullIfEmpty($this->bank_name),
            'account_number' => $nullIfEmpty($this->account_number),
            'account_name' => $nullIfEmpty($this->account_name),
            'account_type' => $nullIfEmpty($this->account_type), // Enum field
            'years_with_bank' => $this->years_with_bank ?? 0,
            'emergency_contact_name' => $nullIfEmpty($this->emergency_contact_name),
            'emergency_contact_relationship' => $nullIfEmpty($this->emergency_contact_relationship),
            'emergency_contact_phone' => $nullIfEmpty($this->emergency_contact_phone),
            'emergency_contact_address' => $nullIfEmpty($this->emergency_contact_address),
            'preferred_disbursement_method' => $this->preferred_disbursement_method ?? 'bank_transfer',
            'last_updated' => now(),
        ];
        
        // Remove empty string values for enum fields - ensure they're null
        $enumFields = ['gender', 'marital_status', 'employment_status', 'account_type'];
        foreach ($enumFields as $field) {
            if (isset($data[$field]) && ($data[$field] === '' || trim($data[$field]) === '')) {
                $data[$field] = null;
            }
        }
        
        return $data;
    }

    private function calculateCompletion()
    {
        if ($this->profile->exists) {
            $this->completionPercentage = $this->profile->calculateCompletionPercentage();
        }
    }

    public function saveCurrentStep()
    {
        $this->saveStep($this->currentStep);
    }

    public function updated($propertyName)
    {
        // Auto-calculate total income
        if (in_array($propertyName, ['monthly_salary', 'other_monthly_income', 'monthly_business_income'])) {
            $this->total_monthly_income = $this->monthly_salary + $this->other_monthly_income + $this->monthly_business_income;
        }

        // Handle permanent address same as current
        if ($propertyName === 'is_permanent_same_as_current' && $this->is_permanent_same_as_current) {
            $this->permanent_address = $this->current_address;
            $this->permanent_city = $this->current_city;
            $this->permanent_region = $this->current_region;
        }

        // Auto-save on important field changes (debounced)
        $autoSaveFields = [
            'gender', 'marital_status', 'employment_status', 'has_bank_account',
            'current_address', 'current_city', 'current_region',
            'monthly_salary', 'monthly_business_income', 'monthly_expenses'
        ];

        if (in_array($propertyName, $autoSaveFields) && $this->currentStep !== 'overview') {
            $this->dispatch('auto-save-triggered');
        }
    }

    private function getMaritalStatuses(): array
    {
        return [
            'single' => 'Single',
            'married' => 'Married',
            'divorced' => 'Divorced',
            'widowed' => 'Widowed',
            'separated' => 'Separated',
        ];
    }

    private function getEmploymentSectors(): array
    {
        return [
            'agriculture' => 'Agriculture',
            'banking' => 'Banking & Finance',
            'construction' => 'Construction',
            'education' => 'Education',
            'healthcare' => 'Healthcare',
            'manufacturing' => 'Manufacturing',
            'retail' => 'Retail',
            'technology' => 'Technology',
            'telecommunications' => 'Telecommunications',
            'transportation' => 'Transportation',
            'government' => 'Government',
            'ngo' => 'NGO/Non-Profit',
            'other' => 'Other',
        ];
    }

    private function getBusinessTypes(): array
    {
        return [
            'retail' => 'Retail Business',
            'services' => 'Services',
            'manufacturing' => 'Manufacturing',
            'agriculture' => 'Agriculture',
            'technology' => 'Technology',
            'consulting' => 'Consulting',
            'trading' => 'Trading',
            'transportation' => 'Transportation',
            'construction' => 'Construction',
            'other' => 'Other',
        ];
    }
}