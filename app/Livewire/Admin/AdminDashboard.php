<?php

namespace App\Livewire\Admin;

use App\Models\NidaVerification;
use Livewire\Component;
use App\Models\User;
use App\Models\Lender;
use App\Models\Application;
use App\Models\LoanProduct;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class AdminDashboard extends Component
{
    private const DASHBOARD_CACHE_TTL_MINUTES = 5;

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
        $cacheKey = 'dashboard:admin:data:v1';
        $cachedData = $this->getCachedData($cacheKey);
        if (is_array($cachedData)) {
            foreach ($cachedData as $property => $value) {
                $this->{$property} = $value;
            }
            return;
        }

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
        $activities = [];

        // Get recent lender approvals
        $recentLenders = Lender::where('status', 'approved')
            //->where('updated_at', '>=', Carbon::now()->subWeek())
            ->orderBy('updated_at', 'desc')
            ->limit(2)
            ->get();
        
        foreach ($recentLenders as $lender) {
            $activities[] = [
                'type' => 'lender_approved',
                'message' => 'New lender approved',
                'details' => $lender->company_name . ' approved',
                'time' => $lender->approved_at->diffForHumans(),
                'color' => 'green'
            ];
        }
        
        // Get recent applications
        $recentApplications = Application::where('created_at', '>=', Carbon::now()->subDay())
            ->orderBy('created_at', 'desc')
            ->limit(2)
            ->get();
        
        foreach ($recentApplications as $application) {
            $activities[] = [
                'type' => 'application_submitted',
                'message' => 'New application received',
                'details' => $application->first_name . ' ' . $application->last_name . ' submitted loan application',
                'time' => $application->created_at->diffForHumans(),
                'color' => 'brand-red'
            ];
        }
        
        // Get recent approved applications
        $approvedApplications = Application::where('status', 'approved')
            ->where('approved_at', '>=', Carbon::now()->subDay())
            ->orderBy('approved_at', 'desc')
            ->limit(2)
            ->get();
        
        foreach ($approvedApplications as $application) {
            $activities[] = [
                'type' => 'application_approved',
                'message' => 'Application approved',
                'details' => $application->first_name . ' ' . $application->last_name . ' - TZS ' . number_format($application->requested_amount),
                'time' => $application->approved_at->diffForHumans(),
                'color' => 'blue'
            ];
        }
        
        // Add system update if NIDA verifications happened
        $nidaCount = NidaVerification::where('verified_at', '>=', Carbon::now()->subHours(6))->count();
        if ($nidaCount > 0) {
            $activities[] = [
                'type' => 'system_update',
                'message' => 'System maintenance',
                'details' => 'NIDA integration updated',
                'time' => '3 hours ago',
                'color' => 'purple'
            ];
        }

        
        
        $this->recentActivity = collect($activities);


        $this->putCachedData($cacheKey, [
            'totalLenders' => $this->totalLenders,
            'pendingLenders' => $this->pendingLenders,
            'approvedLenders' => $this->approvedLenders,
            'totalApplications' => $this->totalApplications,
            'totalBorrowers' => $this->totalBorrowers,
            'recentApplications' => $this->recentApplications,
            'applicationsByStatus' => $this->applicationsByStatus,
            'pendingLendersList' => $this->pendingLendersList,
            'recentActivity' => $this->recentActivity,
            'totalRevenue' => $this->totalRevenue,
            'monthlyRevenue' => $this->monthlyRevenue,
            'approvedApplications' => $this->approvedApplications,
            'rejectedApplications' => $this->rejectedApplications,
            'conversionRate' => $this->conversionRate,
        ]);
    }

    public function loadChartData()
    {
        $cacheKey = 'dashboard:admin:charts:v1';
        $cachedData = $this->getCachedData($cacheKey);
        if (is_array($cachedData)) {
            foreach ($cachedData as $property => $value) {
                $this->{$property} = $value;
            }
            return;
        }

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

        $this->putCachedData($cacheKey, [
            'monthlyLabels' => $this->monthlyLabels,
            'monthlyApplications' => $this->monthlyApplications,
            'monthlyApproved' => $this->monthlyApproved,
            'monthlyRejected' => $this->monthlyRejected,
            'monthlyDisbursed' => $this->monthlyDisbursed,
            'statusLabels' => $this->statusLabels,
            'statusData' => $this->statusData,
        ]);
    }

    private function clearDashboardCache(): void
    {
        $this->forgetCachedData('dashboard:admin:data:v1');
        $this->forgetCachedData('dashboard:admin:charts:v1');
    }

    private function getCachedData(string $key): mixed
    {
        try {
            return Cache::store('redis')->get($key);
        } catch (\Throwable $e) {
            report($e);
        }

        try {
            return Cache::get($key);
        } catch (\Throwable $e) {
            report($e);
            return null;
        }
    }

    private function putCachedData(string $key, array $data): void
    {
        $ttl = now()->addMinutes(self::DASHBOARD_CACHE_TTL_MINUTES);

        try {
            Cache::store('redis')->put($key, $data, $ttl);
            return;
        } catch (\Throwable $e) {
            report($e);
        }

        try {
            Cache::put($key, $data, $ttl);
        } catch (\Throwable $e) {
            report($e);
        }
    }

    private function forgetCachedData(string $key): void
    {
        try {
            Cache::store('redis')->forget($key);
        } catch (\Throwable $e) {
            report($e);
        }

        try {
            Cache::forget($key);
        } catch (\Throwable $e) {
            report($e);
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
        
        $this->clearDashboardCache();
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
        
        $this->clearDashboardCache();
        $this->loadDashboardData();
        $this->loadChartData();
        session()->flash('message', 'Lender rejected.');
    }

    public function suspendLender($lenderId)
    {
        $lender = Lender::find($lenderId);
        $lender->update(['status' => 'suspended']);
        
        $this->clearDashboardCache();
        $this->loadDashboardData();
        $this->loadChartData();
        session()->flash('message', 'Lender suspended.');
    }

    public function refreshCharts()
    {
        $this->clearDashboardCache();
        $this->loadChartData();
        $this->dispatch('refreshCharts');
    }

    public function render()
    {
        return view('livewire.admin.admin-dashboard');
    }
}