<div class="min-h-screen bg-gray-50">
    @if($currentStep === 'list')
        <livewire:leads.lead-listing />
    @elseif($currentStep === 'view' && $selectedLeadId)
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <livewire:leads.components.lead-detail 
                :leadId="$selectedLeadId" 
                :isAvailable="$isAvailable" 
                :key="'lead-detail-' . $selectedLeadId . '-' . ($isAvailable ? 'available' : 'booked')" />
        </div>
    @endif

    



</div>
