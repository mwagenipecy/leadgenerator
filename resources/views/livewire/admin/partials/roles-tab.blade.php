<div>
{{-- resources/views/livewire/admin/partials/roles-tab.blade.php --}}

<!-- Filters and Search -->
<div class="bg-white rounded-3xl shadow-sm p-6 border border-gray-100 mb-8">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- Search -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Search Roles</label>
            <div class="relative">
                <input wire:model.live="search" type="text" placeholder="Search by role name..." 
                       class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                <svg class="w-4 h-4 text-gray-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
        </div>

        <!-- Status Filter -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Filter by Status</label>
            <select wire:model.live="statusFilter" class="w-full border border-gray-300 rounded-xl px-3 py-2 focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                <option value="">All Statuses</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
        </div>

        <!-- Role Level Range -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Role Level Range</label>
            <select wire:model.live="levelFilter" class="w-full border border-gray-300 rounded-xl px-3 py-2 focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                <option value="">All Levels</option>
                <option value="high">High (80-100)</option>
                <option value="medium">Medium (50-79)</option>
                <option value="low">Low (1-49)</option>
            </select>
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
                <div class="flex items-center space-x-2">
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

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div class="text-center bg-gray-50 rounded-xl p-3">
                    <div class="text-2xl font-bold text-gray-900">{{ $role->users_count ?? $role->users->count() }}</div>
                    <div class="text-xs text-gray-500">Users</div>
                </div>
                <div class="text-center bg-gray-50 rounded-xl p-3">
                    <div class="text-2xl font-bold text-gray-900">{{ $role->permissions_count ?? $role->permissions->count() }}</div>
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
                    
                    <button wire:click="openManageRolePermissionsModal({{ $role->id }})" 
                        class="text-green-600 hover:text-green-700 p-2 rounded-xl hover:bg-green-50 transition-all duration-200" title="Manage Permissions">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                        </svg>
                    </button>

                    @if(!$role->is_system_role)
                        <button wire:click="deleteRole({{ $role->id }})" 
                            onclick="return confirm('Are you sure you want to delete this role? Users with this role will lose access.')"
                            class="text-red-600 hover:text-red-700 p-2 rounded-xl hover:bg-red-50 transition-all duration-200" title="Delete Role">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    @endif
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

<!-- Roles Table View (Alternative) -->
<div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden mt-8">
    <div class="px-8 py-6 border-b border-gray-100 bg-gradient-to-r from-white to-gray-50">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-2xl font-bold text-gray-900 mb-1">Roles Table View</h3>
                <p class="text-gray-600">Detailed view of all system roles</p>
            </div>
        </div>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-8 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Role</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Level</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Users</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Permissions</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @foreach($roles as $role)
                    <tr class="hover:bg-gray-50 transition-colors duration-200 group">
                        <td class="px-8 py-6 whitespace-nowrap">
                            <div class="flex items-center">
                                @php
                                    $levelColor = match(true) {
                                        $role->level >= 80 => 'from-red-500 to-red-600',
                                        $role->level >= 50 => 'from-purple-500 to-purple-600',
                                        default => 'from-blue-500 to-blue-600'
                                    };
                                @endphp
                                <div class="w-10 h-10 bg-gradient-to-br {{ $levelColor }} rounded-xl flex items-center justify-center">
                                    <span class="text-white text-xs font-bold">{{ substr($role->display_name, 0, 2) }}</span>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-bold text-gray-900 group-hover:text-purple-600 transition-colors">
                                        {{ $role->display_name }}
                                    </div>
                                    <div class="text-xs text-gray-500">{{ $role->name }}</div>
                                    @if($role->is_system_role)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800 mt-1">
                                            System Role
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-6 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="text-2xl font-bold text-gray-900">{{ $role->level }}</div>
                                <div class="ml-2">
                                    @if($role->level >= 80)
                                        <span class="text-xs text-red-600 font-medium">High</span>
                                    @elseif($role->level >= 50)
                                        <span class="text-xs text-purple-600 font-medium">Medium</span>
                                    @else
                                        <span class="text-xs text-blue-600 font-medium">Low</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-6 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">
                                {{ $role->users_count ?? $role->users->count() }} users
                            </div>
                        </td>
                        <td class="px-6 py-6 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">
                                {{ $role->permissions_count ?? $role->permissions->count() }} permissions
                            </div>
                        </td>
                        <td class="px-6 py-6 whitespace-nowrap">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                {{ $role->is_active ? 'bg-green-100 text-green-800 border border-green-200' : 'bg-red-100 text-red-800 border border-red-200' }}">
                                <div class="w-2 h-2 rounded-full mr-2 {{ $role->is_active ? 'bg-green-400' : 'bg-red-400' }}"></div>
                                {{ $role->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-6 py-6 whitespace-nowrap">
                            <div class="flex items-center space-x-2">
                                <button wire:click="openEditRoleModal({{ $role->id }})" 
                                    class="text-purple-600 hover:text-purple-700 p-2 rounded-xl hover:bg-purple-50 transition-all duration-200" title="Edit Role">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>
                                
                                <button wire:click="openManageRolePermissionsModal({{ $role->id }})" 
                                    class="text-green-600 hover:text-green-700 p-2 rounded-xl hover:bg-green-50 transition-all duration-200" title="Manage Permissions">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                                    </svg>
                                </button>

                                @if(!$role->is_system_role)
                                    <button wire:click="deleteRole({{ $role->id }})" 
                                        onclick="return confirm('Are you sure you want to delete this role?')"
                                        class="text-red-600 hover:text-red-700 p-2 rounded-xl hover:bg-red-50 transition-all duration-200" title="Delete Role">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
</div>
