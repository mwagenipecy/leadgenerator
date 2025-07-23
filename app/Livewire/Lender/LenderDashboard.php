<?php

namespace App\Livewire\Lender;

use Livewire\Component;
use App\Models\Application;
use App\Models\LoanProduct;
use App\Models\Lender;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LenderDashboard extends Component
{
    public $newApplications;
    public $totalApplications;
    public $approvedApplications;
    public $rejectedApplications;
    public $pendingApplications;
    public $totalDisbursed;
    public $monthlyDisbursed;
    public $conversionRate;
    public $recentApplications = [];
    public $loanProducts;
    public $applicationsByStatus = [];
    public $applicationsByProduct;
    public $monthlyStats = [];
    public $topPerformingProducts = [];
    public $applicationTrends = [];
    public $productInsights = [];

    public function mount()
    {
        $this->loadDashboardData();
    }

    public function loadDashboardData()
    {
        $lender = Auth::user()->lender ?? Lender::where('user_id', Auth::id())->first();
        
        if (!$lender) {
            return;
        }

        // Basic application counts
        $this->totalApplications = Application::where('lender_id', $lender->id)->count();
        $this->newApplications = Application::where('lender_id', $lender->id)
            ->where('status', 'submitted')
            ->count();
        $this->pendingApplications = Application::where('lender_id', $lender->id)
            ->where('status', 'under_review')
            ->count();
        $this->approvedApplications = Application::where('lender_id', $lender->id)
            ->where('status', 'approved')
            ->count();
        $this->rejectedApplications = Application::where('lender_id', $lender->id)
            ->where('status', 'rejected')
            ->count();

        // Conversion rate
        $this->conversionRate = $this->totalApplications > 0 
            ? round(($this->approvedApplications / $this->totalApplications) * 100, 1) 
            : 0;

        // Disbursement amounts
        $this->totalDisbursed = Application::where('lender_id', $lender->id)
            ->where('status', 'disbursed')
            ->sum('requested_amount');

        $this->monthlyDisbursed = Application::where('lender_id', $lender->id)
            ->where('status', 'disbursed')
            ->whereMonth('disbursed_at', now()->month)
            ->whereYear('disbursed_at', now()->year)
            ->sum('requested_amount');

        // Recent applications
        $this->recentApplications = Application::with(['user', 'loanProduct'])
            ->where('lender_id', $lender->id)
            ->orderBy('created_at', 'desc')
            ->limit(8)
            ->get();

        // Loan products
        $this->loanProducts = LoanProduct::where('lender_id', $lender->id)
            ->withCount('applications')
            ->get();

        // Applications by status
        $this->applicationsByStatus = Application::where('lender_id', $lender->id)
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status')
            ->toArray();

        // Applications by product with product details
        $this->applicationsByProduct = Application::where('lender_id', $lender->id)
            ->with('loanProduct')
            ->select('loan_product_id', DB::raw('count(*) as count'))
            ->whereNotNull('loan_product_id')
            ->groupBy('loan_product_id')
            ->get()
            ->map(function ($item) {
                return [
                    'product_name' => $item->loanProduct->name ?? 'Unknown',
                    'count' => $item->count,
                    'product_id' => $item->loan_product_id
                ];
            });

        // Top performing products with enhanced data
        $this->topPerformingProducts = LoanProduct::where('lender_id', $lender->id)
            ->withCount([
                'applications',
                'applications as approved_count' => function ($query) {
                    $query->where('status', 'approved');
                },
                'applications as rejected_count' => function ($query) {
                    $query->where('status', 'rejected');
                },
                'applications as pending_count' => function ($query) {
                    $query->whereIn('status', ['submitted', 'under_review']);
                }
            ])
            ->having('applications_count', '>', 0)
            ->orderBy('applications_count', 'desc')
            ->limit(5)
            ->get();

        // Monthly application trends (last 6 months)
        $this->applicationTrends = $this->getMonthlyTrends($lender->id);

        // Product performance insights
        $this->productInsights = $this->getProductInsights($lender->id);
    }

    private function getMonthlyTrends($lenderId)
    {
        $trends = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthName = $date->format('M');
            $year = $date->year;
            $month = $date->month;

            $totalApps = Application::where('lender_id', $lenderId)
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->count();

            $approvedApps = Application::where('lender_id', $lenderId)
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->where('status', 'approved')
                ->count();

            $disbursedAmount = Application::where('lender_id', $lenderId)
                ->whereYear('disbursed_at', $year)
                ->whereMonth('disbursed_at', $month)
                ->where('status', 'disbursed')
                ->sum('requested_amount');

            $trends[] = [
                'month' => $monthName,
                'applications' => $totalApps,
                'approved' => $approvedApps,
                'disbursed_amount' => $disbursedAmount,
                'approval_rate' => $totalApps > 0 ? round(($approvedApps / $totalApps) * 100, 1) : 0
            ];
        }

        return collect($trends);
    }

    private function getProductInsights($lenderId)
    {
        return LoanProduct::where('lender_id', $lenderId)
            ->withCount([
                'applications',
                'applications as approved_count' => function ($query) {
                    $query->where('status', 'approved');
                },
                'applications as recent_count' => function ($query) {
                    $query->where('created_at', '>=', Carbon::now()->subDays(30));
                }
            ])
            ->withAvg('applications as avg_amount', 'requested_amount')
            ->get()
            ->map(function ($product) {
                $approvalRate = $product->applications_count > 0 
                    ? round(($product->approved_count / $product->applications_count) * 100, 1) 
                    : 0;

                $performance = 'average';
                if ($approvalRate >= 70) {
                    $performance = 'excellent';
                } elseif ($approvalRate >= 50) {
                    $performance = 'good';
                } elseif ($approvalRate < 30 && $product->applications_count > 5) {
                    $performance = 'poor';
                }

                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'applications_count' => $product->applications_count,
                    'approved_count' => $product->approved_count,
                    'recent_count' => $product->recent_count,
                    'approval_rate' => $approvalRate,
                    'avg_amount' => $product->avg_amount ?? 0,
                    'performance' => $performance,
                    'min_amount' => $product->min_amount,
                    'max_amount' => $product->max_amount,
                    'interest_rate_min' => $product->interest_rate_min,
                    'interest_rate_max' => $product->interest_rate_max
                ];
            });
    }

    public function approveApplication($applicationId)
    {
        $application = Application::find($applicationId);
        
        if (!$application) {
            session()->flash('error', 'Application not found.');
            return;
        }

        $application->update([
            'status' => 'approved',
            'approved_at' => now(),
            'reviewed_by' => Auth::id()
        ]);
        
        $this->loadDashboardData();
        $this->dispatch('dashboardUpdated'); // Trigger chart updates
        session()->flash('message', 'Application approved successfully!');
    }

    public function rejectApplication($applicationId, $reason = 'Requirements not met')
    {
        $application = Application::find($applicationId);
        
        if (!$application) {
            session()->flash('error', 'Application not found.');
            return;
        }

        $application->update([
            'status' => 'rejected',
            'rejected_at' => now(),
            'reviewed_by' => Auth::id(),
            'rejection_reasons' => json_encode(['reason' => $reason])
        ]);
        
        $this->loadDashboardData();
        $this->dispatch('dashboardUpdated'); // Trigger chart updates
        session()->flash('message', 'Application rejected.');
    }

    public function reviewApplication($applicationId)
    {
        $application = Application::find($applicationId);
        
        if (!$application) {
            session()->flash('error', 'Application not found.');
            return;
        }

        $application->update([
            'status' => 'under_review',
            'reviewed_at' => now(),
            'reviewed_by' => Auth::id()
        ]);
        
        $this->loadDashboardData();
        $this->dispatch('dashboardUpdated'); // Trigger chart updates
        session()->flash('message', 'Application moved to review.');
    }

    // Helper method to get application status distribution for charts
    public function getStatusDistribution()
    {
        $total = $this->totalApplications;
        if ($total === 0) return [];

        return [
            'submitted' => [
                'count' => $this->applicationsByStatus['submitted'] ?? 0,
                'percentage' => round((($this->applicationsByStatus['submitted'] ?? 0) / $total) * 100, 1)
            ],
            'under_review' => [
                'count' => $this->applicationsByStatus['under_review'] ?? 0,
                'percentage' => round((($this->applicationsByStatus['under_review'] ?? 0) / $total) * 100, 1)
            ],
            'approved' => [
                'count' => $this->applicationsByStatus['approved'] ?? 0,
                'percentage' => round((($this->applicationsByStatus['approved'] ?? 0) / $total) * 100, 1)
            ],
            'rejected' => [
                'count' => $this->applicationsByStatus['rejected'] ?? 0,
                'percentage' => round((($this->applicationsByStatus['rejected'] ?? 0) / $total) * 100, 1)
            ],
            'disbursed' => [
                'count' => $this->applicationsByStatus['disbursed'] ?? 0,
                'percentage' => round((($this->applicationsByStatus['disbursed'] ?? 0) / $total) * 100, 1)
            ],
        ];
    }

    public function render()
    {
        return view('livewire.lender.lender-dashboard');
    }
}