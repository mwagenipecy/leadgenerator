<?php

// Event Listener for Application Status Changes
namespace App\Listeners;

use App\Events\ApplicationStatusChanged;
use App\Services\IntegrationService;
use Illuminate\Support\Facades\Log;

class ProcessIntegrations
{
    protected $integrationService;

    public function __construct(IntegrationService $integrationService)
    {
        $this->integrationService = $integrationService;
    }

    public function handle(ApplicationStatusChanged $event)
    {
        $application = $event->application;
        $oldStatus = $event->oldStatus;
        $newStatus = $application->status;

        // Trigger integrations based on status change
        $triggerEvent = $this->determineTriggerEvent($oldStatus, $newStatus);
        
        if ($triggerEvent) {
            $this->integrationService->processIntegrations($application->id, $triggerEvent);
        }
    }

    private function determineTriggerEvent($oldStatus, $newStatus)
    {
        switch ($newStatus) {
            case 'approved':
                return 'offer_accepted';
            case 'rejected':
                return 'offer_rejected';
            case 'disbursed':
                return 'loan_disbursed';
            case 'cancelled':
                return 'application_cancelled';
            default:
                return 'status_changed';
        }
    }
}