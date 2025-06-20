<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\IntegrationService;

class ProcessIntegrationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $applicationId;
    protected $triggerEvent;

    public function __construct($applicationId, $triggerEvent = 'offer_accepted')
    {
        $this->applicationId = $applicationId;
        $this->triggerEvent = $triggerEvent;
    }

    public function handle(IntegrationService $integrationService)
    {
        $integrationService->processIntegrations($this->applicationId, $this->triggerEvent);
    }
}
