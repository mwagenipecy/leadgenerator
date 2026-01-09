<?php

namespace App\Livewire\Lender;

use App\Models\ApplicationLenderSubmission;
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

        // All counts should come from application_lender_submissions table
        // New applications: status = 'submitted' (not approved yet)
        $this->newApplications = ApplicationLenderSubmission::where('lender_id', $lender->id)
            ->where('status', 'submitted')
            ->count();

        // Total applications submitted to this lender
        $this->totalApplications = ApplicationLenderSubmission::where('lender_id', $lender->id)->count();

        // Pending applications (submitted or under_review)
        $this->pendingApplications = ApplicationLenderSubmission::where('lender_id', $lender->id)
            ->whereIn('status', ['submitted', 'under_review'])
            ->count();

        // Approved applications
        $this->approvedApplications = ApplicationLenderSubmission::where('lender_id', $lender->id)
            ->where('status', 'approved')
            ->count();
            
        // Rejected applications
        $this->rejectedApplications = ApplicationLenderSubmission::where('lender_id', $lender->id)
            ->where('status', 'rejected')
            ->count();

        // Conversion rate
        $this->conversionRate = $this->totalApplications > 0 
            ? round(($this->approvedApplications / $this->totalApplications) * 100, 1) 
            : 0;

        // Disbursement amounts - get from applications linked via application_lender_submissions
        $approvedSubmissionIds = ApplicationLenderSubmission::where('lender_id', $lender->id)
            ->where('status', 'approved')
            ->pluck('application_id');
            
        $this->totalDisbursed = Application::whereIn('id', $approvedSubmissionIds)
            ->where('status', 'disbursed')
            ->sum('requested_amount');

        $this->monthlyDisbursed = Application::whereIn('id', $approvedSubmissionIds)
            ->where('status', 'disbursed')
            ->whereMonth('disbursed_at', now()->month)
            ->whereYear('disbursed_at', now()->year)
            ->sum('requested_amount');

        // Recent applications from application_lender_submissions
        // Get the applications linked to submissions and attach submission status
        $submissions = ApplicationLenderSubmission::with(['application.user', 'loanProduct'])
            ->where('lender_id', $lender->id)
            ->orderBy('submitted_at', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit(8)
            ->get()
            ->filter(function ($submission) {
                return $submission->application !== null;
            });

        $this->recentApplications = $submissions->map(function ($submission) {
            $application = $submission->application;
            // Attach submission status and loan product to the application object
            $application->submission_status = $submission->status;
            $application->loanProduct = $submission->loanProduct;
            $application->submitted_at = $submission->submitted_at ?? $submission->created_at;
            return $application;
        });

        // Loan products with real counts from application_lender_submissions
        $this->loanProducts = LoanProduct::where('lender_id', $lender->id)
            ->get()
            ->map(function ($product) use ($lender) {
                $submissions = ApplicationLenderSubmission::where('lender_id', $lender->id)
                    ->where('loan_product_id', $product->id)
                    ->get();
                    
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'applications_count' => $submissions->count(),
                ];
            });

        // Applications by status from application_lender_submissions
        $submissionStatuses = ApplicationLenderSubmission::where('lender_id', $lender->id)
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status')
            ->toArray();

        // Get disbursed count from Application model where submission is approved
        $approvedSubmissionIds = ApplicationLenderSubmission::where('lender_id', $lender->id)
            ->where('status', 'approved')
            ->pluck('application_id');
            
        $disbursedCount = Application::whereIn('id', $approvedSubmissionIds)
            ->where('status', 'disbursed')
            ->count();

        // Initialize all expected statuses with 0, then merge with actual data
        $this->applicationsByStatus = [
            'submitted' => $submissionStatuses['submitted'] ?? 0,
            'under_review' => $submissionStatuses['under_review'] ?? 0,
            'approved' => $submissionStatuses['approved'] ?? 0,
            'rejected' => $submissionStatuses['rejected'] ?? 0,
            'disbursed' => $disbursedCount,
            // Include other statuses that might exist
            'pending' => $submissionStatuses['pending'] ?? 0,
            'withdrawn' => $submissionStatuses['withdrawn'] ?? 0,
            'expired' => $submissionStatuses['expired'] ?? 0,
        ];

        // Applications by product with product details - from application_lender_submissions
        $this->applicationsByProduct = ApplicationLenderSubmission::where('lender_id', $lender->id)
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

        // Top performing products with real data from application_lender_submissions
        $this->topPerformingProducts = LoanProduct::where('lender_id', $lender->id)
            ->get()
            ->map(function ($product) use ($lender) {
                $submissions = ApplicationLenderSubmission::where('lender_id', $lender->id)
                    ->where('loan_product_id', $product->id)
                    ->get();
                    
                $approvedCount = $submissions->where('status', 'approved')->count();
                $rejectedCount = $submissions->where('status', 'rejected')->count();
                $pendingCount = $submissions->whereIn('status', ['submitted', 'under_review'])->count();
                $totalCount = $submissions->count();
                
                return (object) [
                    'id' => $product->id,
                    'name' => $product->name,
                    'applications_count' => $totalCount,
                    'approved_count' => $approvedCount,
                    'rejected_count' => $rejectedCount,
                    'pending_count' => $pendingCount,
                ];
            })
            ->filter(function ($product) {
                return $product->applications_count > 0;
            })
            ->sortByDesc('applications_count')
            ->take(5)
            ->values();

        // Monthly application trends (last 6 months) - from application_lender_submissions
        $this->applicationTrends = $this->getMonthlyTrends($lender->id);

        // Product performance insights - from application_lender_submissions
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

            // Get submissions from application_lender_submissions
            $totalApps = ApplicationLenderSubmission::where('lender_id', $lenderId)
                ->whereYear('submitted_at', $year)
                ->whereMonth('submitted_at', $month)
                ->count();

            $approvedApps = ApplicationLenderSubmission::where('lender_id', $lenderId)
                ->whereYear('submitted_at', $year)
                ->whereMonth('submitted_at', $month)
                ->where('status', 'approved')
                ->count();

            // Get disbursed amount from linked applications
            $approvedSubmissionIds = ApplicationLenderSubmission::where('lender_id', $lenderId)
                ->whereYear('submitted_at', $year)
                ->whereMonth('submitted_at', $month)
                ->where('status', 'approved')
                ->pluck('application_id');
                
            $disbursedAmount = Application::whereIn('id', $approvedSubmissionIds)
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
            ->get()
            ->map(function ($product) use ($lenderId) {
                // Get all submissions for this product
                $submissions = ApplicationLenderSubmission::where('lender_id', $lenderId)
                    ->where('loan_product_id', $product->id)
                    ->get();
                    
                $totalCount = $submissions->count();
                $approvedCount = $submissions->where('status', 'approved')->count();
                $recentCount = $submissions->where('submitted_at', '>=', Carbon::now()->subDays(30))->count();
                
                // Get average amount from linked applications
                $applicationIds = $submissions->pluck('application_id');
                $avgAmount = Application::whereIn('id', $applicationIds)
                    ->avg('requested_amount') ?? 0;
                
                $approvalRate = $totalCount > 0 
                    ? round(($approvedCount / $totalCount) * 100, 1) 
                    : 0;

                $performance = 'average';
                if ($approvalRate >= 70) {
                    $performance = 'excellent';
                } elseif ($approvalRate >= 50) {
                    $performance = 'good';
                } elseif ($approvalRate < 30 && $totalCount > 5) {
                    $performance = 'poor';
                }

                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'applications_count' => $totalCount,
                    'approved_count' => $approvedCount,
                    'recent_count' => $recentCount,
                    'approval_rate' => $approvalRate,
                    'avg_amount' => round($avgAmount, 2),
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