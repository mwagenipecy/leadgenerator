<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\LoanCategory;

class LoanProduct extends Model
{
    use HasFactory, HasUuids;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'lender_id',
        'created_by',
        'updated_by',
        'name',
        'description',
        'product_code',
        'min_amount',
        'max_amount',
        'min_tenure_months',
        'max_tenure_months',
        'interest_rate_min',
        'interest_rate_max',
        'interest_type',
        'employment_requirement',
        'min_employment_months',
        'min_age',
        'max_age',
        'min_monthly_income',
        'max_debt_to_income_ratio',
        'min_credit_score',
        'allow_bad_credit',
        'processing_fee_percentage',
        'processing_fee_fixed',
        'late_payment_fee',
        'early_repayment_fee_percentage',
        'requires_collateral',
        'collateral_types',
        'requires_guarantor',
        'min_guarantors',
        'required_documents',
        'approval_time_days',
        'disbursement_time_days',
        'disbursement_methods',
        'is_active',
        'status',
        'auto_approval_eligible',
        'auto_approval_max_amount',
        'terms_and_conditions',
        'eligibility_criteria',
        'business_sectors_allowed',
        'promotional_tag',
        'key_features',
        'minimum_dsr',
        'loan_type',
        'loan_category_id'
    ];

    protected $casts = [
        'min_amount' => 'decimal:2',
        'max_amount' => 'decimal:2',
        'interest_rate_min' => 'decimal:2',
        'interest_rate_max' => 'decimal:2',
        'min_monthly_income' => 'decimal:2',
        'max_debt_to_income_ratio' => 'decimal:2',
        'processing_fee_percentage' => 'decimal:2',
        'processing_fee_fixed' => 'decimal:2',
        'late_payment_fee' => 'decimal:2',
        'early_repayment_fee_percentage' => 'decimal:2',
        'auto_approval_max_amount' => 'decimal:2',
        'requires_collateral' => 'boolean',
        'requires_guarantor' => 'boolean',
        'allow_bad_credit' => 'boolean',
        'is_active' => 'boolean',
        'status' => 'string',
        'auto_approval_eligible' => 'boolean',
        'required_documents' => 'array',
        'collateral_types' => 'array',
        'disbursement_methods' => 'array',
        'business_sectors_allowed' => 'array',
        'key_features' => 'array',
    ];

    // Relationships
    public function lender(): BelongsTo
    {
        return $this->belongsTo(Lender::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function loanCategory(): BelongsTo
    {
        return $this->belongsTo(LoanCategory::class, 'loan_category_id');
    }

    public function regions(): BelongsToMany
    {
        return $this->belongsToMany(Region::class, 'loan_product_region')->withTimestamps();
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    

    // Boot method to generate product code
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($product) {
            if (empty($product->product_code)) {
                $product->product_code = $product->generateProductCode();
            }
        });
    }

    // Generate unique product code
    private function generateProductCode(): string
    {
        do {
            $code = 'LP' . Str::upper(Str::random(2)) . rand(1000, 9999);
        } while (self::where('product_code', $code)->exists());
        
        return $code;
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true)->where('status', '!=', 'deleted');
    }

    public function scopeNotDeleted($query)
    {
        return $query->where('status', '!=', 'deleted');
    }

    public function scopeForEmploymentType($query, string $employmentType)
    {
        return $query->where(function ($q) use ($employmentType) {
            $q->where('employment_requirement', $employmentType)
              ->orWhere('employment_requirement', 'all');
        });
    }

    public function scopeForAmount($query, float $amount)
    {
        return $query->where('min_amount', '<=', $amount)
                    ->where('max_amount', '>=', $amount);
    }

    public function scopeForTenure($query, int $months)
    {
        return $query->where('min_tenure_months', '<=', $months)
                    ->where('max_tenure_months', '>=', $months);
    }

    // Helper Methods
    public function getAmountRangeAttribute(): string
    {
        return 'TSh ' . number_format($this->min_amount) . ' - TSh ' . number_format($this->max_amount);
    }

    public function getTenureRangeAttribute(): string
    {
        if ($this->min_tenure_months == $this->max_tenure_months) {
            return $this->min_tenure_months . ' month' . ($this->min_tenure_months > 1 ? 's' : '');
        }
        return $this->min_tenure_months . ' - ' . $this->max_tenure_months . ' months';
    }

    public function getInterestRangeAttribute(): string
    {
        if ($this->interest_rate_min == $this->interest_rate_max) {
            return $this->interest_rate_min . '% ' . $this->interest_type;
        }
        return $this->interest_rate_min . '% - ' . $this->interest_rate_max . '% ' . $this->interest_type;
    }

    public function getProcessingFeeAttribute(): string
    {
        if ($this->processing_fee_percentage > 0 && $this->processing_fee_fixed > 0) {
            return $this->processing_fee_percentage . '% + TSh ' . number_format($this->processing_fee_fixed);
        } elseif ($this->processing_fee_percentage > 0) {
            return $this->processing_fee_percentage . '%';
        } elseif ($this->processing_fee_fixed > 0) {
            return 'TSh ' . number_format($this->processing_fee_fixed);
        }
        return 'Free';
    }

    public function getEmploymentRequirementLabelAttribute(): string
    {
        return match($this->employment_requirement) {
            'employed' => 'Employed Only',
            'business' => 'Business',
            'all' => 'All Employment Types',
            default => 'All Employment Types'
        };
    }

    public function getStatusBadgeColorAttribute(): string
    {
        return $this->is_active ? 'green' : 'red';
    }

    public function getStatusBadgeTextAttribute(): string
    {
        return $this->is_active ? 'Active' : 'Inactive';
    }

    // Calculate monthly payment estimate
    public function calculateMonthlyPayment(float $amount, int $months, float $interestRate = null): float
    {
        $rate = $interestRate ?? $this->interest_rate_min;
        
        if ($this->interest_type === 'flat') {
            $interest = ($amount * $rate * $months) / (100 * 12);
            return ($amount + $interest) / $months;
        } elseif ($this->interest_type === 'reducing') {
            $monthlyRate = $rate / (100 * 12);
            if ($monthlyRate == 0) return $amount / $months;
            
            return $amount * ($monthlyRate * pow(1 + $monthlyRate, $months)) / (pow(1 + $monthlyRate, $months) - 1);
        } else { // fixed
            return $amount / $months;
        }
    }

    // Check if applicant is eligible
    public function isEligible(array $applicantData): array
    {
        $eligible = true;
        $reasons = [];

        // Age check
        if (isset($applicantData['age'])) {
            if ($applicantData['age'] < $this->min_age || $applicantData['age'] > $this->max_age) {
                $eligible = false;
                $reasons[] = "Age must be between {$this->min_age} and {$this->max_age} years";
            }
        }

        // Employment check
        if (isset($applicantData['employment_status'])) {
            if ($this->employment_requirement !== 'all') {
                if ($this->employment_requirement === 'employed' && $applicantData['employment_status'] !== 'employed') {
                    $eligible = false;
                    $reasons[] = "Only employed individuals are eligible";
                } elseif ($this->employment_requirement === 'business' && !in_array($applicantData['employment_status'], ['self_employed', 'unemployed'])) {
                    $eligible = false;
                    $reasons[] = "Only business/self-employed individuals are eligible";
                }
            }
        }

        // Income check
        if (isset($applicantData['monthly_income']) && $this->min_monthly_income) {
            if ($applicantData['monthly_income'] < $this->min_monthly_income) {
                $eligible = false;
                $reasons[] = "Minimum monthly income required: TSh " . number_format($this->min_monthly_income);
            }
        }

        // Credit score check
        if (isset($applicantData['credit_score']) && $this->min_credit_score) {
            if ($applicantData['credit_score'] < $this->min_credit_score) {
                if (!$this->allow_bad_credit) {
                    $eligible = false;
                    $reasons[] = "Minimum credit score required: {$this->min_credit_score}";
                }
            }
        }

        return [
            'eligible' => $eligible,
            'reasons' => $reasons
        ];
    }

    // Get available document types
    public static function getAvailableDocumentTypes(): array
    {
        return [
            'national_id' => 'National ID',
            'passport' => 'Passport',
            'driving_license' => 'Driving License',
            'birth_certificate' => 'Birth Certificate',
            'employment_letter' => 'Employment Letter',
            'salary_slip' => 'Salary Slip (3 months)',
            'bank_statement' => 'Bank Statement (6 months)',
            'tax_certificate' => 'Tax Certificate',
            'business_license' => 'Business License',
            'financial_statements' => 'Financial Statements',
            'collateral_documents' => 'Collateral Documents',
            'guarantor_id' => 'Guarantor ID',
            'guarantor_employment_letter' => 'Guarantor Employment Letter',
            'utility_bill' => 'Utility Bill (Proof of Residence)',
            'lease_agreement' => 'Lease Agreement',
            'marriage_certificate' => 'Marriage Certificate',
            'divorce_decree' => 'Divorce Decree',
            'academic_certificates' => 'Academic Certificates',
            'professional_certificates' => 'Professional Certificates',
            'medical_certificate' => 'Medical Certificate'
        ];
    }

    // Get available collateral types
    public static function getAvailableCollateralTypes(): array
    {
        return [
            'property' => 'Real Estate/Property',
            'vehicle' => 'Vehicle',
            'machinery' => 'Machinery/Equipment',
            'inventory' => 'Inventory/Stock',
            'fixed_deposit' => 'Fixed Deposit',
            'shares' => 'Shares/Securities',
            'gold_jewelry' => 'Gold/Jewelry',
            'electronics' => 'Electronics',
            'other' => 'Other Assets'
        ];
    }

    // Get available business sectors
    public static function getAvailableBusinessSectors(): array
    {
        return [
            'agriculture' => 'Agriculture & Farming',
            'retail' => 'Retail & Trade',
            'manufacturing' => 'Manufacturing',
            'services' => 'Professional Services',
            'transport' => 'Transport & Logistics',
            'construction' => 'Construction',
            'hospitality' => 'Hospitality & Tourism',
            'technology' => 'Technology & IT',
            'healthcare' => 'Healthcare',
            'education' => 'Education',
            'finance' => 'Financial Services',
            'real_estate' => 'Real Estate',
            'mining' => 'Mining & Extraction',
            'energy' => 'Energy & Utilities',
            'media' => 'Media & Entertainment',
            'other' => 'Other'
        ];
    }


    public static function getLoanCategories(): array
    {
        return [
            'personal' => 'Personal Loan',
            'business' => 'Business Loan',
            'auto' => 'Auto Loan',
            'home' => 'Home Loan',
            'education' => 'Education Loan',
            'agriculture' => 'Agriculture Loan',
            'emergency' => 'Emergency Loan',
            'debt_consolidation' => 'Debt Consolidation'
        ];
    }

    public static function getLoanTypes(): array
    {
        return [
            'secured' => 'Secured Loan',
            'unsecured' => 'Unsecured Loan'
        ];
    }



    public function checkEligibility(UserProfile $profile, float $requestedAmount, int $requestedTenure): array
    {
        $issues = [];
        $score = 100;

        // Amount check
        if ($requestedAmount < $this->min_amount || $requestedAmount > $this->max_amount) {
            $issues[] = "Amount not in range: TSh " . number_format($this->min_amount) . " - " . number_format($this->max_amount);
            $score -= 30;
        }

        // Tenure check
        if ($requestedTenure < $this->min_tenure_months || $requestedTenure > $this->max_tenure_months) {
            $issues[] = "Tenure not in range: {$this->min_tenure_months} - {$this->max_tenure_months} months";
            $score -= 20;
        }

        // Income check
        if ($profile->total_monthly_income < $this->min_monthly_income) {
            $issues[] = "Minimum income requirement: TSh " . number_format($this->min_monthly_income);
            $score -= 25;
        }

        // DSR check
        $monthlyPayment = $this->calculateMonthlyPayment($requestedAmount, $requestedTenure);
        $dsr = $profile->calculateDSR($monthlyPayment);
        if ($dsr > $this->maximum_dsr) {
          //  $issues[] = "DSR too high: {$dsr}% (max: {$this->maximum_dsr}%)";
          //  $score -= 20;
        }

        // Age check (if date of birth is available)
        if ($profile->date_of_birth) {
            $age = $profile->date_of_birth->age;
            if ($age < $this->min_age || $age > $this->max_age) {
               // $issues[] = "Age requirement: {$this->min_age} - {$this->max_age} years";
               // $score -= 15;
            }
        }


        return [
            'eligible' => $score >= 70  , //&& empty($issues),
            'score' => max(0, $score),
            'issues' => $issues,
            'monthly_payment' => $monthlyPayment,
            'dsr' => $dsr
        ];
    }
    
}