<?php

namespace App\Livewire\Component;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\TransactionAnalysis;

class TransactionAnalysisComponent extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $selectedAnalysis = null;
    public $showDetails = false;

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => '']
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function viewDetails($id)
    {
        $this->selectedAnalysis = TransactionAnalysis::find($id);
        $this->showDetails = true;
    }

    public function closeDetails()
    {
        $this->showDetails = false;
        $this->selectedAnalysis = null;
    }

    public function render()
    {
        $analyses = TransactionAnalysis::query()
            ->when($this->search, function ($query) {
                $query->where('account_number', 'like', '%' . $this->search . '%');
            })
            ->when($this->statusFilter, function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->latest()
            ->paginate(10);

        return view('livewire.component.transaction-analysis-component', [
            'analyses' => $analyses
        ]);
    }

    
}
