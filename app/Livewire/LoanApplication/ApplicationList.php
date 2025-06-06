<?php

namespace App\Livewire\LoanApplication;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Application;
use App\Models\LoanProduct;
use App\Models\Lender;
use Illuminate\Support\Facades\Auth;

class ApplicationList extends Component
{
    use WithPagination;

    // Search and filters
    public $search = '';
    public $statusFilter = 'all';
    public $sortBy = 'created_at';
    public $sortDirection = 'desc';
    public $dateRange = 'all';
    public $amountRange = 'all';
    public $dsrRange = 'all';
    public $productFilter = 'all';
    public $employmentFilter = 'all';

    public $tabName;
    
    // View states
    public $currentStep = 'list';
    public $selectedApplication = null;
    public $viewMode = 'grid'; // grid, table, detailed
    public $showFilters = false;
    
    // Bulk actions
    public $selectedApplications = [];
    public $selectAll = false;
    
    // Application management
    public $applicationNotes = '';
    public $rejectionReasons = [];
    public $approvalAmount = null;
    public $approvalTenure = null;
    public $approvalInterestRate = null;

    protected $paginationTheme = 'tailwind';

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => 'all'],
        'sortBy' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
        'dateRange' => ['except' => 'all'],
        'page' => ['except' => 1],
    ];

    public function mount()
    {
        // Initialize based on user role/lender
        if (Auth::user()->role === 'lender') {
            // Set lender-specific defaults
        }
    }

    // Search and filtering
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingDateRange()
    {
        $this->resetPage();
    }

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

    public function clearFilters()
    {
        $this->search = '';
        $this->statusFilter = 'all';
        $this->dateRange = 'all';
        $this->amountRange = 'all';
        $this->dsrRange = 'all';
        $this->productFilter = 'all';
        $this->employmentFilter = 'all';
        $this->resetPage();
    }

    // View management
    public function toggleFilters()
    {
        $this->showFilters = !$this->showFilters;
    }

    public function setViewMode($mode)
    {
        $this->viewMode = $mode;
    }

    // Application actions
    public function viewApplication($applicationId)
    {
        $this->selectedApplication = Application::with([
            'loanProduct', 
            'lender', 
            'user', 
            'documents',
           // 'reviewedByUser'
        ])->find($applicationId);
        $this->currentStep = 'view';
    }


    public function switchTab($tabName){

$this->tabName=$tabName;
    }

    public function editApplication($applicationId)
    {
        $this->selectedApplication = Application::find($applicationId);
        $this->currentStep = 'edit';
    }

    public function startReview($applicationId)
    {
        $application = Application::find($applicationId);
        if ($application && $application->status === 'submitted') {
            $application->update([
                'status' => 'under_review',
                'reviewed_at' => now(),
                'reviewed_by' => Auth::id()
            ]);
            session()->flash('message', 'Application review started.');
        }
    }

    public function approveApplication($applicationId)
    {
        $application = Application::find($applicationId);
        if ($application && in_array($application->status, ['submitted', 'under_review'])) {
            $application->update([
                'status' => 'approved',
                'approved_at' => now(),
                'reviewed_by' => Auth::id(),
                'notes' => $this->applicationNotes
            ]);
            session()->flash('message', 'Application approved successfully.');
        }
        $this->resetApplicationForm();
    }

    public function rejectApplication($applicationId)
    {
        $application = Application::find($applicationId);
        if ($application && in_array($application->status, ['submitted', 'under_review'])) {
            $application->update([
                'status' => 'rejected',
                'reviewed_at' => now(),
                'reviewed_by' => Auth::id(),
                'rejection_reasons' => $this->rejectionReasons,
                'notes' => $this->applicationNotes
            ]);
            session()->flash('message', 'Application rejected.');
        }
        $this->resetApplicationForm();
    }

    public function markDisbursed($applicationId)
    {
        $application = Application::find($applicationId);
        if ($application && $application->status === 'approved') {
            $application->update([
                'status' => 'disbursed',
                'disbursed_at' => now(),
                'notes' => $this->applicationNotes
            ]);
            session()->flash('message', 'Application marked as disbursed.');
        }
        $this->resetApplicationForm();
    }

    // Bulk actions
    public function toggleSelectAll()
    {
        if ($this->selectAll) {
            $this->selectedApplications = $this->applications->pluck('id')->toArray();
        } else {
            $this->selectedApplications = [];
        }
    }

    public function toggleApplicationSelection($applicationId)
    {
        if (in_array($applicationId, $this->selectedApplications)) {
            $this->selectedApplications = array_filter($this->selectedApplications, fn($id) => $id !== $applicationId);
        } else {
            $this->selectedApplications[] = $applicationId;
        }
        
        $this->selectAll = count($this->selectedApplications) === $this->applications->count();
    }

    public function bulkApprove()
    {
        Application::whereIn('id', $this->selectedApplications)
            ->whereIn('status', ['submitted', 'under_review'])
            ->update([
                'status' => 'approved',
                'approved_at' => now(),
                'reviewed_by' => Auth::id()
            ]);
        
        session()->flash('message', count($this->selectedApplications) . ' applications approved.');
        $this->selectedApplications = [];
        $this->selectAll = false;
    }

    public function bulkReject()
    {
        Application::whereIn('id', $this->selectedApplications)
            ->whereIn('status', ['submitted', 'under_review'])
            ->update([
                'status' => 'rejected',
                'reviewed_at' => now(),
                'reviewed_by' => Auth::id(),
                'rejection_reasons' => $this->rejectionReasons
            ]);
        
        session()->flash('message', count($this->selectedApplications) . ' applications rejected.');
        $this->selectedApplications = [];
        $this->selectAll = false;
    }

    // Navigation
    public function backToList()
    {
        $this->currentStep = 'list';
        $this->selectedApplication = null;
        $this->resetApplicationForm();
    }

    private function resetApplicationForm()
    {
        $this->applicationNotes = '';
        $this->rejectionReasons = [];
        $this->approvalAmount = null;
        $this->approvalTenure = null;
        $this->approvalInterestRate = null;
    }

    // Data properties
    public function getApplicationsProperty()
    {
        $query = Application::with(['loanProduct', 'lender', 'user', 'documents'])
            ->when(Auth::user()->role === 'lender', function ($q) {
                return $q->where('lender_id', Auth::user()->lender_id);
            });

        // Search
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('application_number', 'like', '%' . $this->search . '%')
                  ->orWhere('first_name', 'like', '%' . $this->search . '%')
                  ->orWhere('last_name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%')
                  ->orWhere('phone_number', 'like', '%' . $this->search . '%')
                  ->orWhere('national_id', 'like', '%' . $this->search . '%');
            });
        }

        // Status filter
        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }

        // Date range filter
        if ($this->dateRange !== 'all') {
            $query->where('created_at', '>=', $this->getDateRangeStart());
        }

        // Amount range filter
        if ($this->amountRange !== 'all') {
            [$min, $max] = $this->getAmountRange();
            $query->whereBetween('requested_amount', [$min, $max]);
        }

        // DSR range filter
        if ($this->dsrRange !== 'all') {
            [$min, $max] = $this->getDSRRange();
            $query->whereBetween('debt_to_income_ratio', [$min, $max]);
        }

        // Product filter
        if ($this->productFilter !== 'all') {
            $query->where('loan_product_id', $this->productFilter);
        }

        // Employment filter
        if ($this->employmentFilter !== 'all') {
            $query->where('employment_status', $this->employmentFilter);
        }

        // Sorting
        $query->orderBy($this->sortBy, $this->sortDirection);

        return $query->paginate(20);
    }

    public function getStatsProperty()
    {
        $baseQuery = Application::when(Auth::user()->role === 'lender', function ($q) {
            return $q->where('lender_id', Auth::user()->lender_id);
        });

        return [
            'total' => $baseQuery->count(),
            'pending' => $baseQuery->where('status', 'submitted')->count(),
            'under_review' => $baseQuery->where('status', 'under_review')->count(),
            'approved' => $baseQuery->where('status', 'approved')->count(),
            'rejected' => $baseQuery->where('status', 'rejected')->count(),
            'disbursed' => $baseQuery->where('status', 'disbursed')->count(),
            'total_amount' => $baseQuery->where('status', 'approved')->sum('requested_amount'),
            'avg_dsr' => $baseQuery->whereNotNull('debt_to_income_ratio')->avg('debt_to_income_ratio'),
        ];
    }

    public function getLoanProductsProperty()
    {
        return LoanProduct::where('is_active', true)
            ->when(Auth::user()->role === 'lender', function ($q) {
                return $q->where('lender_id', Auth::user()->lender_id);
            })
            ->get();
    }

    // Helper methods
    private function getDateRangeStart()
    {
        return match($this->dateRange) {
            'today' => now()->startOfDay(),
            'week' => now()->subWeek(),
            'month' => now()->subMonth(),
            'quarter' => now()->subQuarter(),
            'year' => now()->subYear(),
            default => now()->subYear(),
        };
    }

    private function getAmountRange()
    {
        return match($this->amountRange) {
            'under_100k' => [0, 100000],
            '100k_500k' => [100000, 500000],
            '500k_1m' => [500000, 1000000],
            '1m_5m' => [1000000, 5000000],
            'over_5m' => [5000000, PHP_INT_MAX],
            default => [0, PHP_INT_MAX],
        };
    }

    private function getDSRRange()
    {
        return match($this->dsrRange) {
            'excellent' => [0, 30],
            'good' => [30, 40],
            'fair' => [40, 50],
            'poor' => [50, 100],
            default => [0, 100],
        };
    }

    public function render()
    {
        return view('livewire.loan-application.application-list', [
            'applications' => $this->applications,
            'stats' => $this->stats,
            'loanProducts' => $this->loanProducts,
        ]);
    }
}