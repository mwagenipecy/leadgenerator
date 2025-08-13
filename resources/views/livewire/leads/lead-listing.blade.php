<div>
<div>
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
                            <div class="font-bold text-red-600">{{ $stats['available_leads'] }}</div>
                            <div class="text-gray-500">Available</div>
                        </div>
                        <div class="text-center">
                            <div class="font-bold text-black">{{ $stats['my_leads'] }}</div>
                            <div class="text-gray-500">My Leads</div>
                        </div>
                        <div class="text-center">
                            <div class="font-bold text-red-600">{{ $stats['pending_review'] }}</div>
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
        {{-- Dashboard Stats Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                        <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Pending Review</p>
                        <p class="text-2xl font-bold text-black">{{ number_format($stats['pending_review']) }}</p>
                        <p class="text-xs text-red-600 mt-1">Requires action</p>
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
                            class="py-4 px-1 text-sm font-medium border-b-2 {{ $leadTypeFilter === 'available' ? 'border-red-600 text-red-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        Available Leads
                        <span class="ml-2 bg-red-100 text-red-600 py-0.5 px-2 rounded-full text-xs">{{ $stats['available_leads'] }}</span>
                    </button>
                    <button wire:click="setLeadTypeFilter('booked')" 
                            class="py-4 px-1 text-sm font-medium border-b-2 {{ $leadTypeFilter === 'booked' ? 'border-red-600 text-red-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
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
                                   class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm transition-all duration-200" 
                                   placeholder="Search by application #, name...">
                        </div>
                    </div>

                    <!-- Quick Filters -->
                    <div class="flex flex-wrap items-center gap-3">
                        @if($leadTypeFilter === 'booked')
                            <!-- Status Filter for booked leads -->
                            <div class="relative">
                                <select wire:model.live="statusFilter" class="appearance-none bg-white border border-gray-300 rounded-xl px-4 py-3 pr-8 text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 cursor-pointer">
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
                            <select wire:model.live="dateRange" class="appearance-none bg-white border border-gray-300 rounded-xl px-4 py-3 pr-8 text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 cursor-pointer">
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
                            <select wire:model.live="sortBy" class="appearance-none bg-white border border-gray-300 rounded-xl px-4 py-3 pr-8 text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 cursor-pointer">
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
                                class="inline-flex items-center px-4 py-3 border border-gray-300 rounded-xl text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:ring-2 focus:ring-red-500 transition-all duration-200 {{ $showFilters ? 'bg-red-50 border-red-300 text-red-700' : '' }}">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                            </svg>
                            Filters
                        </button>

                        <!-- Export Button -->
                        <button class="inline-flex items-center px-4 py-3 bg-black text-white rounded-xl text-sm font-medium hover:bg-gray-800 focus:ring-2 focus:ring-red-500 transition-all duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Export
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
                            <select wire:model.live="amountRange" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-red-500">
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
                            <select wire:model.live="crbScoreRange" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-red-500">
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
                                    class="text-sm text-red-600 hover:text-red-800 underline transition-colors">
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

        {{-- LEADS DISPLAY SECTION --}}
        @if($viewMode === 'grid')
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($leads as $lead)
                    <livewire:leads.components.lead-card 
                        :lead="$lead" 
                        :isAvailable="$leadTypeFilter === 'available'" 
                        :key="'lead-card-' . $lead->id . '-' . ($leadTypeFilter === 'available' ? 'available' : 'booked')" />
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
        @else
            {{-- Table view implementation would go here --}}
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
                                                <div class="h-10 w-10 rounded-full {{ $isAvailable ? 'bg-gradient-to-br from-red-400 to-red-600 blur-sm' : 'bg-gradient-to-br from-gray-400 to-gray-600' }} flex items-center justify-center">
                                                    <span class="text-sm font-bold text-white">
                                                        {{ substr($application->first_name, 0, 1) }}{{ substr($application->last_name, 0, 1) }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-black {{ $isAvailable ? 'blur-sm' : '' }}">
                                                    @if($isAvailable)
                                                        {{ substr($application->first_name, 0, 1) }}*** {{ substr($application->last_name, 0, 1) }}***
                                                    @else
                                                        {{ $application->first_name }} {{ $application->last_name }}
                                                    @endif
                                                </div>
                                                <div class="text-sm text-gray-500 {{ $isAvailable ? 'blur-sm' : '' }}">
                                                    @if($isAvailable)
                                                        ***@***.***
                                                    @else
                                                        {{ $application->email }}
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-black {{ $isAvailable ? 'blur-sm' : '' }}">
                                            @if($isAvailable)
                                                TSh ***,***
                                            @else
                                                TSh {{ number_format($application->requested_amount) }}
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($application->credit_score)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                {{ $application->credit_score >= 650 ? 'bg-green-100 text-green-800' : 
                                                   ($application->credit_score >= 550 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
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
                                                    @case('submitted') bg-red-100 text-red-800 @break
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
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <livewire:leads.components.lead-card 
                                            :lead="$lead" 
                                            :isAvailable="$isAvailable" 
                                            :key="'table-lead-card-' . $lead->id . '-' . ($isAvailable ? 'available' : 'booked')" 
                                            view="table" />
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
    </div>
</div>
</div>
