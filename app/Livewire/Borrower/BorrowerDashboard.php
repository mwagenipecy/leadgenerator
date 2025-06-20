<?php

namespace App\Livewire\Borrower;

use Livewire\Component;
use App\Models\Application;
use App\Models\LoanProduct;
use App\Models\NidaVerification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BorrowerDashboard extends Component
{
    public $totalApplications;
    public $approvedApplications;
    public $rejectedApplications;
    public $pendingApplications;
    public $disbursedApplications;
    public $totalApprovedAmount;
    public $totalDisbursedAmount;
    public $nidaVerificationStatus;
    public $myApplications;
    public $availableLoanProducts;
    public $applicationsByStatus;
    public $creditScore;
    public $nextPaymentDue;
    public $outstandingBalance;
    public $recentActivity;
    public $completionPercentage;

    public function mount()
    {
        $this->loadDashboardData();
    }

    public function loadDashboardData()
    {
        $user = Auth::user();
        
        // Application counts
        $this->totalApplications = Application::where('user_id', $user->id)->count();
        $this->approvedApplications = Application::where('user_id', $user->id)
            ->where('status', 'approved')
            ->count();
        $this->rejectedApplications = Application::where('user_id', $user->id)
            ->where('status', 'rejected')
            ->count();
        $this->pendingApplications = Application::where('user_id', $user->id)
            ->whereIn('status', ['submitted', 'under_review'])
            ->count();
        $this->disbursedApplications = Application::where('user_id', $user->id)
            ->where('status', 'disbursed')
            ->count();

        // Amounts
        $this->totalApprovedAmount = Application::where('user_id', $user->id)
            ->where('status', 'approved')
            ->sum('requested_amount');
        
        $this->totalDisbursedAmount = Application::where('user_id', $user->id)
            ->where('status', 'disbursed')
            ->sum('requested_amount');

        // NIDA verification status
        $nidaVerification = NidaVerification::where('user_id', $user->id)
            ->latest()
            ->first();
        $this->nidaVerificationStatus = $nidaVerification ? $nidaVerification->status : 'pending';

        // My applications
        $this->myApplications = Application::with(['lender', 'loanProduct'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Available loan products
        $this->availableLoanProducts = LoanProduct::with('lender')
            ->where('is_active', true)
            ->orderBy('interest_rate_min')
            ->limit(6)
            ->get();

        // Applications by status
        $this->applicationsByStatus = Application::where('user_id', $user->id)
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status');

        // Mock credit score (in real app, this would come from credit bureau)
        $this->creditScore = 650 + ($this->approvedApplications * 10) - ($this->rejectedApplications * 5);
        
        // Mock next payment and outstanding balance
        $this->nextPaymentDue = now()->addDays(15);
        $this->outstandingBalance = $this->totalDisbursedAmount * 0.85; // Mock 85% remaining

        // Profile completion percentage
        $this->completionPercentage = $this->calculateProfileCompletion($user);

        // Recent activity
        $this->recentActivity = collect([
            [
                'type' => 'application_submitted',
                'message' => 'Application submitted',
                'details' => 'Personal loan application for TSh 2,000,000',
                'time' => '2 hours ago',
                'color' => 'blue'
            ],
            [
                'type' => 'nida_verified',
                'message' => 'Identity verified',
                'details' => 'NIDA verification completed successfully',
                'time' => '1 day ago',
                'color' => 'green'
            ],
            [
                'type' => 'document_uploaded',
                'message' => 'Documents uploaded',
                'details' => 'Salary statement and bank statements',
                'time' => '3 days ago',
                'color' => 'purple'
            ],
        ]);
    }

    private function calculateProfileCompletion($user)
    {
        $completed = 0;
        $total = 10;

        if ($user->first_name) $completed++;
        if ($user->last_name) $completed++;
        if ($user->email) $completed++;
        if ($user->phone) $completed++;
        if ($user->date_of_birth) $completed++;
        if ($user->nida_number) $completed++;
        if ($user->nida_verified_at) $completed++;
        if ($user->email_verified_at) $completed++;
        
        // Check if user has any applications
        if ($this->totalApplications > 0) $completed++;
        
        // Check if user has complete application data
        $hasCompleteApplication = Application::where('user_id', $user->id)
            ->whereNotNull(['monthly_salary', 'employment_status', 'current_address'])
            ->exists();
        if ($hasCompleteApplication) $completed++;

        return round(($completed / $total) * 100);
    }

    public function withdrawApplication($applicationId)
    {
        $application = Application::where('id', $applicationId)
            ->where('user_id', Auth::id())
            ->whereIn('status', ['submitted', 'under_review'])
            ->first();

        if ($application) {
            $application->update(['status' => 'cancelled']);
            $this->loadDashboardData();
            session()->flash('message', 'Application withdrawn successfully.');
        }
    }

    public function render()
    {
        return view('livewire.borrower.borrower-dashboard');
    }
}