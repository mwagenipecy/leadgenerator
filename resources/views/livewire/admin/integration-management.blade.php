<div>
    <div class="p-8">
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-4xl font-bold text-gray-900 mb-2">{{ __('integration.integration_management') }}</h1>
                    <p class="text-gray-600 text-lg">{{ __('integration.create_manage_webhooks') }}</p>
                </div>
                <div class="flex items-center space-x-3">
                    <div class="flex items-center space-x-2 bg-blue-50 px-4 py-2 rounded-full">
                        <div class="w-2 h-2 bg-blue-400 rounded-full animate-pulse"></div>
                        <span class="text-sm font-medium text-blue-700">{{ __('integration.webhooks_active') }}</span>
                </div>
                    <button wire:click="openCreateModal" 
                            class="bg-sidebar-green text-white px-6 py-2 rounded-lg font-semibold hover:bg-sidebar-green-light transition-all duration-200 shadow-lg shadow-sidebar-green/25">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        {{ __('integration.create_integration') }}
                    </button>
                </div>
            </div>
        </div>

        <!-- Flash Messages -->
        @if (session()->has('message'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-6 py-4 rounded-lg mb-6 flex items-center space-x-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <span>{{ session('message') }}</span>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="bg-sidebar-green-100 border border-sidebar-green-400 text-sidebar-green-light px-6 py-4 rounded-lg mb-6 flex items-center space-x-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100 mb-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Search -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('integration.search_integrations') }}</label>
                    <div class="relative">
                        <input wire:model.live="search" type="text" placeholder="{{ __('integration.search_by_name_api_url') }}" 
                               class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green">
                        <svg class="w-4 h-4 text-gray-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                </div>

                <!-- Status Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('integration.filter_by_status') }}</label>
                    <select wire:model.live="status_filter" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green">
                        <option value="">{{ __('integration.all_statuses') }}</option>
                        <option value="active">{{ __('integration.active') }}</option>
                        <option value="inactive">{{ __('integration.inactive') }}</option>
                    </select>
                </div>

                <!-- Quick Stats -->
                <div class="flex items-end">
                    <div class="grid grid-cols-2 gap-4 w-full">
                        <div class="text-center p-3 bg-green-50 rounded-lg">
                            <div class="text-2xl font-bold text-green-600">{{ $integrations->where('is_active', true)->count() }}</div>
                            <div class="text-xs text-green-600">{{ __('integration.active') }}</div>
                        </div>
                        <div class="text-center p-3 bg-gray-50 rounded-lg">
                            <div class="text-2xl font-bold text-gray-600">{{ $integrations->total() }}</div>
                            <div class="text-xs text-gray-600">{{ __('integration.total') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Integrations Table -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-8 py-6 border-b border-gray-100 bg-gradient-to-r from-white to-gray-50">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-1">{{ __('integration.your_integrations') }}</h3>
                        <p class="text-gray-600">{{ __('integration.manage_webhook_endpoints') }}</p>
                    </div>
                    <div class="text-sm text-gray-500">
                        {{ __('integration.showing') }} {{ $integrations->count() }} {{ __('integration.of') }} {{ $integrations->total() }} {{ __('integration.integrations') }}
                    </div>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-8 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('integration.integration') }}</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('integration.endpoint') }}</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('integration.auth_method') }}</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('integration.status') }}</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('integration.last_used') }}</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('common.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($integrations as $integration)
                            <tr class="hover:bg-gray-50 transition-colors duration-200 group">
                                <td class="px-8 py-6 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-12 h-12 bg-gradient-to-br from-gray-800 to-sidebar-green rounded-2xl flex items-center justify-center shadow-md">
                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-bold text-gray-900 group-hover:text-sidebar-green transition-colors">{{ $integration->name }}</div>
                                            <div class="text-xs text-gray-500">API: {{ $integration->api_name }}</div>
                                            @if($integration->description)
                                                <div class="text-xs text-gray-400 mt-1">{{ Str::limit($integration->description, 50) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-6 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ Str::limit($integration->webhook_url, 40) }}</div>
                                    <div class="text-xs text-gray-500">{{ $integration->http_method }}</div>
                                </td>
                                <td class="px-6 py-6 whitespace-nowrap">
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold
                                        @if($integration->auth_type === 'none') bg-gray-100 text-gray-800
                                        @elseif($integration->auth_type === 'basic') bg-blue-100 text-blue-800
                                        @elseif($integration->auth_type === 'bearer') bg-green-100 text-green-800
                                        @else bg-purple-100 text-purple-800
                                        @endif">
                                        {{ ucfirst(str_replace('_', ' ', $integration->auth_type)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-6 whitespace-nowrap">
                                    <button wire:click="toggleIntegrationStatus({{ $integration->id }})"
                                        class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold cursor-pointer transition-colors
                                            {{ $integration->is_active ? 'bg-green-100 text-green-800 border border-green-200 hover:bg-green-200' : 'bg-sidebar-green-100 text-sidebar-green-800 border border-sidebar-green-200 hover:bg-sidebar-green-200' }}">
                                        <div class="w-2 h-2 rounded-full mr-2 {{ $integration->is_active ? 'bg-green-400' : 'bg-sidebar-green-400' }}"></div>
                                        {{ $integration->is_active ? __('integration.active') : __('integration.inactive') }}
                                    </button>
                                </td>
                                <td class="px-6 py-6 whitespace-nowrap">
                                    @if($integration->logs()->latest()->first())
                                        <div class="text-sm font-medium text-gray-900">{{ $integration->logs()->latest()->first()->created_at->format('M d, Y') }}</div>
                                        <div class="text-xs text-gray-500">{{ $integration->logs()->latest()->first()->created_at->format('g:i A') }}</div>
                                    @else
                                        <span class="text-xs text-gray-400">{{ __('integration.never_used') }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-6 whitespace-nowrap">
                                    <div class="flex items-center space-x-2">
                                        <button wire:click="openTestModal({{ $integration->id }})" 
                                            class="text-green-600 hover:text-green-700 p-2 rounded-lg hover:bg-green-50 transition-all duration-200" 
                                            title="{{ __('integration.test_integration') }}">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h8m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                        </button>
                                        <button wire:click="openLogsModal({{ $integration->id }})" 
                                            class="text-blue-600 hover:text-blue-700 p-2 rounded-lg hover:bg-blue-50 transition-all duration-200"
                                            title="{{ __('integration.view_logs') }}">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                        </button>
                                        <button wire:click="openEditModal({{ $integration->id }})" 
                                            class="text-yellow-600 hover:text-yellow-700 p-2 rounded-lg hover:bg-yellow-50 transition-all duration-200"
                                            title="{{ __('integration.edit_integration') }}">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </button>
                                        <button wire:click="deleteIntegration({{ $integration->id }})" 
                                            class="text-sidebar-green hover:text-sidebar-green-light p-2 rounded-lg hover:bg-sidebar-green-50 transition-all duration-200"
                                            title="{{ __('integration.delete_integration') }}">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-8 py-12 text-center">
                                    <div class="w-20 h-20 bg-gray-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <h4 class="text-lg font-semibold text-gray-900 mb-2">{{ __('integration.no_integrations_found') }}</h4>
                                    <p class="text-gray-500 mb-4">{{ __('integration.create_first_integration') }}</p>
                                    <button wire:click="openCreateModal" 
                                        class="bg-sidebar-green text-white px-6 py-2 rounded-lg font-semibold hover:bg-sidebar-green-light transition-colors">
                                        {{ __('integration.create_your_first_integration') }}
                                    </button>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($integrations->hasPages())
                <div class="px-8 py-4 border-t border-gray-100">
                    {{ $integrations->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Create Integration Modal -->
    @if($showCreateModal)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" wire:click.self="$set('showCreateModal', false)">
            <div class="relative top-10 mx-auto p-5 border w-11/12 max-w-6xl shadow-lg rounded-lg bg-white">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-2xl font-bold text-gray-900">{{ __('integration.create_new_integration') }}</h3>
                    <button wire:click="$set('showCreateModal', false)" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <form wire:submit.prevent="createIntegration" class="space-y-6">
                    <!-- Basic Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('integration.integration_name') }} *</label>
                            <input wire:model.live="name" type="text" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green">
                            @error('name') <span class="text-sidebar-green text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('integration.api_name') }} *</label>
                            <input wire:model="api_name" type="text" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green">
                            @error('api_name') <span class="text-sidebar-green text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('integration.description') }}</label>
                            <textarea wire:model="description" rows="2" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green"></textarea>
                        </div>
                    </div>

                    <!-- Webhook Configuration -->
                    <div class="border-t pt-6">
                        <h4 class="text-lg font-semibold text-gray-900 mb-4">{{ __('integration.webhook_configuration') }}</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('integration.webhook_url') }} *</label>
                                <input wire:model="webhook_url" type="url" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green" placeholder="https://your-api.com/webhook">
                                @error('webhook_url') <span class="text-sidebar-green text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('integration.http_method') }}</label>
                                <select wire:model="http_method" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green">
                                    <option value="POST">POST</option>
                                    <option value="PUT">PUT</option>
                                    <option value="PATCH">PATCH</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('integration.content_type') }}</label>
                                <select wire:model="content_type" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green">
                                    <option value="application/json">application/json</option>
                                    <option value="application/x-www-form-urlencoded">application/x-www-form-urlencoded</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Authentication -->
                    <div class="border-t pt-6">
                        <h4 class="text-lg font-semibold text-gray-900 mb-4">{{ __('integration.authentication') }}</h4>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('integration.authentication_type') }}</label>
                                <select wire:model.live="auth_type" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green">
                                    <option value="none">{{ __('integration.none') }}</option>
                                    <option value="basic">{{ __('integration.basic_authentication') }}</option>
                                    <option value="bearer">{{ __('integration.bearer_token') }}</option>
                                    <option value="api_key">{{ __('integration.api_key') }}</option>
                                </select>
                            </div>

                            @if($auth_type === 'basic')
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('integration.username') }} *</label>
                                        <input wire:model="auth_username" type="text" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green">
                                        @error('auth_username') <span class="text-sidebar-green text-xs">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('integration.password') }} *</label>
                                        <input wire:model="auth_password" type="password" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green">
                                        @error('auth_password') <span class="text-sidebar-green text-xs">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            @elseif($auth_type === 'bearer')
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('integration.bearer_token_label') }} *</label>
                                    <input wire:model="auth_token" type="password" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green">
                                    @error('auth_token') <span class="text-sidebar-green text-xs">{{ $message }}</span> @enderror
                                </div>
                            @elseif($auth_type === 'api_key')
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('integration.header_name') }} *</label>
                                        <input wire:model="api_key_header" type="text" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green" placeholder="X-API-Key">
                                        @error('api_key_header') <span class="text-sidebar-green text-xs">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('integration.api_key') }} *</label>
                                        <input wire:model="api_key_value" type="password" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green">
                                        @error('api_key_value') <span class="text-sidebar-green text-xs">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Action Trigger -->
                    <div class="border-t pt-6">
                        <h4 class="text-lg font-semibold text-gray-900 mb-4">{{ __('integration.activation_trigger') }}</h4>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('integration.trigger_on_status_label') }} *</label>
                            <select wire:model="action_on" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green">
                                <option value="">{{ __('integration.select_action_status') }}</option>
                                @foreach ($applicationStatus as $status)
                                    <option value="{{ $status }}">{{ $status === 'approved' ? __('integration.approved') : __('integration.disbursed') }}</option>
                                @endforeach
                            </select>
                            @error('action_on') <span class="text-sidebar-green text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Field Mappings -->
                    <div class="border-t pt-6">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="text-lg font-semibold text-gray-900">{{ __('integration.field_mappings') }}</h4>
                            <button type="button" wire:click="addFieldMapping" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors">
                                {{ __('integration.add_field') }}
                            </button>
                        </div>
                        <div class="space-y-3">
                            @foreach($field_mappings as $index => $mapping)
                                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">{{ __('integration.source_field') }}</label>
                                        <select wire:model="field_mappings.{{ $index }}.source_field" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green">
                                            <option value="">{{ __('integration.select_field') }}</option>
                                            @foreach($available_fields as $key => $label)
                                                <option value="{{ $key }}">{{ $label }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">{{ __('integration.target_field') }}</label>
                                        <input wire:model="field_mappings.{{ $index }}.target_field" type="text" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green" placeholder="api_field_name">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">{{ __('integration.default_value') }}</label>
                                        <input wire:model="field_mappings.{{ $index }}.default_value" type="text" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green" placeholder="{{ __('integration.optional') }}">
                                    </div>
                                    <div>
                                        <button type="button" wire:click="removeFieldMapping({{ $index }})" class="w-full bg-sidebar-green-100 text-sidebar-green px-3 py-2 rounded-lg text-sm font-medium hover:bg-sidebar-green-200 transition-colors">
                                            {{ __('integration.remove') }}
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Advanced Settings -->
                    <div class="border-t pt-6">
                        <h4 class="text-lg font-semibold text-gray-900 mb-4">{{ __('integration.advanced_settings') }}</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('integration.timeout_seconds') }}</label>
                                <input wire:model="timeout_seconds" type="number" min="5" max="300" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('integration.retry_attempts') }}</label>
                                <input wire:model="retry_attempts" type="number" min="0" max="10" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green">
                            </div>
                            <div class="flex items-end">
                                <label class="flex items-center">
                                    <input wire:model="verify_ssl" type="checkbox" class="h-4 w-4 text-sidebar-green focus:ring-sidebar-green border-gray-300 rounded">
                                    <span class="ml-2 text-sm text-gray-700">{{ __('integration.verify_ssl_certificate') }}</span>
                                </label>
                            </div>
                        </div>
                        <div class="mt-4">
                            <label class="flex items-center">
                                <input wire:model="is_active" type="checkbox" class="h-4 w-4 text-sidebar-green focus:ring-sidebar-green border-gray-300 rounded">
                                <span class="ml-2 text-sm text-gray-700">{{ __('integration.integration_is_active_label') }}</span>
                            </label>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-end space-x-4 pt-6 border-t">
                        <button type="button" wire:click="$set('showCreateModal', false)" 
                            class="bg-gray-100 text-gray-700 px-6 py-2 rounded-lg font-medium hover:bg-gray-200 transition-colors">
                            {{ __('common.cancel') }}
                        </button>
                        <button type="submit" 
                            class="bg-sidebar-green text-white px-6 py-2 rounded-lg font-semibold hover:bg-sidebar-green-light transition-colors">
                            {{ __('integration.create_integration') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Edit Integration Modal -->
    @if($showEditModal && $selectedIntegration)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" wire:click.self="$set('showEditModal', false)">
            <div class="relative top-10 mx-auto p-5 border w-11/12 max-w-6xl shadow-lg rounded-lg bg-white">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-2xl font-bold text-gray-900">{{ __('integration.edit_integration_colon') }}: {{ $selectedIntegration->name }}</h3>
                    <button wire:click="$set('showEditModal', false)" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <form wire:submit.prevent="updateIntegration" class="space-y-6">
                    <!-- Same form structure as create modal, but with update action -->
                    <!-- Basic Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('integration.integration_name') }} *</label>
                            <input wire:model.live="name" type="text" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green">
                            @error('name') <span class="text-sidebar-green text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('integration.api_name') }} *</label>
                            <input wire:model="api_name" type="text" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green">
                            @error('api_name') <span class="text-sidebar-green text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('integration.description') }}</label>
                            <textarea wire:model="description" rows="2" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green"></textarea>
                        </div>
                    </div>

                    <!-- Action Trigger -->
                    <div class="border-t pt-6">
                        <h4 class="text-lg font-semibold text-gray-900 mb-4">{{ __('integration.activation_trigger') }}</h4>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('integration.trigger_on_status_label') }} *</label>
                            <select wire:model="action_on" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green">
                                <option value="">{{ __('integration.select_action_status') }}</option>
                                @foreach ($applicationStatus as $status)
                                    <option value="{{ $status }}">{{ $status === 'approved' ? __('integration.approved') : __('integration.disbursed') }}</option>
                                @endforeach
                            </select>
                            @error('action_on') <span class="text-sidebar-green text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-end space-x-4 pt-6 border-t">
                        <button type="button" wire:click="$set('showEditModal', false)" 
                            class="bg-gray-100 text-gray-700 px-6 py-2 rounded-lg font-medium hover:bg-gray-200 transition-colors">
                            {{ __('common.cancel') }}
                        </button>
                        <button type="submit" 
                            class="bg-sidebar-green text-white px-6 py-2 rounded-lg font-semibold hover:bg-sidebar-green-light transition-colors">
                            {{ __('integration.update_integration') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Test Integration Modal -->
    @if($showTestModal && $selectedIntegration)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" wire:click.self="$set('showTestModal', false)">
            <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-4xl shadow-lg rounded-lg bg-white">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-2xl font-bold text-gray-900">{{ __('integration.test_integration_colon') }}: {{ $selectedIntegration->name }}</h3>
                    <button wire:click="$set('showTestModal', false)" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <form wire:submit.prevent="testIntegration" class="space-y-6">
                    <!-- Test Configuration -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('integration.test_with_application') }} *</label>
                        <select wire:model="test_application_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green">
                            <option value="">{{ __('integration.select_application') }}</option>
                            @foreach($recentApplications as $app)
                                <option value="{{ $app->id }}">{{ $app->application_number }} - {{ $app->first_name }} {{ $app->last_name }}</option>
                            @endforeach
                        </select>
                        @error('test_application_id') <span class="text-sidebar-green text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Test Button -->
                    <div class="text-center">
                        <button type="submit" 
                            class="bg-sidebar-green text-white px-8 py-3 rounded-lg font-semibold hover:bg-sidebar-green-light transition-colors">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h8m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            {{ __('integration.test_integration') }}
                        </button>
                    </div>
                </form>

                <!-- Test Results -->
                @if($test_result)
                    <div class="bg-gray-50 rounded-2xl p-6 mt-6">
                        <h4 class="text-lg font-semibold text-gray-900 mb-4">{{ __('integration.test_results') }}</h4>
                        
                        <!-- Status -->
                        <div class="mb-4">
                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold
                                {{ $test_result['success'] ? 'bg-green-100 text-green-800' : 'bg-sidebar-green-100 text-sidebar-green-800' }}">
                                @if($test_result['success'])
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    {{ __('integration.success') }}
                                @else
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    {{ __('integration.failed') }}
                                @endif
                            </span>
                            @if(isset($test_result['response_status']))
                                <span class="ml-2 text-sm text-gray-600">HTTP {{ $test_result['response_status'] }}</span>
                            @endif
                            @if(isset($test_result['response_time']))
                                <span class="ml-2 text-sm text-gray-600">{{ number_format($test_result['response_time'], 2) }}ms</span>
                            @endif
                        </div>

                        <!-- Response -->
                        @if(isset($test_result['response_body']))
                            <div class="mb-4">
                                <h5 class="text-sm font-medium text-gray-700 mb-2">{{ __('integration.response_body') }}:</h5>
                                <pre class="bg-white border rounded-lg p-3 text-xs overflow-x-auto">{{ $test_result['response_body'] }}</pre>
                            </div>
                        @endif

                        <!-- Request Payload -->
                        @if(isset($test_result['request_payload']))
                            <div class="mb-4">
                                <h5 class="text-sm font-medium text-gray-700 mb-2">{{ __('integration.request_payload') }}:</h5>
                                <pre class="bg-white border rounded-lg p-3 text-xs overflow-x-auto">{{ json_encode($test_result['request_payload'], JSON_PRETTY_PRINT) }}</pre>
                            </div>
                        @endif

                        <!-- Error -->
                        @if(isset($test_result['error_message']))
                            <div class="mb-4">
                                <h5 class="text-sm font-medium text-sidebar-green-light mb-2">{{ __('integration.error_message') }}:</h5>
                                <div class="bg-sidebar-green-50 border border-sidebar-green-200 rounded-lg p-3 text-sm text-sidebar-green-light">{{ $test_result['error_message'] }}</div>
                            </div>
                        @endif
                    </div>
                @endif

                <!-- Close Button -->
                <div class="flex justify-end pt-6 border-t">
                    <button wire:click="$set('showTestModal', false)" 
                        class="bg-gray-100 text-gray-700 px-6 py-2 rounded-lg font-medium hover:bg-gray-200 transition-colors">
                        {{ __('integration.close') }}
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Integration Logs Modal -->
    @if($showLogsModal && $selectedIntegration)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" wire:click.self="$set('showLogsModal', false)">
            <div class="relative top-10 mx-auto p-5 border w-11/12 max-w-6xl shadow-lg rounded-lg bg-white">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-2xl font-bold text-gray-900">{{ __('integration.integration_logs') }}: {{ $selectedIntegration->name }}</h3>
                    <button wire:click="$set('showLogsModal', false)" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <!-- Logs Table -->
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase">{{ __('integration.event') }}</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase">{{ __('integration.status') }}</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase">{{ __('integration.response') }}</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase">{{ __('integration.time') }}</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase">{{ __('integration.date') }}</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @forelse($integrationLogs as $log)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-sm">
                                        <div class="font-medium text-gray-900">{{ $log->trigger_event }}</div>
                                        <div class="text-xs text-gray-500">App: {{ $log->application->application_number ?? 'N/A' }}</div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold
                                            @if($log->status === 'success') bg-green-100 text-green-800
                                            @elseif($log->status === 'failed') bg-sidebar-green-100 text-sidebar-green-800
                                            @elseif($log->status === 'retrying') bg-yellow-100 text-yellow-800
                                            @else bg-gray-100 text-gray-800
                                            @endif">
                                            {{ ucfirst($log->status) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        @if($log->response_status)
                                            <div class="font-medium">HTTP {{ $log->response_status }}</div>
                                        @endif
                                        @if($log->error_message)
                                            <div class="text-xs text-sidebar-green">{{ Str::limit($log->error_message, 50) }}</div>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        @if($log->response_time_ms)
                                            {{ number_format($log->response_time_ms, 2) }}ms
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-500">
                                        {{ $log->created_at->format('M d, Y g:i A') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                                        {{ __('integration.no_logs_found') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Close Button -->
                <div class="flex justify-end pt-6 border-t">
                    <button wire:click="$set('showLogsModal', false)" 
                        class="bg-gray-100 text-gray-700 px-6 py-2 rounded-lg font-medium hover:bg-gray-200 transition-colors">
                        {{ __('integration.close') }}
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Password Confirmation Modal -->
    @if($show)
        <div x-data="{ show: @entangle('show') }" 
             x-show="show" 
             class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-60"
             x-cloak>
            <div class="bg-white w-full max-w-md p-6 rounded shadow-lg relative">
                <h2 class="text-lg font-bold text-black mb-4">{{ __('integration.confirm_password') }}</h2>
                
                <p class="text-sm text-gray-700 mb-4">{{ __('integration.enter_password_to_continue') }}</p>

                <form wire:submit.prevent="confirm">
                    <div class="mb-4">
                        <input 
                            type="password" 
                            wire:model="password"
                            placeholder="{{ __('integration.password_placeholder') }}"
                            class="w-full px-4 py-2 border border-sidebar-green rounded focus:outline-none focus:ring-2 focus:ring-sidebar-green text-black"
                        >
                        @error('password') 
                            <span class="text-sm text-sidebar-green">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="flex justify-end space-x-2">
                        <button type="button" 
                                wire:click="$set('show', false)" 
                                class="px-4 py-2 bg-black text-white rounded hover:bg-gray-800">
                            {{ __('common.cancel') }}
                        </button>

                        <button type="submit" 
                                class="px-4 py-2 bg-sidebar-green text-white rounded hover:bg-sidebar-green-light">
                            {{ __('integration.confirm') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>