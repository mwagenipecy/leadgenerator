<div class="min-h-screen bg-gray-50">
    <div class="p-8">
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-4xl font-bold text-gray-900 mb-2">System Logs</h1>
                    <p class="text-gray-600 text-lg">Monitor and track all critical user actions and system events</p>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Logs -->
            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100 hover:shadow-md transition-all duration-300 group hover:border-blue-500/20">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-14 h-14 bg-blue-100 rounded-2xl flex items-center justify-center group-hover:bg-blue-200 transition-colors">
                        <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-medium text-gray-500">Total Logs</p>
                        <p class="text-lg font-bold text-gray-900 group-hover:text-blue-600 transition-colors">{{ number_format($totalLogs) }}</p>
                    </div>
                </div>
            </div>

            <!-- Critical Logs -->
            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100 hover:shadow-md transition-all duration-300 group hover:border-sidebar-green/20">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-14 h-14 bg-sidebar-green-100 rounded-2xl flex items-center justify-center group-hover:bg-sidebar-green-200 transition-colors">
                        <svg class="w-7 h-7 text-sidebar-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.464 0L4.348 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                        </svg>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-medium text-gray-500">Critical Logs</p>
                        <p class="text-lg font-bold text-gray-900 group-hover:text-sidebar-green transition-colors">{{ number_format($criticalLogs) }}</p>
                    </div>
                </div>
            </div>

            <!-- Today's Logs -->
            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100 hover:shadow-md transition-all duration-300 group hover:border-green-500/20">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-14 h-14 bg-green-100 rounded-2xl flex items-center justify-center group-hover:bg-green-200 transition-colors">
                        <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-medium text-gray-500">Today's Logs</p>
                        <p class="text-lg font-bold text-gray-900 group-hover:text-green-600 transition-colors">{{ number_format($todayLogs) }}</p>
                    </div>
                </div>
            </div>

            <!-- This Week's Logs -->
            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100 hover:shadow-md transition-all duration-300 group hover:border-purple-500/20">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-14 h-14 bg-purple-100 rounded-2xl flex items-center justify-center group-hover:bg-purple-200 transition-colors">
                        <svg class="w-7 h-7 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-medium text-gray-500">This Week</p>
                        <p class="text-lg font-bold text-gray-900 group-hover:text-purple-600 transition-colors">{{ number_format($thisWeekLogs) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100 mb-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4">
                <!-- Search -->
                <div class="lg:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Search Logs</label>
                    <div class="relative">
                        <input wire:model.live="search" type="text" placeholder="Search by action, description, IP..." 
                               class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <svg class="w-4 h-4 text-gray-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                </div>

                <!-- Severity Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Severity</label>
                    <select wire:model.live="severityFilter" class="w-full border border-gray-300 rounded-xl px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All Severities</option>
                        @foreach($severities as $severity)
                            <option value="{{ $severity }}">{{ ucfirst($severity) }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Action Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Action</label>
                    <select wire:model.live="actionFilter" class="w-full border border-gray-300 rounded-xl px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All Actions</option>
                        @foreach($actions as $action)
                            <option value="{{ $action }}">{{ ucfirst(str_replace('_', ' ', $action)) }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- User Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">User</label>
                    <select wire:model.live="userFilter" class="w-full border border-gray-300 rounded-xl px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All Users</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name ?? $user->email }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Date From -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Date From</label>
                    <input wire:model.live="dateFrom" type="date" 
                           class="w-full border border-gray-300 rounded-xl px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Date To -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Date To</label>
                    <input wire:model.live="dateTo" type="date" 
                           class="w-full border border-gray-300 rounded-xl px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>
        </div>

        <!-- Logs Table -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-8 py-6 border-b border-gray-100 bg-gradient-to-r from-white to-gray-50">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-1">Activity Logs</h3>
                        <p class="text-gray-600">View all system activity and user actions</p>
                    </div>
                    <div class="text-sm text-gray-500">
                        Showing {{ $logs->count() }} of {{ $logs->total() }} logs
                    </div>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Timestamp</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">User</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Action</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Description</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Severity</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">IP Address</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($logs as $log)
                            <tr class="hover:bg-gray-50 transition-colors duration-200 group">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $log->created_at->format('M d, Y') }}</div>
                                    <div class="text-xs text-gray-500">{{ $log->created_at->format('g:i A') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($log->user)
                                        <div class="flex items-center">
                                            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center">
                                                <span class="text-white text-xs font-bold">
                                                    {{ substr($log->user->name ?? $log->user->email, 0, 2) }}
                                                </span>
                                            </div>
                                            <div class="ml-3">
                                                <div class="text-sm font-medium text-gray-900">{{ $log->user->name ?? 'N/A' }}</div>
                                                <div class="text-xs text-gray-500">{{ $log->user->email }}</div>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-sm text-gray-400 italic">System</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800 border border-blue-200">
                                        {{ ucfirst(str_replace('_', ' ', $log->action)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900 max-w-md truncate">{{ $log->description }}</div>
                                    @if($log->model_type)
                                        <div class="text-xs text-gray-500 mt-1">
                                            {{ class_basename($log->model_type) }}
                                            @if($log->model_id)
                                                #{{ $log->model_id }}
                                            @endif
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $severityColors = [
                                            'low' => 'bg-gray-100 text-gray-800 border-gray-200',
                                            'medium' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                            'high' => 'bg-orange-100 text-orange-800 border-orange-200',
                                            'critical' => 'bg-sidebar-green-100 text-sidebar-green-800 border-sidebar-green-200',
                                        ];
                                        $color = $severityColors[$log->severity] ?? $severityColors['medium'];
                                    @endphp
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold border {{ $color }}">
                                        {{ ucfirst($log->severity) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $log->ip_address ?? 'N/A' }}</div>
                                    @if($log->user_agent)
                                        <div class="text-xs text-gray-500 truncate max-w-xs" title="{{ $log->user_agent }}">
                                            {{ Str::limit($log->user_agent, 30) }}
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <button wire:click="viewLogDetail({{ $log->id }})" 
                                            class="text-blue-600 hover:text-blue-700 p-2 rounded-xl hover:bg-blue-50 transition-all duration-200"
                                            title="View Details">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-8 py-12 text-center">
                                    <div class="w-20 h-20 bg-gray-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    </div>
                                    <h4 class="text-lg font-semibold text-gray-900 mb-2">No Logs Found</h4>
                                    <p class="text-gray-500">No logs match your current search criteria.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($logs->hasPages())
                <div class="px-8 py-4 border-t border-gray-100">
                    {{ $logs->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Log Detail Modal -->
    @if($showLogDetailModal && $selectedLog)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" 
             wire:click.self="closeLogDetailModal"
             x-data="{ show: true }" 
             x-show="show" 
             x-transition:enter="ease-out duration-300" 
             x-transition:enter-start="opacity-0" 
             x-transition:enter-end="opacity-100">
            <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-4xl shadow-lg rounded-lg bg-white"
                 x-transition:enter="ease-out duration-300" 
                 x-transition:enter-start="opacity-0 transform scale-95" 
                 x-transition:enter-end="opacity-100 transform scale-100">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-2xl font-bold text-gray-900">Log Details</h3>
                    <button wire:click="closeLogDetailModal" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <div class="space-y-6">
                    <!-- Basic Information -->
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Action</label>
                            <div class="text-sm text-gray-900 bg-gray-50 p-3 rounded-lg">
                                {{ ucfirst(str_replace('_', ' ', $selectedLog->action)) }}
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Severity</label>
                            @php
                                $severityColors = [
                                    'low' => 'bg-gray-100 text-gray-800',
                                    'medium' => 'bg-yellow-100 text-yellow-800',
                                    'high' => 'bg-orange-100 text-orange-800',
                                    'critical' => 'bg-sidebar-green-100 text-sidebar-green-800',
                                ];
                                $color = $severityColors[$selectedLog->severity] ?? $severityColors['medium'];
                            @endphp
                            <div class="text-sm {{ $color }} p-3 rounded-lg font-semibold">
                                {{ ucfirst($selectedLog->severity) }}
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">User</label>
                            <div class="text-sm text-gray-900 bg-gray-50 p-3 rounded-lg">
                                {{ $selectedLog->user ? $selectedLog->user->name . ' (' . $selectedLog->user->email . ')' : 'System' }}
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Timestamp</label>
                            <div class="text-sm text-gray-900 bg-gray-50 p-3 rounded-lg">
                                {{ $selectedLog->created_at->format('M d, Y g:i A') }}
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">IP Address</label>
                            <div class="text-sm text-gray-900 bg-gray-50 p-3 rounded-lg">
                                {{ $selectedLog->ip_address ?? 'N/A' }}
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Request Method</label>
                            <div class="text-sm text-gray-900 bg-gray-50 p-3 rounded-lg">
                                {{ $selectedLog->request_method ?? 'N/A' }}
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <div class="text-sm text-gray-900 bg-gray-50 p-3 rounded-lg">
                            {{ $selectedLog->description }}
                        </div>
                    </div>

                    <!-- Model Information -->
                    @if($selectedLog->model_type)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Affected Model</label>
                            <div class="text-sm text-gray-900 bg-gray-50 p-3 rounded-lg">
                                {{ class_basename($selectedLog->model_type) }}
                                @if($selectedLog->model_id)
                                    (ID: {{ $selectedLog->model_id }})
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Old Values -->
                    @if($selectedLog->old_values)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Old Values</label>
                            <div class="bg-sidebar-green-50 border border-sidebar-green-200 p-4 rounded-lg">
                                <pre class="text-xs text-gray-800 whitespace-pre-wrap">{{ json_encode($selectedLog->old_values, JSON_PRETTY_PRINT) }}</pre>
                            </div>
                        </div>
                    @endif

                    <!-- New Values -->
                    @if($selectedLog->new_values)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">New Values</label>
                            <div class="bg-green-50 border border-green-200 p-4 rounded-lg">
                                <pre class="text-xs text-gray-800 whitespace-pre-wrap">{{ json_encode($selectedLog->new_values, JSON_PRETTY_PRINT) }}</pre>
                            </div>
                        </div>
                    @endif

                    <!-- Metadata -->
                    @if($selectedLog->metadata)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Metadata</label>
                            <div class="bg-blue-50 border border-blue-200 p-4 rounded-lg">
                                <pre class="text-xs text-gray-800 whitespace-pre-wrap">{{ json_encode($selectedLog->metadata, JSON_PRETTY_PRINT) }}</pre>
                            </div>
                        </div>
                    @endif

                    <!-- User Agent -->
                    @if($selectedLog->user_agent)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">User Agent</label>
                            <div class="text-sm text-gray-900 bg-gray-50 p-3 rounded-lg">
                                {{ $selectedLog->user_agent }}
                            </div>
                        </div>
                    @endif

                    <!-- Request URL -->
                    @if($selectedLog->request_url)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Request URL</label>
                            <div class="text-sm text-gray-900 bg-gray-50 p-3 rounded-lg break-all">
                                {{ $selectedLog->request_url }}
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Close Button -->
                <div class="flex justify-end mt-6 pt-6 border-t border-gray-200">
                    <button wire:click="closeLogDetailModal" 
                            class="bg-gray-100 text-gray-700 px-6 py-2 rounded-xl font-medium hover:bg-gray-200 transition-colors">
                        Close
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
