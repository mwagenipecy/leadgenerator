<?php

namespace App\Livewire;

use App\Models\Application;
use App\Models\CreditInfoRequest;
use App\Services\CreditInfoService;
use Livewire\Component;
use Livewire\WithPagination;

class CreditInfoComponent extends Component
{
    use WithPagination;

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

    protected $rules = [
        'application_id' => 'required|exists:applications,id',
    ];
    
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

    public function boot($applicationId = null)
    {
        if ($applicationId) {
            $this->checkCreditInfo($applicationId);
        }
    }

    public function mount()
    {
        // Set default application when component is mounted
        $this->setDefaultApplication();
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
        $this->validate();
        
        $this->isLoading = true;

        try {
            $application = Application::findOrFail($this->application_id);
            
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
            
            if ($result->isSuccessful()) {
                session()->flash('message', 'Credit information retrieved successfully for ' . $application->application_number . '!');
            } else {
                session()->flash('error', 'Failed to retrieve credit information: ' . $result->error_message);
            }
        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred: ' . $e->getMessage());
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
            // Reset to default application when opening the form
            $this->setDefaultApplication();
        } else {
            $this->resetForm();
        }
    }

    public function resetForm()
    {
        $this->reset(['application_id']);
        $this->setDefaultApplication();
    }

    public function render()
    {
        $query = CreditInfoRequest::with('application')
            ->when($this->search, function ($query) {
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
        
        $applications = Application::select('id', 'application_number', 'first_name', 'last_name')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('livewire.credit-info-component', compact('creditRequests', 'applications'));
    }
}