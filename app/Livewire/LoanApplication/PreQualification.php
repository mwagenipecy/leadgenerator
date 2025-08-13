<?php

namespace App\Livewire\LoanApplication;

use App\Models\LoanProduct;
use App\Models\UserProfile;
use Livewire\Component;
use Livewire\Attributes\Rule;
use Illuminate\Support\Facades\Auth;

class PreQualification extends Component
{
    public $currentStep = 'category'; // category, criteria, results
    public $userProfile;
    
    // Loan Selection
    #[Rule('required|string')]
    public $loan_category = '';
    
    #[Rule('required|string')]
    public $loan_type = '';
    
    #[Rule('required|numeric|min:1000')]
    public $requested_amount = 50000;
    
    #[Rule('required|integer|min:1|max:120')]
    public $requested_tenure = 12;
    
    // Financial Data (can be overridden)
    #[Rule('required|numeric|min:1')]
    public $monthly_income = 0;
    
    #[Rule('required|numeric|min:0')]
    public $existing_loans = 0;
    
    public $use_profile_data = true;
    
    // Results
    public $calculated_dsr = 0;
    public $matching_products = [];
    public $selected_lenders = [];
    
    // Filter options
    public $filter_by_eligibility = 'all'; // all, eligible, not_eligible
    public $sort_by = 'score'; // score, interest_rate, processing_time
    
    public function mount()
    {
        $this->userProfile = Auth::user()->profile;
        
        if ($this->userProfile) {
            $this->monthly_income = $this->userProfile->total_monthly_income;
            $this->existing_loans = $this->userProfile->existing_loan_payments;
        }
    }

    public function render()
    {
        return view('livewire.loan-application.pre-qualification', [
            'loanCategories' => LoanProduct::getLoanCategories(),
            'loanTypes' => LoanProduct::getLoanTypes(),
            'availableProducts' => $this->getFilteredProducts(),
        ]);
    }

    public function selectLoanCategory($category)
    {
        $this->loan_category = $category;
        $this->loan_type = ''; // Reset loan type when category changes
    }

    public function selectLoanType($type)
    {
        $this->loan_type = $type;
        $this->currentStep = 'criteria';
    }

    public function toggleProfileData()
    {
        $this->use_profile_data = !$this->use_profile_data;
        
        if ($this->use_profile_data && $this->userProfile) {
            $this->monthly_income = $this->userProfile->total_monthly_income;
            $this->existing_loans = $this->userProfile->existing_loan_payments;
        }
    }

    public function calculateEligibility()
    {
        $this->validate([
            'loan_category' => 'required|string',
            'loan_type' => 'required|string',
            'requested_amount' => 'required|numeric|min:1000',
            'requested_tenure' => 'required|integer|min:1|max:120',
            'monthly_income' => 'required|numeric|min:1',
            'existing_loans' => 'required|numeric|min:0',
        ]);

        // Calculate DSR
        $this->calculated_dsr = $this->calculateDSR();
        
        // Find matching products
        $this->findMatchingProducts();
        
        $this->currentStep = 'results';
    }

    private function calculateDSR(): float
    {
        // Calculate estimated monthly payment (using average interest rate of 15%)
        $principal = $this->requested_amount;
        $monthlyRate = 0.15 / 12; // 15% annual rate
        $numberOfPayments = $this->requested_tenure;
        
        $estimatedMonthlyPayment = $principal * 
            ($monthlyRate * pow(1 + $monthlyRate, $numberOfPayments)) / 
            (pow(1 + $monthlyRate, $numberOfPayments) - 1);

        // Calculate DSR
        $totalMonthlyDebt = $this->existing_loans + $estimatedMonthlyPayment;
        return ($totalMonthlyDebt / $this->monthly_income) * 100;
    }

    private function findMatchingProducts()
    {
        $products = LoanProduct::with('lender')
            ->where('is_active', true)
            ->where('loan_category', $this->loan_category)
            ->where('loan_type', $this->loan_type)
            ->where('min_amount', '<=', $this->requested_amount)
            ->where('max_amount', '>=', $this->requested_amount)
            ->where('min_tenure_months', '<=', $this->requested_tenure)
            ->where('max_tenure_months', '>=', $this->requested_tenure)
            ->where('min_monthly_income', '<=', $this->monthly_income)
            ->get();

        $this->matching_products = $products->map(function ($product) {
            // Create mock profile for eligibility check if no real profile
            $profile = $this->userProfile ?? new UserProfile([
                'total_monthly_income' => $this->monthly_income,
                'existing_loan_payments' => $this->existing_loans,
                'date_of_birth' => now()->subYears(30), // Default age 30
            ]);

            $eligibility = $product->checkEligibility($profile, $this->requested_amount, $this->requested_tenure);
            
            return [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'lender_name' => $product->lender->company_name,
                'lender_id' => $product->lender->id,
                'loan_category' => $product->loan_category,
                'loan_type' => $product->loan_type,
                'interest_rate_min' => $product->interest_rate_min,
                'interest_rate_max' => $product->interest_rate_max,
                'monthly_payment' => $eligibility['monthly_payment'],
                'dsr' => $eligibility['dsr'],
                'max_dsr_allowed' => $product->maximum_dsr,
                'processing_fee_percentage' => $product->processing_fee_percentage,
                'processing_fee_fixed' => $product->processing_fee_fixed,
                'approval_time_days' => $product->approval_time_days,
                'disbursement_time_days' => $product->disbursement_time_days,
                'eligible' => $eligibility['eligible'],
                'eligibility_score' => $eligibility['score'],
                'eligibility_issues' => $eligibility['issues'],
                'collateral_required' => $product->loan_type === 'secured',
                'collateral_requirements' => $product->collateral_requirements,
                'features' => $product->features,
                'required_documents' => $product->required_documents,
            ];
        })->sortByDesc('eligibility_score')->values()->toArray();
    }

    private function getFilteredProducts()
    {
        $products = collect($this->matching_products);

        // Filter by eligibility
        if ($this->filter_by_eligibility === 'eligible') {
            $products = $products->where('eligible', true);
        } elseif ($this->filter_by_eligibility === 'not_eligible') {
            $products = $products->where('eligible', false);
        }

        // Sort products
        switch ($this->sort_by) {
            case 'interest_rate':
                $products = $products->sortBy('interest_rate_min');
                break;
            case 'processing_time':
                $products = $products->sortBy('approval_time_days');
                break;
            case 'score':
            default:
                $products = $products->sortByDesc('eligibility_score');
                break;
        }

        return $products->values()->toArray();
    }

    public function selectLender($lenderId)
    {
        if (in_array($lenderId, $this->selected_lenders)) {
            $this->selected_lenders = array_filter($this->selected_lenders, fn($id) => $id !== $lenderId);
        } else {
            $this->selected_lenders[] = $lenderId;
        }
    }

    public function selectAllEligible()
    {
        $this->selected_lenders = collect($this->matching_products)
            ->where('eligible', true)
            ->pluck('lender_id')
            ->unique()
            ->toArray();
    }

    public function clearSelection()
    {
        $this->selected_lenders = [];
    }

    public function proceedToApplication()
    {
        if (empty($this->selected_lenders)) {
            session()->flash('error', 'Please select at least one lender to proceed.');
            return;
        }

        // Store data in session and redirect to application
        session([
            'prequalification_data' => [
                'loan_category' => $this->loan_category,
                'loan_type' => $this->loan_type,
                'requested_amount' => $this->requested_amount,
                'requested_tenure' => $this->requested_tenure,
                'monthly_income' => $this->monthly_income,
                'existing_loans' => $this->existing_loans,
                'calculated_dsr' => $this->calculated_dsr,
                'selected_lenders' => $this->selected_lenders,
                'matching_products' => $this->matching_products,
            ]
        ]);

        return redirect()->route('loan-application.completed');
    }

    public function backToCategory()
    {
        $this->currentStep = 'category';
        $this->reset(['loan_category', 'loan_type']);
    }

    public function backToCriteria()
    {
        $this->currentStep = 'criteria';
    }

    public function updated($propertyName)
    {
        // Auto-recalculate when criteria changes
        if (in_array($propertyName, ['requested_amount', 'requested_tenure', 'monthly_income', 'existing_loans'])) {
            if ($this->currentStep === 'results' && 
                $this->requested_amount > 0 && 
                $this->requested_tenure > 0 && 
                $this->monthly_income > 0) {
                $this->calculateEligibility();
            }
        }

        // Update filters
        if (in_array($propertyName, ['filter_by_eligibility', 'sort_by'])) {
            // Filters will be applied in the next render
        }
    }

    public function getLoanCategoryDescription($category): string
    {
        $descriptions = [
            'personal' => 'General purpose loans for personal expenses, emergencies, or other individual needs.',
            'business' => 'Loans to support business operations, expansion, or equipment purchase.',
            'auto' => 'Financing for vehicle purchases including cars, motorcycles, and commercial vehicles.',
            'home' => 'Mortgage and home improvement loans for property purchase or renovation.',
            'education' => 'Educational loans for tuition fees, training, and academic expenses.',
            'agriculture' => 'Specialized loans for farming equipment, seeds, livestock, and agricultural development.',
            'emergency' => 'Quick access loans for urgent financial needs and unexpected expenses.',
            'debt_consolidation' => 'Loans to combine multiple debts into a single payment with better terms.',
        ];

        return $descriptions[$category] ?? 'Loan for specific financial needs.';
    }

    public function getLoanTypeDescription($type): string
    {
        $descriptions = [
            'secured' => 'Requires collateral (property, vehicle, etc.) as security. Generally offers lower interest rates.',
            'unsecured' => 'No collateral required. Based on creditworthiness and income. Higher interest rates.',
        ];

        return $descriptions[$type] ?? '';
    }
}