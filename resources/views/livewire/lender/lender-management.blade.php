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
                <div class="flex items-center space-x-2 bg-green-50 px-4 py-2 rounded-full">
                    <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
                    <span class="text-sm font-medium text-green-700">{{ $stats['pending'] }} Pending Review</span>
                </div>
                <button wire:click="showAddLenderForm" class="bg-brand-red text-white px-6 py-2 rounded-lg font-semibold hover:bg-brand-dark-red transition-all duration-200 shadow-sm shadow-brand-red/25">
                    + Add Lender
                </button>
            </div>
        </div>
    </div>

    <!-- Flash Messages -->
    @if (session()->has('message'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl" role="alert">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                {{ session('message') }}
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl" role="alert">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                {{ session('error') }}
            </div>
        </div>
    @endif

   
    <!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Lenders -->
    <div class="bg-white rounded-lg p-6 border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300 group">
        <div class="flex items-center justify-between">
            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center shadow-md group-hover:scale-105 transition-transform duration-300">
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
            <div class="w-12 h-12 bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-lg flex items-center justify-center shadow-md group-hover:scale-105 transition-transform duration-300">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="text-right">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Pending</p>
                <p class="text-2xl font-bold text-yellow-600">{{ $stats['pending'] }}</p>
            </div>
        </div>
    </div>

    <!-- Approved -->
    <div class="bg-white rounded-xl p-6 border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300 group">
        <div class="flex items-center justify-between">
            <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-lg flex items-center justify-center shadow-md group-hover:scale-105 transition-transform duration-300">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="text-right">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Approved</p>
                <p class="text-2xl font-bold text-green-600">{{ $stats['approved'] }}</p>
            </div>
        </div>
    </div>

    <!-- Rejected -->
    <div class="bg-white rounded-xl p-6 border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300 group">
        <div class="flex items-center justify-between">
            <div class="w-12 h-12 bg-gradient-to-br from-red-500 to-red-600 rounded-lg flex items-center justify-center shadow-md group-hover:scale-105 transition-transform duration-300">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </div>
            <div class="text-right">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Rejected</p>
                <p class="text-2xl font-bold text-red-600">{{ $stats['rejected'] +  $stats['suspended'] }}</p>
            </div>
        </div>
    </div>

    
</div>




<div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100 mb-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                <!-- Search -->
                <div class="lg:col-span-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Search Lenders</label>
                    <div class="relative">
                        <input wire:model.live="search" type="text" placeholder="Search by name, email..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500">
                        <svg class="w-4 h-4 text-gray-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>

                <!-- Status Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Filter by Status</label>
                    <select wire:model.live="statusFilter" class="w-full border border-gray-300 rounded-xl px-3 py-2 focus:ring-2 focus:ring-red-500 focus:border-red-500">
                        <option value="">All Statuses</option>

                        @foreach ($lender_status  as  $status)
 
                        <option value="{{ $status }}">{{ $status }}</option>


                        @endforeach



                    </select>
                </div>

              
            </div>
        </div>




  

    <!-- Lenders Table -->
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
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
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Applied Date</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($lenders as $lender)
                        <tr class="hover:bg-gray-50 transition-colors duration-200 group">
                            <td class="px-8 py-6 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 bg-gradient-to-br from-brand-red to-brand-dark-red rounded-2xl flex items-center justify-center shadow-md">
                                        <span class="text-white text-sm font-bold">{{ substr($lender->company_name, 0, 2) }}</span>
                                    </div>
                                    <div class="ml-4">
                                        <a href="{{ route('lender.dashboard',$lender) }}" class="text-sm font-bold text-gray-900 group-hover:text-brand-red transition-colors">{{ $lender->company_name }}</a>
                                        <div class="text-xs text-gray-500">{{ $lender->license_number ?? 'No License' }}</div>
                                        <div class="text-xs text-blue-600 font-medium mt-1">{{ $lender->contact_person }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-6 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $lender->email }}</div>
                                <div class="text-sm text-gray-500">{{ $lender->phone }}</div>
                                @if($lender->website)
                                    <div class="text-xs text-blue-600 mt-1">
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
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800 border border-yellow-200">
                                        <svg class="w-3 h-3 mr-1.5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        Pending
                                    </span>
                                @elseif($lender->status === 'approved')
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-green-100 text-green-800 border border-green-200">
                                        <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        Approved
                                    </span>
                                @elseif($lender->status === 'rejected')
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-red-100 text-red-800 border border-red-200">
                                        <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                        Rejected
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-800 border border-gray-200">
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
                                        <span class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-medium bg-green-100 text-green-800">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                            {{ count($lender->documents) }} docs
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-medium bg-red-100 text-red-800">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.664-.833-2.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                            </svg>
                                            No docs
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-6 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $lender->created_at->format('M d, Y') }}</div>
                                <div class="text-xs text-gray-500">{{ $lender->created_at->format('g:i A') }}</div>
                            </td>
                            <td class="px-6 py-6 whitespace-nowrap text-right">
                                <div class="flex items-center space-x-2">
                                    <!-- View Button -->
                                    <button wire:click="viewLender({{ $lender->id }})" class="text-blue-600 hover:text-blue-800 p-2 rounded-xl hover:bg-blue-50 transition-all duration-200" title="View Details">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </button>

                                    @if($lender->isPending())
                                        <!-- Approve Button -->
                                        <button wire:click="approveLender({{ $lender->id }})" 
                                                wire:confirm="Are you sure you want to approve this lender? This will create a user account for them."
                                                class="text-green-600 hover:text-green-800 p-2 rounded-xl hover:bg-green-50 transition-all duration-200" title="Approve">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        </button>

                                        <!-- Reject Button -->
                                        <button onclick="document.getElementById('reject-modal-{{ $lender->id }}').style.display='block'" 
                                                class="text-red-600 hover:text-red-800 p-2 rounded-xl hover:bg-red-50 transition-all duration-200" title="Reject">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </button>
                                    @endif

                                    @if($lender->isApproved())
                                        <!-- Suspend Button -->
                                        <button wire:click="suspendLender({{ $lender->id }})" 
                                                wire:confirm="Are you sure you want to suspend this lender?"
                                                class="text-yellow-600 hover:text-yellow-800 p-2 rounded-xl hover:bg-yellow-50 transition-all duration-200" title="Suspend">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        </button>
                                    @endif

                                    @if($lender->isSuspended())
                                        <!-- Reactivate Button -->
                                        <button wire:click="reactivateLender({{ $lender->id }})" 
                                                wire:confirm="Are you sure you want to reactivate this lender?"
                                                class="text-green-600 hover:text-green-800 p-2 rounded-xl hover:bg-green-50 transition-all duration-200" title="Reactivate">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                            </svg>
                                        </button>
                                    @endif

                                    <!-- Delete Button -->
                                    <button wire:click="deleteLender({{ $lender->id }})" 
                                            wire:confirm="Are you sure you want to delete this lender? This action cannot be undone."
                                            class="text-red-600 hover:text-red-800 p-2 rounded-xl hover:bg-red-50 transition-all duration-200" title="Delete">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>

                                <!-- Rejection Modal -->
                                <div id="reject-modal-{{ $lender->id }}" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
                                    <div class="flex items-center justify-center min-h-screen p-4">
                                        <div class="bg-white rounded-3xl p-6 w-full max-w-md">
                                            <h3 class="text-lg font-bold text-gray-900 mb-4">Reject Lender Application</h3>
                                            <div class="mb-4">
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Rejection Reason</label>
                                                <textarea wire:model="rejection_reason" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-brand-red focus:border-brand-red" placeholder="Please provide a reason for rejection..."></textarea>
                                            </div>
                                            <div class="flex justify-end space-x-3">
                                                <button onclick="document.getElementById('reject-modal-{{ $lender->id }}').style.display='none'" class="px-4 py-2 text-gray-600 bg-gray-100 rounded-xl hover:bg-gray-200 transition-colors">
                                                    Cancel
                                                </button>
                                                <button wire:click="rejectLender({{ $lender->id }})" onclick="document.getElementById('reject-modal-{{ $lender->id }}').style.display='none'" class="px-4 py-2 bg-red-600 text-white rounded-xl hover:bg-red-700 transition-colors">
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
                                    <button wire:click="showAddLenderForm" class="bg-brand-red text-white px-6 py-2 rounded-xl font-semibold hover:bg-brand-dark-red transition-all duration-200">
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
        <div class="px-8 py-4 border-t border-gray-100">
            {{ $lenders->links() }}
        </div>
    </div>

    <!-- Add Lender Modal -->
    @if($showAddForm)
    <div class="fixed inset-0 bg-black bg-opacity-50 z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-3xl p-8 w-full max-w-4xl max-h-screen overflow-y-auto">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-gray-900">Add New Lender</h2>
                    <button wire:click="hideAddLenderForm" class="text-gray-400 hover:text-gray-600 p-2 rounded-xl hover:bg-gray-100 transition-all duration-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <!-- Important Notice -->
                <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-6">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div>
                            <h4 class="text-sm font-semibold text-blue-800 mb-1">Important Notice</h4>
                            <p class="text-sm text-blue-700">The contact person will become the primary system user for this lender account. They will receive login credentials and have access to manage all loan applications and lender settings.</p>
                        </div>
                    </div>
                </div>

                <form wire:submit="saveLender">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- Company Name -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Company Name *</label>
                            <input wire:model="company_name" type="text" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-brand-red focus:border-brand-red" placeholder="Enter company name">
                            @error('company_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- License Number -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">License Number</label>
                            <input wire:model="license_number" type="text" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-brand-red focus:border-brand-red" placeholder="Enter license number">
                            @error('license_number') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Contact Person -->
                        <div class="relative">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Contact Person *
                                <span class="inline-flex items-center ml-1">
                                    <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </span>
                            </label>
                            <input wire:model="contact_person" type="text" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-brand-red focus:border-brand-red" placeholder="Enter contact person name">
                            <p class="text-xs text-blue-600 mt-1 flex items-center">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                This person will become the system user
                            </p>
                            @error('contact_person') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Email -->
                        <div class="relative">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Email Address *
                                <span class="inline-flex items-center ml-1">
                                    <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                                    </svg>
                                </span>
                            </label>
                            <input wire:model="email" type="email" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-brand-red focus:border-brand-red" placeholder="Enter email address">
                            <p class="text-xs text-blue-600 mt-1 flex items-center">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                                </svg>
                                Login credentials will be sent to this email
                            </p>
                            @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Phone -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number *</label>
                            <input wire:model="phone" type="text" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-brand-red focus:border-brand-red" placeholder="Enter phone number">
                            @error('phone') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Website -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Website</label>
                            <input wire:model="website" type="url" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-brand-red focus:border-brand-red" placeholder="https://example.com">
                            @error('website') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- City -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">City *</label>
                            <input wire:model="city" type="text" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-brand-red focus:border-brand-red" placeholder="Enter city">
                            @error('city') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Region -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Region *</label>
                            <input wire:model="region" type="text" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-brand-red focus:border-brand-red" placeholder="Enter region">
                            @error('region') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Postal Code -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Postal Code</label>
                            <input wire:model="postal_code" type="text" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-brand-red focus:border-brand-red" placeholder="Enter postal code">
                            @error('postal_code') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Address -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Address *</label>
                        <textarea wire:model="address" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-brand-red focus:border-brand-red" placeholder="Enter full address"></textarea>
                        @error('address') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Description -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <textarea wire:model="description" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-brand-red focus:border-brand-red" placeholder="Brief description about the lender"></textarea>
                        @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Document Uploads -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Required Documents</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- Business License -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Business License</label>
                                <input wire:model="business_license" type="file" accept=".pdf,.jpg,.jpeg,.png" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-brand-red focus:border-brand-red">
                                @error('business_license') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <!-- Tax Certificate -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Tax Certificate</label>
                                <input wire:model="tax_certificate" type="file" accept=".pdf,.jpg,.jpeg,.png" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-brand-red focus:border-brand-red">
                                @error('tax_certificate') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <!-- Bank Statement -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Bank Statement</label>
                                <input wire:model="bank_statement" type="file" accept=".pdf,.jpg,.jpeg,.png" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-brand-red focus:border-brand-red">
                                @error('bank_statement') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex justify-end space-x-4">
                        <button type="button" wire:click="hideAddLenderForm" class="px-6 py-3 text-gray-600 bg-gray-100 rounded-xl font-semibold hover:bg-gray-200 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" class="px-6 py-3 bg-brand-red text-white rounded-xl font-semibold hover:bg-brand-dark-red transition-all duration-200 shadow-sm shadow-brand-red/25">
                            Submit Application
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
                <div class="bg-white rounded-3xl p-8 w-full max-w-4xl max-h-screen overflow-y-auto">
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
                                                <a href="{{ $selectedLender->website }}" target="_blank" class="text-blue-600 hover:underline">{{ $selectedLender->website }}</a>
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
                                                    <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">
                                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                        </svg>
                                                    </div>
                                                    <div>
                                                        <p class="text-sm font-medium text-gray-900">{{ ucwords(str_replace('_', ' ', $type)) }}</p>
                                                        <p class="text-xs text-gray-500">Uploaded document</p>
                                                    </div>
                                                </div>
                                                <a href="{{ Storage::url($path) }}" target="_blank" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
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
                                        <div class="w-3 h-3 bg-blue-500 rounded-full mt-2"></div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">Application Submitted</p>
                                            <p class="text-xs text-gray-500">{{ $selectedLender->created_at->format('M d, Y g:i A') }}</p>
                                        </div>
                                    </div>

                                    @if($selectedLender->approved_at)
                                        <div class="flex items-start space-x-3">
                                            <div class="w-3 h-3 bg-green-500 rounded-full mt-2"></div>
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
                                            <div class="w-3 h-3 bg-red-500 rounded-full mt-2"></div>
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">Application Rejected</p>
                                                <p class="text-xs text-gray-500">{{ $selectedLender->updated_at->format('M d, Y g:i A') }}</p>
                                                <p class="text-sm text-red-600 mt-1">{{ $selectedLender->rejection_reason }}</p>
                                            </div>
                                        </div>
                                    @endif

                                    @if($selectedLender->user)
                                        <div class="flex items-start space-x-3">
                                            <div class="w-3 h-3 bg-purple-500 rounded-full mt-2"></div>
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
                                                wire:confirm="Are you sure you want to approve this lender?"
                                                class="flex-1 bg-green-600 text-white py-2 px-4 rounded-xl font-semibold hover:bg-green-700 transition-colors">
                                            Approve Lender
                                        </button>
                                        <button onclick="document.getElementById('reject-action-modal').style.display='block'" 
                                                class="flex-1 bg-red-600 text-white py-2 px-4 rounded-xl font-semibold hover:bg-red-700 transition-colors">
                                            Reject Application
                                        </button>
                                    </div>
                                </div>

                                <!-- Reject Action Modal -->
                                <div id="reject-action-modal" class="fixed inset-0 bg-black bg-opacity-50 z-60 hidden">
                                    <div class="flex items-center justify-center min-h-screen p-4">
                                        <div class="bg-white rounded-3xl p-6 w-full max-w-md">
                                            <h3 class="text-lg font-bold text-gray-900 mb-4">Reject Lender Application</h3>
                                            <div class="mb-4">
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Rejection Reason</label>
                                                <textarea wire:model="rejection_reason" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-brand-red focus:border-brand-red" placeholder="Please provide a reason for rejection..."></textarea>
                                            </div>
                                            <div class="flex justify-end space-x-3">
                                                <button onclick="document.getElementById('reject-action-modal').style.display='none'" class="px-4 py-2 text-gray-600 bg-gray-100 rounded-xl hover:bg-gray-200 transition-colors">
                                                    Cancel
                                                </button>
                                                <button wire:click="rejectLender({{ $selectedLender->id }})" onclick="document.getElementById('reject-action-modal').style.display='none'" class="px-4 py-2 bg-red-600 text-white rounded-xl hover:bg-red-700 transition-colors">
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

    <script>
        // Auto-hide flash messages after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                const alerts = document.querySelectorAll('[role="alert"]');
                alerts.forEach(function(alert) {
                    alert.style.transition = 'opacity 0.5s ease-out';
                    alert.style.opacity = '0';
                    setTimeout(function() {
                        alert.remove();
                    }, 500);
                });
            }, 5000);
        });
    </script>
</div>

</div>
