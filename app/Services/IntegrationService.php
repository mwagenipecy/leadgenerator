<?php

namespace App\Services;

use App\Models\Integration;
use App\Models\IntegrationLog;
use App\Models\Application;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class IntegrationService
{
    /**
     * Process integrations for an application
     */
    public function processIntegrations($applicationId, $triggerEvent = 'offer_accepted')
    {
        $application = Application::with(['user', 'lender', 'loanProduct'])->find($applicationId);
        
        if (!$application) {
            Log::error("Application not found for integration processing", ['application_id' => $applicationId]);
            return false;
        }

        // Get all active integrations for the application owner
        $integrations = Integration::where('user_id', $application->user_id)
            ->where('is_active', true)
            ->get();

        $processedCount = 0;

        foreach ($integrations as $integration) {
            if ($integration->shouldTrigger($triggerEvent, $application)) {
                $this->executeIntegration($integration, $application, $triggerEvent);
                $processedCount++;
            }
        }

        Log::info("Processed integrations for application", [
            'application_id' => $applicationId,
            'trigger_event' => $triggerEvent,
            'integrations_processed' => $processedCount
        ]);

        return $processedCount;
    }

    /**
     * Execute a single integration
     */
    public function executeIntegration(Integration $integration, Application $application, $triggerEvent)
    {
        $startTime = microtime(true);

        // Create integration log
        $log = IntegrationLog::create([
            'integration_id' => $integration->id,
            'application_id' => $application->id,
            'trigger_event' => $triggerEvent,
            'status' => 'pending',
            'request_payload' => [],
            'request_url' => $integration->webhook_url,
            'last_attempt_at' => now(),
        ]);

        try {
            // Build request payload
            $payload = $this->buildPayload($integration, $application);
            
            // Build headers
            $headers = $this->buildHeaders($integration);
            
            // Update log with request data
            $log->update([
                'request_payload' => $payload,
                'request_headers' => $headers,
            ]);

            // Make HTTP request
            $response = $this->makeHttpRequest($integration, $payload, $headers);
            
            $responseTime = (microtime(true) - $startTime) * 1000; // Convert to milliseconds

            // Update log with response
            $log->update([
                'status' => $response->successful() ? 'success' : 'failed',
                'response_body' => $response->body(),
                'response_status' => $response->status(),
                'response_headers' => $response->headers(),
                'response_time_ms' => $responseTime,
                'error_message' => $response->successful() ? null : $this->getErrorMessage($response),
            ]);

            if ($response->successful()) {
                Log::info("Integration executed successfully", [
                    'integration_id' => $integration->id,
                    'application_id' => $application->id,
                    'response_status' => $response->status(),
                    'response_time_ms' => $responseTime,
                ]);
            } else {
                Log::warning("Integration failed", [
                    'integration_id' => $integration->id,
                    'application_id' => $application->id,
                    'response_status' => $response->status(),
                    'error' => $this->getErrorMessage($response),
                ]);

                // Schedule retry if needed
                $this->scheduleRetryIfNeeded($log);
            }

        } catch (\Exception $e) {
            $responseTime = (microtime(true) - $startTime) * 1000;

            $log->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
                'response_time_ms' => $responseTime,
            ]);

            Log::error("Integration execution failed with exception", [
                'integration_id' => $integration->id,
                'application_id' => $application->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            // Schedule retry if needed
            $this->scheduleRetryIfNeeded($log);
        }

        return $log;
    }

    /**
     * Build request payload based on field mappings
     */
    private function buildPayload(Integration $integration, Application $application)
    {
        $payload = [];
        $fieldMappings = $integration->field_mappings ?? [];

        foreach ($fieldMappings as $mapping) {
            $sourceField = $mapping['source_field'] ?? null;
            $targetField = $mapping['target_field'] ?? null;
            $defaultValue = $mapping['default_value'] ?? null;

            if (!$sourceField || !$targetField) {
                continue;
            }

            // Get value from application data
            $value = $this->getFieldValue($application, $sourceField, $defaultValue);
            
            // Set value in payload using dot notation
            data_set($payload, $targetField, $value);
        }

        // Add metadata
        $payload['_metadata'] = [
            'integration_name' => $integration->name,
            'api_name' => $integration->api_name,
            'trigger_event' => 'offer_accepted',
            'timestamp' => now()->toISOString(),
            'application_id' => $application->id,
        ];

        return $payload;
    }

    /**
     * Get field value from application with dot notation support
     */
    private function getFieldValue(Application $application, $fieldPath, $defaultValue = null)
    {
        try {
            $value = data_get($application, $fieldPath, $defaultValue);
            
            // Handle special formatting
            if ($value instanceof Carbon) {
                return $value->toISOString();
            }
            
            return $value;
        } catch (\Exception $e) {
            Log::warning("Failed to get field value", [
                'field_path' => $fieldPath,
                'error' => $e->getMessage(),
            ]);
            return $defaultValue;
        }
    }

    /**
     * Build HTTP headers for request
     */
    private function buildHeaders(Integration $integration)
    {
        $headers = [
            'Content-Type' => $integration->content_type,
            'User-Agent' => 'LoanLeadGenerator-Integration/1.0',
            'X-Integration-Name' => $integration->api_name,
        ];

        // Add authentication headers
        switch ($integration->auth_type) {
            case 'basic':
                if ($integration->auth_username && $integration->decrypted_password) {
                    $headers['Authorization'] = 'Basic ' . base64_encode(
                        $integration->auth_username . ':' . $integration->decrypted_password
                    );
                }
                break;

            case 'bearer':
                if ($integration->decrypted_token) {
                    $headers['Authorization'] = 'Bearer ' . $integration->decrypted_token;
                }
                break;

            case 'api_key':
                if ($integration->api_key_header && $integration->decrypted_api_key) {
                    $headers[$integration->api_key_header] = $integration->decrypted_api_key;
                }
                break;
        }

        // Add custom headers
        if ($integration->headers) {
            $headers = array_merge($headers, $integration->headers);
        }

        return $headers;
    }

    /**
     * Make HTTP request to integration endpoint
     */
    private function makeHttpRequest(Integration $integration, array $payload, array $headers)
    {
        $http = Http::withHeaders($headers)
            ->timeout($integration->timeout_seconds)
            ->retry(1, 1000); // Initial attempt only, retries handled separately

        if (!$integration->verify_ssl) {
            $http = $http->withoutVerifying();
        }

        switch (strtoupper($integration->http_method)) {
            case 'POST':
                return $http->post($integration->webhook_url, $payload);
            case 'PUT':
                return $http->put($integration->webhook_url, $payload);
            case 'PATCH':
                return $http->patch($integration->webhook_url, $payload);
            default:
                throw new \InvalidArgumentException('Unsupported HTTP method: ' . $integration->http_method);
        }
    }

    /**
     * Get error message from response
     */
    private function getErrorMessage($response)
    {
        $statusCode = $response->status();
        $body = $response->body();

        $message = "HTTP {$statusCode}";

        if ($body) {
            // Try to extract meaningful error message
            $json = json_decode($body, true);
            if ($json && isset($json['error'])) {
                $message .= ": " . $json['error'];
            } elseif ($json && isset($json['message'])) {
                $message .= ": " . $json['message'];
            } else {
                $message .= ": " . substr($body, 0, 200); // First 200 chars
            }
        }

        return $message;
    }

    /**
     * Schedule retry if needed
     */
    private function scheduleRetryIfNeeded(IntegrationLog $log)
    {
        if ($log->retry_count < $log->integration->retry_attempts) {
            $log->markForRetry();
        }
    }

    /**
     * Process failed integrations for retry
     */
    public function processRetries()
    {
        $failedLogs = IntegrationLog::with(['integration', 'application'])
            ->where('status', 'retrying')
            ->where(function ($query) {
                $query->whereNull('next_retry_at')
                      ->orWhere('next_retry_at', '<=', now());
            })
            ->limit(100) // Process in batches
            ->get();

        $processedCount = 0;

        foreach ($failedLogs as $log) {
            $log->update([
                'retry_count' => $log->retry_count + 1,
                'last_attempt_at' => now(),
            ]);

            $this->executeIntegration(
                $log->integration, 
                $log->application, 
                $log->trigger_event
            );

            $processedCount++;
        }

        Log::info("Processed integration retries", ['count' => $processedCount]);

        return $processedCount;
    }

    /**
     * Test integration endpoint
     */
    public function testIntegration(Integration $integration, $sampleApplicationId = null)
    {
        $application = $sampleApplicationId 
            ? Application::with(['user', 'lender', 'loanProduct'])->find($sampleApplicationId)
            : $this->createSampleApplication();

        if (!$application) {
            throw new \Exception('No application available for testing');
        }

        return $this->executeIntegration($integration, $application, 'test_integration');
    }

    /**
     * Create sample application data for testing
     */
    private function createSampleApplication()
    {
        // Return a mock application object for testing
        return (object) [
            'id' => 'TEST-123',
            'application_number' => 'APP-TEST-' . time(),
            'status' => 'approved',
            'requested_amount' => 1000000,
            'requested_tenure_months' => 12,
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@test.com',
            'phone_number' => '+255123456789',
            'created_at' => now(),
            'user' => (object) ['name' => 'John Doe'],
            'lender' => (object) ['company_name' => 'Test Bank'],
            'loanProduct' => (object) ['name' => 'Personal Loan'],
        ];
    }

    /**
     * Get integration statistics
     */
    public function getIntegrationStats($integrationId, $days = 30)
    {
        $integration = Integration::find($integrationId);
        
        if (!$integration) {
            return null;
        }

        $startDate = now()->subDays($days);

        $logs = IntegrationLog::where('integration_id', $integrationId)
            ->where('created_at', '>=', $startDate)
            ->get();

        return [
            'total_executions' => $logs->count(),
            'successful_executions' => $logs->where('status', 'success')->count(),
            'failed_executions' => $logs->where('status', 'failed')->count(),
            'average_response_time' => $logs->where('response_time_ms', '>', 0)->avg('response_time_ms'),
            'success_rate' => $logs->count() > 0 ? ($logs->where('status', 'success')->count() / $logs->count()) * 100 : 0,
            'recent_logs' => $logs->sortByDesc('created_at')->take(10)->values(),
        ];
    }
}