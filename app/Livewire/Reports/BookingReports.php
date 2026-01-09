<?php

namespace App\Livewire\Reports;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Application;
use App\Models\ApplicationLenderSubmission;
use App\Models\Lender;
use App\Models\LenderCommissionSetting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BookingReports extends Component
{
    use WithPagination;

    // Filters
    public $dateRange = 'all';
    public $lenderFilter = 'all'; // For admin only
    public $statusFilter = 'all';
    public $sortBy = 'booked_at';
    public $sortDirection = 'desc';

    // View mode
    public $viewMode = 'table'; // table, summary

    protected $paginationTheme = 'tailwind';

    public function mount()
    {
        // Ensure user is lender or admin
        if (!in_array(Auth::user()->role, ['lender', 'super_admin', 'admin'])) {
            abort(403, 'Access denied.');
        }
    }

    public function updatingDateRange()
    {
        $this->resetPage();
    }

    public function updatingLenderFilter()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function setViewMode($mode)
    {
        $this->viewMode = $mode;
    }

    public function getBookedApplicationsProperty()
    {
        $query = ApplicationLenderSubmission::with([
            'application.user',
            'application.loanProduct',
            'lender',
            'loanProduct'
        ])
        ->where('status', 'approved')
        ->whereNotNull('booked_at');

        // For lenders, only show their own bookings
        if (Auth::user()->role === 'lender') {
            $query->where('lender_id', Auth::user()->lender_id);
        }

        // For admins, filter by lender if selected
        if ((Auth::user()->isAdmin() || Auth::user()->role === 'super_admin') && $this->lenderFilter !== 'all') {
            $query->where('lender_id', $this->lenderFilter);
        }

        // Date range filter
        if ($this->dateRange !== 'all') {
            $query->where('booked_at', '>=', $this->getDateRangeStart());
        }

        // Status filter (application status)
        if ($this->statusFilter !== 'all') {
            $query->whereHas('application', function($q) {
                $q->where('status', $this->statusFilter);
            });
        }

        // Sorting
        $query->orderBy($this->sortBy, $this->sortDirection);

        return $query->paginate(20);
    }

    public function getSummaryStatsProperty()
    {
        $baseQuery = ApplicationLenderSubmission::where('status', 'approved')
            ->whereNotNull('booked_at');

        // For lenders, only their bookings
        if (Auth::user()->role === 'lender') {
            $baseQuery->where('lender_id', Auth::user()->lender_id);
        }

        // For admins, filter by lender if selected
        if (in_array(Auth::user()->role, ['super_admin', 'admin']) && $this->lenderFilter !== 'all') {
            $baseQuery->where('lender_id', $this->lenderFilter);
        }

        // Date range filter
        if ($this->dateRange !== 'all') {
            $baseQuery->where('booked_at', '>=', $this->getDateRangeStart());
        }

        $totalBookings = $baseQuery->count();
        
        $totalValue = $baseQuery->get()->sum(function($submission) {
            return (float) ($submission->offered_amount ?? $submission->application->requested_amount ?? 0);
        });

        $avgBookingValue = $totalBookings > 0 ? $totalValue / $totalBookings : 0;

        // Calculate total booking fees
        $totalBookingFees = $baseQuery->get()->sum(function($submission) {
            // Use the booking_fee from submission if set (already calculated when booked)
            if ($submission->booking_fee && $submission->booking_fee > 0) {
                return (float) $submission->booking_fee;
            }
            // Fallback: recalculate based on commission settings and loan amount
            $loanAmount = (float) ($submission->offered_amount ?? $submission->application->requested_amount ?? 0);
            return LenderCommissionSetting::getBookingFeeForLender($submission->lender_id, $loanAmount);
        });

        // Status breakdown
        $statusBreakdown = $baseQuery->get()->groupBy(function($submission) {
            return $submission->application->status ?? 'unknown';
        })->map->count();

        // Monthly trends (last 6 months)
        $monthlyTrends = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthStart = $month->copy()->startOfMonth();
            $monthEnd = $month->copy()->endOfMonth();
            
            $monthQuery = clone $baseQuery;
            $count = $monthQuery->whereBetween('booked_at', [$monthStart, $monthEnd])->count();
            
            $monthlyTrends[] = [
                'month' => $month->format('M Y'),
                'count' => $count
            ];
        }

        return [
            'total_bookings' => $totalBookings,
            'total_value' => $totalValue,
            'avg_booking_value' => $avgBookingValue,
            'total_booking_fees' => $totalBookingFees,
            'status_breakdown' => $statusBreakdown,
            'monthly_trends' => $monthlyTrends,
        ];
    }

    public function getTopLendersProperty()
    {
        // Only for admins
        if (!Auth::user()->isAdmin() && Auth::user()->role !== 'super_admin') {
            return collect([]);
        }

        $query = ApplicationLenderSubmission::with('lender')
            ->where('status', 'approved')
            ->whereNotNull('booked_at');

        // Date range filter
        if ($this->dateRange !== 'all') {
            $query->where('booked_at', '>=', $this->getDateRangeStart());
        }

        return $query->select('lender_id', DB::raw('count(*) as booking_count'), DB::raw('sum(coalesce(offered_amount, 0)) as total_value'))
            ->groupBy('lender_id')
            ->orderBy('booking_count', 'desc')
            ->limit(10)
            ->get()
            ->map(function($item) {
                return [
                    'lender' => $item->lender,
                    'booking_count' => $item->booking_count,
                    'total_value' => (float) $item->total_value,
                ];
            });
    }

    public function getLendersProperty()
    {
        // Only for admins
        if (!Auth::user()->isAdmin() && Auth::user()->role !== 'super_admin') {
            return collect([]);
        }

        return Lender::where('status', 'approved')
            ->orderBy('company_name')
            ->get();
    }

    private function getDateRangeStart()
    {
        return match($this->dateRange) {
            'today' => Carbon::now()->startOfDay(),
            'week' => Carbon::now()->subWeek(),
            'month' => Carbon::now()->subMonth(),
            'quarter' => Carbon::now()->subQuarter(),
            'year' => Carbon::now()->subYear(),
            default => Carbon::now()->subYear(),
        };
    }

    public function render()
    {
        return view('livewire.reports.booking-reports', [
            'bookedApplications' => $this->bookedApplications,
            'summaryStats' => $this->summaryStats,
            'topLenders' => $this->topLenders,
            'lenders' => $this->lenders,
        ]);
    }
}

