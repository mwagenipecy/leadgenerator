<?php

namespace App\Livewire\Leads\Components;

use Livewire\Component;

class LeadOverview extends Component
{
    public $application;
    public $isAvailable;

    public function mount($application, $isAvailable = true)
    {
        $isAvailable=true;
        $this->application = $application;
        $this->isAvailable = $isAvailable;
    }

    public function render()
    {
        return view('livewire.leads.components.lead-overview');
    }
}