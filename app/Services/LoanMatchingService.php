<?php

namespace App\Services;

use App\Models\User;
use App\Models\LoanProduct;
use App\Models\Application;
use Illuminate\Support\Facades\Log;

/**
 * Central service for loan product matching.
 * Uses same criteria on dashboard and application/create; credit_score from users table is most important.
 */
class LoanMatchingService
{
    /** Minimum match percentage required to allow applying (application/create). */
    public const MIN_MATCH_TO_APPLY = 55;

    /**
     * Build borrower profile for matching from User, UserProfile, and latest Application.
     */
    public function getBorrowerProfileForMatching(User $user): array
    {
        $profile = $user->profile;
        $latestApp = Application::with('loanProduct')
            ->where('user_id', $user->id)
            ->whereNotNull('requested_amount')
            ->orderBy('created_at', 'desc')
            ->first();

        $creditScore = $this->getNumericCreditScore(
            $user->credit_score,
            $latestApp ? $latestApp->credit_score : null,
            $profile ? $profile->credit_score : null
        );

        $age = null;
        if ($user->date_of_birth) {
            $age = \Carbon\Carbon::parse($user->date_of_birth)->age;
        } elseif ($latestApp && $latestApp->date_of_birth) {
            $age = \Carbon\Carbon::parse($latestApp->date_of_birth)->age;
        } elseif ($profile && $profile->date_of_birth) {
            $age = \Carbon\Carbon::parse($profile->date_of_birth)->age;
        }

        $totalMonthlyIncome = null;
        if ($latestApp && $latestApp->total_monthly_income > 0) {
            $totalMonthlyIncome = (float) $latestApp->total_monthly_income;
        } elseif ($profile) {
            $totalMonthlyIncome = (float) (
                $profile->total_monthly_income
                ?? (($profile->monthly_salary ?? 0) + ($profile->other_monthly_income ?? 0) + ($profile->monthly_business_income ?? 0))
            );
        }

        $debtToIncomeRatio = $latestApp && $latestApp->debt_to_income_ratio !== null
            ? (float) $latestApp->debt_to_income_ratio
            : null;

        $employmentStatus = $latestApp ? ($latestApp->employment_status ?? null) : ($profile ? $profile->employment_status : null);
        $monthsWithEmployer = $latestApp ? ($latestApp->months_with_current_employer ?? 0) : ($profile ? ($profile->months_with_current_employer ?? 0) : 0);
        $hasBadCredit = (bool) (($latestApp->has_bad_credit_history ?? null) ?? ($profile->has_bad_credit_history ?? null) ?? false);

        $preferredAmount = $latestApp ? (float) $latestApp->requested_amount : null;
        $preferredTenure = $latestApp ? (int) $latestApp->requested_tenure_months : null;
        $preferredLoanCategoryId = null;
        if ($latestApp && $latestApp->loanProduct && $latestApp->loanProduct->loan_category_id) {
            $preferredLoanCategoryId = $latestApp->loanProduct->loan_category_id;
        }

        $monthlyExpenses = $latestApp ? ($latestApp->monthly_expenses ?? 0) : ($profile ? ($profile->monthly_expenses ?? 0) : 0);
        $existingLoanPayments = $latestApp ? ($latestApp->existing_loan_payments ?? 0) : ($profile ? ($profile->existing_loan_payments ?? 0) : 0);
        $employmentSector = $latestApp ? ($latestApp->employment_sector ?? null) : ($profile ? $profile->employment_sector : null);
        $businessType = $latestApp ? $latestApp->business_type : null;

        return [
            'credit_score' => $creditScore,
            'age' => $age,
            'total_monthly_income' => $totalMonthlyIncome,
            'debt_to_income_ratio' => $debtToIncomeRatio,
            'employment_status' => $employmentStatus,
            'months_with_current_employer' => $monthsWithEmployer,
            'has_bad_credit_history' => $hasBadCredit,
            'preferred_amount' => $preferredAmount,
            'preferred_tenure' => $preferredTenure,
            'preferred_loan_category_id' => $preferredLoanCategoryId,
            'monthly_expenses' => (float) $monthlyExpenses,
            'existing_loan_payments' => (float) $existingLoanPayments,
            'employment_sector' => $employmentSector,
            'business_type' => $businessType,
        ];
    }

    /**
     * Build profile from pre-qualification form (application/create) for matching.
     */
    public function getProfileFromPrequalification(User $user, float $requestedAmount, int $requestedTenure, string $loanCategoryName, float $monthlyIncome, float $existingLoans = 0): array
    {
        $profile = $this->getBorrowerProfileForMatching($user);
        $profile['preferred_amount'] = $requestedAmount;
        $profile['preferred_tenure'] = $requestedTenure;
        $profile['existing_loan_payments'] = $existingLoans;
        if ($monthlyIncome > 0) {
            $profile['total_monthly_income'] = $monthlyIncome;
        }
        $category = \App\Models\LoanCategory::where('name', $loanCategoryName)->orWhere('slug', $loanCategoryName)->first();
        $profile['preferred_loan_category_id'] = $category?->id;
        return $profile;
    }

    /**
     * Calculate match result for one product. Same logic for dashboard and application/create.
     * Credit score (from users table) is the most important criterion.
     *
     * @return array{percent: int, matched_criteria: array, unmatched_criteria: array, can_apply: bool}
     */
    public function calculateMatch(LoanProduct $product, array $profile, ?float $requestedAmount = null, ?int $requestedTenure = null, $loanCategoryIdOrName = null): array
    {
        $requestedAmount = $requestedAmount ?? $profile['preferred_amount'] ?? null;
        $requestedTenure = $requestedTenure ?? $profile['preferred_tenure'] ?? null;
        $preferredCategoryId = $profile['preferred_loan_category_id'] ?? null;
        if ($loanCategoryIdOrName !== null && $preferredCategoryId === null) {
            if (is_string($loanCategoryIdOrName)) {
                $cat = \App\Models\LoanCategory::where('name', $loanCategoryIdOrName)->orWhere('slug', $loanCategoryIdOrName)->first();
                $preferredCategoryId = $cat?->id;
            } else {
                $preferredCategoryId = $loanCategoryIdOrName;
            }
        }

        $score = 0;
        $maxScore = 0;
        $matched = [];
        $unmatched = [];

        // 1. Credit score (from users table) - most important
        $maxScore += 25;
        $userScore = $profile['credit_score'] ?? null;
        if ($product->min_credit_score) {
            if ($userScore !== null) {
                if ($userScore >= $product->min_credit_score) {
                    $score += 25;
                    $matched[] = __('matching.credit_score_met');
                } else {
                    if ($product->allow_bad_credit) {
                        $score += 10;
                        $matched[] = __('matching.bad_credit_allowed');
                    } else {
                        $unmatched[] = __('matching.credit_score_below', ['min' => $product->min_credit_score, 'yours' => (int) $userScore]);
                    }
                }
            } else {
                $unmatched[] = __('matching.credit_score_unknown');
            }
        } else {
            $score += 25;
            $matched[] = __('matching.credit_score_not_required');
        }

        if (!empty($profile['has_bad_credit_history']) && !$product->allow_bad_credit) {
            $unmatched[] = __('matching.bad_credit_not_allowed');
        }

        // 2. Loan amount range
        $maxScore += 20;
        $amount = $requestedAmount ?? ($product->min_amount + $product->max_amount) / 2;
        if ($amount >= $product->min_amount && $amount <= $product->max_amount) {
            $score += 20;
            $matched[] = __('matching.amount_in_range');
        } else {
            $unmatched[] = __('matching.amount_out_range', ['min' => number_format($product->min_amount), 'max' => number_format($product->max_amount)]);
        }

        // 3. Tenure range
        $maxScore += 15;
        $tenure = $requestedTenure ?? (int) (($product->min_tenure_months + $product->max_tenure_months) / 2);
        if ($tenure >= $product->min_tenure_months && $tenure <= $product->max_tenure_months) {
            $score += 15;
            $matched[] = __('matching.tenure_in_range');
        } else {
            $unmatched[] = __('matching.tenure_out_range', ['min' => $product->min_tenure_months, 'max' => $product->max_tenure_months]);
        }

        // 4. Loan category
        $maxScore += 10;
        if ($product->loan_category_id && $preferredCategoryId) {
            if ($product->loan_category_id === $preferredCategoryId) {
                $score += 10;
                $matched[] = __('matching.category_match');
            } else {
                $unmatched[] = __('matching.category_no_match');
            }
        } else {
            $score += 6;
        }

        // 5. Age
        $maxScore += 10;
        $age = $profile['age'] ?? null;
        $minAge = $product->min_age ?? 18;
        $maxAge = $product->max_age ?? 100;
        if ($age !== null) {
            if ($age >= $minAge && $age <= $maxAge) {
                $score += 10;
                $matched[] = __('matching.age_met');
            } else {
                $unmatched[] = __('matching.age_not_met', ['min' => $minAge, 'max' => $maxAge]);
            }
        } else {
            $score += 5;
        }

        // 6. Employment requirement
        $maxScore += 10;
        $employmentMap = [
            'employed' => ['employed'],
            'business' => ['unemployed', 'self_employed'],
            'all' => ['employed', 'unemployed', 'self_employed', 'retired', 'student'],
        ];
        $empStatus = $profile['employment_status'] ?? null;
        $allowed = $employmentMap[$product->employment_requirement ?? 'all'] ?? $employmentMap['all'];
        if ($product->employment_requirement === 'all' || ($empStatus && in_array($empStatus, $allowed))) {
            $score += 10;
            $matched[] = __('matching.employment_met');
        } elseif ($empStatus) {
            $unmatched[] = __('matching.employment_not_met');
        } else {
            $score += 4;
        }

        // 7. Min employment months (if employed)
        if (($product->employment_requirement ?? '') === 'employed' && ($product->min_employment_months ?? 0) > 0 && ($empStatus ?? '') === 'employed') {
            $maxScore += 5;
            $months = $profile['months_with_current_employer'] ?? 0;
            if ($months >= $product->min_employment_months) {
                $score += 5;
                $matched[] = __('matching.employment_months_met');
            } else {
                $unmatched[] = __('matching.employment_months_not_met', ['min' => $product->min_employment_months]);
            }
        }

        // 8. Min monthly income
        if ($product->min_monthly_income) {
            $maxScore += 10;
            $income = $profile['total_monthly_income'] ?? null;
            if ($income !== null && $income >= $product->min_monthly_income) {
                $score += 10;
                $matched[] = __('matching.income_met');
            } elseif ($income !== null) {
                $unmatched[] = __('matching.income_below', ['min' => number_format($product->min_monthly_income)]);
            } else {
                $score += 3;
            }
        }

        // 9. Debt-to-income ratio
        if ($product->max_debt_to_income_ratio) {
            $maxScore += 5;
            $dti = $profile['debt_to_income_ratio'] ?? null;
            if ($dti !== null && $dti <= $product->max_debt_to_income_ratio) {
                $score += 5;
                $matched[] = __('matching.dti_met');
            } elseif ($dti !== null) {
                $unmatched[] = __('matching.dti_high');
            } else {
                $score += 2;
            }
        }

        // 10. DSR (if we have income and product has minimum_dsr)
        if (($product->minimum_dsr ?? null) && ($profile['total_monthly_income'] ?? 0) > 0 && $requestedAmount && $requestedTenure) {
            $maxScore += 5;
            $monthlyPayment = $this->estimateMonthlyPayment($product, $requestedAmount, $requestedTenure);
            $totalDebt = ($profile['existing_loan_payments'] ?? 0) + $monthlyPayment;
            $dsr = ($totalDebt / $profile['total_monthly_income']) * 100;
            if ($dsr <= ($product->minimum_dsr ?? 100)) {
                $score += 5;
                $matched[] = __('matching.dsr_met');
            } else {
                $unmatched[] = __('matching.dsr_exceeded', ['max' => $product->minimum_dsr, 'yours' => round($dsr, 1)]);
            }
        }

        // 11. Business sector (if restricted)
        if ($product->business_sectors_allowed && !empty($product->business_sectors_allowed)) {
            $maxScore += 5;
            $sector = $profile['employment_sector'] ?? $profile['business_type'] ?? null;
            if ($sector && in_array($sector, $product->business_sectors_allowed)) {
                $score += 5;
                $matched[] = __('matching.sector_allowed');
            } elseif (in_array($profile['employment_status'] ?? '', ['self_employed', 'employed'])) {
                $unmatched[] = __('matching.sector_not_allowed');
            } else {
                $score += 3;
            }
        }

        // 12. Collateral (if required)
        if ($product->requires_collateral) {
            $maxScore += 5;
            $unmatched[] = __('matching.collateral_required');
            $score += 0;
        }

        // 13. Guarantor (if required)
        if ($product->requires_guarantor) {
            $maxScore += 3;
            $unmatched[] = __('matching.guarantor_required');
            $score += 0;
        }

        $percent = $maxScore > 0 ? (int) round(($score / $maxScore) * 100) : 0;
        $percent = max(0, min(100, $percent));
        $canApply = $percent > self::MIN_MATCH_TO_APPLY;

        return [
            'percent' => $percent,
            'matched_criteria' => $matched,
            'unmatched_criteria' => $unmatched,
            'can_apply' => $canApply,
        ];
    }

    private function getNumericCreditScore($userScore, $appScore, $profileScore): ?float
    {
        foreach ([$userScore, $appScore, $profileScore] as $raw) {
            if ($raw === null) continue;
            if (is_numeric($raw)) return (float) $raw;
            if (is_array($raw) && isset($raw['_value']) && is_numeric($raw['_value'])) return (float) $raw['_value'];
        }
        return null;
    }

    private function estimateMonthlyPayment(LoanProduct $product, float $amount, int $months): float
    {
        $rate = $product->interest_rate_max ?? $product->interest_rate_min ?? 0;
        $monthlyRate = $rate / (100 * 12);
        if ($monthlyRate <= 0) return $amount / $months;
        return $amount * ($monthlyRate * pow(1 + $monthlyRate, $months)) / (pow(1 + $monthlyRate, $months) - 1);
    }
}
