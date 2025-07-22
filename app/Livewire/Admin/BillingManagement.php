<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Application;
use App\Models\CommissionBill;
use App\Models\CommissionPayment;
use App\Models\Lender;
use App\Models\SystemSetting;
use App\Models\LenderCommissionSetting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BillingManagement extends Component
{
    use WithPagination;

    // Tab Management
    public $activeTab = 'applications';

    // Filters
    public $filterStatus = 'all';
    public $filterLender = 'all';
    public $filterDateFrom = '';
    public $filterDateTo = '';
    public $search = '';

    // Bill Creation
    public $selectedApplications = [];
    public $showBillModal = false;
    public $billNotes = '';

    // Payment Recording
    public $showPaymentModal = false;
    public $selectedBill = null;
    public $paymentAmount = 0;
    public $paymentMethod = 'bank_transfer';
    public $paymentReference = '';
    public $paymentDate = '';
    public $paymentNotes = '';

    // Bulk Actions
    public $selectAll = false;
    public $bulkAction = '';

    protected $rules = [
        'paymentAmount' => 'required|numeric|min:0.01',
        'paymentMethod' => 'required|string',
        'paymentDate' => 'required|date',
        'paymentReference' => 'nullable|string|max:255',
        'paymentNotes' => 'nullable|string|max:1000',
    ];

    public function mount()
    {
        $this->paymentDate = now()->format('Y-m-d');
    }

    public function updatedSelectAll()
    {
        if ($this->selectAll) {
            $this->selectedApplications = $this->getFilteredApplications()->pluck('id')->toArray();
        } else {
            $this->selectedApplications = [];
        }
    }

    public function updatedSelectedApplications()
    {
        $totalApplications = $this->getFilteredApplications()->count();
        $this->selectAll = count($this->selectedApplications) === $totalApplications;
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
        $this->resetPage();
    }

    public function getFilteredApplications()
    {
        $query = Application::with(['lender', 'user'])
            ->where('booking_status', 'booked');
           // ->where('status', 'approved');

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('application_number', 'like', '%' . $this->search . '%')
                  ->orWhere('first_name', 'like', '%' . $this->search . '%')
                  ->orWhere('last_name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->filterLender !== 'all') {
            $query->where('lender_id', $this->filterLender);
        }

        if ($this->filterDateFrom) {
            $query->whereDate('approved_at', '>=', $this->filterDateFrom);
        }

        if ($this->filterDateTo) {
            $query->whereDate('approved_at', '<=', $this->filterDateTo);
        }

        return $query->orderBy('approved_at', 'desc');
    }

    public function getFilteredBills()
    {
        $query = CommissionBill::with(['application', 'lender', 'payments']);

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('bill_number', 'like', '%' . $this->search . '%')
                  ->orWhereHas('application', function ($subQ) {
                      $subQ->where('application_number', 'like', '%' . $this->search . '%')
                           ->orWhere('first_name', 'like', '%' . $this->search . '%')
                           ->orWhere('last_name', 'like', '%' . $this->search . '%');
                  });
            });
        }

        if ($this->filterStatus !== 'all') {
            $query->where('status', $this->filterStatus);
        }

        if ($this->filterLender !== 'all') {
            $query->where('lender_id', $this->filterLender);
        }

        if ($this->filterDateFrom) {
            $query->whereDate('created_at', '>=', $this->filterDateFrom);
        }

        if ($this->filterDateTo) {
            $query->whereDate('created_at', '<=', $this->filterDateTo);
        }

        return $query->orderBy('created_at', 'desc');
    }

    public function createBillsForSelected()
    {
        if (empty($this->selectedApplications)) {
            session()->flash('error', 'Please select at least one application to create bills.');
            return;
        }

        $this->showBillModal = true;
    }

    public function generateBills()
    {
        DB::beginTransaction();

        try {
            $applications = Application::whereIn('id', $this->selectedApplications)
                ->where('booking_status', 'booked')
                ->where('status', 'approved')
                ->get();

            $billsCreated = 0;

            foreach ($applications as $application) {
                // Check if bill already exists for this application
                $existingBill = CommissionBill::where('application_id', $application->id)->first();
                if ($existingBill) {
                    continue;
                }

                $commissionData = $this->calculateCommission($application);

                CommissionBill::create([
                    'application_id' => $application->id,
                    'lender_id' => $application->lender_id,
                    'commission_type' => $commissionData['type'],
                    'commission_rate' => $commissionData['rate'],
                    'loan_amount' => $application->requested_amount,
                    'commission_amount' => $commissionData['commission_amount'],
                    'tax_amount' => $commissionData['tax_amount'],
                    'total_amount' => $commissionData['total_amount'],
                    'due_date' => now()->addDays($this->getPaymentDueDays()),
                    'notes' => $this->billNotes,
                    'created_by' => Auth::id(),
                ]);

                $billsCreated++;
            }

            DB::commit();

            $this->selectedApplications = [];
            $this->showBillModal = false;
            $this->billNotes = '';
            $this->selectAll = false;

            session()->flash('message', "Successfully created {$billsCreated} commission bills.");

        } catch (\Exception $e) {
            DB::rollback();
            session()->flash('error', 'Error creating bills: ' . $e->getMessage());
        }
    }

    private function calculateCommission($application)
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
        $commissionAmount = max($commissionAmount, $minimumAmount);
        if ($maximumAmount) {
            $commissionAmount = min($commissionAmount, $maximumAmount);
        }

        // Calculate tax
        $taxRate = (float) (SystemSetting::where('key', 'tax_rate')->value('value') ?: 18.0);
        $taxAmount = ($commissionAmount * $taxRate) / 100;
        $totalAmount = $commissionAmount + $taxAmount;

        return [
            'type' => $commissionType,
            'rate' => $commissionRate,
            'commission_amount' => $commissionAmount,
            'tax_amount' => $taxAmount,
            'total_amount' => $totalAmount,
        ];
    }

    private function getPaymentDueDays()
    {
        return (int) (SystemSetting::where('key', 'payment_due_days')->value('value') ?: 30);
    }

    public function openPaymentModal($billId)
    {
        $this->selectedBill = CommissionBill::find($billId);
        $this->paymentAmount = $this->selectedBill->balance;
        $this->showPaymentModal = true;
    }

    public function recordPayment()
    {
        $this->validate();

        DB::beginTransaction();

        try {
            $payment = CommissionPayment::create([
                'commission_bill_id' => $this->selectedBill->id,
                'amount' => $this->paymentAmount,
                'payment_method' => $this->paymentMethod,
                'payment_reference' => $this->paymentReference,
                'payment_date' => $this->paymentDate,
                'notes' => $this->paymentNotes,
                'recorded_by' => Auth::id(),
            ]);

            // Update bill status if fully paid
            $totalPaid = $this->selectedBill->payments()->sum('amount') + $this->paymentAmount;
            if ($totalPaid >= $this->selectedBill->total_amount) {
                $this->selectedBill->markAsPaid($this->paymentMethod, $this->paymentReference);
            }

            DB::commit();

            $this->resetPaymentForm();
            session()->flash('message', 'Payment recorded successfully.');

        } catch (\Exception $e) {
            DB::rollback();
            session()->flash('error', 'Error recording payment: ' . $e->getMessage());
        }
    }

    public function resetPaymentForm()
    {
        $this->showPaymentModal = false;
        $this->selectedBill = null;
        $this->paymentAmount = 0;
        $this->paymentMethod = 'bank_transfer';
        $this->paymentReference = '';
        $this->paymentDate = now()->format('Y-m-d');
        $this->paymentNotes = '';
    }

    public function executeBulkAction()
    {
        if (empty($this->selectedApplications) || empty($this->bulkAction)) {
            session()->flash('error', 'Please select applications and an action.');
            return;
        }

        switch ($this->bulkAction) {
            case 'create_bills':
                $this->createBillsForSelected();
                break;
            default:
                session()->flash('error', 'Invalid bulk action selected.');
        }

        $this->bulkAction = '';
    }

    public function render()
    {
        $data = [
            'lenders' => Lender::where('status', 'approved')->get(),
        ];

        if ($this->activeTab === 'applications') {
            $data['applications'] = $this->getFilteredApplications()->paginate(15);
        } elseif ($this->activeTab === 'bills') {
            $data['bills'] = $this->getFilteredBills()->paginate(15);
        }

        return view('livewire.admin.billing-management', $data);
    }



    public $showApplicationModal = false;
public $selectedApplication = null;

// View Bill Details
public $showBillDetailsModal = false;
public $selectedBillForView = null;

/**
 * Open application details modal
 */
public function viewApplication($applicationId)
{
    $this->selectedApplication = Application::with([
        'lender', 
        'user', 
        'documents',
        'commissionBills.payments',
       
    ])->findOrFail($applicationId);
    
    $this->showApplicationModal = true;
}


public function viewApplicationBill($applicationId)
{
    $bill = CommissionBill::where('application_id', $applicationId)->first();
    
    if ($bill) {
        $this->viewBillDetails($bill->id);
    } else {
        session()->flash('error', 'No commission bill found for this application.');
    }
}

/**
 * Close application modal
 */
public function closeApplicationModal()
{
    $this->showApplicationModal = false;
    $this->selectedApplication = null;
}

/**
 * Open bill details modal
 */
public function viewBillDetails($billId)
{
    $this->selectedBillForView = CommissionBill::with([
        'application', 
        'lender', 
        'payments' => function($query) {
            $query->latest();
        },
        'createdBy'
    ])->findOrFail($billId);
    
    $this->showBillDetailsModal = true;
}

/**
 * Close bill details modal
 */
public function closeBillDetailsModal()
{
    $this->showBillDetailsModal = false;
    $this->selectedBillForView = null;
}

/**
 * Download bill as PDF
 */
public function downloadBillPdf($billId)
{
    try {
        $bill = CommissionBill::with(['application', 'lender', 'payments'])->findOrFail($billId);
        
        // Generate PDF using your preferred PDF library (e.g., DomPDF, wkhtmltopdf)
        // This is a placeholder - implement based on your PDF generation preference
        
        session()->flash('message', 'Bill PDF download initiated.');
        
    } catch (\Exception $e) {
        session()->flash('error', 'Error generating PDF: ' . $e->getMessage());
    }
}

/**
 * Send bill notification
 */
public function sendBillNotification($billId)
{
    try {
        $bill = CommissionBill::with(['application', 'lender'])->findOrFail($billId);
        
        // Send notification logic here
        // This could be email, SMS, or both based on your notification preferences
        
        $bill->update([
            'status' => 'sent',
            'sent_at' => now(),
            'sent_by' => Auth::id()
        ]);
        
        session()->flash('message', 'Bill notification sent successfully.');
        
    } catch (\Exception $e) {
        session()->flash('error', 'Error sending notification: ' . $e->getMessage());
    }
}

/**
 * Mark bill as overdue manually
 */
public function markBillOverdue($billId)
{
    try {
        $bill = CommissionBill::findOrFail($billId);
        
        if ($bill->status !== 'paid') {
            $bill->update(['status' => 'overdue']);
            session()->flash('message', 'Bill marked as overdue.');
        } else {
            session()->flash('error', 'Cannot mark paid bill as overdue.');
        }
        
    } catch (\Exception $e) {
        session()->flash('error', 'Error updating bill status: ' . $e->getMessage());
    }
}

/**
 * Cancel/void a bill
 */
public function cancelBill($billId)
{
    try {
        $bill = CommissionBill::findOrFail($billId);
        
        if ($bill->status === 'paid') {
            session()->flash('error', 'Cannot cancel a paid bill.');
            return;
        }
        
        if ($bill->payments()->count() > 0) {
            session()->flash('error', 'Cannot cancel a bill with recorded payments.');
            return;
        }
        
        $bill->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
            'cancelled_by' => Auth::id()
        ]);
        
        session()->flash('message', 'Bill cancelled successfully.');
        
    } catch (\Exception $e) {
        session()->flash('error', 'Error cancelling bill: ' . $e->getMessage());
    }
}

/**
 * Generate bill preview before creation
 */
public function previewBill($applicationId)
{
    try {
        $application = Application::with('lender')->findOrFail($applicationId);
        $commissionData = $this->calculateCommission($application);
        
        return [
            'application' => $application,
            'commission_data' => $commissionData,
            'due_date' => now()->addDays($this->getPaymentDueDays())->format('M d, Y')
        ];
        
    } catch (\Exception $e) {
        session()->flash('error', 'Error generating preview: ' . $e->getMessage());
        return null;
    }
}



}