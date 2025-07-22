<div>
{{-- resources/views/livewire/admin/billing-management.blade.php --}}
<div>
    <div class="p-8">
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-4xl font-bold text-gray-900 mb-2">Commission Billing</h1>
                    <p class="text-gray-600 text-lg">Manage commission billing for booked applications</p>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="flex items-center space-x-2 bg-green-50 px-4 py-2 rounded-full">
                        <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
                        <span class="text-sm font-medium text-green-700">Billing Active</span>
                    </div>
                    @if($activeTab === 'applications' && count($selectedApplications) > 0)
                        <button wire:click="createBillsForSelected" 
                                class="bg-red-600 text-white px-6 py-2 rounded-xl font-semibold hover:bg-red-700 transition-all duration-200 shadow-lg shadow-red-600/25">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Create Bills ({{ count($selectedApplications) }})
                        </button>
                    @endif
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

        <!-- Navigation Tabs -->
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden mb-8">
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-8 px-8" aria-label="Tabs">
                    <button wire:click="setActiveTab('applications')" 
                            class="border-transparent {{ $activeTab === 'applications' ? 'text-red-600 border-b-2 border-red-500' : 'text-gray-500 hover:text-gray-700' }} py-6 px-1 text-sm font-medium transition-colors">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Booked Applications
                    </button>
                    <button wire:click="setActiveTab('bills')" 
                            class="border-transparent {{ $activeTab === 'bills' ? 'text-red-600 border-b-2 border-red-500' : 'text-gray-500 hover:text-gray-700' }} py-6 px-1 text-sm font-medium transition-colors">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        Commission Bills
                    </button>
                </nav>
            </div>

            <!-- Filters Section -->
            <div class="p-6 bg-gray-50 border-b border-gray-200">
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <!-- Search -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                        <input wire:model.live="search" type="text" 
                               class="w-full border border-gray-300 rounded-xl px-3 py-2 focus:ring-2 focus:ring-red-500 focus:border-red-500"
                               placeholder="Search applications or bills...">
                    </div>

                    <!-- Status Filter -->
                    @if($activeTab === 'bills')
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select wire:model.live="filterStatus" class="w-full border border-gray-300 rounded-xl px-3 py-2 focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                <option value="all">All Status</option>
                                <option value="pending">Pending</option>
                                <option value="sent">Sent</option>
                                <option value="paid">Paid</option>
                                <option value="overdue">Overdue</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>
                    @endif

                    <!-- Lender Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Lender</label>
                        <select wire:model.live="filterLender" class="w-full border border-gray-300 rounded-xl px-3 py-2 focus:ring-2 focus:ring-red-500 focus:border-red-500">
                            <option value="all">All Lenders</option>
                            @foreach($lenders as $lender)
                                <option value="{{ $lender->id }}">{{ $lender->company_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Date From -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">From Date</label>
                        <input wire:model.live="filterDateFrom" type="date" 
                               class="w-full border border-gray-300 rounded-xl px-3 py-2 focus:ring-2 focus:ring-red-500 focus:border-red-500">
                    </div>

                    <!-- Date To -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">To Date</label>
                        <input wire:model.live="filterDateTo" type="date" 
                               class="w-full border border-gray-300 rounded-xl px-3 py-2 focus:ring-2 focus:ring-red-500 focus:border-red-500">
                    </div>
                </div>
            </div>

            <!-- Applications Tab -->
            @if($activeTab === 'applications')
                <div class="p-6">
                    <!-- Bulk Actions -->
                    @if(count($selectedApplications) > 0)
                        <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-xl flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                                <span class="text-blue-700 font-medium">{{ count($selectedApplications) }} applications selected</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <select wire:model="bulkAction" class="border border-blue-300 rounded-lg px-3 py-1.5 text-sm focus:ring-2 focus:ring-blue-500">
                                    <option value="">Choose Action</option>
                                    <option value="create_bills">Create Commission Bills</option>
                                </select>
                                <button wire:click="executeBulkAction" 
                                        class="bg-blue-600 text-white px-4 py-1.5 rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors"
                                        @if(empty($bulkAction)) disabled @endif>
                                    Execute
                                </button>
                            </div>
                        </div>
                    @endif

                    <!-- Applications Table -->
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-4 text-left">
                                        <input wire:model.live="selectAll" type="checkbox" 
                                               class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Application</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Applicant</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Lender</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Loan Amount</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Approved Date</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Billing Status</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Action </th>

                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @forelse($applications as $application)
                                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                                        <td class="px-6 py-4">
                                            <input wire:model.live="selectedApplications" type="checkbox" value="{{ $application->id }}" 
                                                   class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div>
                                                <div class="text-sm font-bold text-gray-900">{{ $application->application_number }}</div>
                                                <div class="text-xs text-gray-500">{{ $application->loan_purpose }}</div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center shadow-md">
                                                    <span class="text-white text-sm font-bold">{{ substr($application->first_name, 0, 1) }}{{ substr($application->last_name, 0, 1) }}</span>
                                                </div>
                                                <div class="ml-3">
                                                    <div class="text-sm font-medium text-gray-900">{{ $application->first_name }} {{ $application->last_name }}</div>
                                                    <div class="text-xs text-gray-500">{{ $application->email }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $application->lender->company_name ?? 'N/A' }}</div>
                                            <div class="text-xs text-gray-500">{{ $application->lender->license_number ?? 'No License' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-bold text-gray-900">TSh {{ number_format($application->requested_amount) }}</div>
                                            <div class="text-xs text-gray-500">{{ $application->requested_tenure_months }} months</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $application->approved_at?->format('M d, Y') }}</div>
                                            <div class="text-xs text-gray-500">{{ $application->approved_at?->diffForHumans() }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $hasBill = \App\Models\CommissionBill::where('application_id', $application->id)->exists();
                                            @endphp
                                            @if($hasBill)
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800 border border-green-200">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                    </svg>
                                                    Bill Created
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800 border border-yellow-200">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    Pending Bill
                                                </span>
                                            @endif
                                        </td>


                                        <td class="px-6 py-4 whitespace-nowrap">
    <div class="flex items-center space-x-2">
        <button wire:click="viewApplication({{ $application->id }})" 
                class="text-blue-600 hover:text-blue-700 p-2 rounded-xl hover:bg-blue-50 transition-all duration-200"
                title="View Application Details">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
            </svg>
        </button>
        
        @php
            $hasBill = \App\Models\CommissionBill::where('application_id', $application->id)->exists();
        @endphp
        
        @if(!$hasBill)
            <button wire:click="createSingleBill({{ $application->id }})" 
                    class="text-green-600 hover:text-green-700 p-2 rounded-xl hover:bg-green-50 transition-all duration-200"
                    title="Create Commission Bill">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
            </button>
        @else
            <button wire:click="viewApplicationBill({{ $application->id }})" 
                    class="text-purple-600 hover:text-purple-700 p-2 rounded-xl hover:bg-purple-50 transition-all duration-200"
                    title="View Commission Bill">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </button>
        @endif
    </div>
</td>


                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-12 text-center">
                                            <div class="w-20 h-20 bg-gray-100 rounded-3xl flex items-center justify-center mx-auto mb-4">
                                                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                </svg>
                                            </div>
                                            <h4 class="text-lg font-semibold text-gray-900 mb-2">No Booked Applications</h4>
                                            <p class="text-gray-500">No approved applications with booked status found matching your filters.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if(isset($applications))
                        <div class="mt-6">
                            {{ $applications->links() }}
                        </div>
                    @endif
                </div>
            @endif

            <!-- Bills Tab -->
            @if($activeTab === 'bills')
                <div class="p-6">
                    <!-- Bills Table -->
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Bill Details</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Application</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Lender</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Commission</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Total Amount</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Due Date</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @forelse($bills as $bill)
                                    <tr class="hover:bg-gray-50 transition-colors duration-200 group">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div>
                                                <div class="text-sm font-bold text-gray-900">{{ $bill->bill_number }}</div>
                                                <div class="text-xs text-gray-500">{{ $bill->created_at->format('M d, Y') }}</div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">{{ $bill->application->application_number }}</div>
                                                <div class="text-xs text-gray-500">{{ $bill->application->first_name }} {{ $bill->application->last_name }}</div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="w-8 h-8 bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg flex items-center justify-center shadow-sm">
                                                    <span class="text-white text-xs font-bold">{{ substr($bill->lender->company_name, 0, 2) }}</span>
                                                </div>
                                                <div class="ml-2">
                                                    <div class="text-sm font-medium text-gray-900">{{ $bill->lender->company_name }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($bill->commission_type === 'percentage')
                                                <div class="text-sm font-medium text-gray-900">{{ $bill->commission_rate }}%</div>
                                                <div class="text-xs text-gray-500">TSh {{ number_format($bill->commission_amount) }}</div>
                                            @else
                                                <div class="text-sm font-medium text-gray-900">Fixed</div>
                                                <div class="text-xs text-gray-500">TSh {{ number_format($bill->commission_amount) }}</div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-bold text-gray-900">TSh {{ number_format($bill->total_amount) }}</div>
                                            @if($bill->total_paid > 0)
                                                <div class="text-xs text-green-600">Paid: TSh {{ number_format($bill->total_paid) }}</div>
                                                @if($bill->balance > 0)
                                                    <div class="text-xs text-red-600">Balance: TSh {{ number_format($bill->balance) }}</div>
                                                @endif
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                                @if($bill->status === 'paid') bg-green-100 text-green-800 border border-green-200
                                                @elseif($bill->status === 'overdue') bg-red-100 text-red-800 border border-red-200
                                                @elseif($bill->status === 'sent') bg-blue-100 text-blue-800 border border-blue-200
                                                @else bg-yellow-100 text-yellow-800 border border-yellow-200 @endif">
                                                <div class="w-2 h-2 rounded-full mr-2
                                                    @if($bill->status === 'paid') bg-green-400
                                                    @elseif($bill->status === 'overdue') bg-red-400
                                                    @elseif($bill->status === 'sent') bg-blue-400
                                                    @else bg-yellow-400 @endif"></div>
                                                {{ ucfirst($bill->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $bill->due_date->format('M d, Y') }}</div>
                                            @if($bill->is_overdue)
                                                <div class="text-xs text-red-600">{{ $bill->days_overdue }} days overdue</div>
                                            @else
                                                <div class="text-xs text-gray-500">{{ $bill->due_date->diffForHumans() }}</div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center space-x-2">
                                                @if($bill->status !== 'paid')
                                                    <button wire:click="openPaymentModal({{ $bill->id }})" 
                                                            class="text-green-600 hover:text-green-700 p-2 rounded-xl hover:bg-green-50 transition-all duration-200">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                                        </svg>
                                                    </button>
                                                @endif
                                                <button wire:click="viewBillDetails({{ $bill->id }})" 
                                        class="text-blue-600 hover:text-blue-700 p-2 rounded-xl hover:bg-blue-50 transition-all duration-200"
                                        title="View Bill Details">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
</button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-6 py-12 text-center">
                                            <div class="w-20 h-20 bg-gray-100 rounded-3xl flex items-center justify-center mx-auto mb-4">
                                                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                                </svg>
                                            </div>
                                            <h4 class="text-lg font-semibold text-gray-900 mb-2">No Commission Bills</h4>
                                            <p class="text-gray-500">No commission bills found matching your filters.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if(isset($bills))
                        <div class="mt-6">
                            {{ $bills->links() }}
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <!-- Create Bills Modal -->
    @if($showBillModal)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" wire:click.self="$set('showBillModal', false)">
            <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-2xl shadow-lg rounded-3xl bg-white">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-2xl font-bold text-gray-900">Create Commission Bills</h3>
                    <button wire:click="$set('showBillModal', false)" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <div class="mb-6">
                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                        <div class="flex items-center space-x-3">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div>
                                <h4 class="text-sm font-semibold text-blue-900">Bill Creation Summary</h4>
                                <p class="text-sm text-blue-700">You are about to create {{ count($selectedApplications) }} commission bills for the selected applications.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <form wire:submit.prevent="generateBills">
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Notes (Optional)</label>
                        <textarea wire:model="billNotes" rows="3" 
                                  class="w-full border border-gray-300 rounded-xl px-3 py-2 focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                  placeholder="Add any notes for these commission bills..."></textarea>
                    </div>


                    

                    <div class="flex justify-end space-x-4">
                        <button type="button" wire:click="$set('showBillModal', false)" 
                                class="bg-gray-100 text-gray-700 px-6 py-2 rounded-xl font-medium hover:bg-gray-200 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" 
                                class="bg-red-600 text-white px-6 py-2 rounded-xl font-semibold hover:bg-red-700 transition-colors">
                            Create {{ count($selectedApplications) }} Bills
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Record Payment Modal -->
    @if($showPaymentModal && $selectedBill)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" wire:click.self="resetPaymentForm">
            <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-3xl shadow-lg rounded-3xl bg-white">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-2xl font-bold text-gray-900">Record Payment</h3>
                    <button wire:click="resetPaymentForm" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <!-- Bill Summary -->
                <div class="mb-6 bg-gray-50 rounded-xl p-4">
                    <h4 class="font-semibold text-gray-900 mb-2">Bill: {{ $selectedBill->bill_number }}</h4>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-gray-600">Total Amount:</span>
                            <span class="font-medium ml-2">TSh {{ number_format($selectedBill->total_amount) }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Amount Paid:</span>
                            <span class="font-medium ml-2">TSh {{ number_format($selectedBill->total_paid) }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Balance:</span>
                            <span class="font-medium ml-2 text-red-600">TSh {{ number_format($selectedBill->balance) }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Due Date:</span>
                            <span class="font-medium ml-2">{{ $selectedBill->due_date->format('M d, Y') }}</span>
                        </div>
                    </div>
                </div>

                <form wire:submit.prevent="recordPayment" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Payment Amount *</label>
                            <div class="relative">
                                <input wire:model="paymentAmount" type="number" step="0.01" min="0.01" 
                                       class="w-full border border-gray-300 rounded-xl px-3 py-2 pl-12 focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                <span class="absolute left-3 top-2 text-gray-500">TSh</span>
                            </div>
                            @error('paymentAmount') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Payment Method *</label>
                            <select wire:model="paymentMethod" class="w-full border border-gray-300 rounded-xl px-3 py-2 focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                <option value="bank_transfer">Bank Transfer</option>
                                <option value="mobile_money">Mobile Money</option>
                                <option value="cash">Cash</option>
                                <option value="cheque">Cheque</option>
                                <option value="other">Other</option>
                            </select>
                            @error('paymentMethod') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Payment Date *</label>
                            <input wire:model="paymentDate" type="date" 
                                   class="w-full border border-gray-300 rounded-xl px-3 py-2 focus:ring-2 focus:ring-red-500 focus:border-red-500">
                            @error('paymentDate') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Payment Reference</label>
                            <input wire:model="paymentReference" type="text" 
                                   class="w-full border border-gray-300 rounded-xl px-3 py-2 focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                   placeholder="Transaction ID, Cheque number, etc.">
                            @error('paymentReference') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Payment Notes</label>
                        <textarea wire:model="paymentNotes" rows="3" 
                                  class="w-full border border-gray-300 rounded-xl px-3 py-2 focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                  placeholder="Additional notes about this payment..."></textarea>
                        @error('paymentNotes') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex justify-end space-x-4 pt-6">
                        <button type="button" wire:click="resetPaymentForm" 
                                class="bg-gray-100 text-gray-700 px-6 py-2 rounded-xl font-medium hover:bg-gray-200 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" 
                                class="bg-green-600 text-white px-6 py-2 rounded-xl font-semibold hover:bg-green-700 transition-colors">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Record Payment
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>



{{-- Add these modals to the end of your billing-management.blade.php file, before the closing </div> --}}

<!-- Application Details Modal -->
@if($showApplicationModal && $selectedApplication)
    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" wire:click.self="closeApplicationModal">
        <div class="relative top-10 mx-auto p-5 border w-11/12 max-w-6xl shadow-lg rounded-3xl bg-white">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-2xl font-bold text-gray-900">Application Details</h3>
                <button wire:click="closeApplicationModal" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Applicant Information -->
                <div class="lg:col-span-1">
                    <div class="bg-gray-50 rounded-xl p-6">
                        <h4 class="text-lg font-semibold text-gray-900 mb-4">Applicant Information</h4>
                        <div class="space-y-3">
                            <div class="flex items-center space-x-3 mb-4">
                                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center shadow-md">
                                    <span class="text-white text-lg font-bold">
                                        {{ substr($selectedApplication->first_name, 0, 1) }}{{ substr($selectedApplication->last_name, 0, 1) }}
                                    </span>
                                </div>
                                <div>
                                    <div class="text-lg font-semibold text-gray-900">
                                        {{ $selectedApplication->first_name }} {{ $selectedApplication->last_name }}
                                    </div>
                                    <div class="text-sm text-gray-600">{{ $selectedApplication->email }}</div>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 gap-3 text-sm">
                                <div class="flex justify-between py-2 border-b border-gray-200">
                                    <span class="text-gray-600">Phone:</span>
                                    <span class="font-medium">{{ $selectedApplication->phone_number }}</span>
                                </div>
                                <div class="flex justify-between py-2 border-b border-gray-200">
                                    <span class="text-gray-600">Employment:</span>
                                    <span class="font-medium">{{ ucwords(str_replace('_', ' ', $selectedApplication->employment_status)) }}</span>
                                </div>
                                <div class="flex justify-between py-2 border-b border-gray-200">
                                    <span class="text-gray-600">Monthly Income:</span>
                                    <span class="font-medium">TSh {{ number_format($selectedApplication->total_monthly_income) }}</span>
                                </div>
                                @if($selectedApplication->debt_to_income_ratio)
                                    <div class="flex justify-between py-2 border-b border-gray-200">
                                        <span class="text-gray-600">DSR:</span>
                                        <span class="font-medium {{ $selectedApplication->debt_to_income_ratio <= 30 ? 'text-green-600' : ($selectedApplication->debt_to_income_ratio <= 40 ? 'text-yellow-600' : 'text-red-600') }}">
                                            {{ number_format($selectedApplication->debt_to_income_ratio, 1) }}%
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Loan Information -->
                <div class="lg:col-span-1">
                    <div class="bg-gray-50 rounded-xl p-6">
                        <h4 class="text-lg font-semibold text-gray-900 mb-4">Loan Information</h4>
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between py-2 border-b border-gray-200">
                                <span class="text-gray-600">Application #:</span>
                                <span class="font-medium">{{ $selectedApplication->application_number }}</span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-200">
                                <span class="text-gray-600">Requested Amount:</span>
                                <span class="font-medium text-lg">TSh {{ number_format($selectedApplication->requested_amount) }}</span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-200">
                                <span class="text-gray-600">Tenure:</span>
                                <span class="font-medium">{{ $selectedApplication->requested_tenure_months }} months</span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-200">
                                <span class="text-gray-600">Purpose:</span>
                                <span class="font-medium">{{ ucwords(str_replace('_', ' ', $selectedApplication->loan_purpose ?? 'N/A')) }}</span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-200">
                                <span class="text-gray-600">Status:</span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @switch($selectedApplication->status)
                                        @case('submitted') bg-blue-100 text-blue-800 @break
                                        @case('under_review') bg-yellow-100 text-yellow-800 @break
                                        @case('approved') bg-green-100 text-green-800 @break
                                        @case('rejected') bg-red-100 text-red-800 @break
                                        @case('disbursed') bg-purple-100 text-purple-800 @break
                                        @default bg-gray-100 text-gray-800
                                    @endswitch">
                                    {{ ucwords(str_replace('_', ' ', $selectedApplication->status)) }}
                                </span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-200">
                                <span class="text-gray-600">Booking Status:</span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $selectedApplication->booking_status === 'booked' ? 'bg-green-100 text-green-800' : 'bg-orange-100 text-orange-800' }}">
                                    {{ ucwords($selectedApplication->booking_status) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Lender Information -->
                    @if($selectedApplication->lender)
                        <div class="bg-gray-50 rounded-xl p-6 mt-6">
                            <h4 class="text-lg font-semibold text-gray-900 mb-4">Lender Information</h4>
                            <div class="space-y-3 text-sm">
                                <div class="flex justify-between py-2 border-b border-gray-200">
                                    <span class="text-gray-600">Company:</span>
                                    <span class="font-medium">{{ $selectedApplication->lender->company_name }}</span>
                                </div>
                                <div class="flex justify-between py-2 border-b border-gray-200">
                                    <span class="text-gray-600">License:</span>
                                    <span class="font-medium">{{ $selectedApplication->lender->license_number ?? 'N/A' }}</span>
                                </div>
                                <div class="flex justify-between py-2 border-b border-gray-200">
                                    <span class="text-gray-600">Contact:</span>
                                    <span class="font-medium">{{ $selectedApplication->lender->email }}</span>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Commission & Timeline -->
                <div class="lg:col-span-1">
                    <!-- Commission Bills -->
                    @if($selectedApplication->commissionBills->count() > 0)
                        <div class="bg-gray-50 rounded-xl p-6 mb-6">
                            <h4 class="text-lg font-semibold text-gray-900 mb-4">Commission Bills</h4>
                            <div class="space-y-3">
                                @foreach($selectedApplication->commissionBills as $bill)
                                    <div class="bg-white rounded-lg p-3 border border-gray-200">
                                        <div class="flex items-center justify-between mb-2">
                                            <span class="text-sm font-medium text-gray-900">{{ $bill->bill_number }}</span>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                                                @if($bill->status === 'paid') bg-green-100 text-green-800
                                                @elseif($bill->status === 'overdue') bg-red-100 text-red-800
                                                @elseif($bill->status === 'sent') bg-blue-100 text-blue-800
                                                @else bg-yellow-100 text-yellow-800 @endif">
                                                {{ ucfirst($bill->status) }}
                                            </span>
                                        </div>
                                        <div class="text-sm text-gray-600">
                                            <div>Amount: TSh {{ number_format($bill->total_amount) }}</div>
                                            <div>Due: {{ $bill->due_date->format('M d, Y') }}</div>
                                            @if($bill->total_paid > 0)
                                                <div class="text-green-600">Paid: TSh {{ number_format($bill->total_paid) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Documents -->
                    @if($selectedApplication->documents->count() > 0)
                        <div class="bg-gray-50 rounded-xl p-6">
                            <h4 class="text-lg font-semibold text-gray-900 mb-4">Documents ({{ $selectedApplication->documents->count() }})</h4>
                            <div class="space-y-2">
                                @foreach($selectedApplication->documents as $document)
                                    <div class="flex items-center justify-between py-2 px-3 bg-white rounded-lg border border-gray-200">
                                        <div class="flex items-center space-x-2">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                            <span class="text-sm text-gray-900">{{ $document->document_type }}</span>
                                        </div>
                                        <button class="text-blue-600 hover:text-blue-800 text-sm">View</button>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Timeline -->
           
        </div>
    </div>
@endif

<!-- Bill Details Modal -->
@if($showBillDetailsModal && $selectedBillForView)
    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" wire:click.self="closeBillDetailsModal">
        <div class="relative top-10 mx-auto p-5 border w-11/12 max-w-5xl shadow-lg rounded-3xl bg-white">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-2xl font-bold text-gray-900">Bill Details - {{ $selectedBillForView->bill_number }}</h3>
                <button wire:click="closeBillDetailsModal" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Bill Information -->
                <div class="bg-gray-50 rounded-xl p-6">
                    <h4 class="text-lg font-semibold text-gray-900 mb-4">Bill Information</h4>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between py-2 border-b border-gray-200">
                            <span class="text-gray-600">Bill Number:</span>
                            <span class="font-medium">{{ $selectedBillForView->bill_number }}</span>
                        </div>
                        <div class="flex justify-between py-2 border-b border-gray-200">
                            <span class="text-gray-600">Application:</span>
                            <span class="font-medium">{{ $selectedBillForView->application->application_number }}</span>
                        </div>
                        <div class="flex justify-between py-2 border-b border-gray-200">
                            <span class="text-gray-600">Lender:</span>
                            <span class="font-medium">{{ $selectedBillForView->lender->company_name }}</span>
                        </div>
                        <div class="flex justify-between py-2 border-b border-gray-200">
                            <span class="text-gray-600">Loan Amount:</span>
                            <span class="font-medium">TSh {{ number_format($selectedBillForView->loan_amount) }}</span>
                        </div>
                        <div class="flex justify-between py-2 border-b border-gray-200">
                            <span class="text-gray-600">Commission Type:</span>
                            <span class="font-medium">{{ ucfirst($selectedBillForView->commission_type) }}</span>
                        </div>
                        @if($selectedBillForView->commission_type === 'percentage')
                            <div class="flex justify-between py-2 border-b border-gray-200">
                                <span class="text-gray-600">Commission Rate:</span>
                                <span class="font-medium">{{ $selectedBillForView->commission_rate }}%</span>
                            </div>
                        @endif
                        <div class="flex justify-between py-2 border-b border-gray-200">
                            <span class="text-gray-600">Commission Amount:</span>
                            <span class="font-medium">TSh {{ number_format($selectedBillForView->commission_amount) }}</span>
                        </div>
                        <div class="flex justify-between py-2 border-b border-gray-200">
                            <span class="text-gray-600">Tax Amount:</span>
                            <span class="font-medium">TSh {{ number_format($selectedBillForView->tax_amount) }}</span>
                        </div>
                        <div class="flex justify-between py-3 border-b-2 border-gray-300">
                            <span class="text-gray-900 font-semibold">Total Amount:</span>
                            <span class="font-bold text-lg">TSh {{ number_format($selectedBillForView->total_amount) }}</span>
                        </div>
                        <div class="flex justify-between py-2 border-b border-gray-200">
                            <span class="text-gray-600">Status:</span>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                @if($selectedBillForView->status === 'paid') bg-green-100 text-green-800
                                @elseif($selectedBillForView->status === 'overdue') bg-red-100 text-red-800
                                @elseif($selectedBillForView->status === 'sent') bg-blue-100 text-blue-800
                                @else bg-yellow-100 text-yellow-800 @endif">
                                {{ ucfirst($selectedBillForView->status) }}
                            </span>
                        </div>
                        <div class="flex justify-between py-2 border-b border-gray-200">
                            <span class="text-gray-600">Due Date:</span>
                            <span class="font-medium {{ $selectedBillForView->is_overdue ? 'text-red-600' : '' }}">
                                {{ $selectedBillForView->due_date->format('M d, Y') }}
                                @if($selectedBillForView->is_overdue)
                                    ({{ $selectedBillForView->days_overdue }} days overdue)
                                @endif
                            </span>
                        </div>
                        <div class="flex justify-between py-2">
                            <span class="text-gray-600">Created:</span>
                            <span class="font-medium">{{ $selectedBillForView->created_at->format('M d, Y H:i') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Payment History -->
                <div class="bg-gray-50 rounded-xl p-6">
                    <h4 class="text-lg font-semibold text-gray-900 mb-4">Payment History</h4>
                    
                    @if($selectedBillForView->payments->count() > 0)
                        <div class="space-y-3 mb-4">
                            @foreach($selectedBillForView->payments as $payment)
                                <div class="bg-white rounded-lg p-3 border border-gray-200">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-sm font-medium text-gray-900">TSh {{ number_format($payment->amount) }}</span>
                                        <span class="text-xs text-gray-500">{{ $payment->payment_date->format('M d, Y') }}</span>
                                    </div>
                                    <div class="text-xs text-gray-600">
                                        <div>Method: {{ ucwords(str_replace('_', ' ', $payment->payment_method)) }}</div>
                                        @if($payment->payment_reference)
                                            <div>Ref: {{ $payment->payment_reference }}</div>
                                        @endif
                                        @if($payment->notes)
                                            <div class="mt-1">{{ $payment->notes }}</div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <!-- Payment Summary -->
                        <div class="bg-white rounded-lg p-4 border-2 border-gray-300">
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Total Paid:</span>
                                    <span class="font-medium text-green-600">TSh {{ number_format($selectedBillForView->total_paid) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Balance:</span>
                                    <span class="font-medium {{ $selectedBillForView->balance > 0 ? 'text-red-600' : 'text-green-600' }}">
                                        TSh {{ number_format($selectedBillForView->balance) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            <h4 class="text-sm font-medium text-gray-900 mb-1">No Payments Recorded</h4>
                            <p class="text-sm text-gray-500">No payments have been recorded for this bill yet.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Bill Notes -->
            @if($selectedBillForView->notes)
                <div class="mt-6 bg-gray-50 rounded-xl p-6">
                    <h4 class="text-lg font-semibold text-gray-900 mb-2">Notes</h4>
                    <p class="text-sm text-gray-700">{{ $selectedBillForView->notes }}</p>
                </div>
            @endif

            <!-- Actions -->
            <div class="mt-6 flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    @if($selectedBillForView->status !== 'paid')
                        <button wire:click="openPaymentModal({{ $selectedBillForView->id }})" 
                                class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg text-sm font-medium hover:bg-green-700 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            Record Payment
                        </button>
                    @endif
                    
                    @if($selectedBillForView->status === 'pending')
                        <button wire:click="sendBillNotification({{ $selectedBillForView->id }})" 
                                class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            Send Notification
                        </button>
                    @endif
                </div>
                
                <div class="flex items-center space-x-3">
                    <button wire:click="downloadBillPdf({{ $selectedBillForView->id }})" 
                            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Download PDF
                    </button>
                    
                    @if(in_array($selectedBillForView->status, ['pending', 'sent']) && $selectedBillForView->payments()->count() === 0)
                        <button wire:click="cancelBill({{ $selectedBillForView->id }})" 
                                onclick="return confirm('Are you sure you want to cancel this bill?')"
                                class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg text-sm font-medium hover:bg-red-700 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Cancel Bill
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endif




</div>
