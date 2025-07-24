<?php

namespace App\Livewire\LoanProduct;

use App\Models\LoanProduct;
use Livewire\Component;
use Livewire\Attributes\Rule;

class LoanProductForm extends Component
{
    public $mode = 'create'; // create or edit
    public $productId = null;
    public $selectedProduct = null;
    public $currentStep = 1; // For multi-step form (1-4)
    
    // Basic Information (Step 1)
    #[Rule('required|string|max:255')]
    public $name = '';

    #[Rule('nullable|string')]
    public $description = '';

    #[Rule('nullable|string|max:50')]
    public $promotional_tag = '';

    #[Rule('required|string')]
    public $loan_type = '';

    // Amount and Tenure (Step 2)
    #[Rule('required|numeric|min:1000')]
    public $min_amount = 1000;

    #[Rule('required|numeric|gt:min_amount')]
    public $max_amount = 1000000;

    #[Rule('required|integer|min:1')]
    public $min_tenure_months = 1;

    #[Rule('required|integer|gte:min_tenure_months')]
    public $max_tenure_months = 60;

    #[Rule('required|numeric|min:0|max:100')]
    public $interest_rate_min = 5;

    #[Rule('required|numeric|gte:interest_rate_min|max:100')]
    public $interest_rate_max = 25;

    #[Rule('required|in:fixed,reducing,flat')]
    public $interest_type = 'reducing';

    #[Rule('required|integer|min:1|max:90')]
    public $approval_time_days = 7;

    #[Rule('required|integer|min:1|max:30')]
    public $disbursement_time_days = 3;

    #[Rule('required|integer|min:1|max:99')]
    public $minimum_dsr = 40;

    // Eligibility Criteria (Step 3)
    #[Rule('required|in:employed,unemployed,all')]
    public $employment_requirement = 'all';

    #[Rule('nullable|integer|min:1|max:120')]
    public $min_employment_months = null;

    #[Rule('required|integer|min:18|max:100')]
    public $min_age = 18;

    #[Rule('required|integer|gte:min_age|max:100')]
    public $max_age = 65;

    #[Rule('nullable|numeric|min:0')]
    public $min_monthly_income = null;

    #[Rule('nullable|numeric|min:0|max:100')]
    public $max_debt_to_income_ratio = null;

    #[Rule('nullable|integer|min:300|max:850')]
    public $min_credit_score = null;

    public $allow_bad_credit = false;
    public $business_sectors_allowed = [];

    // Fees and Requirements (Step 4)
    #[Rule('nullable|numeric|min:0|max:100')]
    public $processing_fee_percentage = 0;

    #[Rule('nullable|numeric|min:0')]
    public $processing_fee_fixed = 0;

    #[Rule('nullable|numeric|min:0')]
    public $late_payment_fee = 0;

    #[Rule('nullable|numeric|min:0|max:100')]
    public $early_repayment_fee_percentage = 0;

    public $requires_collateral = false;
    public $collateral_types = [];
    public $requires_guarantor = false;

    #[Rule('nullable|integer|min:0|max:5')]
    public $min_guarantors = 0;

    public $required_documents = [];
    public $disbursement_methods = [];
    public $auto_approval_eligible = false;

    #[Rule('nullable|numeric|min:0')]
    public $auto_approval_max_amount = null;

    #[Rule('nullable|string')]
    public $terms_and_conditions = '';

    #[Rule('nullable|string')]
    public $eligibility_criteria = '';

    public $key_features = [];
    public $is_active = true;

    // Temporary arrays for UI
    public $newKeyFeature = '';
    public $selectedDocuments = [];
    public $selectedCollateralTypes = [];
    public $selectedDisbursementMethods = [];
    public $selectedBusinessSectors = [];

    public function mount($productId = null)
    {
        if ($productId) {
            $this->mode = 'edit';
            $this->productId = $productId;
            $this->selectedProduct = LoanProduct::findOrFail($productId);
            $this->loadProductData();
        }

        // Initialize defaults
        $this->selectedDisbursementMethods = $this->selectedDisbursementMethods ?: ['bank_transfer'];
        $this->selectedDocuments = $this->selectedDocuments ?: ['national_id', 'salary_slip', 'bank_statement'];
        $this->required_documents = $this->selectedDocuments;
        $this->disbursement_methods = $this->selectedDisbursementMethods;
    }

    public function render()
    {

        return view('livewire.Loan-product.loan-product-form', [
            'documentTypes' => LoanProduct::getAvailableDocumentTypes(),
            'collateralTypes' => LoanProduct::getAvailableCollateralTypes(),
            'businessSectors' => LoanProduct::getAvailableBusinessSectors(),
        ]);
    }

    // Navigation methods
    public function nextStep()
    {
        $this->validateCurrentStep();
        
        if ($this->currentStep < 4) {
            $this->currentStep++;
        }
    }

    public function previousStep()
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
        }
    }

    public function goToStep($step)
    {
        if ($step >= 1 && $step <= 4) {
            // Validate all previous steps
            for ($i = 1; $i < $step; $i++) {
                $this->validateStep($i);
            }
            $this->currentStep = $step;
        }
    }

    // Validation for each step
    private function validateCurrentStep()
    {
        $this->validateStep($this->currentStep);
    }

    private function validateStep($step)
    {
        switch ($step) {
            case 1:
                $this->validate([
                    'name' => 'required|string',
                    'description' => 'nullable|string',
                    'promotional_tag' => 'nullable|string',
                    'loan_type' => 'required|string'
                ]);
                break;
            case 2:
                $this->validate([
                    'min_amount' => 'required|numeric',
                    'max_amount' => 'required|numeric',
                    'min_tenure_months' => 'required|integer',
                    'max_tenure_months' => 'required|integer',
                    'interest_rate_min' => 'required|numeric',
                    'interest_rate_max' => 'required|numeric',
                    'interest_type' => 'required|string',
                    'approval_time_days' => 'required|integer',
                    'disbursement_time_days' => 'required|integer',
                    'minimum_dsr' => 'required|integer',
                ]);
                break;
            case 3:
                $this->validate([
                    'employment_requirement' => 'required|string',
                    'min_age' => 'required|integer',
                    'max_age' => 'required|integer',
                ]);
                break;
            case 4:
                $this->validate([
                    'processing_fee_percentage' => 'nullable|numeric',
                    'processing_fee_fixed' => 'nullable|numeric',
                    'late_payment_fee' => 'nullable|numeric',
                    'early_repayment_fee_percentage' => 'nullable|numeric',
                ]);
                break;
        }
    }

    // Save product
    public function saveProduct()
    {
        $lender = auth()->user()->lender;
        if (!$lender) {
            session()->flash('error', 'You must be associated with a lender to create products.');
            return;
        }

        $data = $this->getProductData();
        $data['lender_id'] = $lender->id;
        
        if ($this->mode === 'edit' && $this->selectedProduct) {
            $this->selectedProduct->update($data);
            session()->flash('message', 'Loan product updated successfully!');
        } else {
            LoanProduct::create($data);
            session()->flash('message', 'Loan product created successfully!');
        }

        return redirect()->route('loan.product.index');
    }

    public function cancel()
    {
        return redirect()->route('loan.product.index');
    }

    // Key features management
    public function addKeyFeature()
    {
        if (!empty($this->newKeyFeature)) {
            $this->key_features[] = $this->newKeyFeature;
            $this->newKeyFeature = '';
        }
    }

    public function removeKeyFeature($index)
    {
        unset($this->key_features[$index]);
        $this->key_features = array_values($this->key_features);
    }

    // Document selection
    public function toggleDocument($document)
    {
        if (in_array($document, $this->selectedDocuments)) {
            $this->selectedDocuments = array_filter($this->selectedDocuments, fn($doc) => $doc !== $document);
        } else {
            $this->selectedDocuments[] = $document;
        }
        $this->required_documents = $this->selectedDocuments;
    }

    // Collateral types selection
    public function toggleCollateralType($type)
    {
        if (in_array($type, $this->selectedCollateralTypes)) {
            $this->selectedCollateralTypes = array_filter($this->selectedCollateralTypes, fn($t) => $t !== $type);
        } else {
            $this->selectedCollateralTypes[] = $type;
        }
        $this->collateral_types = $this->selectedCollateralTypes;
    }

    // Disbursement methods selection
    public function toggleDisbursementMethod($method)
    {
        if (in_array($method, $this->selectedDisbursementMethods)) {
            $this->selectedDisbursementMethods = array_filter($this->selectedDisbursementMethods, fn($m) => $m !== $method);
        } else {
            $this->selectedDisbursementMethods[] = $method;
        }
        $this->disbursement_methods = $this->selectedDisbursementMethods;
    }

    // Business sectors selection
    public function toggleBusinessSector($sector)
    {
        if (in_array($sector, $this->selectedBusinessSectors)) {
            $this->selectedBusinessSectors = array_filter($this->selectedBusinessSectors, fn($s) => $s !== $sector);
        } else {
            $this->selectedBusinessSectors[] = $sector;
        }
        $this->business_sectors_allowed = $this->selectedBusinessSectors;
    }

    private function loadProductData()
    {
        if (!$this->selectedProduct) return;

        $product = $this->selectedProduct;
        
        // Basic Info
        $this->name = $product->name;
        $this->description = $product->description;
        $this->promotional_tag = $product->promotional_tag;
        $this->loan_type = $product->loan_type ?? 'personal';
        
        // Amount & Terms
        $this->min_amount = $product->min_amount;
        $this->max_amount = $product->max_amount;
        $this->min_tenure_months = $product->min_tenure_months;
        $this->max_tenure_months = $product->max_tenure_months;
        $this->interest_rate_min = $product->interest_rate_min;
        $this->interest_rate_max = $product->interest_rate_max;
        $this->interest_type = $product->interest_type;
        $this->approval_time_days = $product->approval_time_days;
        $this->disbursement_time_days = $product->disbursement_time_days;
        $this->minimum_dsr = $product->minimum_dsr;
        
        // Eligibility
        $this->employment_requirement = $product->employment_requirement;
        $this->min_employment_months = $product->min_employment_months;
        $this->min_age = $product->min_age;
        $this->max_age = $product->max_age;
        $this->min_monthly_income = $product->min_monthly_income;
        $this->max_debt_to_income_ratio = $product->max_debt_to_income_ratio;
        $this->min_credit_score = $product->min_credit_score;
        $this->allow_bad_credit = $product->allow_bad_credit;
        
        // Fees & Requirements
        $this->processing_fee_percentage = $product->processing_fee_percentage;
        $this->processing_fee_fixed = $product->processing_fee_fixed;
        $this->late_payment_fee = $product->late_payment_fee;
        $this->early_repayment_fee_percentage = $product->early_repayment_fee_percentage;
        $this->requires_collateral = $product->requires_collateral;
        $this->requires_guarantor = $product->requires_guarantor;
        $this->min_guarantors = $product->min_guarantors;
        $this->auto_approval_eligible = $product->auto_approval_eligible;
        $this->auto_approval_max_amount = $product->auto_approval_max_amount;
        $this->terms_and_conditions = $product->terms_and_conditions;
        $this->eligibility_criteria = $product->eligibility_criteria;
        $this->is_active = $product->is_active;

        // Load arrays
        $this->key_features = $product->key_features ?? [];
        $this->selectedDocuments = $product->required_documents ?? [];
        $this->selectedCollateralTypes = $product->collateral_types ?? [];
        $this->selectedDisbursementMethods = $product->disbursement_methods ?? [];
        $this->selectedBusinessSectors = $product->business_sectors_allowed ?? [];

        // Sync with form fields
        $this->required_documents = $this->selectedDocuments;
        $this->collateral_types = $this->selectedCollateralTypes;
        $this->disbursement_methods = $this->selectedDisbursementMethods;
        $this->business_sectors_allowed = $this->selectedBusinessSectors;
    }

    private function getProductData(): array
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
            'promotional_tag' => $this->promotional_tag,
            'loan_type' => $this->loan_type,
            'min_amount' => $this->min_amount,
            'max_amount' => $this->max_amount,
            'min_tenure_months' => $this->min_tenure_months,
            'max_tenure_months' => $this->max_tenure_months,
            'interest_rate_min' => $this->interest_rate_min,
            'interest_rate_max' => $this->interest_rate_max,
            'interest_type' => $this->interest_type,
            'employment_requirement' => $this->employment_requirement,
            'min_employment_months' => $this->min_employment_months,
            'min_age' => $this->min_age,
            'max_age' => $this->max_age,
            'min_monthly_income' => $this->min_monthly_income,
            'max_debt_to_income_ratio' => $this->max_debt_to_income_ratio,
            'min_credit_score' => $this->min_credit_score,
            'allow_bad_credit' => $this->allow_bad_credit,
            'processing_fee_percentage' => $this->processing_fee_percentage,
            'processing_fee_fixed' => $this->processing_fee_fixed,
            'late_payment_fee' => $this->late_payment_fee,
            'early_repayment_fee_percentage' => $this->early_repayment_fee_percentage,
            'requires_collateral' => $this->requires_collateral,
            'collateral_types' => $this->selectedCollateralTypes,
            'requires_guarantor' => $this->requires_guarantor,
            'min_guarantors' => $this->min_guarantors,
            'required_documents' => $this->selectedDocuments,
            'approval_time_days' => $this->approval_time_days,
            'disbursement_time_days' => $this->disbursement_time_days,
            'disbursement_methods' => $this->selectedDisbursementMethods,
            'auto_approval_eligible' => $this->auto_approval_eligible,
            'auto_approval_max_amount' => $this->auto_approval_max_amount,
            'terms_and_conditions' => $this->terms_and_conditions,
            'eligibility_criteria' => $this->eligibility_criteria,
            'business_sectors_allowed' => $this->selectedBusinessSectors,
            'key_features' => $this->key_features,
            'is_active' => $this->is_active,
            'minimum_dsr' => $this->minimum_dsr,
        ];
    }

    // Real-time validation updates
    public function updated($propertyName)
    {
        // Auto-update related fields
        if ($propertyName === 'requires_guarantor' && !$this->requires_guarantor) {
            $this->min_guarantors = 0;
        }

        if ($propertyName === 'auto_approval_eligible' && !$this->auto_approval_eligible) {
            $this->auto_approval_max_amount = null;
        }

        if ($propertyName === 'employment_requirement' && $this->employment_requirement !== 'employed') {
            $this->min_employment_months = null;
        }
    }
}