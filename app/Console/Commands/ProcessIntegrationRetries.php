<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\IntegrationService;

class ProcessIntegrationRetries extends Command
{
    protected $signature = 'integrations:process-retries';
    protected $description = 'Process failed integration retries';

    protected $integrationService;

    public function __construct(IntegrationService $integrationService)
    {
        parent::__construct();
        $this->integrationService = $integrationService;
    }

    public function handle()
    {
        $this->info('Processing integration retries...');
        
        $processedCount = $this->integrationService->processRetries();
        
        $this->info("Processed {$processedCount} integration retries.");
        
        return 0;
    }
}