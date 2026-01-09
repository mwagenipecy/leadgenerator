<?php

namespace App\Livewire\LoanApplication;

use App\Models\LoanProduct;
use App\Models\LoanCategory;
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
    public $matching_products = [];
    public $selected_products = []; // Array of product IDs
    public $selected_lenders = []; // Array to track selected lenders
    
    // Filter options
    public $filter_by_eligibility = 'all'; // all, eligible, not_eligible
    public $sort_by = 'score'; // score, interest_rate, processing_time
    
    // Product details modal
    public $showProductDetails = false;
    public $selectedProductForDetails = null;

    public function mount()
    {
        $this->userProfile = Auth::user()->profile;
    }

    public function render()
    {
        return view('livewire.loan-application.pre-qualification', [
            'loanCategories' => LoanCategory::active()->ordered()->get(),
            'loanTypes' => LoanProduct::getLoanTypes(),
            'availableProducts' => $this->getFilteredProducts(),
        ]);
    }

    public function selectLoanCategory($categorySlugOrName)
    {
        // Accept either slug or name for backward compatibility
        $category = LoanCategory::where('slug', $categorySlugOrName)
            ->orWhere('name', $categorySlugOrName)
            ->first();
        
        if ($category) {
            $this->loan_category = $category->name; // Store category name for querying products
        } else {
            $this->loan_category = $categorySlugOrName; // Fallback for backward compatibility
        }
        $this->currentStep = 'criteria'; // Go directly to criteria after selecting category
    }

    public function toggleProfileData()
    {
        $this->use_profile_data = !$this->use_profile_data;
    }

    public function calculateEligibility()
    {
        \Log::info('PreQualification calculateEligibility - Before validation', [
            'monthly_income' => $this->monthly_income,
            'existing_loans' => $this->existing_loans,
            'requested_amount' => $this->requested_amount,
            'requested_tenure' => $this->requested_tenure,
            'loan_category' => $this->loan_category,
        ]);

        $this->validate([
            'loan_category' => 'required|string',
            'requested_amount' => 'required|numeric|min:1000',
            'requested_tenure' => 'required|integer|min:1|max:120',
            'monthly_income' => 'required|numeric|min:1',
            'existing_loans' => 'required|numeric|min:0',
        ], [
            'monthly_income.required' => 'Please enter your monthly income.',
            'monthly_income.min' => 'Monthly income must be at least TSh 1.',
            'monthly_income.numeric' => 'Monthly income must be a valid number.',
        ]);

        \Log::info('PreQualification calculateEligibility - After validation', [
            'monthly_income' => $this->monthly_income,
            'existing_loans' => $this->existing_loans,
        ]);

        // Ensure monthly_income is a positive number
        if (empty($this->monthly_income) || $this->monthly_income <= 0) {
            session()->flash('error', 'Please enter a valid monthly income (minimum TSh 1).');
            return;
        }

        // Find matching products with DSR calculation
        $this->findMatchingProducts();
        
        $this->currentStep = 'results';
    }

    private function calculateDSRForProduct($product): array
    {
        // Use the maximum interest rate for worst-case scenario calculation
        $annualRate = $product->interest_rate_max / 100;
        $monthlyRate = $annualRate / 12;
        $principal = $this->requested_amount;
        $numberOfPayments = $this->requested_tenure;
        
        // Calculate monthly payment using loan formula
        if ($monthlyRate > 0) {
            $monthlyPayment = $principal * 
                ($monthlyRate * pow(1 + $monthlyRate, $numberOfPayments)) / 
                (pow(1 + $monthlyRate, $numberOfPayments) - 1);
        } else {
            // If interest rate is 0, just divide principal by tenure
            $monthlyPayment = $principal / $numberOfPayments;
        }

        // Calculate DSR
        $totalMonthlyDebt = $this->existing_loans + $monthlyPayment;
        $dsr = ($totalMonthlyDebt / $this->monthly_income) * 100;

        return [
            'monthly_payment' => $monthlyPayment,
            'dsr' => $dsr,
            'eligible' => $dsr <= $product->minimum_dsr 
        ];
    }

    private function findMatchingProducts()
    {
        // Validate that loan category is selected
        if (empty($this->loan_category)) {
            $this->matching_products = [];
            return;
        }
        
        // Get category by name or slug (more precise matching)
        $category = LoanCategory::where(function($query) {
            $query->where('name', $this->loan_category)
                  ->orWhere('slug', $this->loan_category);
        })
        ->where('is_active', true)
        ->first();
        
        if (!$category) {
            // If category not found, try case-insensitive match
            $category = LoanCategory::whereRaw('LOWER(name) = ?', [strtolower($this->loan_category)])
                ->orWhereRaw('LOWER(slug) = ?', [strtolower($this->loan_category)])
                ->where('is_active', true)
                ->first();
        }
        
        // Start base query with relationships
        $productsQuery = LoanProduct::with(['lender', 'loanCategory'])
            ->where('is_active', true)
            ->where('status', '!=', 'deleted');
        
        // CRITICAL: Filter by loan category ID - this ensures only products matching the selected category are returned
        if ($category) {
            $productsQuery->where('loan_category_id', $category->id);
        } else {
            // If no category found, return empty results (category is required)
            $this->matching_products = [];
            session()->flash('error', 'Selected loan category not found. Please select a valid category.');
            return;
        }
        
        // Get all products that match basic criteria with improved filtering
        $products = $productsQuery
            // Amount range matching
            ->where('min_amount', '<=', $this->requested_amount)
            ->where('max_amount', '>=', $this->requested_amount)
            // Tenure range matching
            ->where('min_tenure_months', '<=', $this->requested_tenure)
            ->where('max_tenure_months', '>=', $this->requested_tenure)
            // Monthly income requirement (null means no minimum)
            ->where(function($query) {
                $query->whereNull('min_monthly_income')
                      ->orWhere('min_monthly_income', '<=', $this->monthly_income);
            })
            // Ensure lender is active
            ->whereHas('lender', function($query) {
                $query->where('status', 'approved');
            })
            ->get();

        $this->matching_products = $products->map(function ($product) {
            // Calculate DSR for this specific product
            $dsrData = $this->calculateDSRForProduct($product);
            
            // Create mock profile for additional eligibility checks if no real profile
            $profile = $this->userProfile ?? new UserProfile([
                'total_monthly_income' => $this->monthly_income,
                'existing_loan_payments' => $this->existing_loans,
                'date_of_birth' => now()->subYears(30), // Default age 30
            ]);

            // Get additional eligibility data from the product model
            $additionalEligibility = $product->checkEligibility($profile, $this->requested_amount, $this->requested_tenure);
            
            // Check individual criteria to identify why a product might not be eligible
            $eligibilityReasons = [];
            $basicEligible = true;

            // Amount check
            if ($this->requested_amount < $product->min_amount || $this->requested_amount > $product->max_amount) {
                $eligibilityReasons[] = "Requested amount (TSh " . number_format($this->requested_amount) . ") is outside the product range (TSh " . number_format($product->min_amount) . " - TSh " . number_format($product->max_amount) . ")";
                $basicEligible = false;
            }

            // Tenure check
            if ($this->requested_tenure < $product->min_tenure_months || $this->requested_tenure > $product->max_tenure_months) {
                $eligibilityReasons[] = "Requested tenure ({$this->requested_tenure} months) is outside the product range ({$product->min_tenure_months} - {$product->max_tenure_months} months)";
                $basicEligible = false;
            }

            // Income check
            if ($product->min_monthly_income && $this->monthly_income < $product->min_monthly_income) {
                $eligibilityReasons[] = "Monthly income (TSh " . number_format($this->monthly_income) . ") is below minimum required (TSh " . number_format($product->min_monthly_income) . ")";
                $basicEligible = false;
            }

            // DSR check
            if (!$dsrData['eligible']) {
                $eligibilityReasons[] = "DSR of " . number_format($dsrData['dsr'], 1) . "% exceeds maximum allowed " . $product->minimum_dsr . "%";
            }

            // Additional eligibility issues
            if (isset($additionalEligibility['issues']) && !empty($additionalEligibility['issues'])) {
                $eligibilityReasons = array_merge($eligibilityReasons, $additionalEligibility['issues']);
            }

            // Combine DSR eligibility with other eligibility factors
            $finalEligibility = $dsrData['eligible'] && $additionalEligibility['eligible'] && $basicEligible;
            
            // Calculate eligibility score with category matching bonus
            $score = 0;
            if ($finalEligibility) {
                $score += 50; // Base score for being eligible
                
                // BONUS: Category match ensures products are from the selected category (already filtered)
                // Additional verification that product category matches selection
                $categoryMatch = false;
                if ($product->loanCategory) {
                    $categoryMatch = ($product->loanCategory->name === $this->loan_category) || 
                                   ($product->loanCategory->slug === $this->loan_category);
                }
                if ($categoryMatch) {
                    $score += 10; // Bonus for exact category match
                }
                
                // Bonus points for lower DSR (closer to 0% gets more points, max 50% DSR gets full points)
                $dsrBonus = max(0, (50 - min(50, $dsrData['dsr']))) * 0.6;
                $score += $dsrBonus;
                
                // Bonus points for lower interest rate (max 30% rate, lower is better)
                $interestBonus = max(0, (30 - min(30, $product->interest_rate_max))) * 0.6;
                $score += $interestBonus;
                
                // Bonus points for faster processing (max 30 days, faster is better)
                $processingBonus = max(0, (30 - min(30, $product->approval_time_days))) * 0.4;
                $score += $processingBonus;
                
                // Bonus for faster disbursement
                $disbursementBonus = max(0, (15 - min(15, $product->disbursement_time_days))) * 0.2;
                $score += $disbursementBonus;
                
                // Bonus for lower processing fees
                $feePercentage = $product->processing_fee_percentage ?? 0;
                $feeBonus = max(0, (10 - min(10, $feePercentage))) * 0.2;
                $score += $feeBonus;
            } else {
                // Even for ineligible products, give partial score based on how close they are
                if ($dsrData['dsr'] <= ($product->minimum_dsr + 10)) {
                    $score += 20; // Close to eligible
                } elseif ($dsrData['dsr'] <= ($product->minimum_dsr + 20)) {
                    $score += 10; // Somewhat close
                }
            }
            
            return [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'lender_name' => $product->lender->company_name,
                'lender_id' => $product->lender->id,
                'loan_category' => $product->loanCategory ? $product->loanCategory->name : ($product->loan_category ?? ''),
                'loan_type' => $product->loan_type,
                'is_secured' => $product->loan_type === 'secured',
                'interest_rate_min' => $product->interest_rate_min,
                'interest_rate_max' => $product->interest_rate_max,
                'monthly_payment' => $dsrData['monthly_payment'],
                'dsr' => $dsrData['dsr'],
                'max_dsr_allowed' => $product->minimum_dsr,
                'processing_fee_percentage' => $product->processing_fee_percentage,
                'processing_fee_fixed' => $product->processing_fee_fixed,
                'approval_time_days' => $product->approval_time_days,
                'disbursement_time_days' => $product->disbursement_time_days,
                'eligible' => $finalEligibility,
                'eligibility_score' => min(100, max(0, $score)), // Cap at 100
                'eligibility_issues' => $eligibilityReasons ?? [],
                'eligibility_reasons' => $eligibilityReasons ?? [],
                'category_match' => $product->loanCategory ? (
                    ($product->loanCategory->name === $this->loan_category) || 
                    ($product->loanCategory->slug === $this->loan_category)
                ) : false,
                'category_id' => $product->loanCategory ? $product->loanCategory->id : null,
                'category_name' => $product->loanCategory ? $product->loanCategory->name : null,
                'collateral_required' => $product->loan_type === 'secured',
                'collateral_requirements' => $product->collateral_requirements,
                'features' => $product->features,
                'required_documents' => $product->required_documents,
                'description' => $product->description ?? '',
                'min_amount' => $product->min_amount,
                'max_amount' => $product->max_amount,
                'min_tenure_months' => $product->min_tenure_months,
                'max_tenure_months' => $product->max_tenure_months,
                'is_lender_selected' => in_array($product->lender->id, $this->selected_lenders),
            ];
        })
        // Don't filter - show all products, both eligible and ineligible
        ->sortByDesc(function ($product) {
            // Sort eligible products first, then by score
            return [$product['eligible'] ? 1 : 0, $product['eligibility_score']];
        })
        ->values()
        ->toArray();
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
                $products = $products->sortBy('interest_rate_max');
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

    public function selectProduct($productId)
    {
        // Find the product to get its lender ID
        $product = collect($this->matching_products)->firstWhere('product_id', $productId);
        
        if (!$product) {
            return;
        }

        $lenderId = $product['lender_id'];

        // Check if this product is already selected
        if (in_array($productId, $this->selected_products)) {
            // Deselect the product and remove lender from selected lenders
            $this->selected_products = array_filter($this->selected_products, fn($id) => $id !== $productId);
            $this->selected_lenders = array_filter($this->selected_lenders, fn($id) => $id !== $lenderId);
        } else {
            // Check if we already have a product selected from this lender
            $existingProductFromLender = collect($this->matching_products)
                ->where('lender_id', $lenderId)
                ->whereIn('product_id', $this->selected_products)
                ->first();

            if ($existingProductFromLender) {
                // Remove the existing product from this lender
                $this->selected_products = array_filter($this->selected_products, 
                    fn($id) => $id !== $existingProductFromLender['product_id']);
            }

            // Add the new product and lender
            $this->selected_products[] = $productId;
            if (!in_array($lenderId, $this->selected_lenders)) {
                $this->selected_lenders[] = $lenderId;
            }
        }

        // Update the matching products to reflect lender selection status
        $this->updateLenderSelectionStatus();
    }

    private function updateLenderSelectionStatus()
    {
        $this->matching_products = collect($this->matching_products)->map(function ($product) {
            $product['is_lender_selected'] = in_array($product['lender_id'], $this->selected_lenders);
            return $product;
        })->toArray();
    }

    public function selectAllEligible()
    {
        // Group products by lender and select the best product from each lender
        $bestProductsByLender = collect($this->matching_products)
            ->where('eligible', true)
            ->groupBy('lender_id')
            ->map(function ($lenderProducts) {
                // Get the product with highest eligibility score for each lender
                return $lenderProducts->sortByDesc('eligibility_score')->first();
            });

        $this->selected_products = $bestProductsByLender->pluck('product_id')->toArray();
        $this->selected_lenders = $bestProductsByLender->pluck('lender_id')->toArray();
        
        $this->updateLenderSelectionStatus();
    }

    public function clearSelection()
    {
        $this->selected_products = [];
        $this->selected_lenders = [];
        $this->updateLenderSelectionStatus();
    }

    public function showProductDetails($productId)
    {
        $this->selectedProductForDetails = collect($this->matching_products)
            ->firstWhere('product_id', $productId);
        $this->showProductDetails = true;
    }

    public function closeProductDetails()
    {
        $this->showProductDetails = false;
        $this->selectedProductForDetails = null;
    }

    public function proceedToApplication()
    {
        if (empty($this->selected_products)) {
            session()->flash('error', 'Please select at least one product to proceed.');
            return;
        }

        // Get selected product details
        $selectedProductDetails = collect($this->matching_products)
            ->whereIn('product_id', $this->selected_products)
            ->toArray();

        // Validate that we have the required income data
        if (empty($this->monthly_income) || $this->monthly_income <= 0) {
            session()->flash('error', 'Please enter your monthly income to proceed with the application.');
            return;
        }

        \Log::info('Storing prequalification data in session', [
            'monthly_income' => $this->monthly_income,
            'existing_loans' => $this->existing_loans,
            'requested_amount' => $this->requested_amount,
            'requested_tenure' => $this->requested_tenure,
        ]);

        // Store data in session and redirect to application
        session([
            'prequalification_data' => [
                'loan_category' => $this->loan_category,
                'loan_type' => $this->loan_type ?? 'unsecured', // Default loan type
                'requested_amount' => $this->requested_amount,
                'requested_tenure' => $this->requested_tenure,
                'monthly_income' => (float)$this->monthly_income,
                'existing_loans' => (float)($this->existing_loans ?? 0),
                'selected_products' => $this->selected_products,
                'selected_lenders' => $this->selected_lenders,
                'selected_product_details' => $selectedProductDetails,
                'matching_products' => $this->matching_products,
                'calculated_dsr' => $this->calculateAverageDSR($selectedProductDetails),
            ]
        ]);

        return redirect()->route('loan-application.completed');
    }

    private function calculateAverageDSR($selectedProducts)
    {
        if (empty($selectedProducts)) {
            return 0;
        }

        $totalDSR = collect($selectedProducts)->sum('dsr');
        return $totalDSR / count($selectedProducts);
    }

    public function backToCategory()
    {
        $this->currentStep = 'category';
        $this->reset(['loan_category', 'selected_products', 'selected_lenders', 'matching_products']);
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

    public function getLoanCategoryDescription($categorySlugOrName): string
    {
        // Try to get description from database first
        $category = LoanCategory::where('slug', $categorySlugOrName)
            ->orWhere('name', $categorySlugOrName)
            ->first();
        
        if ($category && $category->description) {
            return $category->description;
        }
        
        // Fallback to hardcoded descriptions for backward compatibility
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

        return $descriptions[$categorySlugOrName] ?? 'Loan for specific financial needs.';
    }

    public function getLoanTypeDescription($type): string
    {
        $descriptions = [
            'secured' => 'Requires collateral (property, vehicle, etc.) as security. Generally offers lower interest rates.',
            'unsecured' => 'No collateral required. Based on creditworthiness and income. Higher interest rates.',
        ];

        return $descriptions[$type] ?? '';
    }

    public function getSelectedLendersCount(): int
    {
        return count($this->selected_lenders);
    }
}