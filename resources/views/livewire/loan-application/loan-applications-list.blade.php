<div>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
            <div class="mb-6 lg:mb-0">
                <h1 class="text-4xl font-bold text-gray-900 mb-2">My Loan Applications</h1>
                <p class="text-gray-600 text-lg">Track and manage your loan applications</p>
                <div class="mt-3 flex items-center space-x-4 text-sm text-gray-500">
                    <span class="flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Last updated: {{ now()->format('M d, Y H:i') }}
                    </span>
                    <span class="flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        {{ $applications->total() }} total applications
                    </span>
                </div>
            </div>
            <div class="flex flex-col sm:flex-row items-start sm:items-center space-y-3 sm:space-y-0 sm:space-x-3">
                <a href="{{ route('loan-application.create') }}" 
                   class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-red-600 to-red-700 text-white font-bold rounded-lg hover:from-red-700 hover:to-red-800 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    New Application
                </a>
                <!-- <button wire:click="exportApplications" 
                        class="inline-flex items-center px-4 py-3 bg-white text-gray-700 font-medium rounded-lg border border-gray-300 hover:bg-gray-50 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Export PDF
                </button> -->
            </div>
        </div>
    </div>

    <!-- Flash Messages -->
    @if (session()->has('message'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg" role="alert">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                {{ session('message') }}
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg" role="alert">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                {{ session('error') }}
            </div>
        </div>
    @endif

    <!-- Status Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Applications</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $applications->total() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Under Review</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $statusCounts['under_review'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Approved</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $statusCounts['approved'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Disbursed</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $statusCounts['disbursed'] ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 mb-6">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between space-y-4 lg:space-y-0">
            <!-- Search -->
            <div class="flex-1 max-w-md">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input wire:model.live.debounce.300ms="search" 
                           type="text" 
                           class="block w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 placeholder-gray-500 text-gray-900 text-sm transition-all duration-200" 
                           placeholder="Search applications...">
                </div>
            </div>

            <!-- Filters -->
            <div class="flex items-center space-x-4">
                <select wire:model.live="statusFilter" 
                        class="bg-gray-50 border border-gray-200 rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500">
                    <option value="all">All Status</option>
                    <option value="draft">Draft</option>
                    <option value="submitted">Submitted</option>
                    <option value="under_review">Under Review</option>
                    <option value="approved">Approved</option>
                    <option value="rejected">Rejected</option>
                    <option value="disbursed">Disbursed</option>
                    <option value="cancelled">Cancelled</option>
                </select>

                <select wire:model.live="sortBy" 
                        class="bg-gray-50 border border-gray-200 rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500">
                    <option value="created_at">Date Created</option>
                    <option value="requested_amount">Amount</option>
                    <option value="status">Status</option>
                    <option value="application_number">App Number</option>
                </select>

                <button wire:click="toggleSortDirection" 
                        class="p-3 bg-gray-50 border border-gray-200 rounded-lg hover:bg-gray-100 transition-colors">
                    <svg class="w-4 h-4 text-gray-600 {{ $sortDirection === 'desc' ? 'transform rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"/>
                    </svg>
                </button>

                @if($search || $statusFilter !== 'all')
                    <button wire:click="clearFilters" 
                            class="px-4 py-3 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors text-sm font-medium">
                        Clear Filters
                    </button>
                @endif
            </div>
        </div>
    </div>

    <!-- Loading Indicator -->
    <div wire:loading class="fixed top-4 left-1/2 transform -translate-x-1/2 bg-white px-4 py-2 rounded-lg shadow-lg border border-gray-200 z-50">
        <div class="flex items-center space-x-2">
            <div class="animate-spin rounded-full h-4 w-4 border-2 border-red-600 border-t-transparent"></div>
            <span class="text-sm text-gray-600">Loading...</span>
        </div>
    </div>

    <!-- Applications Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        @if($applications->count() > 0)
            <!-- Desktop Table -->
            <div class="hidden lg:block overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <button wire:click="sortBy('application_number')" class="flex items-center space-x-1 hover:text-gray-900">
                                    <span>Application</span>
                                    @if($sortBy === 'application_number')
                                        <svg class="w-3 h-3 {{ $sortDirection === 'desc' ? 'transform rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"/>
                                        </svg>
                                    @endif
                                </button>
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <button wire:click="sortBy('requested_amount')" class="flex items-center space-x-1 hover:text-gray-900">
                                    <span>Amount & Terms</span>
                                    @if($sortBy === 'requested_amount')
                                        <svg class="w-3 h-3 {{ $sortDirection === 'desc' ? 'transform rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"/>
                                        </svg>
                                    @endif
                                </button>
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <button wire:click="sortBy('status')" class="flex items-center space-x-1 hover:text-gray-900">
                                    <span>Status</span>
                                    @if($sortBy === 'status')
                                        <svg class="w-3 h-3 {{ $sortDirection === 'desc' ? 'transform rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"/>
                                        </svg>
                                    @endif
                                </button>
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Financial Info</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Lender</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <button wire:click="sortBy('created_at')" class="flex items-center space-x-1 hover:text-gray-900">
                                    <span>Dates</span>
                                    @if($sortBy === 'created_at')
                                        <svg class="w-3 h-3 {{ $sortDirection === 'desc' ? 'transform rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"/>
                                        </svg>
                                    @endif
                                </button>
                            </th>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($applications as $application)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <!-- Application Number & Applicant -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex flex-col">
                                        <div class="text-sm font-bold text-gray-900 mb-1">{{ $application->application_number }}</div>
                                        <div class="text-sm text-gray-600">{{ $application->first_name }} {{ $application->last_name }}</div>
                                        <div class="text-xs text-gray-500">{{ ucwords(str_replace('_', ' ', $application->employment_status)) }}</div>
                                    </div>
                                </td>

                                <!-- Amount & Terms -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex flex-col">
                                        <div class="text-lg font-bold text-red-600">TSh {{ number_format($application->requested_amount) }}</div>
                                        <div class="text-sm text-gray-600">{{ $application->requested_tenure_months }} months</div>
                                        @if($application->loan_purpose)
                                            <div class="text-xs text-gray-500">{{ ucwords(str_replace('_', ' ', $application->loan_purpose)) }}</div>
                                        @endif
                                    </div>
                                </td>

                                <!-- Status with Progress -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex flex-col">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $this->getStatusBadgeClass($application->status) }}">
                                            {{ ucwords(str_replace('_', ' ', $application->status)) }}
                                        </span>
                                        <div class="mt-2 w-full bg-gray-200 rounded-full h-1.5">
                                            <div class="bg-gradient-to-r from-red-600 to-red-700 h-1.5 rounded-full transition-all duration-500" 
                                                 style="width: {{ $this->getProgressPercentage($application->status) }}%"></div>
                                        </div>
                                        <div class="text-xs text-gray-500 mt-1">{{ $this->getProgressPercentage($application->status) }}% complete</div>
                                        @if($application->status === 'submitted' && !$application->lender_id)
                                            <span class="mt-1 inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                Ready for lender selection
                                            </span>
                                        @endif
                                    </div>
                                </td>

                                <!-- Financial Info -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex flex-col text-sm">
                                        <div class="text-gray-900">Income: <span class="font-medium text-green-600">TSh {{ number_format($application->total_monthly_income) }}</span></div>
                                        <div class="text-gray-600">Expenses: <span class="font-medium">TSh {{ number_format($application->monthly_expenses) }}</span></div>
                                        @if($application->debt_to_income_ratio)
                                            <div class="text-gray-600">DSR: 
                                                <span class="font-medium {{ $application->debt_to_income_ratio <= 30 ? 'text-green-600' : ($application->debt_to_income_ratio <= 40 ? 'text-yellow-600' : 'text-red-600') }}">
                                                    {{ number_format($application->debt_to_income_ratio, 1) }}%
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                </td>

                                <!-- Lender Info -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex flex-col">
                                        @if($application->lender)
                                            <div class="text-sm font-medium text-gray-900">{{ $application->lender->company_name }}</div>
                                            @if($application->loanProduct)
                                                <div class="text-sm text-blue-600">{{ $application->loanProduct->name }}</div>
                                            @endif
                                        @else
                                            <div class="text-sm text-gray-500 italic">No lender assigned</div>
                                        @endif
                                    </div>
                                </td>

                                <!-- Dates -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex flex-col text-sm">
                                        <div class="text-gray-900">Created: {{ $application->created_at->format('M d, Y') }}</div>
                                        <div class="text-xs text-gray-500">{{ $application->created_at->diffForHumans() }}</div>
                                        @if($application->submitted_at)
                                            <div class="text-blue-600 text-xs mt-1">Submitted: {{ $application->submitted_at->format('M d, Y') }}</div>
                                        @endif
                                    </div>
                                </td>

                                <!-- Actions -->
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-2">
                                        <!-- View Button -->
                                        <button wire:click="viewApplication({{ $application->id }})" 
                                                class="text-blue-600 hover:text-blue-900 p-2 rounded-lg hover:bg-blue-50 transition-colors" 
                                                title="View Details">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </button>

                                        <!-- Edit Button (Draft only) -->
                                     

                                        <!-- Lender Selection (Submitted without lender) -->
                                      

                                        <!-- Download PDF -->
                                        <button wire:click="downloadApplication({{ $application->id }})" 
                                                class="text-gray-600 hover:text-gray-900 p-2 rounded-lg hover:bg-gray-50 transition-colors" 
                                                title="Download PDF">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                        </button>

                                        <!-- Copy Application Number -->
                                 

                                        <!-- Cancel Button -->
                                        

                                        <!-- Delete Button (Draft only) -->
                                        @if($application->status === 'draft')
                                            <button wire:click="showDeleteConfirmation({{ $application->id }})" 
                                                    class="text-red-600 hover:text-red-900 p-2 rounded-lg hover:bg-red-50 transition-colors" 
                                                    title="Delete Draft">
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

            <!-- Mobile Card View -->
            <div class="lg:hidden">
                <div class="p-4 space-y-4">
                    @foreach($applications as $application)
                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                            <!-- Mobile Header -->
                            <div class="flex items-center justify-between mb-3">
                                <div>
                                    <h3 class="font-bold text-gray-900">{{ $application->application_number }}</h3>
                                    <p class="text-sm text-gray-600">{{ $application->first_name }} {{ $application->last_name }}</p>
                                </div>
                                <div class="text-right">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $this->getStatusBadgeClass($application->status) }}">
                                        {{ ucwords(str_replace('_', ' ', $application->status)) }}
                                    </span>
                                </div>
                            </div>

                            <!-- Mobile Content -->
                            <div class="grid grid-cols-2 gap-4 text-sm mb-4">
                                <div>
                                    <p class="text-gray-500">Amount</p>
                                    <p class="font-bold text-red-600">TSh {{ number_format($application->requested_amount) }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-500">Period</p>
                                    <p class="font-medium">{{ $application->requested_tenure_months }} months</p>
                                </div>
                                <div>
                                    <p class="text-gray-500">Income</p>
                                    <p class="font-medium text-green-600">TSh {{ number_format($application->total_monthly_income) }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-500">Created</p>
                                    <p class="font-medium">{{ $application->created_at->format('M d, Y') }}</p>
                                </div>
                            </div>

                            <!-- Mobile Progress -->
                            <div class="mb-4">
                                <div class="flex items-center justify-between mb-1">
                                    <span class="text-xs text-gray-500">Progress</span>
                                    <span class="text-xs text-gray-500">{{ $this->getProgressPercentage($application->status) }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-1.5">
                                    <div class="bg-gradient-to-r from-red-600 to-red-700 h-1.5 rounded-full" 
                                         style="width: {{ $this->getProgressPercentage($application->status) }}%"></div>
                                </div>
                            </div>

                            <!-- Mobile Actions -->
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-2">
                                    <button wire:click="viewApplication({{ $application->id }})" 
                                            class="text-blue-600 hover:text-blue-800 p-2 rounded-lg hover:bg-blue-50">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                    </button>
                                    @if($application->status === 'draft')
                                        <button wire:click="editApplication({{ $application->id }})" 
                                                class="text-gray-600 hover:text-gray-800 p-2 rounded-lg hover:bg-gray-50">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </button>
                                    @endif
                                    @if(in_array($application->status, ['draft', 'submitted']))
                                        <button wire:click="showCancelConfirmation({{ $application->id }})" 
                                                class="text-red-600 hover:text-red-800 p-2 rounded-lg hover:bg-red-50">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                                @if($application->status === 'submitted' && !$application->lender_id)
                                    <button wire:click="selectLenders({{ $application->id }})" 
                                           class="bg-blue-100 text-blue-700 px-3 py-1 rounded-lg text-sm font-medium hover:bg-blue-200">
                                        Choose Lender
                                    </button>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <!-- Empty State -->
            <div class="p-12 text-center">
                <div class="w-20 h-20 bg-gradient-to-br from-red-600 to-red-700 rounded-xl flex items-center justify-center mx-auto mb-6 shadow-lg">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">No applications found</h3>
                <p class="text-gray-500 mb-6">
                    @if($search || $statusFilter !== 'all')
                        No applications match your current filters. Try adjusting your search criteria.
                    @else
                        Start your loan application journey today. Check your eligibility first!
                    @endif
                </p>
                <div class="flex justify-center space-x-3">
                    @if($search || $statusFilter !== 'all')
                        <button wire:click="clearFilters" 
                                class="bg-gray-100 text-gray-700 px-6 py-3 rounded-lg font-medium hover:bg-gray-200 transition-colors">
                            Clear Filters
                        </button>
                    @endif
                    <a href="" 
                       class="bg-gradient-to-r from-red-600 to-red-700 text-white px-8 py-3 rounded-lg font-bold hover:from-red-700 hover:to-red-800 transition-all duration-200 shadow-lg">
                        Start Application
                    </a>
                </div>
            </div>
        @endif
    </div>

    <!-- Pagination -->
    @if($applications->hasPages())
        <div class="mt-8">
            {{ $applications->links() }}
        </div>
    @endif

    <!-- Toast Notifications -->
    @if($this->showToast ?? false)
        <div class="fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 transform transition-transform duration-300" 
             x-data="{ show: true }" 
             x-show="show" 
             x-init="setTimeout(() => show = false, 3000)">
            <div class="flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <span>{{ $this->toastMessage ?? 'Action completed successfully!' }}</span>
            </div>
        </div>
    @endif

    <!-- Cancel Confirmation Modal -->
    @if($showCancelModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" x-data="{ show: @entangle('showCancelModal') }">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <!-- Background overlay -->
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" 
                     x-show="show" 
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     wire:click="closeCancelModal"></div>

                <!-- Modal panel -->
                <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6"
                     x-show="show"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                    
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Cancel Application</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Are you sure you want to cancel application <strong>{{ $applicationToCancel ? $applicationToCancel->application_number : '' }}</strong>?
                                </p>
                                <p class="text-sm text-gray-500 mt-2">
                                    This action cannot be undone and you will need to create a new application if you wish to apply again.
                                </p>
                                @if($applicationToCancel && $applicationToCancel->status === 'under_review')
                                    <div class="mt-3 p-3 bg-yellow-50 rounded-lg">
                                        <p class="text-sm text-yellow-800">
                                            <strong>Note:</strong> This application is currently under review by {{ $applicationToCancel->lender->company_name ?? 'the lender' }}. 
                                            Cancelling now may affect your relationship with the lender.
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                        <button wire:click="confirmCancel" 
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                            <span wire:loading.remove wire:target="confirmCancel">Yes, Cancel Application</span>
                            <span wire:loading wire:target="confirmCancel" class="flex items-center">
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Cancelling...
                            </span>
                        </button>
                        <button wire:click="closeCancelModal" 
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">
                            Keep Application
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Delete Confirmation Modal -->
    @if($showDeleteModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" x-data="{ show: @entangle('showDeleteModal') }">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <!-- Background overlay -->
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" 
                     x-show="show" 
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     wire:click="closeDeleteModal"></div>

                <!-- Modal panel -->
                <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6"
                     x-show="show"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                    
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Delete Draft Application</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Are you sure you want to permanently delete draft application <strong>{{ $applicationToDelete ? $applicationToDelete->application_number : '' }}</strong>?
                                </p>
                                <p class="text-sm text-gray-500 mt-2">
                                    This action cannot be undone and all associated documents will be permanently removed.
                                </p>
                                @if($applicationToDelete && $applicationToDelete->documents && $applicationToDelete->documents->count() > 0)
                                    <div class="mt-3 p-3 bg-yellow-50 rounded-lg">
                                        <p class="text-sm text-yellow-800">
                                            <strong>{{ $applicationToDelete->documents->count() }} document(s)</strong> will also be permanently deleted.
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                        <button wire:click="confirmDelete" 
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                            <span wire:loading.remove wire:target="confirmDelete">Yes, Delete Forever</span>
                            <span wire:loading wire:target="confirmDelete" class="flex items-center">
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Deleting...
                            </span>
                        </button>
                        <button wire:click="closeDeleteModal" 
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">
                            Keep Draft
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Download Progress Modal -->
    @if($showDownloadModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" x-data="{ show: @entangle('showDownloadModal') }">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <!-- Background overlay -->
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" 
                     x-show="show" 
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"></div>

                <!-- Modal panel -->
                <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6"
                     x-show="show"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100">
                    
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="animate-spin h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Generating PDF</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Please wait while we generate your application PDF...
                                </p>
                                <div class="mt-4">
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-blue-600 h-2 rounded-full animate-pulse" style="width: 75%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<!-- JavaScript for clipboard functionality -->
<script>
    document.addEventListener('livewire:init', () => {
        // Listen for copy-to-clipboard events
        Livewire.on('copy-to-clipboard', (event) => {
            const text = event.text;
            
            if (navigator.clipboard && window.isSecureContext) {
                // Use modern clipboard API
                navigator.clipboard.writeText(text).then(() => {
                    console.log('Text copied to clipboard');
                }).catch(err => {
                    console.error('Failed to copy text: ', err);
                    fallbackCopyTextToClipboard(text);
                });
            } else {
                // Fallback for older browsers
                fallbackCopyTextToClipboard(text);
            }
        });

        // Auto-refresh for pending applications every 30 seconds
        const hasPendingApplications = @json($applications->whereIn('status', ['submitted', 'under_review'])->count() > 0);
        
        if (hasPendingApplications) {
            setInterval(() => {
                @this.call('refreshData');
            }, 30000); // 30 seconds
        }

        // Hide toast after delay
        Livewire.on('hide-toast-after-delay', () => {
            setTimeout(() => {
                @this.call('hideToast');
            }, 3000);
        });
    });

    // Fallback clipboard function for older browsers
    function fallbackCopyTextToClipboard(text) {
        const textArea = document.createElement("textarea");
        textArea.value = text;
        
        // Avoid scrolling to bottom
        textArea.style.top = "0";
        textArea.style.left = "0";
        textArea.style.position = "fixed";
        
        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();
        
        try {
            const successful = document.execCommand('copy');
            if (successful) {
                console.log('Fallback: Text copied to clipboard');
            } else {
                console.error('Fallback: Unable to copy text');
            }
        } catch (err) {
            console.error('Fallback: Copy command failed', err);
        }
        
        document.body.removeChild(textArea);
    }

    // Keyboard shortcuts
    document.addEventListener('keydown', function(event) {
        // Ctrl/Cmd + K to focus search
        if ((event.ctrlKey || event.metaKey) && event.key === 'k') {
            event.preventDefault();
            const searchInput = document.querySelector('input[wire\\:model\\.live\\.debounce\\.300ms="search"]');
            if (searchInput) {
                searchInput.focus();
                searchInput.select();
            }
        }
        
        // Escape to clear search
        if (event.key === 'Escape') {
            const searchInput = document.querySelector('input[wire\\:model\\.live\\.debounce\\.300ms="search"]');
            if (searchInput && searchInput === document.activeElement) {
                @this.call('clearFilters');
                searchInput.blur();
            }
        }

        // Ctrl/Cmd + R to refresh
        if ((event.ctrlKey || event.metaKey) && event.key === 'r') {
            event.preventDefault();
            @this.call('refreshData');
        }
    });

    // Add loading indicator for better UX
    document.addEventListener('livewire:init', () => {
        Livewire.hook('morph.updating', () => {
            // Show loading state
            document.body.style.cursor = 'wait';
        });

        Livewire.hook('morph.updated', () => {
            // Hide loading state
            document.body.style.cursor = 'default';
        });
    });
</script>

<style>
    /* Custom styles for better visual hierarchy */
    .bg-red-600 { background-color: #dc2626; }
    .bg-red-700 { background-color: #b91c1c; }
    .text-red-600 { color: #dc2626; }
    .text-red-700 { color: #b91c1c; }
    .border-red-500 { border-color: #ef4444; }
    .ring-red-500 { --tw-ring-color: #ef4444; }
    .focus\:ring-red-500:focus { --tw-ring-color: #ef4444; }
    .focus\:border-red-500:focus { border-color: #ef4444; }
    .hover\:from-red-700:hover { --tw-gradient-from: #b91c1c; }
    .hover\:to-red-800:hover { --tw-gradient-to: #991b1b; }
    
    /* Loading animation */
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
    
    .animate-spin {
        animation: spin 1s linear infinite;
    }
    
    /* Smooth transitions */
    .transition-all {
        transition-property: all;
        transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
        transition-duration: 150ms;
    }
    
    .duration-200 {
        transition-duration: 200ms;
    }
    
    .duration-300 {
        transition-duration: 300ms;
    }
    
    /* Custom scrollbar for table */
    .overflow-x-auto::-webkit-scrollbar {
        height: 6px;
    }
    
    .overflow-x-auto::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 10px;
    }
    
    .overflow-x-auto::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }
    
    .overflow-x-auto::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }

    /* Enhanced hover effects */
    .hover\:shadow-lg:hover {
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }

    .hover\:-translate-y-0\.5:hover {
        transform: translateY(-2px);
    }

    /* Focus states for accessibility */
    .focus\:outline-none:focus {
        outline: none;
    }

    .focus\:ring-2:focus {
        --tw-ring-offset-shadow: var(--tw-ring-inset) 0 0 0 var(--tw-ring-offset-width) var(--tw-ring-offset-color);
        --tw-ring-shadow: var(--tw-ring-inset) 0 0 0 calc(2px + var(--tw-ring-offset-width)) var(--tw-ring-color);
        box-shadow: var(--tw-ring-offset-shadow), var(--tw-ring-shadow), var(--tw-shadow, 0 0 #0000);
    }

    /* Print styles */
    @media print {
        .no-print {
            display: none !important;
        }
        
        body {
            font-size: 12pt;
            line-height: 1.4;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        th, td {
            border: 1px solid #000;
            padding: 4pt;
            text-align: left;
        }
        
        th {
            background-color: #f0f0f0 !important;
            font-weight: bold;
        }
    }

    /* Mobile optimizations */
    @media (max-width: 640px) {
        .text-4xl {
            font-size: 2rem;
            line-height: 2.5rem;
        }
        
        .px-6 {
            padding-left: 1rem;
            padding-right: 1rem;
        }
        
        .py-4 {
            padding-top: 0.75rem;
            padding-bottom: 0.75rem;
        }
    }

    /* Dark mode support (optional) */
    @media (prefers-color-scheme: dark) {
        .dark\:bg-gray-800 {
            background-color: #1f2937;
        }
        
        .dark\:text-white {
            color: #ffffff;
        }
        
        .dark\:border-gray-600 {
            border-color: #4b5563;
        }
    }
</style>

</div>