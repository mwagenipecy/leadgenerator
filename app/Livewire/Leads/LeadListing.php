<?php

namespace App\Livewire\Leads;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Application;
use App\Models\ApplicationLenderSubmission;
use App\Models\LoanProduct;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LeadListing extends Component
{
    use WithPagination;

    // Search and filters
    public $search = '';
    public $statusFilter = 'all';
    public $sortBy = 'created_at';
    public $sortDirection = 'desc';
    public $dateRange = 'all';
    public $amountRange = 'all';
    public $crbScoreRange = 'all';
    public $leadTypeFilter = 'available'; // available, booked
    
    // View states
    public $viewMode = 'grid'; // grid, table
    public $showFilters = false;
    
    // Booking modal states
    public $showBookingModal = false;
    public $selectedLead = null;
    public $bookingFee = 50000; // Default booking fee in TSh

    protected $paginationTheme = 'tailwind';

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => 'all'],
        'leadTypeFilter' => ['except' => 'available'],
        'sortBy' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
        'dateRange' => ['except' => 'all'],
        'page' => ['except' => 1],
    ];

    protected $listeners = [
        'leadBooked' => '$refresh',
        'leadProcessed' => '$refresh',
        'refreshLeads' => '$refresh',
        'openBookingModal' => 'openBookingModal',
        'closeBookingModal' => 'closeBookingModal'
    ];

    public function mount()
    {
        // Ensure user is a lender
        if (Auth::user()->role !== 'lender' || !Auth::user()->lender_id) {
            abort(403, 'Access denied. Lender access required.');
        }
    }

    // Booking modal methods
    public function openBookingModal($leadId)
    {
        $this->selectedLead = Application::find($leadId);
        $this->showBookingModal = true;
    }

    public function closeBookingModal()
    {
        $this->showBookingModal = false;
        $this->selectedLead = null;
    }

    public function confirmBooking()
    {
        if (!$this->selectedLead) {
            return;
        }

        try {
            DB::beginTransaction();

            // Create the booking submission
            $submission = ApplicationLenderSubmission::create([
                'application_id' => $this->selectedLead->id,
                'lender_id' => Auth::user()->lender_id,
                'status' => 'submitted',
                'booking_fee' => $this->bookingFee,
                'booked_at' => now(),
                'notes' => 'Lead booked via portal'
            ]);

            // Update application booking status
            $this->selectedLead->update([
                'booking_status' => 'booked'
            ]);

            DB::commit();

            // Close modal
            $this->closeBookingModal();

            // Show success message
            session()->flash('success', 'Lead successfully booked! You will be charged TSh ' . number_format($this->bookingFee) . ' for this booking.');
            
            // Refresh the leads list
            $this->dispatch('leadBooked');
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Lead booking failed', [
                'lead_id' => $this->selectedLead->id,
                'lender_id' => Auth::user()->lender_id,
                'error' => $e->getMessage()
            ]);
            
            session()->flash('error', 'Failed to book lead. Please try again.');
        }
    }

    public function cancelBooking($submissionId)
    {
        try {
            DB::beginTransaction();

            $submission = ApplicationLenderSubmission::where('id', $submissionId)
                ->where('lender_id', Auth::user()->lender_id)
                ->first();

            if (!$submission) {
                session()->flash('error', 'Booking not found.');
                return;
            }

            // Update submission status to cancelled
            $submission->update([
                'status' => 'cancelled',
                'cancelled_at' => now(),
                'cancellation_reason' => 'Cancelled by lender'
            ]);

            // Update application status back to unbooked if this was the only booking
            $activeBookings = ApplicationLenderSubmission::where('application_id', $submission->application_id)
                ->whereNotIn('status', ['cancelled', 'rejected'])
                ->count();

            if ($activeBookings === 0) {
                $submission->application->update([
                    'booking_status' => 'unbooked'
                ]);
            }

            DB::commit();

            session()->flash('success', 'Booking cancelled successfully. This lead will be available to other lenders.');
            $this->dispatch('leadProcessed');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Booking cancellation failed', [
                'submission_id' => $submissionId,
                'error' => $e->getMessage()
            ]);
            
            session()->flash('error', 'Failed to cancel booking. Please try again.');
        }
    }

    // Search and filtering methods
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingLeadTypeFilter()
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
        $this->crbScoreRange = 'all';
        $this->resetPage();
    }

    public function toggleFilters()
    {
        $this->showFilters = !$this->showFilters;
    }

    public function setViewMode($mode)
    {
        $this->viewMode = $mode;
    }

    public function setLeadTypeFilter($type)
    {
        $this->leadTypeFilter = $type;
        $this->resetPage();
    }

    // Data properties
    public function getLeadsProperty()
    {
        if ($this->leadTypeFilter === 'available') {
            return $this->getAvailableLeads();
        } else {
            return $this->getBookedLeads();
        }
    }

    private function getAvailableLeads()
    {
        $query = Application::with(['loanProduct', 'user'])
            ->where('booking_status', 'unbooked')
            ->where('status', 'submitted')
            ->whereNotIn('id', function($subquery) {
                $subquery->select('application_id')
                    ->from('application_lender_submissions')
                    ->where('lender_id', Auth::user()->lender_id)
                    ->whereIn('status', ['rejected', 'cancelled']);
            });

        // Apply filters
        $this->applyFilters($query);

        return $query->paginate(20);
    }

    private function getBookedLeads()
    {
        $query = ApplicationLenderSubmission::with(['application.loanProduct', 'application.user', 'lender'])
            ->where('application_lender_submissions.lender_id', Auth::user()->lender_id)
            ->where('application_lender_submissions.status', 'approved');

        // Status filter for booked leads
        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }

        // Apply other filters
        $this->applyFiltersToBookedLeads($query);

        return $query->paginate(20);
    }


    public function viewLead($selected){

      //  dd($selected);

        $this->dispatch('viewLead', [
            'leadId' => $selected["application_id"],
            'isAvailable' => true
        ]);
    }




    private function applyFilters($query)
    {
        // Search
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('application_number', 'like', '%' . $this->search . '%')
                  ->orWhere('first_name', 'like', '%' . $this->search . '%')
                  ->orWhere('last_name', 'like', '%' . $this->search . '%');
            });
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

        // CRB Score range filter
        if ($this->crbScoreRange !== 'all') {
            [$min, $max] = $this->getCrbScoreRange();
            $query->whereBetween('credit_score', [$min, $max]);
        }

        // Sorting
        $query->orderBy($this->sortBy, $this->sortDirection);
    }

    private function applyFiltersToBookedLeads($query)
    {
        // Search in related application
        if ($this->search) {
            $query->whereHas('application', function ($q) {
                $q->where('application_number', 'like', '%' . $this->search . '%')
                  ->orWhere('first_name', 'like', '%' . $this->search . '%')
                  ->orWhere('last_name', 'like', '%' . $this->search . '%');
            });
        }

        // Date range filter
        if ($this->dateRange !== 'all') {
            $query->where('created_at', '>=', $this->getDateRangeStart());
        }

        // Amount range filter for booked leads
        if ($this->amountRange !== 'all') {
            [$min, $max] = $this->getAmountRange();
            $query->whereHas('application', function ($q) use ($min, $max) {
                $q->whereBetween('requested_amount', [$min, $max]);
            });
        }

        // Sorting
        if ($this->sortBy === 'requested_amount' || $this->sortBy === 'credit_score') {
            $query->join('applications', 'application_lender_submissions.application_id', '=', 'applications.id')
                  ->orderBy('applications.' . $this->sortBy, $this->sortDirection)
                  ->select('application_lender_submissions.*');
        } else {
            $query->orderBy($this->sortBy, $this->sortDirection);
        }
    }

    public function getStatsProperty()
    {
        $lenderId = Auth::user()->lender_id;
        
        return [
            'available_leads' => Application::where('booking_status', 'unbooked')
                ->where('status', 'submitted')
                ->whereNotIn('id', function($subquery) use ($lenderId) {
                    $subquery->select('application_id')
                        ->from('application_lender_submissions')
                        ->where('lender_id', $lenderId)
                        ->whereIn('status', ['rejected', 'cancelled']);
                })
                ->count(),
            'my_leads' => ApplicationLenderSubmission::where('lender_id', $lenderId)
                ->whereNotIn('status', ['cancelled'])
                ->count(),
            'pending_review' => ApplicationLenderSubmission::where('lender_id', $lenderId)
                ->where('status', 'submitted')->count(),
            'approved' => ApplicationLenderSubmission::where('lender_id', $lenderId)
                ->where('status', 'approved')->count(),
            'rejected' => ApplicationLenderSubmission::where('lender_id', $lenderId)
                ->where('status', 'rejected')->count(),
            'total_value' => ApplicationLenderSubmission::where('application_lender_submissions.lender_id', $lenderId)
                ->where('application_lender_submissions.status', 'approved')
                ->join('applications', 'application_lender_submissions.application_id', '=', 'applications.id')
                ->sum('applications.requested_amount'),
        ];
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

    private function getCrbScoreRange()
    {
        return match($this->crbScoreRange) {
            'excellent' => [750, 850],
            'good' => [650, 749],
            'fair' => [550, 649],
            'poor' => [300, 549],
            default => [0, 1000],
        };
    }

    public function render()
    {
        return view('livewire.leads.lead-listing', [
            'leads' => $this->leads,
            'stats' => $this->stats,
        ]);
    }
}