<div>
<div class="w-full">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-4xl font-bold text-gray-900 mb-2">Lender Management</h1>
                <p class="text-gray-600 text-lg">Manage lender onboarding and verification</p>
            </div>
            <div class="flex items-center space-x-3">
                <div class="flex items-center space-x-2 bg-sidebar-green-50 px-4 py-2 rounded-full">
                    <div class="w-2 h-2 bg-sidebar-green-400 rounded-full animate-pulse"></div>
                    <span class="text-sm font-medium text-sidebar-green-light">{{ $stats['pending'] }} Pending Review</span>
                </div>
                <button wire:click="showAddLenderForm" class="bg-sidebar-green text-white px-6 py-2 rounded-lg font-semibold hover:bg-sidebar-green-light transition-all duration-200 shadow-lg shadow-sidebar-green/25">
                    + Add Lender
                </button>
            </div>
        </div>
    </div>

    <!-- Flash Messages -->
    @if (session()->has('message'))
        <div class="mb-6 bg-sidebar-green-50 border border-sidebar-green-200 text-sidebar-green-light px-4 py-3 rounded-xl" role="alert"
             x-data="{ show: true }" x-show="show" x-transition 
             x-init="setTimeout(() => show = false, 5000)">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                {{ session('message') }}
                <button @click="show = false" class="ml-auto">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-6 bg-sidebar-green-100 border border-sidebar-green-300 text-sidebar-green-800 px-4 py-3 rounded-xl" role="alert"
             x-data="{ show: true }" x-show="show" x-transition 
             x-init="setTimeout(() => show = false, 8000)">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                {{ session('error') }}
                <button @click="show = false" class="ml-auto">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Lenders -->
        <div class="bg-white rounded-lg p-6 border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300 group">
            <div class="flex items-center justify-between">
                <div class="w-12 h-12 bg-gradient-to-br from-sidebar-green to-sidebar-green rounded-lg flex items-center justify-center shadow-md group-hover:scale-105 transition-transform duration-300">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <div class="text-right">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Total</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</p>
                </div>
            </div>
        </div>

        <!-- Pending -->
        <div class="bg-white rounded-xl p-6 border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300 group">
            <div class="flex items-center justify-between">
                <div class="w-12 h-12 bg-gradient-to-br from-sidebar-green-400 to-sidebar-green rounded-lg flex items-center justify-center shadow-md group-hover:scale-105 transition-transform duration-300">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="text-right">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Pending</p>
                    <p class="text-2xl font-bold text-sidebar-green">{{ $stats['pending'] }}</p>
                </div>
            </div>
        </div>

        <!-- Approved -->
        <div class="bg-white rounded-xl p-6 border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300 group">
            <div class="flex items-center justify-between">
                <div class="w-12 h-12 bg-gradient-to-br from-gray-600 to-gray-700 rounded-lg flex items-center justify-center shadow-md group-hover:scale-105 transition-transform duration-300">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="text-right">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Approved</p>
                    <p class="text-2xl font-bold text-gray-700">{{ $stats['approved'] }}</p>
                </div>
            </div>
        </div>

        <!-- Rejected/Suspended -->
        <div class="bg-white rounded-xl p-6 border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300 group">
            <div class="flex items-center justify-between">
                <div class="w-12 h-12 bg-gradient-to-br from-gray-800 to-black rounded-lg flex items-center justify-center shadow-md group-hover:scale-105 transition-transform duration-300">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </div>
                <div class="text-right">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Rejected</p>
                    <p class="text-2xl font-bold text-black">{{ $stats['rejected'] + $stats['suspended'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100 mb-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
            <!-- Search -->
            <div class="lg:col-span-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Search Lenders</label>
                <div class="relative">
                    <input wire:model.live="search" type="text" placeholder="Search by name, email..." 
                           class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green">
                    <svg class="w-4 h-4 text-gray-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
            </div>

            <!-- Status Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Filter by Status</label>
                <select wire:model.live="statusFilter" class="w-full border border-gray-300 rounded-xl px-3 py-2 focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green">
                    <option value="">All Statuses</option>
                    @foreach ($lender_status as $status)
                        <option value="{{ $status }}">{{ ucfirst($status) }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <!-- Lenders Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-8 py-6 border-b border-gray-100 bg-gradient-to-r from-white to-gray-50">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-1">Lenders</h3>
                    <p class="text-gray-600">Manage lender applications and status</p>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-8 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Company</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Contact</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Location</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Documents</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Created/Updated</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($lenders as $lender)
                        <tr class="hover:bg-gray-50 transition-colors duration-200 group">
                            <td class="px-8 py-6 whitespace-nowrap">
                                <div class="flex items-center">
                                    @if($lender->icon)
                                        <div class="w-12 h-12 rounded-2xl flex items-center justify-center shadow-md overflow-hidden">
                                            <img src="{{ asset('storage/' . $lender->icon) }}" alt="{{ $lender->company_name }}" class="w-full h-full object-cover">
                                        </div>
                                    @else
                                        <div class="w-12 h-12 bg-gradient-to-br from-sidebar-green to-sidebar-green rounded-2xl flex items-center justify-center shadow-md">
                                            <span class="text-white text-sm font-bold">{{ substr($lender->company_name, 0, 2) }}</span>
                                        </div>
                                    @endif
                                    <div class="ml-4">
                                        <a href="{{ route('lender.dashboard',$lender) }}" class="text-sm font-bold text-gray-900 group-hover:text-sidebar-green transition-colors">{{ $lender->company_name }}</a>
                                        <div class="text-xs text-gray-500">{{ $lender->license_number ?? 'No License' }}</div>
                                        <div class="text-xs text-sidebar-green font-medium mt-1">{{ $lender->contact_person }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-6 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $lender->email }}</div>
                                <div class="text-sm text-gray-500">{{ $lender->phone }}</div>
                                @if($lender->website)
                                    <div class="text-xs text-sidebar-green mt-1">
                                        <a href="{{ $lender->website }}" target="_blank" class="hover:underline">Website</a>
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-6 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $lender->city }}</div>
                                <div class="text-sm text-gray-500">{{ $lender->region }}</div>
                                @if($lender->postal_code)
                                    <div class="text-xs text-gray-400">{{ $lender->postal_code }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-6 whitespace-nowrap">
                                @if($lender->status === 'pending')
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-sidebar-green-100 text-sidebar-green-800 border border-sidebar-green-200">
                                        <svg class="w-3 h-3 mr-1.5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        Pending
                                    </span>
                                @elseif($lender->status === 'approved')
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-800 border border-gray-200">
                                        <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        Approved
                                    </span>
                                @elseif($lender->status === 'rejected')
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-black text-white border border-black">
                                        <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                        Rejected
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-gray-200 text-gray-900 border border-gray-300">
                                        <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636"/>
                                        </svg>
                                        Suspended
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-6 whitespace-nowrap">
                                <div class="flex items-center space-x-1">
                                    @if($lender->documents && count($lender->documents) > 0)
                                        <span class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-medium bg-sidebar-green-100 text-sidebar-green-800">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                            {{ count($lender->documents) }} docs
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-medium bg-gray-100 text-gray-800">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.664-.833-2.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                            </svg>
                                            No docs
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-6 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    <div class="flex items-center space-x-1 mb-1">
                                        <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span>Created: {{ $lender->created_at->format('M d, Y') }}</span>
                                    </div>
                                    @if($lender->createdBy)
                                        <div class="text-xs text-gray-500">By: {{ $lender->createdBy->name ?? $lender->createdBy->email ?? 'N/A' }}</div>
                                    @else
                                        <div class="text-xs text-gray-400">By: System</div>
                                    @endif
                                    @if($lender->updated_at && $lender->updated_at != $lender->created_at)
                                        <div class="flex items-center space-x-1 mt-1">
                                            <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                            </svg>
                                            <span class="text-xs text-gray-500">Updated: {{ $lender->updated_at->format('M d, Y') }}</span>
                                        </div>
                                        @if($lender->updatedBy)
                                            <div class="text-xs text-gray-500">By: {{ $lender->updatedBy->name ?? $lender->updatedBy->email ?? 'N/A' }}</div>
                                        @else
                                            <div class="text-xs text-gray-400">By: System</div>
                                        @endif
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-6 whitespace-nowrap text-right">
                                <div class="flex items-center justify-end space-x-2">
                                    <!-- View Button -->
                                    <a href="{{ route('lenders.view', $lender->id) }}" 
                                            class="text-gray-600 hover:text-gray-700 p-2 rounded-xl hover:bg-gray-50 transition-all duration-200 group relative" 
                                            title="View Details">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        <span class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 text-xs text-white bg-gray-900 rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap pointer-events-none">View Details</span>
                                    </a>

                                    @if($lender->isPending())
                                        <!-- Approve Button -->
                                        <button wire:click="approveLender({{ $lender->id }})" 
                                                onclick="confirm('Are you sure you want to approve this lender? This will create a user account for them.') || event.stopImmediatePropagation()"
                                                class="text-gray-600 hover:text-gray-700 p-2 rounded-xl hover:bg-gray-50 transition-all duration-200 group relative" 
                                                title="Approve">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            <span class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 text-xs text-white bg-gray-900 rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap pointer-events-none">Approve Lender</span>
                                        </button>

                                        <!-- Reject Button -->
                                        <button onclick="document.getElementById('reject-modal-{{ $lender->id }}').style.display='block'" 
                                                class="text-sidebar-green hover:text-sidebar-green-light p-2 rounded-xl hover:bg-sidebar-green-50 transition-all duration-200 group relative" 
                                                title="Reject">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                            <span class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 text-xs text-white bg-gray-900 rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap pointer-events-none">Reject Application</span>
                                        </button>
                                    @endif

                                    @if($lender->isApproved())
                                        <!-- Suspend Button -->
                                        <button wire:click="suspendLender({{ $lender->id }})" 
                                                class="text-sidebar-green hover:text-sidebar-green-light p-2 rounded-xl hover:bg-sidebar-green-50 transition-all duration-200 group relative" 
                                                title="Suspend">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            <span class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 text-xs text-white bg-gray-900 rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap pointer-events-none">Suspend Lender</span>
                                        </button>
                                    @endif

                                    @if($lender->isSuspended())
                                        <!-- Reactivate Button -->
                                        <button wire:click="reactivateLender({{ $lender->id }})" 
                                                onclick="confirm('Are you sure you want to reactivate this lender?') || event.stopImmediatePropagation()"
                                                class="text-gray-600 hover:text-gray-700 p-2 rounded-xl hover:bg-gray-50 transition-all duration-200 group relative" 
                                                title="Reactivate">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                            </svg>
                                            <span class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 text-xs text-white bg-gray-900 rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap pointer-events-none">Reactivate Lender</span>
                                        </button>
                                    @endif

                                    <!-- Delete Button -->
                                    <button wire:click="deleteLender({{ $lender->id }})" 
                                            class="text-sidebar-green hover:text-sidebar-green-light p-2 rounded-xl hover:bg-sidebar-green-50 transition-all duration-200 group relative" 
                                            title="Delete">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                        <span class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 text-xs text-white bg-gray-900 rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap pointer-events-none">Delete Lender</span>
                                    </button>
                                </div>

                                <!-- Rejection Modal -->
                                <div id="reject-modal-{{ $lender->id }}" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
                                    <div class="flex items-center justify-center min-h-screen p-4">
                                        <div class="bg-white rounded-xl p-6 w-full max-w-md">
                                            <h3 class="text-lg font-bold text-gray-900 mb-4">Reject Lender Application</h3>
                                            <div class="mb-4">
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Rejection Reason</label>
                                                <textarea wire:model="rejection_reason" rows="3" 
                                                          class="w-full px-3 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green" 
                                                          placeholder="Please provide a reason for rejection..."></textarea>
                                            </div>
                                            <div class="flex justify-end space-x-3">
                                                <button onclick="document.getElementById('reject-modal-{{ $lender->id }}').style.display='none'" 
                                                        class="px-4 py-2 text-gray-600 bg-gray-100 rounded-xl hover:bg-gray-200 transition-colors">
                                                    Cancel
                                                </button>
                                                <button wire:click="rejectLender({{ $lender->id }})" 
                                                        onclick="document.getElementById('reject-modal-{{ $lender->id }}').style.display='none'" 
                                                        class="px-4 py-2 bg-sidebar-green text-white rounded-xl hover:bg-sidebar-green-light transition-colors">
                                                    Reject
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-8 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">No lenders found</h3>
                                    <p class="text-gray-500 mb-4">Get started by adding your first lender.</p>
                                    <button wire:click="showAddLenderForm" class="bg-sidebar-green text-white px-6 py-2 rounded-xl font-semibold hover:bg-sidebar-green-light transition-all duration-200">
                                        Add First Lender
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($lenders->hasPages())
            <div class="bg-white px-8 py-4 border-t border-gray-200">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="text-sm text-gray-700">
                        <span class="font-medium">{{ $lenders->firstItem() }}</span>
                        -
                        <span class="font-medium">{{ $lenders->lastItem() }}</span>
                        of
                        <span class="font-medium">{{ $lenders->total() }}</span>
                        lenders
                    </div>
                    <div class="flex-1 flex justify-center sm:justify-end">
                        {{ $lenders->links() }}
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Add Lender Modal -->
    @if($showAddForm)
    <div class="fixed inset-0 bg-black bg-opacity-50 z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-xl p-8 w-full max-w-4xl max-h-screen overflow-y-auto">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-gray-900">Add New Lender</h2>
                    <button wire:click="hideAddLenderForm" class="text-gray-400 hover:text-gray-600 p-2 rounded-xl hover:bg-gray-100 transition-all duration-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <!-- Important Notice -->
                <div class="bg-sidebar-green-50 border border-sidebar-green-200 rounded-xl p-4 mb-6">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-sidebar-green mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div>
                            <h4 class="text-sm font-semibold text-sidebar-green-800 mb-1">Important Notice</h4>
                            <p class="text-sm text-sidebar-green-light">The contact person will become the primary system user for this lender account. They will receive login credentials and have access to manage all loan applications and lender settings.</p>
                        </div>
                    </div>
                </div>

                <form wire:submit="saveLender">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- Company Name -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Company Name *</label>
                            <input wire:model="company_name" type="text" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green @error('company_name') border-sidebar-green @enderror" 
                                   placeholder="Enter company name">
                            @error('company_name') <span class="text-sidebar-green text-sm mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- License Number -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">License Number</label>
                            <input wire:model="license_number" type="text" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green @error('license_number') border-sidebar-green @enderror" 
                                   placeholder="Enter license number">
                            @error('license_number') <span class="text-sidebar-green text-sm mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Icon (Optional) -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Company Icon (Optional)</label>
                            <input wire:model="icon" type="file" accept="image/*" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green @error('icon') border-sidebar-green @enderror">
                            @if($icon)
                                <div class="mt-2">
                                    <img src="{{ $icon->temporaryUrl() }}" alt="Preview" class="w-16 h-16 object-cover rounded-lg border border-gray-200">
                                </div>
                            @endif
                            <p class="text-xs text-gray-500 mt-1">Recommended: Square image (e.g., 200x200px). Max 2MB. If not provided, initials will be displayed.</p>
                            @error('icon') <span class="text-sidebar-green text-sm mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Contact Person -->
                        <div class="relative">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Contact Person *
                                <span class="inline-flex items-center ml-1">
                                    <svg class="w-4 h-4 text-sidebar-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </span>
                            </label>
                            <input wire:model="contact_person" type="text" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green @error('contact_person') border-sidebar-green @enderror" 
                                   placeholder="Enter contact person name">
                            <p class="text-xs text-sidebar-green mt-1 flex items-center">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                This person will become the system user
                            </p>
                            @error('contact_person') <span class="text-sidebar-green text-sm mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Email -->
                        <div class="relative">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Email Address *
                                <span class="inline-flex items-center ml-1">
                                    <svg class="w-4 h-4 text-sidebar-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                                    </svg>
                                </span>
                            </label>
                            <input wire:model="email" type="email" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green @error('email') border-sidebar-green @enderror" 
                                   placeholder="Enter email address">
                            <p class="text-xs text-sidebar-green mt-1 flex items-center">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1721 9z"/>
                                </svg>
                                Login credentials will be sent to this email
                            </p>
                            @error('email') <span class="text-sidebar-green text-sm mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Phone -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number *</label>
                            <input wire:model="phone" type="text" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green @error('phone') border-sidebar-green @enderror" 
                                   placeholder="Enter phone number">
                            @error('phone') <span class="text-sidebar-green text-sm mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Website -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Website</label>
                            <input wire:model="website" type="url" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green @error('website') border-sidebar-green @enderror" 
                                   placeholder="https://example.com">
                            @error('website') <span class="text-sidebar-green text-sm mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- City -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">City *</label>
                            <input wire:model="city" type="text" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green @error('city') border-sidebar-green @enderror" 
                                   placeholder="Enter city">
                            @error('city') <span class="text-sidebar-green text-sm mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Region -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Region *</label>
                            <input wire:model="region" type="text" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green @error('region') border-sidebar-green @enderror" 
                                   placeholder="Enter region">
                            @error('region') <span class="text-sidebar-green text-sm mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Postal Code -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Postal Code</label>
                            <input wire:model="postal_code" type="text" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green @error('postal_code') border-sidebar-green @enderror" 
                                   placeholder="Enter postal code">
                            @error('postal_code') <span class="text-sidebar-green text-sm mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Address -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Address *</label>
                        <textarea wire:model="address" rows="3" 
                                  class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green @error('address') border-sidebar-green @enderror" 
                                  placeholder="Enter full address"></textarea>
                        @error('address') <span class="text-sidebar-green text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Description -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <textarea wire:model="description" rows="3" 
                                  class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green @error('description') border-sidebar-green @enderror" 
                                  placeholder="Brief description about the lender"></textarea>
                        @error('description') <span class="text-sidebar-green text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Document Uploads -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Required Documents</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- Business License -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Business License</label>
                                <input wire:model="business_license" type="file" accept=".pdf,.jpg,.jpeg,.png" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green @error('business_license') border-sidebar-green @enderror">
                                @error('business_license') <span class="text-sidebar-green text-sm mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <!-- Tax Certificate -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Tax Certificate</label>
                                <input wire:model="tax_certificate" type="file" accept=".pdf,.jpg,.jpeg,.png" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green @error('tax_certificate') border-sidebar-green @enderror">
                                @error('tax_certificate') <span class="text-sidebar-green text-sm mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <!-- Bank Statement -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Bank Statement</label>
                                <input wire:model="bank_statement" type="file" accept=".pdf,.jpg,.jpeg,.png" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green @error('bank_statement') border-sidebar-green @enderror">
                                @error('bank_statement') <span class="text-sidebar-green text-sm mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex justify-end space-x-4">
                        <button type="button" wire:click="hideAddLenderForm" 
                                class="px-6 py-3 text-gray-600 bg-gray-100 rounded-xl font-semibold hover:bg-gray-200 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" 
                                class="px-6 py-3 bg-sidebar-green text-white rounded-xl font-semibold hover:bg-sidebar-green-light transition-all duration-200 shadow-lg shadow-sidebar-green/25 disabled:opacity-50 disabled:cursor-not-allowed"
                                wire:loading.attr="disabled">
                            <span wire:loading.remove>Submit Application</span>
                            <span wire:loading>Submitting...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <!-- View Lender Modal -->
    @if($showViewModal && $selectedLender)
        <div class="fixed inset-0 bg-black bg-opacity-50 z-50">
            <div class="flex items-center justify-center min-h-screen p-4">
                <div class="bg-white rounded-xl p-8 w-full max-w-4xl max-h-screen overflow-y-auto">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-2xl font-bold text-gray-900">Lender Details</h2>
                        <button wire:click="closeViewModal" class="text-gray-400 hover:text-gray-600 p-2 rounded-xl hover:bg-gray-100 transition-all duration-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Basic Information -->
                        <div class="space-y-6">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Company Information</h3>
                                <div class="space-y-3">
                                    <div>
                                        <label class="text-sm font-medium text-gray-500">Company Name</label>
                                        <p class="text-gray-900 font-semibold">{{ $selectedLender->company_name }}</p>
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium text-gray-500">License Number</label>
                                        <p class="text-gray-900">{{ $selectedLender->license_number ?: 'Not provided' }}</p>
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium text-gray-500">Contact Person</label>
                                        <p class="text-gray-900">{{ $selectedLender->contact_person }}</p>
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium text-gray-500">Email</label>
                                        <p class="text-gray-900">{{ $selectedLender->email }}</p>
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium text-gray-500">Phone</label>
                                        <p class="text-gray-900">{{ $selectedLender->phone }}</p>
                                    </div>
                                    @if($selectedLender->website)
                                        <div>
                                            <label class="text-sm font-medium text-gray-500">Website</label>
                                            <p class="text-gray-900">
                                                <a href="{{ $selectedLender->website }}" target="_blank" class="text-sidebar-green hover:underline">{{ $selectedLender->website }}</a>
                                            </p>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Location</h3>
                                <div class="space-y-3">
                                    <div>
                                        <label class="text-sm font-medium text-gray-500">Address</label>
                                        <p class="text-gray-900">{{ $selectedLender->address }}</p>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="text-sm font-medium text-gray-500">City</label>
                                            <p class="text-gray-900">{{ $selectedLender->city }}</p>
                                        </div>
                                        <div>
                                            <label class="text-sm font-medium text-gray-500">Region</label>
                                            <p class="text-gray-900">{{ $selectedLender->region }}</p>
                                        </div>
                                    </div>
                                    @if($selectedLender->postal_code)
                                        <div>
                                            <label class="text-sm font-medium text-gray-500">Postal Code</label>
                                            <p class="text-gray-900">{{ $selectedLender->postal_code }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            @if($selectedLender->description)
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Description</h3>
                                    <p class="text-gray-700">{{ $selectedLender->description }}</p>
                                </div>
                            @endif
                        </div>

                        <!-- Documents and Additional Info -->
                        <div class="space-y-6">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Documents</h3>
                                @if($selectedLender->documents && count($selectedLender->documents) > 0)
                                    <div class="space-y-3">
                                        @foreach($selectedLender->documents as $type => $path)
                                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                                                <div class="flex items-center space-x-3">
                                                    <div class="w-10 h-10 bg-sidebar-green-100 rounded-xl flex items-center justify-center">
                                                        <svg class="w-5 h-5 text-sidebar-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                        </svg>
                                                    </div>
                                                    <div>
                                                        <p class="text-sm font-medium text-gray-900">{{ ucwords(str_replace('_', ' ', $type)) }}</p>
                                                        <p class="text-xs text-gray-500">Uploaded document</p>
                                                    </div>
                                                </div>
                                                <a href="{{ Storage::url($path) }}" target="_blank" class="text-sidebar-green hover:text-sidebar-green-800 text-sm font-medium">
                                                    View
                                                </a>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center py-8">
                                        <svg class="w-12 h-12 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        <p class="text-gray-500">No documents uploaded</p>
                                    </div>
                                @endif
                            </div>

                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Application Timeline</h3>
                                <div class="space-y-4">
                                    <div class="flex items-start space-x-3">
                                        <div class="w-3 h-3 bg-sidebar-green rounded-full mt-2"></div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">Application Submitted</p>
                                            <p class="text-xs text-gray-500">{{ $selectedLender->created_at->format('M d, Y g:i A') }}</p>
                                        </div>
                                    </div>

                                    @if($selectedLender->approved_at)
                                        <div class="flex items-start space-x-3">
                                            <div class="w-3 h-3 bg-gray-600 rounded-full mt-2"></div>
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">Application Approved</p>
                                                <p class="text-xs text-gray-500">{{ $selectedLender->approved_at->format('M d, Y g:i A') }}</p>
                                                @if($selectedLender->approvedBy)
                                                    <p class="text-xs text-gray-400">by {{ $selectedLender->approvedBy->name }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    @endif

                                    @if($selectedLender->isRejected() && $selectedLender->rejection_reason)
                                        <div class="flex items-start space-x-3">
                                            <div class="w-3 h-3 bg-black rounded-full mt-2"></div>
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">Application Rejected</p>
                                                <p class="text-xs text-gray-500">{{ $selectedLender->updated_at->format('M d, Y g:i A') }}</p>
                                                <p class="text-sm text-sidebar-green mt-1">{{ $selectedLender->rejection_reason }}</p>
                                            </div>
                                        </div>
                                    @endif

                                    @if($selectedLender->user)
                                        <div class="flex items-start space-x-3">
                                            <div class="w-3 h-3 bg-sidebar-green-400 rounded-full mt-2"></div>
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">User Account Created</p>
                                                <p class="text-xs text-gray-500">{{ $selectedLender->user->created_at->format('M d, Y g:i A') }}</p>
                                                <p class="text-xs text-gray-400">User ID: {{ $selectedLender->user->id }}</p>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            @if($selectedLender->isPending())
                                <div class="pt-4 border-t border-gray-200">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Actions</h3>
                                    <div class="flex space-x-3">
                                        <button wire:click="approveLender({{ $selectedLender->id }})" 
                                                onclick="confirm('Are you sure you want to approve this lender?') || event.stopImmediatePropagation()"
                                                class="flex-1 bg-gray-600 text-white py-2 px-4 rounded-xl font-semibold hover:bg-gray-700 transition-colors">
                                            Approve Lender
                                        </button>
                                        <button onclick="document.getElementById('reject-action-modal').style.display='block'" 
                                                class="flex-1 bg-sidebar-green text-white py-2 px-4 rounded-xl font-semibold hover:bg-sidebar-green-light transition-colors">
                                            Reject Application
                                        </button>
                                    </div>
                                </div>

                                <!-- Reject Action Modal -->
                                <div id="reject-action-modal" class="fixed inset-0 bg-black bg-opacity-50 z-60 hidden">
                                    <div class="flex items-center justify-center min-h-screen p-4">
                                        <div class="bg-white rounded-xl p-6 w-full max-w-md">
                                            <h3 class="text-lg font-bold text-gray-900 mb-4">Reject Lender Application</h3>
                                            <div class="mb-4">
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Rejection Reason</label>
                                                <textarea wire:model="rejection_reason" rows="3" 
                                                          class="w-full px-3 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green" 
                                                          placeholder="Please provide a reason for rejection..."></textarea>
                                            </div>
                                            <div class="flex justify-end space-x-3">
                                                <button onclick="document.getElementById('reject-action-modal').style.display='none'" 
                                                        class="px-4 py-2 text-gray-600 bg-gray-100 rounded-xl hover:bg-gray-200 transition-colors">
                                                    Cancel
                                                </button>
                                                <button wire:click="rejectLender({{ $selectedLender->id }})" 
                                                        onclick="document.getElementById('reject-action-modal').style.display='none'" 
                                                        class="px-4 py-2 bg-sidebar-green text-white rounded-xl hover:bg-sidebar-green-light transition-colors">
                                                    Reject
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Password Confirmation Modal -->
    @if($showPasswordConfirmModal)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" 
             wire:click.self="closePasswordConfirmModal"
             x-data="{ show: true }" 
             x-show="show" 
             x-transition:enter="ease-out duration-300" 
             x-transition:enter-start="opacity-0" 
             x-transition:enter-end="opacity-100">
            <div class="relative top-1/2 transform -translate-y-1/2 mx-auto p-5 border w-11/12 max-w-md shadow-lg rounded-lg bg-white"
                 x-transition:enter="ease-out duration-300" 
                 x-transition:enter-start="opacity-0 transform scale-95" 
                 x-transition:enter-end="opacity-100 transform scale-100">
                
                <!-- Modal Header -->
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-sidebar-green-100 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-sidebar-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                <div class="bg-sidebar-green-50 border border-sidebar-green-200 rounded-lg p-4 mb-6">
                    <div class="flex items-start space-x-3">
                        <svg class="w-5 h-5 text-sidebar-green mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.464 0L4.348 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                        </svg>
                        <div>
                            <h4 class="text-sm font-semibold text-sidebar-green-800">Critical Action Warning</h4>
                            <p class="text-sm text-sidebar-green-light mt-1">{{ $passwordConfirmMessage }}</p>
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
                               class="w-full border border-gray-300 rounded-xl px-3 py-2 focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green @error('currentPassword') border-sidebar-green @enderror"
                               autofocus>
                        @error('currentPassword') 
                            <span class="text-sidebar-green text-xs mt-1 block">{{ $message }}</span> 
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
                                class="bg-sidebar-green text-white px-4 py-2 rounded-xl font-semibold hover:bg-sidebar-green-light transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                                wire:loading.attr="disabled">
                            <span wire:loading.remove>Confirm Action</span>
                            <span wire:loading>Processing...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
</div>