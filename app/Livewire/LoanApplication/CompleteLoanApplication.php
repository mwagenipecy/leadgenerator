<?php

namespace App\Livewire\LoanApplication;

use App\Models\Application;
use App\Models\ApplicationLenderSubmission;
use App\Models\ApplicationDocument;
use App\Models\UserProfile;
use App\Models\LoanProduct;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class CompleteLoanApplication extends Component
{
    use WithFileUploads;

    public $currentStep = 1; // 1: Documents, 2: Preview, 3: Submit
    public $userProfile;
    public $prequalificationData = [];
    
    // Document uploads
    public $documents = [];
    public $uploadedDocuments = [];
    public $requiredDocuments = [];
    
    // Application data (copied from profile for security/backup)
    public $applicationData = [];
    
    // Final application
    public $finalApplication = null;
    public $submissionResults = [];
    
    // Loading states
    public $isUploading = false;
    public $isSubmitting = false;

    public function mount()
    {
        // Load pre-qualification data
        $this->prequalificationData = session('prequalification_data', []);

        \Log::info('CompleteLoanApplication mount - Prequalification data loaded', [
            'has_data' => !empty($this->prequalificationData),
            'monthly_income' => $this->prequalificationData['monthly_income'] ?? 'NOT SET',
            'existing_loans' => $this->prequalificationData['existing_loans'] ?? 'NOT SET',
            'all_keys' => array_keys($this->prequalificationData),
        ]);

        if (empty($this->prequalificationData)) {
            session()->flash('error', 'No pre-qualification data found. Please start from pre-qualification.');
            return redirect()->route('loan-application.create');
        }

        // Validate that we have monthly income
        if (empty($this->prequalificationData['monthly_income']) || $this->prequalificationData['monthly_income'] <= 0) {
            \Log::warning('CompleteLoanApplication mount - Missing monthly income', [
                'prequalificationData' => $this->prequalificationData,
            ]);
        }

        // Load user profile
        $this->userProfile = Auth::user()->profile;
        
        if (!$this->userProfile || $this->userProfile->profile_completion_percentage < 70) {
            session()->flash('error', 'Please complete your profile first. Your profile must be at least 70% complete to apply for a loan.');
            return redirect()->route('loan-application.profile');
        }
        
        // Check for mandatory emergency contact information
        if (empty($this->userProfile->emergency_contact_name) ||
            empty($this->userProfile->emergency_contact_relationship) ||
            empty($this->userProfile->emergency_contact_phone)) {
            session()->flash('error', 'Emergency contact information is required. Please complete the Emergency Contact section in your profile before applying for a loan.');
            return redirect()->route('loan-application.profile');
        }

        // Re-calculate and verify profile completion percentage
        $actualCompletion = $this->userProfile->calculateCompletionPercentage();
        if ($actualCompletion < 70) {
            session()->flash('error', 'Please complete your profile first. Your profile must be at least 70% complete to apply for a loan. Current completion: ' . $actualCompletion . '%.');
            return redirect()->route('loan-application.profile');
        }

        // Prepare application data (copy from profile for backup)
        $this->prepareApplicationData();
        
        // Set required documents based on employment status and selected lenders
        $this->setRequiredDocuments();
    }

    public function render()
    {
        return view('livewire.loan-application.complete-loan-application', [
            'selectedLenders' => $this->getSelectedLenders(),
            'estimatedProcessingTime' => $this->getEstimatedProcessingTime(),
        ]);
    }

    private function prepareApplicationData()
    {
        // Use isset() to properly check if key exists (even if value is 0)
        $monthlyIncomeFromPrequal = isset($this->prequalificationData['monthly_income']) 
            ? (float)$this->prequalificationData['monthly_income'] 
            : null;
        $existingLoansFromPrequal = isset($this->prequalificationData['existing_loans']) 
            ? (float)$this->prequalificationData['existing_loans'] 
            : null;

        $this->applicationData = [
            // Loan Details (from pre-qualification)
            'loan_category' => $this->prequalificationData['loan_category'],
            'loan_type' => $this->prequalificationData['loan_type'] ?? 'unsecured',
            'requested_amount' => $this->prequalificationData['requested_amount'],
            'requested_tenure_months' => $this->prequalificationData['requested_tenure'],
            'loan_purpose' => 'General purpose', // Can be updated if needed
            
            // Personal Information (from profile)
            'first_name' => $this->userProfile->first_name,
            'middle_name' => $this->userProfile->middle_name,
            'last_name' => $this->userProfile->last_name,
            'date_of_birth' => $this->userProfile->date_of_birth,
            'gender' => $this->userProfile->gender ?? 'other',
            'marital_status' => $this->userProfile->marital_status ?? 'single',
            'national_id' => $this->userProfile->national_id,
            'phone_number' => $this->userProfile->phone_number,
            'email' => $this->userProfile->email,
            
            // Address Information
            'current_address' => $this->userProfile->current_address,
            'current_city' => $this->userProfile->current_city,
            'current_region' => $this->userProfile->current_region,
            'current_postal_code' => $this->userProfile->current_postal_code,
            'years_at_current_address' => $this->userProfile->years_at_current_address,
            'is_permanent_same_as_current' => $this->userProfile->is_permanent_same_as_current,
            'permanent_address' => $this->userProfile->permanent_address,
            'permanent_city' => $this->userProfile->permanent_city,
            'permanent_region' => $this->userProfile->permanent_region,
            
            // Employment Information (ensure valid enum value)
            'employment_status' => in_array($this->userProfile->employment_status ?? null, ['employed', 'self_employed', 'unemployed', 'retired', 'student'])
                ? $this->userProfile->employment_status
                : 'unemployed',
            'employer_name' => $this->userProfile->employer_name,
            'job_title' => $this->userProfile->job_title,
            'employment_sector' => $this->userProfile->employment_sector,
            'years_of_employment' => $this->userProfile->years_of_employment,
            'months_with_current_employer' => $this->userProfile->months_with_current_employer,
            
            // Business Information (if self-employed)
            'business_name' => $this->userProfile->business_name,
            'business_type' => $this->userProfile->business_type,
            'business_registration_number' => $this->userProfile->business_registration_number,
            'years_in_business' => $this->userProfile->years_in_business,
            'business_address' => $this->userProfile->business_address,
            
            // Financial Information (use prequalification data if available, fallback to profile, ensure non-null values)
            'monthly_salary' => $monthlyIncomeFromPrequal ?? ($this->userProfile->monthly_salary ?? 0),
            'other_monthly_income' => $this->userProfile->other_monthly_income ?? 0,
            'monthly_business_income' => $this->userProfile->monthly_business_income ?? 0,
            'total_monthly_income' => $monthlyIncomeFromPrequal ?? 
                                     ($this->userProfile->total_monthly_income ?? 
                                      (($this->userProfile->monthly_salary ?? 0) + 
                                       ($this->userProfile->other_monthly_income ?? 0) + 
                                       ($this->userProfile->monthly_business_income ?? 0)) ?: 0),
            'monthly_expenses' => $this->userProfile->monthly_expenses ?? 0,
            'existing_loan_payments' => $existingLoansFromPrequal ?? ($this->userProfile->existing_loan_payments ?? 0),
            'credit_score' => $this->userProfile->credit_score,
            'has_bad_credit_history' => $this->userProfile->has_bad_credit_history,
            
            // Bank Information
            'has_bank_account' => $this->userProfile->has_bank_account,
            'bank_name' => $this->userProfile->bank_name,
            'account_number' => $this->userProfile->account_number,
            'account_name' => $this->userProfile->account_name,
            'account_type' => $this->userProfile->account_type,
            'years_with_bank' => $this->userProfile->years_with_bank,
            
            // Emergency Contact (with fallback values)
            'emergency_contact_name' => $this->userProfile->emergency_contact_name ?? 'Not Provided',
            'emergency_contact_relationship' => $this->userProfile->emergency_contact_relationship ?? 'other',
            'emergency_contact_phone' => $this->userProfile->emergency_contact_phone ?? 'Not Provided',
            'emergency_contact_address' => $this->userProfile->emergency_contact_address ?? '',
            'preferred_disbursement_method' => $this->userProfile->preferred_disbursement_method ?? 'bank_transfer',
            
            // Calculated values
            'debt_to_income_ratio' => $this->prequalificationData['calculated_dsr'] ?? 0,
            'use_profile_data' => true,
            'profile_overrides' => [],
            
            // System fields
            'user_id' => Auth::id(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'application_source' => ['platform' => 'web', 'version' => '2.0'],
        ];
    }

    private function setRequiredDocuments()
    {
        // Base required documents
        $this->requiredDocuments = [
            'national_id' => [
                'name' => 'National ID',
                'description' => 'Clear photo of both sides of your National ID',
                'required' => true,
                'uploaded' => false,
                'requested_by' => ['All Lenders']
            ]
        ];

        // Get all selected products to aggregate document requirements
        $selectedProducts = collect($this->prequalificationData['selected_product_details'] ?? []);

        // Collect all unique document requirements from selected lenders
        $allRequiredDocs = [];
        $lenderDocRequirements = [];

        foreach ($selectedProducts as $product) {
            $lenderName = $product['lender_name'];
            $requiredDocs = $product['required_documents'] ?? [];
            
            foreach ($requiredDocs as $docType) {
                $normalizedDocType = $this->normalizeDocumentType($docType);
                
                if (!isset($allRequiredDocs[$normalizedDocType])) {
                    $allRequiredDocs[$normalizedDocType] = [
                        'name' => $this->getDocumentDisplayName($normalizedDocType),
                        'description' => $this->getDocumentDescription($normalizedDocType),
                        'required' => true,
                        'uploaded' => false,
                        'requested_by' => []
                    ];
                }
                
                if (!in_array($lenderName, $allRequiredDocs[$normalizedDocType]['requested_by'])) {
                    $allRequiredDocs[$normalizedDocType]['requested_by'][] = $lenderName;
                }
            }
        }

        // Employment-specific documents
        if ($this->userProfile->employment_status === 'employed') {
            $employmentDocs = [
                'salary_slip' => [
                    'name' => 'Salary Slip',
                    'description' => 'Most recent 3 months salary slips',
                    'required' => true,
                    'uploaded' => false,
                    'requested_by' => []
                ],
                'employment_letter' => [
                    'name' => 'Employment Letter',
                    'description' => 'Employment confirmation letter from your employer',
                    'required' => false,
                    'uploaded' => false,
                    'requested_by' => []
                ]
            ];

            foreach ($employmentDocs as $docType => $docInfo) {
                if (!isset($allRequiredDocs[$docType])) {
                    $allRequiredDocs[$docType] = $docInfo;
                }
            }
        }

        if ($this->userProfile->employment_status === 'self_employed') {
            $businessDocs = [
                'business_license' => [
                    'name' => 'Business License',
                    'description' => 'Valid business license or registration certificate',
                    'required' => true,
                    'uploaded' => false,
                    'requested_by' => []
                ],
                'tax_certificate' => [
                    'name' => 'Tax Certificate',
                    'description' => 'Tax compliance certificate or recent tax returns',
                    'required' => false,
                    'uploaded' => false,
                    'requested_by' => []
                ]
            ];

            foreach ($businessDocs as $docType => $docInfo) {
                if (!isset($allRequiredDocs[$docType])) {
                    $allRequiredDocs[$docType] = $docInfo;
                }
            }
        }

        // Bank statement (always required)
        if (!isset($allRequiredDocs['bank_statement'])) {
            $allRequiredDocs['bank_statement'] = [
                'name' => 'Bank Statement',
                'description' => 'Last 6 months bank statements',
                'required' => true,
                'uploaded' => false,
                'requested_by' => []
            ];
        }

        // Collateral documents (if any secured loan)
        $hasSecuredLoan = $selectedProducts->contains('is_secured', true);
        if ($hasSecuredLoan) {
            $allRequiredDocs['collateral_documents'] = [
                'name' => 'Collateral Documents',
                'description' => 'Property title, vehicle logbook, or other collateral documents',
                'required' => true,
                'uploaded' => false,
                'requested_by' => $selectedProducts->where('is_secured', true)->pluck('lender_name')->unique()->toArray()
            ];
        }

        // Merge with existing required documents
        $this->requiredDocuments = array_merge($this->requiredDocuments, $allRequiredDocs);
    }

    private function normalizeDocumentType($docType)
    {
        $normalizedTypes = [
            'salary slip' => 'salary_slip',
            'payslip' => 'salary_slip',
            'pay slip' => 'salary_slip',
            'bank statement' => 'bank_statement',
            'bank statements' => 'bank_statement',
            'employment letter' => 'employment_letter',
            'employment certificate' => 'employment_letter',
            'business license' => 'business_license',
            'business registration' => 'business_license',
            'tax certificate' => 'tax_certificate',
            'tax clearance' => 'tax_certificate',
            'national id' => 'national_id',
            'id copy' => 'national_id',
            'collateral' => 'collateral_documents',
            'security documents' => 'collateral_documents',
        ];

        $lowercaseDoc = strtolower(trim($docType));
        return $normalizedTypes[$lowercaseDoc] ?? str_replace(' ', '_', $lowercaseDoc);
    }

    private function getDocumentDisplayName($docType)
    {
        $displayNames = [
            'salary_slip' => 'Salary Slip',
            'bank_statement' => 'Bank Statement',
            'employment_letter' => 'Employment Letter',
            'business_license' => 'Business License',
            'tax_certificate' => 'Tax Certificate',
            'national_id' => 'National ID',
            'collateral_documents' => 'Collateral Documents',
        ];

        return $displayNames[$docType] ?? ucwords(str_replace('_', ' ', $docType));
    }

    private function getDocumentDescription($docType)
    {
        $descriptions = [
            'salary_slip' => 'Most recent 3 months salary slips',
            'bank_statement' => 'Last 6 months bank statements',
            'employment_letter' => 'Employment confirmation letter from your employer',
            'business_license' => 'Valid business license or registration certificate',
            'tax_certificate' => 'Tax compliance certificate or recent tax returns',
            'national_id' => 'Clear photo of both sides of your National ID',
            'collateral_documents' => 'Property title, vehicle logbook, or other collateral documents',
        ];

        return $descriptions[$docType] ?? 'Required document for loan application';
    }

    public function updatedDocuments()
    {
        // Auto-upload when a file is selected
        foreach ($this->documents as $documentType => $file) {
            if ($file && !$this->requiredDocuments[$documentType]['uploaded']) {
                $this->uploadDocument($documentType);
            }
        }
    }

    public function uploadDocument($documentType)
    {
        $this->validate([
            'documents.' . $documentType => 'required|file|max:5120|mimes:pdf,jpg,jpeg,png'
        ]);

        $this->isUploading = true;

        try {
            $file = $this->documents[$documentType];
            $path = $file->store('application-documents', 'public');

            // Generate file hash for integrity checking
            $fileHash = hash_file('sha256', $file->getRealPath());

            // Create document record
            $document = ApplicationDocument::create([
                'application_id' => 0, // Will be updated when application is created
                'document_type' => $documentType,
                'document_name' => $file->getClientOriginalName(),
                'file_path' => $path,
                'file_type' => $file->getClientOriginalExtension(),
                'file_size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
                'status' => 'uploaded',
                'document_date' => now()->toDateString(),
                'file_hash' => $fileHash,
                'is_required' => $this->requiredDocuments[$documentType]['required'],
                'is_encrypted' => 0,
            ]);

            $this->uploadedDocuments[$documentType] = [
                'id' => $document->id,
                'path' => $path,
                'name' => $file->getClientOriginalName(),
                'size' => $file->getSize(),
                'type' => $file->getClientOriginalExtension(),
                'hash' => $fileHash,
                'uploaded_at' => now(),
            ];

            $this->requiredDocuments[$documentType]['uploaded'] = true;

            session()->flash('message', 'Document uploaded successfully!');

        } catch (\Exception $e) {
            session()->flash('error', 'Error uploading document. Please try again.');
        }

        $this->isUploading = false;

        // Reset the file input
        unset($this->documents[$documentType]);
    }

    public function removeDocument($documentType)
    {
        if (isset($this->uploadedDocuments[$documentType])) {
            // Delete file from storage
            Storage::disk('public')->delete($this->uploadedDocuments[$documentType]['path']);
            
            // Delete database record
            ApplicationDocument::find($this->uploadedDocuments[$documentType]['id'])->delete();
            
            // Update state
            unset($this->uploadedDocuments[$documentType]);
            $this->requiredDocuments[$documentType]['uploaded'] = false;
            
            session()->flash('message', 'Document removed successfully!');
        }
    }

    public function nextStep()
    {
        if ($this->currentStep === 1) {
            // Validate required documents are uploaded
            $missingRequired = [];
            foreach ($this->requiredDocuments as $type => $doc) {
                if ($doc['required'] && !$doc['uploaded']) {
                    $missingRequired[] = $doc['name'];
                }
            }

            if (!empty($missingRequired)) {
                session()->flash('error', 'Please upload all required documents: ' . implode(', ', $missingRequired));
                return;
            }
        }

        if ($this->currentStep < 3) {
            $this->currentStep++;
        }

        if($this->currentStep == 3){
            $this->submitApplication();
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
        if ($step >= 1 && $step <= 3) {
            $this->currentStep = $step;
        }
    }

    public function submitApplication()
    {
        $this->isSubmitting = true;

        try {
            DB::beginTransaction();

            // Validate required data before creating application
            if (empty($this->prequalificationData['selected_product_details'] ?? [])) {
                throw new \Exception('No loan products selected. Please go back and select products.');
            }

            if (count($this->uploadedDocuments) === 0) {
                throw new \Exception('No documents uploaded. Please upload required documents.');
            }

            // Ensure user has a profile
            if (!$this->userProfile) {
                throw new \Exception('User profile not found. Please complete your profile first.');
            }

            // Prepare application data with validation
            $applicationData = [
                'user_id' => Auth::id(),
                'requested_amount' => $this->applicationData['requested_amount'],
                'requested_tenure_months' => $this->applicationData['requested_tenure_months'],
                'loan_purpose' => $this->applicationData['loan_purpose'],
                'first_name' => $this->applicationData['first_name'],
                'middle_name' => $this->applicationData['middle_name'],
                'last_name' => $this->applicationData['last_name'],
                'date_of_birth' => $this->applicationData['date_of_birth'],
                'gender' => $this->applicationData['gender'],
                'marital_status' => $this->applicationData['marital_status'],
                'national_id' => $this->applicationData['national_id'],
                'phone_number' => $this->applicationData['phone_number'],
                'email' => $this->applicationData['email'],
                'current_address' => $this->applicationData['current_address'],
                'current_city' => $this->applicationData['current_city'],
                'current_region' => $this->applicationData['current_region'],
                'current_postal_code' => $this->applicationData['current_postal_code'],
                'years_at_current_address' => $this->applicationData['years_at_current_address'],
                'is_permanent_same_as_current' => $this->applicationData['is_permanent_same_as_current'],
                'permanent_address' => $this->applicationData['permanent_address'],
                'permanent_city' => $this->applicationData['permanent_city'],
                'permanent_region' => $this->applicationData['permanent_region'],
                'employment_status' => $this->applicationData['employment_status'] ?? 'unemployed',
                'employer_name' => !empty($this->applicationData['employer_name']) ? $this->applicationData['employer_name'] : null,
                'job_title' => !empty($this->applicationData['job_title']) ? $this->applicationData['job_title'] : null,
                'employment_sector' => !empty($this->applicationData['employment_sector']) ? $this->applicationData['employment_sector'] : null,
                'years_of_employment' => !empty($this->applicationData['years_of_employment']) ? $this->applicationData['years_of_employment'] : null,
                'months_with_current_employer' => !empty($this->applicationData['months_with_current_employer']) ? $this->applicationData['months_with_current_employer'] : null,
                'business_name' => $this->applicationData['business_name'],
                'business_type' => $this->applicationData['business_type'],
                'business_registration_number' => $this->applicationData['business_registration_number'],
                'years_in_business' => $this->applicationData['years_in_business'],
                'business_address' => $this->applicationData['business_address'],
                'monthly_salary' => (float)($this->applicationData['monthly_salary'] ?? 0),
                'other_monthly_income' => (float)($this->applicationData['other_monthly_income'] ?? 0),
                'monthly_business_income' => (float)($this->applicationData['monthly_business_income'] ?? 0),
                'total_monthly_income' => (float)($this->applicationData['total_monthly_income'] ?? 0),
                'monthly_expenses' => (float)($this->applicationData['monthly_expenses'] ?? 0),
                'existing_loan_payments' => (float)($this->applicationData['existing_loan_payments'] ?? 0),
                'credit_score' => $this->applicationData['credit_score'],
                'has_bad_credit_history' => $this->applicationData['has_bad_credit_history'],
                'has_bank_account' => $this->applicationData['has_bank_account'],
                'bank_name' => $this->applicationData['bank_name'],
                'account_number' => $this->applicationData['account_number'],
                'account_name' => $this->applicationData['account_name'],
                'account_type' => $this->applicationData['account_type'],
                'years_with_bank' => $this->applicationData['years_with_bank'],
                'emergency_contact_name' => $this->applicationData['emergency_contact_name'],
                'emergency_contact_relationship' => $this->applicationData['emergency_contact_relationship'],
                'emergency_contact_phone' => $this->applicationData['emergency_contact_phone'],
                'emergency_contact_address' => $this->applicationData['emergency_contact_address'],
                'preferred_disbursement_method' => $this->applicationData['preferred_disbursement_method'],
                'debt_to_income_ratio' => $this->applicationData['debt_to_income_ratio'],
                'use_profile_data' => $this->applicationData['use_profile_data'],
                'profile_overrides' => $this->applicationData['profile_overrides'],
                'ip_address' => $this->applicationData['ip_address'],
                'user_agent' => $this->applicationData['user_agent'],
                'application_source' => $this->applicationData['application_source'],
                'status' => 'submitted',
                'submitted_at' => now(),
            ];

            // Validate required fields (be more lenient with some fields that might be optional for certain loan types)
            $criticalFields = ['first_name', 'last_name', 'national_id', 'phone_number', 'email', 'current_address'];
            foreach ($criticalFields as $field) {
                if (empty($applicationData[$field])) {
                    throw new \Exception("Required field '{$field}' is missing or empty.");
                }
            }

            // Ensure employment_status is set and valid (handle null, empty string, and invalid values)
            $validEmploymentStatuses = ['employed', 'self_employed', 'unemployed', 'retired', 'student'];
            $employmentStatus = trim($applicationData['employment_status'] ?? '');
            
            if (empty($employmentStatus) || !in_array($employmentStatus, $validEmploymentStatuses)) {
                \Log::warning('Invalid or missing employment_status, setting to unemployed', [
                    'employment_status' => $applicationData['employment_status'] ?? 'NOT SET',
                    'trimmed_value' => $employmentStatus,
                    'user_id' => Auth::id(),
                    'user_profile_employment_status' => $this->userProfile->employment_status ?? 'NOT SET',
                ]);
                $applicationData['employment_status'] = 'unemployed';
            } else {
                $applicationData['employment_status'] = $employmentStatus;
            }

            // Ensure total_monthly_income is set and is numeric (allow 0 as valid value)
            if (!isset($applicationData['total_monthly_income']) || !is_numeric($applicationData['total_monthly_income'])) {
                // Calculate from individual income sources if total is missing
                $calculatedTotal = (float)($applicationData['monthly_salary'] ?? 0) + 
                                  (float)($applicationData['other_monthly_income'] ?? 0) + 
                                  (float)($applicationData['monthly_business_income'] ?? 0);
                $applicationData['total_monthly_income'] = $calculatedTotal;
            }

            // Ensure it's at least 0 (can't be negative)
            $applicationData['total_monthly_income'] = max(0, (float)$applicationData['total_monthly_income']);

            \Log::info('Application data before creation', [
                'total_monthly_income' => $applicationData['total_monthly_income'],
                'monthly_salary' => $applicationData['monthly_salary'] ?? null,
                'other_monthly_income' => $applicationData['other_monthly_income'] ?? null,
                'monthly_business_income' => $applicationData['monthly_business_income'] ?? null,
                'prequalification_monthly_income' => $this->prequalificationData['monthly_income'] ?? null,
            ]);

            \Log::info('Creating application with data:', [
                'data_count' => count($applicationData),
                'employment_status' => $applicationData['employment_status'] ?? 'NOT SET',
                'employment_status_type' => gettype($applicationData['employment_status'] ?? null),
                'gender' => $applicationData['gender'] ?? 'NOT SET',
                'marital_status' => $applicationData['marital_status'] ?? 'NOT SET',
            ]);

            // Create the main application record
            try {
                $this->finalApplication = Application::create($applicationData);
            } catch (\Exception $createException) {
                \Log::error('Application::create() failed', [
                    'error' => $createException->getMessage(),
                    'employment_status' => $applicationData['employment_status'] ?? 'NOT SET',
                    'all_data' => $applicationData,
                ]);
                throw $createException;
            }

            // Update document records with application ID
            foreach ($this->uploadedDocuments as $document) {
                ApplicationDocument::find($document['id'])->update([
                    'application_id' => $this->finalApplication->id
                ]);
            }

            // Create lender submissions based on selected products
            $selectedProducts = collect($this->prequalificationData['selected_product_details'] ?? []);

            if ($selectedProducts->isEmpty()) {
                throw new \Exception('No products selected for submission.');
            }

            foreach ($selectedProducts as $product) {
                // Check for existing submission to avoid duplicates
                $existingSubmission = ApplicationLenderSubmission::where('application_id', $this->finalApplication->id)
                    ->where('lender_id', $product['lender_id'])
                    ->first();

                if ($existingSubmission) {
                    \Log::warning('Skipping duplicate submission', [
                        'application_id' => $this->finalApplication->id,
                        'lender_id' => $product['lender_id']
                    ]);
                    continue;
                }

                $submission = ApplicationLenderSubmission::create([
                    'user_id' => Auth::id(),
                    'application_id' => $this->finalApplication->id,
                    'lender_id' => $product['lender_id'],
                    'loan_product_id' => $product['product_id'],
                    'status' => 'submitted',
                    'submitted_at' => now(),
                    'submission_data' => [
                        'requested_amount' => $this->applicationData['requested_amount'],
                        'requested_tenure' => $this->applicationData['requested_tenure_months'],
                        'estimated_monthly_payment' => $product['monthly_payment'] ?? 0,
                        'calculated_dsr' => $this->applicationData['debt_to_income_ratio'],
                        'prequalification_score' => $product['eligibility_score'] ?? 0,
                        'submission_reference' => 'APP-' . $this->finalApplication->application_number . '-' . $product['lender_id'],
                    ],
                ]);

                $this->submissionResults[] = [
                    'lender_name' => $product['lender_name'] ?? 'Unknown Lender',
                    'product_name' => $product['product_name'] ?? 'Unknown Product',
                    'status' => 'submitted',
                    'submission_id' => $submission->id,
                    'reference' => $submission->submission_data['submission_reference'],
                ];
            }

            DB::commit();

            // Update user's profile with the income they entered during application
            if (!empty($this->prequalificationData['monthly_income'])) {
                $user = Auth::user();
                if ($user->profile) {
                    $user->profile->update([
                        'total_monthly_income' => $this->prequalificationData['monthly_income'],
                        'existing_loan_payments' => $this->prequalificationData['existing_loans'] ?? 0,
                    ]);

                    \Log::info('Updated user profile with application income data', [
                        'user_id' => $user->id,
                        'monthly_income' => $this->prequalificationData['monthly_income'],
                        'existing_loans' => $this->prequalificationData['existing_loans'] ?? 0,
                    ]);
                }
            }

            // Clear session data
            session()->forget('prequalification_data');

            $this->currentStep = 3;
            session()->flash('success', 'Application submitted successfully to ' . count($selectedProducts) . ' lenders!');

        } catch (\Exception $e) {
            DB::rollBack();

            // Log detailed error information
            \Log::error('Application submission error: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'application_data' => $this->applicationData,
                'selected_products_count' => count($this->prequalificationData['selected_product_details'] ?? []),
                'uploaded_documents_count' => count($this->uploadedDocuments),
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);

            // Show more specific error message if possible
            $errorMessage = 'Error submitting application. Please try again.';

            if (str_contains($e->getMessage(), 'foreign key constraint')) {
                $errorMessage = 'Database constraint error. Please contact support.';
            } elseif (str_contains($e->getMessage(), 'unique constraint')) {
                $errorMessage = 'Duplicate application detected. Please try again.';
            } elseif (str_contains($e->getMessage(), 'required')) {
                $errorMessage = 'Missing required information. Please complete all fields.';
            } elseif (str_contains($e->getMessage(), 'employment_status')) {
                $errorMessage = 'Employment information is required. Please complete your employment details in your profile.';
            } elseif (str_contains($e->getMessage(), 'total_monthly_income') || str_contains($e->getMessage(), 'monthly income')) {
                // This shouldn't happen anymore since we're setting defaults, but just in case
                $errorMessage = 'Please enter your monthly income on the application form to proceed.';
            }

            session()->flash('error', $errorMessage);
        }

        $this->isSubmitting = false;
    }

    private function getSelectedLenders()
    {
        return collect($this->prequalificationData['selected_product_details'] ?? []);
    }

    private function getEstimatedProcessingTime()
    {
        $selectedLenders = $this->getSelectedLenders();
        if ($selectedLenders->isEmpty()) {
            return '5-7 days';
        }

        $avgDays = $selectedLenders->avg('approval_time_days');
        return ceil($avgDays) . ' days';
    }

    public function calculateRequiredDocumentsProgress()
    {
        $totalRequired = collect($this->requiredDocuments)->where('required', true)->count();
        $uploadedRequired = collect($this->requiredDocuments)->where('required', true)->where('uploaded', true)->count();
        
        return $totalRequired > 0 ? ($uploadedRequired / $totalRequired) * 100 : 0;
    }

    public function backToPrequalification()
    {
        // Restore session data and redirect
        session(['prequalification_data' => $this->prequalificationData]);
        return redirect()->route('loan-application.create');
    }

    public function viewApplications()
    {
        return redirect()->route('user.loan.application');
    }

    public function getDocumentRequestedBy($documentType)
    {
        $requestedBy = $this->requiredDocuments[$documentType]['requested_by'] ?? [];
        
        if (empty($requestedBy)) {
            return 'General requirement';
        }
        
        if (count($requestedBy) === 1) {
            return $requestedBy[0];
        }
        
        if (count($requestedBy) === count($this->getSelectedLenders())) {
            return 'All selected lenders';
        }
        
        return implode(', ', array_slice($requestedBy, 0, 2)) . (count($requestedBy) > 2 ? ' and ' . (count($requestedBy) - 2) . ' more' : '');
    }
}