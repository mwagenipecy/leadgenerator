<?php

namespace App\Livewire\LoanApplication;

use App\Models\Application;
use App\Models\LoanProduct;
use App\Models\Lender;
use App\Models\ApplicationDocument;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ApplicationManagement extends Component
{
    use WithFileUploads;

    // Component state
    public $currentStep = 'list'; // list, create, edit, view, products, prequalify
    public $currentFormStep = 1; // Multi-step form (1-6) - added pre-qualification step
    public $selectedApplication = null;
    public $matchingProducts = [];
    public $preQualificationResults = [];
    public $selectedLenders = []; // For multi-lender application
    
    // Pre-qualification fields
    #[Rule('required|numeric|min:1000')]
    public $prequalify_amount = 50000;
    
    #[Rule('required|numeric|min:0')]
    public $prequalify_monthly_income = 0;
    
    #[Rule('required|numeric|min:0')]
    public $prequalify_existing_loans = 0;
    
    #[Rule('required|integer|min:1|max:120')]
    public $prequalify_tenure = 12;
    
    public $calculated_dsr = 0;
    public $can_proceed = false;
    public $dsr_message = '';
    
    // Step 1: Loan Details
    #[Rule('required|numeric|min:1000')]
    public $requested_amount = 50000;

    #[Rule('required|integer|min:1|max:120')]
    public $requested_tenure_months = 12;

    #[Rule('nullable|string|max:255')]
    public $loan_purpose = '';

    // Step 2: Personal Information
    #[Rule('required|string|max:255')]
    public $first_name = '';

    #[Rule('required|string|max:255')]
    public $last_name = '';

    #[Rule('nullable|string|max:255')]
    public $middle_name = '';

    #[Rule('required|date|before:today')]
    public $date_of_birth = '';

    #[Rule('required|in:male,female,other')]
    public $gender = '';

    #[Rule('required|string')]
    public $marital_status = '';

    #[Rule('required|string|min:10|max:20')]
    public $national_id = '';

    #[Rule('required|string|min:10|max:15')]
    public $phone_number = '';

    #[Rule('required|email')]
    public $email = '';

    // Step 3: Address Information
    #[Rule('required|string')]
    public $current_address = '';

    #[Rule('required|string')]
    public $current_city = '';

    #[Rule('required|string')]
    public $current_region = '';

    #[Rule('nullable|string')]
    public $current_postal_code = '';

    #[Rule('required|integer|min:0|max:50')]
    public $years_at_current_address = 0;

    public $is_permanent_same_as_current = true;
    public $permanent_address = '';
    public $permanent_city = '';
    public $permanent_region = '';

    // Step 4: Employment & Financial Information
    #[Rule('required|in:employed,self_employed,unemployed,retired,student')]
    public $employment_status = 'employed';

    // Employment fields
    public $employer_name = '';
    public $job_title = '';
    public $employment_sector = '';
    public $years_of_employment = 0;
    public $months_with_current_employer = 0;
    public $monthly_salary = 0;
    public $other_monthly_income = 0;

    // Business fields (self-employed)
    public $business_name = '';
    public $business_type = '';
    public $business_registration_number = '';
    public $years_in_business = 0;
    public $monthly_business_income = 0;
    public $business_address = '';

    // Financial information
    #[Rule('required|numeric|min:0')]
    public $total_monthly_income = 0;

    #[Rule('required|numeric|min:0')]
    public $monthly_expenses = 0;

    #[Rule('nullable|numeric|min:0')]
    public $existing_loan_payments = 0;

    #[Rule('nullable|integer|min:300|max:850')]
    public $credit_score = null;

    public $has_bad_credit_history = false;

    // Bank Information
    public $bank_name = '';
    public $account_number = '';
    public $account_name = '';
    public $account_type = 'savings';
    public $years_with_bank = 0;

    // Step 5: Emergency Contact & Additional Info
    #[Rule('required|string')]
    public $emergency_contact_name = '';

    #[Rule('required|string')]
    public $emergency_contact_relationship = '';

    #[Rule('required|string')]
    public $emergency_contact_phone = '';

    public $emergency_contact_address = '';
    public $preferred_disbursement_method = 'bank_transfer';

    // Document uploads
    public $documents = [];
    public $uploadedDocuments = [];

    // Search and filters
    public $search = '';
    public $statusFilter = 'all';

    public function mount()
    {
        // Pre-fill user information if available
        if (Auth::check()) {
            $user = Auth::user();
            $this->email = $user->email ?? '';
            $this->first_name = $user->first_name ?? '';
            $this->last_name = $user->last_name ?? '';
            $this->phone_number = $user->phone ?? '';
        }
    }

    public function render()
    {
        $applications = collect();
        
        if ($this->currentStep === 'list') {
            $applications = Application::where('user_id', Auth::id())
                ->when($this->search, function ($query) {
                    $query->where(function ($q) {
                        $q->where('application_number', 'like', '%' . $this->search . '%')
                          ->orWhere('first_name', 'like', '%' . $this->search . '%')
                          ->orWhere('last_name', 'like', '%' . $this->search . '%');
                    });
                })
                ->when($this->statusFilter !== 'all', function ($query) {
                    $query->where('status', $this->statusFilter);
                })
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('livewire.loan-application.application-management', [
            'applications' => $applications,
            'employmentSectors' => $this->getEmploymentSectors(),
            'businessTypes' => $this->getBusinessTypes(),
            'maritalStatuses' => $this->getMaritalStatuses(),
            'requiredDocuments' => $this->getRequiredDocuments(),
        ]);
    }

    // Pre-qualification methods
    public function startPreQualification()
    {
        $this->resetForm();
        $this->currentStep = 'prequalify';
    }

    public function calculateDSR()
    {
        $this->validate([
            'prequalify_amount' => 'required|numeric|min:1000',
            'prequalify_monthly_income' => 'required|numeric|min:1',
            'prequalify_existing_loans' => 'required|numeric|min:0',
            'prequalify_tenure' => 'required|integer|min:1|max:120',
        ]);

        // Calculate estimated monthly payment (using average interest rate of 15%)
        $principal = $this->prequalify_amount;
        $monthlyRate = 0.15 / 12; // 15% annual rate
        
        $numberOfPayments = $this->prequalify_tenure;
        
        $estimatedMonthlyPayment = $principal * 
            ($monthlyRate * pow(1 + $monthlyRate, $numberOfPayments)) / 
            (pow(1 + $monthlyRate, $numberOfPayments) - 1);

        // Calculate DSR
        $totalMonthlyDebt = $this->prequalify_existing_loans + $estimatedMonthlyPayment;
        $this->calculated_dsr = ($totalMonthlyDebt / $this->prequalify_monthly_income) * 100;

        // Find matching loan products
        $this->findMatchingLenders();
    }

    private function findMatchingLenders()
    {
        $matchingProducts = LoanProduct::with('lender')
            ->where('is_active', true)
            ->where('min_amount', '<=', $this->prequalify_amount)
            ->where('max_amount', '>=', $this->prequalify_amount)
            ->where('min_tenure_months', '<=', $this->prequalify_tenure)
            ->where('max_tenure_months', '>=', $this->prequalify_tenure)
            ->where('min_monthly_income', '<=', $this->prequalify_monthly_income)
            ->where(function ($query) {
                $query->whereNull('minimum_dsr')
                      ->orWhere('minimum_dsr', '>=', $this->calculated_dsr);
            })
            ->orderBy('interest_rate_min', 'asc')
            ->get();

        $this->preQualificationResults = $matchingProducts->map(function ($product) {
            // Calculate more accurate monthly payment with product's interest rate
            $monthlyRate = $product->interest_rate_min / 100 / 12;
            $monthlyPayment = $this->prequalify_amount * 
                ($monthlyRate * pow(1 + $monthlyRate, $this->prequalify_tenure)) / 
                (pow(1 + $monthlyRate, $this->prequalify_tenure) - 1);

            $totalMonthlyDebt = $this->prequalify_existing_loans + $monthlyPayment;
            $actualDSR = ($totalMonthlyDebt / $this->prequalify_monthly_income) * 100;

            return [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'lender_name' => $product->lender->company_name,
                'lender_id' => $product->lender->id,
                'interest_rate_min' => $product->interest_rate_min,
                'interest_rate_max' => $product->interest_rate_max,
                'monthly_payment' => $monthlyPayment,
                'actual_dsr' => $actualDSR,
                'max_dsr_allowed' => $product->minimum_dsr ?? 40,
                'processing_fee_percentage' => $product->processing_fee_percentage,
                'processing_fee_fixed' => $product->processing_fee_fixed,
                'approval_time_days' => $product->approval_time_days,
                'disbursement_time_days' => $product->disbursement_time_days,
                'eligible' => $actualDSR <= ($product->minimum_dsr ?? 40),
            ];
        })->toArray();

        $this->can_proceed = count($this->preQualificationResults) > 0;
        
        if (!$this->can_proceed) {
            $this->dsr_message = "Your DSR of {$this->calculated_dsr}% is too high for available loan products. Consider reducing the loan amount or increasing your income.";
        } else {
            $eligibleCount = collect($this->preQualificationResults)->where('eligible', true)->count();
            $this->dsr_message = "Great! You qualify for {$eligibleCount} loan products with a DSR of {$this->calculated_dsr}%.";
        }
    }

    public function proceedToApplication($selectedLenderIds = null)
    {
        if (!$this->can_proceed) {
            session()->flash('error', 'You do not meet the minimum requirements for any loan products.');
            return;
        }

        // Store selected lenders
        if ($selectedLenderIds) {
            $this->selectedLenders = is_array($selectedLenderIds) ? $selectedLenderIds : [$selectedLenderIds];
        } else {
            // If no specific lenders selected, use all eligible ones
            $this->selectedLenders = collect($this->preQualificationResults)
                ->where('eligible', true)
                ->pluck('lender_id')
                ->toArray();
        }

        // Pre-fill loan details from pre-qualification
        $this->requested_amount = $this->prequalify_amount;
        $this->requested_tenure_months = $this->prequalify_tenure;
        $this->total_monthly_income = $this->prequalify_monthly_income;
        $this->existing_loan_payments = $this->prequalify_existing_loans;

        $this->currentStep = 'create';
        $this->currentFormStep = 1;
    }

    public function selectLender($lenderId)
    {
        if (in_array($lenderId, $this->selectedLenders)) {
            $this->selectedLenders = array_filter($this->selectedLenders, fn($id) => $id !== $lenderId);
        } else {
            $this->selectedLenders[] = $lenderId;
        }
    }

    public function selectAllEligibleLenders()
    {
        $this->selectedLenders = collect($this->preQualificationResults)
            ->where('eligible', true)
            ->pluck('lender_id')
            ->toArray();
    }

    public function clearSelectedLenders()
    {
        $this->selectedLenders = [];
    }

    // Navigation methods
    public function startNewApplication()
    {
        $this->resetForm();
        $this->startPreQualification();
    }

    public function backToList()
    {
        $this->currentStep = 'list';
        $this->resetForm();
    }

    public function backToPreQualification()
    {
        $this->currentStep = 'prequalify';
    }

    public function viewApplication($id)
    {
        $this->selectedApplication = Application::with(['documents', 'loanProduct', 'lender'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);
        $this->currentStep = 'view';
    }

    public function editApplication($id)
    {
        $this->selectedApplication = Application::where('user_id', Auth::id())
            ->where('status', 'draft')
            ->findOrFail($id);
        
        $this->loadApplicationData();
        $this->currentStep = 'edit';
        $this->currentFormStep = 1;
    }

    public function viewMatchingProducts($id)
    {
        $application = Application::where('user_id', Auth::id())
            ->findOrFail($id);
        
        $this->matchingProducts = $application->findMatchingProducts();
        $this->selectedApplication = $application;
        $this->currentStep = 'products';
    }

    // Multi-step form navigation
    public function nextStep()
    {
        $this->validateCurrentStep();
        
        if ($this->currentFormStep < 6) {
            $this->currentFormStep++;
        }
    }

    public function previousStep()
    {
        if ($this->currentFormStep > 1) {
            $this->currentFormStep--;
        } else {
            $this->backToPreQualification();
        }
    }

    public function goToStep($step)
    {
        if ($step >= 1 && $step <= 6) {
            // Validate all previous steps
            for ($i = 1; $i < $step; $i++) {
                $this->validateStep($i);
            }
            $this->currentFormStep = $step;
        }
    }

    // Step validation
    private function validateCurrentStep()
    {
        $this->validateStep($this->currentFormStep);
    }

    private function validateStep($step)
    {
        switch ($step) {
            case 1:
                $this->validate([
                    'requested_amount' => 'required|numeric|min:1000',
                    'requested_tenure_months' => 'required|integer|min:1',
                    'loan_purpose' => 'nullable|string',
                ]);
                break;
            case 2:
                $this->validate([
                    'first_name' => 'required|string',
                    'last_name' => 'required|string',
                    'date_of_birth' => 'required|date',
                    'gender' => 'required|string',
                    'marital_status' => 'required|string',
                    'national_id' => 'required|string',
                    'phone_number' => 'required|string',
                    'email' => 'required|email',
                ]);
                break;
            case 3:
                $this->validate([
                    'current_address' => 'required|string',
                    'current_city' => 'required|string',
                    'current_region' => 'required|string',
                    'years_at_current_address' => 'required|integer',
                ]);
                break;
            case 4:
                $this->validate([
                    'employment_status' => 'required|string',
                    'total_monthly_income' => 'required|numeric|min:0',
                    'monthly_expenses' => 'required|numeric|min:0',
                ]);
                
                if ($this->employment_status === 'employed') {
                    $this->validate([
                        'employer_name' => 'required|string',
                        'job_title' => 'required|string',
                        'monthly_salary' => 'required|numeric|min:0',
                    ]);
                } elseif ($this->employment_status === 'self_employed') {
                    $this->validate([
                        'business_name' => 'required|string',
                        'business_type' => 'required|string',
                        'monthly_business_income' => 'required|numeric|min:0',
                    ]);
                }
                break;
            case 5:
                $this->validate([
                    'emergency_contact_name' => 'required|string',
                    'emergency_contact_relationship' => 'required|string',
                    'emergency_contact_phone' => 'required|string',
                ]);
                break;
        }
    }

    // Save application - modified to handle multiple lenders
    public function saveApplication($submit = false)
    {
        // Validate all steps if submitting
        if ($submit) {
            for ($i = 1; $i <= 5; $i++) {
                // $this->validateStep($i);
            }
        }

        $data = $this->getApplicationData();
        $data['user_id'] = Auth::id();
        $data['status'] = $submit ? 'submitted' : 'draft';
        
        if ($submit) {
            $data['submitted_at'] = now();
        }

        // Calculate final DSR
        $monthlyPayment = $this->calculateMonthlyPayment($this->requested_amount, $this->requested_tenure_months);
        $totalDebt = $this->existing_loan_payments + $monthlyPayment;
        $finalDSR = ($totalDebt / $this->total_monthly_income) * 100;
        $data['debt_to_income_ratio'] = $finalDSR;

        if (count($this->selectedLenders) > 1) {
            // Create separate applications for each selected lender
            $applications = [];
            foreach ($this->selectedLenders as $lenderId) {
                $lenderData = $data;
                $lenderData['lender_id'] = $lenderId;
                
                // Find matching product for this lender
                $matchingProduct = collect($this->preQualificationResults)
                    ->where('lender_id', $lenderId)
                    ->first();
                
                if ($matchingProduct) {
                    $product = LoanProduct::find($matchingProduct['product_id']);
                    $lenderData['loan_product_id'] = $product->id;
                }
                
                $application = Application::create($lenderData);
                $applications[] = $application;
            }
            
            $message = $submit ? 
                'Applications submitted to ' . count($applications) . ' lenders successfully!' : 
                'Applications saved as draft for ' . count($applications) . ' lenders!';
        } else {
            // Single lender application
            if (!empty($this->selectedLenders)) {
                $data['lender_id'] = $this->selectedLenders[0];
                
                $matchingProduct = collect($this->preQualificationResults)
                    ->where('lender_id', $this->selectedLenders[0])
                    ->first();
                
                if ($matchingProduct) {
                    $product = LoanProduct::find($matchingProduct['product_id']);
                    $data['loan_product_id'] = $product->id;
                }
            }

            if ($this->currentStep === 'edit' && $this->selectedApplication) {
                $this->selectedApplication->update($data);
                $application = $this->selectedApplication;
            } else {
                $application = Application::create($data);
            }

            $message = $submit ? 'Application submitted successfully!' : 'Application saved as draft!';
        }

        session()->flash('message', $message);
        $this->backToList();
    }

    private function calculateMonthlyPayment($amount, $tenure, $interestRate = 15)
    {
        $monthlyRate = $interestRate / 100 / 12;
        return $amount * 
            ($monthlyRate * pow(1 + $monthlyRate, $tenure)) / 
            (pow(1 + $monthlyRate, $tenure) - 1);
    }

    // Document upload
    public function uploadDocument($documentType)
    {
        $this->validate([
            'documents.' . $documentType => 'required|file|max:5120|mimes:pdf,jpg,jpeg,png'
        ]);

        $file = $this->documents[$documentType];
        $path = $file->store('application-documents', 'public');

        $this->uploadedDocuments[$documentType] = [
            'path' => $path,
            'name' => $file->getClientOriginalName(),
            'size' => $file->getSize(),
            'type' => $file->getClientOriginalExtension(),
        ];

        session()->flash('message', 'Document uploaded successfully!');
    }

    public function removeDocument($documentType)
    {
        if (isset($this->uploadedDocuments[$documentType])) {
            Storage::disk('public')->delete($this->uploadedDocuments[$documentType]['path']);
            unset($this->uploadedDocuments[$documentType]);
        }
    }

    // Apply to specific product
    public function applyToProduct($productId)
    {
        if (!$this->selectedApplication) {
            return;
        }

        $product = LoanProduct::findOrFail($productId);
        
        // Check eligibility
        if (!$this->selectedApplication->canApplyTo($product)) {
            session()->flash('error', 'You do not meet the eligibility criteria for this product.');
            return;
        }

        $this->selectedApplication->update([
            'loan_product_id' => $product->id,
            'lender_id' => $product->lender_id,
            'status' => 'under_review',
        ]);

        session()->flash('message', 'Application submitted to ' . $product->lender->company_name . ' successfully!');
        $this->backToList();
    }

    // Cancel application
    public function cancelApplication($id)
    {
        $application = Application::where('user_id', Auth::id())
            ->findOrFail($id);
        
        $application->update(['status' => 'cancelled']);
        session()->flash('message', 'Application cancelled successfully!');
    }

    // Helper methods
    private function resetForm()
    {
        $this->reset([
            'prequalify_amount', 'prequalify_monthly_income', 'prequalify_existing_loans', 'prequalify_tenure',
            'calculated_dsr', 'can_proceed', 'dsr_message', 'preQualificationResults', 'selectedLenders',
            'requested_amount', 'requested_tenure_months', 'loan_purpose',
            'first_name', 'last_name', 'middle_name', 'date_of_birth',
            'gender', 'marital_status', 'national_id', 'phone_number',
            'current_address', 'current_city', 'current_region',
            'current_postal_code', 'years_at_current_address',
            'permanent_address', 'permanent_city', 'permanent_region',
            'employment_status', 'employer_name', 'job_title',
            'employment_sector', 'years_of_employment', 'months_with_current_employer',
            'monthly_salary', 'other_monthly_income', 'business_name',
            'business_type', 'business_registration_number', 'years_in_business',
            'monthly_business_income', 'business_address', 'total_monthly_income',
            'monthly_expenses', 'existing_loan_payments', 'credit_score',
            'has_bad_credit_history', 'bank_name', 'account_number',
            'account_name', 'account_type', 'years_with_bank',
            'emergency_contact_name', 'emergency_contact_relationship',
            'emergency_contact_phone', 'emergency_contact_address',
            'preferred_disbursement_method'
        ]);

        $this->documents = [];
        $this->uploadedDocuments = [];
        $this->selectedApplication = null;
        $this->matchingProducts = [];
        
        // Re-populate user info
        if (Auth::check()) {
            $user = Auth::user();
            $this->email = $user->email ?? '';
            $this->first_name = $user->first_name ?? '';
            $this->last_name = $user->last_name ?? '';
            $this->phone_number = $user->phone ?? '';
        }
        
        $this->resetValidation();
    }

    private function loadApplicationData()
    {
        if (!$this->selectedApplication) return;

        $app = $this->selectedApplication;
        
        // Map all application fields to component properties
        $this->requested_amount = $app->requested_amount;
        $this->requested_tenure_months = $app->requested_tenure_months;
        $this->loan_purpose = $app->loan_purpose;
        $this->first_name = $app->first_name;
        $this->last_name = $app->last_name;
        $this->middle_name = $app->middle_name;
        $this->date_of_birth = $app->date_of_birth?->format('Y-m-d');
        $this->gender = $app->gender;
        $this->marital_status = $app->marital_status;
        $this->national_id = $app->national_id;
        $this->phone_number = $app->phone_number;
        $this->email = $app->email;
        $this->current_address = $app->current_address;
        $this->current_city = $app->current_city;
        $this->current_region = $app->current_region;
        $this->current_postal_code = $app->current_postal_code;
        $this->years_at_current_address = $app->years_at_current_address;
        $this->is_permanent_same_as_current = $app->is_permanent_same_as_current;
        $this->permanent_address = $app->permanent_address;
        $this->permanent_city = $app->permanent_city;
        $this->permanent_region = $app->permanent_region;
        $this->employment_status = $app->employment_status;
        $this->total_monthly_income = $app->total_monthly_income;
        $this->existing_loan_payments = $app->existing_loan_payments;
        // ... continue for all other fields
    }

    private function getApplicationData(): array
    {
        // Calculate total income
        $totalIncome = $this->monthly_salary + $this->other_monthly_income + $this->monthly_business_income;
        $this->total_monthly_income = max($totalIncome, $this->total_monthly_income);

        return [
            'requested_amount' => $this->requested_amount,
            'requested_tenure_months' => $this->requested_tenure_months,
            'loan_purpose' => $this->loan_purpose,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'middle_name' => $this->middle_name,
            'date_of_birth' => $this->date_of_birth,
            'gender' => $this->gender,
            'marital_status' => $this->marital_status,
            'national_id' => $this->national_id,
            'phone_number' => $this->phone_number,
            'email' => $this->email,
            'current_address' => $this->current_address,
            'current_city' => $this->current_city,
            'current_region' => $this->current_region,
            'current_postal_code' => $this->current_postal_code,
            'years_at_current_address' => $this->years_at_current_address,
            'is_permanent_same_as_current' => $this->is_permanent_same_as_current,
            'permanent_address' => $this->permanent_address,
            'permanent_city' => $this->permanent_city,
            'permanent_region' => $this->permanent_region,
            'employment_status' => $this->employment_status,
            'employer_name' => $this->employer_name,
            'job_title' => $this->job_title,
            'employment_sector' => $this->employment_sector,
            'years_of_employment' => $this->years_of_employment,
            'months_with_current_employer' => $this->months_with_current_employer,
            'monthly_salary' => $this->monthly_salary,
            'other_monthly_income' => $this->other_monthly_income,
            'business_name' => $this->business_name,
            'business_type' => $this->business_type,
            'business_registration_number' => $this->business_registration_number,
            'years_in_business' => $this->years_in_business,
            'monthly_business_income' => $this->monthly_business_income,
            'business_address' => $this->business_address,
            'total_monthly_income' => $this->total_monthly_income,
            'monthly_expenses' => $this->monthly_expenses,
            'existing_loan_payments' => $this->existing_loan_payments,
            'credit_score' => $this->credit_score,
            'has_bad_credit_history' => $this->has_bad_credit_history,
            'bank_name' => $this->bank_name,
            'account_number' => $this->account_number,
            'account_name' => $this->account_name,
            'account_type' => $this->account_type,
            'years_with_bank' => $this->years_with_bank,
            'emergency_contact_name' => $this->emergency_contact_name,
            'emergency_contact_relationship' => $this->emergency_contact_relationship,
            'emergency_contact_phone' => $this->emergency_contact_phone,
            'emergency_contact_address' => $this->emergency_contact_address,
            'preferred_disbursement_method' => $this->preferred_disbursement_method,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'application_source' => ['platform' => 'web'],
        ];
    }

    // Real-time updates
    public function updated($propertyName)
    {
        // Auto-calculate total income
        if (in_array($propertyName, ['monthly_salary', 'other_monthly_income', 'monthly_business_income'])) {
            $this->total_monthly_income = $this->monthly_salary + $this->other_monthly_income + $this->monthly_business_income;
        }

        // Handle permanent address same as current
        if ($propertyName === 'is_permanent_same_as_current' && $this->is_permanent_same_as_current) {
            $this->permanent_address = $this->current_address;
            $this->permanent_city = $this->current_city;
            $this->permanent_region = $this->current_region;
        }

        // Auto-calculate DSR when pre-qualification fields change
        if (in_array($propertyName, ['prequalify_amount', 'prequalify_monthly_income', 'prequalify_existing_loans', 'prequalify_tenure'])) {
            if ($this->prequalify_amount > 0 && $this->prequalify_monthly_income > 0 && $this->prequalify_tenure > 0) {
                $this->calculateDSR();
            }
        }
    }

    // Helper data methods
    private function getEmploymentSectors(): array
    {
        return [
            'agriculture' => 'Agriculture',
            'banking' => 'Banking & Finance',
            'construction' => 'Construction',
            'education' => 'Education',
            'healthcare' => 'Healthcare',
            'manufacturing' => 'Manufacturing',
            'retail' => 'Retail',
            'technology' => 'Technology',
            'telecommunications' => 'Telecommunications',
            'transportation' => 'Transportation',
            'government' => 'Government',
            'ngo' => 'NGO/Non-Profit',
            'other' => 'Other',
        ];
    }

    private function getBusinessTypes(): array
    {
        return [
            'retail' => 'Retail Business',
            'services' => 'Services',
            'manufacturing' => 'Manufacturing',
            'agriculture' => 'Agriculture',
            'technology' => 'Technology',
            'consulting' => 'Consulting',
            'trading' => 'Trading',
            'transportation' => 'Transportation',
            'construction' => 'Construction',
            'other' => 'Other',
        ];
    }

    private function getMaritalStatuses(): array
    {
        return [
            'single' => 'Single',
            'married' => 'Married',
            'divorced' => 'Divorced',
            'widowed' => 'Widowed',
            'separated' => 'Separated',
        ];
    }

    private function getRequiredDocuments(): array
    {
        return [
            'national_id' => 'National ID',
            'salary_slip' => 'Salary Slip',
            'bank_statement' => 'Bank Statement',
            'employment_letter' => 'Employment Letter',
            'utility_bill' => 'Utility Bill',
            'business_license' => 'Business License',
            'tax_certificate' => 'Tax Certificate',
        ];
    }
}