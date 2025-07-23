<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\User;
use App\Models\Lender;
use App\Models\Application;
use App\Models\LoanProduct;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminDashboard extends Component
{
    public $totalLenders;
    public $pendingLenders;
    public $approvedLenders;
    public $totalApplications;
    public $totalBorrowers;
    public $recentApplications;
    public $lenderStats;
    public $monthlyRevenue;
    public $applicationsByStatus;
    public $pendingLendersList;
    public $recentActivity;
    public $totalRevenue;
    public $approvedApplications;
    public $rejectedApplications;
    public $conversionRate;
    
    // Chart data properties
    public $monthlyLabels;
    public $monthlyApplications;
    public $monthlyApproved;
    public $monthlyRejected;
    public $monthlyDisbursed;
    public $statusLabels;
    public $statusData;

    public function mount()
    {
        $this->loadDashboardData();
        $this->loadChartData();
    }

    public function loadDashboardData()
    {
        // Basic counts
        $this->totalLenders = Lender::count();
        $this->pendingLenders = Lender::where('status', 'pending')->count();
        $this->approvedLenders = Lender::where('status', 'approved')->count();
        $this->totalApplications = Application::count();
        $this->totalBorrowers = User::where('role', 'user')->count();

        // Application counts
        $this->approvedApplications = Application::where('status', 'approved')->count();
        $this->rejectedApplications = Application::where('status', 'rejected')->count();
        
        // Conversion rate calculation
        $this->conversionRate = $this->totalApplications > 0 
            ? round(($this->approvedApplications / $this->totalApplications) * 100, 1) 
            : 0;

        // Recent applications
        $this->recentApplications = Application::with(['user', 'lender', 'loanProduct'])
            ->orderBy('created_at', 'desc')
            ->limit(8)
            ->get();

        // Pending lenders for approval
        $this->pendingLendersList = Lender::where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Applications by status
        $this->applicationsByStatus = Application::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status');

        // Revenue calculation (assuming commission on approved loans)
        $this->totalRevenue = Application::where('status', 'approved')
            ->sum('requested_amount') * 0.015; // 1.5% commission

        $this->monthlyRevenue = Application::where('status', 'approved')
            ->whereMonth('approved_at', now()->month)
            ->sum('requested_amount') * 0.015;

        // Recent activity simulation
        $this->recentActivity = collect([
            [
                'type' => 'lender_approved',
                'message' => 'New lender approved',
                'details' => 'KCB Bank Tanzania approved',
                'time' => '5 minutes ago',
                'color' => 'green'
            ],
            [
                'type' => 'application_submitted',
                'message' => 'New application received',
                'details' => 'John Doe submitted loan application',
                'time' => '12 minutes ago',
                'color' => 'brand-red'
            ],
            [
                'type' => 'application_approved',
                'message' => 'Application approved',
                'details' => 'Maria Johnson - TZS 5,000,000',
                'time' => '1 hour ago',
                'color' => 'blue'
            ],
            [
                'type' => 'system_update',
                'message' => 'System maintenance',
                'details' => 'NIDA integration updated',
                'time' => '3 hours ago',
                'color' => 'purple'
            ],
        ]);
    }

    public function loadChartData()
    {
        // Get current year for monthly trends
        $currentYear = now()->year;
        
        // Generate monthly labels
        $this->monthlyLabels = collect(range(1, 12))->map(function ($month) {
            return Carbon::create(null, $month, 1)->format('M');
        })->toArray();

        // Monthly applications trend data
        $monthlyStats = collect(range(1, 12))->map(function ($month) use ($currentYear) {
            $startOfMonth = Carbon::create($currentYear, $month, 1)->startOfMonth();
            $endOfMonth = Carbon::create($currentYear, $month, 1)->endOfMonth();

            return [
                'month' => $month,
                'total' => Application::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count(),
                'approved' => Application::where('status', 'approved')
                    ->whereBetween('created_at', [$startOfMonth, $endOfMonth])->count(),
                'rejected' => Application::where('status', 'rejected')
                    ->whereBetween('created_at', [$startOfMonth, $endOfMonth])->count(),
                'disbursed' => Application::where('status', 'disbursed')
                    ->whereBetween('created_at', [$startOfMonth, $endOfMonth])->count(),
            ];
        });

        $this->monthlyApplications = $monthlyStats->pluck('total')->toArray();
        $this->monthlyApproved = $monthlyStats->pluck('approved')->toArray();
        $this->monthlyRejected = $monthlyStats->pluck('rejected')->toArray();
        $this->monthlyDisbursed = $monthlyStats->pluck('disbursed')->toArray();

        // Status distribution data
        $statusCounts = Application::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get()
            ->keyBy('status');

        // Define all possible statuses
        $allStatuses = ['submitted', 'under_review', 'approved', 'rejected', 'disbursed'];
        
        $this->statusLabels = [];
        $this->statusData = [];

        foreach ($allStatuses as $status) {
            $count = $statusCounts->get($status)?->count ?? 0;
            if ($count > 0) { // Only include statuses with data
                $this->statusLabels[] = ucfirst(str_replace('_', ' ', $status));
                $this->statusData[] = $count;
            }
        }
    }

    public function approveLender($lenderId)
    {
        $lender = Lender::find($lenderId);
        $lender->update([
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => auth()->id()
        ]);
        
        $this->loadDashboardData();
        $this->loadChartData();
        session()->flash('message', 'Lender approved successfully!');
    }

    public function rejectLender($lenderId, $reason = 'Requirements not met')
    {
        $lender = Lender::find($lenderId);
        $lender->update([
            'status' => 'rejected',
            'rejection_reason' => $reason
        ]);
        
        $this->loadDashboardData();
        $this->loadChartData();
        session()->flash('message', 'Lender rejected.');
    }

    public function suspendLender($lenderId)
    {
        $lender = Lender::find($lenderId);
        $lender->update(['status' => 'suspended']);
        
        $this->loadDashboardData();
        $this->loadChartData();
        session()->flash('message', 'Lender suspended.');
    }

    public function refreshCharts()
    {
        $this->loadChartData();
        $this->dispatch('refreshCharts');
    }

    public function render()
    {
        return view('livewire.admin.admin-dashboard');
    }
}