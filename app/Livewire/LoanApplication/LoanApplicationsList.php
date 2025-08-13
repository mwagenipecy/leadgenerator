<?php

namespace App\Livewire\LoanApplication;

use App\Models\Application;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

class LoanApplicationsList extends Component
{
    use WithPagination;

    // Search and filters
    public $search = '';
    public $statusFilter = 'all';
    public $sortBy = 'created_at';
    public $sortDirection = 'desc';

    // Toast notification properties
    public $showToast = false;
    public $toastMessage = '';
    public $toastType = 'success';

    // Modal properties
    public $showCancelModal = false;
    public $showDeleteModal = false;
    public $showDownloadModal = false;
    public $applicationToCancel = null;
    public $applicationToDelete = null;
    public $applicationToDownload = null;

    // Pagination
    protected $paginationTheme = 'tailwind';

    // Real-time search
    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedStatusFilter()
    {
        $this->resetPage();
    }

    // Sort methods
    public function sortBy($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
        $this->resetPage();
    }

    public function toggleSortDirection()
    {
        $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        $this->resetPage();
    }

    // Clear filters
    public function clearFilters()
    {
        $this->search = '';
        $this->statusFilter = 'all';
        $this->resetPage();
        $this->showToastMessage('Filters cleared');
    }

    // Actions
    public function viewApplication($id)
    {
        return redirect()->route('loan-applications.view', $id);
    }

    public function editApplication($id)
    {
        return redirect()->route('loan-applications.edit', $id);
    }

    public function selectLenders($id)
    {
        return redirect()->route('loan-applications.lenders', $id);
    }

    // Cancel Modal Methods
    public function showCancelConfirmation($id)
    {
        $this->applicationToCancel = Application::where('user_id', Auth::id())
            ->with(['lender'])
            ->findOrFail($id);
        
        $this->showCancelModal = true;
    }

    public function closeCancelModal()
    {
        $this->showCancelModal = false;
        $this->applicationToCancel = null;
    }

    public function confirmCancel()
    {
        try {
            if (!$this->applicationToCancel) {
                throw new \Exception('Application not found');
            }

            // Check if user owns the application and it can be cancelled
            if ($this->applicationToCancel->user_id !== Auth::id() || 
                !in_array($this->applicationToCancel->status, ['draft', 'submitted', 'under_review'])) {
                throw new \Exception('Cannot cancel this application');
            }

            $applicationNumber = $this->applicationToCancel->application_number;
            
            $this->applicationToCancel->update([
                'status' => 'cancelled',
                'cancelled_at' => now(),
                'cancellation_reason' => 'Cancelled by user'
            ]);
            
            $this->closeCancelModal();
            $this->showToastMessage("Application {$applicationNumber} cancelled successfully!");
            
            // Refresh the applications list
            $this->render();
            
        } catch (\Exception $e) {
            $this->showToastMessage('Error cancelling application: ' . $e->getMessage(), 'error');
            $this->closeCancelModal();
        }
    }

    // Delete Modal Methods
    public function showDeleteConfirmation($id)
    {
        $this->applicationToDelete = Application::where('user_id', Auth::id())
            ->with(['documents'])
            ->findOrFail($id);
        
        $this->showDeleteModal = true;
    }

    public function closeDeleteModal()
    {
        $this->showDeleteModal = false;
        $this->applicationToDelete = null;
    }

    public function confirmDelete()
    {
        try {
            if (!$this->applicationToDelete) {
                throw new \Exception('Application not found');
            }

            // Check if user owns the application and it's a draft
            if ($this->applicationToDelete->user_id !== Auth::id() || 
                $this->applicationToDelete->status !== 'draft') {
                throw new \Exception('Cannot delete this application');
            }

            $applicationNumber = $this->applicationToDelete->application_number;
            
            // Delete associated documents
            foreach ($this->applicationToDelete->documents as $document) {
                if (Storage::disk('public')->exists($document->file_path)) {
                    Storage::disk('public')->delete($document->file_path);
                }
            }
            
            $this->applicationToDelete->delete();
            
            $this->closeDeleteModal();
            $this->showToastMessage("Draft application {$applicationNumber} deleted successfully!");
            
            // Refresh the applications list
            $this->render();
            
        } catch (\Exception $e) {
            $this->showToastMessage('Error deleting application: ' . $e->getMessage(), 'error');
            $this->closeDeleteModal();
        }
    }

    // Download Methods
    public function downloadApplication($id)
    {
        try {
            $this->showDownloadModal = true;
            
            $application = Application::where('user_id', Auth::id())
                ->with(['lender', 'loanProduct', 'documents'])
                ->findOrFail($id);

            // Generate PDF
            $pdf = $this->generateApplicationPDF($application);
            $filename = "loan-application-{$application->application_number}.pdf";
            
            $this->showDownloadModal = false;
            
            // Return download response
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

    public function exportApplications()
    {
        try {
            $this->showDownloadModal = true;
            
            $applications = Application::where('user_id', Auth::id())
                ->when($this->search, function ($query) {
                    $query->where(function ($q) {
                        $q->where('application_number', 'like', '%' . $this->search . '%')
                          ->orWhere('first_name', 'like', '%' . $this->search . '%')
                          ->orWhere('last_name', 'like', '%' . $this->search . '%')
                          ->orWhereHas('lender', function ($lenderQuery) {
                              $lenderQuery->where('company_name', 'like', '%' . $this->search . '%');
                          });
                    });
                })
                ->when($this->statusFilter !== 'all', function ($query) {
                    $query->where('status', $this->statusFilter);
                })
                ->with(['lender', 'loanProduct'])
                ->orderBy($this->sortBy, $this->sortDirection)
                ->get();

            if ($applications->isEmpty()) {
                $this->showDownloadModal = false;
                $this->showToastMessage('No applications found to export', 'error');
                return;
            }

            // Generate PDF with all applications
            $pdf = $this->generateBulkExportPDF($applications);
            $filename = "my-loan-applications-" . now()->format('Y-m-d') . ".pdf";
            
            $this->showDownloadModal = false;
            
            return response()->streamDownload(function () use ($pdf) {
                echo $pdf->stream();
            }, $filename, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"'
            ]);
            
        } catch (\Exception $e) {
            $this->showDownloadModal = false;
            $this->showToastMessage('Error exporting applications: ' . $e->getMessage(), 'error');
        }
    }

    // PDF Generation Methods
    private function generateApplicationPDF($application)
    {
        $data = [
            'application' => $application,
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

    private function generateBulkExportPDF($applications)
    {
        $data = [
            'applications' => $applications,
            'filters' => [
                'search' => $this->search,
                'status' => $this->statusFilter,
                'sort_by' => $this->sortBy,
                'sort_direction' => $this->sortDirection
            ],
            'generated_at' => now(),
            'user' => Auth::user(),
            'summary' => [
                'total_count' => $applications->count(),
                'total_amount' => $applications->sum('requested_amount'),
                'status_breakdown' => $applications->groupBy('status')->map->count(),
                'average_amount' => $applications->avg('requested_amount')
            ]
        ];

        return Pdf::loadView('loan-applications.pdf.bulk-export', $data)
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'dpi' => 150,
                'defaultFont' => 'sans-serif',
                'isHtml5ParserEnabled' => true,
                'isPhpEnabled' => true
            ]);
    }

    public function copyApplicationNumber($applicationNumber)
    {
        // This will be handled by JavaScript clipboard API
        $this->showToastMessage("Application number {$applicationNumber} copied to clipboard!");
        
        // Dispatch browser event for clipboard copy
        $this->dispatch('copy-to-clipboard', ['text' => $applicationNumber]);
    }

    // Refresh data (for real-time updates)
    public function refreshData()
    {
        // This method can be called periodically to refresh data
        $this->render();
    }

    // Toast notification helper
    private function showToastMessage($message, $type = 'success')
    {
        $this->toastMessage = $message;
        $this->toastType = $type;
        $this->showToast = true;
        
        // Auto-hide toast after 4 seconds
        $this->dispatch('hide-toast-after-delay');
    }

    public function hideToast()
    {
        $this->showToast = false;
        $this->toastMessage = '';
        $this->toastType = 'success';
    }

    // Bulk actions (for future enhancement)
    public function bulkCancel($applicationIds)
    {
        try {
            $applications = Application::where('user_id', Auth::id())
                ->whereIn('id', $applicationIds)
                ->whereIn('status', ['submitted', 'under_review'])
                ->get();
            
            foreach ($applications as $application) {
                $application->update([
                    'status' => 'cancelled',
                    'cancelled_at' => now(),
                    'cancellation_reason' => 'Bulk cancellation by user'
                ]);
            }
            
            $count = $applications->count();
            $this->showToastMessage("{$count} applications cancelled successfully!");
            
        } catch (\Exception $e) {
            $this->showToastMessage('Error performing bulk cancellation: ' . $e->getMessage(), 'error');
        }
    }

    public function bulkDelete($applicationIds)
    {
        try {
            $applications = Application::where('user_id', Auth::id())
                ->whereIn('id', $applicationIds)
                ->where('status', 'draft')
                ->with('documents')
                ->get();
            
            foreach ($applications as $application) {
                // Delete associated documents
                foreach ($application->documents as $document) {
                    if (Storage::disk('public')->exists($document->file_path)) {
                        Storage::disk('public')->delete($document->file_path);
                    }
                }
                $application->delete();
            }
            
            $count = $applications->count();
            $this->showToastMessage("{$count} draft applications deleted successfully!");
            
        } catch (\Exception $e) {
            $this->showToastMessage('Error performing bulk deletion: ' . $e->getMessage(), 'error');
        }
    }

    // Statistics for dashboard
    public function getApplicationStatistics()
    {
        $userId = Auth::id();
        
        return [
            'total_applications' => Application::where('user_id', $userId)->count(),
            'total_amount_requested' => Application::where('user_id', $userId)->sum('requested_amount'),
            'average_amount' => Application::where('user_id', $userId)->avg('requested_amount'),
            'success_rate' => $this->calculateSuccessRate($userId),
            'pending_count' => Application::where('user_id', $userId)
                ->whereIn('status', ['submitted', 'under_review'])
                ->count(),
            'approved_count' => Application::where('user_id', $userId)
                ->where('status', 'approved')
                ->count(),
            'disbursed_amount' => Application::where('user_id', $userId)
                ->where('status', 'disbursed')
                ->sum('requested_amount'),
        ];
    }

    private function calculateSuccessRate($userId)
    {
        $total = Application::where('user_id', $userId)
            ->whereIn('status', ['approved', 'disbursed', 'rejected'])
            ->count();
            
        if ($total === 0) return 0;
        
        $successful = Application::where('user_id', $userId)
            ->whereIn('status', ['approved', 'disbursed'])
            ->count();
            
        return round(($successful / $total) * 100, 1);
    }

    public function render()
    {
        $applications = Application::where('user_id', Auth::id())
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('application_number', 'like', '%' . $this->search . '%')
                      ->orWhere('first_name', 'like', '%' . $this->search . '%')
                      ->orWhere('last_name', 'like', '%' . $this->search . '%')
                      ->orWhereHas('lender', function ($lenderQuery) {
                          $lenderQuery->where('company_name', 'like', '%' . $this->search . '%');
                      });
                });
            })
            ->when($this->statusFilter !== 'all', function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->with(['lender', 'loanProduct'])
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate(10);

        $statusCounts = $this->getStatusCounts();

        return view('livewire.loan-application.loan-applications-list', [
            'applications' => $applications,
            'statusCounts' => $statusCounts,
        ]);
    }

    private function getStatusCounts()
    {
        return Application::where('user_id', Auth::id())
            ->selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();
    }

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

    // Real-time updates for pending applications
    public function getListeners()
    {
        return [
            'refreshApplications' => 'refreshData',
            'hide-toast-after-delay' => 'hideToast',
        ];
    }
}