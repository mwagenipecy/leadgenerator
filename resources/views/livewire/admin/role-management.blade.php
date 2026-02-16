<div>
    <!-- Section Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 mb-1">{{ __('admin.role_management') }}</h2>
                <p class="text-gray-600 text-sm">{{ __('admin.manage_roles_description') }}</p>
            </div>
            <div class="flex items-center space-x-3">
                <button wire:click="openCreateRoleModal" class="bg-red-600 text-white px-6 py-2 rounded-xl font-semibold hover:bg-red-700 transition-all duration-200 shadow-lg shadow-red-600/25">
                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    {{ __('admin.create_role') }}
                </button>
            </div>
        </div>
    </div>

        <!-- Flash Messages -->
        @if (session()->has('message'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-6 py-4 rounded-3xl mb-6 flex items-center space-x-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <span>{{ session('message') }}</span>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="bg-sidebar-green-100 border border-sidebar-green-400 text-sidebar-green-light px-6 py-4 rounded-3xl mb-6 flex items-center space-x-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-3xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-all duration-300 group hover:border-purple-500/20">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-purple-100 rounded-2xl flex items-center justify-center group-hover:bg-purple-200 transition-colors">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900 group-hover:text-purple-600 transition-colors">{{ number_format($totalRoles) }}</p>
                    <p class="text-sm font-medium text-gray-500">{{ __('admin.total_roles') }}</p>
                </div>
            </div>

            <div class="bg-white rounded-3xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-all duration-300 group hover:border-blue-500/20">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-blue-100 rounded-2xl flex items-center justify-center group-hover:bg-blue-200 transition-colors">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900 group-hover:text-blue-600 transition-colors">{{ number_format($totalSystemRoles) }}</p>
                    <p class="text-sm font-medium text-gray-500">{{ __('admin.system_roles') }}</p>
                </div>
            </div>

            <div class="bg-white rounded-3xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-all duration-300 group hover:border-green-500/20">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-green-100 rounded-2xl flex items-center justify-center group-hover:bg-green-200 transition-colors">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4"/>
                        </svg>
                    </div>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900 group-hover:text-green-600 transition-colors">{{ number_format($totalCustomRoles) }}</p>
                    <p class="text-sm font-medium text-gray-500">{{ __('admin.custom_roles') }}</p>
                </div>
            </div>

            <div class="bg-white rounded-3xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-all duration-300 group hover:border-orange-500/20">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-orange-100 rounded-2xl flex items-center justify-center group-hover:bg-orange-200 transition-colors">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900 group-hover:text-orange-600 transition-colors">{{ number_format($totalActiveRoles) }}</p>
                    <p class="text-sm font-medium text-gray-500">{{ __('admin.active_roles') }}</p>
                </div>
            </div>
        </div>

        <!-- Filters and Search -->
        <div class="bg-white rounded-3xl shadow-sm p-6 border border-gray-100 mb-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('admin.search_roles') }}</label>
                    <div class="relative">
                        <input wire:model.live="search" type="text" placeholder="{{ __('admin.search_by_name') }}" 
                               class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                        <svg class="w-4 h-4 text-gray-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('admin.filter_by_type') }}</label>
                    <select wire:model.live="statusFilter" class="w-full border border-gray-300 rounded-xl px-3 py-2 focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                        <option value="">{{ __('admin.all_types') }}</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                        <option value="system">System Roles</option>
                        <option value="custom">Custom Roles</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('admin.filter_by_level') }}</label>
                    <select wire:model.live="levelFilter" class="w-full border border-gray-300 rounded-xl px-3 py-2 focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                        <option value="">{{ __('admin.all_levels') }}</option>
                        <option value="high">High (80-100)</option>
                        <option value="medium">Medium (50-79)</option>
                        <option value="low">Low (1-49)</option>
                    </select>
                </div>

                <div class="flex items-end">
                    <button wire:click="$refresh" class="w-full bg-gray-100 text-gray-700 px-4 py-2 rounded-xl font-medium hover:bg-gray-200 transition-colors">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        {{ __('admin.refresh') }}
                    </button>
                </div>
            </div>
        </div>

        <!-- Roles Table -->
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-8 py-6 border-b border-gray-100 bg-gradient-to-r from-white to-gray-50">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-1">{{ __('admin.roles_list') }}</h3>
                        <p class="text-gray-600">{{ __('admin.manage_roles_view') }}</p>
                    </div>
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-8 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('admin.role') }}</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('admin.level') }}</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('admin.users_count') }}</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('admin.permissions_count') }}</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('admin.type') }}</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('common.status') }}</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('common.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($roles as $role)
                            <tr class="hover:bg-gray-50 transition-colors duration-200 group">
                                <td class="px-8 py-6 whitespace-nowrap">
                                    <div class="flex items-center">
                                        @php
                                            $levelColor = match(true) {
                                                $role->level >= 80 => 'from-sidebar-green to-sidebar-green',
                                                $role->level >= 50 => 'from-purple-500 to-purple-600',
                                                default => 'from-blue-500 to-blue-600'
                                            };
                                        @endphp
                                        <div class="w-10 h-10 bg-gradient-to-br {{ $levelColor }} rounded-xl flex items-center justify-center shadow-md">
                                            <span class="text-white text-xs font-bold">{{ $role->level }}</span>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-bold text-gray-900 group-hover:text-purple-600 transition-colors">
                                                {{ $role->display_name }}
                                            </div>
                                            <div class="text-xs text-gray-500">{{ $role->name }}</div>
                                            @if($role->description)
                                                <div class="text-xs text-gray-400 mt-1 line-clamp-1">{{ $role->description }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-6 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold 
                                            @if($role->level >= 80) bg-sidebar-green-100 text-sidebar-green-800 border border-sidebar-green-200
                                            @elseif($role->level >= 50) bg-purple-100 text-purple-800 border border-purple-200
                                            @else bg-blue-100 text-blue-800 border border-blue-200
                                            @endif">
                                            Level {{ $role->level }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-6 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="text-lg font-bold text-gray-900">{{ number_format($role->users_count) }}</div>
                                            <div class="text-xs text-gray-500">{{ __('admin.users_count') }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-6 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="text-lg font-bold text-gray-900">{{ number_format($role->permissions_count) }}</div>
                                            <div class="text-xs text-gray-500">{{ __('admin.permissions_count') }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-6 whitespace-nowrap">
                                    @if($role->is_system_role)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800 border border-blue-200">
                                            <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                            </svg>
                                            System
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-800 border border-gray-200">
                                            <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4"/>
                                            </svg>
                                            Custom
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-6 whitespace-nowrap">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $role->is_active ? 'bg-green-100 text-green-800 border border-green-200' : 'bg-sidebar-green-100 text-sidebar-green-800 border border-sidebar-green-200' }}">
                                        <div class="w-2 h-2 rounded-full mr-2 {{ $role->is_active ? 'bg-green-400' : 'bg-sidebar-green-400' }}"></div>
                                        {{ $role->is_active ? __('common.active') : __('common.inactive') }}
                                    </span>
                                </td>
                                <td class="px-6 py-6 whitespace-nowrap">
                                    <div class="flex items-center space-x-2">
                                        <button wire:click="openEditRoleModal('{{ $role->id }}')" 
                                                class="text-red-600 hover:text-red-700 p-2 rounded-xl hover:bg-red-50 transition-all duration-200" 
                                                title="{{ __('admin.update_role') }}">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </button>

                                        <button wire:click="confirmToggleRoleStatus('{{ $role->id }}')" 
                                                class="text-red-600 hover:text-red-700 p-2 rounded-xl hover:bg-red-50 transition-all duration-200" 
                                                title="{{ __('admin.change_status') }}">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-8 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                        </svg>
                                        <h3 class="text-lg font-medium text-gray-900 mb-2">No roles found</h3>
                                        <p class="text-gray-500 mb-4">Get started by creating your first role.</p>
                                        <button wire:click="openCreateRoleModal" class="bg-red-600 text-white px-6 py-2 rounded-xl font-semibold hover:bg-red-700 transition-all duration-200">
                                            Create First Role
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($roles->hasPages())
                <div class="bg-white px-8 py-4 border-t border-gray-200">
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                        <div class="text-sm text-gray-700">
                            <span class="font-medium">{{ $roles->firstItem() }}</span>
                            -
                            <span class="font-medium">{{ $roles->lastItem() }}</span>
                            of
                            <span class="font-medium">{{ $roles->total() }}</span>
                            roles
                        </div>
                        <div class="flex-1 flex justify-center sm:justify-end">
                            {{ $roles->links() }}
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Password Confirmation Modal -->
        @if($showPasswordConfirmModal)
            <div wire:key="password-confirm-modal-{{ $confirmRoleId }}" 
                 class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4" 
                 wire:click.self="closePasswordConfirmModal">
                <div class="relative mx-auto p-5 border w-11/12 max-w-md shadow-lg rounded-lg bg-white"
                     wire:click.stop>
                    
                    <!-- Modal Header -->
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.464 0L4.348 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-900">{{ $passwordConfirmTitle }}</h3>
                                <p class="text-sm text-gray-500">Security verification required</p>
                            </div>
                        </div>
                        <button wire:click="closePasswordConfirmModal" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <!-- Warning Message -->
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                        <div class="flex items-start space-x-3">
                            <svg class="w-5 h-5 text-red-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.464 0L4.348 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                            </svg>
                            <div>
                                <h4 class="text-sm font-semibold text-red-800">Critical Action Warning</h4>
                                <p class="text-sm text-red-700 mt-1">{{ $passwordConfirmMessage }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Password Form -->
                    <form wire:submit.prevent="executeConfirmedAction" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Enter your current password to confirm this action:
                            </label>
                            <input wire:model="currentPassword" 
                                   type="password" 
                                   placeholder="Your current password"
                                   class="w-full border border-gray-300 rounded-xl px-3 py-2 focus:ring-2 focus:ring-red-500 focus:border-red-500 @error('currentPassword') border-red-500 @enderror"
                                   autofocus>
                            @error('currentPassword') 
                                <span class="text-red-600 text-xs mt-1 block">{{ $message }}</span> 
                            @enderror
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex justify-end space-x-3 pt-4">
                            <button type="button" 
                                    wire:click="closePasswordConfirmModal" 
                                    class="bg-gray-100 text-gray-700 px-4 py-2 rounded-xl font-medium hover:bg-gray-200 transition-colors">
                                Cancel
                            </button>
                            <button type="submit" 
                                    class="bg-red-600 text-white px-4 py-2 rounded-xl font-semibold hover:bg-red-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                                    wire:loading.attr="disabled">
                                <span wire:loading.remove>Confirm Action</span>
                                <span wire:loading>Processing...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif

        <!-- Modals -->
        @include('livewire.admin.role-management.modals')
    </div>
