<?php

namespace App\Livewire\Leads\Components;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class LeadTimeline extends Component
{
    public $application;
    public $lead;
    public $isAvailable;
    public $noteText = '';

    public function mount($application, $lead = null, $isAvailable = false)
    {
        $this->application = $application;
        $this->lead = $lead;
        $this->isAvailable = $isAvailable;
    }

    public function addNote()
    {
        if ($this->isAvailable || empty($this->noteText)) {
            return;
        }

        // Add note logic here
        // This would typically create a new note record associated with the application/lead
        
        session()->flash('message', 'Note added successfully.');
        $this->noteText = '';
    }

    public function render()
    {
        return view('livewire.leads.components.lead-timeline');
    }
}