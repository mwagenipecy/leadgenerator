<?php

namespace App\Livewire;

use App\Models\Application;
use App\Models\CreditInfoRequest;
use App\Services\CreditInfoService;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Log;

class CreditInfoComponent extends Component
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
    
    /**
     * Update application_id when applicationId changes
     */
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
     * Refresh the component after a credit report is created
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
        
        // Load and cache application data if applicationId is provided
        if ($this->applicationId) {
            $this->loadApplicationData();
        } else {
            // Set default application when component is mounted (only if no applicationId provided)
            $this->setDefaultApplication();
        }
    }
    
    /**
     * Load and cache application data
     */
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
            // When in application context, ALWAYS use that application - ignore form input
            // This ensures we can only request reports for the application being viewed
            if ($this->applicationId) {
                // Force use of the application being viewed
                $application = Application::findOrFail($this->applicationId);
                $this->application_id = $this->applicationId;
                $this->loadApplicationData();
            } else {
                // Not in application context, validate and use form input
                $this->validate();
                $application = Application::findOrFail($this->application_id);
            }
            
            // Verify application has NIDA number
            if (!$application->national_id) {
                throw new \Exception('Application does not have a NIDA number. Cannot request credit report.');
            }
            
            $data = [
                'loan_id' => $application->id,
                'application_number' => $application->application_number,
                'national_id' => $application->national_id,
                'first_name' => $application->first_name,
                'last_name' => $application->last_name,
                'full_name' => trim($application->first_name . ' ' . ($application->middle_name ? $application->middle_name . ' ' : '') . $application->last_name),
                'date_of_birth' => $application->date_of_birth ? $application->date_of_birth->format('Y-m-d') : null,
                'phone_number' => $application->phone_number,
            ];

            $creditInfoService = new CreditInfoService();
            $result = $creditInfoService->checkCreditInfo($data);
            
            // After creating the credit report, ensure we can see it in the list
            if ($result) {
                // Reload application data to ensure NIDA number is cached (important for filtering)
                if ($this->applicationId) {
                    $this->loadApplicationData();
                } else {
                    // Not in application context, update nationalId based on the application used
                    $this->nationalId = $application->national_id;
                }
                
                // Verify the report was created with the correct NIDA number
                $createdReport = CreditInfoRequest::find($result->id);
                $nidaMatches = $createdReport && $createdReport->national_id == $application->national_id;
                
                // Reset pagination to show the new record (will be on first page)
                $this->resetPage();
                
                // Clear status filter so we can see the new report regardless of status
                // (new reports might be pending, success, or failed)
                $this->statusFilter = '';
                
                // Close the form
                $this->showRequestForm = false;
                
                // Log for debugging
                Log::info('Credit report created', [
                    'credit_request_id' => $result->id,
                    'application_id' => $application->id,
                    'application_number' => $application->application_number,
                    'created_national_id' => $createdReport->national_id ?? 'not_found',
                    'application_national_id' => $application->national_id,
                    'viewing_application_id' => $this->applicationId,
                    'cached_national_id' => $this->nationalId,
                    'nida_matches' => $nidaMatches ? 'yes' : 'no',
                    'status' => $result->status,
                ]);
                
                if ($result->isSuccessful()) {
                    session()->flash('message', 'Credit information retrieved successfully for ' . $application->application_number . '! The report should appear in the list below.');
                } else {
                    session()->flash('message', 'Credit report requested for ' . $application->application_number . '. Status: ' . $result->status . '. It will appear in the list once processed.');
                }
            } else {
                session()->flash('error', 'Failed to retrieve credit information: Unknown error');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred: ' . $e->getMessage());
            Log::error('Credit info request failed', [
                'application_id' => $this->application_id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        } finally {
            $this->isLoading = false;
        }
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
            // If we're in a specific application context, ALWAYS use that application
            // This ensures the form can only request reports for the application being viewed
            if ($this->applicationId) {
                // Force application_id to match applicationId
                $this->application_id = $this->applicationId;
                // Reload application data to ensure NIDA number is cached
                $this->loadApplicationData();
                
                // Log for debugging
                Log::info('Request form opened', [
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
    
    /**
     * Called when the form is shown to ensure application_id is set correctly
     */
    public function updatingShowRequestForm($value)
    {
        if ($value && $this->applicationId) {
            // When showing the form, ensure we're using the correct application
            $this->application_id = $this->applicationId;
            $this->loadApplicationData();
        }
    }

    public function resetForm()
    {
        if ($this->applicationId) {
            // If in application context, reset to that application
            $this->application_id = $this->applicationId;
        } else {
            $this->reset(['application_id']);
            $this->setDefaultApplication();
        }
    }

    public function render()
    {
        // Ensure application_id matches applicationId when in application context
        if ($this->applicationId && $this->application_id != $this->applicationId) {
            $this->application_id = $this->applicationId;
        }
        
        // Reload application data if applicationId is set but nationalId is not cached
        if ($this->applicationId && !$this->nationalId) {
            $this->loadApplicationData();
        }
        
        $query = CreditInfoRequest::with('application');
        
        // If applicationId is provided, filter by the application's NIDA number only
        if ($this->applicationId && $this->nationalId) {
            // Filter by NIDA number only - this ensures we only show reports for this specific applicant
            // This is the key requirement: show only credit reports for this applicant by NIDA number
            $query->where('national_id', $this->nationalId);
        } elseif ($this->applicationId && !$this->nationalId) {
            // If application exists but has no NIDA number, try to reload it
            $this->loadApplicationData();
            if ($this->nationalId) {
                $query->where('national_id', $this->nationalId);
            } else {
                // If still no NIDA number, return empty results
                $query->whereRaw('1 = 0'); // Force empty result
            }
        }
        
        // Apply additional filters (search should work within the NIDA filter)
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
        
        // Only show applications list if we're not in a specific application context
        $applications = [];
        if (!$this->applicationId) {
            $applications = Application::select('id', 'application_number', 'first_name', 'last_name')
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('livewire.credit-info-component', compact('creditRequests', 'applications'));
    }
}