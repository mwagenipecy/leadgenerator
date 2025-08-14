<?php

namespace App\Livewire\Leads\Components;

use Livewire\Component;

class LeadFinancial extends Component
{
    public $application;
    public $isAvailable;

    public function mount($application, $isAvailable = false)
    {
        $this->application = $application;
        $this->isAvailable = $isAvailable;
    }

    public function getBlurredAmount($amount)
    {
        if ($this->isAvailable) {
            return 'TSh ***,***';
        }
        return 'TSh ' . number_format($amount ?? 0);
    }

    public function render()
    {
        return view('livewire.leads.components.lead-financial');
    }
}