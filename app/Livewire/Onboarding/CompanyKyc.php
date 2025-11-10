<?php

namespace App\Livewire\Onboarding;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Models\User;
use App\Models\CompanyVerificationDocument;
use App\Models\NidaVerification;
use Livewire\WithFileUploads;

class CompanyKyc extends Component
{
    use WithFileUploads;

    public $user;
    public $isTanzania = false;
    public $step = 1; // 1: NIDA/Passport, 2: Documents, 3: Complete
    
    // Document uploads
    public $nidaVerificationCompleted = false;
    public $brelaDocument;
    public $tinCertificate;
    public $passportDocument;
    public $companyDocuments;
    public $personalKyc;
    
    // Uploaded documents
    public $uploadedDocuments = [];
    
    public function mount()
    {
        $this->user = Auth::user();
        
        // Check if user is company type
        if ($this->user->registration_type !== 'company') {
            return redirect()->route('dashboard');
        }
        
        // Check if already verified
        if ($this->user->isCompanyVerified()) {
            return redirect()->route('dashboard');
        }
        
        $this->isTanzania = $this->user->isFromTanzania();
        
        // Load existing documents
        $this->loadDocuments();
        
        // Check NIDA verification status for Tanzania
        $this->checkNidaStatus();
        
        // If there's a success message from NIDA verification, refresh status
        if (session()->has('success') && str_contains(session('success'), 'NIDA')) {
            $this->checkNidaStatus();
        }
    }
    
    public function updated($propertyName)
    {
        // Auto-refresh when component updates if returning from verification
        if ($propertyName === 'step' || $propertyName === 'nidaVerificationCompleted') {
            $this->checkNidaStatus();
        }
    }
    
    public function checkNidaStatus()
    {
        if ($this->isTanzania) {
            // Refresh user to get latest NIDA verification status
            $this->user->refresh();
            
            // Check if NIDA is verified - for company users, check both nida_number and company_contact_nida
            $this->nidaVerificationCompleted = $this->user->isNidaVerified() || 
                (!empty($this->user->company_contact_nida) && !empty($this->user->nida_verified_at));
            
            if ($this->nidaVerificationCompleted) {
                $this->step = 2;
            }
        } else {
            $this->step = 2; // Skip NIDA for non-Tanzania
        }
    }
    
    public function refreshStatus()
    {
        // Refresh NIDA status - called after returning from NIDA verification
        $this->checkNidaStatus();
        $this->loadDocuments();
    }

    protected function loadDocuments()
    {
        $documents = $this->user->companyVerificationDocuments;
        foreach ($documents as $doc) {
            $this->uploadedDocuments[$doc->document_type] = [
                'id' => $doc->id,
                'name' => $doc->document_name,
                'type' => $doc->document_type,
                'status' => $doc->status,
            ];
        }
    }

    public function uploadDocument($documentType)
    {
        try {
            $property = match($documentType) {
                'brela' => 'brelaDocument',
                'tin_certificate' => 'tinCertificate',
                'passport' => 'passportDocument',
                'company_documents' => 'companyDocuments',
                'personal_kyc' => 'personalKyc',
                default => null,
            };

            if (!$property) {
                session()->flash('error', 'Invalid document type.');
                \Log::error('Invalid document type in uploadDocument', ['documentType' => $documentType]);
                return;
            }

            if (!$this->$property) {
                session()->flash('error', 'Please select a file to upload.');
                return;
            }

            // Validate the file
            $this->validate([
                $property => 'required|file|max:5120|mimes:pdf,jpg,jpeg,png',
            ], [
                $property . '.required' => 'Please select a file to upload.',
                $property . '.max' => 'File size must not exceed 5MB.',
                $property . '.mimes' => 'File must be a PDF, JPG, JPEG, or PNG.',
            ]);

            $file = $this->$property;
            
            // Ensure storage directory exists
            $storagePath = storage_path('app/public/company-verification-documents');
            if (!file_exists($storagePath)) {
                File::makeDirectory($storagePath, 0755, true);
            }
            
            // Store the file
            $path = $file->store('company-verification-documents', 'public');
            
            if (!$path) {
                throw new \Exception('Failed to store file.');
            }
            
            $fileHash = hash_file('sha256', Storage::disk('public')->path($path));

            // Delete existing document of same type if exists
            $existingDoc = CompanyVerificationDocument::where('user_id', $this->user->id)
                ->where('document_type', $documentType)
                ->first();
            
            if ($existingDoc) {
                try {
                    Storage::disk('public')->delete($existingDoc->file_path);
                } catch (\Exception $e) {
                    \Log::warning('Failed to delete existing file', ['file_path' => $existingDoc->file_path, 'error' => $e->getMessage()]);
                }
                $existingDoc->delete();
            }

            // Create new document record
            $document = CompanyVerificationDocument::create([
                'user_id' => $this->user->id,
                'document_type' => $documentType,
                'document_name' => $file->getClientOriginalName(),
                'file_path' => $path,
                'file_type' => $file->getClientOriginalExtension(),
                'file_size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
                'status' => 'pending',
                'file_hash' => $fileHash,
            ]);

            // Reload documents to update the view
            $this->loadDocuments();

            // Reset file input
            $this->$property = null;

            session()->flash('success', 'Document uploaded successfully!');
            $this->dispatch('document-uploaded');
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            session()->flash('error', $e->getMessage());
            throw $e;
        } catch (\Exception $e) {
            \Log::error('Error uploading document', [
                'user_id' => $this->user->id ?? null,
                'document_type' => $documentType,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            session()->flash('error', 'Error uploading document: ' . $e->getMessage());
        }
    }

    public function removeDocument($documentType)
    {
        $document = CompanyVerificationDocument::where('user_id', $this->user->id)
            ->where('document_type', $documentType)
            ->first();

        if ($document) {
            Storage::disk('public')->delete($document->file_path);
            $document->delete();
            unset($this->uploadedDocuments[$documentType]);
            session()->flash('success', 'Document removed successfully!');
        }
    }

    public function completeKyc()
    {
        // Validate all required documents are uploaded
        $requiredDocs = $this->isTanzania 
            ? ['brela', 'tin_certificate'] 
            : ['passport', 'company_documents', 'personal_kyc'];

        $missingDocs = [];
        foreach ($requiredDocs as $docType) {
            if (!isset($this->uploadedDocuments[$docType])) {
                $missingDocs[] = $docType;
            }
        }

        if (!empty($missingDocs)) {
            session()->flash('error', 'Please upload all required documents.');
            return;
        }

        // For Tanzania, also check NIDA verification
        if ($this->isTanzania && !$this->nidaVerificationCompleted) {
            session()->flash('error', 'Please complete NIDA verification first.');
            return;
        }

        // All documents uploaded, show completion message
        $this->step = 3;
        session()->flash('success', 'All documents have been submitted successfully! Your company verification is pending admin review.');
    }

    public function startNidaVerification()
    {
        try {
            // Refresh user data
            $this->user->refresh();
            
            // Check if company_contact_nida is set (required for Tanzania company users)
            if ($this->isTanzania && empty($this->user->company_contact_nida)) {
                session()->flash('error', 'NIDA number is missing. Please contact support or complete registration again.');
                $this->dispatch('error-message', message: 'NIDA number is missing. Please contact support or complete registration again.');
                return;
            }
            
            // Ensure nida_number is set for company users before starting verification
            if ($this->isTanzania && empty($this->user->nida_number) && !empty($this->user->company_contact_nida)) {
                try {
                    $this->user->update([
                        'nida_number' => $this->user->company_contact_nida
                    ]);
                    $this->user->refresh();
                } catch (\Exception $e) {
                    \Log::error('Failed to set NIDA number for company user', [
                        'user_id' => $this->user->id,
                        'error' => $e->getMessage()
                    ]);
                    session()->flash('error', 'Failed to set NIDA number: ' . $e->getMessage());
                    return;
                }
            }
            
            // Final check - ensure we have a NIDA number to proceed
            if (empty($this->user->nida_number)) {
                session()->flash('error', 'NIDA number is required to start verification. Please ensure your registration is complete.');
                return;
            }
            
            // Log the redirect attempt
            \Log::info('Company user starting NIDA verification', [
                'user_id' => $this->user->id,
                'nida_number' => $this->user->nida_number,
                'company_contact_nida' => $this->user->company_contact_nida
            ]);
            
            // Use JavaScript redirect for Livewire compatibility
            $this->dispatch('redirect', url: route('verification.options'));
            
        } catch (\Exception $e) {
            \Log::error('Error starting NIDA verification for company user', [
                'user_id' => $this->user->id ?? null,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            session()->flash('error', 'An error occurred while starting NIDA verification: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.onboarding.company-kyc')
            ->layout('components.layouts.kyc');
    }
}
