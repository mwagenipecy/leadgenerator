<?php

namespace App\Livewire\LoanApplication;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Application;
use Illuminate\Support\Facades\Auth;

class ApplicationList extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = 'all';
    public $currentStep = 'list';
    public $selectedApplication = null;

    protected $paginationTheme = 'tailwind';

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => 'all'],
        'page' => ['except' => 1],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function startNewApplication()
    {
        return redirect()->route('applications.create');
    }

    public function viewApplication($applicationId)
    {
        $this->selectedApplication = Application::find($applicationId);
        $this->currentStep = 'view';
    }

    public function editApplication($applicationId)
    {
        return redirect()->route('applications.edit', $applicationId);
    }

    public function viewMatchingProducts($applicationId)
    {
        return redirect()->route('applications.matching-products', $applicationId);
    }

    public function cancelApplication($applicationId)
    {
        $application = Application::find($applicationId);
        
        if ($application && in_array($application->status, ['draft', 'submitted'])) {
            $application->update(['status' => 'cancelled']);
            session()->flash('message', 'Application cancelled successfully.');
        }
    }

    public function backToList()
    {
        $this->currentStep = 'list';
        $this->selectedApplication = null;
    }

    public function getApplicationsProperty()
    {
        $query = Application::with(['loanProduct', 'lender', 'user'])
            ->where('user_id', Auth::id());

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('application_number', 'like', '%' . $this->search . '%')
                  ->orWhere('first_name', 'like', '%' . $this->search . '%')
                  ->orWhere('last_name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }

        return $query->orderBy('created_at', 'desc')->paginate(10);
    }

    public function render()
    {
        return view('livewire.loan-application.application-list', [
            'applications' => $this->applications,
        ]);
    }
}