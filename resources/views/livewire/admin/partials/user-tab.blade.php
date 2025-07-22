<div>
{{-- resources/views/livewire/admin/partials/users-tab.blade.php --}}

<!-- Filters and Search -->
<div class="bg-white rounded-3xl shadow-sm p-6 border border-gray-100 mb-8">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Search -->
        <div class="lg:col-span-1">
            <label class="block text-sm font-medium text-gray-700 mb-2">Search Users</label>
            <div class="relative">
                <input wire:model.live="search" type="text" placeholder="Search by name, email..." 
                       class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <svg class="w-4 h-4 text-gray-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
        </div>

        <!-- Role Filter -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Filter by Role</label>
            <select wire:model.live="roleFilter" class="w-full border border-gray-300 rounded-xl px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="">All Roles</option>
                <option value="super_admin">Super Admin</option>
                <option value="admin">Admin</option>
                <option value="lender">Lender</option>
                <option value="borrower">Borrower</option>
            </select>
        </div>

        <!-- Status Filter -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Filter by Status</label>
            <select wire:model.live="statusFilter" class="w-full border border-gray-300 rounded-xl px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="">All Statuses</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
        </div>

        <!-- Lender Filter -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Filter by Lender</label>
            <select wire:model.live="lenderFilter" class="w-full border border-gray-300 rounded-xl px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="">All Lenders</option>
                @foreach($availableLenders as $lender)
                    <option value="{{ $lender->id }}">{{ $lender->company_name }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>

<!-- Users Table -->
<div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-8 py-6 border-b border-gray-100 bg-gradient-to-r from-white to-gray-50">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-2xl font-bold text-gray-900 mb-1">System Users</h3>
                <p class="text-gray-600">Manage all users in the system</p>
            </div>
            <div class="text-sm text-gray-500">
                Showing {{ $users->count() }} of {{ $users->total() }} users
            </div>
        </div>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-8 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">User Information</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Roles & Status</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Lender Association</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Contact Info</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Created Date</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @forelse($users as $user)
                    <tr class="hover:bg-gray-50 transition-colors duration-200 group">
                        <td class="px-8 py-6 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="relative">
                                    @php
                                        $primaryRole = $user->roles->sortByDesc('level')->first();
                                        $roleColor = match($primaryRole?->name ?? 'borrower') {
                                            'super_admin' => 'from-red-500 to-red-600',
                                            'admin' => 'from-purple-500 to-purple-600',
                                            'lender' => 'from-orange-500 to-orange-600',
                                            default => 'from-green-500 to-green-600'
                                        };
                                    @endphp
                                    <div class="w-12 h-12 bg-gradient-to-br {{ $roleColor }} rounded-2xl flex items-center justify-center shadow-md">
                                        <span class="text-white text-sm font-bold">
                                            {{ substr($user->first_name ?: $user->name, 0, 1) }}{{ substr($user->last_name ?: '', 0, 1) }}
                                        </span>
                                    </div>
                                    @if($user->email_verified_at)
                                        <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-400 rounded-full border-2 border-white"></div>
                                    @endif
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-bold text-gray-900 group-hover:text-blue-600 transition-colors">
                                        {{ $user->first_name && $user->last_name ? $user->first_name . ' ' . $user->last_name : $user->name }}
                                    </div>
                                    <div class="text-xs text-gray-500">{{ $user->email }}</div>
                                    @if($user->nida_number)
                                        <div class="text-xs text-blue-600 font-medium mt-1">NIDA: {{ $user->nida_number }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-6 whitespace-nowrap">
                            <div class="flex flex-col space-y-2">
                                @foreach($user->roles->take(2) as $role)
                                    @php
                                        $roleStyle = match($role->name) {
                                            'super_admin' => 'bg-red-100 text-red-800 border-red-200',
                                            'admin' => 'bg-purple-100 text-purple-800 border-purple-200',
                                            'lender' => 'bg-orange-100 text-orange-800 border-orange-200',
                                            default => 'bg-green-100 text-green-800 border-green-200'
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold border {{ $roleStyle }}">
                                        <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                        </svg>
                                        {{ $role->display_name }}
                                    </span>
                                @endforeach
                                @if($user->roles->count() > 2)
                                    <span class="text-xs text-gray-500">+{{ $user->roles->count() - 2 }} more</span>
                                @endif
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                    {{ $user->is_active ? 'bg-green-100 text-green-800 border border-green-200' : 'bg-red-100 text-red-800 border border-red-200' }}">
                                    <div class="w-2 h-2 rounded-full mr-2 {{ $user->is_active ? 'bg-green-400' : 'bg-red-400' }}"></div>
                                    {{ $user->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-6 whitespace-nowrap">
                            @if($user->lender)
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center">
                                        <span class="text-white text-xs font-bold">{{ substr($user->lender->company_name, 0, 2) }}</span>
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-gray-900">{{ $user->lender->company_name }}</div>
                                        <div class="text-xs text-gray-500">{{ $user->lender->license_number ?: 'No License' }}</div>
                                    </div>
                                </div>
                            @else
                                <span class="text-sm text-gray-400 italic">No Lender Association</span>
                            @endif
                        </td>
                        <td class="px-6 py-6 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $user->phone ?: 'No phone' }}</div>
                            <div class="text-xs text-gray-500">{{ $user->email_verified_at ? 'Email Verified' : 'Email Unverified' }}</div>
                        </td>
                        <td class="px-6 py-6 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $user->created_at->format('M d, Y') }}</div>
                            <div class="text-xs text-gray-500">{{ $user->created_at->format('g:i A') }}</div>
                        </td>
                        <td class="px-6 py-6 whitespace-nowrap">
                            <div class="flex items-center space-x-2">
                                <button wire:click="openEditUserModal({{ $user->id }})" 
                                    class="text-blue-600 hover:text-blue-700 p-2 rounded-xl hover:bg-blue-50 transition-all duration-200" title="Edit User">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>
                                
                                <button wire:click="openManageUserRolesModal({{ $user->id }})" 
                                    class="text-purple-600 hover:text-purple-700 p-2 rounded-xl hover:bg-purple-50 transition-all duration-200" title="Manage Roles">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                    </svg>
                                </button>
                                
                                <button wire:click="toggleUserStatus({{ $user->id }})" 
                                    class="text-{{ $user->is_active ? 'yellow' : 'green' }}-600 hover:text-{{ $user->is_active ? 'yellow' : 'green' }}-700 p-2 rounded-xl hover:bg-{{ $user->is_active ? 'yellow' : 'green' }}-50 transition-all duration-200" title="{{ $user->is_active ? 'Deactivate' : 'Activate' }}">
                                    @if($user->is_active)
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9V6a4 4 0 118 0v3M5 12h14l-1 7H6l-1-7z"/>
                                        </svg>
                                    @else
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"/>
                                        </svg>
                                    @endif
                                </button>
                                
                                @if($user->id !== auth()->id())
                                    <button wire:click="deleteUser({{ $user->id }})" 
                                        onclick="return confirm('Are you sure you want to delete this user? This action cannot be undone.')"
                                        class="text-red-600 hover:text-red-700 p-2 rounded-xl hover:bg-red-50 transition-all duration-200" title="Delete User">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-8 py-12 text-center">
                            <div class="w-20 h-20 bg-gray-100 rounded-3xl flex items-center justify-center mx-auto mb-4">
                                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                                </svg>
                            </div>
                            <h4 class="text-lg font-semibold text-gray-900 mb-2">No Users Found</h4>
                            <p class="text-gray-500">No users match your current search criteria.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    @if($users->hasPages())
        <div class="px-8 py-4 border-t border-gray-100">
            {{ $users->links() }}
        </div>
    @endif
</div>
</div>
