<?php

namespace App\Livewire\Leads\Components;

use Livewire\Component;

class LeadEmployment extends Component
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
            case 'company':
                return '***Company';
            case 'email':
                return '***@company.com';
            case 'phone':
                return '+255***-***-***';
            case 'name':
                return substr($value, 0, 1) . '***';
            default:
                return '***';
        }
    }

    public function render()
    {
        return view('livewire.leads.components.lead-employment');
    }
}