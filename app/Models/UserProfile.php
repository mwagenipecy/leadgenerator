<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

// UserProfile Model
class UserProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'first_name', 'middle_name', 'last_name', 'date_of_birth',
        'gender', 'marital_status', 'national_id', 'phone_number', 'email',
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
        'preferred_disbursement_method', 'profile_completion_percentage',
        'last_updated'
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'is_permanent_same_as_current' => 'boolean',
        'has_bad_credit_history' => 'boolean',
        'has_bank_account' => 'boolean',
        'profile_completion_percentage' => 'integer',
        'last_updated' => 'timestamp',
        'monthly_salary' => 'decimal:2',
        'other_monthly_income' => 'decimal:2',
        'monthly_business_income' => 'decimal:2',
        'total_monthly_income' => 'decimal:2',
        'monthly_expenses' => 'decimal:2',
        'existing_loan_payments' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Calculate profile completion percentage
    public function calculateCompletionPercentage(): int
    {
        $requiredFields = [
            'first_name', 'last_name', 'date_of_birth', 'gender', 'marital_status',
            'national_id', 'phone_number', 'email', 'current_address', 'current_city',
            'current_region', 'employment_status', 'total_monthly_income',
            'monthly_expenses', 'emergency_contact_name', 'emergency_contact_relationship',
            'emergency_contact_phone'
        ];

        $completedFields = 0;
        foreach ($requiredFields as $field) {
            if (!empty($this->$field)) {
                $completedFields++;
            }
        }

        $percentage = round(($completedFields / count($requiredFields)) * 100);
        
        // Update the percentage in database
        $this->update(['profile_completion_percentage' => $percentage]);
        
        return $percentage;
    }

    // Get total monthly income
    public function getTotalMonthlyIncomeAttribute(): float
    {
        return $this->monthly_salary + $this->other_monthly_income + $this->monthly_business_income;
    }

    // Calculate DSR
    public function calculateDSR(float $additionalMonthlyPayment = 0): float
    {
        $totalDebt = $this->existing_loan_payments + $additionalMonthlyPayment;
        return $this->total_monthly_income > 0 ? ($totalDebt / $this->total_monthly_income) * 100 : 0;
    }



       /**
     * Calculate the completion percentage of the profile
     */
 
    /**
     * Update profile data with user information
     */
    public function syncWithUser(): void
    {
        if ($this->user) {
            $this->update([
                'first_name' => $this->user->first_name ?? $this->user->name ?? '',
                'last_name' => $this->user->last_name ?? '',
                'national_id' => $this->user->nida_number ?? '',
                'email' => $this->user->email ?? '',
                'phone_number' => $this->user->phone ?? '',
                'date_of_birth' => $this->user->date_of_birth ?? '',
            ]);
        }
    }

    /**
     * Boot method to handle model events
     */
    protected static function boot()
    {
        parent::boot();

        // Automatically sync with user data when creating
        static::creating(function ($profile) {
            if ($profile->user) {
                $profile->first_name = $profile->user->first_name ?? $profile->user->name ?? '';
                $profile->last_name = $profile->user->last_name ?? '';
                $profile->national_id = $profile->user->nida_number ?? '';
                $profile->email = $profile->user->email ?? '';
                $profile->phone_number = $profile->user->phone ?? '';
                $profile->date_of_birth = $profile->user->date_of_birth ?? '';
            }
        });
    }



}