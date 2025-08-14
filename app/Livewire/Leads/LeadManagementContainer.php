<?php

namespace App\Livewire\Leads;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class LeadManagementContainer extends Component
{
    public $currentStep = 'list'; // list, view
    public $selectedLeadId = null;
    public $isAvailable = true;

    protected $listeners = [
        'viewLead' => 'viewLead',
        'backToList' => 'backToList',
        'leadBooked' => 'handleLeadBooked',
        'leadProcessed' => 'handleLeadProcessed'
    ];
    public function mount()
    {
        // Ensure user is a lender
        if (Auth::user()->role !== 'lender' || !Auth::user()->lender_id) {
            abort(403, 'Access denied. Lender access required.');
        }
    }

    public function viewLead($leadId, $isAvailable = true)
    {
        $this->selectedLeadId = $leadId;
        $this->isAvailable = $isAvailable;
        $this->currentStep = 'view';
    }

    public function backToList()
    {
        $this->currentStep = 'list';
        $this->selectedLeadId = null;
        $this->isAvailable = true;
    }

    public function handleLeadBooked()
    {
        $this->backToList();
        $this->dispatch('refreshLeads');
    }

    public function handleLeadProcessed()
    {
        $this->backToList();
        $this->dispatch('refreshLeads');
    }

    public function render()
    {
        return view('livewire.leads.lead-management-container');
    }
}