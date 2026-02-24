<?php

namespace App\Services;

use App\Models\LoanProduct;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\Application;
use Carbon\Carbon;

/**
 * Central service for loan product matching.
 * Same criteria used on dashboard and application/create.
 * Credit score (from users table) is the most important criterion.
 */
class LoanProductMatchingService
{
    /** Minimum match % to allow apply (e.g. on application/create). */
    public const MIN_SCORE_TO_APPLY = 55;

    /**
     * Build applicant profile array from User + optional UserProfile + optional Application/form data.
     * Credit score is taken from User (users.credit_score) as primary.
     */
    public function buildApplicantProfile(
        User $user,
        ?UserProfile $profile = null,
        ?float $requestedAmount = null,
        ?int $requestedTenureMonths = null,
        ?Application $latestApplication = null
    ): array {
        $profile = $profile ?? $user->profile;
        $app = $latestApplication ?? Application::with('loanProduct')
            ->where('user_id', $user->id)
            ->whereNotNull('requested_amount')
            ->orderBy('created_at', 'desc')
            ->first();

        $creditScore = $this->normalizeCreditScore(
            $user->credit_score ?? $app->credit_score ?? $profile->credit_score ?? null
        );

        $age = null;
        if ($user->date_of_birth) {
            $age = Carbon::parse($user->date_of_birth)->age;
        } elseif ($app && $app->date_of_birth) {
            $age = Carbon::parse($app->date_of_birth)->age;
        } elseif ($profile && $profile->date_of_birth) {
            $age = Carbon::parse($profile->date_of_birth)->age;
        }

        $totalMonthlyIncome = null;
        if ($app && $app->total_monthly_income > 0) {
            $totalMonthlyIncome = (float) $app->total_monthly_income;
        } elseif ($profile) {
            $totalMonthlyIncome = (float) (
                $profile->total_monthly_income
                ?? (($profile->monthly_salary ?? 0) + ($profile->other_monthly_income ?? 0) + ($profile->monthly_business_income ?? 0))
            );
        }

        $existingLoanPayments = (float) ($app->existing_loan_payments ?? $profile->existing_loan_payments ?? 0);
        $debtToIncomeRatio = $app && $app->debt_to_income_ratio !== null
            ? (float) $app->debt_to_income_ratio
            : null;

        $employmentStatus = $app->employment_status ?? $profile->employment_status ?? null;
        $monthsWithEmployer = (int) ($app->months_with_current_employer ?? $profile->months_with_current_employer ?? 0);
        $employmentSector = $app->employment_sector ?? $profile->employment_sector ?? null;
        $businessType = $app->business_type ?? $profile->business_type ?? null;

        $hasBadCreditHistory = (bool) ($app->has_bad_credit_history ?? $profile->has_bad_credit_history ?? false);

        $preferredAmount = $requestedAmount ?? ($app ? (float) $app->requested_amount : null);
        $preferredTenure = $requestedTenureMonths ?? ($app ? (int) $app->requested_tenure_months : null);
        $preferredLoanCategoryId = null;
        if ($app && $app->relationLoaded('loanProduct') && $app->loanProduct && $app->loanProduct->loan_category_id) {
            $preferredLoanCategoryId = $app->loanProduct->loan_category_id;
        } elseif ($app) {
            $app->load('loanProduct');
            if ($app->loanProduct && $app->loanProduct->loan_category_id) {
                $preferredLoanCategoryId = $app->loanProduct->loan_category_id;
            }
        }

        return [
            'credit_score' => $creditScore,
            'age' => $age,
            'total_monthly_income' => $totalMonthlyIncome,
            'existing_loan_payments' => $existingLoanPayments,
            'debt_to_income_ratio' => $debtToIncomeRatio,
            'employment_status' => $employmentStatus,
            'months_with_current_employer' => $monthsWithEmployer,
            'employment_sector' => $employmentSector,
            'business_type' => $businessType,
            'has_bad_credit_history' => $hasBadCreditHistory,
            'preferred_amount' => $preferredAmount,
            'preferred_tenure' => $preferredTenure,
            'preferred_loan_category_id' => $preferredLoanCategoryId,
        ];
    }

    /**
     * Get match result for one product: score, matched/unmatched criteria, can_apply.
     * Uses same criteria as application/create; credit_score (from users table) is most important.
     */
    public function getMatchResult(array $applicantProfile, LoanProduct $product): array
    {
        $matched = [];
        $unmatched = [];
        $score = 0;
        $maxScore = 0;

        $creditScore = $applicantProfile['credit_score'] ?? null;
        $age = $applicantProfile['age'] ?? null;
        $totalIncome = $applicantProfile['total_monthly_income'] ?? null;
        $debtToIncome = $applicantProfile['debt_to_income_ratio'] ?? null;
        $employmentStatus = $applicantProfile['employment_status'] ?? null;
        $monthsWithEmployer = $applicantProfile['months_with_current_employer'] ?? 0;
        $hasBadCredit = $applicantProfile['has_bad_credit_history'] ?? false;
        $preferredAmount = $applicantProfile['preferred_amount'] ?? null;
        $preferredTenure = $applicantProfile['preferred_tenure'] ?? null;
        $preferredCategoryId = $applicantProfile['preferred_loan_category_id'] ?? null;
        $employmentSector = $applicantProfile['employment_sector'] ?? null;
        $businessType = $applicantProfile['business_type'] ?? null;
        $existingPayments = $applicantProfile['existing_loan_payments'] ?? 0;

        // --- 1. CREDIT SCORE (most important - from users table) ---
        $maxScore += 25;
        if ($product->min_credit_score) {
            if ($creditScore !== null) {
                if ($creditScore >= $product->min_credit_score) {
                    $score += 25;
                    $matched[] = __('matching.credit_score_met', ['min' => $product->min_credit_score, 'yours' => (int) $creditScore]);
                } else {
                    if ($product->allow_bad_credit) {
                        $score += 10;
                        $matched[] = __('matching.credit_bad_allowed');
                    } else {
                        $unmatched[] = __('matching.credit_score_not_met', ['min' => $product->min_credit_score, 'yours' => (int) $creditScore]);
                    }
                }
            } else {
                $unmatched[] = __('matching.credit_score_unknown');
            }
        } else {
            $score += 25;
            $matched[] = __('matching.credit_score_not_required');
        }

        // Credit score is mandatory: no score = cannot qualify
        if ($creditScore === null) {
            $unmatched[] = __('matching.credit_score_mandatory');
        }

        if ($hasBadCredit && !$product->allow_bad_credit) {
            $unmatched[] = __('matching.bad_credit_not_allowed');
        }

        // --- 2. Loan amount ---
        $maxScore += 15;
        if ($preferredAmount !== null) {
            if ($preferredAmount >= $product->min_amount && $preferredAmount <= $product->max_amount) {
                $score += 15;
                $matched[] = __('matching.amount_in_range', ['min' => number_format($product->min_amount), 'max' => number_format($product->max_amount)]);
            } else {
                $unmatched[] = __('matching.amount_out_of_range', ['min' => number_format($product->min_amount), 'max' => number_format($product->max_amount)]);
            }
        } else {
            $score += 8;
        }

        // --- 3. Tenure ---
        $maxScore += 12;
        if ($preferredTenure !== null) {
            if ($preferredTenure >= $product->min_tenure_months && $preferredTenure <= $product->max_tenure_months) {
                $score += 12;
                $matched[] = __('matching.tenure_in_range', ['min' => $product->min_tenure_months, 'max' => $product->max_tenure_months]);
            } else {
                $unmatched[] = __('matching.tenure_out_of_range', ['min' => $product->min_tenure_months, 'max' => $product->max_tenure_months]);
            }
        } else {
            $score += 6;
        }

        // --- 4. Age ---
        $maxScore += 12;
        if ($product->min_age || $product->max_age) {
            if ($age !== null) {
                if ($age >= $product->min_age && $age <= $product->max_age) {
                    $score += 12;
                    $matched[] = __('matching.age_met', ['min' => $product->min_age, 'max' => $product->max_age]);
                } else {
                    $unmatched[] = __('matching.age_not_met', ['min' => $product->min_age, 'max' => $product->max_age]);
                }
            } else {
                $score += 6;
            }
        } else {
            $score += 12;
        }

        // --- 5. Employment ---
        $maxScore += 12;
        $employmentMap = [
            'employed' => ['employed'],
            'business' => ['unemployed', 'self_employed'],
            'all' => ['employed', 'unemployed', 'self_employed', 'retired', 'student'],
        ];
        $allowed = $employmentMap[$product->employment_requirement] ?? $employmentMap['all'];
        if ($product->employment_requirement === 'all' || ($employmentStatus && in_array($employmentStatus, $allowed))) {
            if ($product->employment_requirement === 'employed' && $employmentStatus === 'employed' && $product->min_employment_months) {
                if ($monthsWithEmployer >= $product->min_employment_months) {
                    $score += 12;
                    $matched[] = __('matching.employment_met');
                } else {
                    $unmatched[] = __('matching.employment_months_not_met', ['min' => $product->min_employment_months]);
                }
            } else {
                $score += 12;
                $matched[] = __('matching.employment_met');
            }
        } elseif ($employmentStatus) {
            $unmatched[] = __('matching.employment_not_allowed');
        } else {
            $score += 6;
        }

        // --- 6. Minimum income ---
        if ($product->min_monthly_income) {
            $maxScore += 8;
            if ($totalIncome !== null && $totalIncome >= $product->min_monthly_income) {
                $score += 8;
                $matched[] = __('matching.income_met', ['min' => number_format($product->min_monthly_income)]);
            } elseif ($totalIncome !== null) {
                $unmatched[] = __('matching.income_not_met', ['min' => number_format($product->min_monthly_income)]);
            } else {
                $score += 4;
            }
        }

        // --- 7. Debt-to-income ratio ---
        if ($product->max_debt_to_income_ratio && $debtToIncome !== null) {
            $maxScore += 6;
            if ($debtToIncome <= $product->max_debt_to_income_ratio) {
                $score += 6;
                $matched[] = __('matching.dti_acceptable');
            } else {
                $unmatched[] = __('matching.dti_too_high');
            }
        }

        // --- 8. DSR (Debt Service Ratio) from income + lender min/max interest ---
        $maxDsr = $product->minimum_dsr ? (float) $product->minimum_dsr : null;
        $monthlyPaymentAtMinRate = null;
        $monthlyPaymentAtMaxRate = null;
        $dsrAtMinRate = null;
        $dsrAtMaxRate = null;

        if ($totalIncome !== null && $totalIncome > 0 && $preferredAmount !== null && $preferredTenure !== null) {
            $rateMin = (float) ($product->interest_rate_min ?? 0);
            $rateMax = (float) ($product->interest_rate_max ?? $product->interest_rate_min ?? 0);
            $monthlyPaymentAtMinRate = $product->calculateMonthlyPayment($preferredAmount, $preferredTenure, $rateMin);
            $monthlyPaymentAtMaxRate = $product->calculateMonthlyPayment($preferredAmount, $preferredTenure, $rateMax);
            $dsrAtMinRate = (($existingPayments + $monthlyPaymentAtMinRate) / $totalIncome) * 100;
            $dsrAtMaxRate = (($existingPayments + $monthlyPaymentAtMaxRate) / $totalIncome) * 100;
        }

        if ($maxDsr !== null) {
            $maxScore += 6;
            if ($totalIncome === null || $totalIncome <= 0) {
                $unmatched[] = __('matching.income_required_for_dsr');
            } elseif ($preferredAmount === null || $preferredTenure === null) {
                $score += 3;
            } else {
                // Use worst case: DSR at max interest rate (lender may charge up to max)
                $dsrWorst = $dsrAtMaxRate;
                if ($dsrWorst <= $maxDsr) {
                    $score += 6;
                    $matched[] = __('matching.dsr_acceptable_range', [
                        'dsr_min' => number_format($dsrAtMinRate, 1),
                        'dsr_max' => number_format($dsrAtMaxRate, 1),
                        'max' => $maxDsr,
                    ]);
                } else {
                    $unmatched[] = __('matching.dsr_too_high_range', [
                        'dsr_min' => number_format($dsrAtMinRate, 1),
                        'dsr_max' => number_format($dsrAtMaxRate, 1),
                        'max' => $maxDsr,
                    ]);
                }
            }
        }

        // --- 9. Loan category ---
        $maxScore += 6;
        if ($preferredCategoryId && $product->loan_category_id) {
            if ($product->loan_category_id === $preferredCategoryId) {
                $score += 6;
                $matched[] = __('matching.category_match');
            } else {
                $unmatched[] = __('matching.category_no_match');
            }
        } else {
            $score += 4;
        }

        // --- 10. Business sector (if restricted) ---
        if ($product->business_sectors_allowed && !empty($product->business_sectors_allowed)) {
            $maxScore += 4;
            $sector = $employmentSector ?? $businessType;
            if ($sector && in_array($sector, $product->business_sectors_allowed)) {
                $score += 4;
                $matched[] = __('matching.sector_allowed');
            } elseif (in_array($employmentStatus, ['self_employed', 'employed'])) {
                $unmatched[] = __('matching.sector_not_allowed');
            } else {
                $score += 2;
            }
        }

        // --- 11. Collateral (if required) ---
        if ($product->requires_collateral) {
            $maxScore += 4;
            $unmatched[] = __('matching.collateral_required');
        }

        // --- 12. Guarantor (if required) ---
        if ($product->requires_guarantor) {
            $maxScore += 3;
            $unmatched[] = __('matching.guarantor_required');
        }

        // --- True applicant matches only (not product info) ---
        if (!$product->requires_collateral) {
            $matched[] = __('matching.no_collateral_required');
        }
        if (!$product->requires_guarantor) {
            $matched[] = __('matching.no_guarantor_required');
        }

        // --- Product details (for modal "About this product" - not "where you match") ---
        $productInfo = [];
        $loanTypeLabel = $product->loan_type === 'secured' ? __('matching.loan_type_secured') : __('matching.loan_type_unsecured');
        $productInfo[] = __('matching.product_loan_type', ['type' => $loanTypeLabel]);
        $productInfo[] = __('matching.product_interest_rate', [
            'min' => $product->interest_rate_min,
            'max' => $product->interest_rate_max,
            'interest_type' => $product->interest_type ?? 'reducing',
        ]);
        $productInfo[] = __('matching.product_amount_range', ['min' => number_format($product->min_amount), 'max' => number_format($product->max_amount)]);
        $productInfo[] = __('matching.product_tenure_range', ['min' => $product->min_tenure_months, 'max' => $product->max_tenure_months]);
        if ($product->min_age || $product->max_age) {
            $productInfo[] = __('matching.product_age_range', ['min' => $product->min_age ?? 18, 'max' => $product->max_age ?? 100]);
        }
        $empLabel = match($product->employment_requirement ?? 'all') {
            'employed' => __('matching.employment_employed_only'),
            'business' => __('matching.employment_business'),
            default => __('matching.employment_all_types'),
        };
        $productInfo[] = __('matching.product_employment_requirement', ['requirement' => $empLabel]);
        if (($product->employment_requirement === 'employed') && $product->min_employment_months) {
            $productInfo[] = __('matching.product_min_employment_months', ['months' => $product->min_employment_months]);
        }
        if ($product->approval_time_days) {
            $productInfo[] = __('matching.product_approval_days', ['days' => $product->approval_time_days]);
        }
        if ($product->disbursement_time_days) {
            $productInfo[] = __('matching.product_disbursement_days', ['days' => $product->disbursement_time_days]);
        }
        if (($product->processing_fee_percentage ?? 0) > 0 || ($product->processing_fee_fixed ?? 0) > 0) {
            $feeParts = [];
            if (($product->processing_fee_percentage ?? 0) > 0) $feeParts[] = $product->processing_fee_percentage . '%';
            if (($product->processing_fee_fixed ?? 0) > 0) $feeParts[] = 'TSh ' . number_format($product->processing_fee_fixed);
            $productInfo[] = __('matching.product_processing_fee', ['fee' => implode(' + ', $feeParts)]);
        } else {
            $productInfo[] = __('matching.product_no_processing_fee');
        }
        if ($product->minimum_dsr) {
            $productInfo[] = __('matching.product_max_dsr', ['max' => $product->minimum_dsr]);
        }
        if ($product->max_debt_to_income_ratio) {
            $productInfo[] = __('matching.product_max_dti', ['max' => $product->max_debt_to_income_ratio]);
        }
        if ($product->min_monthly_income) {
            $productInfo[] = __('matching.product_min_income', ['min' => number_format($product->min_monthly_income)]);
        }
        if ($product->min_credit_score) {
            $productInfo[] = __('matching.product_min_credit_score', ['min' => $product->min_credit_score]);
        }
        $docCount = is_array($product->required_documents) ? count($product->required_documents) : 0;
        if ($docCount > 0) {
            $productInfo[] = __('matching.product_required_documents_count', ['count' => $docCount]);
        }
        // DSR and monthly payment from income × lender min/max interest
        if ($monthlyPaymentAtMinRate !== null && $dsrAtMinRate !== null && $totalIncome > 0) {
            $productInfo[] = __('matching.dsr_estimated_from_income', [
                'dsr_min' => number_format($dsrAtMinRate, 1),
                'dsr_max' => number_format($dsrAtMaxRate, 1),
            ]);
            $productInfo[] = __('matching.monthly_payment_estimate', [
                'min' => number_format($monthlyPaymentAtMinRate),
                'max' => number_format($monthlyPaymentAtMaxRate),
            ]);
        }
        if ($product->auto_approval_eligible && $product->auto_approval_max_amount && $preferredAmount !== null && $preferredAmount <= $product->auto_approval_max_amount) {
            $productInfo[] = __('matching.auto_approval_eligible', ['max' => number_format($product->auto_approval_max_amount)]);
        }
        if ($product->relationLoaded('loanCategory') && $product->loanCategory) {
            $productInfo[] = __('matching.product_loan_category', ['name' => $product->loanCategory->name]);
        } elseif ($product->loan_category_id) {
            $product->load('loanCategory');
            if ($product->loanCategory) {
                $productInfo[] = __('matching.product_loan_category', ['name' => $product->loanCategory->name]);
            }
        }

        $percent = $maxScore > 0 ? (int) round(($score / $maxScore) * 100) : 0;
        $percent = max(0, min(100, $percent));
        $canApply = $percent > self::MIN_SCORE_TO_APPLY;

        // Credit score is mandatory: no score = cannot qualify
        if ($creditScore === null) {
            $canApply = false;
        }

        return [
            'score' => $percent,
            'matched_criteria' => $matched,
            'unmatched_criteria' => $unmatched,
            'product_info' => $productInfo,
            'can_apply' => $canApply,
            'min_score_to_apply' => self::MIN_SCORE_TO_APPLY,
        ];
    }

    /**
     * Get matching products with results for a user (e.g. dashboard).
     */
    public function getMatchingProductsForUser(User $user): \Illuminate\Support\Collection
    {
        $profile = $this->buildApplicantProfile($user);
        $products = LoanProduct::with(['lender', 'loanCategory'])
            ->where('is_active', true)
            ->where('status', '!=', 'deleted')
            ->get();

        return $products->map(function (LoanProduct $product) use ($profile) {
            $result = $this->getMatchResult($profile, $product);
            return [
                'product' => $product,
                'match_percent' => $result['score'],
                'matched_criteria' => $result['matched_criteria'],
                'unmatched_criteria' => $result['unmatched_criteria'],
                'product_info' => $result['product_info'] ?? [],
                'can_apply' => $result['can_apply'],
            ];
        })->sortByDesc('match_percent')->values();
    }

    private function normalizeCreditScore($value): ?float
    {
        if ($value === null) {
            return null;
        }
        if (is_numeric($value)) {
            return (float) $value;
        }
        if (is_array($value)) {
            if (isset($value['_value']) && is_numeric($value['_value'])) {
                return (float) $value['_value'];
            }
            if (isset($value[0]) && is_numeric($value[0])) {
                return (float) $value[0];
            }
        }
        if (is_string($value) && is_numeric($value)) {
            return (float) $value;
        }
        return null;
    }
}
