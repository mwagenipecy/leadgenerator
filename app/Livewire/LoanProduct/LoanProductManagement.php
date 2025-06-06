<?php

namespace App\Livewire\LoanProduct;

use App\Models\LoanProduct;
use App\Models\Lender;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Rule;

class LoanProductManagement extends Component
{
    use WithPagination;

    // Component state
    public $currentStep = 'list'; // list, create, edit, view
    public $selectedProduct = null;
    public $currentEditStep = 2; // For multi-step form (1-4)
    
    // Search and filters
    public $search = '';
    public $statusFilter = 'all';
    public $employmentFilter = 'all';

    // Basic Information (Step 1)
    #[Rule('required|string|max:255')]
    public $name = '';

    #[Rule('nullable|string')]
    public $description = '';

    #[Rule('nullable|string|max:50')]
    public $promotional_tag = '';

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

    #[Rule('required|integer|min:1|max:90')]
    public $approval_time_days = 7;

    #[Rule('required|integer|min:1|max:30')]
    public $disbursement_time_days = 3;


    #[Rule('required|string')]
    public $loan_type;


    #[Rule('required|integer|min:1|max:99')]
    public $minimum_dsr;

    public $disbursement_methods = [];
    public $auto_approval_eligible = false;

    #[Rule('nullable|numeric|min:0')]
    public $auto_approval_max_amount = null;

    #[Rule('nullable|string')]
    public $terms_and_conditions = '';

    #[Rule('nullable|string')]
    public $eligibility_criteria = '';

    public $business_sectors_allowed = [];
    public $key_features = [];
    public $is_active = true;

    // Temporary arrays for UI
    public $newKeyFeature = '';
    public $selectedDocuments = [];
    public $selectedCollateralTypes = [];
    public $selectedDisbursementMethods = [];
    public $selectedBusinessSectors = [];

    protected $paginationTheme = 'bootstrap';

    public function mount()
    {
        // Initialize with default disbursement methods
        $this->selectedDisbursementMethods = ['bank_transfer'];
        $this->selectedDocuments = ['national_id', 'salary_slip', 'bank_statement'];
    }

    public function render()
    {
        $products = [];
        $stats = [];

        if ($this->currentStep === 'list') {
            // Get lender ID from authenticated user
            $lenderId = 1 ; //auth()->user()->lender?->id;
            
            if ($lenderId) {
                $products = LoanProduct::where('lender_id', $lenderId)
                    ->when($this->search, function ($query) {
                        $query->where(function ($q) {
                            $q->where('name', 'like', '%' . $this->search . '%')
                              ->orWhere('product_code', 'like', '%' . $this->search . '%')
                              ->orWhere('description', 'like', '%' . $this->search . '%');
                        });
                    })
                    ->when($this->statusFilter !== 'all', function ($query) {
                        $query->where('is_active', $this->statusFilter === 'active');
                    })
                    ->when($this->employmentFilter !== 'all', function ($query) {
                        $query->where('employment_requirement', $this->employmentFilter);
                    })
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);

                $stats = [
                    'total' => LoanProduct::where('lender_id', $lenderId)->count(),
                    'active' => LoanProduct::where('lender_id', $lenderId)->active()->count(),
                    'inactive' => LoanProduct::where('lender_id', $lenderId)->where('is_active', false)->count(),
                ];
            }
        }

        return view('livewire.loan-product.loan-product-management', [
            'products' => $products,
            'stats' => $stats,
            'documentTypes' => LoanProduct::getAvailableDocumentTypes(),
            'collateralTypes' => LoanProduct::getAvailableCollateralTypes(),
            'businessSectors' => LoanProduct::getAvailableBusinessSectors(),
        ]);
    }

    // Navigation methods
    public function showCreateForm()
    {
        $this->resetForm();
        $this->currentStep = 'create';
        $this->currentEditStep = 1;
    }

    public function backToList()
    {
        $this->currentStep = 'list';
        $this->resetForm();
        $this->resetPage();
    }

    public function editProduct($id)
    {
        $this->selectedProduct = LoanProduct::findOrFail($id);
        $this->loadProductData();
        $this->currentStep = 'edit';
        $this->currentEditStep = 1;
    }

    public function viewProduct($id)
    {
        $this->selectedProduct = LoanProduct::findOrFail($id);
        $this->currentStep = 'view';
    }

    // Multi-step form navigation
    public function nextStep()
    {
        $this->validateCurrentStep();
        
        if ($this->currentEditStep < 4) {
            $this->currentEditStep++;
        }
    }

    public function previousStep()
    {
        if ($this->currentEditStep > 1) {
            $this->currentEditStep--;
        }
    }

    public function goToStep($step)
    {
        if ($step >= 1 && $step <= 4) {
            // Validate all previous steps
            for ($i = 1; $i < $step; $i++) {
                $this->validateStep($i);
            }
            $this->currentEditStep = $step;
        }
    }

    // Validation for each step
    private function validateCurrentStep()
    {
        $this->validateStep($this->currentEditStep);
    }

    private function validateStep($step)
    {
        switch ($step) {
            case 1:
                $this->validate([
                    'name' => 'required|string',
                    'description' => 'nullable|string',
                    'promotional_tag' => 'nullable|string',
                    'loan_type'=>'required|string'
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
                ]);
                break;
            case 3:
                $this->validate([
                    'employment_requirement' => 'required|string',
                    'min_employment_months' => 'required|integer',
                    'min_age' => 'required|integer',
                    'max_age' => 'required|integer',
                    'min_monthly_income' => 'required|numeric',
                    'max_debt_to_income_ratio' => 'required|numeric',
                    'min_credit_score' => 'required|integer',
                ]);
                break;
            case 4:
                $this->validate([
                    'processing_fee_percentage' => 'nullable|numeric',
                    'processing_fee_fixed' => 'nullable|numeric',
                    'late_payment_fee' => 'nullable|numeric',
                    'early_repayment_fee_percentage' => 'nullable|numeric',
                    'min_guarantors' => 'required|integer',
                    'approval_time_days' => 'required|integer',
                    'disbursement_time_days' => 'required|integer',
                    'auto_approval_max_amount' => 'nullable|numeric',
                ]);
                break;
        }
    }

    

    // Save product
    public function saveProduct()
    {
        // Validate all steps
      //  $this->validate();

        $lender = auth()->user()->lender;
        if (!$lender) {
            session()->flash('error', 'You must be associated with a lender to create products.');
            return;
        }

        $data = $this->getProductData();
        $data['lender_id'] = $lender->id;

        if ($this->currentStep === 'edit' && $this->selectedProduct) {
            $this->selectedProduct->update($data);
            session()->flash('message', 'Loan product updated successfully!');
        } else {
            LoanProduct::create($data);
            session()->flash('message', 'Loan product created successfully!');
        }

        $this->backToList();
    }

    // Toggle product status
    public function toggleProductStatus($id)
    {
        $product = LoanProduct::findOrFail($id);
        $product->update(['is_active' => !$product->is_active]);
        
        $status = $product->is_active ? 'activated' : 'deactivated';
        session()->flash('message', "Product {$status} successfully!");
    }

    // Delete product
    public function deleteProduct($id)
    {
        $product = LoanProduct::findOrFail($id);
        $product->delete();
        session()->flash('message', 'Product deleted successfully!');
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

    // Helper methods
    private function resetForm()
    {
        $this->reset([
            'name', 'description', 'promotional_tag', 'min_amount', 'max_amount',
            'min_tenure_months', 'max_tenure_months', 'interest_rate_min', 'interest_rate_max',
            'interest_type', 'employment_requirement', 'min_employment_months', 'min_age',
            'max_age', 'min_monthly_income', 'max_debt_to_income_ratio', 'min_credit_score',
            'allow_bad_credit', 'processing_fee_percentage', 'processing_fee_fixed',
            'late_payment_fee', 'early_repayment_fee_percentage', 'requires_collateral',
            'requires_guarantor', 'min_guarantors', 'approval_time_days', 'disbursement_time_days',
            'auto_approval_eligible', 'auto_approval_max_amount', 'terms_and_conditions',
            'eligibility_criteria', 'is_active', 'newKeyFeature'
        ]);

        $this->key_features = [];
        $this->selectedDocuments = ['national_id', 'salary_slip', 'bank_statement'];
        $this->selectedCollateralTypes = [];
        $this->selectedDisbursementMethods = ['bank_transfer'];
        $this->selectedBusinessSectors = [];
        $this->required_documents = $this->selectedDocuments;
        $this->disbursement_methods = $this->selectedDisbursementMethods;
        $this->selectedProduct = null;
        
        $this->resetValidation();
    }

    private function loadProductData()
    {
        if (!$this->selectedProduct) return;

        $product = $this->selectedProduct;
        
        $this->name = $product->name;
        $this->description = $product->description;
        $this->promotional_tag = $product->promotional_tag;
        $this->min_amount = $product->min_amount;
        $this->max_amount = $product->max_amount;
        $this->min_tenure_months = $product->min_tenure_months;
        $this->max_tenure_months = $product->max_tenure_months;
        $this->interest_rate_min = $product->interest_rate_min;
        $this->interest_rate_max = $product->interest_rate_max;
        $this->interest_type = $product->interest_type;
        $this->employment_requirement = $product->employment_requirement;
        $this->min_employment_months = $product->min_employment_months;
        $this->min_age = $product->min_age;
        $this->max_age = $product->max_age;
        $this->min_monthly_income = $product->min_monthly_income;
        $this->max_debt_to_income_ratio = $product->max_debt_to_income_ratio;
        $this->min_credit_score = $product->min_credit_score;
        $this->allow_bad_credit = $product->allow_bad_credit;
        $this->processing_fee_percentage = $product->processing_fee_percentage;
        $this->processing_fee_fixed = $product->processing_fee_fixed;
        $this->late_payment_fee = $product->late_payment_fee;
        $this->early_repayment_fee_percentage = $product->early_repayment_fee_percentage;
        $this->requires_collateral = $product->requires_collateral;
        $this->requires_guarantor = $product->requires_guarantor;
        $this->min_guarantors = $product->min_guarantors;
        $this->approval_time_days = $product->approval_time_days;
        $this->disbursement_time_days = $product->disbursement_time_days;
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

        // Dsr
        $this->minimum_dsr=  $product->minimum_dsr;

    }

    private function getProductData(): array
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
            'promotional_tag' => $this->promotional_tag,
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
            'minimum_dsr'=>$this->minimum_dsr,
            'loan_type'=>$this->loan_type
        ];
    }

    // Reactive updates
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingEmploymentFilter()
    {
        $this->resetPage();
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