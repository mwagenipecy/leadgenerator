<div>
    {{-- Header Section --}}
    <div class="bg-white border-b border-gray-200 sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center space-x-4">
                    <h1 class="text-2xl font-bold text-black">{{ __('leads.lead_management') }}</h1>
                    <span class="text-sm text-gray-500">{{ Auth::user()->lender->company_name ?? __('leads.lender_portal') }}</span>
                </div>
                <div class="flex items-center space-x-4">
                    <!-- Quick Stats -->
                    <div class="hidden md:flex items-center space-x-6 text-sm">
                        <div class="text-center">
                            <div class="font-bold text-sidebar-green">{{ $stats['available_leads'] }}</div>
                            <div class="text-gray-500">{{ __('leads.available') }}</div>
                        </div>
                        <div class="text-center">
                            <div class="font-bold text-black">{{ $stats['my_leads'] }}</div>
                            <div class="text-gray-500">{{ __('leads.my_leads') }}</div>
                        </div>
                        <div class="text-center">
                            <div class="font-bold text-sidebar-green">{{ $stats['pending_review'] }}</div>
                            <div class="text-gray-500">{{ __('leads.pending') }}</div>
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
        {{-- Flash Messages --}}
        @if (session()->has('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl" role="alert">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="mb-6 bg-sidebar-green-50 border border-sidebar-green-200 text-sidebar-green-light px-4 py-3 rounded-xl" role="alert">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    {{ session('error') }}
                </div>
            </div>
        @endif

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
                        <p class="text-sm font-medium text-gray-500">{{ __('leads.available_leads') }}</p>
                        <p class="text-2xl font-bold text-black">{{ number_format($stats['available_leads']) }}</p>
                        <p class="text-xs text-gray-400 mt-1">{{ __('leads.in_the_market') }}</p>
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
                        <p class="text-sm font-medium text-gray-500">{{ __('leads.my_leads') }}</p>
                        <p class="text-2xl font-bold text-black">{{ number_format($stats['my_leads']) }}</p>
                        <p class="text-xs text-gray-400 mt-1">{{ __('leads.total_acquired') }}</p>
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
                        <p class="text-sm font-medium text-gray-500">{{ __('leads.pending_review') }}</p>
                        <p class="text-2xl font-bold text-black">{{ number_format($stats['pending_review']) }}</p>
                        <p class="text-xs text-sidebar-green mt-1">{{ __('leads.requires_action') }}</p>
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
                        <p class="text-sm font-medium text-gray-500">{{ __('leads.total_value') }}</p>
                        <p class="text-2xl font-bold text-black">
                            TSh {{ isset($stats['total_value']) && $stats['total_value'] > 0 ? number_format($stats['total_value']/1000000, 1) . 'M' : '0.0M' }}
                        </p>
                        <p class="text-xs text-gray-400 mt-1">{{ __('leads.approved_loans') }}</p>
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
                        {{ __('leads.available_leads') }}
                        <span class="ml-2 bg-sidebar-green-100 text-sidebar-green py-0.5 px-2 rounded-full text-xs">{{ $stats['available_leads'] }}</span>
                    </button>
                    <button wire:click="setLeadTypeFilter('booked')" 
                            class="py-4 px-1 text-sm font-medium border-b-2 {{ $leadTypeFilter === 'booked' ? 'border-sidebar-green text-sidebar-green' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        {{ __('leads.my_booked_leads') }}
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
                                   placeholder="{{ __('leads.search_placeholder') }}">
                        </div>
                    </div>

                    <!-- Quick Filters -->
                    <div class="flex flex-wrap items-center gap-3">
                        @if($leadTypeFilter === 'booked')
                            <!-- Status Filter for booked leads -->
                            <div class="relative">
                                <select wire:model.live="statusFilter" class="appearance-none bg-white border border-gray-300 rounded-xl px-4 py-3 pr-8 text-sm focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green cursor-pointer">
                                    <option value="all">{{ __('leads.all_status') }}</option>
                                    <option value="submitted">{{ __('leads.under_review') }}</option>
                                    <option value="approved">{{ __('leads.approved') }}</option>
                                    <option value="rejected">{{ __('leads.rejected') }}</option>
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
                                <option value="all">{{ __('leads.all_time') }}</option>
                                <option value="today">{{ __('leads.today') }}</option>
                                <option value="week">{{ __('leads.this_week') }}</option>
                                <option value="month">{{ __('leads.this_month') }}</option>
                                <option value="quarter">{{ __('leads.this_quarter') }}</option>
                                <option value="year">{{ __('leads.this_year') }}</option>
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
                                <option value="created_at">{{ __('leads.latest_first') }}</option>
                                <option value="requested_amount">{{ __('leads.amount_high_low') }}</option>
                                <option value="credit_score">{{ __('leads.crb_score') }}</option>
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
                            {{ __('leads.filters') }}
                        </button>

                        <!-- Export Button -->
                        <button class="inline-flex items-center px-4 py-3 bg-black text-white rounded-xl text-sm font-medium hover:bg-gray-800 focus:ring-2 focus:ring-sidebar-green transition-all duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            {{ __('leads.export') }}
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
                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('leads.loan_amount_range') }}</label>
                            <select wire:model.live="amountRange" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-sidebar-green">
                                <option value="all">{{ __('leads.all_amounts') }}</option>
                                <option value="under_100k">{{ __('leads.under_100k') }}</option>
                                <option value="100k_500k">{{ __('leads.100k_500k') }}</option>
                                <option value="500k_1m">{{ __('leads.500k_1m') }}</option>
                                <option value="1m_5m">{{ __('leads.1m_5m') }}</option>
                                <option value="over_5m">{{ __('leads.over_5m') }}</option>
                            </select>
                        </div>

                        <!-- CRB Score Range Filter -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('leads.crb_score_range') }}</label>
                            <select wire:model.live="crbScoreRange" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-sidebar-green">
                                <option value="all">{{ __('leads.all_scores') }}</option>
                                <option value="excellent">{{ __('leads.excellent') }}</option>
                                <option value="good">{{ __('leads.good') }}</option>
                                <option value="fair">{{ __('leads.fair') }}</option>
                                <option value="poor">{{ __('leads.poor') }}</option>
                            </select>
                        </div>
                    </div>

                    <!-- Filter Actions and Results Info -->
                    <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                        <div class="flex items-center space-x-4">
                            <button wire:click="clearFilters" 
                                    class="text-sm text-sidebar-green hover:text-sidebar-green-800 underline transition-colors">
                                {{ __('leads.clear_all_filters') }}
                            </button>
                            <div class="text-sm text-gray-500">
                                {{ $leads->total() }} {{ $leads->total() !== 1 ? __('leads.leads_found_plural') : __('leads.leads_found') }}
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
                    @php
                        // Both available and booked leads are now ApplicationLenderSubmission objects
                        $application = $lead->application;
                        $isAvailable = $leadTypeFilter === 'available';
                        $submission = $lead; // Both have submission, but status differs
                    @endphp
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-all duration-200 overflow-hidden">
                        <!-- Card Header -->
                        <div class="p-6 pb-4">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex items-center space-x-3">
                                    <div class="h-12 w-12 rounded-full bg-gradient-to-br {{ $isAvailable ? 'from-sidebar-green-400 to-sidebar-green' : 'from-gray-400 to-gray-600' }} flex items-center justify-center {{ $isAvailable ? 'blur-sm' : '' }}">
                                        <span class="text-lg font-bold text-white">
                                            {{ substr($application->first_name, 0, 1) }}{{ substr($application->last_name, 0, 1) }}
                                        </span>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-semibold text-black {{ $isAvailable ? 'blur-sm' : '' }}">
                                            @if($isAvailable)
                                                {{ substr($application->first_name, 0, 1) }}*** {{ substr($application->last_name, 0, 1) }}***
                                            @else
                                                {{ $application->first_name }} {{ $application->last_name }}
                                            @endif
                                        </h3>
                                        <p class="text-sm text-gray-600">{{ $application->application_number }}</p>
                                    </div>
                                </div>
                                
                                <div class="flex flex-col items-end space-y-2">
                                    @if(!$isAvailable)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @switch($submission->status)
                                                @case('submitted') bg-sidebar-green-100 text-sidebar-green-800 @break
                                                @case('approved') bg-green-100 text-green-800 @break
                                                @case('rejected') bg-gray-100 text-gray-800 @break
                                                @default bg-gray-100 text-gray-800
                                            @endswitch">
                                            {{ ucwords(str_replace('_', ' ', $submission->status)) }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                            {{ __('leads.available') }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Key Metrics Grid -->
                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div class="text-center p-3 bg-gray-50 rounded-lg">
                                    <div class="text-xs text-gray-500 mb-1">{{ __('leads.loan_amount') }}</div>
                                    <div class="text-sm font-bold text-black {{ $isAvailable ? '-sm' : '' }}">
                                        @if($isAvailable)
                                        TSh {{ number_format($application->requested_amount/1000) }}K
                                        @else
                                            TSh {{ number_format($application->requested_amount/1000) }}K
                                        @endif
                                    </div>
                                </div>
                                <div class="text-center p-3 bg-gray-50 rounded-lg">
                                    <div class="text-xs text-gray-500 mb-1">{{ __('leads.monthly_income') }}</div>
                                    <div class="text-sm font-bold text-black {{ $isAvailable ? '-sm' : '' }}">
                                        @if($isAvailable)
                                        TSh {{ number_format(($application->total_monthly_income ?? 0)/1000) }}K
                                        @else
                                            TSh {{ number_format(($application->total_monthly_income ?? 0)/1000) }}K
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Additional Info -->
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-500">{{ __('leads.crb_score') }}:</span>
                                    @if($application->user && $application->user->credit_score)
                                        <span class="font-medium {{ $application->user->credit_score >= 650 ? 'text-green-600' : ($application->user->credit_score >= 550 ? 'text-yellow-600' : 'text-sidebar-green') }}">
                                            {{ $application->user->credit_score }}
                                            @if($application->user->credit_score_rating)
                                                <span class="text-xs text-gray-500">({{ $application->user->credit_score_rating }})</span>
                                            @endif
                                        </span>
                                    @else
                                        <span class="font-medium text-gray-400">{{ __('leads.waiting') }}</span>
                                    @endif
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-500">{{ __('leads.tenure') }}:</span>
                                    <span class="font-medium">{{ $application->requested_tenure_months }} {{ __('leads.months') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-500">{{ __('leads.product') }}:</span>
                                    <span class="font-medium text-xs">{{ $submission->loanProduct->name ?? 'N/A' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-500">{{ __('leads.applied') }}:</span>
                                    <span class="font-medium text-xs">{{ $application->created_at->format('M d, Y') }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Card Actions -->
                        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
                            @if($isAvailable)
                                <div class="flex justify-center">
                                    <button wire:click="openBookingModal('{{ $application->id }}')" 
                                            class="inline-flex items-center px-4 py-2 bg-sidebar-green text-white rounded-lg text-sm font-medium hover:bg-sidebar-green-light transition-all duration-200">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                        </svg>
                                        {{ __('leads.book_lead') }} 
                                    </button>
                                </div>
                            @else
                                <div class="flex items-center justify-between">
                                    <div class="text-xs text-gray-500">

                                        {{ __('leads.booked') }}: {{ $submission->booked_at ? $submission->booked_at : 'N/A' }}

                                    </div>
                                    
                                    <div class="flex items-center space-x-2">
                                        @if($submission->status === 'submitted')
                                            <button wire:click="cancelBooking({{ $submission->id }})" 
                                                    wire:confirm="Are you sure you want to cancel this booking?"
                                                    class="text-sidebar-green hover:text-sidebar-green-800 text-xs font-medium transition-colors">
                                                {{ __('leads.cancel') }}
                                            </button>
                                        @endif
                                        
                                        <a href="{{ route('view.loan.details',$submission->id) }}" class="text-black hover:text-gray-700 text-xs font-medium transition-colors">
                                            {{ __('leads.view_details') }}
                                        </a>
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
                            <h3 class="mt-2 text-sm font-medium text-gray-900">{{ __('leads.no_leads_found') }}</h3>
                            <p class="mt-1 text-sm text-gray-500">
                                @if($leadTypeFilter === 'available')
                                    {{ __('leads.no_available_leads') }}
                                @else
                                    {{ __('leads.no_booked_leads') }}
                                @endif
                            </p>
                        </div>
                    </div>
                @endforelse
            </div>
        @else
            {{-- TABLE VIEW --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition-colors" 
                                    wire:click="sortBy('application_number')">
                                    <div class="flex items-center space-x-1">
                                        <span>{{ __('leads.application') }}</span>
                                        @if($sortBy === 'application_number')
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $sortDirection === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}"/>
                                            </svg>
                                        @endif
                                    </div>
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('leads.applicant') }}</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition-colors" 
                                    wire:click="sortBy('requested_amount')">
                                    <div class="flex items-center space-x-1">
                                        <span>{{ __('leads.loan_amount') }}</span>
                                        @if($sortBy === 'requested_amount')
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $sortDirection === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}"/>
                                            </svg>
                                        @endif
                                    </div>
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('leads.monthly_income') }}</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition-colors" 
                                    wire:click="sortBy('credit_score')">
                                    <div class="flex items-center space-x-1">
                                        <span>{{ __('leads.crb_score') }}</span>
                                        @if($sortBy === 'credit_score')
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $sortDirection === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}"/>
                                            </svg>
                                        @endif
                                    </div>
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('leads.product') }}</th>
                                @if($leadTypeFilter === 'booked')
                                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('leads.status') }}</th>
                                @endif
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('leads.applied') }}</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('leads.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($leads as $lead)
                                @php
                                    // Both available and booked leads are now ApplicationLenderSubmission objects
                                    $application = $lead->application;
                                    $isAvailable = $leadTypeFilter === 'available';
                                    $submission = $lead; // Both have submission, but status differs
                                @endphp
                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-black">{{ $application->application_number }}</div>
                                        <div class="text-sm text-gray-500">{{ $application->requested_tenure_months }} months</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <div class="h-10 w-10 rounded-full bg-gradient-to-br {{ $isAvailable ? 'from-sidebar-green-400 to-sidebar-green blur-sm' : 'from-gray-400 to-gray-600' }} flex items-center justify-center">
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
                                        <div class="text-sm font-bold text-black {{ $isAvailable ? '' : '' }}">
                                            @if($isAvailable)
                                            TSh {{ number_format($application->requested_amount) }}
                                            @else
                                                TSh {{ number_format($application->requested_amount) }}
                                            @endif
                                        </div>
                                        <div class="text-xs text-gray-500">{{ __('leads.requested') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-black {{ $isAvailable ? '' : '' }}">
                                            @if($isAvailable)
                                            TSh {{ number_format($application->total_monthly_income ?? 0) }}
                                            @else
                                                TSh {{ number_format($application->total_monthly_income ?? 0) }}
                                            @endif
                                        </div>
                                        <div class="text-xs text-gray-500">{{ __('leads.monthly') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($application->user && $application->user->credit_score)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                {{ $application->user->credit_score >= 650 ? 'bg-green-100 text-green-800' : 
                                                   ($application->user->credit_score >= 550 ? 'bg-yellow-100 text-yellow-800' : 'bg-sidebar-green-100 text-sidebar-green-800') }}">
                                                {{ $application->user->credit_score }}
                                                @if($application->user->credit_score_rating)
                                                    <span class="ml-1 text-xs">({{ $application->user->credit_score_rating }})</span>
                                                @endif
                                            </span>
                                        @else
                                            <span class="text-gray-400 text-sm">{{ __('leads.waiting') }}</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-black">{{ $submission->loanProduct->name ?? 'N/A' }}</div>
                                    </td>
                                    @if($leadTypeFilter === 'booked')
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                @switch($submission->status)
                                                    @case('submitted') bg-sidebar-green-100 text-sidebar-green-800 @break
                                                    @case('approved') bg-green-100 text-green-800 @break
                                                    @case('rejected') bg-gray-100 text-gray-800 @break
                                                    @default bg-gray-100 text-gray-800
                                                @endswitch">
                                                {{ ucwords(str_replace('_', ' ', $submission->status)) }}
                                            </span>
                                        </td>
                                    @endif
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-black">{{ $application->created_at->format('M d, Y') }}</div>
                                        <div class="text-xs text-gray-500">{{ $application->created_at->diffForHumans() }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        @if($isAvailable)
                                            <button wire:click="openBookingModal('{{ $application->id }}')" 
                                                    class="inline-flex items-center px-3 py-1.5 bg-sidebar-green text-white rounded-lg text-xs font-medium hover:bg-sidebar-green-light transition-all duration-200">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                                </svg>
                                                {{ __('leads.book') }}
                                            </button>
                                        @else
                                            <div class="flex items-center space-x-2">
                                                @if($submission->status === 'submitted')
                                                    <button wire:click="cancelBooking({{ $submission->id }})" 
                                                            wire:confirm="Are you sure you want to cancel this booking?"
                                                            class="text-sidebar-green hover:text-sidebar-green-800 p-1.5 rounded-lg hover:bg-sidebar-green-50 transition-all duration-200"
                                                            title="{{ __('leads.cancel_booking') }}">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                        </svg>
                                                    </button>
                                                @endif
                                                
                                                <a  href="{{ route('view.loan.details', $submission->id) }}"  class="text-black hover:text-gray-700 p-1.5 rounded-lg hover:bg-gray-50 transition-all duration-200"
                                                        title="View Details">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    </svg>
                                                </button>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ $leadTypeFilter === 'booked' ? '9' : '8' }}" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center">
                                            <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                            </svg>
                                            <h3 class="mt-2 text-sm font-medium text-gray-900">{{ __('leads.no_leads_found') }}</h3>
                                            <p class="mt-1 text-sm text-gray-500">
                                                @if($leadTypeFilter === 'available')
                                                    {{ __('leads.no_available_leads') }}
                                                @else
                                                    {{ __('leads.no_booked_leads') }}
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

    {{-- Booking Confirmation Modal --}}
    @if($showBookingModal && $selectedLead)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" wire:click="closeBookingModal"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div class="relative inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-sidebar-green-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-sidebar-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left flex-1">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                {{ __('leads.confirm_lead_booking') }}
                            </h3>
                            <div class="mt-4 space-y-4">
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="text-xs font-medium text-gray-500">{{ __('leads.application_number') }}</label>
                                            <p class="text-sm font-bold text-black">{{ $selectedLead->application_number }}</p>
                                        </div>
                                        <div>
                                            <label class="text-xs font-medium text-gray-500">{{ __('leads.loan_amount') }}</label>
                                            <p class="text-sm font-bold text-black ">TSh  {{  number_format($selectedLead->requested_amount,2) }} </p>
                                        </div>
                                        <div>
                                            <label class="text-xs font-medium text-gray-500">{{ __('leads.applicant_label') }}</label>
                                            <p class="text-sm font-bold text-black blur-sm">{{ substr($selectedLead->first_name, 0, 1) }}*** {{ substr($selectedLead->last_name, 0, 1) }}***</p>
                                        </div>
                                        <div>
                                            <label class="text-xs font-medium text-gray-500">{{ __('leads.product') }}</label>
                                            <p class="text-sm font-bold text-black">
                                                @php
                                                    // Get product from submission if available, otherwise from application
                                                    $product = $selectedLead->lenderSubmissions()->where('lender_id', Auth::user()->lender_id)->with('loanProduct')->first()?->loanProduct ?? $selectedLead->loanProduct;
                                                @endphp
                                                {{ $product->name ?? 'N/A' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="bg-sidebar-green-50 border border-sidebar-green-200 rounded-lg p-4">
                                    <!-- <div class="flex items-center">
                                        <svg class="h-5 w-5 text-sidebar-green mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                        </svg>
                                        <div>
                                            <p class="text-sm font-medium text-sidebar-green-800">Booking Fee</p>
                                            <p class="text-lg font-bold text-sidebar-green-900">TSh {{ number_format($bookingFee) }}</p>
                                        </div>
                                    </div> -->
                                </div>

                                <div class="text-sm text-gray-600">
                                    <p>{{ __('leads.by_booking') }}</p>
                                    <ul class="mt-2 space-y-1 text-xs">
                                        <!-- <li>• Pay the booking fee of TSh {{ number_format($bookingFee) }}</li> -->
                                        <li>• {{ __('leads.booking_terms_1') }}</li>
                                        <li>• {{ __('leads.booking_terms_2') }}</li>
                                        <li>• {{ __('leads.booking_terms_3') }}</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                        <button wire:click="confirmBooking" 
                                wire:loading.attr="disabled"
                                wire:target="confirmBooking"
                                type="button" 
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-sidebar-green text-base font-medium text-white hover:bg-sidebar-green-light focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sidebar-green sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-50 disabled:cursor-not-allowed">
                            <span wire:loading.remove wire:target="confirmBooking">{{ __('leads.confirm_booking') }}</span>
                            <span wire:loading wire:target="confirmBooking" class="flex items-center">
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                {{ __('leads.processing') }}
                            </span>
                        </button>
                        <button wire:click="closeBookingModal" 
                                type="button" 
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">
                            {{ __('leads.cancel') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    
</div>