<div>
{{-- resources/views/livewire/admin/permission-management.blade.php --}}
<div>
    <div class="p-8">
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-4xl font-bold text-gray-900 mb-2">Permission Management</h1>
                    <p class="text-gray-600 text-lg">Manage system permissions and access controls</p>
                </div>
                <div class="flex items-center space-x-3">


                        <button wire:click="openCreatePermissionModal" class="bg-green-600 text-white px-6 py-2 rounded-xl font-semibold hover:bg-green-700 transition-all duration-200 shadow-lg shadow-green-600/25">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900 group-hover:text-blue-600 transition-colors">{{ number_format($totalActivePermissions) }}</p>
                    <p class="text-sm font-medium text-gray-500">Active Permissions</p>
                </div>
            </div>

            <div class="bg-white rounded-3xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-all duration-300 group hover:border-purple-500/20">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-purple-100 rounded-2xl flex items-center justify-center group-hover:bg-purple-200 transition-colors">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                    </div>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900 group-hover:text-purple-600 transition-colors">{{ number_format($totalCategories) }}</p>
                    <p class="text-sm font-medium text-gray-500">Categories</p>
                </div>
            </div>
        </div>

        <!-- Filters and Search -->
        <div class="bg-white rounded-3xl shadow-sm p-6 border border-gray-100 mb-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Search Permissions</label>
                    <div class="relative">
                        <input wire:model.live="search" type="text" placeholder="Search by name or description..." 
                               class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        <svg class="w-4 h-4 text-gray-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Filter by Category</label>
                    <select wire:model.live="categoryFilter" class="w-full border border-gray-300 rounded-xl px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        <option value="">All Categories</option>
                        @foreach($categories as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Filter by Status</label>
                    <select wire:model.live="statusFilter" class="w-full border border-gray-300 rounded-xl px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        <option value="">All Statuses</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
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

        <!-- Permissions by Category -->
        @if(count($permissionsByCategory) > 0)
            <div class="space-y-8 mb-8">
                @foreach($permissionsByCategory as $category => $categoryPermissions)
                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="px-8 py-6 border-b border-gray-100 bg-gradient-to-r from-white to-gray-50">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    @php
                                        $categoryColors = [
                                            'users' => 'from-blue-500 to-blue-600',
                                            'roles' => 'from-purple-500 to-purple-600',
                                            'applications' => 'from-green-500 to-green-600',
                                            'lenders' => 'from-orange-500 to-orange-600',
                                            'products' => 'from-pink-500 to-pink-600',
                                            'reports' => 'from-indigo-500 to-indigo-600',
                                            'system' => 'from-red-500 to-red-600',
                                            'financial' => 'from-yellow-500 to-yellow-600',
                                            'general' => 'from-gray-500 to-gray-600'
                                        ];
                                        $categoryColor = $categoryColors[$category] ?? 'from-gray-500 to-gray-600';
                                    @endphp
                                    <div class="w-12 h-12 bg-gradient-to-br {{ $categoryColor }} rounded-2xl flex items-center justify-center mr-4">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1721 9z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-2xl font-bold text-gray-900 mb-1 capitalize">
                                            {{ $categories[$category] ?? str_replace('_', ' ', $category) }}
                                        </h3>
                                        <p class="text-gray-600">{{ count($categoryPermissions) }} permissions in this category</p>
                                    </div>
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ count($categoryPermissions) }} total
                                </div>
                            </div>
                        </div>

                        <div class="p-8">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach($categoryPermissions as $permission)
                                    <div class="bg-gray-50 rounded-2xl p-4 hover:bg-gray-100 transition-colors duration-200 group">
                                        <div class="flex items-start justify-between mb-3">
                                            <div class="flex-1">
                                                <h4 class="text-sm font-semibold text-gray-900 mb-1 group-hover:text-green-600 transition-colors">
                                                    {{ $permission->display_name }}
                                                </h4>
                                                <p class="text-xs text-gray-500 font-mono bg-white px-2 py-1 rounded">
                                                    {{ $permission->name }}
                                                </p>
                                            </div>
                                            <div class="flex items-center space-x-2">
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                                    {{ $permission->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                    {{ $permission->is_active ? 'Active' : 'Inactive' }}
                                                </span>
                                                
                                                @role('super_admin')
                                                    <div class="flex space-x-1">
                                                        <button wire:click="openEditPermissionModal({{ $permission->id }})" 
                                                            class="text-blue-600 hover:text-blue-700 p-1 rounded hover:bg-blue-50 transition-all duration-200" title="Edit">
                                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                            </svg>
                                                        </button>
                                                        <button wire:click="togglePermissionStatus({{ $permission->id }})" 
                                                            class="text-{{ $permission->is_active ? 'yellow' : 'green' }}-600 hover:text-{{ $permission->is_active ? 'yellow' : 'green' }}-700 p-1 rounded hover:bg-{{ $permission->is_active ? 'yellow' : 'green' }}-50 transition-all duration-200" 
                                                            title="{{ $permission->is_active ? 'Deactivate' : 'Activate' }}">
                                                            @if($permission->is_active)
                                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728"/>
                                                                </svg>
                                                            @else
                                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                                </svg>
                                                            @endif
                                                        </button>
                                                    </div>
                                                @endrole
                                            </div>
                                        </div>
                                        
                                        @if($permission->description)
                                            <p class="text-xs text-gray-600 mb-3 line-clamp-2">{{ $permission->description }}</p>
                                        @endif

                                        <div class="flex items-center justify-between pt-3 border-t border-gray-200">
                                            <div class="text-xs text-gray-500">
                                                {{ $permission->roles->count() }} role(s)
                                            </div>
                                            <div class="flex items-center space-x-1">
                                                @if($permission->roles->count() > 0)
                                                    <button wire:click="openPermissionRolesModal({{ $permission->id }})" 
                                                        class="text-purple-600 hover:text-purple-700 text-xs hover:underline">
                                                        View Roles
                                                    </button>
                                                @else
                                                    <span class="text-xs text-gray-400 italic">No roles</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <!-- All Permissions Table -->
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-8 py-6 border-b border-gray-100 bg-gradient-to-r from-white to-gray-50">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-1">All Permissions</h3>
                        <p class="text-gray-600">Complete list of system permissions</p>
                    </div>
                    <div class="text-sm text-gray-500">
                        Showing {{ $permissions->count() }} of {{ $permissions->total() }} permissions
                    </div>
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-8 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Permission</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Category</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Roles</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($permissions as $permission)
                            <tr class="hover:bg-gray-50 transition-colors duration-200 group">
                                <td class="px-8 py-6 whitespace-nowrap">
                                    <div class="flex items-center">
                                        @php
                                            $categoryColors = [
                                                'users' => 'from-blue-500 to-blue-600',
                                                'roles' => 'from-purple-500 to-purple-600',
                                                'applications' => 'from-green-500 to-green-600',
                                                'lenders' => 'from-orange-500 to-orange-600',
                                                'products' => 'from-pink-500 to-pink-600',
                                                'reports' => 'from-indigo-500 to-indigo-600',
                                                'system' => 'from-red-500 to-red-600',
                                                'financial' => 'from-yellow-500 to-yellow-600',
                                                'general' => 'from-gray-500 to-gray-600'
                                            ];
                                            $categoryColor = $categoryColors[$permission->category] ?? 'from-gray-500 to-gray-600';
                                        @endphp
                                        <div class="w-10 h-10 bg-gradient-to-br {{ $categoryColor }} rounded-xl flex items-center justify-center">
                                            <span class="text-white text-xs font-bold">{{ substr($permission->display_name, 0, 2) }}</span>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-bold text-gray-900 group-hover:text-green-600 transition-colors">
                                                {{ $permission->display_name }}
                                            </div>
                                            <div class="text-xs text-gray-500 font-mono">{{ $permission->name }}</div>
                                            @if($permission->description)
                                                <div class="text-xs text-gray-600 mt-1 max-w-xs truncate">{{ $permission->description }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-6 whitespace-nowrap">
                                    @php
                                        $categoryStyle = match($permission->category) {
                                            'users' => 'bg-blue-100 text-blue-800',
                                            'roles' => 'bg-purple-100 text-purple-800',
                                            'applications' => 'bg-green-100 text-green-800',
                                            'lenders' => 'bg-orange-100 text-orange-800',
                                            'products' => 'bg-pink-100 text-pink-800',
                                            'reports' => 'bg-indigo-100 text-indigo-800',
                                            'system' => 'bg-red-100 text-red-800',
                                            'financial' => 'bg-yellow-100 text-yellow-800',
                                            default => 'bg-gray-100 text-gray-800'
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $categoryStyle }}">
                                        {{ $categories[$permission->category] ?? ucfirst(str_replace('_', ' ', $permission->category)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-6 whitespace-nowrap">
                                    <div class="flex flex-wrap gap-1">
                                        @forelse($permission->roles->take(3) as $role)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-100 text-purple-800">
                                                {{ $role->display_name }}
                                            </span>
                                        @empty
                                            <span class="text-xs text-gray-400 italic">No roles</span>
                                        @endforelse
                                        @if($permission->roles->count() > 3)
                                            <button wire:click="openPermissionRolesModal({{ $permission->id }})" class="text-xs text-purple-600 hover:underline">
                                                +{{ $permission->roles->count() - 3 }} more
                                            </button>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-6 whitespace-nowrap">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                        {{ $permission->is_active ? 'bg-green-100 text-green-800 border border-green-200' : 'bg-red-100 text-red-800 border border-red-200' }}">
                                        <div class="w-2 h-2 rounded-full mr-2 {{ $permission->is_active ? 'bg-green-400' : 'bg-red-400' }}"></div>
                                        {{ $permission->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="px-6 py-6 whitespace-nowrap">
                                    <div class="flex items-center space-x-2">
                                        @role('super_admin')
                                            <button wire:click="openEditPermissionModal({{ $permission->id }})" 
                                                class="text-blue-600 hover:text-blue-700 p-2 rounded-xl hover:bg-blue-50 transition-all duration-200" title="Edit Permission">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                            </button>

                                            <button wire:click="togglePermissionStatus({{ $permission->id }})" 
                                                class="text-{{ $permission->is_active ? 'yellow' : 'green' }}-600 hover:text-{{ $permission->is_active ? 'yellow' : 'green' }}-700 p-2 rounded-xl hover:bg-{{ $permission->is_active ? 'yellow' : 'green' }}-50 transition-all duration-200" 
                                                title="{{ $permission->is_active ? 'Deactivate' : 'Activate' }} Permission">
                                                @if($permission->is_active)
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728"/>
                                                    </svg>
                                                @else
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                @endif
                                            </button>

                                            <button wire:click="confirmDeletePermission({{ $permission->id }})" 
                                                class="text-red-600 hover:text-red-700 p-2 rounded-xl hover:bg-red-50 transition-all duration-200" title="Delete Permission">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        @endrole

                                        <button wire:click="openPermissionRolesModal({{ $permission->id }})" 
                                            class="text-purple-600 hover:text-purple-700 p-2 rounded-xl hover:bg-purple-50 transition-all duration-200" title="View Roles">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-8 py-12 text-center">
                                    <div class="w-20 h-20 bg-gray-100 rounded-3xl flex items-center justify-center mx-auto mb-4">
                                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1721 9z"/>
                                        </svg>
                                    </div>
                                    <h4 class="text-lg font-semibold text-gray-900 mb-2">No Permissions Found</h4>
                                    <p class="text-gray-500">No permissions match your current search criteria.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($permissions->hasPages())
                <div class="px-8 py-4 border-t border-gray-100">
                    {{ $permissions->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Modals -->
    @include('livewire.admin.permission-management.modals')
</div>
                        
                        
                        
                        </div>
