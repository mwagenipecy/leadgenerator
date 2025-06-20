<?php

namespace App\Livewire\Lender;

use Livewire\Component;
use App\Models\Application;
use App\Models\LoanProduct;
use App\Models\Lender;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
    public $recentApplications=[];
    public $loanProducts;
    public $applicationsByStatus=[];
    public $applicationsByProduct;
    public $monthlyStats;
    public $topPerformingProducts=[];

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
            ->pluck('count', 'status');

        // Applications by product
        $this->applicationsByProduct = Application::where('lender_id', $lender->id)
            ->with('loanProduct')
            ->select('loan_product_id', DB::raw('count(*) as count'))
            ->groupBy('loan_product_id')
            ->get()
            ->map(function ($item) {
                return [
                    'product_name' => $item->loanProduct->name ?? 'Unknown',
                    'count' => $item->count
                ];
            });

        // Top performing products
        $this->topPerformingProducts = LoanProduct::where('lender_id', $lender->id)
            ->withCount([
                'applications',
                'applications as approved_count' => function ($query) {
                    $query->where('status', 'approved');
                }
            ])
            ->orderBy('approved_count', 'desc')
            ->limit(5)
            ->get();
    }

    public function approveApplication($applicationId)
    {
        $application = Application::find($applicationId);
        $application->update([
            'status' => 'approved',
            'approved_at' => now(),
            'reviewed_by' => Auth::id()
        ]);
        
        $this->loadDashboardData();
        session()->flash('message', 'Application approved successfully!');
    }

    public function rejectApplication($applicationId, $reason = 'Requirements not met')
    {
        $application = Application::find($applicationId);
        $application->update([
            'status' => 'rejected',
            'reviewed_at' => now(),
            'reviewed_by' => Auth::id(),
            'rejection_reasons' => json_encode(['reason' => $reason])
        ]);
        
        $this->loadDashboardData();
        session()->flash('message', 'Application rejected.');
    }

    public function reviewApplication($applicationId)
    {
        $application = Application::find($applicationId);
        $application->update([
            'status' => 'under_review',
            'reviewed_at' => now(),
            'reviewed_by' => Auth::id()
        ]);
        
        $this->loadDashboardData();
        session()->flash('message', 'Application moved to review.');
    }

    public function render()
    {
        return view('livewire.lender.lender-dashboard');
    }
}