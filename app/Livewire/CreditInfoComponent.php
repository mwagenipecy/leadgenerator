<?php

namespace App\Livewire;

use App\Models\Application;
use App\Models\CreditInfoRequest;
use App\Services\CreditInfoService;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;

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

    // Form fields for manual check
    public $national_id ;
    public $first_name ;
    public $last_name ;
    public $date_of_birth ;
    public $phone_number ;
    public $loan_id ;

    public $showManualForm = false;
    public $isLoading = false;

    protected $rules = [
        'national_id' => 'nullable|string|max:50',
        'first_name' => 'nullable|string|max:255',
        'last_name' => 'nullable|string|max:255',
        'date_of_birth' => 'nullable|date|before:today',
        'phone_number' => 'nullable|string|max:20',
        'loan_id' => 'nullable|exists:applications,id',
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


    public function boot($applicationId=null){

        // dd($applicationId);
     $this->checkCreditInfo($applicationId);
    }

    public function checkCreditInfo($applicationId = null)
    {
        if ($applicationId) {
            $application = Application::findOrFail($applicationId);
            
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
        } else {
          //  $this->validate();

          if( empty($this->national_id) && 
              empty($this->first_name) && 
              empty($this->last_name) && 
              empty($this->date_of_birth) && 
              empty($this->phone_number) && 
              empty($this->loan_id)) {
                session()->flash('error', 'Please provide at least one search criterion.');
                return;
            }
            
            $data = [
                'loan_id' => $this->loan_id ?: null,
                'national_id' => $this->national_id,
                'first_name' => $this->first_name,
                'last_name' => $this->last_name,
                'full_name' => trim($this->first_name . ' ' . $this->last_name),
                'date_of_birth' => $this->date_of_birth,
                'phone_number' => $this->phone_number,
            ];
        }

        $this->isLoading = true;

        try {
            $creditInfoService = new CreditInfoService();
            $result = $creditInfoService->checkCreditInfo($data);
            
            if ($result->isSuccessful()) {
                session()->flash('message', 'Credit information retrieved successfully!');
            } else {
                session()->flash('error', 'Failed to retrieve credit information: ' . $result->error_message);
            }
            
            $this->resetForm();
            $this->showManualForm = false;
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

    public function toggleManualForm()
    {
        $this->showManualForm = !$this->showManualForm;
        if (!$this->showManualForm) {
            $this->resetForm();
        }
    }

    public function resetForm()
    {
        $this->reset([
            'national_id', 'first_name', 'last_name', 
            'date_of_birth', 'phone_number', 'loan_id'
        ]);
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