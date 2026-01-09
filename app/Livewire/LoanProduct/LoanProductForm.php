<?php

namespace App\Livewire\LoanProduct;

use App\Models\LoanProduct;
use App\Models\LoanCategory;
use Livewire\Component;
use Livewire\Attributes\Rule;
use Illuminate\Support\Facades\Log;
use App\Services\LogService;

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

    #[Rule('required|in:secured,unsecured')]
    public $loan_type = '';

    // Amount and Tenure (Step 2)
    #[Rule('required|numeric|min:1000|max:999999999999')]
    public $min_amount = 1000;

    #[Rule('required|numeric|gt:min_amount|max:999999999999')]
    public $max_amount = 1000000;

    #[Rule('required|integer|min:1')]
    public $min_tenure_months = 1;

    #[Rule('required|integer|gte:min_tenure_months')]
    public $max_tenure_months = 60;

    #[Rule('required|numeric|min:0|max:100')]
    public $interest_rate_min = 5;

    #[Rule('required|numeric|gte:interest_rate_min|max:100')]
    public $interest_rate_max = 25;

    #[Rule('required|in:fixed,reducing')]
    public $interest_type = 'reducing';

    #[Rule('required|integer|min:1|max:90')]
    public $approval_time_days = 7;

    #[Rule('required|integer|min:1|max:30')]
    public $disbursement_time_days = 3;

    #[Rule('required|integer|min:1|max:99')]
    public $minimum_dsr = 40;

    // Eligibility Criteria (Step 3)
    #[Rule('required|in:employed,business,all')]
    public $employment_requirement = 'all';

    #[Rule('nullable|integer|min:1|max:120')]
    public $min_employment_months = null;

    #[Rule('required|integer|min:18|max:100')]
    public $min_age = 18;

    #[Rule('required|uuid|exists:loan_categories,id')]
    public $loan_category_id;

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
        $user = auth()->user();
        
        // Check if user is a lender
        if (!$user->isLender() && !$user->hasRole('lender')) {
            abort(403, 'Only lenders can access loan products.');
        }
        
        // Check if user has a lender association
        if (!$user->lender) {
            abort(403, 'You must be associated with a lender to access loan products.');
        }
        
        if ($productId) {
            $this->mode = 'edit';
            $this->productId = $productId;
            
            // Check if the product belongs to the user's lender
            $this->selectedProduct = LoanProduct::where('id', $productId)
                ->where('lender_id', $user->lender->id)
                ->where('status', '!=', 'deleted')
                ->firstOrFail();
            
            // For edit mode, check if lender has products (at least one product exists)
            // Allow creating first product, but require products for editing
            if ($this->mode === 'edit') {
                $hasProducts = LoanProduct::where('lender_id', $user->lender->id)
                    ->where('status', '!=', 'deleted')
                    ->exists();
                
                if (!$hasProducts) {
                    abort(403, 'You must have at least one product to perform this operation.');
                }
            }
            
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
        $loanCategories = LoanCategory::active()->ordered()->get();

        return view('livewire.Loan-product.loan-product-form', [
            'loanCategories' => $loanCategories,
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
                    'name' => 'required|string|max:255|regex:/^[a-zA-Z0-9\s\-\']+$/',
                    'description' => 'nullable|string|max:5000',
                    'promotional_tag' => 'nullable|string|max:50|regex:/^[a-zA-Z0-9\s\-,!]+$/',
                    'loan_type' => 'required|in:secured,unsecured',
                    'loan_category_id' => 'required|uuid|exists:loan_categories,id',
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
        $user = auth()->user();
        
        // Check if user is a lender
        if (!$user->isLender() && !$user->hasRole('lender')) {
            session()->flash('error', 'Only lenders can create/edit loan products.');
            return;
        }
        
        $lender = $user->lender;
        if (!$lender) {
            session()->flash('error', 'You must be associated with a lender to create products.');
            return;
        }
        
        // For edit mode, check if lender has products and verify ownership
        if ($this->mode === 'edit') {
            // Verify the product belongs to this lender
            if ($this->selectedProduct && $this->selectedProduct->lender_id !== $lender->id) {
                session()->flash('error', 'You do not have permission to edit this product.');
                return;
            }
            
            // Check if lender has products (at least one product exists) for edit operations
            $hasProducts = LoanProduct::where('lender_id', $lender->id)
                ->where('status', '!=', 'deleted')
                ->exists();
            
            if (!$hasProducts) {
                session()->flash('error', 'You must have at least one product to perform this operation.');
                return;
            }
        }

        // Validate all steps before saving
        for ($i = 1; $i <= 4; $i++) {
            try {
                $this->validateStep($i);
            } catch (\Illuminate\Validation\ValidationException $e) {
                session()->flash('error', 'Please complete all required fields correctly.');
                return;
            }
        }

        // Sanitize all inputs one final time
        $this->sanitizeAllInputs();

        $data = $this->getProductData();
        $data['lender_id'] = $lender->id;
        $data['updated_by'] = auth()->id();
        
        try {
            if ($this->mode === 'edit' && $this->selectedProduct) {
                $oldValues = $this->selectedProduct->toArray();
                $this->selectedProduct->update($data);
                $this->selectedProduct->refresh();
                
                // Log the update
                if (class_exists(LogService::class)) {
                    LogService::logLoanProductUpdated(
                        $this->selectedProduct,
                        $oldValues,
                        $this->selectedProduct->toArray()
                    );
                }
                
                session()->flash('message', 'Loan product updated successfully!');
            } else {
                $data['created_by'] = auth()->id();
                $product = LoanProduct::create($data);
                
                // Log the creation
                if (class_exists(LogService::class)) {
                    LogService::logLoanProductCreated($product);
                }
                
                session()->flash('message', 'Loan product created successfully!');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred while saving the product. Please try again.');
            \Log::error('Loan product save error: ' . $e->getMessage());
            return;
        }

        return redirect()->route('loan.product.index');
    }

    /**
     * Sanitize all inputs before saving
     */
    private function sanitizeAllInputs()
    {
        // Sanitize all string properties
        $stringProperties = [
            'name', 'description', 'promotional_tag', 'loan_type', 
            'interest_type', 'employment_requirement',
            'terms_and_conditions', 'eligibility_criteria', 'newKeyFeature'
        ];

        foreach ($stringProperties as $property) {
            if (isset($this->$property) && is_string($this->$property)) {
                $this->$property = trim(strip_tags($this->$property));
            }
        }

        // Sanitize arrays
        $arrayProperties = [
            'key_features', 'selectedDocuments', 'selectedCollateralTypes',
            'selectedDisbursementMethods', 'selectedBusinessSectors'
        ];

        foreach ($arrayProperties as $property) {
            if (isset($this->$property) && is_array($this->$property)) {
                $this->$property = array_map(function($item) {
                    return is_string($item) ? trim(strip_tags($item)) : $item;
                }, array_filter($this->$property));
            }
        }
    }

    public function cancel()
    {
        return redirect()->route('loan.product.index');
    }

    // Key features management
    public function addKeyFeature()
    {
        if (!empty($this->newKeyFeature)) {
            // Sanitize the input before adding
            $feature = trim(strip_tags($this->newKeyFeature));
            if (!empty($feature) && strlen($feature) <= 255) {
                $this->key_features[] = $feature;
                $this->newKeyFeature = '';
            }
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
        // Sanitize document value
        $document = trim(strip_tags((string)$document));
        
        // Validate against allowed document types
        $allowedDocuments = array_keys(LoanProduct::getAvailableDocumentTypes());
        if (!in_array($document, $allowedDocuments)) {
            return; // Invalid document type, ignore
        }
        
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
        // Sanitize type value
        $type = trim(strip_tags((string)$type));
        
        // Validate against allowed collateral types
        $allowedTypes = array_keys(LoanProduct::getAvailableCollateralTypes());
        if (!in_array($type, $allowedTypes)) {
            return; // Invalid type, ignore
        }
        
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
        // Sanitize method value
        $method = trim(strip_tags((string)$method));
        
        // Validate against allowed disbursement methods
        $allowedMethods = ['bank_transfer', 'mobile_money', 'cash'];
        if (!in_array($method, $allowedMethods)) {
            return; // Invalid method, ignore
        }
        
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
        // Sanitize sector value
        $sector = trim(strip_tags((string)$sector));
        
        // Validate against allowed business sectors
        $allowedSectors = array_keys(LoanProduct::getAvailableBusinessSectors());
        if (!in_array($sector, $allowedSectors)) {
            return; // Invalid sector, ignore
        }
        
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
        $this->loan_type = $product->loan_type ?? 'secured';
        $this->loan_category_id = $product->loan_category_id;
        
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
        // If status exists and is deleted, don't allow editing
        if (isset($product->status) && $product->status === 'deleted') {
            session()->flash('error', 'Cannot edit a deleted product.');
            return redirect()->route('loan.product.index');
        }

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
        // Final sanitization before saving
        return [
            'name' => trim(strip_tags($this->name ?? '')),
            'description' => $this->description ? trim(strip_tags($this->description)) : null,
            'promotional_tag' => $this->promotional_tag ? trim(strip_tags($this->promotional_tag)) : null,
            'loan_type' => $this->loan_type,
            'loan_category_id' => $this->loan_category_id,
            'min_amount' => (float) ($this->min_amount ?? 0),
            'max_amount' => (float) ($this->max_amount ?? 0),
            'min_tenure_months' => (int) ($this->min_tenure_months ?? 1),
            'max_tenure_months' => (int) ($this->max_tenure_months ?? 1),
            'interest_rate_min' => (float) ($this->interest_rate_min ?? 0),
            'interest_rate_max' => (float) ($this->interest_rate_max ?? 0),
            'interest_type' => $this->interest_type,
            'employment_requirement' => $this->employment_requirement,
            'min_employment_months' => $this->min_employment_months ? (int) $this->min_employment_months : null,
            'min_age' => (int) ($this->min_age ?? 18),
            'max_age' => (int) ($this->max_age ?? 65),
            'min_monthly_income' => $this->min_monthly_income ? (float) $this->min_monthly_income : null,
            'max_debt_to_income_ratio' => $this->max_debt_to_income_ratio ? (float) $this->max_debt_to_income_ratio : null,
            'min_credit_score' => $this->min_credit_score ? (int) $this->min_credit_score : null,
            'allow_bad_credit' => false, // Always set to false as field is removed
            'processing_fee_percentage' => (float) ($this->processing_fee_percentage ?? 0),
            'processing_fee_fixed' => (float) ($this->processing_fee_fixed ?? 0),
            'late_payment_fee' => (float) ($this->late_payment_fee ?? 0),
            'early_repayment_fee_percentage' => (float) ($this->early_repayment_fee_percentage ?? 0),
            'requires_collateral' => (bool) $this->requires_collateral,
            'collateral_types' => array_values(array_filter($this->selectedCollateralTypes ?? [])),
            'requires_guarantor' => (bool) $this->requires_guarantor,
            'min_guarantors' => (int) ($this->min_guarantors ?? 0),
            'required_documents' => array_values(array_filter($this->selectedDocuments ?? [])),
            'approval_time_days' => (int) ($this->approval_time_days ?? 7),
            'disbursement_time_days' => (int) ($this->disbursement_time_days ?? 3),
            'disbursement_methods' => array_values(array_filter($this->selectedDisbursementMethods ?? [])),
            'auto_approval_eligible' => (bool) $this->auto_approval_eligible,
            'auto_approval_max_amount' => $this->auto_approval_max_amount ? (float) $this->auto_approval_max_amount : null,
            'terms_and_conditions' => $this->terms_and_conditions ? trim(strip_tags($this->terms_and_conditions)) : null,
            'eligibility_criteria' => $this->eligibility_criteria ? trim(strip_tags($this->eligibility_criteria)) : null,
            'business_sectors_allowed' => array_values(array_filter($this->selectedBusinessSectors ?? [])),
            'key_features' => array_map(function($feature) {
                return trim(strip_tags((string)$feature));
            }, array_filter($this->key_features ?? [])),
            'is_active' => (bool) $this->is_active,
            'status' => $this->is_active ? 'active' : 'inactive',
            'minimum_dsr' => (int) ($this->minimum_dsr ?? 0),
        ];
    }

    // Real-time validation updates and sanitization
    public function updated($propertyName)
    {
        // Sanitize string inputs
        $this->sanitizeInput($propertyName);

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

    /**
     * Sanitize input based on property name
     */
    private function sanitizeInput($propertyName)
    {
        // Sanitize string fields
        $stringFields = [
            'name', 'description', 'promotional_tag', 'loan_type', 
            'interest_type', 'employment_requirement',
            'terms_and_conditions', 'eligibility_criteria', 'newKeyFeature'
        ];

        if (in_array($propertyName, $stringFields) && is_string($this->$propertyName)) {
            // Remove HTML tags and trim whitespace
            $this->$propertyName = trim(strip_tags($this->$propertyName));
            
            // Additional sanitization for specific fields
            if ($propertyName === 'name') {
                // Remove special characters except spaces, hyphens, and apostrophes
                $this->$propertyName = preg_replace('/[^a-zA-Z0-9\s\-\']/', '', $this->$propertyName);
            }
            
            if ($propertyName === 'promotional_tag') {
                // Only allow alphanumeric, spaces, and common punctuation
                $this->$propertyName = preg_replace('/[^a-zA-Z0-9\s\-,!]/', '', $this->$propertyName);
            }
            
            if (in_array($propertyName, ['loan_type', 'interest_type', 'employment_requirement'])) {
                // For enum fields, ensure only valid values
                $validValues = [
                    'loan_type' => ['secured', 'unsecured'],
                    'interest_type' => ['fixed', 'reducing'],
                    'employment_requirement' => ['employed', 'business', 'all']
                ];
                
                if (isset($validValues[$propertyName]) && !in_array($this->$propertyName, $validValues[$propertyName])) {
                    $this->$propertyName = $validValues[$propertyName][0] ?? '';
                }
            }
            
            if ($propertyName === 'loan_category_id') {
                // Validate that the category ID exists and is active
                $category = LoanCategory::active()->find($this->loan_category_id);
                if (!$category) {
                    // Reset to first active category if invalid
                    $firstCategory = LoanCategory::active()->ordered()->first();
                    $this->loan_category_id = $firstCategory ? $firstCategory->id : null;
                }
            }
        }

        // Sanitize numeric fields - ensure they're numeric
        $numericFields = [
            'min_amount', 'max_amount', 'min_tenure_months', 'max_tenure_months',
            'interest_rate_min', 'interest_rate_max', 'approval_time_days',
            'disbursement_time_days', 'minimum_dsr', 'min_employment_months',
            'min_age', 'max_age', 'min_monthly_income', 'max_debt_to_income_ratio',
            'min_credit_score', 'processing_fee_percentage', 'processing_fee_fixed',
            'late_payment_fee', 'early_repayment_fee_percentage', 'min_guarantors',
            'auto_approval_max_amount'
        ];

        if (in_array($propertyName, $numericFields) && $this->$propertyName !== null) {
            // Remove any non-numeric characters except decimal point
            if (is_string($this->$propertyName)) {
                $this->$propertyName = preg_replace('/[^0-9.]/', '', $this->$propertyName);
            }
        }

        // Sanitize boolean fields
        $booleanFields = ['is_active', 'requires_collateral', 'requires_guarantor', 
                         'auto_approval_eligible', 'allow_bad_credit'];
        
        if (in_array($propertyName, $booleanFields)) {
            $this->$propertyName = (bool) $this->$propertyName;
        }

        // Sanitize array fields
        $arrayFields = ['key_features', 'selectedDocuments', 'selectedCollateralTypes',
                        'selectedDisbursementMethods', 'selectedBusinessSectors'];
        
        if (in_array($propertyName, $arrayFields) && is_array($this->$propertyName)) {
            $this->$propertyName = array_map(function($item) {
                if (is_string($item)) {
                    return trim(strip_tags($item));
                }
                return $item;
            }, $this->$propertyName);
            
            // Remove empty values
            $this->$propertyName = array_filter($this->$propertyName);
        }
    }
}