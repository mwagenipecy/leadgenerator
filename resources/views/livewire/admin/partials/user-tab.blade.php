<div>
    <!-- Flash Messages -->
    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-6 py-4 rounded-lg mb-6 flex items-center space-x-3" 
             x-data="{ show: true }" x-show="show" x-transition 
             x-init="setTimeout(() => show = false, 5000)">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            <span>{{ session('message') }}</span>
            <button @click="show = false" class="ml-auto">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="bg-sidebar-green-100 border border-sidebar-green-400 text-sidebar-green-light px-6 py-4 rounded-lg mb-6 flex items-center space-x-3"
             x-data="{ show: true }" x-show="show" x-transition 
             x-init="setTimeout(() => show = false, 8000)">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
            <span>{{ session('error') }}</span>
            <button @click="show = false" class="ml-auto">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    @endif

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <!-- Total Users Card -->
        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100 hover:shadow-md transition-all duration-300 group hover:border-sidebar-green/20">
            <div class="flex items-center justify-between mb-4">
                <div class="w-14 h-14 bg-sidebar-green-100 rounded-2xl flex items-center justify-center group-hover:bg-sidebar-green-200 transition-colors">
                    <svg class="w-7 h-7 text-sidebar-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                    </svg>
                </div>
                <div class="text-right">
                    <p class="text-sm font-medium text-gray-500">Total Users</p>
                    <p class="text-lg font-bold text-gray-900 group-hover:text-sidebar-green transition-colors">{{ number_format($totalUsers) }}</p>
                </div>
            </div>
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-1 text-sidebar-green">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                    <span class="text-sm font-semibold">All Roles</span>
                </div>
                <span class="text-sm text-gray-500">active system</span>
            </div>
        </div>

        <!-- Total Lenders Card -->
        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100 hover:shadow-md transition-all duration-300 group hover:border-sidebar-green/20">
            <div class="flex items-center justify-between mb-4">
                <div class="w-14 h-14 bg-sidebar-green-100 rounded-2xl flex items-center justify-center group-hover:bg-sidebar-green-200 transition-colors">
                    <svg class="w-7 h-7 text-sidebar-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <div class="text-right">
                    <p class="text-sm font-medium text-gray-500">Lender Users</p>
                    <p class="text-lg font-bold text-gray-900 group-hover:text-sidebar-green transition-colors">{{ number_format($totalLenders) }}</p>
                </div>
            </div>
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-1 text-sidebar-green">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    <span class="text-sm font-semibold">Lending Partners</span>
                </div>
                <span class="text-sm text-gray-500">institutions</span>
            </div>
        </div>

        <!-- Total Borrowers Card -->
        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100 hover:shadow-md transition-all duration-300 group hover:border-sidebar-green/20">
            <div class="flex items-center justify-between mb-4">
                <div class="w-14 h-14 bg-sidebar-green-100 rounded-2xl flex items-center justify-center group-hover:bg-sidebar-green-200 transition-colors">
                    <svg class="w-7 h-7 text-sidebar-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <div class="text-right">
                    <p class="text-sm font-medium text-gray-500">Borrower Users</p>
                    <p class="text-lg font-bold text-gray-900 group-hover:text-sidebar-green transition-colors">{{ number_format($totalBorrowers) }}</p>
                </div>
            </div>
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-1 text-sidebar-green">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                    <span class="text-sm font-semibold">Active Customers</span>
                </div>
                <span class="text-sm text-gray-500">loan seekers</span>
            </div>
        </div>
    </div>

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
                @foreach($roles as $role)
                    <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                @endforeach
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
                                        // Use the role column directly to avoid relationship conflicts
                                        $roleName = $user->role ?? 'user';
                                        $roleColor = match(strtolower($roleName)) {
                                            'super_admin', 'super admin' => 'from-sidebar-green to-sidebar-green',
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
                                @php
                                    // Use the role column directly to avoid relationship conflicts
                                    $roleName = $user->role ?? 'user';
                                    $roleStyle = match(strtolower($roleName)) {
                                        'super_admin', 'super admin' => 'bg-sidebar-green-100 text-sidebar-green-800 border-sidebar-green-200',
                                        'admin' => 'bg-purple-100 text-purple-800 border-purple-200',
                                        'lender' => 'bg-orange-100 text-orange-800 border-orange-200',
                                        default => 'bg-green-100 text-green-800 border-green-200'
                                    };
                                @endphp
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold border {{ $roleStyle }}">
                                    <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                    </svg>
                                    {{ ucfirst(str_replace('_', ' ', $roleName)) }}
                                </span>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                    {{ $user->is_active ? 'bg-green-100 text-green-800 border border-green-200' : 'bg-sidebar-green-100 text-sidebar-green-800 border border-sidebar-green-200' }}">
                                    <div class="w-2 h-2 rounded-full mr-2 {{ $user->is_active ? 'bg-green-400' : 'bg-sidebar-green-400' }}"></div>
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
                                        class="text-sidebar-green hover:text-sidebar-green-light p-2 rounded-xl hover:bg-sidebar-green-50 transition-all duration-200" title="Delete User">
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
