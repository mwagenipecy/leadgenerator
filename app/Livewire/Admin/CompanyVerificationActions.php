<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\User;
use App\Models\CompanyVerificationDocument;
use App\Services\LogService;
use Illuminate\Support\Facades\Auth;

class CompanyVerificationActions extends Component
{
    public User $user;
    public $showVerifyModal = false;
    public $showRejectModal = false;
    public $verificationNotes = '';
    public $rejectionReason = '';

    public function mount(User $user)
    {
        $this->user = $user;
    }

    public function openVerifyModal()
    {
        $this->showVerifyModal = true;
        $this->verificationNotes = '';
    }

    public function openRejectModal()
    {
        $this->showRejectModal = true;
        $this->rejectionReason = '';
    }

    public function closeModals()
    {
        $this->showVerifyModal = false;
        $this->showRejectModal = false;
        $this->verificationNotes = '';
        $this->rejectionReason = '';
    }

    public function verifyCompany()
    {
        $this->validate([
            'verificationNotes' => 'nullable|string|max:1000',
        ]);

        try {
            $this->user->update([
                'company_verification_status' => 'verified',
                'company_verified_at' => now(),
                'company_verified_by' => Auth::id(),
                'company_verification_notes' => $this->verificationNotes,
            ]);

            // Update all documents status to verified
            $documentsCount = CompanyVerificationDocument::where('user_id', $this->user->id)
                ->update([
                    'status' => 'verified',
                    'verified_at' => now(),
                    'verified_by' => Auth::id(),
                ]);

            // Log company verification
            LogService::logCompanyVerified($this->user, $this->verificationNotes);

            session()->flash('success', 'Company verified successfully!');
            $this->closeModals();
            
            // Refresh the page
            return redirect()->route('admin.company.verification.show', $this->user->id);
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
            $this->user->update([
                'company_verification_status' => 'rejected',
                'company_verified_by' => Auth::id(),
                'company_verification_notes' => $this->rejectionReason,
            ]);

            // Update all documents status to rejected
            CompanyVerificationDocument::where('user_id', $this->user->id)
                ->update([
                    'status' => 'rejected',
                    'rejection_reason' => $this->rejectionReason,
                    'verified_by' => Auth::id(),
                ]);

            // Log company rejection
            LogService::logCompanyRejected($this->user, $this->rejectionReason);

            session()->flash('success', 'Company verification rejected.');
            $this->closeModals();
            
            // Refresh the page
            return redirect()->route('admin.company.verification.show', $this->user->id);
        } catch (\Exception $e) {
            session()->flash('error', 'Error rejecting company: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.company-verification-actions');
    }
}

