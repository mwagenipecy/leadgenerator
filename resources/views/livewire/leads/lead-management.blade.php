<div>
<div>
{{-- SECTION 1: HEADER AND STATS --}}
<div class="min-h-screen bg-gray-50">
    {{-- Header Section --}}
    <div class="bg-white border-b border-gray-200 sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center space-x-4">
                    <h1 class="text-2xl font-bold text-black">Lead Management</h1>
                    <span class="text-sm text-gray-500">{{ Auth::user()->lender->company_name ?? 'Lender Portal' }}</span>
                </div>
                <div class="flex items-center space-x-4">
                    <!-- Quick Stats -->
                    <div class="hidden md:flex items-center space-x-6 text-sm">
                        <div class="text-center">
                            <div class="font-bold text-sidebar-green">{{ $stats['available_leads'] }}</div>
                            <div class="text-gray-500">Available</div>
                        </div>
                        <div class="text-center">
                            <div class="font-bold text-black">{{ $stats['my_leads'] }}</div>
                            <div class="text-gray-500">My Leads</div>
                        </div>
                        <div class="text-center">
                            <div class="font-bold text-sidebar-green">{{ $stats['pending_review'] }}</div>
                            <div class="text-gray-500">Pending</div>
                        </div>
                    </div>
                    
                    <!-- View Mode Toggle -->
                    <div class="flex items-center bg-gray-100 rounded-lg p-1">
                        <button wire:click="setViewMode('grid')" 
                                class="px-3 py-1 rounded-md text-sm {{ $viewMode === 'grid' ? 'bg-white shadow-sm' : '' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                            </svg>
                        </button>
                        <button wire:click="setViewMode('table')" 
                                class="px-3 py-1 rounded-md text-sm {{ $viewMode === 'table' ? 'bg-white shadow-sm' : '' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18M8 4v16"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        @if($currentStep === 'list')
            
        {{-- Dashboard Stats Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-sidebar-green-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-sidebar-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Available Leads</p>
                        <p class="text-2xl font-bold text-black">{{ number_format($stats['available_leads']) }}</p>
                        <p class="text-xs text-gray-400 mt-1">In the market</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-black bg-opacity-10 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">My Leads</p>
                        <p class="text-2xl font-bold text-black">{{ number_format($stats['my_leads']) }}</p>
                        <p class="text-xs text-gray-400 mt-1">Total acquired</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-sidebar-green-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-sidebar-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Pending Review</p>
                        <p class="text-2xl font-bold text-black">{{ number_format($stats['pending_review']) }}</p>
                        <p class="text-xs text-sidebar-green mt-1">Requires action</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-black bg-opacity-10 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Total Value</p>
                        <p class="text-2xl font-bold text-black">TSh {{ number_format($stats['total_value']/1000000, 1) }}M</p>
                        <p class="text-xs text-gray-400 mt-1">Approved loans</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Lead Type Tabs --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 mb-6">
            <div class="border-b border-gray-200">
                <nav class="flex space-x-8 px-6" aria-label="Tabs">
                    <button wire:click="setLeadTypeFilter('available')" 
                            class="py-4 px-1 text-sm font-medium border-b-2 {{ $leadTypeFilter === 'available' ? 'border-sidebar-green text-sidebar-green' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        Available Leads
                        <span class="ml-2 bg-sidebar-green-100 text-sidebar-green py-0.5 px-2 rounded-full text-xs">{{ $stats['available_leads'] }}</span>
                    </button>
                    <button wire:click="setLeadTypeFilter('booked')" 
                            class="py-4 px-1 text-sm font-medium border-b-2 {{ $leadTypeFilter === 'booked' ? 'border-sidebar-green text-sidebar-green' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        My Booked Leads
                        <span class="ml-2 bg-gray-100 text-gray-600 py-0.5 px-2 rounded-full text-xs">{{ $stats['my_leads'] }}</span>
                    </button>
                </nav>
            </div>

            {{-- Filters and Search Panel --}}
            <div class="p-6 border-b border-gray-100">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between space-y-4 lg:space-y-0">
                    <!-- Search -->
                    <div class="flex-1 max-w-md">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                            <input wire:model.live.debounce.300ms="search" type="text" 
                                   class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green text-sm transition-all duration-200" 
                                   placeholder="Search by application #, name...">
                        </div>
                    </div>

                    <!-- Quick Filters -->
                    <div class="flex flex-wrap items-center gap-3">
                        @if($leadTypeFilter === 'booked')
                            <!-- Status Filter for booked leads -->
                            <div class="relative">
                                <select wire:model.live="statusFilter" class="appearance-none bg-white border border-gray-300 rounded-xl px-4 py-3 pr-8 text-sm focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green cursor-pointer">
                                    <option value="all">All Status</option>
                                    <option value="submitted">Under Review</option>
                                    <option value="approved">Approved</option>
                                    <option value="rejected">Rejected</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </div>
                            </div>
                        @endif

                        <!-- Date Range Filter -->
                        <div class="relative">
                            <select wire:model.live="dateRange" class="appearance-none bg-white border border-gray-300 rounded-xl px-4 py-3 pr-8 text-sm focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green cursor-pointer">
                                <option value="all">All Time</option>
                                <option value="today">Today</option>
                                <option value="week">This Week</option>
                                <option value="month">This Month</option>
                                <option value="quarter">This Quarter</option>
                                <option value="year">This Year</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                        </div>

                        <!-- Sort Options -->
                        <div class="relative">
                            <select wire:model.live="sortBy" class="appearance-none bg-white border border-gray-300 rounded-xl px-4 py-3 pr-8 text-sm focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green cursor-pointer">
                                <option value="created_at">Latest First</option>
                                <option value="requested_amount">Amount (High to Low)</option>
                                <option value="credit_score">CRB Score</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                        </div>

                        <!-- Advanced Filters Toggle -->
                        <button wire:click="toggleFilters" 
                                class="inline-flex items-center px-4 py-3 border border-gray-300 rounded-xl text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:ring-2 focus:ring-sidebar-green transition-all duration-200 {{ $showFilters ? 'bg-sidebar-green-50 border-sidebar-green-300 text-sidebar-green-light' : '' }}">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                            </svg>
                            Filters
                        </button>

                        <!-- Export Button -->
                        <button wire:click="export"
                                class="inline-flex items-center px-4 py-3 bg-black text-white rounded-xl text-sm font-medium hover:bg-gray-800 focus:ring-2 focus:ring-sidebar-green transition-all duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Export CSV
                        </button>
                    </div>
                </div>
            </div>

            {{-- Advanced Filters Panel --}}
            @if($showFilters)
                <div class="p-6 bg-gray-50 border-t border-gray-100">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-4">
                        <!-- Amount Range Filter -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Loan Amount Range</label>
                            <select wire:model.live="amountRange" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-sidebar-green">
                                <option value="all">All Amounts</option>
                                <option value="under_100k">Under TSh 100K</option>
                                <option value="100k_500k">TSh 100K - 500K</option>
                                <option value="500k_1m">TSh 500K - 1M</option>
                                <option value="1m_5m">TSh 1M - 5M</option>
                                <option value="over_5m">Over TSh 5M</option>
                            </select>
                        </div>

                        <!-- CRB Score Range Filter -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">CRB Score Range</label>
                            <select wire:model.live="crbScoreRange" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-sidebar-green">
                                <option value="all">All Scores</option>
                                <option value="excellent">Excellent (750+)</option>
                                <option value="good">Good (650-749)</option>
                                <option value="fair">Fair (550-649)</option>
                                <option value="poor">Poor (Below 550)</option>
                            </select>
                        </div>
                    </div>

                    <!-- Filter Actions and Results Info -->
                    <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                        <div class="flex items-center space-x-4">
                            <button wire:click="clearFilters" 
                                    class="text-sm text-sidebar-green hover:text-sidebar-green-800 underline transition-colors">
                                Clear all filters
                            </button>
                            <div class="text-sm text-gray-500">
                                {{ $leads->total() }} lead{{ $leads->total() !== 1 ? 's' : '' }} found
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        {{-- Flash Messages --}}
        @if (session()->has('message'))
            <div class="mb-6 bg-sidebar-green-50 border border-sidebar-green-200 text-sidebar-green-light px-4 py-3 rounded-xl" role="alert">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    {{ session('message') }}
                </div>
            </div>
        @endif

        {{-- LEADS DISPLAY SECTION --}}

        {{-- GRID VIEW --}}
        @if($viewMode === 'grid')
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($leads as $lead)
                    @php
                        $application = $leadTypeFilter === 'available' ? $lead : $lead->application;
                        $isAvailable = $leadTypeFilter === 'available';
                    @endphp
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-all duration-200 overflow-hidden">
                        <!-- Card Header -->
                        <div class="p-6 pb-4">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex items-center space-x-3">
                                    <div class="h-12 w-12 rounded-full {{ $isAvailable ? 'bg-gradient-to-br from-sidebar-green-400 to-sidebar-green' : 'bg-gradient-to-br from-gray-400 to-gray-600' }} flex items-center justify-center">
                                        <span class="text-lg font-bold text-white">
                                            {{ substr($application->first_name, 0, 1) }}{{ substr($application->last_name, 0, 1) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="flex flex-col items-end space-y-2">
                                    @if($isAvailable)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-sidebar-green-100 text-sidebar-green-800">
                                            Available
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @switch($lead->status)
                                                @case('submitted') bg-sidebar-green-100 text-sidebar-green-800 @break
                                                @case('approved') bg-green-100 text-green-800 @break
                                                @case('rejected') bg-gray-100 text-gray-800 @break
                                                @default bg-gray-100 text-gray-800
                                            @endswitch">
                                            {{ ucwords(str_replace('_', ' ', $lead->status)) }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Applicant Info -->
                            <div class="mb-4">
                                <h3 class="text-lg font-semibold text-black">
                                    {{ $application->first_name }} {{ $application->last_name }}
                                </h3>
                                <p class="text-sm text-gray-600">{{ $application->application_number }}</p>
                                <p class="text-xs text-gray-500">Applied {{ $application->created_at->diffForHumans() }}</p>
                            </div>

                            <!-- Key Metrics -->
                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div class="text-center p-3 bg-gray-50 rounded-lg">
                                    <div class="text-xs text-gray-500 mb-1">Amount</div>
                                    <div class="text-sm font-bold text-black">
                                        TSh {{ number_format($application->requested_amount/1000) }}K
                                    </div>
                                </div>
                                <div class="text-center p-3 bg-gray-50 rounded-lg">
                                    <div class="text-xs text-gray-500 mb-1">CRB Score</div>
                                    @if($application->credit_score)
                                        <div class="text-sm font-bold {{ $application->credit_score >= 650 ? 'text-green-600' : ($application->credit_score >= 550 ? 'text-yellow-600' : 'text-sidebar-green') }}">
                                            {{ $application->credit_score }}
                                        </div>
                                    @else
                                        <div class="text-sm text-gray-400">N/A</div>
                                    @endif
                                </div>
                            </div>

                            <!-- Additional Info -->
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-500">Tenure:</span>
                                    <span class="font-medium text-black">{{ $application->requested_tenure_months }} months</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-500">Product:</span>
                                    <span class="font-medium text-xs text-black">{{ $application->loanProduct->name ?? 'N/A' }}</span>
                                </div>
                                @if($application->debt_to_income_ratio)
                                    <div class="flex justify-between">
                                        <span class="text-gray-500">DSR:</span>
                                        <span class="font-medium text-black">{{ number_format($application->debt_to_income_ratio, 1) }}%</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Card Actions -->
                        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
                            @if($isAvailable)
                                <div class="flex flex-wrap items-center justify-between gap-2">
                                    <button wire:click="viewLead({{ $lead->id }})" 
                                            class="inline-flex items-center px-3 py-1.5 bg-indigo-50 text-indigo-700 border border-indigo-200 rounded-lg text-xs font-medium hover:bg-indigo-100 transition-colors">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        View
                                    </button>
                                    
                                    <button wire:click="bookLead({{ $lead->id }})" 
                                            class="inline-flex items-center px-3 py-1.5 bg-sidebar-green text-white rounded-lg text-xs font-medium hover:bg-sidebar-green-light transition-all duration-200 shadow-sm">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                        </svg>
                                        Book Lead
                                    </button>
                                </div>
                            @else
                                <div class="flex flex-wrap items-center justify-between gap-2">
                                    <button wire:click="viewLead({{ $lead->id }})" 
                                            class="inline-flex items-center px-3 py-1.5 bg-indigo-50 text-indigo-700 border border-indigo-200 rounded-lg text-xs font-medium hover:bg-indigo-100 transition-colors">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        View
                                    </button>
                                    
                                    <div class="flex items-center gap-1.5">
                                        @if($lead->status === 'submitted')
                                            <button wire:click="processLead({{ $lead->id }}, 'approve')" 
                                                    class="inline-flex items-center px-3 py-1.5 bg-green-50 text-green-700 rounded-lg text-xs font-medium hover:bg-green-100 border border-green-200 transition-all duration-200"
                                                    title="Approve">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                </svg>
                                                Approve
                                            </button>
                                            <button wire:click="processLead({{ $lead->id }}, 'reject')" 
                                                    class="inline-flex items-center px-3 py-1.5 bg-red-50 text-red-700 rounded-lg text-xs font-medium hover:bg-red-100 border border-red-200 transition-all duration-200"
                                                    title="Reject">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                                Reject
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="col-span-full">
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No leads found</h3>
                            <p class="mt-1 text-sm text-gray-500">
                                @if($leadTypeFilter === 'available')
                                    No available leads match your current filters.
                                @else
                                    You haven't booked any leads yet. Check the available leads tab.
                                @endif
                            </p>
                        </div>
                    </div>
                @endforelse
            </div>

        {{-- TABLE VIEW --}}
        @elseif($viewMode === 'table')
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition-colors" 
                                    wire:click="sortBy('application_number')">
                                    <div class="flex items-center space-x-1">
                                        <span>Application</span>
                                        @if($sortBy === 'application_number')
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $sortDirection === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}"/>
                                            </svg>
                                        @endif
                                    </div>
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Applicant</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition-colors" 
                                    wire:click="sortBy('requested_amount')">
                                    <div class="flex items-center space-x-1">
                                        <span>Amount</span>
                                        @if($sortBy === 'requested_amount')
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $sortDirection === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}"/>
                                            </svg>
                                        @endif
                                    </div>
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition-colors" 
                                    wire:click="sortBy('credit_score')">
                                    <div class="flex items-center space-x-1">
                                        <span>CRB Score</span>
                                        @if($sortBy === 'credit_score')
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $sortDirection === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}"/>
                                            </svg>
                                        @endif
                                    </div>
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                @if($leadTypeFilter === 'booked')
                                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                @endif
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Applied</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($leads as $lead)
                                @php
                                    $application = $leadTypeFilter === 'available' ? $lead : $lead->application;
                                    $isAvailable = $leadTypeFilter === 'available';
                                @endphp
                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-black">{{ $application->application_number }}</div>
                                        <div class="text-sm text-gray-500">{{ $application->requested_tenure_months }} months</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <div class="h-10 w-10 rounded-full {{ $isAvailable ? 'bg-gradient-to-br from-sidebar-green-400 to-sidebar-green' : 'bg-gradient-to-br from-gray-400 to-gray-600' }} flex items-center justify-center">
                                                    <span class="text-sm font-bold text-white">
                                                        {{ substr($application->first_name, 0, 1) }}{{ substr($application->last_name, 0, 1) }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-black">
                                                    {{ $application->first_name }} {{ $application->last_name }}
                                                </div>
                                                <div class="text-sm text-gray-500">{{ $application->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-black">TSh {{ number_format($application->requested_amount) }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($application->credit_score)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                {{ $application->credit_score >= 650 ? 'bg-green-100 text-green-800' : 
                                                   ($application->credit_score >= 550 ? 'bg-yellow-100 text-yellow-800' : 'bg-sidebar-green-100 text-sidebar-green-800') }}">
                                                {{ $application->credit_score }}
                                            </span>
                                        @else
                                            <span class="text-gray-400 text-sm">N/A</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-black">{{ $application->loanProduct->name ?? 'N/A' }}</div>
                                    </td>
                                    @if($leadTypeFilter === 'booked')
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                @switch($lead->status)
                                                    @case('submitted') bg-sidebar-green-100 text-sidebar-green-800 @break
                                                    @case('approved') bg-green-100 text-green-800 @break
                                                    @case('rejected') bg-gray-100 text-gray-800 @break
                                                    @default bg-gray-100 text-gray-800
                                                @endswitch">
                                                {{ ucwords(str_replace('_', ' ', $lead->status)) }}
                                            </span>
                                        </td>
                                    @endif
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-black">{{ $application->created_at->format('M d, Y') }}</div>
                                        <div class="text-xs text-gray-500">{{ $application->created_at->diffForHumans() }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex items-center gap-1.5">
                                            <button wire:click="viewLead({{ $isAvailable ? $lead->id : $lead->id }})" 
                                                    class="inline-flex items-center px-2.5 py-1.5 bg-indigo-50 text-indigo-700 rounded-lg text-xs font-medium hover:bg-indigo-100 border border-indigo-200 transition-all duration-200"
                                                    title="View Details">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                                View
                                            </button>
                                            
                                            @if($isAvailable)
                                                <button wire:click="bookLead({{ $lead->id }})" 
                                                        class="inline-flex items-center px-2.5 py-1.5 bg-sidebar-green text-white rounded-lg text-xs font-medium hover:bg-sidebar-green-light transition-all duration-200 shadow-sm">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                                    </svg>
                                                    Book
                                                </button>
                                            @else
                                                @if($lead->status === 'submitted')
                                                    <button wire:click="processLead({{ $lead->id }}, 'approve')" 
                                                            class="inline-flex items-center px-2.5 py-1.5 bg-green-50 text-green-700 rounded-lg text-xs font-medium hover:bg-green-100 border border-green-200 transition-all duration-200"
                                                            title="Approve">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                        </svg>
                                                        Approve
                                                    </button>
                                                    <button wire:click="processLead({{ $lead->id }}, 'reject')" 
                                                            class="inline-flex items-center px-2.5 py-1.5 bg-red-50 text-red-700 rounded-lg text-xs font-medium hover:bg-red-100 border border-red-200 transition-all duration-200"
                                                            title="Reject">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                        </svg>
                                                        Reject
                                                    </button>
                                                @endif
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ $leadTypeFilter === 'booked' ? '8' : '7' }}" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center">
                                            <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                            </svg>
                                            <h3 class="mt-2 text-sm font-medium text-gray-900">No leads found</h3>
                                            <p class="mt-1 text-sm text-gray-500">
                                                @if($leadTypeFilter === 'available')
                                                    No available leads match your current filters.
                                                @else
                                                    You haven't booked any leads yet. Check the available leads tab.
                                                @endif
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <!-- Pagination -->
        <div class="mt-8">
            {{ $leads->links() }}
        </div>

        {{-- LEAD DETAIL VIEW --}}
        @elseif($currentStep === 'view' && $selectedLead)
            @php
                $application = $leadTypeFilter === 'available' ? $selectedLead : $selectedLead->application;
                $isAvailable = $leadTypeFilter === 'available';
            @endphp
            
            <div class="max-w-7xl mx-auto">
                <!-- Header with Back Button -->
                <div class="flex items-center justify-between mb-8">
                    <div class="flex items-center space-x-4">
                        <button wire:click="backToList" 
                                class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Back to Leads
                        </button>
                        <div>
                            <h1 class="text-2xl font-bold text-black">Lead Details</h1>
                            <p class="text-sm text-gray-600">{{ $application->application_number }}</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-3">
                        @if($isAvailable)
                            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-sidebar-green-100 text-sidebar-green-800">
                                Available
                            </span>
                        @else
                            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium
                                @switch($selectedLead->status)
                                    @case('submitted') bg-sidebar-green-100 text-sidebar-green-800 @break
                                    @case('approved') bg-green-100 text-green-800 @break
                                    @case('rejected') bg-gray-100 text-gray-800 @break
                                    @default bg-gray-100 text-gray-800
                                @endswitch">
                                {{ ucwords(str_replace('_', ' ', $selectedLead->status)) }}
                            </span>
                        @endif

                        <!-- Quick Actions -->
                        <div class="flex flex-wrap items-center gap-2">
                            @if($isAvailable)
                                <button wire:click="bookLead({{ $selectedLead->id }})" 
                                        class="inline-flex items-center px-4 py-2 bg-sidebar-green text-white rounded-lg text-sm font-medium hover:bg-sidebar-green-light transition-colors shadow-sm">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    </svg>
                                    Book This Lead
                                </button>
                            @else
                                @if($selectedLead->status === 'submitted')
                                    <button wire:click="processLead({{ $selectedLead->id }}, 'approve')" 
                                            class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg text-sm font-medium hover:bg-green-700 transition-colors shadow-sm">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        Approve
                                    </button>
                                    <button wire:click="processLead({{ $selectedLead->id }}, 'reject')" 
                                            class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg text-sm font-medium hover:bg-red-700 transition-colors shadow-sm">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                        Reject
                                    </button>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Applicant Summary Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-8">
                    <div class="flex items-start justify-between">
                        <div class="flex items-center space-x-6">
                            <div class="h-20 w-20 rounded-full {{ $isAvailable ? 'bg-gradient-to-br from-sidebar-green-400 to-sidebar-green' : 'bg-gradient-to-br from-gray-400 to-gray-600' }} flex items-center justify-center">
                                <span class="text-2xl font-bold text-white">
                                    {{ substr($application->first_name, 0, 1) }}{{ substr($application->last_name, 0, 1) }}
                                </span>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-black">
                                    {{ $application->first_name }} {{ $application->last_name }}
                                </h2>
                                <div class="mt-2 space-y-1">
                                    <p class="text-gray-600 flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                        </svg>
                                        {{ $application->email }}
                                    </p>
                                    <p class="text-gray-600 flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                        </svg>
                                        {{ $application->phone_number }}
                                    </p>
                                    <p class="text-gray-600 flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/>
                                        </svg>
                                        National ID: {{ $application->national_id }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Key Metrics -->
                        <div class="grid grid-cols-3 gap-6 text-center">
                            <div class="p-4 bg-sidebar-green-50 rounded-lg">
                                <div class="text-2xl font-bold text-sidebar-green">TSh {{ number_format($application->requested_amount/1000000, 1) }}M</div>
                                <div class="text-sm text-sidebar-green-light">Requested Amount</div>
                            </div>
                            <div class="p-4 bg-gray-50 rounded-lg">
                                <div class="text-2xl font-bold text-black">TSh {{ number_format($application->total_monthly_income/1000, 0) }}K</div>
                                <div class="text-sm text-gray-700">Monthly Income</div>
                            </div>
                            <div class="p-4 {{ $application->credit_score >= 650 ? 'bg-green-50' : ($application->credit_score >= 550 ? 'bg-yellow-50' : 'bg-sidebar-green-50') }} rounded-lg">
                                <div class="text-2xl font-bold {{ $application->credit_score >= 650 ? 'text-green-600' : ($application->credit_score >= 550 ? 'text-yellow-600' : 'text-sidebar-green') }}">
                                    {{ $application->credit_score ?? 'N/A' }}
                                </div>
                                <div class="text-sm {{ $application->credit_score >= 650 ? 'text-green-700' : ($application->credit_score >= 550 ? 'text-yellow-700' : 'text-sidebar-green-light') }}">CRB Score</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Detailed Information Tabs -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                            <!-- Loan Details -->
                            <div>
                                <h3 class="text-lg font-semibold text-black mb-4">Loan Application Details</h3>
                                <div class="bg-gray-50 rounded-lg p-6 space-y-4">
                                    <div class="flex justify-between items-center py-3 border-b border-gray-200">
                                        <span class="text-sm font-medium text-gray-600">Application Number</span>
                                        <span class="text-sm font-bold text-black">{{ $application->application_number }}</span>
                                    </div>
                                    <div class="flex justify-between items-center py-3 border-b border-gray-200">
                                        <span class="text-sm font-medium text-gray-600">Loan Product</span>
                                        <span class="text-sm font-bold text-black">{{ $application->loanProduct->name ?? 'N/A' }}</span>
                                    </div>
                                    <div class="flex justify-between items-center py-3 border-b border-gray-200">
                                        <span class="text-sm font-medium text-gray-600">Requested Amount</span>
                                        <span class="text-sm font-bold text-black">TSh {{ number_format($application->requested_amount) }}</span>
                                    </div>
                                    <div class="flex justify-between items-center py-3 border-b border-gray-200">
                                        <span class="text-sm font-medium text-gray-600">Tenure</span>
                                        <span class="text-sm font-bold text-black">{{ $application->requested_tenure_months }} months</span>
                                    </div>
                                    <div class="flex justify-between items-center py-3 border-b border-gray-200">
                                        <span class="text-sm font-medium text-gray-600">Purpose</span>
                                        <span class="text-sm font-bold text-black">{{ ucwords(str_replace('_', ' ', $application->loan_purpose ?? 'N/A')) }}</span>
                                    </div>
                                    <div class="flex justify-between items-center py-3">
                                        <span class="text-sm font-medium text-gray-600">Application Date</span>
                                        <span class="text-sm font-bold text-black">{{ $application->created_at->format('M d, Y H:i') }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Financial Profile -->
                            <div>
                                <h3 class="text-lg font-semibold text-black mb-4">Financial Profile</h3>
                                <div class="bg-gray-50 rounded-lg p-6 space-y-4">
                                    <div class="flex justify-between items-center py-3 border-b border-gray-200">
                                        <span class="text-sm font-medium text-gray-600">Monthly Income</span>
                                        <span class="text-sm font-bold text-black">TSh {{ number_format($application->total_monthly_income) }}</span>
                                    </div>
                                    <div class="flex justify-between items-center py-3 border-b border-gray-200">
                                        <span class="text-sm font-medium text-gray-600">Employment Status</span>
                                        <span class="text-sm font-bold text-black">{{ ucwords(str_replace('_', ' ', $application->employment_status)) }}</span>
                                    </div>
                                    <div class="flex justify-between items-center py-3 border-b border-gray-200">
                                        <span class="text-sm font-medium text-gray-600">DSR</span>
                                        @if($application->debt_to_income_ratio)
                                            <span class="text-sm font-bold {{ $application->debt_to_income_ratio <= 30 ? 'text-green-600' : ($application->debt_to_income_ratio <= 40 ? 'text-yellow-600' : 'text-sidebar-green') }}">
                                                {{ number_format($application->debt_to_income_ratio, 1) }}%
                                            </span>
                                        @else
                                            <span class="text-sm text-gray-400">N/A</span>
                                        @endif
                                    </div>
                                    <div class="flex justify-between items-center py-3 border-b border-gray-200">
                                        <span class="text-sm font-medium text-gray-600">Credit Score</span>
                                        <span class="text-sm font-bold text-black">{{ $application->credit_score ?? 'N/A' }}</span>
                                    </div>
                                    <div class="flex justify-between items-center py-3">
                                        <span class="text-sm font-medium text-gray-600">Bank</span>
                                        <span class="text-sm font-bold text-black">{{ $application->bank_name ?? 'N/A' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Additional Information -->
                        <div class="mt-8">
                            <h3 class="text-lg font-semibold text-black mb-4">Additional Information</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <h4 class="font-medium text-black mb-2">Employment Details</h4>
                                    <div class="space-y-2 text-sm">
                                        <div><span class="text-gray-600">Employer:</span> <span class="font-medium text-black">{{ $application->employer_name ?? 'N/A' }}</span></div>
                                        <div><span class="text-gray-600">Job Title:</span> <span class="font-medium text-black">{{ $application->job_title ?? 'N/A' }}</span></div>
                                        <div><span class="text-gray-600">Experience:</span> <span class="font-medium text-black">{{ $application->months_with_current_employer ?? 'N/A' }} months</span></div>
                                    </div>
                                </div>
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <h4 class="font-medium text-black mb-2">Contact Information</h4>
                                    <div class="space-y-2 text-sm">
                                        <div><span class="text-gray-600">Address:</span> <span class="font-medium text-black">{{ $application->current_address ?? 'N/A' }}</span></div>
                                        <div><span class="text-gray-600">City:</span> <span class="font-medium text-black">{{ $application->current_city ?? 'N/A' }}</span></div>
                                        <div><span class="text-gray-600">Region:</span> <span class="font-medium text-black">{{ $application->current_region ?? 'N/A' }}</span></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Panel for Booked Leads -->
                @if(!$isAvailable && $selectedLead->status === 'submitted')
                    <div class="mt-8 bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <h3 class="text-lg font-semibold text-black mb-4">Process Lead</h3>
                        
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                            <!-- Approval Section -->
                            <div class="space-y-4">
                                <h4 class="text-md font-medium text-green-700">Approve Lead</h4>
                                <div class="bg-green-50 rounded-lg p-4 space-y-4">
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Offer Amount</label>
                                            <input type="number" wire:model="offerAmount" 
                                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500"
                                                   placeholder="{{ $application->requested_amount }}">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Interest Rate (%)</label>
                                            <input type="number" step="0.1" wire:model="offerInterestRate" 
                                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500"
                                                   placeholder="Enter rate">
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Tenure (months)</label>
                                        <input type="number" wire:model="offerTenure" 
                                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500"
                                               placeholder="{{ $application->requested_tenure_months }}">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                                        <textarea wire:model="leadNotes" rows="3" 
                                                  class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500"
                                                  placeholder="Add approval notes..."></textarea>
                                    </div>
                                    <button wire:click="processLead({{ $selectedLead->id }}, 'approve')" 
                                            class="w-full inline-flex justify-center items-center px-4 py-2 bg-green-600 text-white rounded-lg text-sm font-medium hover:bg-green-700 transition-colors shadow-sm">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        Approve Lead
                                    </button>
                                </div>
                            </div>

                            <!-- Rejection Section -->
                            <div class="space-y-4">
                                <h4 class="text-md font-medium text-sidebar-green-light">Reject Lead</h4>
                                <div class="bg-sidebar-green-50 rounded-lg p-4 space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Rejection Reason</label>
                                        <textarea wire:model="leadNotes" rows="6" 
                                                  class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-sidebar-green"
                                                  placeholder="Please provide reason for rejection..."></textarea>
                                    </div>
                                    <button wire:click="processLead({{ $selectedLead->id }}, 'reject')" 
                                            class="w-full inline-flex justify-center items-center px-4 py-2 bg-red-600 text-white rounded-lg text-sm font-medium hover:bg-red-700 transition-colors shadow-sm">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                        Reject Lead
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        @endif
    </div>
</div>

<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('show-alert', (event) => {
            const alertData = event[0] || event;
            const alertType = alertData.type || 'info';
            const message = alertData.message || 'Something happened';
            
            // You can integrate this with your preferred notification system
            // For now, using a simple alert
            if (alertType === 'success') {
                alert('✅ ' + message);
            } else if (alertType === 'error') {
                alert('❌ ' + message);
            } else if (alertType === 'warning') {
                alert('⚠️ ' + message);
            } else {
                alert('ℹ️ ' + message);
            }
        });
    });
</script>

</div>
