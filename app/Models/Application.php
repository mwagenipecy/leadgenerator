<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Application extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'loan_product_id', 'lender_id', 'status', 'requested_amount', 
        'requested_tenure_months', 'loan_purpose', 'first_name', 'last_name', 
        'middle_name', 'date_of_birth', 'gender', 'marital_status', 'national_id', 
        'phone_number', 'email', 'current_address', 'current_city', 'current_region', 
        'current_postal_code', 'years_at_current_address', 'permanent_address', 
        'permanent_city', 'permanent_region', 'is_permanent_same_as_current', 
        'employment_status', 'employer_name', 'job_title', 'employment_sector', 
        'years_of_employment', 'months_with_current_employer', 'monthly_salary', 
        'other_monthly_income', 'salary_payment_method', 'business_name', 
        'business_type', 'business_registration_number', 'years_in_business', 
        'monthly_business_income', 'business_address', 'total_monthly_income', 
        'monthly_expenses', 'existing_loan_payments', 'debt_to_income_ratio', 
        'credit_score', 'has_bad_credit_history', 'bank_name', 'account_number', 
        'account_name', 'account_type', 'years_with_bank', 'emergency_contact_name', 
        'emergency_contact_relationship', 'emergency_contact_phone', 'emergency_contact_address', 
        'collateral_offered', 'guarantors', 'preferred_disbursement_method', 
        'matching_products', 'rejection_reasons', 'notes', 'submitted_at', 
        'reviewed_at', 'approved_at', 'disbursed_at', 'reviewed_by', 'ip_address', 
        'user_agent', 'application_source','booking_status'
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'requested_amount' => 'decimal:2',
        'monthly_salary' => 'decimal:2',
        'other_monthly_income' => 'decimal:2',
        'monthly_business_income' => 'decimal:2',
        'total_monthly_income' => 'decimal:2',
        'monthly_expenses' => 'decimal:2',
        'existing_loan_payments' => 'decimal:2',
        'debt_to_income_ratio' => 'decimal:2',
        'is_permanent_same_as_current' => 'boolean',
        'has_bad_credit_history' => 'boolean',
        'collateral_offered' => 'array',
        'guarantors' => 'array',
        'matching_products' => 'array',
        'rejection_reasons' => 'array',
        'application_source' => 'array',
        'submitted_at' => 'datetime',
        'reviewed_at' => 'datetime',
        'approved_at' => 'datetime',
        'disbursed_at' => 'datetime',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }


    public function creditInfoRequests(): HasMany
    {
        return $this->hasMany(CreditInfoRequest::class, 'loan_id');
    }


    public function commissionBills()
{
    return $this->hasMany(CommissionBill::class, 'application_id');
}




    public function getLatestCreditInfoAttribute(): ?CreditInfoRequest
    {
        return $this->creditInfoRequests()->latest()->first();
    }

    public function hasSuccessfulCreditCheck(): bool
    {
        return $this->creditInfoRequests()->where('status', 'success')->exists();
    }

    public function scopeWithCreditInfo($query)
    {
        return $query->with(['creditInfoRequests' => function ($query) {
            $query->latest();
        }]);
    }


    public function loanProduct(): BelongsTo
    {
        return $this->belongsTo(LoanProduct::class);
    }

    public function lender(): BelongsTo
    {
        return $this->belongsTo(Lender::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(ApplicationDocument::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->whereNotIn('status', ['cancelled', 'rejected']);
    }

    public function scopePending($query)
    {
        return $query->whereIn('status', ['draft', 'submitted', 'under_review']);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    // Mutators & Accessors
    public function getFullNameAttribute(): string
    {
        return trim($this->first_name . ' ' . $this->middle_name . ' ' . $this->last_name);
    }

    public function getAgeAttribute(): int
    {
        return $this->date_of_birth->age;
    }

    public function getMonthlyIncomeAttribute(): float
    {
        return $this->monthly_salary + $this->other_monthly_income + $this->monthly_business_income;
    }

    public function getNetIncomeAttribute(): float
    {
        return $this->total_monthly_income - $this->monthly_expenses - $this->existing_loan_payments;
    }

    public function getStatusBadgeAttribute(): array
    {
        $badges = [
            'draft' => ['color' => 'gray', 'text' => 'Draft'],
            'submitted' => ['color' => 'blue', 'text' => 'Submitted'],
            'under_review' => ['color' => 'yellow', 'text' => 'Under Review'],
            'approved' => ['color' => 'green', 'text' => 'Approved'],
            'rejected' => ['color' => 'red', 'text' => 'Rejected'],
            'disbursed' => ['color' => 'purple', 'text' => 'Disbursed'],
            'cancelled' => ['color' => 'gray', 'text' => 'Cancelled'],
        ];

        return $badges[$this->status] ?? ['color' => 'gray', 'text' => 'Unknown'];
    }

    // Boot method for auto-generating application number
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($application) {
            if (!$application->application_number) {
                $application->application_number = self::generateApplicationNumber();
            }

            // Calculate debt-to-income ratio
            if ($application->total_monthly_income > 0) {
                $totalDebts = $application->existing_loan_payments;
                $application->debt_to_income_ratio = ($totalDebts / $application->total_monthly_income) * 100;
            }
        });
    }

    // Helper Methods
    public static function generateApplicationNumber(): string
    {
        do {
            $number = 'APP' . date('Y') . str_pad(rand(1, 999999), 6, '0', STR_PAD_LEFT);
        } while (self::where('application_number', $number)->exists());

        return $number;
    }

    public function findMatchingProducts(): array
    {
        $matchingProducts = [];
        $loanProducts = LoanProduct::active()->get();

        foreach ($loanProducts as $product) {
            $eligibilityScore = $this->calculateEligibilityScore($product);
            
            if ($eligibilityScore['is_eligible']) {
                $matchingProducts[] = [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'lender_id' => $product->lender_id,
                    'lender_name' => $product->lender->name,
                    'eligibility_score' => $eligibilityScore['score'],
                    'matched_criteria' => $eligibilityScore['matched_criteria'],
                    'unmatched_criteria' => $eligibilityScore['unmatched_criteria'],
                    'recommended_amount' => min($this->requested_amount, $product->max_amount),
                    'interest_rate' => $product->interest_rate_min,
                ];
            }
        }

        // Sort by eligibility score (highest first)
        usort($matchingProducts, function ($a, $b) {
            return $b['eligibility_score'] <=> $a['eligibility_score'];
        });

        return $matchingProducts;
    }

    public function calculateEligibilityScore(LoanProduct $product): array
    {
        $score = 0;
        $maxScore = 0;
        $matchedCriteria = [];
        $unmatchedCriteria = [];

        // Check loan amount (Required)
        $maxScore += 20;
        if ($this->requested_amount >= $product->min_amount && $this->requested_amount <= $product->max_amount) {
            $score += 20;
            $matchedCriteria[] = 'Loan amount within range';
        } else {
            $unmatchedCriteria[] = 'Loan amount outside range';
        }

        // Check tenure (Required)
        $maxScore += 15;
        if ($this->requested_tenure_months >= $product->min_tenure_months && 
            $this->requested_tenure_months <= $product->max_tenure_months) {
            $score += 15;
            $matchedCriteria[] = 'Tenure within range';
        } else {
            $unmatchedCriteria[] = 'Tenure outside range';
        }

        // Check age (Required)
        $maxScore += 20;
        $age = $this->age;
        if ($age >= $product->min_age && $age <= $product->max_age) {
            $score += 20;
            $matchedCriteria[] = 'Age requirement met';
        } else {
            $unmatchedCriteria[] = 'Age requirement not met';
        }

        // Check employment requirement (Required)
        $maxScore += 25;
        $employmentMatch = $this->checkEmploymentRequirement($product);
        if ($employmentMatch['match']) {
            $score += 25;
            $matchedCriteria[] = 'Employment requirement met';
        } else {
            $unmatchedCriteria[] = $employmentMatch['reason'];
        }

        // Check minimum income (if set)
        if ($product->min_monthly_income) {
            $maxScore += 10;
            if ($this->total_monthly_income >= $product->min_monthly_income) {
                $score += 10;
                $matchedCriteria[] = 'Minimum income requirement met';
            } else {
                $unmatchedCriteria[] = 'Minimum income requirement not met';
            }
        }

        // Check debt-to-income ratio (if set)
        if ($product->max_debt_to_income_ratio) {
            $maxScore += 10;
            if ($this->debt_to_income_ratio <= $product->max_debt_to_income_ratio) {
                $score += 10;
                $matchedCriteria[] = 'Debt-to-income ratio acceptable';
            } else {
                $unmatchedCriteria[] = 'Debt-to-income ratio too high';
            }
        }

        // Check credit score (if set)
        if ($product->min_credit_score) {
            $maxScore += 10;
            if ($this->credit_score && $this->credit_score >= $product->min_credit_score) {
                $score += 10;
                $matchedCriteria[] = 'Credit score requirement met';
            } else {
                $unmatchedCriteria[] = 'Credit score requirement not met';
            }
        }

        // Check bad credit allowance
        if ($this->has_bad_credit_history && !$product->allow_bad_credit) {
            $unmatchedCriteria[] = 'Bad credit history not allowed';
        }

        // Check business sectors (if restricted)
        if ($product->business_sectors_allowed && !empty($product->business_sectors_allowed)) {
            if ($this->employment_status === 'self_employed' || $this->employment_status === 'employed') {
                $sector = $this->employment_sector ?? $this->business_type;
                if (!in_array($sector, $product->business_sectors_allowed)) {
                    $unmatchedCriteria[] = 'Business sector not allowed';
                }
            }
        }

        // Calculate final eligibility
        $finalScore = $maxScore > 0 ? ($score / $maxScore) * 100 : 0;
        $isEligible = $finalScore >= 70 && empty($unmatchedCriteria); // 70% threshold

        return [
            'is_eligible' => $isEligible,
            'score' => round($finalScore, 2),
            'matched_criteria' => $matchedCriteria,
            'unmatched_criteria' => $unmatchedCriteria,
        ];
    }

    private function checkEmploymentRequirement(LoanProduct $product): array
    {
        $employmentMap = [
            'employed' => ['employed'],
            'unemployed' => ['unemployed', 'self_employed'],
            'all' => ['employed', 'unemployed', 'self_employed', 'retired'],
        ];

        $allowedStatuses = $employmentMap[$product->employment_requirement] ?? [];
        
        if (!in_array($this->employment_status, $allowedStatuses)) {
            return [
                'match' => false,
                'reason' => 'Employment status not allowed'
            ];
        }

        // Check minimum employment months for employed applicants
        if ($product->employment_requirement === 'employed' && 
            $this->employment_status === 'employed' && 
            $product->min_employment_months) {
            
            if ($this->months_with_current_employer < $product->min_employment_months) {
                return [
                    'match' => false,
                    'reason' => 'Insufficient employment duration'
                ];
            }
        }

        return ['match' => true, 'reason' => null];
    }

    public function canApplyTo(LoanProduct $product): bool
    {
        $eligibility = $this->calculateEligibilityScore($product);
        return $eligibility['is_eligible'];
    }

    public function submit(): bool
    {
        if ($this->status !== 'draft') {
            return false;
        }

        $this->update([
            'status' => 'submitted',
            'submitted_at' => now(),
        ]);

        // Find and store matching products
        $matchingProducts = $this->findMatchingProducts();
        $this->update(['matching_products' => $matchingProducts]);

        return true;
    }

    public function approve(User $reviewer, ?LoanProduct $loanProduct = null): bool
    {
        if (!in_array($this->status, ['submitted', 'under_review'])) {
            return false;
        }

        $this->update([
            'status' => 'approved',
            'approved_at' => now(),
            'reviewed_by' => $reviewer->id,
            'reviewed_at' => now(),
            'loan_product_id' => $loanProduct?->id,
            'lender_id' => $loanProduct?->lender_id,
        ]);

        return true;
    }

    public function reject(User $reviewer, array $reasons): bool
    {
        if (!in_array($this->status, ['submitted', 'under_review'])) {
            return false;
        }

        $this->update([
            'status' => 'rejected',
            'reviewed_by' => $reviewer->id,
            'reviewed_at' => now(),
            'rejection_reasons' => $reasons,
        ]);

        return true;
    }

    public function disburse(): bool
    {
        if ($this->status !== 'approved') {
            return false;
        }

        $this->update([
            'status' => 'disbursed',
            'disbursed_at' => now(),
        ]);

        return true;
    }

    public function cancel(): bool
    {
        if (in_array($this->status, ['disbursed', 'cancelled'])) {
            return false;
        }

        $this->update(['status' => 'cancelled']);
        return true;
    }

    // Check if application is editable
    public function isEditable(): bool
    {
        return $this->status === 'draft';
    }

    // Check if documents can be uploaded
    public function canUploadDocuments(): bool
    {
        return in_array($this->status, ['draft', 'submitted', 'under_review']);
    }

    // Get completion percentage
    public function getCompletionPercentage(): int
    {
        $requiredFields = [
            'first_name', 'last_name', 'date_of_birth', 'gender', 'national_id',
            'phone_number', 'email', 'current_address', 'employment_status',
            'total_monthly_income', 'monthly_expenses', 'emergency_contact_name',
            'emergency_contact_phone', 'requested_amount', 'requested_tenure_months'
        ];

        $filledFields = 0;
        foreach ($requiredFields as $field) {
            if (!empty($this->$field)) {
                $filledFields++;
            }
        }

        return (int) (($filledFields / count($requiredFields)) * 100);
    }



    public function commissionBill()
    {
        return $this->hasOne(CommissionBill::class);
    }

    // Scopes
    public function scopeBooked($query)
    {
        return $query->where('booking_status', 'booked');
    }

    public function scopeUnbooked($query)
    {
        return $query->where('booking_status', 'unbooked');
    }

  

    public function scopeBookedAndApproved($query)
    {
        return $query->where('booking_status', 'booked')
                    ->where('status', 'approved');
    }

    public function scopePendingBilling($query)
    {
        return $query->where('booking_status', 'booked')
                    ->where('status', 'approved')
                    ->whereDoesntHave('commissionBill');
    }

  

    public function getHasBillAttribute(): bool
    {
        return $this->commissionBill()->exists();
    }

    public function getCanCreateBillAttribute(): bool
    {
        return $this->booking_status === 'booked' 
            && $this->status === 'approved' 
            && !$this->has_bill;
    }

    // Mutators
    public function markAsBooked(): void
    {
        $this->update(['booking_status' => 'booked']);
    }

    public function markAsUnbooked(): void
    {
        $this->update(['booking_status' => 'unbooked']);
    }

    // Boot method for auto-generating application number

   

  

}