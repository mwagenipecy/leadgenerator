<div>
{{-- resources/views/livewire/admin/role-management.blade.php --}}
<div>
    <div class="p-8">
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-4xl font-bold text-gray-900 mb-2">Role Management</h1>
                    <p class="text-gray-600 text-lg">Manage system roles and their permissions</p>
                </div>
                <div class="flex items-center space-x-3">

                        <button wire:click="openCreateRoleModal" class="bg-purple-600 text-white px-6 py-2 rounded-xl font-semibold hover:bg-purple-700 transition-all duration-200 shadow-lg shadow-purple-600/25">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Create Role
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
            <div class="bg-red-100 border border-red-400 text-red-700 px-6 py-4 rounded-3xl mb-6 flex items-center space-x-3">
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
                    <p class="text-sm font-medium text-gray-500">Total Roles</p>
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
                    <p class="text-sm font-medium text-gray-500">System Roles</p>
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
                    <p class="text-sm font-medium text-gray-500">Custom Roles</p>
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
                    <p class="text-sm font-medium text-gray-500">Active Roles</p>
                </div>
            </div>
        </div>

        <!-- Filters and Search -->
        <div class="bg-white rounded-3xl shadow-sm p-6 border border-gray-100 mb-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Search Roles</label>
                    <div class="relative">
                        <input wire:model.live="search" type="text" placeholder="Search by name or description..." 
                               class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                        <svg class="w-4 h-4 text-gray-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Filter by Type</label>
                    <select wire:model.live="statusFilter" class="w-full border border-gray-300 rounded-xl px-3 py-2 focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                        <option value="">All Types</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                        <option value="system">System Roles</option>
                        <option value="custom">Custom Roles</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Filter by Level</label>
                    <select wire:model.live="levelFilter" class="w-full border border-gray-300 rounded-xl px-3 py-2 focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                        <option value="">All Levels</option>
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
                        Refresh
                    </button>
                </div>
            </div>
        </div>

        <!-- Roles Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            @forelse($roles as $role)


 


                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-all duration-300 group">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center">
                            @php
                                $levelColor = match(true) {
                                    $role->level >= 80 => 'from-red-500 to-red-600',
                                    $role->level >= 50 => 'from-purple-500 to-purple-600',
                                    default => 'from-blue-500 to-blue-600'
                                };
                            @endphp



                            <div class="w-12 h-12 bg-gradient-to-br {{ $levelColor }} rounded-2xl flex items-center justify-center">
                                <span class="text-white text-sm font-bold">{{ $role->level }}</span>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-bold text-gray-900 group-hover:text-purple-600 transition-colors">
                                    {{ $role->display_name }}
                                </h3>
                                <p class="text-sm text-gray-500">{{ $role->name }}</p>
                            </div>
                        </div>





                        <div class="flex flex-col items-end space-y-2">
                            @if($role->is_system_role)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                    </svg>
                                    System
                                </span>
                            @endif
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $role->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                <div class="w-2 h-2 rounded-full mr-1 {{ $role->is_active ? 'bg-green-400' : 'bg-red-400' }}"></div>
                                {{ $role->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    </div>

                    @if($role->description)
                        <p class="text-sm text-gray-600 mb-4 line-clamp-2">{{ $role->description }}</p>
                    @endif



                  <!-- Replace the grid section in your blade template with this fixed version -->
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div class="text-center bg-gray-50 rounded-xl p-3">
                            <div class="text-2xl font-bold text-gray-900">{{ $role->users_count }}</div>
                            <div class="text-xs text-gray-500">Users</div>
                        </div>
                        <div class="text-center bg-gray-50 rounded-xl p-3">
                            <div class="text-2xl font-bold text-gray-900">{{ $role->permissions_count }}</div>
                            <div class="text-xs text-gray-500">Permissions</div>
                        </div>
                    </div>


 

                    <!-- Role Actions -->
                    <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                        <div class="flex items-center space-x-2">

                     
                                   
                                <button wire:click="openEditRoleModal({{ $role->id }})" 
                                        class="text-purple-600 hover:text-purple-700 p-2 rounded-xl hover:bg-purple-50 transition-all duration-200" title="Edit Role">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                    
                                    <button wire:click="openManagePermissionsModal({{ $role->id }})" 
                                        class="text-green-600 hover:text-green-700 p-2 rounded-xl hover:bg-green-50 transition-all duration-200" title="Manage Permissions">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1721 9z"/>
                                        </svg>
                                    </button>

                                    <button wire:click="toggleRoleStatus({{ $role->id }})" 
                                        class="text-{{ $role->is_active ? 'yellow' : 'green' }}-600 hover:text-{{ $role->is_active ? 'yellow' : 'green' }}-700 p-2 rounded-xl hover:bg-{{ $role->is_active ? 'yellow' : 'green' }}-50 transition-all duration-200" 
                                        title="{{ $role->is_active ? 'Deactivate' : 'Activate' }} Role">
                                        @if($role->is_active)
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728"/>
                                            </svg>
                                        @else
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        @endif
                                    </button>
                          

                                <button wire:click="openRoleUsersModal({{ $role->id }})" 
                                    class="text-blue-600 hover:text-blue-700 p-2 rounded-xl hover:bg-blue-50 transition-all duration-200" title="View Users">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                                    </svg>
                                </button>

                                    <button wire:click="confirmDeleteRole({{ $role->id }})" 
                                        class="text-red-600 hover:text-red-700 p-2 rounded-xl hover:bg-red-50 transition-all duration-200" title="Delete Role">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                             
                        </div>


      
                        
                        <div class="text-xs text-gray-500">
                            Level {{ $role->level }}
                        </div>
                    </div>
                </div>


   
            @empty
                <div class="col-span-full">
                    <div class="text-center py-12">
                        <div class="w-20 h-20 bg-gray-100 rounded-3xl flex items-center justify-center mx-auto mb-4">
                            <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                        <h4 class="text-lg font-semibold text-gray-900 mb-2">No Roles Found</h4>
                        <p class="text-gray-500">No roles match your current search criteria.</p>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($roles->hasPages())
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 px-8 py-4">
                {{ $roles->links() }}
            </div>
        @endif

    </div>


  
    <!-- Modals -->

    @include('livewire.admin.role-management.modals')

</div>

</div>
