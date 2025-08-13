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
        
        if (empty($this->prequalificationData)) {
            session()->flash('error', 'No pre-qualification data found. Please start from pre-qualification.');
            return redirect()->route('loan-application.prequalify');
        }

        // Load user profile
        $this->userProfile = Auth::user()->profile;
        
        if (!$this->userProfile || $this->userProfile->profile_completion_percentage < 70) {
            session()->flash('error', 'Please complete your profile first.');
            return redirect()->route('loan-application.profile');
        }

        // Prepare application data (copy from profile for backup)
        $this->prepareApplicationData();
        
        // Set required documents based on employment status
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
        $this->applicationData = [
            // Loan Details (from pre-qualification)
            'loan_category' => $this->prequalificationData['loan_category'],
            'loan_type' => $this->prequalificationData['loan_type'],
            'requested_amount' => $this->prequalificationData['requested_amount'],
            'requested_tenure_months' => $this->prequalificationData['requested_tenure'],
            'loan_purpose' => 'General purpose', // Can be updated if needed
            
            // Personal Information (from profile)
            'first_name' => $this->userProfile->first_name,
            'middle_name' => $this->userProfile->middle_name,
            'last_name' => $this->userProfile->last_name,
            'date_of_birth' => $this->userProfile->date_of_birth,
            'gender' => $this->userProfile->gender,
            'marital_status' => $this->userProfile->marital_status,
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
            
            // Employment Information
            'employment_status' => $this->userProfile->employment_status,
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
            
            // Financial Information
            'monthly_salary' => $this->userProfile->monthly_salary,
            'other_monthly_income' => $this->userProfile->other_monthly_income,
            'monthly_business_income' => $this->userProfile->monthly_business_income,
            'total_monthly_income' => $this->userProfile->total_monthly_income,
            'monthly_expenses' => $this->userProfile->monthly_expenses,
            'existing_loan_payments' => $this->userProfile->existing_loan_payments,
            'credit_score' => $this->userProfile->credit_score,
            'has_bad_credit_history' => $this->userProfile->has_bad_credit_history,
            
            // Bank Information
            'has_bank_account' => $this->userProfile->has_bank_account,
            'bank_name' => $this->userProfile->bank_name,
            'account_number' => $this->userProfile->account_number,
            'account_name' => $this->userProfile->account_name,
            'account_type' => $this->userProfile->account_type,
            'years_with_bank' => $this->userProfile->years_with_bank,
            
            // Emergency Contact
            'emergency_contact_name' => $this->userProfile->emergency_contact_name,
            'emergency_contact_relationship' => $this->userProfile->emergency_contact_relationship,
            'emergency_contact_phone' => $this->userProfile->emergency_contact_phone,
            'emergency_contact_address' => $this->userProfile->emergency_contact_address,
            'preferred_disbursement_method' => $this->userProfile->preferred_disbursement_method,
            
            // Calculated values
            'debt_to_income_ratio' => $this->prequalificationData['calculated_dsr'],
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
        $this->requiredDocuments = [
            'national_id' => [
                'name' => 'National ID',
                'description' => 'Clear photo of both sides of your National ID',
                'required' => true,
                'uploaded' => false
            ]
        ];

        // Employment-specific documents
        if ($this->userProfile->employment_status === 'employed') {
            $this->requiredDocuments['salary_slip'] = [
                'name' => 'Salary Slip',
                'description' => 'Most recent 3 months salary slips',
                'required' => true,
                'uploaded' => false
            ];
            $this->requiredDocuments['employment_letter'] = [
                'name' => 'Employment Letter',
                'description' => 'Employment confirmation letter from your employer',
                'required' => false,
                'uploaded' => false
            ];
        }

        if ($this->userProfile->employment_status === 'self_employed') {
            $this->requiredDocuments['business_license'] = [
                'name' => 'Business License',
                'description' => 'Valid business license or registration certificate',
                'required' => true,
                'uploaded' => false
            ];
            $this->requiredDocuments['tax_certificate'] = [
                'name' => 'Tax Certificate',
                'description' => 'Tax compliance certificate or recent tax returns',
                'required' => false,
                'uploaded' => false
            ];
        }

        // Bank statement (always required)
        $this->requiredDocuments['bank_statement'] = [
            'name' => 'Bank Statement',
            'description' => 'Last 6 months bank statements',
            'required' => true,
            'uploaded' => false
        ];

        // Collateral documents (if secured loan)
        if ($this->prequalificationData['loan_type'] === 'secured') {
            $this->requiredDocuments['collateral_documents'] = [
                'name' => 'Collateral Documents',
                'description' => 'Property title, vehicle logbook, or other collateral documents',
                'required' => true,
                'uploaded' => false
            ];
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

        if($this->currentStep==3){

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

            // Create the main application record
            $this->finalApplication = Application::create([
                'user_id' => Auth::id(),
                'loan_category' => $this->applicationData['loan_category'],
                'loan_type' => $this->applicationData['loan_type'],
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
                'employment_status' => $this->applicationData['employment_status'],
                'employer_name' => $this->applicationData['employer_name'],
                'job_title' => $this->applicationData['job_title'],
                'employment_sector' => $this->applicationData['employment_sector'],
                'years_of_employment' => $this->applicationData['years_of_employment'],
                'months_with_current_employer' => $this->applicationData['months_with_current_employer'],
                'business_name' => $this->applicationData['business_name'],
                'business_type' => $this->applicationData['business_type'],
                'business_registration_number' => $this->applicationData['business_registration_number'],
                'years_in_business' => $this->applicationData['years_in_business'],
                'business_address' => $this->applicationData['business_address'],
                'monthly_salary' => $this->applicationData['monthly_salary'],
                'other_monthly_income' => $this->applicationData['other_monthly_income'],
                'monthly_business_income' => $this->applicationData['monthly_business_income'],
                'total_monthly_income' => $this->applicationData['total_monthly_income'],
                'monthly_expenses' => $this->applicationData['monthly_expenses'],
                'existing_loan_payments' => $this->applicationData['existing_loan_payments'],
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
            ]);

            // Update document records with application ID
            foreach ($this->uploadedDocuments as $document) {
                ApplicationDocument::find($document['id'])->update([
                    'application_id' => $this->finalApplication->id
                ]);
            }

            // Create lender submissions
            foreach ($this->prequalificationData['selected_lenders'] as $lenderId) {
                // Find matching product for this lender
                $matchingProduct = collect($this->prequalificationData['matching_products'])
                    ->where('lender_id', $lenderId)
                    ->first();

                $submission = ApplicationLenderSubmission::create([
                    'user_id' => Auth::id(),
                    'application_id' => $this->finalApplication->id,
                    'lender_id' => $lenderId,
                    'loan_product_id' => $matchingProduct['product_id'] ?? null,
                    'status' => 'submitted',
                    'submitted_at' => now(),
                    'submission_data' => [
                        'requested_amount' => $this->applicationData['requested_amount'],
                        'requested_tenure' => $this->applicationData['requested_tenure_months'],
                        'estimated_monthly_payment' => $matchingProduct['monthly_payment'] ?? 0,
                        'calculated_dsr' => $this->applicationData['debt_to_income_ratio'],
                        'prequalification_score' => $matchingProduct['eligibility_score'] ?? 0,
                        'submission_reference' => 'APP-' . $this->finalApplication->application_number . '-' . $lenderId,
                    ],
                ]);

                $this->submissionResults[] = [
                    'lender_name' => $matchingProduct['lender_name'] ?? 'Unknown Lender',
                    'product_name' => $matchingProduct['product_name'] ?? 'Unknown Product',
                    'status' => 'submitted',
                    'submission_id' => $submission->id,
                    'reference' => $submission->submission_data['submission_reference'],
                ];
            }

            DB::commit();

            // Clear session data
            session()->forget('prequalification_data');

            $this->currentStep = 3;
            session()->flash('success', 'Application submitted successfully to ' . count($this->prequalificationData['selected_lenders']) . ' lenders!');

        } catch (\Exception $e) {
            DB::rollBack();

            dd($e->getMessage());
            session()->flash('error', 'Error submitting application. Please try again.');
            \Log::error('Application submission error: ' . $e->getMessage());
        }

        $this->isSubmitting = false;
    }

    private function getSelectedLenders()
    {
        return collect($this->prequalificationData['matching_products'] ?? [])
            ->whereIn('lender_id', $this->prequalificationData['selected_lenders'] ?? [])
            ->values()
            ->toArray();
    }

    private function getEstimatedProcessingTime()
    {
        $selectedLenders = $this->getSelectedLenders();
        if (empty($selectedLenders)) {
            return '5-7 days';
        }

        $avgDays = collect($selectedLenders)->avg('approval_time_days');
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
}