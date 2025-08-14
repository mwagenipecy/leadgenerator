<?php

namespace App\Livewire\Leads\Components;

use Livewire\Component;

class LeadDocuments extends Component
{
    public $application;
    public $isAvailable;

    public function mount($application, $isAvailable = false)
    {
        $this->application = $application;
        $this->isAvailable = $isAvailable;
    }

    public function downloadDocument($documentId)
    {
        if ($this->isAvailable) {
            session()->flash('error', 'You must book this lead to download documents.');
            return;
        }

        // Implement document download logic here
        // This would typically generate a secure download link or stream the file
        session()->flash('message', 'Document download started.');
    }

    public function render()
    {
        return view('livewire.leads.components.lead-documents');
    }
}