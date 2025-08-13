<?php

namespace App\Livewire\Leads;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Application;
use App\Models\ApplicationLenderSubmission;
use App\Models\LoanProduct;
use App\Models\Lender;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LeadManagement extends Component
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
    public $leadTypeFilter = 'available'; // available, booked, submitted
    
    // View states
    public $currentStep = 'list';
    public $selectedLead = null;
    public $viewMode = 'grid'; // grid, table
    public $showFilters = false;
    
    // Lead management
    public $selectedLeads = [];
    public $selectAll = false;
    public $leadNotes = '';
    public $offerAmount = null;
    public $offerInterestRate = null;
    public $offerTenure = null;

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

    public function mount()
    {
        // Ensure user is a lender
        if (Auth::user()->role !== 'lender' || !Auth::user()->lender_id) {
            abort(403, 'Access denied. Lender access required.');
        }
    }

    // Search and filtering
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

    // View management
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

    // Lead actions
    public function viewLead($leadId)
    {
        if ($this->leadTypeFilter === 'available') {
            $this->selectedLead = Application::with(['loanProduct', 'user'])
                ->where('booking_status', 'unbooked')
                ->where('status', 'submitted')
                ->find($leadId);
        } else {
            $this->selectedLead = ApplicationLenderSubmission::with(['application.loanProduct', 'application.user', 'lender'])
                ->where('lender_id', Auth::user()->lender_id)
                ->find($leadId);
        }
        
        $this->currentStep = 'view';
    }

    public function bookLead($applicationId)
    {
        try {
            DB::beginTransaction();

            $application = Application::where('id', $applicationId)
                ->where('booking_status', 'unbooked')
                ->where('status', 'submitted')
                ->first();

            if (!$application) {
                $this->dispatch('show-alert', [
                    'type' => 'error',
                    'message' => 'Lead is no longer available or has been booked by another lender.'
                ]);
                return;
            }

            // Create submission record
            $submission = ApplicationLenderSubmission::create([
                'user_id' => $application->user_id,
                'application_id' => $application->id,
                'lender_id' => Auth::user()->lender_id,
                'status' => 'submitted',
                'submitted_at' => now(),
                'submission_data' => [
                    'booked_by' => Auth::id(),
                    'booking_method' => 'manual',
                    'booking_timestamp' => now()
                ]
            ]);

            // Update application status
            $application->update([
                'booking_status' => 'booked',
                'lender_id' => Auth::user()->lender_id,
                'status' => 'under_review',
                'reviewed_at' => now(),
                'reviewed_by' => Auth::id()
            ]);

            Log::info('Lead booked successfully', [
                'application_id' => $applicationId,
                'lender_id' => Auth::user()->lender_id,
                'submission_id' => $submission->id
            ]);

            DB::commit();

            $this->dispatch('show-alert', [
                'type' => 'success',
                'message' => 'Lead booked successfully! You can now process this application.'
            ]);

            // Refresh the leads list
            $this->resetPage();

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Failed to book lead', [
                'application_id' => $applicationId,
                'error' => $e->getMessage(),
                'lender_id' => Auth::user()->lender_id
            ]);

            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'Failed to book lead. Please try again.'
            ]);
        }
    }

    public function processLead($submissionId, $decision)
    {
        try {
            DB::beginTransaction();

            $submission = ApplicationLenderSubmission::where('id', $submissionId)
                ->where('lender_id', Auth::user()->lender_id)
                ->first();

            if (!$submission) {
                $this->dispatch('show-alert', [
                    'type' => 'error',
                    'message' => 'Submission not found.'
                ]);
                return;
            }

            if ($decision === 'approve') {
                $submission->update([
                    'status' => 'approved',
                    'decision_at' => now(),
                    'offered_amount' => $this->offerAmount,
                    'offered_interest_rate' => $this->offerInterestRate,
                    'offered_tenure_months' => $this->offerTenure,
                    'lender_response' => [
                        'decision' => 'approved',
                        'notes' => $this->leadNotes,
                        'processed_by' => Auth::id()
                    ]
                ]);

                $submission->application->update([
                    'status' => 'approved',
                    'approved_at' => now(),
                    'notes' => $this->leadNotes
                ]);

            } elseif ($decision === 'reject') {
                $submission->update([
                    'status' => 'rejected',
                    'decision_at' => now(),
                    'rejection_reason' => $this->leadNotes,
                    'lender_response' => [
                        'decision' => 'rejected',
                        'notes' => $this->leadNotes,
                        'processed_by' => Auth::id()
                    ]
                ]);

                // Return to market for other lenders
                $submission->application->update([
                    'booking_status' => 'unbooked',
                    'lender_id' => null,
                    'status' => 'submitted',
                    'reviewed_at' => null,
                    'reviewed_by' => null
                ]);
            }

            DB::commit();

            $this->dispatch('show-alert', [
                'type' => 'success',
                'message' => ucfirst($decision) . ' processed successfully.'
            ]);

            $this->resetLeadForm();
            $this->currentStep = 'list';

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Failed to process lead', [
                'submission_id' => $submissionId,
                'decision' => $decision,
                'error' => $e->getMessage()
            ]);

            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'Failed to process lead. Please try again.'
            ]);
        }
    }

    // Navigation
    public function backToList()
    {
        $this->currentStep = 'list';
        $this->selectedLead = null;
        $this->resetLeadForm();
    }

    private function resetLeadForm()
    {
        $this->leadNotes = '';
        $this->offerAmount = null;
        $this->offerInterestRate = null;
        $this->offerTenure = null;
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
                    ->where('status', 'rejected');
            });

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

        return $query->paginate(20);
    }

    private function getBookedLeads()
    {
        $query = ApplicationLenderSubmission::with(['application.loanProduct', 'application.user', 'lender'])
            ->where('lender_id', Auth::user()->lender_id);

        // Status filter
        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }

        // Search
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

        // Sorting
        $query->orderBy($this->sortBy, $this->sortDirection);

        return $query->paginate(20);
    }

    public function getStatsProperty()
    {
        $lenderId = Auth::user()->lender_id;
        
        return [
            'available_leads' => Application::where('booking_status', 'unbooked')
                ->where('status', 'submitted')
                ->count(),
            'my_leads' => ApplicationLenderSubmission::where('lender_id', $lenderId)->count(),
            'pending_review' => ApplicationLenderSubmission::where('lender_id', $lenderId)
                ->where('status', 'submitted')->count(),
            'approved' => ApplicationLenderSubmission::where('lender_id', $lenderId)
                ->where('status', 'approved')->count(),
            'rejected' => ApplicationLenderSubmission::where('lender_id', $lenderId)
                ->where('status', 'rejected')->count(),
           'total_value' => ApplicationLenderSubmission::where('application_lender_submissions.lender_id', $lenderId)
    ->where('application_lender_submissions.status', 'approved')
    ->whereHas('application')
    ->join('applications', 'application_lender_submissions.application_id', '=', 'applications.id')
    ->sum('applications.requested_amount'),
        ];
    }

    public function getLoanProductsProperty()
    {
        return LoanProduct::where('is_active', true)
            ->where('lender_id', Auth::user()->lender_id)
            ->get();
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
        return view('livewire.leads.lead-management', [
            'leads' => $this->leads,
            'stats' => $this->stats,
            'loanProducts' => $this->loanProducts,
        ]);
    }
}