<?php

namespace App\Livewire\Admin;

use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Integration;
use App\Models\IntegrationLog;
use App\Models\Application;
use App\Services\IntegrationService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class IntegrationManagement extends Component
{
    use WithPagination;

    // Modal States
    public $showCreateModal = false;
    public $showEditModal = false;
    public $showTestModal = false;
    public $showLogsModal = false;
    public $selectedIntegration = null;

    // Form Properties
    public $name = '';
    public $api_name = '';
    public $description = '';
    public $webhook_url = '';
    public $http_method = 'POST';
    public $auth_type = 'basic';
    public $auth_username = '';
    public $auth_password = '';
    public $auth_token = '';
    public $api_key_header = '';
    public $api_key_value = '';
    public $timeout_seconds = 30;
    public $retry_attempts = 3;
    public $verify_ssl = true;
    public $content_type = 'application/json';
    public $is_active = true;

    // Field Mappings
    public $field_mappings = [];
    public $available_fields = [];

    // Trigger Conditions
    public $trigger_conditions = [];

    // Custom Headers
    public $custom_headers = [];

    // Testing

    #[Validate('required|string')]
    public  $test_application_id = '';

    public $test_result = null;

    // Filters
    public $search = '';
    public $status_filter = '';

    protected $paginationTheme = 'tailwind';

    public function mount()
    {
        $this->available_fields = Integration::getAvailableFields();
        $this->initializeDefaults();
    }

    public function initializeDefaults()
    {
        $this->field_mappings = [
            ['source_field' => 'application_number', 'target_field' => 'application_id', 'default_value' => ''],
            ['source_field' => 'first_name', 'target_field' => 'first_name', 'default_value' => ''],
            ['source_field' => 'last_name', 'target_field' => 'last_name', 'default_value' => ''],
            ['source_field' => 'email', 'target_field' => 'email', 'default_value' => ''],
            ['source_field' => 'phone_number', 'target_field' => 'phone', 'default_value' => ''],
            ['source_field' => 'requested_amount', 'target_field' => 'loan_amount', 'default_value' => ''],
        ];

        $this->trigger_conditions = [
            ['field' => 'status', 'operator' => '=', 'value' => 'approved', 'event' => 'offer_accepted']
        ];

        $this->custom_headers = [
            ['key' => '', 'value' => '']
        ];
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'api_name' => 'required|string|max:255|unique:integrations,api_name,' . ($this->selectedIntegration ? $this->selectedIntegration->id : 'null'),
            'webhook_url' => 'required|url',
            'http_method' => 'required|in:POST,PUT,PATCH',
            'auth_type' => 'required|in:none,basic,bearer,api_key',
            'auth_username' => 'required_if:auth_type,basic|nullable|string',
            'auth_password' => 'required_if:auth_type,basic|nullable|string',
            'auth_token' => 'required_if:auth_type,bearer|nullable|string',
            'api_key_header' => 'required_if:auth_type,api_key|nullable|string',
            'api_key_value' => 'required_if:auth_type,api_key|nullable|string',
            'timeout_seconds' => 'required|integer|min:5|max:300',
            'retry_attempts' => 'required|integer|min:0|max:10',
            'content_type' => 'required|string',
        ];
    }

    public function updatedName()
    {
        $this->api_name = Str::slug($this->name, '_');
    }

    public function openCreateModal()
    {
        $this->resetValidation();
        $this->resetForm();
        $this->initializeDefaults();
        $this->showCreateModal = true;
    }

    public function openEditModal($integrationId)
    {
        $this->selectedIntegration = Integration::find($integrationId);
        $this->resetValidation();
        $this->loadIntegrationData();
        $this->showEditModal = true;
    }

    public function openTestModal($integrationId)
    {
        $this->selectedIntegration = Integration::find($integrationId);
        $this->test_result = null;
        $this->test_application_id = '';
        $this->showTestModal = true;
    }

    public function openLogsModal($integrationId)
    {
        $this->selectedIntegration = Integration::find($integrationId);
        $this->showLogsModal = true;
    }

    public function loadIntegrationData()
    {
        $integration = $this->selectedIntegration;
        
        $this->name = $integration->name;
        $this->api_name = $integration->api_name;
        $this->description = $integration->description;
        $this->webhook_url = $integration->webhook_url;
        $this->http_method = $integration->http_method;
        $this->auth_type = $integration->auth_type;
        $this->auth_username = $integration->auth_username;
        $this->auth_password = ''; // Don't populate for security
        $this->auth_token = ''; // Don't populate for security
        $this->api_key_header = $integration->api_key_header;
        $this->api_key_value = ''; // Don't populate for security
        $this->timeout_seconds = $integration->timeout_seconds;
        $this->retry_attempts = $integration->retry_attempts;
        $this->verify_ssl = $integration->verify_ssl;
        $this->content_type = $integration->content_type;
        $this->is_active = $integration->is_active;
        
        $this->field_mappings = $integration->field_mappings ?? [];
        $this->trigger_conditions = $integration->trigger_conditions ?? [];
        $this->custom_headers = $integration->headers ? 
            collect($integration->headers)->map(fn($value, $key) => ['key' => $key, 'value' => $value])->values()->toArray() : 
            [['key' => '', 'value' => '']];
    }

    public function createIntegration()
    {
        $this->validate();

        $data = $this->getFormData();
        $data['user_id'] = Auth::id();

        Integration::create($data);

        $this->showCreateModal = false;
        session()->flash('message', 'Integration created successfully!');
        $this->resetForm();
    }

    public function updateIntegration()
    {
        $this->validate();

        $data = $this->getFormData();
        
        // Don't update password fields if they're empty (keeping existing)
        if (empty($this->auth_password)) {
            unset($data['auth_password']);
        }
        if (empty($this->auth_token)) {
            unset($data['auth_token']);
        }
        if (empty($this->api_key_value)) {
            unset($data['api_key_value']);
        }

        $this->selectedIntegration->update($data);

        $this->showEditModal = false;
        session()->flash('message', 'Integration updated successfully!');
    }

    private function getFormData()
    {
        // Process custom headers
        $headers = [];
        foreach ($this->custom_headers as $header) {
            if (!empty($header['key']) && !empty($header['value'])) {
                $headers[$header['key']] = $header['value'];
            }
        }

        // Clean up field mappings
        $fieldMappings = array_filter($this->field_mappings, function($mapping) {
            return !empty($mapping['source_field']) && !empty($mapping['target_field']);
        });

        // Clean up trigger conditions
        $triggerConditions = array_filter($this->trigger_conditions, function($condition) {
            return !empty($condition['event']);
        });

        return [
            'name' => $this->name,
            'api_name' => $this->api_name,
            'description' => $this->description,
            'webhook_url' => $this->webhook_url,
            'http_method' => $this->http_method,
            'auth_type' => $this->auth_type,
            'auth_username' => $this->auth_username,
            'auth_password' => $this->auth_password,
            'auth_token' => $this->auth_token,
            'api_key_header' => $this->api_key_header,
            'api_key_value' => $this->api_key_value,
            'headers' => $headers,
            'field_mappings' => array_values($fieldMappings),
            'trigger_conditions' => array_values($triggerConditions),
            'timeout_seconds' => $this->timeout_seconds,
            'retry_attempts' => $this->retry_attempts,
            'verify_ssl' => $this->verify_ssl,
            'content_type' => $this->content_type,
            'is_active' => $this->is_active,
        ];
    }

    public function addFieldMapping()
    {
        $this->field_mappings[] = ['source_field' => '', 'target_field' => '', 'default_value' => ''];
    }

    public function removeFieldMapping($index)
    {
        unset($this->field_mappings[$index]);
        $this->field_mappings = array_values($this->field_mappings);
    }

    public function addTriggerCondition()
    {
        $this->trigger_conditions[] = ['field' => '', 'operator' => '=', 'value' => '', 'event' => 'offer_accepted'];
    }

    public function removeTriggerCondition($index)
    {
        unset($this->trigger_conditions[$index]);
        $this->trigger_conditions = array_values($this->trigger_conditions);
    }

    public function addCustomHeader()
    {
        $this->custom_headers[] = ['key' => '', 'value' => ''];
    }

    public function removeCustomHeader($index)
    {
        unset($this->custom_headers[$index]);
        $this->custom_headers = array_values($this->custom_headers);
    }

    public function testIntegration()
    {
        try {
            $integrationService = new IntegrationService();
            
            $applicationId = $this->test_application_id ?: null;
            $log = $integrationService->testIntegration($this->selectedIntegration, $applicationId);
            
            $this->test_result = [
                'success' => $log->status === 'success',
                'status' => $log->status,
                'response_status' => $log->response_status,
                'response_time' => $log->response_time_ms,
                'response_body' => $log->response_body,
                'error_message' => $log->error_message,
                'request_payload' => $log->request_payload,
            ];

            session()->flash('message', 'Integration test completed!');
        } catch (\Exception $e) {
            $this->test_result = [
                'success' => false,
                'error_message' => $e->getMessage(),
            ];
            session()->flash('error', 'Integration test failed: ' . $e->getMessage());
        }
    }

    public function toggleIntegrationStatus($integrationId)
    {
        $integration = Integration::find($integrationId);
        $integration->update(['is_active' => !$integration->is_active]);
        
        session()->flash('message', 'Integration status updated successfully!');
    }

    public function deleteIntegration($integrationId)
    {
        Integration::find($integrationId)->delete();
        session()->flash('message', 'Integration deleted successfully!');
    }

    public function resetForm()
    {
        $this->reset([
            'name', 'api_name', 'description', 'webhook_url', 'http_method',
            'auth_type', 'auth_username', 'auth_password', 'auth_token',
            'api_key_header', 'api_key_value', 'timeout_seconds', 'retry_attempts',
            'verify_ssl', 'content_type', 'is_active'
        ]);
        
        $this->selectedIntegration = null;
    }

    public function render()
    {
        $query = Integration::where('user_id', Auth::id())
            ->when($this->search, function ($q) {
                $q->where(function ($query) {
                    $query->where('name', 'like', '%' . $this->search . '%')
                          ->orWhere('api_name', 'like', '%' . $this->search . '%')
                          ->orWhere('webhook_url', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->status_filter, function ($q) {
                if ($this->status_filter === 'active') {
                    $q->where('is_active', true);
                } elseif ($this->status_filter === 'inactive') {
                    $q->where('is_active', false);
                }
            });

        $integrations = $query->orderBy('created_at', 'desc')->paginate(10);

        $recentApplications = Application::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get(['id', 'application_number', 'first_name', 'last_name', 'status']);

        $integrationLogs = $this->selectedIntegration ? 
            IntegrationLog::where('integration_id', $this->selectedIntegration->id)
                ->orderBy('created_at', 'desc')
                ->limit(20)
                ->get() : collect();

        return view('livewire.admin.integration-management', [
            'integrations' => $integrations,
            'recentApplications' => $recentApplications,
            'integrationLogs' => $integrationLogs,
        ]);
    }
}