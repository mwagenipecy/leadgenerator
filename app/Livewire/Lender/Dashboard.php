<?php

namespace App\Livewire\Lender;

use App\Models\Lender;
use App\Models\Application;
use App\Models\LoanProduct;
use App\Models\CommissionTransaction;
use Livewire\Component;
use Livewire\WithPagination;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Dashboard extends Component
{
    use WithPagination;

    public $lender;
    public $dateRange = '30'; // Last 30 days by default
    public $statusFilter = '';
    public $search = '';
    public $showApplicationModal = false;
    public $selectedApplication = null;

    public $application_statuses = [
        'draft',
        'submitted', 
        'under_review',
        'approved',
        'rejected',
        'disbursed',
        'cancelled'
    ];

    protected $paginationTheme = 'bootstrap';

    public function mount(Lender $lender)
    {
        $this->lender = $lender;
    }

    public function render()
    {
        $applications = $this->getApplications();
        $stats = $this->getStats();
        $recentApplications = $this->getRecentApplications();
        $monthlyStats = $this->getMonthlyStats();
        $commissionStats = $this->getCommissionStats();
        $topProducts = $this->getTopPerformingProducts();

        return view('livewire.lender.dashboard', [
            'applications' => $applications,
            'stats' => $stats,
            'recentApplications' => $recentApplications,
            'monthlyStats' => $monthlyStats,
            'commissionStats' => $commissionStats,
            'topProducts' => $topProducts
        ]);
    }

    public function getApplications()
    {
        return Application::where('lender_id', $this->lender->id)
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('first_name', 'like', '%' . $this->search . '%')
                      ->orWhere('last_name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%')
                      ->orWhere('application_number', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->statusFilter !== '', function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->when($this->dateRange, function ($query) {
                $days = (int) $this->dateRange;
                $query->where('created_at', '>=', Carbon::now()->subDays($days));
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    }

    public function getStats()
    {
        $baseQuery = Application::where('lender_id', $this->lender->id);

        if ($this->dateRange) {
            $days = (int) $this->dateRange;
            $baseQuery->where('created_at', '>=', Carbon::now()->subDays($days));
        }

        return [
            'total_applications' => $baseQuery->count(),
            'pending_review' => $baseQuery->where('status', 'under_review')->count(),
            'approved' => $baseQuery->where('status', 'approved')->count(),
            'disbursed' => $baseQuery->where('status', 'disbursed')->count(),
            'rejected' => $baseQuery->where('status', 'rejected')->count(),
            'total_loan_amount' => $baseQuery->where('status', 'disbursed')->sum('requested_amount'),
            'avg_loan_amount' => $baseQuery->where('status', 'disbursed')->avg('requested_amount'),
            'approval_rate' => $this->calculateApprovalRate($baseQuery)
        ];
    }

    private function calculateApprovalRate($query)
    {
        $total = $query->whereIn('status', ['approved', 'rejected', 'disbursed'])->count();
        $approved = $query->whereIn('status', ['approved', 'disbursed'])->count();
        
        return $total > 0 ? round(($approved / $total) * 100, 1) : 0;
    }

    public function getRecentApplications()
    {
        return Application::where('lender_id', $this->lender->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
    }

    public function getMonthlyStats()
    {
        $months = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthStart = $date->copy()->startOfMonth();
            $monthEnd = $date->copy()->endOfMonth();
            
            $stats = Application::where('lender_id', $this->lender->id)
                ->whereBetween('created_at', [$monthStart, $monthEnd])
                ->selectRaw('
                    COUNT(*) as total_applications,
                    COUNT(CASE WHEN status = "approved" OR status = "disbursed" THEN 1 END) as approved_applications,
                    SUM(CASE WHEN status = "disbursed" THEN requested_amount ELSE 0 END) as disbursed_amount
                ')
                ->first();

            $months[] = [
                'month' => $date->format('M Y'),
                'short_month' => $date->format('M'),
                'total_applications' => $stats->total_applications ?? 0,
                'approved_applications' => $stats->approved_applications ?? 0,
                'disbursed_amount' => $stats->disbursed_amount ?? 0
            ];
        }

        return $months;
    }

    public function getCommissionStats()
    {
        $baseQuery = CommissionTransaction::where('lender_id', $this->lender->id);

        if ($this->dateRange) {
            $days = (int) $this->dateRange;
            $baseQuery->where('created_at', '>=', Carbon::now()->subDays($days));
        }

        return [
            'total_commission' => $baseQuery->sum('commission_amount'),
            'paid_commission' => $baseQuery->where('status', 'paid')->sum('commission_amount'),
            'pending_commission' => $baseQuery->where('status', 'pending')->sum('commission_amount'),
            'overdue_commission' => $baseQuery->where('status', 'overdue')->sum('commission_amount'),
            'total_transactions' => $baseQuery->count()
        ];
    }

    public function getTopPerformingProducts()
    {
        return LoanProduct::where('lender_id', $this->lender->id)
            ->withCount(['applications' => function ($query) {
                if ($this->dateRange) {
                    $days = (int) $this->dateRange;
                    $query->where('created_at', '>=', Carbon::now()->subDays($days));
                }
            }])
            ->withCount(['applications as approved_count' => function ($query) {
                $query->whereIn('status', ['approved', 'disbursed']);
                if ($this->dateRange) {
                    $days = (int) $this->dateRange;
                    $query->where('created_at', '>=', Carbon::now()->subDays($days));
                }
            }])
            ->withSum(['applications as total_amount' => function ($query) {
                $query->where('status', 'disbursed');
                if ($this->dateRange) {
                    $days = (int) $this->dateRange;
                    $query->where('created_at', '>=', Carbon::now()->subDays($days));
                }
            }], 'requested_amount')
            ->orderBy('applications_count', 'desc')
            ->limit(5)
            ->get();
    }

    public function viewApplication($id)
    {
        $this->selectedApplication = Application::where('lender_id', $this->lender->id)
            ->findOrFail($id);
        $this->showApplicationModal = true;
    }

    public function closeApplicationModal()
    {
        $this->showApplicationModal = false;
        $this->selectedApplication = null;
    }

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

    public function exportApplications()
    {
        // You can implement CSV/Excel export functionality here
        session()->flash('message', 'Export functionality will be implemented soon.');
    }
}