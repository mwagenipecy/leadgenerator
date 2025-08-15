<?php

namespace App\Livewire\LoanApplication;

use App\Models\Application;
use App\Models\ApplicationLenderSubmission;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class LoanApplicationView extends Component
{
    public Application $application;
    
    // Modal states
    public $showCancelModal = false;
    public $showDownloadModal = false;
    public $showDocumentModal = false;
    public $selectedDocument = null;
    
    // Toast notification properties
    public $showToast = false;
    public $toastMessage = '';
    public $toastType = 'success';

    // Tab state
    public $activeTab = 'overview';

    public function mount($application)
    {
        // Load the application with all relationships
        $this->application = Application::where('user_id', Auth::id())
            ->with([
                'lender', 
                'loanProduct', 
                'documents' => function($query) {
                    $query->orderBy('created_at', 'desc');
                }
            ])
            ->findOrFail($application->id);
    }

    // Navigation methods
    public function backToList()
    {
        return redirect()->route('user.loan.application');
    }

    public function editApplication()
    {
        if ($this->application->status !== 'draft') {
            $this->showToastMessage('Only draft applications can be edited', 'error');
            return;
        }
        
        return redirect()->route('loan-applications.edit', $this->application->id);
    }

    public function selectLenders()
    {
        if ($this->application->status !== 'submitted' || $this->application->lender_id) {
            $this->showToastMessage('Lender selection not available for this application', 'error');
            return;
        }
        
        return redirect()->route('loan-applications.lenders', $this->application->id);
    }

    // Tab switching
    public function switchTab($tab)
    {
        $this->activeTab = $tab;
    }

    // Cancel modal methods
    public function showCancelConfirmation()
    {
        if (!in_array($this->application->status, ['draft', 'submitted', 'under_review'])) {
            $this->showToastMessage('This application cannot be cancelled', 'error');
            return;
        }
        
        $this->showCancelModal = true;
    }

    public function closeCancelModal()
    {
        $this->showCancelModal = false;
    }

    public function confirmCancel()
    {
        try {
            if (!in_array($this->application->status, ['draft', 'submitted', 'under_review'])) {
                throw new \Exception('Cannot cancel this application');
            }

            $this->application->update([
                'status' => 'cancelled',
                'cancelled_at' => now(),
                'cancellation_reason' => 'Cancelled by user'
            ]);

            ApplicationLenderSubmission::where('application_id', $this->application->id)
                ->update(['status' => 'cancelled']);
            
            $this->closeCancelModal();
            $this->showToastMessage('Application cancelled successfully!');
            
            // Refresh the application data
            $this->mount($this->application->id);
            
        } catch (\Exception $e) {
            $this->showToastMessage('Error cancelling application: ' . $e->getMessage(), 'error');
            $this->closeCancelModal();
        }
    }

    // Download methods
    public function downloadApplication()
    {
        try {
            $this->showDownloadModal = true;
            
            // Generate PDF
            $pdf = $this->generateApplicationPDF();
            $filename = "loan-application-{$this->application->application_number}.pdf";
            
            $this->showDownloadModal = false;
            
            return response()->streamDownload(function () use ($pdf) {
                echo $pdf->stream();
            }, $filename, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"'
            ]);
            
        } catch (\Exception $e) {
            $this->showDownloadModal = false;
            $this->showToastMessage('Error downloading application: ' . $e->getMessage(), 'error');
        }
    }

    // Document methods
    public function viewDocument($documentId)
    {
        $document = $this->application->documents()->findOrFail($documentId);
        
        if (!Storage::disk('public')->exists($document->file_path)) {
            $this->showToastMessage('Document file not found', 'error');
            return;
        }
        
        $this->selectedDocument = $document;
        $this->showDocumentModal = true;
    }

    public function downloadDocument($documentId)
    {
        try {
            $document = $this->application->documents()->findOrFail($documentId);
            
            if (!Storage::disk('public')->exists($document->file_path)) {
                $this->showToastMessage('Document file not found', 'error');
                return;
            }
            
            return Storage::disk('public')->download(
                $document->file_path, 
                $document->document_name
            );
            
        } catch (\Exception $e) {
            $this->showToastMessage('Error downloading document: ' . $e->getMessage(), 'error');
        }
    }

    public function closeDocumentModal()
    {
        $this->showDocumentModal = false;
        $this->selectedDocument = null;
    }

    // Copy application number
    public function copyApplicationNumber()
    {
        $this->showToastMessage("Application number {$this->application->application_number} copied to clipboard!");
        $this->dispatch('copy-to-clipboard', ['text' => $this->application->application_number]);
    }

    // Refresh application data
    public function refreshApplication()
    {
        $this->mount($this->application->id);
        $this->showToastMessage('Application data refreshed');
    }

    // PDF generation
    private function generateApplicationPDF()
    {
        $data = [
            'application' => $this->application,
            'generated_at' => now(),
            'user' => Auth::user()
        ];

        return Pdf::loadView('loan-applications.pdf.single', $data)
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'dpi' => 150,
                'defaultFont' => 'sans-serif',
                'isHtml5ParserEnabled' => true,
                'isPhpEnabled' => true
            ]);
    }

    // Helper methods
    public function getStatusBadgeClass($status)
    {
        return match($status) {
            'draft' => 'bg-gray-100 text-gray-800',
            'submitted' => 'bg-blue-100 text-blue-800',
            'under_review' => 'bg-yellow-100 text-yellow-800',
            'approved' => 'bg-green-100 text-green-800',
            'rejected' => 'bg-red-100 text-red-800',
            'disbursed' => 'bg-purple-100 text-purple-800',
            'cancelled' => 'bg-gray-100 text-gray-600',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    public function getProgressPercentage($status)
    {
        return match($status) {
            'draft' => 20,
            'submitted' => 40,
            'under_review' => 60,
            'approved' => 80,
            'disbursed' => 100,
            'rejected' => 100,
            'cancelled' => 100,
            default => 0
        };
    }

    public function getStatusIcon($status)
    {
        return match($status) {
            'draft' => 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z',
            'submitted' => 'M12 19l9 2-9-18-9 18 9-2zm0 0v-8',
            'under_review' => 'M15 12a3 3 0 11-6 0 3 3 0 016 0z',
            'approved' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
            'rejected' => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z',
            'disbursed' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1',
            'cancelled' => 'M6 18L18 6M6 6l12 12',
            default => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'
        };
    }

    public function calculateEstimatedPayment()
    {
        if (!$this->application->requested_amount || !$this->application->requested_tenure_months) {
            return 0;
        }

        $interestRate = 0.15; // Default 15%
        if ($this->application->loanProduct) {
            $interestRate = $this->application->loanProduct->interest_rate_min / 100;
        }

        $monthlyRate = $interestRate / 12;
        $numberOfPayments = $this->application->requested_tenure_months;

        return $this->application->requested_amount * 
            ($monthlyRate * pow(1 + $monthlyRate, $numberOfPayments)) / 
            (pow(1 + $monthlyRate, $numberOfPayments) - 1);
    }

    public function getNetAvailableIncome()
    {
        return $this->application->total_monthly_income - 
               $this->application->monthly_expenses - 
               $this->application->existing_loan_payments;
    }

    // Toast notification helper
    private function showToastMessage($message, $type = 'success')
    {
        $this->toastMessage = $message;
        $this->toastType = $type;
        $this->showToast = true;
        
        $this->dispatch('hide-toast-after-delay');
    }

    public function hideToast()
    {
        $this->showToast = false;
        $this->toastMessage = '';
        $this->toastType = 'success';
    }

    // Listeners
    public function getListeners()
    {
        return [
            'hide-toast-after-delay' => 'hideToast',
            'refresh-application' => 'refreshApplication',
        ];
    }

    public function render()
    {
        return view('livewire.loan-application.loan-application-view');
    }
}