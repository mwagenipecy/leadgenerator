<?php

namespace App\Livewire\Leads;

use App\Models\CommissionBill;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Application;
use App\Models\ApplicationLenderSubmission;
use App\Models\LoanProduct;
use App\Models\LenderCommissionSetting;
use App\Models\SystemSetting;
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
    public $viewMode = 'table'; // grid, table
    public $showFilters = false;
    
    // Booking modal states
    public $showBookingModal = false;
    public $selectedLead = null;
    public $bookingFee = 50000; // Will be set dynamically based on lender settings

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
    public function openBookingModal($applicationId)
    {
        // The view passes the application ID, so we just need to load the application
        // The booking logic will find the submission by application_id and lender_id
        $this->selectedLead = Application::find($applicationId);
        
        if (!$this->selectedLead) {
            session()->flash('error', 'Application not found.');
            return;
        }
        
        // Calculate booking fee based on lender's commission settings or default
        // Uses percentage of loan amount or fixed amount based on commission configuration
        $loanAmount = (float) ($this->selectedLead->requested_amount ?? 0);
        $this->bookingFee = LenderCommissionSetting::getBookingFeeForLender(
            Auth::user()->lender_id,
            $loanAmount
        );
        
        // Log for debugging
        Log::info('Booking modal opened', [
            'application_id' => $applicationId,
            'lender_id' => Auth::user()->lender_id,
            'loan_amount' => $loanAmount,
            'calculated_booking_fee' => $this->bookingFee,
        ]);
        
        $this->showBookingModal = true;
    }

    public function closeBookingModal()
    {
        $this->showBookingModal = false;
        $this->selectedLead = null;
    }

    public function confirmBooking()
    {
        // Early validation
        if (!$this->selectedLead) {
            session()->flash('error', 'No lead selected for booking.');
            $this->dispatch('booking-error', ['message' => 'No lead selected for booking.']);
            return;
        }
    
        // Recalculate booking fee to ensure it's current
        $loanAmount = (float) ($this->selectedLead->requested_amount ?? 0);
        $this->bookingFee = LenderCommissionSetting::getBookingFeeForLender(
            Auth::user()->lender_id,
            $loanAmount
        );
    
        if (!$this->bookingFee || $this->bookingFee <= 0) {
            $errorMsg = 'Invalid booking fee amount. Please ensure commission settings are configured in system settings.';
            session()->flash('error', $errorMsg);
            Log::warning('Booking failed: Invalid booking fee', [
                'lender_id' => Auth::user()->lender_id,
                'loan_amount' => $loanAmount,
                'calculated_booking_fee' => $this->bookingFee,
            ]);
            $this->dispatch('booking-error', ['message' => $errorMsg]);
            return;
        }
    
        $currentLenderId = Auth::user()->lender_id;
        $applicationId = $this->selectedLead->id;


    
        if (!$currentLenderId) {
            session()->flash('error', 'User is not associated with a lender.');
            return;
        }
    
        try {
            DB::beginTransaction();
    
            // Lock the application row to prevent concurrent bookings
            $application = Application::lockForUpdate()->find($applicationId);
            
            if (!$application) {
                throw new \Exception('Application not found.');
            }
            
            // Check if lead is already booked by another lender
            if ($application->booking_status === 'booked' && $application->lender_id !== $currentLenderId) {
                throw new \Exception('This lead has already been booked by another lender.');
            }
            
            // Check if submission exists and is in correct status
            $currentSubmission = ApplicationLenderSubmission::where('application_id', $applicationId)
                ->where('lender_id', $currentLenderId)
                ->where('status', 'submitted')
                ->first();
    
            if (!$currentSubmission) {
                throw new \Exception('No valid submission found for this lender and application.');
            }
    
            // Update current lender's submission to approved
            $updatedRows = ApplicationLenderSubmission::where('application_id', $applicationId)
                ->where('lender_id', $currentLenderId)
                ->where('status', 'submitted')
                ->update([
                    'status' => 'approved',
                    'booking_fee' => $this->bookingFee,
                    'booked_at' => now(),
                    'notes' => 'Lead booked via portal',
                    'updated_at' => now()
                ]);
    
            if ($updatedRows === 0) {
                throw new \Exception('Failed to update submission status. The submission may have been modified by another process.');
            }
    
            // Withdraw other lenders' submissions
            $withdrawnCount = ApplicationLenderSubmission::where('application_id', $applicationId)
                ->where('lender_id', '!=', $currentLenderId)
                ->where('status', 'submitted')
                ->update([
                    'status' => 'withdrawn',
                    'booking_fee' => 0,
                    'booked_at' => now(),
                    'notes' => 'Lead booked by another lender via portal',
                    'updated_at' => now()
                ]);
    
            // Update application booking status (use the locked application instance)
            $application->update([
                'booking_status' => 'booked',
                'lender_id' => $currentLenderId,
                'status'=>'under_review',
                'loan_product_id' => $currentSubmission->loan_product_id,
                'booked_at' => now(),
                'updated_at' => now()
            ]);
    
            // Generate commission bill (use the updated application instance)
            $this->generateCommissionBill($application);
    
            // Log successful booking
            Log::info('Lead booking successful', [
                'lead_id' => $applicationId,
                'lender_id' => $currentLenderId,
                'booking_fee' => $this->bookingFee,
                'withdrawn_submissions' => $withdrawnCount,
                'loan_product_id' => $currentSubmission->loan_product_id
            ]);
    
            DB::commit();
    
            // Close modal and show success message
            $this->closeBookingModal();
            
            session()->flash('success', sprintf(
                'Lead successfully booked! You will be charged TSh %s for this booking. %d other submission(s) have been withdrawn.',
                number_format($this->bookingFee),
                $withdrawnCount
            ));
            
            // Refresh the leads list
            $this->dispatch('leadBooked');
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            // Enhanced error logging
            Log::error('Lead booking failed', [
                'lead_id' => $applicationId,
                'lender_id' => $currentLenderId,
                'booking_fee' => $this->bookingFee ?? 'not_set',
                'error_message' => $e->getMessage(),
                'error_trace' => $e->getTraceAsString(),
                'user_id' => Auth::id(),
                'timestamp' => now()
            ]);
            
            // User-friendly error message
            $errorMessage = 'Failed to book lead. ';
            
            if (str_contains($e->getMessage(), 'already been booked')) {
                $errorMessage .= 'This lead has already been booked by another lender.';
            } elseif (str_contains($e->getMessage(), 'No valid submission')) {
                $errorMessage .= 'Your submission for this lead was not found or is no longer valid.';
            } elseif (str_contains($e->getMessage(), 'modified by another process')) {
                $errorMessage .= 'The lead status was changed while you were booking. Please refresh and try again.';
            } else {
                $errorMessage .= 'Please try again or contact support if the problem persists.';
            }
            
            session()->flash('error', $errorMessage);
            $this->dispatch('booking-error', ['message' => $errorMessage]);
        }
    }
    
    /**
     * Helper method to validate booking prerequisites
     */
    private function validateBookingPrerequisites(): array
    {
        $errors = [];
        
        if (!$this->selectedLead) {
            $errors[] = 'No lead selected for booking.';
        }
        
        if (!$this->bookingFee || $this->bookingFee <= 0) {
            $errors[] = 'Invalid booking fee amount.';
        }
        
        if (!Auth::user()->lender_id) {
            $errors[] = 'User is not associated with a lender.';
        }
        
        if ($this->selectedLead && $this->selectedLead->booking_status === 'booked') {
            $errors[] = 'This lead has already been booked.';
        }
        
        return $errors;
    }
    
    /**
     * Alternative version using the validation helper
     */
    public function confirmBookingWithValidation()
    {
        // Validate prerequisites
        $validationErrors = $this->validateBookingPrerequisites();
        
        if (!empty($validationErrors)) {
            session()->flash('error', implode(' ', $validationErrors));
            return;
        }
        
        $currentLenderId = Auth::user()->lender_id;
        $applicationId = $this->selectedLead->id;
        
        try {
            DB::beginTransaction();
            
            // Rest of the booking logic here...
            $this->executeBookingTransaction($currentLenderId, $applicationId);
            
            DB::commit();
            $this->handleBookingSuccess();
            
        } catch (\Exception $e) {
            DB::rollBack();
            $this->handleBookingError($e, $applicationId, $currentLenderId);
        }
    }
    
    /**
     * Execute the main booking transaction
     */
    private function executeBookingTransaction(int $currentLenderId, int $applicationId): void
    {
        // Get and validate current submission
        $currentSubmission = $this->getCurrentSubmission($applicationId, $currentLenderId);
        
        // Update current lender's submission
        $this->approveCurrentSubmission($applicationId, $currentLenderId);
        
        // Withdraw other submissions
        $withdrawnCount = $this->withdrawOtherSubmissions($applicationId, $currentLenderId);
        
        // Update application
        $this->updateApplicationBookingStatus($currentSubmission, $currentLenderId);
        
        // Generate commission bill
        $this->generateCommissionBill($this->selectedLead);
        
        // Log success
        $this->logBookingSuccess($applicationId, $currentLenderId, $withdrawnCount, $currentSubmission);
    }
    
    /**
     * Get current submission with validation
     */
    private function getCurrentSubmission(int $applicationId, int $currentLenderId): ApplicationLenderSubmission
    {
        $submission = ApplicationLenderSubmission::where('application_id', $applicationId)
            ->where('lender_id', $currentLenderId)
            ->where('status', 'submitted')
            ->first();
            
        if (!$submission) {
            throw new \Exception('No valid submission found for this lender and application.');
        }
        
        return $submission;
    }
    
    /**
     * Approve current lender's submission
     */
    private function approveCurrentSubmission(int $applicationId, int $currentLenderId): void
    {
        $updatedRows = ApplicationLenderSubmission::where('application_id', $applicationId)
            ->where('lender_id', $currentLenderId)
            ->where('status', 'submitted')
            ->update([
                'status' => 'approved',
                'booking_fee' => $this->bookingFee,
                'booked_at' => now(),
                'notes' => 'Lead booked via portal',
                'updated_at' => now()
            ]);
    
        if ($updatedRows === 0) {
            throw new \Exception('Failed to update submission status. The submission may have been modified.');
        }
    }
    
    /**
     * Withdraw other lenders' submissions
     */
    private function withdrawOtherSubmissions(int $applicationId, int $currentLenderId): int
    {
        return ApplicationLenderSubmission::where('application_id', $applicationId)
            ->where('lender_id', '!=', $currentLenderId)
            ->where('status', 'submitted')
            ->update([
                'status' => 'withdrawn',
                'booking_fee' => 0,
                'withdrawn_at' => now(),
                'notes' => 'Lead booked by another lender via portal',
                'updated_at' => now()
            ]);
    }
    
    /**
     * Update application booking status
     */
    private function updateApplicationBookingStatus(ApplicationLenderSubmission $submission, int $currentLenderId): void
    {
        $updated = $this->selectedLead->update([
            'booking_status' => 'booked',
            'lender_id' => $currentLenderId,
            'loan_product_id' => $submission->loan_product_id,
            'booked_at' => now(),
            'updated_at' => now()
        ]);
    
        if (!$updated) {
            throw new \Exception('Failed to update application booking status.');
        }
    }
    
    /**
     * Handle successful booking
     */
    private function handleBookingSuccess(): void
    {
        $this->closeBookingModal();
        
        session()->flash('success', sprintf(
            'Lead successfully booked! You will be charged TSh %s for this booking.',
            number_format($this->bookingFee)
        ));
        
        $this->dispatch('leadBooked');
    }
    
    /**
     * Handle booking errors
     */
    private function handleBookingError(\Exception $e, int $applicationId, int $currentLenderId): void
    {
        Log::error('Lead booking failed', [
            'lead_id' => $applicationId,
            'lender_id' => $currentLenderId,
            'booking_fee' => $this->bookingFee ?? 'not_set',
            'error_message' => $e->getMessage(),
            'user_id' => Auth::id(),
            'timestamp' => now()
        ]);
        
        session()->flash('error', $this->getErrorMessage($e));
    }
    
    /**
     * Get user-friendly error message
     */
    private function getErrorMessage(\Exception $e): string
    {
        $message = $e->getMessage();
        
        if (str_contains($message, 'already been booked')) {
            return 'This lead has already been booked by another lender.';
        }
        
        if (str_contains($message, 'No valid submission')) {
            return 'Your submission for this lead was not found or is no longer valid.';
        }
        
        if (str_contains($message, 'modified')) {
            return 'The lead status was changed while you were booking. Please refresh and try again.';
        }
        
        return 'Failed to book lead. Please try again or contact support if the problem persists.';
    }
    
    /**
     * Log successful booking
     */
    private function logBookingSuccess(int $applicationId, int $currentLenderId, int $withdrawnCount, ApplicationLenderSubmission $submission): void
    {
        Log::info('Lead booking successful', [
            'lead_id' => $applicationId,
            'lender_id' => $currentLenderId,
            'booking_fee' => $this->bookingFee,
            'withdrawn_submissions' => $withdrawnCount,
            'loan_product_id' => $submission->loan_product_id,
            'user_id' => Auth::id(),
            'timestamp' => now()
        ]);
    }



    /**
 * Generate commission bill for the booked application
 */
private function generateCommissionBill(Application $application)
{
    try {
        // Check if commission bill already exists for this application
        $existingBill = CommissionBill::where('application_id', $application->id)->first();
        
        if ($existingBill) {
            Log::info('Commission bill already exists for application', [
                'application_id' => $application->id,
                'bill_id' => $existingBill->id
            ]);
            return $existingBill;
        }



        // Calculate commission based on application details
        $commissionData = $this->calculateCommission($application);


        

        // Create commission bill
        $bill = CommissionBill::create([
            'application_id' => $application->id,
            'lender_id' => $application->lender_id,
            'bill_number' => $this->generateBillNumber(),
            'commission_type' => $commissionData['type'],
            'commission_rate' => $commissionData['rate'],
            'loan_amount' => $application->requested_amount,
            'commission_amount' => $commissionData['commission_amount'],
            'tax_amount' => $commissionData['tax_amount'],
            'total_amount' => $commissionData['total_amount'],
            'status' => 'pending',
            'due_date' => now()->addDays(30), // 30 days from booking
            'created_by' => Auth::id(),
            'notes' => "Commission for application {$application->application_number}",
            // 'metadata' => [
            //     'application_number' => $application->application_number,
            //     'loan_amount' => $application->requested_amount,
            //     'applicant_name' => $application->first_name . ' ' . $application->last_name,
            //     'generated_via' => 'booking_process'
            // ]
        ]);



        Log::info('Commission bill generated', [
            'application_id' => $application->id,
            'bill_id' => $bill->id,
            'amount' => $bill->amount
        ]);

        return $bill;

    } catch (\Exception $e) {
        Log::error('Failed to generate commission bill', [
            'application_id' => $application->id,
            'error' => $e->getMessage()
        ]);
        
        // Don't throw exception here as booking should still succeed
        // even if bill generation fails
    }
}





private function calculateCommission(Application $application)
{
    // Get lender-specific commission settings or default
    $lenderSetting = LenderCommissionSetting::where('lender_id', $application->lender_id)->first();
    
    if ($lenderSetting && $lenderSetting->is_active) {
        $commissionType = $lenderSetting->commission_type;
        $commissionRate = $lenderSetting->commission_type === 'percentage' 
            ? $lenderSetting->commission_percentage 
            : $lenderSetting->commission_fixed_amount;
        $minimumAmount = $lenderSetting->minimum_amount;
        $maximumAmount = $lenderSetting->maximum_amount;
    } else {
        // Use default settings
        $defaultType = SystemSetting::where('key', 'default_commission_type')->value('value') ?: 'percentage';
        $commissionType = $defaultType;
        $commissionRate = $defaultType === 'percentage' 
            ? (float) (SystemSetting::where('key', 'default_commission_percentage')->value('value') ?: 5.0)
            : (float) (SystemSetting::where('key', 'default_commission_fixed_amount')->value('value') ?: 0);
        $minimumAmount = (float) (SystemSetting::where('key', 'minimum_commission_amount')->value('value') ?: 100);
        $maximumAmount = SystemSetting::where('key', 'maximum_commission_amount')->value('value');
    }

    // Calculate commission amount
    if ($commissionType === 'percentage') {
        $commissionAmount = ($application->requested_amount * $commissionRate) / 100;
    } else {
        $commissionAmount = $commissionRate;
    }

    // Apply limits
    if ($minimumAmount && $commissionAmount < $minimumAmount) {
        $commissionAmount = $minimumAmount;
    }
    if ($maximumAmount) {
        $commissionAmount = min($commissionAmount, (float) $maximumAmount);
    }

    // Calculate tax
    $taxRate = (float) (SystemSetting::where('key', 'tax_rate')->value('value') ?: 18.0);
    $taxAmount = ($commissionAmount * $taxRate) / 100;
    $totalAmount = $commissionAmount + $taxAmount;

    return [
        'type' => $commissionType,
        'rate' => $commissionRate,
        'base_amount' => $application->requested_amount,
        'commission_rate' => $commissionRate,
        'commission_amount' => $commissionAmount,
        'tax_amount' => $taxAmount,
        'total_amount' => $totalAmount,
        'currency' => 'TSh'
    ];
}

/**
 * Generate unique bill number
 */
private function generateBillNumber()
{
    $prefix = 'BILL';
    $year = date('Y');
    $month = date('m');
    
    // Get the last bill number for this month
    $lastBill = CommissionBill::where('bill_number', 'like', "{$prefix}-{$year}{$month}-%")
        ->orderBy('bill_number', 'desc')
        ->first();
    
    if ($lastBill) {
        $lastNumber = (int) substr($lastBill->bill_number, -4);
        $newNumber = $lastNumber + 1;
    } else {
        $newNumber = 1;
    }
    
    return sprintf('%s-%s%s-%04d', $prefix, $year, $month, $newNumber);
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
        $lenderId = Auth::user()->lender_id;
        
        // Get submissions from application_lender_submissions table for this lender
        // that are in 'submitted' status and the application hasn't been booked by any lender yet
        $query = ApplicationLenderSubmission::with(['application.user', 'lender', 'loanProduct'])
            ->where('lender_id', $lenderId)
            ->where('status', 'submitted')
            ->whereHas('application', function($subquery) {
                // Application must be unbooked (not booked by any lender)
                $subquery->where('booking_status', 'unbooked')
                         ->where('status', 'submitted');
            });

        // Apply filters to available leads
        $this->applyFiltersToAvailableLeads($query);

        return $query->paginate(20);
    }

    private function getBookedLeads()
    {
        $query = ApplicationLenderSubmission::with(['application.user', 'lender', 'loanProduct'])
              ->where('application_lender_submissions.status', 'approved')
            ->whereHas('application', function($subquery) {
                $subquery->where('lender_id', Auth::user()->lender_id);
            });
    
        // Status filter for booked leads
        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }
    
        // Apply other filters
        $this->applyFiltersToBookedLeads($query);
    
        return $query->paginate(20);
    }


    public function viewLead($selected){
        // $selected can be either an array with application_id or a submission ID
        if (is_array($selected) && isset($selected["application_id"])) {
            $applicationId = $selected["application_id"];
        } else {
            // If it's a submission ID, get the application_id from the submission
            $submission = ApplicationLenderSubmission::find($selected);
            $applicationId = $submission ? $submission->application_id : $selected;
        }

        $this->dispatch('viewLead', [
            'leadId' => $applicationId,
            'isAvailable' => ($this->leadTypeFilter === 'available')
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

    private function applyFiltersToAvailableLeads($query)
    {
        // Search in related application
        if ($this->search) {
            $query->whereHas('application', function ($q) {
                $q->where('application_number', 'like', '%' . $this->search . '%')
                  ->orWhere('first_name', 'like', '%' . $this->search . '%')
                  ->orWhere('last_name', 'like', '%' . $this->search . '%');
            });
        }

        // Date range filter (use submission created_at or application created_at)
        if ($this->dateRange !== 'all') {
            $query->whereHas('application', function ($q) {
                $q->where('created_at', '>=', $this->getDateRangeStart());
            });
        }

        // Amount range filter for available leads
        if ($this->amountRange !== 'all') {
            [$min, $max] = $this->getAmountRange();
            $query->whereHas('application', function ($q) use ($min, $max) {
                $q->whereBetween('requested_amount', [$min, $max]);
            });
        }

        // CRB Score range filter
        if ($this->crbScoreRange !== 'all') {
            [$min, $max] = $this->getCrbScoreRange();
            $query->whereHas('application', function ($q) use ($min, $max) {
                $q->whereBetween('credit_score', [$min, $max]);
            });
        }

        // Sorting
        if ($this->sortBy === 'requested_amount' || $this->sortBy === 'credit_score' || $this->sortBy === 'application_number' || $this->sortBy === 'first_name' || $this->sortBy === 'last_name') {
            $query->join('applications', 'application_lender_submissions.application_id', '=', 'applications.id')
                  ->orderBy('applications.' . $this->sortBy, $this->sortDirection)
                  ->select('application_lender_submissions.*');
        } else {
            $query->orderBy($this->sortBy, $this->sortDirection);
        }
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

        // Calculate total_value from booked/approved submissions
        // Filter by both submission.lender_id AND application.lender_id to ensure accuracy
        // This should match the approved submissions that are shown in the booked leads tab
        // Use offered_amount if set (actual approved amount), otherwise use requested_amount from application
        try {
            $approvedSubmissions = ApplicationLenderSubmission::where('application_lender_submissions.lender_id', $lenderId)
                ->where('application_lender_submissions.status', 'approved')
                ->whereHas('application', function($subquery) use ($lenderId) {
                    $subquery->where('lender_id', $lenderId);
                })
                ->with('application:id,requested_amount')
                ->get();
            
            $totalValue = (float) $approvedSubmissions->sum(function($submission) {
                // Use offered_amount if available and greater than 0, otherwise use requested_amount from the application
                if (!is_null($submission->offered_amount) && $submission->offered_amount > 0) {
                    return (float) $submission->offered_amount;
                }
                // Fall back to requested_amount from application
                $requestedAmount = $submission->application->requested_amount ?? 0;
                return (float) $requestedAmount;
            });
            
            // Log for debugging
            Log::info('Total value calculated for booked leads', [
                'lender_id' => $lenderId,
                'submissions_count' => $approvedSubmissions->count(),
                'total_value' => $totalValue,
                'submissions_with_offered_amount' => $approvedSubmissions->filter(fn($s) => $s->offered_amount > 0)->count(),
            ]);
        } catch (\Exception $e) {
            Log::error('Error calculating total_value for booked leads', [
                'lender_id' => $lenderId,
                'error' => $e->getMessage(),
            ]);
            $totalValue = 0;
        }
        
        return [
            // Available leads: submissions in application_lender_submissions for this lender
            // that are in 'submitted' status and application hasn't been booked by any lender yet
            'available_leads' => ApplicationLenderSubmission::where('lender_id', $lenderId)
                ->where('status', 'submitted')
                ->whereHas('application', function($subquery) {
                    // Application must be unbooked (not booked by any lender)
                    $subquery->where('booking_status', 'unbooked')
                             ->where('status', 'submitted');
                })
                ->count(),

            'my_leads' => ApplicationLenderSubmission::where('lender_id', $lenderId)
                ->where('status', 'approved')
                ->count(),

            'pending_review' => Application::where('lender_id', $lenderId)
                ->where('status', 'under_review')->count(),

            'approved' => ApplicationLenderSubmission::where('lender_id', $lenderId)
                ->where('status', 'approved')->count(),
            'rejected' => ApplicationLenderSubmission::where('lender_id', $lenderId)
                ->where('status', 'rejected')->count(),
            'total_value' => $totalValue,
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