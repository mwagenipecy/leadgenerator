<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use App\Models\CompanyVerificationDocument;
use App\Services\LogService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class CompanyVerification extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = 'pending'; // pending, verified, rejected
    public $selectedUser = null;
    public $viewDocumentsModal = false;
    public $verifyModal = false;
    public $rejectModal = false;
    public $verificationNotes = '';
    public $rejectionReason = '';

    protected $paginationTheme = 'tailwind';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function viewDocuments($userId)
    {
        $this->selectedUser = User::with('companyVerificationDocuments')->find($userId);
        $this->viewDocumentsModal = true;
        
        // Log document viewing
        if ($this->selectedUser) {
            LogService::logCompanyDocumentsViewed($this->selectedUser);
        }
    }

    public function openVerifyModal($userId)
    {
        $this->selectedUser = User::with('companyVerificationDocuments')->find($userId);
        $this->verificationNotes = '';
        $this->verifyModal = true;
    }

    public function openRejectModal($userId)
    {
        $this->selectedUser = User::with('companyVerificationDocuments')->find($userId);
        $this->rejectionReason = '';
        $this->rejectModal = true;
    }

    public function verifyCompany()
    {
        $this->validate([
            'verificationNotes' => 'nullable|string|max:1000',
        ]);

        try {
            $this->selectedUser->update([
                'company_verification_status' => 'verified',
                'company_verified_at' => now(),
                'company_verified_by' => Auth::id(),
                'company_verification_notes' => $this->verificationNotes,
            ]);

            // Update all documents status to verified
            CompanyVerificationDocument::where('user_id', $this->selectedUser->id)
                ->update([
                    'status' => 'verified',
                    'verified_at' => now(),
                    'verified_by' => Auth::id(),
                ]);

            // Log company verification
            LogService::logCompanyVerified($this->selectedUser, $this->verificationNotes);

            session()->flash('success', 'Company verified successfully!');
            $this->verifyModal = false;
            $this->selectedUser = null;
            $this->verificationNotes = '';
        } catch (\Exception $e) {
            session()->flash('error', 'Error verifying company: ' . $e->getMessage());
        }
    }

    public function rejectCompany()
    {
        $this->validate([
            'rejectionReason' => 'required|string|max:1000',
        ]);

        try {
            $this->selectedUser->update([
                'company_verification_status' => 'rejected',
                'company_verified_by' => Auth::id(),
                'company_verification_notes' => $this->rejectionReason,
            ]);

            // Update all documents status to rejected
            CompanyVerificationDocument::where('user_id', $this->selectedUser->id)
                ->update([
                    'status' => 'rejected',
                    'rejection_reason' => $this->rejectionReason,
                    'verified_by' => Auth::id(),
                ]);

            // Log company rejection
            LogService::logCompanyRejected($this->selectedUser, $this->rejectionReason);

            session()->flash('success', 'Company verification rejected.');
            $this->rejectModal = false;
            $this->selectedUser = null;
            $this->rejectionReason = '';
        } catch (\Exception $e) {
            session()->flash('error', 'Error rejecting company: ' . $e->getMessage());
        }
    }

    public function downloadDocument($documentId)
    {
        $document = CompanyVerificationDocument::with('user')->find($documentId);
        
        if (!$document || !Storage::disk('public')->exists($document->file_path)) {
            session()->flash('error', 'Document not found.');
            return;
        }

        // Log document download
        if ($document->user) {
            LogService::logCompanyDocumentDownloaded($document->user, $document);
        }

        return Storage::disk('public')->download($document->file_path, $document->document_name);
    }

    public function closeModals()
    {
        $this->viewDocumentsModal = false;
        $this->verifyModal = false;
        $this->rejectModal = false;
        $this->selectedUser = null;
        $this->verificationNotes = '';
        $this->rejectionReason = '';
    }

    public function render()
    {
        $query = User::where('registration_type', 'company')
            ->whereNotNull('company_verification_status');

        // Apply search
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('company_name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%')
                  ->orWhere('first_name', 'like', '%' . $this->search . '%')
                  ->orWhere('last_name', 'like', '%' . $this->search . '%')
                  ->orWhere('company_tin', 'like', '%' . $this->search . '%');
            });
        }

        // Apply status filter
        if ($this->statusFilter) {
            $query->where('company_verification_status', $this->statusFilter);
        }

        $companies = $query->with('companyVerificationDocuments')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.admin.company-verification', [
            'companies' => $companies,
        ]);
    }
}
