<?php

namespace App\Livewire\Leads\Components;

use Livewire\Component;

class LeadPersonal extends Component
{
    public $application;
    public $isAvailable;

    public function mount($application, $isAvailable = false)
    {
        $this->application = $application;
        $this->isAvailable = $isAvailable;
    }

    public function getBlurredValue($value, $type = 'text')
    {
        if (!$this->isAvailable) {
            return $value;
        }

        switch ($type) {
            case 'name':
                return substr($value, 0, 1) . str_repeat('*', strlen($value) - 1);
            case 'address':
                return '***Address';
            case 'city':
                return '***City';
            case 'region':
                return '***Region';
            default:
                return '***';
        }
    }

    public function render()
    {
        return view('livewire.leads.components.lead-personal');
    }
}