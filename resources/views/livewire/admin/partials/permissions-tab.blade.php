<div>
{{-- resources/views/livewire/admin/partials/permissions-tab.blade.php --}}

<!-- Filters and Search -->
<div class="bg-white rounded-3xl shadow-sm p-6 border border-gray-100 mb-8">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- Search -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Search Permissions</label>
            <div class="relative">
                <input wire:model.live="search" type="text" placeholder="Search by permission name..." 
                       class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500">
                <svg class="w-4 h-4 text-gray-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
        </div>

        <!-- Category Filter -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Filter by Category</label>
            <select wire:model.live="categoryFilter" class="w-full border border-gray-300 rounded-xl px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                <option value="">All Categories</option>
                @if(isset($permissionsByCategory))
                    @foreach(array_keys($permissionsByCategory) as $category)
                        <option value="{{ $category }}">{{ ucfirst(str_replace('_', ' ', $category)) }}</option>
                    @endforeach
                @endif
            </select>
        </div>

        <!-- Status Filter -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Filter by Status</label>
            <select wire:model.live="statusFilter" class="w-full border border-gray-300 rounded-xl px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                <option value="">All Statuses</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
        </div>
    </div>
</div>

<!-- Permissions by Category -->
@if(isset($permissionsByCategory))
    <div class="space-y-8">
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
                                    @if($category === 'users')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                                    @elseif($category === 'roles')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                    @elseif($category === 'applications')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    @elseif($category === 'lenders')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    @elseif($category === 'system')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    @elseif($category === 'financial')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    @else
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                                    @endif
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold text-gray-900 mb-1 capitalize">
                                    {{ str_replace('_', ' ', $category) }} Permissions
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
                                            {{ $permission['display_name'] }}
                                        </h4>
                                        <p class="text-xs text-gray-500 font-mono bg-white px-2 py-1 rounded">
                                            {{ $permission['name'] }}
                                        </p>
                                    </div>
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                        {{ $permission['is_active'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $permission['is_active'] ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>
                                
                                @if($permission['description'])
                                    <p class="text-xs text-gray-600 mb-3 line-clamp-2">{{ $permission['description'] }}</p>
                                @endif

                                <div class="flex items-center justify-between pt-3 border-t border-gray-200">
                                    <div class="text-xs text-gray-500">
                                        Used in {{ count($permission['roles'] ?? []) }} roles
                                    </div>
                                    <div class="flex items-center space-x-1">
                                        @if(isset($permission['roles']) && count($permission['roles']) > 0)
                                            @foreach(array_slice($permission['roles'], 0, 3) as $role)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-100 text-purple-800">
                                                    {{ $role['display_name'] }}
                                                </span>
                                            @endforeach
                                            @if(count($permission['roles']) > 3)
                                                <span class="text-xs text-gray-500">+{{ count($permission['roles']) - 3 }}</span>
                                            @endif
                                        @else
                                            <span class="text-xs text-gray-400 italic">No roles assigned</span>
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

<!-- Permissions Table View -->
<div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden mt-8">
    <div class="px-8 py-6 border-b border-gray-100 bg-gradient-to-r from-white to-gray-50">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-2xl font-bold text-gray-900 mb-1">All Permissions</h3>
                <p class="text-gray-600">Complete list of system permissions</p>
            </div>
            @if(isset($permissions))
                <div class="text-sm text-gray-500">
                    Showing {{ $permissions->count() }} of {{ $permissions->total() }} permissions
                </div>
            @endif
        </div>
    </div>
    
    @if(isset($permissions))
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-8 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Permission</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Category</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Roles</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Usage</th>
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
                                    {{ ucfirst(str_replace('_', ' ', $permission->category)) }}
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
                                        <span class="text-xs text-gray-500">+{{ $permission->roles->count() - 3 }} more</span>
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
                                <div class="text-sm text-gray-900">
                                    {{ $permission->roles->count() }} role{{ $permission->roles->count() !== 1 ? 's' : '' }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    {{ $permission->roles->sum(function($role) { return $role->users->count(); }) }} users affected
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-8 py-12 text-center">
                                <div class="w-20 h-20 bg-gray-100 rounded-3xl flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
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
    @endif
</div>

<!-- Permission Statistics -->
<div class="mt-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
    @if(isset($permissionsByCategory))
        @foreach($permissionsByCategory as $category => $categoryPermissions)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                @php
                    $categoryColors = [
                        'users' => 'text-blue-600 bg-blue-100',
                        'roles' => 'text-purple-600 bg-purple-100',
                        'applications' => 'text-green-600 bg-green-100',
                        'lenders' => 'text-orange-600 bg-orange-100',
                        'products' => 'text-pink-600 bg-pink-100',
                        'reports' => 'text-indigo-600 bg-indigo-100',
                        'system' => 'text-red-600 bg-red-100',
                        'financial' => 'text-yellow-600 bg-yellow-100',
                        'general' => 'text-gray-600 bg-gray-100'
                    ];
                    $categoryColor = $categoryColors[$category] ?? 'text-gray-600 bg-gray-100';
                @endphp
                <div class="flex items-center">
                    <div class="w-10 h-10 {{ $categoryColor }} rounded-xl flex items-center justify-center">
                        <span class="text-sm font-bold">{{ count($categoryPermissions) }}</span>
                    </div>
                    <div class="ml-3">
                        <div class="text-sm font-medium text-gray-900 capitalize">{{ str_replace('_', ' ', $category) }}</div>
                        <div class="text-xs text-gray-500">Permissions</div>
                    </div>
                </div>
            </div>
        @endforeach
    @endif
</div>

</div>
