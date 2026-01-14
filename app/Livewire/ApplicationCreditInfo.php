<?php

namespace App\Livewire;

use App\Models\Application;
use App\Models\CreditInfoRequest;
use App\Jobs\RequestCreditReport;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Log;

class ApplicationCreditInfo extends Component
{
    use WithPagination;

    public $applicationId = null; // The application ID passed from parent
    public $isAvailable = false; // Whether this is an available lead
    public $search = '';
    public $statusFilter = '';
    public $selectedLoanId = '';
    public $showModal = false;
    public $selectedRequest = null;
    public $showJsonModal = false;
    public $jsonData = '';
    public $jsonTitle = '';
    public $showRequestForm = false;
    public $isLoading = false;

    // Form fields for new request
    public $application_id = '';
    
    // Cached application data
    protected $application = null;
    protected $nationalId = null;

    protected $rules = [
        'application_id' => 'required|exists:applications,id',
    ];

    public function updatedApplicationId()
    {
        if ($this->applicationId) {
            $this->application_id = $this->applicationId;
            $this->loadApplicationData();
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingSelectedLoanId()
    {
        $this->resetPage();
    }

    /**
     * Refresh the component after a credit report is created.
     * Used by wire:poll on the loan details page.
     */
    public function refreshList()
    {
        $this->resetPage();
        $this->loadApplicationData();
    }

    public function mount($applicationId = null, $isAvailable = false)
    {
        $this->applicationId = $applicationId;
        $this->isAvailable = $isAvailable;
        
        if ($this->applicationId) {
            $this->loadApplicationData();
        } else {
            $this->setDefaultApplication();
        }
    }
    
    protected function loadApplicationData()
    {
        if ($this->applicationId) {
            $this->application = Application::find($this->applicationId);
            if ($this->application && $this->application->national_id) {
                $this->nationalId = $this->application->national_id;
                $this->application_id = $this->applicationId;
            }
        }
    }

    protected function setDefaultApplication()
    {
        $defaultApplication = Application::orderBy('created_at', 'desc')->first();
        if ($defaultApplication) {
            $this->application_id = $defaultApplication->id;
        }
    }

    public function requestNewReport()
    {
        $this->isLoading = true;

        try {
            $application = $this->resolveApplicationForRequest();

            if (!$application->national_id) {
                throw new \Exception('Application does not have a NIDA number. Cannot request credit report.');
            }

            // 1) Create a pending record immediately so the table shows the request
            $creditRequest = CreditInfoRequest::create([
                'loan_id' => $application->id,
                'application_number' => $application->application_number,
                'national_id' => $application->national_id,
                'first_name' => $application->first_name,
                'last_name' => $application->last_name,
                'full_name' => trim($application->first_name . ' ' . ($application->middle_name ? $application->middle_name . ' ' : '') . $application->last_name),
                'date_of_birth' => $application->date_of_birth,
                'phone_number' => $application->phone_number,
                'status' => 'pending',
                'request_payload' => [
                    'loan_id' => $application->id,
                    'application_number' => $application->application_number,
                    'national_id' => $application->national_id,
                ],
                'requested_at' => now(),
            ]);

            // 2) Dispatch background job to fetch the credit report and update this record
            RequestCreditReport::dispatch($creditRequest->id);

            // Reset pagination and filters so new reports will be visible once the job finishes
            $this->resetPage();
            $this->statusFilter = '';

            // Close the form
            $this->showRequestForm = false;

            Log::info('ApplicationCreditInfo: Credit report job dispatched', [
                'credit_info_request_id' => $creditRequest->id,
                'application_id' => $application->id,
                'application_number' => $application->application_number,
                'viewing_application_id' => $this->applicationId,
            ]);

            session()->flash(
                'message',
                'Credit report request queued for ' . $application->application_number . '. It will appear in the list once processing is complete.'
            );
        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred: ' . $e->getMessage());
            Log::error('ApplicationCreditInfo: Credit info request failed', [
                'application_id' => $this->application_id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        } finally {
            $this->isLoading = false;
        }
    }

    /**
     * Re-check credit info for a specific application (from the table action).
     * Also uses the queued job.
     */
    public function checkCreditInfo($applicationId)
    {
        $this->applicationId = $applicationId;
        $this->application_id = $applicationId;

        // Simply reuse the same flow as a fresh request (create pending row + job)
        $this->requestNewReport();
    }

    protected function resolveApplicationForRequest(): Application
    {
        if ($this->applicationId) {
            $application = Application::findOrFail($this->applicationId);
            $this->application_id = $this->applicationId;
            $this->loadApplicationData();
        } else {
            $this->validate();
            $application = Application::findOrFail($this->application_id);
        }

        return $application;
    }

    public function viewDetails($requestId)
    {
        $this->selectedRequest = CreditInfoRequest::findOrFail($requestId);
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->selectedRequest = null;
    }

    public function viewJson($data, $title)
    {
        $this->jsonData = is_array($data) ? json_encode($data, JSON_PRETTY_PRINT) : $data;
        $this->jsonTitle = $title;
        $this->showJsonModal = true;
    }

    public function closeJsonModal()
    {
        $this->showJsonModal = false;
        $this->jsonData = '';
        $this->jsonTitle = '';
    }

    public function toggleRequestForm()
    {
        $this->showRequestForm = !$this->showRequestForm;
        if ($this->showRequestForm) {
            if ($this->applicationId) {
                $this->application_id = $this->applicationId;
                $this->loadApplicationData();

                Log::info('ApplicationCreditInfo: Request form opened', [
                    'applicationId' => $this->applicationId,
                    'application_id' => $this->application_id,
                    'nationalId' => $this->nationalId
                ]);
            } else {
                $this->setDefaultApplication();
            }
        } else {
            $this->resetForm();
        }
    }
    
    public function updatingShowRequestForm($value)
    {
        if ($value && $this->applicationId) {
            $this->application_id = $this->applicationId;
            $this->loadApplicationData();
        }
    }

    public function resetForm()
    {
        if ($this->applicationId) {
            $this->application_id = $this->applicationId;
        } else {
            $this->reset(['application_id']);
            $this->setDefaultApplication();
        }
    }

    public function render()
    {
        if ($this->applicationId && $this->application_id != $this->applicationId) {
            $this->application_id = $this->applicationId;
        }
        
        if ($this->applicationId && !$this->nationalId) {
            $this->loadApplicationData();
        }
        
        $query = CreditInfoRequest::with('application');
        
        if ($this->applicationId && $this->nationalId) {
            $query->where('national_id', $this->nationalId);
        } elseif ($this->applicationId && !$this->nationalId) {
            $this->loadApplicationData();
            if ($this->nationalId) {
                $query->where('national_id', $this->nationalId);
            } else {
                $query->whereRaw('1 = 0');
            }
        }
        
        $query->when($this->search, function ($query) {
            $query->where(function ($q) {
                $q->where('national_id', 'like', '%' . $this->search . '%')
                  ->orWhere('first_name', 'like', '%' . $this->search . '%')
                  ->orWhere('last_name', 'like', '%' . $this->search . '%')
                  ->orWhere('full_name', 'like', '%' . $this->search . '%')
                  ->orWhere('application_number', 'like', '%' . $this->search . '%');
            });
        })
        ->when($this->statusFilter, function ($query) {
            $query->where('status', $this->statusFilter);
        })
        ->when($this->selectedLoanId, function ($query) {
            $query->where('loan_id', $this->selectedLoanId);
        })
        ->latest();

        $creditRequests = $query->paginate(10);
        
        // On loan details / lead pages we are always in an application context,
        // so we don't need to expose a separate applications list here.
        $applications = [];

        return view('livewire.application-credit-info', compact('creditRequests', 'applications'));
    }
}


