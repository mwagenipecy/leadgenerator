<?php

namespace App\Livewire\Borrower;

use Livewire\Component;
use App\Models\Application;
use App\Models\LoanProduct;
use App\Models\NidaVerification;
use App\Services\LoanProductMatchingService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class BorrowerDashboard extends Component
{
    private const DASHBOARD_CACHE_TTL_MINUTES = 5;

    // Core metrics
    public $totalApplications = 0;
    public $approvedApplications = 0;
    public $rejectedApplications = 0;
    public $pendingApplications = 0;
    public $disbursedApplications = 0;
    public $cancelledApplications = 0;
    
    // Financial data
    public $totalApprovedAmount = 0;
    public $totalDisbursedAmount = 0;
    public $outstandingBalance = 0;
    public $nextPaymentDue;
    public $monthlyPayment = 0;
    
    // User profile data
    public $nidaVerificationStatus = 'pending';
    public $creditScore = null;
    public $completionPercentage = 0;
    
    // Collections
    public $myApplications;
    /** @var \Illuminate\Support\Collection<int, array{product: \App\Models\LoanProduct, match_percent: int, matched_criteria: array, unmatched_criteria: array, can_apply: bool}> */
    public $availableLoanProducts;
    public $applicationsByStatus;
    /** Max amount the user may qualify for across all matching products (for summary). */
    public $maxPossibleAmount = 0;
    public $recentActivity;
    public $paymentHistory;
    public $monthlyApplicationTrend;
    
    // Modal states
    public $showWithdrawModal = false;
    public $selectedApplicationId = null;
    public $selectedApplicationNumber = '';
    public $showProductModal = false;
    public $selectedProduct = null;
    /** View matching criteria modal: show where user matches / doesn't match */
    public $showCriteriaModal = false;
    /** @var array{product: \App\Models\LoanProduct, match_percent: int, matched_criteria: array, unmatched_criteria: array, can_apply: bool}|null */
    public $selectedMatchItem = null;

    public function mount()
    {
        $this->availableLoanProducts = $this->availableLoanProducts ?? collect();
        $this->loadDashboardData();
    }

    public function loadDashboardData()
    {
        $user = Auth::user();
        $cacheKey = "dashboard:borrower:{$user->id}:v1";
        $cachedData = Cache::store('redis')->get($cacheKey);
        if (is_array($cachedData)) {
            foreach ($cachedData as $property => $value) {
                $this->{$property} = $value;
            }
            return;
        }
        
        // Load application statistics
        $this->loadApplicationStats($user);
        
        // Load financial data
        $this->loadFinancialData($user);
        
        // Load user profile data
        $this->loadProfileData($user);
        
        // Load collections and activity
        $this->loadCollections($user);

        Cache::store('redis')->put($cacheKey, [
            'totalApplications' => $this->totalApplications,
            'approvedApplications' => $this->approvedApplications,
            'rejectedApplications' => $this->rejectedApplications,
            'pendingApplications' => $this->pendingApplications,
            'disbursedApplications' => $this->disbursedApplications,
            'cancelledApplications' => $this->cancelledApplications,
            'totalApprovedAmount' => $this->totalApprovedAmount,
            'totalDisbursedAmount' => $this->totalDisbursedAmount,
            'outstandingBalance' => $this->outstandingBalance,
            'nextPaymentDue' => $this->nextPaymentDue,
            'monthlyPayment' => $this->monthlyPayment,
            'nidaVerificationStatus' => $this->nidaVerificationStatus,
            'creditScore' => $this->creditScore,
            'completionPercentage' => $this->completionPercentage,
            'myApplications' => $this->myApplications,
            'availableLoanProducts' => $this->availableLoanProducts,
            'applicationsByStatus' => $this->applicationsByStatus,
            'maxPossibleAmount' => $this->maxPossibleAmount,
            'recentActivity' => $this->recentActivity,
            'monthlyApplicationTrend' => $this->monthlyApplicationTrend,
        ], now()->addMinutes(self::DASHBOARD_CACHE_TTL_MINUTES));
    }

    private function clearDashboardCache(): void
    {
        $userId = Auth::id();
        if ($userId) {
            Cache::store('redis')->forget("dashboard:borrower:{$userId}:v1");
        }
    }

    private function loadApplicationStats($user)
    {
        $applications = Application::where('user_id', $user->id);
        
        $this->totalApplications = $applications->count();
        $this->approvedApplications = $applications->clone()->where('status', 'approved')->count();
        $this->rejectedApplications = $applications->clone()->where('status', 'rejected')->count();
        $this->pendingApplications = $applications->clone()->whereIn('status', ['submitted', 'under_review'])->count();
        $this->disbursedApplications = $applications->clone()->where('status', 'disbursed')->count();
        $this->cancelledApplications = $applications->clone()->where('status', 'cancelled')->count();
        
        // Applications by status for chart
        $this->applicationsByStatus = $applications->clone()
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status')
            ->toArray();
    }

    private function loadFinancialData($user)
    {
        // Approved amount
        $this->totalApprovedAmount = Application::where('user_id', $user->id)
            ->whereIn('status', ['approved', 'disbursed'])
            ->sum('requested_amount');
        
        // Disbursed amount
        $this->totalDisbursedAmount = Application::where('user_id', $user->id)
            ->where('status', 'disbursed')
            ->sum('requested_amount');

        // Calculate outstanding balance (mock calculation)
        // In real app, this would come from loan management system
        $this->outstandingBalance = $this->totalDisbursedAmount * 0.75; // Assume 75% remaining
        
        // Mock monthly payment calculation
        if ($this->totalDisbursedAmount > 0) {
            $avgTenure = Application::where('user_id', $user->id)
                ->where('status', 'disbursed')
                ->avg('requested_tenure_months') ?? 12;
            $this->monthlyPayment = ($this->totalDisbursedAmount * 1.15) / $avgTenure; // With 15% interest
        }
        
        // Next payment due date
        $this->nextPaymentDue = $this->disbursedApplications > 0 
            ? Carbon::now()->addDays(rand(5, 25))
            : null;
    }

    private function loadProfileData($user)
    {
        // NIDA verification status
        $nidaVerification = NidaVerification::where('user_id', $user->id)
            ->latest()
            ->first();
        $this->nidaVerificationStatus = $nidaVerification ? $nidaVerification->status : 'pending';

        // Get credit score from user table (primary source)
        $this->creditScore = $user->credit_score;

        // Profile completion
        $this->completionPercentage = $this->calculateProfileCompletion($user);
    }

    private function loadCollections($user)
    {
        // Recent applications with relationships
        $this->myApplications = Application::with(['lender', 'loanProduct'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Matching loan products via central service (same criteria as application/create; credit_score from users table)
        $service = app(LoanProductMatchingService::class);
        $this->availableLoanProducts = $service->getMatchingProductsForUser($user);
        $this->maxPossibleAmount = $this->availableLoanProducts->isEmpty()
            ? 0
            : (float) $this->availableLoanProducts->max(fn ($r) => $r['product']->max_amount);

        // Recent activity from applications
        $this->recentActivity = $this->generateRecentActivity($user);
        
        // Monthly application trend
        $this->monthlyApplicationTrend = $this->getMonthlyApplicationTrend($user);
    }

    /** Open modal showing where user matches / doesn't match for a product. */
    public function viewMatchingCriteria($productId)
    {
        $item = $this->availableLoanProducts->firstWhere(fn ($i) => $i['product']->id === $productId);
        if ($item) {
            $this->selectedMatchItem = $item;
            $this->showCriteriaModal = true;
        }
    }

    public function closeCriteriaModal()
    {
        $this->showCriteriaModal = false;
        $this->selectedMatchItem = null;
    }

    private function generateRecentActivity($user)
    {
        $activities = collect();
        
        // Get recent applications
        $recentApps = Application::where('user_id', $user->id)
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get();

        foreach ($recentApps as $app) {
            $activities->push([
                'type' => 'application_' . $app->status,
                'message' => $this->getActivityMessage($app->status),
                'details' => "Application #{$app->application_number} for TSh " . number_format($app->requested_amount),
                'time' => $app->updated_at->diffForHumans(),
                'color' => $this->getActivityColor($app->status),
                'icon' => $this->getActivityIcon($app->status)
            ]);
        }

        // Add NIDA verification if exists
        if ($this->nidaVerificationStatus === 'verified') {
            $activities->push([
                'type' => 'nida_verified',
                'message' => 'Identity Verified',
                'details' => 'NIDA verification completed successfully',
                'time' => 'Recently',
                'color' => 'green',
                'icon' => 'check-circle'
            ]);
        }

        return $activities->take(8);
    }

    private function getMonthlyApplicationTrend($user)
    {
        $trend = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $count = Application::where('user_id', $user->id)
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
                
            $trend[] = [
                'month' => $date->format('M'),
                'applications' => $count,
                'full_date' => $date->format('F Y')
            ];
        }
        
        return $trend;
    }

    private function getActivityMessage($status)
    {
        return match($status) {
            'submitted' => 'Application Submitted',
            'under_review' => 'Under Review',
            'approved' => 'Application Approved',
            'rejected' => 'Application Declined',
            'disbursed' => 'Loan Disbursed',
            'cancelled' => 'Application Cancelled',
            default => 'Status Updated'
        };
    }

    private function getActivityColor($status)
    {
        return match($status) {
            'submitted' => 'blue',
            'under_review' => 'yellow',
            'approved' => 'green',
            'rejected' => 'red',
            'disbursed' => 'purple',
            'cancelled' => 'gray',
            default => 'gray'
        };
    }

    private function getActivityIcon($status)
    {
        return match($status) {
            'submitted' => 'document',
            'under_review' => 'clock',
            'approved' => 'check-circle',
            'rejected' => 'x-circle',
            'disbursed' => 'currency-dollar',
            'cancelled' => 'ban',
            default => 'information-circle'
        };
    }

    private function calculateProfileCompletion($user)
    {
        $fields = [
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'phone' => $user->phone,
            'date_of_birth' => $user->date_of_birth,
            'nida_number' => $user->nida_number,
            'email_verified' => $user->email_verified_at,
            'nida_verified' => $this->nidaVerificationStatus === 'verified',
        ];

        // Check for complete application data
        $hasCompleteApplication = Application::where('user_id', $user->id)
            ->whereNotNull(['monthly_salary', 'employment_status', 'current_address'])
            ->exists();
        
        $fields['complete_application'] = $hasCompleteApplication;
        $fields['has_applications'] = $this->totalApplications > 0;

        $completed = collect($fields)->filter()->count();
        $total = count($fields);

        return round(($completed / $total) * 100);
    }

    public function confirmWithdraw($applicationId, $applicationNumber)
    {
        $this->selectedApplicationId = $applicationId;
        $this->selectedApplicationNumber = $applicationNumber;
        $this->showWithdrawModal = true;
    }

    public function withdrawApplication()
    {
        if (!$this->selectedApplicationId) return;

        $application = Application::where('id', $this->selectedApplicationId)
            ->where('user_id', Auth::id())
            ->whereIn('status', ['submitted', 'under_review'])
            ->first();

        if ($application) {
            $application->update([
                'status' => 'cancelled',
                'notes' => 'Cancelled by borrower on ' . now()->format('Y-m-d H:i:s')
            ]);
            
            $this->clearDashboardCache();
            $this->loadDashboardData();
            session()->flash('message', 'Application #' . $application->application_number . ' has been withdrawn successfully.');
        }

        $this->closeWithdrawModal();
    }

    public function closeWithdrawModal()
    {
        $this->showWithdrawModal = false;
        $this->selectedApplicationId = null;
        $this->selectedApplicationNumber = '';
    }

    public function viewProductDetails($productId)
    {
        $this->selectedProduct = LoanProduct::with('lender')->find($productId);
        $this->showProductModal = true;
    }

    public function closeProductModal()
    {
        $this->showProductModal = false;
        $this->selectedProduct = null;
    }

    public function applyForLoan($productId = null)
    {
        // Redirect to loan application form
        if ($productId) {
            return redirect()->route('loan-application.create', ['product' => $productId]);
        }
        return redirect()->route('loan-application.create');
    }

    public function viewApplication($applicationId)
    {
        return redirect()->route('borrower.application.show', $applicationId);
    }

    public function completeProfile()
    {
        return redirect()->route('borrower.profile.edit');
    }

    public function getCreditScoreColor()
    {
        $score = $this->getNumericCreditScore();
        if (!$score) return 'gray';
        
        if ($score >= 750) return 'green';
        if ($score >= 650) return 'blue';
        if ($score >= 550) return 'yellow';
        return 'red';
    }

    public function getCreditScoreLabel()
    {
        $score = $this->getNumericCreditScore();
        if (!$score) return 'N/A';
        
        if ($score >= 750) return 'Excellent';
        if ($score >= 650) return 'Good';
        if ($score >= 550) return 'Fair';
        return 'Poor';
    }
    
    /**
     * Get numeric credit score, handling arrays and other formats
     */
    private function getNumericCreditScore()
    {
        if (!$this->creditScore) {
            return null;
        }
        
        // If it's already numeric, return it
        if (is_numeric($this->creditScore)) {
            return (float) $this->creditScore;
        }
        
        // If it's an array, try to extract the value
        if (is_array($this->creditScore)) {
            // Try _value key (common in XML parsed structures)
            if (isset($this->creditScore['_value']) && is_numeric($this->creditScore['_value'])) {
                return (float) $this->creditScore['_value'];
            }
            // Try first element if it's numeric
            if (isset($this->creditScore[0]) && is_numeric($this->creditScore[0])) {
                return (float) $this->creditScore[0];
            }
        }
        
        // If it's a string that looks numeric, try to convert
        if (is_string($this->creditScore) && is_numeric($this->creditScore)) {
            return (float) $this->creditScore;
        }
        
        return null;
    }

    public function render()
    {
        return view('livewire.borrower.borrower-dashboard');
    }
}