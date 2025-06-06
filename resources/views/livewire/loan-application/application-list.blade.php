<div>

{{-- SECTION 1: HEADER AND STATS --}}
<div class="min-h-screen bg-gray-50">
    {{-- Header Section --}}
    <div class="bg-white border-b border-gray-200 sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center space-x-4">
                    <h1 class="text-2xl font-bold text-gray-900">Loan Applications</h1>
                    @if(Auth::user()->role === 'lender')
                        <span class="text-sm text-gray-500">{{ Auth::user()->lender->company_name ?? 'Lender Portal' }}</span>
                    @endif
                </div>
                <div class="flex items-center space-x-4">
                    <!-- Quick Stats -->
                    <div class="hidden md:flex items-center space-x-6 text-sm">
                        <div class="text-center">
                            <div class="font-bold text-blue-600">{{ $stats['pending'] }}</div>
                            <div class="text-gray-500">Pending</div>
                        </div>
                        <div class="text-center">
                            <div class="font-bold text-yellow-600">{{ $stats['under_review'] }}</div>
                            <div class="text-gray-500">Review</div>
                        </div>
                        <div class="text-center">
                            <div class="font-bold text-green-600">{{ $stats['approved'] }}</div>
                            <div class="text-gray-500">Approved</div>
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
                        <button wire:click="setViewMode('detailed')" 
                                class="px-3 py-1 rounded-md text-sm {{ $viewMode === 'detailed' ? 'bg-white shadow-sm' : '' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
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
                            <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Total Applications</p>
                            <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total']) }}</p>
                            <p class="text-xs text-gray-400 mt-1">All time</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Pending Review</p>
                            <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['pending']) }}</p>
                            <p class="text-xs text-gray-400 mt-1">Requires attention</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Approved</p>
                            <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['approved']) }}</p>
                            <p class="text-xs text-green-600 mt-1">{{ number_format(($stats['approved']/$stats['total'])*100, 1) }}% approval rate</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Total Value</p>
                            <p class="text-2xl font-bold text-gray-900">TSh {{ number_format($stats['total_amount']/1000000, 1) }}M</p>
                            <p class="text-xs text-gray-400 mt-1">Approved loans</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Additional Stats Row --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-blue-100 text-sm">Average DSR</p>
                            <p class="text-2xl font-bold">{{ number_format($stats['avg_dsr'], 1) }}%</p>
                        </div>
                        <div class="text-blue-200">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-green-100 text-sm">This Month</p>
                            <p class="text-2xl font-bold">{{ $stats['pending'] + $stats['under_review'] }}</p>
                            <p class="text-green-100 text-xs">New applications</p>
                        </div>
                        <div class="text-green-200">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-purple-100 text-sm">Disbursed</p>
                            <p class="text-2xl font-bold">{{ number_format($stats['disbursed']) }}</p>
                            <p class="text-purple-100 text-xs">Completed loans</p>
                        </div>
                        <div class="text-purple-200">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Flash Messages --}}
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





            {{-- SECTION 2: FILTERS AND SEARCH --}}

            {{-- Main Filters and Search Panel --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 mb-6">
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
                                       class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm transition-all duration-200" 
                                       placeholder="Search by name, application #, ID, email, phone...">
                            </div>
                        </div>

                        <!-- Quick Filters -->
                        <div class="flex flex-wrap items-center gap-3">
                            <!-- Status Filter -->
                            <div class="relative">
                                <select wire:model.live="statusFilter" class="appearance-none bg-white border border-gray-300 rounded-xl px-4 py-3 pr-8 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 cursor-pointer">
                                    <option value="all">All Status ({{ $stats['total'] }})</option>
                                    <option value="submitted">Submitted ({{ $stats['pending'] }})</option>
                                    <option value="under_review">Under Review ({{ $stats['under_review'] }})</option>
                                    <option value="approved">Approved ({{ $stats['approved'] }})</option>
                                    <option value="rejected">Rejected ({{ $stats['rejected'] }})</option>
                                    <option value="disbursed">Disbursed ({{ $stats['disbursed'] }})</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </div>
                            </div>

                            <!-- Date Range Filter -->
                            <div class="relative">
                                <select wire:model.live="dateRange" class="appearance-none bg-white border border-gray-300 rounded-xl px-4 py-3 pr-8 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 cursor-pointer">
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
                                <select wire:model.live="sortBy" class="appearance-none bg-white border border-gray-300 rounded-xl px-4 py-3 pr-8 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 cursor-pointer">
                                    <option value="created_at">Latest First</option>
                                    <option value="requested_amount">Amount (High to Low)</option>
                                    <option value="debt_to_income_ratio">DSR (Low to High)</option>
                                    <option value="total_monthly_income">Income (High to Low)</option>
                                    <option value="status">Status</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </div>
                            </div>

                            <!-- Advanced Filters Toggle -->
                            <button wire:click="toggleFilters" 
                                    class="inline-flex items-center px-4 py-3 border border-gray-300 rounded-xl text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:ring-2 focus:ring-blue-500 transition-all duration-200 {{ $showFilters ? 'bg-blue-50 border-blue-300 text-blue-700' : '' }}">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                                </svg>
                                Advanced Filters
                                @if($showFilters)
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                                    </svg>
                                @else
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                @endif
                            </button>

                            <!-- Export Button -->
                            <button class="inline-flex items-center px-4 py-3 bg-gray-100 border border-gray-300 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-200 focus:ring-2 focus:ring-blue-500 transition-all duration-200">
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
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
                            <!-- Amount Range Filter -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Loan Amount Range</label>
                                <select wire:model.live="amountRange" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-blue-500">
                                    <option value="all">All Amounts</option>
                                    <option value="under_100k">Under TSh 100K</option>
                                    <option value="100k_500k">TSh 100K - 500K</option>
                                    <option value="500k_1m">TSh 500K - 1M</option>
                                    <option value="1m_5m">TSh 1M - 5M</option>
                                    <option value="over_5m">Over TSh 5M</option>
                                </select>
                            </div>

                            <!-- DSR Range Filter -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">DSR Range</label>
                                <select wire:model.live="dsrRange" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-blue-500">
                                    <option value="all">All DSR Levels</option>
                                    <option value="excellent">Excellent (0-30%)</option>
                                    <option value="good">Good (30-40%)</option>
                                    <option value="fair">Fair (40-50%)</option>
                                    <option value="poor">Poor (50%+)</option>
                                </select>
                            </div>

                            <!-- Employment Status Filter -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Employment Status</label>
                                <select wire:model.live="employmentFilter" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-blue-500">
                                    <option value="all">All Employment Types</option>
                                    <option value="employed">Employed</option>
                                    <option value="self_employed">Self Employed</option>
                                    <option value="unemployed">Unemployed</option>
                                    <option value="retired">Retired</option>
                                    <option value="student">Student</option>
                                </select>
                            </div>

                            <!-- Loan Product Filter -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Loan Product</label>
                                <select wire:model.live="productFilter" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-blue-500">
                                    <option value="all">All Products</option>
                                    @foreach($loanProducts as $product)
                                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Filter Actions and Results Info -->
                        <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                            <div class="flex items-center space-x-4">
                                <button wire:click="clearFilters" 
                                        class="text-sm text-gray-600 hover:text-gray-800 underline transition-colors">
                                    Clear all filters
                                </button>
                                <div class="text-sm text-gray-500">
                                    {{ $applications->total() }} application{{ $applications->total() !== 1 ? 's' : '' }} found
                                </div>
                            </div>
                            
                            <!-- Active Filters Display -->
                            <div class="flex items-center space-x-2">
                                @if($statusFilter !== 'all')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        Status: {{ ucwords(str_replace('_', ' ', $statusFilter)) }}
                                        <button wire:click="$set('statusFilter', 'all')" class="ml-1.5 text-blue-600 hover:text-blue-800">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </button>
                                    </span>
                                @endif
                                
                                @if($dateRange !== 'all')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Period: {{ ucwords(str_replace('_', ' ', $dateRange)) }}
                                        <button wire:click="$set('dateRange', 'all')" class="ml-1.5 text-green-600 hover:text-green-800">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </button>
                                    </span>
                                @endif

                                @if($amountRange !== 'all')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                        Amount: {{ ucwords(str_replace('_', ' ', $amountRange)) }}
                                        <button wire:click="$set('amountRange', 'all')" class="ml-1.5 text-purple-600 hover:text-purple-800">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </button>
                                    </span>
                                @endif

                                @if($dsrRange !== 'all')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        DSR: {{ ucwords($dsrRange) }}
                                        <button wire:click="$set('dsrRange', 'all')" class="ml-1.5 text-yellow-600 hover:text-yellow-800">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </button>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Bulk Actions Panel --}}
            @if(!empty($selectedApplications))
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-xl p-4 mb-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="flex items-center space-x-2">
                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                    <span class="text-blue-600 font-bold text-sm">{{ count($selectedApplications) }}</span>
                                </div>
                                <span class="text-sm font-medium text-blue-900">
                                    application{{ count($selectedApplications) !== 1 ? 's' : '' }} selected
                                </span>
                            </div>
                            
                            <!-- Selection Info -->
                            <div class="hidden md:flex items-center space-x-4 text-sm text-blue-700">
                                @php
                                    $selectedTotal = \App\Models\Application::whereIn('id', $selectedApplications)->sum('requested_amount');
                                @endphp
                                <span>Total Value: TSh {{ number_format($selectedTotal) }}</span>
                            </div>
                        </div>

                        <!-- Bulk Action Buttons -->
                        <div class="flex items-center space-x-2">
                            <button wire:click="bulkApprove" 
                                    wire:confirm="Are you sure you want to approve {{ count($selectedApplications) }} application(s)?"
                                    class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-green-700 transition-colors flex items-center">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Bulk Approve
                            </button>
                            
                            <button wire:click="bulkReject" 
                                    wire:confirm="Are you sure you want to reject {{ count($selectedApplications) }} application(s)?"
                                    class="bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-red-700 transition-colors flex items-center">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                Bulk Reject
                            </button>
                            
                            <div class="border-l border-blue-300 h-6 mx-2"></div>
                            
                            <button class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors flex items-center">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                Export Selected
                            </button>
                            
                            <button wire:click="$set('selectedApplications', [])" 
                                    class="text-blue-600 hover:text-blue-800 px-3 py-2 text-sm font-medium transition-colors">
                                Clear Selection
                            </button>
                        </div>
                    </div>
                </div>
            @endif



            {{-- SECTION 3: APPLICATIONS VIEWS (TABLE, GRID, DETAILED) --}}

            {{-- TABLE VIEW --}}
            @if($viewMode === 'table')
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-4 text-left">
                                        <input type="checkbox" wire:model="selectAll" wire:click="toggleSelectAll" 
                                               class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    </th>
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
                                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition-colors" 
                                        wire:click="sortBy('first_name')">
                                        <div class="flex items-center space-x-1">
                                            <span>Applicant</span>
                                            @if($sortBy === 'first_name')
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $sortDirection === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}"/>
                                                </svg>
                                            @endif
                                        </div>
                                    </th>
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
                                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Income</th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition-colors" 
                                        wire:click="sortBy('debt_to_income_ratio')">
                                        <div class="flex items-center space-x-1">
                                            <span>DSR</span>
                                            @if($sortBy === 'debt_to_income_ratio')
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $sortDirection === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}"/>
                                                </svg>
                                            @endif
                                        </div>
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition-colors" 
                                        wire:click="sortBy('status')">
                                        <div class="flex items-center space-x-1">
                                            <span>Status</span>
                                            @if($sortBy === 'status')
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $sortDirection === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}"/>
                                                </svg>
                                            @endif
                                        </div>
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition-colors" 
                                        wire:click="sortBy('created_at')">
                                        <div class="flex items-center space-x-1">
                                            <span>Applied</span>
                                            @if($sortBy === 'created_at')
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $sortDirection === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}"/>
                                                </svg>
                                            @endif
                                        </div>
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($applications as $application)
                                    <tr class="hover:bg-gray-50 transition-colors duration-150 {{ in_array($application->id, $selectedApplications) ? 'bg-blue-50 border-l-4 border-blue-500' : '' }}">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <input type="checkbox" 
                                                   wire:click="toggleApplicationSelection({{ $application->id }})"
                                                   {{ in_array($application->id, $selectedApplications) ? 'checked' : '' }}
                                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $application->application_number }}</div>
                                            <div class="text-sm text-gray-500">{{ $application->loanProduct->name ?? 'No Product' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    <div class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center">
                                                        <span class="text-sm font-bold text-white">
                                                            {{ substr($application->first_name, 0, 1) }}{{ substr($application->last_name, 0, 1) }}
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $application->first_name }} {{ $application->last_name }}
                                                    </div>
                                                    <div class="text-sm text-gray-500">{{ $application->email }}</div>
                                                    <div class="text-xs text-gray-400">{{ $application->phone_number }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-bold text-gray-900">TSh {{ number_format($application->requested_amount) }}</div>
                                            <div class="text-sm text-gray-500">{{ $application->requested_tenure_months }} months</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">TSh {{ number_format($application->total_monthly_income) }}</div>
                                            <div class="text-xs text-gray-500">{{ ucwords(str_replace('_', ' ', $application->employment_status)) }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($application->debt_to_income_ratio)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                    {{ $application->debt_to_income_ratio <= 30 ? 'bg-green-100 text-green-800' : 
                                                       ($application->debt_to_income_ratio <= 40 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                                    {{ number_format($application->debt_to_income_ratio, 1) }}%
                                                </span>
                                            @else
                                                <span class="text-gray-400 text-sm">N/A</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                @switch($application->status)
                                                    @case('submitted') bg-blue-100 text-blue-800 @break
                                                    @case('under_review') bg-yellow-100 text-yellow-800 @break
                                                    @case('approved') bg-green-100 text-green-800 @break
                                                    @case('rejected') bg-red-100 text-red-800 @break
                                                    @case('disbursed') bg-purple-100 text-purple-800 @break
                                                    @default bg-gray-100 text-gray-800
                                                @endswitch">
                                                {{ ucwords(str_replace('_', ' ', $application->status)) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $application->created_at->format('M d, Y') }}</div>
                                            <div class="text-xs text-gray-500">{{ $application->created_at->diffForHumans() }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex items-center space-x-2">
                                                <button wire:click="viewApplication({{ $application->id }})" 
                                                        class="text-indigo-600 hover:text-indigo-900 p-1.5 rounded-lg hover:bg-indigo-50 transition-all duration-200"
                                                        title="View Details">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    </svg>
                                                </button>
                                                
                                                @if($application->status === 'submitted')
                                                    <button wire:click="startReview({{ $application->id }})" 
                                                            class="text-blue-600 hover:text-blue-900 p-1.5 rounded-lg hover:bg-blue-50 transition-all duration-200"
                                                            title="Start Review">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                                        </svg>
                                                    </button>
                                                @endif

                                                @if(in_array($application->status, ['submitted', 'under_review']))
                                                    <button wire:click="approveApplication({{ $application->id }})" 
                                                            class="text-green-600 hover:text-green-900 p-1.5 rounded-lg hover:bg-green-50 transition-all duration-200"
                                                            title="Approve">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                        </svg>
                                                    </button>
                                                    <button wire:click="rejectApplication({{ $application->id }})" 
                                                            class="text-red-600 hover:text-red-900 p-1.5 rounded-lg hover:bg-red-50 transition-all duration-200"
                                                            title="Reject">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                        </svg>
                                                    </button>
                                                @endif

                                                @if($application->status === 'approved')
                                                    <button wire:click="markDisbursed({{ $application->id }})" 
                                                            class="text-purple-600 hover:text-purple-900 p-1.5 rounded-lg hover:bg-purple-50 transition-all duration-200"
                                                            title="Mark as Disbursed">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                                        </svg>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="px-6 py-12 text-center">
                                            <div class="flex flex-col items-center">
                                                <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                </svg>
                                                <h3 class="mt-2 text-sm font-medium text-gray-900">No applications found</h3>
                                                <p class="mt-1 text-sm text-gray-500">Try adjusting your search or filter criteria.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>


            @endif

      
        
        

        {{-- GRID VIEW --}}
        @elseif($viewMode === 'grid')
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($applications as $application)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-all duration-200 overflow-hidden {{ in_array($application->id, $selectedApplications) ? 'ring-2 ring-blue-500 bg-blue-50' : '' }}">
                        <!-- Card Header -->
                        <div class="p-6 pb-4">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex items-center space-x-3">
                                    <input type="checkbox" 
                                        wire:click="toggleApplicationSelection({{ $application->id }})"
                                        {{ in_array($application->id, $selectedApplications) ? 'checked' : '' }}
                                        class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <div class="h-12 w-12 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center">
                                        <span class="text-lg font-bold text-white">
                                            {{ substr($application->first_name, 0, 1) }}{{ substr($application->last_name, 0, 1) }}
                                        </span>
                                    </div>
                                </div>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @switch($application->status)
                                        @case('submitted') bg-blue-100 text-blue-800 @break
                                        @case('under_review') bg-yellow-100 text-yellow-800 @break
                                        @case('approved') bg-green-100 text-green-800 @break
                                        @case('rejected') bg-red-100 text-red-800 @break
                                        @case('disbursed') bg-purple-100 text-purple-800 @break
                                        @default bg-gray-100 text-gray-800
                                    @endswitch">
                                    {{ ucwords(str_replace('_', ' ', $application->status)) }}
                                </span>
                            </div>

                            <!-- Applicant Info -->
                            <div class="mb-4">
                                <h3 class="text-lg font-semibold text-gray-900">
                                    {{ $application->first_name }} {{ $application->last_name }}
                                </h3>
                                <p class="text-sm text-gray-600">{{ $application->email }}</p>
                                <p class="text-xs text-gray-500">{{ $application->application_number }}</p>
                            </div>

                            <!-- Key Metrics -->
                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div class="text-center p-3 bg-gray-50 rounded-lg">
                                    <div class="text-xs text-gray-500 mb-1">Loan Amount</div>
                                    <div class="text-sm font-bold text-gray-900">TSh {{ number_format($application->requested_amount/1000) }}K</div>
                                </div>
                                <div class="text-center p-3 bg-gray-50 rounded-lg">
                                    <div class="text-xs text-gray-500 mb-1">DSR</div>
                                    @if($application->debt_to_income_ratio)
                                        <div class="text-sm font-bold {{ $application->debt_to_income_ratio <= 30 ? 'text-green-600' : ($application->debt_to_income_ratio <= 40 ? 'text-yellow-600' : 'text-red-600') }}">
                                            {{ number_format($application->debt_to_income_ratio, 1) }}%
                                        </div>
                                    @else
                                        <div class="text-sm text-gray-400">N/A</div>
                                    @endif
                                </div>
                            </div>

                            <!-- Additional Info -->
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-500">Income:</span>
                                    <span class="font-medium">TSh {{ number_format($application->total_monthly_income) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-500">Tenure:</span>
                                    <span class="font-medium">{{ $application->requested_tenure_months }} months</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-500">Product:</span>
                                    <span class="font-medium text-xs">{{ $application->loanProduct->name ?? 'N/A' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-500">Applied:</span>
                                    <span class="font-medium text-xs">{{ $application->created_at->format('M d, Y') }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Card Actions -->
                        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
                            <div class="flex items-center justify-between">
                                <button wire:click="viewApplication({{ $application->id }})" 
                                        class="text-sm font-medium text-indigo-600 hover:text-indigo-800 transition-colors">
                                    View Details
                                </button>
                                
                                <div class="flex items-center space-x-2">
                                    @if($application->status === 'submitted')
                                        <button wire:click="startReview({{ $application->id }})" 
                                                class="text-blue-600 hover:text-blue-800 p-1.5 rounded-lg hover:bg-blue-100 transition-all duration-200"
                                                title="Start Review">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                            </svg>
                                        </button>
                                    @endif

                                    @if(in_array($application->status, ['submitted', 'under_review']))
                                        <button wire:click="approveApplication({{ $application->id }})" 
                                                class="text-green-600 hover:text-green-800 p-1.5 rounded-lg hover:bg-green-100 transition-all duration-200"
                                                title="Approve">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        </button>
                                        <button wire:click="rejectApplication({{ $application->id }})" 
                                                class="text-red-600 hover:text-red-800 p-1.5 rounded-lg hover:bg-red-100 transition-all duration-200"
                                                title="Reject">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </button>
                                    @endif

                                    @if($application->status === 'approved')
                                        <button wire:click="markDisbursed({{ $application->id }})" 
                                                class="text-purple-600 hover:text-purple-800 p-1.5 rounded-lg hover:bg-purple-100 transition-all duration-200"
                                                title="Mark as Disbursed">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full">
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No applications found</h3>
                            <p class="mt-1 text-sm text-gray-500">Try adjusting your search or filter criteria.</p>
                        </div>
                    </div>
                @endforelse
            </div>

            {{-- DETAILED VIEW --}}
        @elseif($viewMode === 'detailed')
            <div class="space-y-6">
                @forelse($applications as $application)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden {{ in_array($application->id, $selectedApplications) ? 'ring-2 ring-blue-500' : '' }}">
                        <!-- Header Section -->
                        <div class="p-6 border-b border-gray-100">
                            <div class="flex items-start justify-between">
                                <div class="flex items-center space-x-4">
                                    <input type="checkbox" 
                                        wire:click="toggleApplicationSelection({{ $application->id }})"
                                        {{ in_array($application->id, $selectedApplications) ? 'checked' : '' }}
                                        class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 mt-1">
                                    
                                    <div class="h-16 w-16 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center">
                                        <span class="text-xl font-bold text-white">
                                            {{ substr($application->first_name, 0, 1) }}{{ substr($application->last_name, 0, 1) }}
                                        </span>
                                    </div>
                                    
                                    <div>
                                        <h3 class="text-xl font-semibold text-gray-900">
                                            {{ $application->first_name }} {{ $application->last_name }}
                                        </h3>
                                        <p class="text-sm text-gray-600">{{ $application->email }}</p>
                                        <p class="text-sm text-gray-600">{{ $application->phone_number }}</p>
                                        <p class="text-xs text-gray-500 mt-1">Application: {{ $application->application_number }}</p>
                                    </div>
                                </div>
                                
                                <div class="text-right">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                        @switch($application->status)
                                            @case('submitted') bg-blue-100 text-blue-800 @break
                                            @case('under_review') bg-yellow-100 text-yellow-800 @break
                                            @case('approved') bg-green-100 text-green-800 @break
                                            @case('rejected') bg-red-100 text-red-800 @break
                                            @case('disbursed') bg-purple-100 text-purple-800 @break
                                            @default bg-gray-100 text-gray-800
                                        @endswitch">
                                        {{ ucwords(str_replace('_', ' ', $application->status)) }}
                                    </span>
                                    <div class="text-sm text-gray-500 mt-2">
                                        Applied {{ $application->created_at->diffForHumans() }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Content Section -->
                        <div class="p-6">
                            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                                <!-- Loan Details -->
                                <div class="lg:col-span-1">
                                    <h4 class="text-lg font-medium text-gray-900 mb-4">Loan Details</h4>
                                    <div class="space-y-3">
                                        <div class="flex justify-between py-2 border-b border-gray-100">
                                            <span class="text-sm text-gray-600">Requested Amount:</span>
                                            <span class="text-sm font-semibold text-gray-900">TSh {{ number_format($application->requested_amount) }}</span>
                                        </div>
                                        <div class="flex justify-between py-2 border-b border-gray-100">
                                            <span class="text-sm text-gray-600">Tenure:</span>
                                            <span class="text-sm font-semibold text-gray-900">{{ $application->requested_tenure_months }} months</span>
                                        </div>
                                        <div class="flex justify-between py-2 border-b border-gray-100">
                                            <span class="text-sm text-gray-600">Product:</span>
                                            <span class="text-sm font-semibold text-gray-900">{{ $application->loanProduct->name ?? 'N/A' }}</span>
                                        </div>
                                        <div class="flex justify-between py-2 border-b border-gray-100">
                                            <span class="text-sm text-gray-600">Purpose:</span>
                                            <span class="text-sm font-semibold text-gray-900">{{ ucwords(str_replace('_', ' ', $application->loan_purpose ?? 'N/A')) }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Financial Details -->
                                <div class="lg:col-span-1">
                                    <h4 class="text-lg font-medium text-gray-900 mb-4">Financial Profile</h4>
                                    <div class="space-y-3">
                                        <div class="flex justify-between py-2 border-b border-gray-100">
                                            <span class="text-sm text-gray-600">Monthly Income:</span>
                                            <span class="text-sm font-semibold text-gray-900">TSh {{ number_format($application->total_monthly_income) }}</span>
                                        </div>
                                        <div class="flex justify-between py-2 border-b border-gray-100">
                                            <span class="text-sm text-gray-600">Employment:</span>
                                            <span class="text-sm font-semibold text-gray-900">{{ ucwords(str_replace('_', ' ', $application->employment_status)) }}</span>
                                        </div>
                                        <div class="flex justify-between py-2 border-b border-gray-100">
                                            <span class="text-sm text-gray-600">DSR:</span>
                                            @if($application->debt_to_income_ratio)
                                                <span class="text-sm font-semibold {{ $application->debt_to_income_ratio <= 30 ? 'text-green-600' : ($application->debt_to_income_ratio <= 40 ? 'text-yellow-600' : 'text-red-600') }}">
                                                    {{ number_format($application->debt_to_income_ratio, 1) }}%
                                                </span>
                                            @else
                                                <span class="text-sm text-gray-400">N/A</span>
                                            @endif
                                        </div>
                                        <div class="flex justify-between py-2 border-b border-gray-100">
                                            <span class="text-sm text-gray-600">Credit Score:</span>
                                            <span class="text-sm font-semibold text-gray-900">{{ $application->credit_score ?? 'N/A' }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Application Progress -->
                                <div class="lg:col-span-1">
                                    <h4 class="text-lg font-medium text-gray-900 mb-4">Application Progress</h4>
                                    <div class="space-y-4">
                                        <!-- Documents Status -->
                                        <div class="flex items-center justify-between">
                                            <span class="text-sm text-gray-600">Documents:</span>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                {{ $application->documents->count() }} files
                                            </span>
                                        </div>
                                        
                                        <!-- Reviewer Info -->
                                        @if($application->reviewed_by)
                                            <div class="flex items-center justify-between">
                                                <span class="text-sm text-gray-600">Reviewed by:</span>
                                                <span class="text-sm font-medium text-gray-900">{{ $application->reviewedByUser->name ?? 'Unknown' }}</span>
                                            </div>
                                        @endif

                                        <!-- Timeline -->
                                        <div class="mt-4">
                                            <div class="text-sm text-gray-600 mb-2">Timeline:</div>
                                            <div class="space-y-2">
                                                <div class="flex items-center text-xs">
                                                    <div class="w-2 h-2 bg-blue-500 rounded-full mr-2"></div>
                                                    <span class="text-gray-600">Applied: {{ $application->created_at->format('M d, Y H:i') }}</span>
                                                </div>
                                                @if($application->reviewed_at)
                                                    <div class="flex items-center text-xs">
                                                        <div class="w-2 h-2 bg-yellow-500 rounded-full mr-2"></div>
                                                        <span class="text-gray-600">Reviewed: {{ $application->reviewed_at->format('M d, Y H:i') }}</span>
                                                    </div>
                                                @endif
                                                @if($application->approved_at)
                                                    <div class="flex items-center text-xs">
                                                        <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                                                        <span class="text-gray-600">Approved: {{ $application->approved_at->format('M d, Y H:i') }}</span>
                                                    </div>
                                                @endif
                                                @if($application->disbursed_at)
                                                    <div class="flex items-center text-xs">
                                                        <div class="w-2 h-2 bg-purple-500 rounded-full mr-2"></div>
                                                        <span class="text-gray-600">Disbursed: {{ $application->disbursed_at->format('M d, Y H:i') }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Notes Section -->
                            @if($application->notes)
                                <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                                    <h5 class="text-sm font-medium text-gray-900 mb-2">Notes:</h5>
                                    <p class="text-sm text-gray-700">{{ $application->notes }}</p>
                                </div>
                            @endif
                        </div>

                        <!-- Actions Footer -->
                        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
                            <div class="flex items-center justify-between">
                                <button wire:click="viewApplication({{ $application->id }})" 
                                        class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    View Full Details
                                </button>
                                
                                <div class="flex items-center space-x-2">
                                    @if($application->status === 'submitted')
                                        <button wire:click="startReview({{ $application->id }})" 
                                                class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors">
                                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                            </svg>
                                            Start Review
                                        </button>
                                    @endif

                                    @if(in_array($application->status, ['submitted', 'under_review']))
                                        <button wire:click="approveApplication({{ $application->id }})" 
                                                class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg text-sm font-medium hover:bg-green-700 transition-colors">
                                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            Approve
                                        </button>
                                        <button wire:click="rejectApplication({{ $application->id }})" 
                                                class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg text-sm font-medium hover:bg-red-700 transition-colors">
                                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                            Reject
                                        </button>
                                    @endif

                                    @if($application->status === 'approved')
                                        <button wire:click="markDisbursed({{ $application->id }})" 
                                                class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-lg text-sm font-medium hover:bg-purple-700 transition-colors">
                                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                            </svg>
                                            Mark Disbursed
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No applications found</h3>
                        <p class="mt-1 text-sm text-gray-500">Try adjusting your search or filter criteria.</p>
                    </div>
                @endforelse
            </div>
        <!-- Pagination -->
        <div class="mt-8">
            {{ $applications->links() }}
        </div>




{{-- APPLICATION DETAIL VIEW (when currentStep === 'view') --}}
@elseif($currentStep === 'view' && $selectedApplication)
    <div class="max-w-7xl mx-auto">
        <!-- Header with Back Button -->
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center space-x-4">
                <button wire:click="backToList" 
                        class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to Applications
                </button>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Application Details</h1>
                    <p class="text-sm text-gray-600">{{ $selectedApplication->application_number }}</p>
                </div>
            </div>
            
            <div class="flex items-center space-x-3">
                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium
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

                <!-- Quick Actions -->
                <div class="flex items-center space-x-2">
                    @if($selectedApplication->status === 'submitted')
                        <button wire:click="startReview({{ $selectedApplication->id }})" 
                                class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            Start Review
                        </button>
                    @endif

                    @if(in_array($selectedApplication->status, ['submitted', 'under_review']))
                        <button wire:click="approveApplication({{ $selectedApplication->id }})" 
                                class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg text-sm font-medium hover:bg-green-700 transition-colors">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Approve
                        </button>
                        <button wire:click="rejectApplication({{ $selectedApplication->id }})" 
                                class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg text-sm font-medium hover:bg-red-700 transition-colors">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Reject
                        </button>
                    @endif

                    @if($selectedApplication->status === 'approved')
                        <button wire:click="markDisbursed({{ $selectedApplication->id }})" 
                                class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-lg text-sm font-medium hover:bg-purple-700 transition-colors">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                            </svg>
                            Mark Disbursed
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <!-- Applicant Summary Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-8">
            <div class="flex items-start justify-between">
                <div class="flex items-center space-x-6">
                    <div class="h-20 w-20 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center">
                        <span class="text-2xl font-bold text-white">
                            {{ substr($selectedApplication->first_name, 0, 1) }}{{ substr($selectedApplication->last_name, 0, 1) }}
                        </span>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">
                            {{ $selectedApplication->first_name }} {{ $selectedApplication->last_name }}
                        </h2>
                        <div class="mt-2 space-y-1">
                            <p class="text-gray-600 flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                {{ $selectedApplication->email }}
                            </p>
                            <p class="text-gray-600 flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                                {{ $selectedApplication->phone_number }}
                            </p>
                            <p class="text-gray-600 flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/>
                                </svg>
                                National ID: {{ $selectedApplication->national_id }}
                            </p>
                        </div>
                    </div>
                </div>
                
                <!-- Key Metrics -->
                <div class="grid grid-cols-3 gap-6 text-center">
                    <div class="p-4 bg-blue-50 rounded-lg">
                        <div class="text-2xl font-bold text-blue-600">TSh {{ number_format($selectedApplication->requested_amount/1000000, 1) }}M</div>
                        <div class="text-sm text-blue-700">Requested Amount</div>
                    </div>
                    <div class="p-4 bg-green-50 rounded-lg">
                        <div class="text-2xl font-bold text-green-600">TSh {{ number_format($selectedApplication->total_monthly_income/1000, 0) }}K</div>
                        <div class="text-sm text-green-700">Monthly Income</div>
                    </div>
                    <div class="p-4 {{ $selectedApplication->debt_to_income_ratio <= 30 ? 'bg-green-50' : ($selectedApplication->debt_to_income_ratio <= 40 ? 'bg-yellow-50' : 'bg-red-50') }} rounded-lg">
                        <div class="text-2xl font-bold {{ $selectedApplication->debt_to_income_ratio <= 30 ? 'text-green-600' : ($selectedApplication->debt_to_income_ratio <= 40 ? 'text-yellow-600' : 'text-red-600') }}">
                            {{ number_format($selectedApplication->debt_to_income_ratio ?? 0, 1) }}%
                        </div>
                        <div class="text-sm {{ $selectedApplication->debt_to_income_ratio <= 30 ? 'text-green-700' : ($selectedApplication->debt_to_income_ratio <= 40 ? 'text-yellow-700' : 'text-red-700') }}">DSR</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabbed Content -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <!-- Tab Navigation -->
            <div class="border-b border-gray-200">
                <nav class="flex space-x-8 px-6" aria-label="Tabs">
                    <button wire:click="switchTab('overview')" 
                            id="tab-overview"
                            class="tab-button border-b-2 border-blue-500 text-blue-600 py-4 px-1 text-sm font-medium">
                        Overview
                    </button>
                    <button wire:click="switchTab('personal')" 
                            id="tab-personal"
                            class="tab-button border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 py-4 px-1 text-sm font-medium">
                        Personal Details
                    </button>

                    <button wire:click="switchTab('financial')" 
                            id="tab-financial"
                            class="tab-button border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 py-4 px-1 text-sm font-medium">
                        Financial Information
                    </button>
                    <button wire:click="switchTab('employment')" 
                            id="tab-employment"
                            class="tab-button border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 py-4 px-1 text-sm font-medium">
                        Employment
                    </button>
                    <button wire:click="switchTab('documents')" 
                            id="tab-documents"
                            class="tab-button border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 py-4 px-1 text-sm font-medium">
                        Documents
                        <span class="ml-2 bg-gray-100 text-gray-600 py-0.5 px-2 rounded-full text-xs">{{ $selectedApplication->documents->count() }}</span>
                    </button>
                    <button wire:click="switchTab('history')" 
                            id="tab-history"
                            class="tab-button border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 py-4 px-1 text-sm font-medium">
                        History & Notes
                    </button>


                    <button wire:click="switchTab('creditReport')" 
                            id="tab-history"
                            class="tab-button border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 py-4 px-1 text-sm font-medium">
                        History & Notes
                    </button>


                </nav>
            </div>

            <!-- Tab Content -->
            <div class="p-6">
                <!-- Overview Tab -->

                @if($this->tabName=="overview")
                <div id="content-overview" class="tab-content">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Loan Details -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Loan Application Details</h3>
                            <div class="bg-gray-50 rounded-lg p-6 space-y-4">
                                <div class="flex justify-between items-center py-3 border-b border-gray-200">
                                    <span class="text-sm font-medium text-gray-600">Application Number</span>
                                    <span class="text-sm font-bold text-gray-900">{{ $selectedApplication->application_number }}</span>
                                </div>
                                <div class="flex justify-between items-center py-3 border-b border-gray-200">
                                    <span class="text-sm font-medium text-gray-600">Loan Product</span>
                                    <span class="text-sm font-bold text-gray-900">{{ $selectedApplication->loanProduct->name ?? 'N/A' }}</span>
                                </div>
                                <div class="flex justify-between items-center py-3 border-b border-gray-200">
                                    <span class="text-sm font-medium text-gray-600">Requested Amount</span>
                                    <span class="text-sm font-bold text-gray-900">TSh {{ number_format($selectedApplication->requested_amount) }}</span>
                                </div>
                                <div class="flex justify-between items-center py-3 border-b border-gray-200">
                                    <span class="text-sm font-medium text-gray-600">Tenure</span>
                                    <span class="text-sm font-bold text-gray-900">{{ $selectedApplication->requested_tenure_months }} months</span>
                                </div>
                                <div class="flex justify-between items-center py-3 border-b border-gray-200">
                                    <span class="text-sm font-medium text-gray-600">Purpose</span>
                                    <span class="text-sm font-bold text-gray-900">{{ ucwords(str_replace('_', ' ', $selectedApplication->loan_purpose ?? 'N/A')) }}</span>
                                </div>
                                <div class="flex justify-between items-center py-3">
                                    <span class="text-sm font-medium text-gray-600">Application Date</span>
                                    <span class="text-sm font-bold text-gray-900">{{ $selectedApplication->created_at->format('M d, Y H:i') }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Risk Assessment -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Risk Assessment</h3>
                            <div class="bg-gray-50 rounded-lg p-6 space-y-4">
                                <div class="flex justify-between items-center py-3 border-b border-gray-200">
                                    <span class="text-sm font-medium text-gray-600">Credit Score</span>
                                    <span class="text-sm font-bold text-gray-900">{{ $selectedApplication->credit_score ?? 'N/A' }}</span>
                                </div>
                                <div class="flex justify-between items-center py-3 border-b border-gray-200">
                                    <span class="text-sm font-medium text-gray-600">Debt-to-Income Ratio</span>
                                    <span class="text-sm font-bold {{ $selectedApplication->debt_to_income_ratio <= 30 ? 'text-green-600' : ($selectedApplication->debt_to_income_ratio <= 40 ? 'text-yellow-600' : 'text-red-600') }}">
                                        {{ number_format($selectedApplication->debt_to_income_ratio ?? 0, 1) }}%
                                    </span>
                                </div>
                                <div class="flex justify-between items-center py-3 border-b border-gray-200">
                                    <span class="text-sm font-medium text-gray-600">Monthly Obligations</span>
                                    <span class="text-sm font-bold text-gray-900">TSh {{ number_format($selectedApplication->total_monthly_obligations ?? 0) }}</span>
                                </div>
                                <div class="flex justify-between items-center py-3 border-b border-gray-200">
                                    <span class="text-sm font-medium text-gray-600">Net Monthly Income</span>
                                    <span class="text-sm font-bold text-gray-900">TSh {{ number_format(($selectedApplication->total_monthly_income ?? 0) - ($selectedApplication->total_monthly_obligations ?? 0)) }}</span>
                                </div>
                                <div class="flex justify-between items-center py-3">
                                    <span class="text-sm font-medium text-gray-600">Risk Level</span>
                                    @php
                                        $dsr = $selectedApplication->debt_to_income_ratio ?? 0;
                                        $riskLevel = $dsr <= 30 ? 'Low' : ($dsr <= 40 ? 'Medium' : 'High');
                                        $riskColor = $dsr <= 30 ? 'text-green-600' : ($dsr <= 40 ? 'text-yellow-600' : 'text-red-600');
                                    @endphp
                                    <span class="text-sm font-bold {{ $riskColor }}">{{ $riskLevel }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Application Timeline -->
                    <div class="mt-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Application Timeline</h3>
                        <div class="bg-gray-50 rounded-lg p-6">
                            <div class="flow-root">
                                <ul class="-mb-8">
                                    <li>
                                        <div class="relative pb-8">
                                            <div class="relative flex space-x-3">
                                                <div>
                                                    <span class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center ring-8 ring-white">
                                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                                        </svg>
                                                    </span>
                                                </div>
                                                <div class="min-w-0 flex-1 pt-1.5">
                                                    <div>
                                                        <p class="text-sm text-gray-500">Application submitted</p>
                                                        <p class="text-xs text-gray-400">{{ $selectedApplication->created_at->format('M d, Y H:i') }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200"></div>
                                        </div>
                                    </li>

                                    @if($selectedApplication->reviewed_at)
                                        <li>
                                            <div class="relative pb-8">
                                                <div class="relative flex space-x-3">
                                                    <div>
                                                        <span class="h-8 w-8 rounded-full bg-yellow-500 flex items-center justify-center ring-8 ring-white">
                                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                            </svg>
                                                        </span>
                                                    </div>
                                                    <div class="min-w-0 flex-1 pt-1.5">
                                                        <div>
                                                            <p class="text-sm text-gray-500">Review started by {{ $selectedApplication->reviewedByUser->name ?? 'Unknown' }}</p>
                                                            <p class="text-xs text-gray-400">{{ $selectedApplication->reviewed_at->format('M d, Y H:i') }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                @if($selectedApplication->approved_at || $selectedApplication->disbursed_at)
                                                    <div class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200"></div>
                                                @endif
                                            </div>
                                        </li>
                                    @endif

                                    @if($selectedApplication->approved_at)
                                        <li>
                                            <div class="relative pb-8">
                                                <div class="relative flex space-x-3">
                                                    <div>
                                                        <span class="h-8 w-8 rounded-full bg-green-500 flex items-center justify-center ring-8 ring-white">
                                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                            </svg>
                                                        </span>
                                                    </div>
                                                    <div class="min-w-0 flex-1 pt-1.5">
                                                        <div>
                                                            <p class="text-sm text-gray-500">Application approved</p>
                                                            <p class="text-xs text-gray-400">{{ $selectedApplication->approved_at->format('M d, Y H:i') }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                @if($selectedApplication->disbursed_at)
                                                    <div class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200"></div>
                                                @endif
                                            </div>
                                        </li>
                                    @endif

                                    @if($selectedApplication->disbursed_at)
                                        <li>
                                            <div class="relative">
                                                <div class="relative flex space-x-3">
                                                    <div>
                                                        <span class="h-8 w-8 rounded-full bg-purple-500 flex items-center justify-center ring-8 ring-white">
                                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                                            </svg>
                                                        </span>
                                                    </div>
                                                    <div class="min-w-0 flex-1 pt-1.5">
                                                        <div>
                                                            <p class="text-sm text-gray-500">Loan disbursed</p>
                                                            <p class="text-xs text-gray-400">{{ $selectedApplication->disbursed_at->format('M d, Y H:i') }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>


                @elseif($this->tabName=="personal")

                <!-- Personal Details Tab -->
                <div id="content-personal" class="tab-content ">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Personal Information -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Personal Information</h3>
                            <div class="bg-gray-50 rounded-lg p-6 space-y-4">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="text-sm font-medium text-gray-600">First Name</label>
                                        <p class="text-sm font-bold text-gray-900 mt-1">{{ $selectedApplication->alternative_phone ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-600">Residential Address</label>
                                    <p class="text-sm font-bold text-gray-900 mt-1">{{ $selectedApplication->residential_address ?? 'N/A' }}</p>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="text-sm font-medium text-gray-600">City</label>
                                        <p class="text-sm font-bold text-gray-900 mt-1">{{ $selectedApplication->city ?? 'N/A' }}</p>
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium text-gray-600">Region</label>
                                        <p class="text-sm font-bold text-gray-900 mt-1">{{ $selectedApplication->region ?? 'N/A' }}</p>
                                    </div>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-600">Postal Code</label>
                                    <p class="text-sm font-bold text-gray-900 mt-1">{{ $selectedApplication->postal_code ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @elseif($this->tabName=="emergence")


                    <!-- Emergency Contact -->
                    <div class="mt-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Emergency Contact</h3>
                        <div class="bg-gray-50 rounded-lg p-6">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <label class="text-sm font-medium text-gray-600">Full Name</label>
                                    <p class="text-sm font-bold text-gray-900 mt-1">{{ $selectedApplication->emergency_contact_name ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-600">Relationship</label>
                                    <p class="text-sm font-bold text-gray-900 mt-1">{{ ucwords(str_replace('_', ' ', $selectedApplication->emergency_contact_relationship ?? 'N/A')) }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-600">Phone Number</label>
                                    <p class="text-sm font-bold text-gray-900 mt-1">{{ $selectedApplication->emergency_contact_phone ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                @elseif($this->tabName=="financial")


              

                <!-- Financial Information Tab -->
                <div id="content-financial" class="tab-content ">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Income Details -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Income Information</h3>
                            <div class="bg-gray-50 rounded-lg p-6 space-y-4">
                                <div>
                                    <label class="text-sm font-medium text-gray-600">Total Monthly Income</label>
                                    <p class="text-lg font-bold text-gray-900 mt-1">TSh {{ number_format($selectedApplication->total_monthly_income ?? 0) }}</p>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="text-sm font-medium text-gray-600">Primary Income</label>
                                        <p class="text-sm font-bold text-gray-900 mt-1">TSh {{ number_format($selectedApplication->primary_income ?? 0) }}</p>
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium text-gray-600">Secondary Income</label>
                                        <p class="text-sm font-bold text-gray-900 mt-1">TSh {{ number_format($selectedApplication->secondary_income ?? 0) }}</p>
                                    </div>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-600">Other Income Sources</label>
                                    <p class="text-sm font-bold text-gray-900 mt-1">TSh {{ number_format($selectedApplication->other_income ?? 0) }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-600">Income Verification</label>
                                    <p class="text-sm font-bold text-gray-900 mt-1">{{ ucwords(str_replace('_', ' ', $selectedApplication->income_verification_status ?? 'Pending')) }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Expenses & Obligations -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Monthly Obligations</h3>
                            <div class="bg-gray-50 rounded-lg p-6 space-y-4">
                                <div>
                                    <label class="text-sm font-medium text-gray-600">Total Monthly Obligations</label>
                                    <p class="text-lg font-bold text-red-600 mt-1">TSh {{ number_format($selectedApplication->total_monthly_obligations ?? 0) }}</p>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="text-sm font-medium text-gray-600">Rent/Mortgage</label>
                                        <p class="text-sm font-bold text-gray-900 mt-1">TSh {{ number_format($selectedApplication->monthly_rent ?? 0) }}</p>
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium text-gray-600">Loan Payments</label>
                                        <p class="text-sm font-bold text-gray-900 mt-1">TSh {{ number_format($selectedApplication->existing_loan_payments ?? 0) }}</p>
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="text-sm font-medium text-gray-600">Credit Cards</label>
                                        <p class="text-sm font-bold text-gray-900 mt-1">TSh {{ number_format($selectedApplication->credit_card_payments ?? 0) }}</p>
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium text-gray-600">Other Expenses</label>
                                        <p class="text-sm font-bold text-gray-900 mt-1">TSh {{ number_format($selectedApplication->other_monthly_expenses ?? 0) }}</p>
                                    </div>
                                </div>
                                <div class="pt-4 border-t border-gray-200">
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm font-medium text-gray-600">Net Monthly Income</span>
                                        <span class="text-lg font-bold text-green-600">
                                            TSh {{ number_format(($selectedApplication->total_monthly_income ?? 0) - ($selectedApplication->total_monthly_obligations ?? 0)) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Assets & Credit History -->
                    <div class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Assets -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Assets & Collateral</h3>
                            <div class="bg-gray-50 rounded-lg p-6 space-y-4">
                                <div>
                                    <label class="text-sm font-medium text-gray-600">Bank Account Balance</label>
                                    <p class="text-sm font-bold text-gray-900 mt-1">TSh {{ number_format($selectedApplication->bank_account_balance ?? 0) }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-600">Property Value</label>
                                    <p class="text-sm font-bold text-gray-900 mt-1">TSh {{ number_format($selectedApplication->property_value ?? 0) }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-600">Vehicle Value</label>
                                    <p class="text-sm font-bold text-gray-900 mt-1">TSh {{ number_format($selectedApplication->vehicle_value ?? 0) }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-600">Other Assets</label>
                                    <p class="text-sm font-bold text-gray-900 mt-1">TSh {{ number_format($selectedApplication->other_assets ?? 0) }}</p>
                                </div>
                                <div class="pt-4 border-t border-gray-200">
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm font-medium text-gray-600">Total Assets</span>
                                        <span class="text-lg font-bold text-blue-600">
                                            TSh {{ number_format(($selectedApplication->bank_account_balance ?? 0) + ($selectedApplication->property_value ?? 0) + ($selectedApplication->vehicle_value ?? 0) + ($selectedApplication->other_assets ?? 0)) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Credit History -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Credit History</h3>
                            <div class="bg-gray-50 rounded-lg p-6 space-y-4">
                                <div>
                                    <label class="text-sm font-medium text-gray-600">Credit Score</label>
                                    <p class="text-lg font-bold text-gray-900 mt-1">{{ $selectedApplication->credit_score ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-600">Credit Bureau</label>
                                    <p class="text-sm font-bold text-gray-900 mt-1">{{ $selectedApplication->credit_bureau ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-600">Previous Defaults</label>
                                    <p class="text-sm font-bold text-gray-900 mt-1">{{ $selectedApplication->has_previous_defaults ? 'Yes' : 'No' }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-600">Bankruptcy History</label>
                                    <p class="text-sm font-bold text-gray-900 mt-1">{{ $selectedApplication->has_bankruptcy_history ? 'Yes' : 'No' }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-600">Current Loans</label>
                                    <p class="text-sm font-bold text-gray-900 mt-1">{{ $selectedApplication->number_of_current_loans ?? 0 }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @elseif($this->tabName=="employment")

                <!-- Employment Tab -->
                <div id="content-employment" class="tab-content ">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Current Employment -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Current Employment</h3>
                            <div class="bg-gray-50 rounded-lg p-6 space-y-4">
                                <div>
                                    <label class="text-sm font-medium text-gray-600">Employment Status</label>
                                    <p class="text-sm font-bold text-gray-900 mt-1">{{ ucwords(str_replace('_', ' ', $selectedApplication->employment_status ?? 'N/A')) }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-600">Company Name</label>
                                    <p class="text-sm font-bold text-gray-900 mt-1">{{ $selectedApplication->employer_name ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-600">Job Title</label>
                                    <p class="text-sm font-bold text-gray-900 mt-1">{{ $selectedApplication->job_title ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-600">Industry</label>
                                    <p class="text-sm font-bold text-gray-900 mt-1">{{ $selectedApplication->industry ?? 'N/A' }}</p>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="text-sm font-medium text-gray-600">Years with Employer</label>
                                        <p class="text-sm font-bold text-gray-900 mt-1">{{ $selectedApplication->years_with_employer ?? 'N/A' }} years</p>
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium text-gray-600">Employment Type</label>
                                        <p class="text-sm font-bold text-gray-900 mt-1">{{ ucwords(str_replace('_', ' ', $selectedApplication->employment_type ?? 'N/A')) }}</p>
                                    </div>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-600">Employee ID</label>
                                    <p class="text-sm font-bold text-gray-900 mt-1">{{ $selectedApplication->employee_id ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Employer Contact -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Employer Information</h3>
                            <div class="bg-gray-50 rounded-lg p-6 space-y-4">
                                <div>
                                    <label class="text-sm font-medium text-gray-600">Company Address</label>
                                    <p class="text-sm font-bold text-gray-900 mt-1">{{ $selectedApplication->employer_address ?? 'N/A' }}</p>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="text-sm font-medium text-gray-600">Company Phone</label>
                                        <p class="text-sm font-bold text-gray-900 mt-1">{{ $selectedApplication->employer_phone ?? 'N/A' }}</p>
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium text-gray-600">HR Contact</label>
                                        <p class="text-sm font-bold text-gray-900 mt-1">{{ $selectedApplication->hr_contact_name ?? 'N/A' }}</p>
                                    </div>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-600">HR Email</label>
                                    <p class="text-sm font-bold text-gray-900 mt-1">{{ $selectedApplication->hr_contact_email ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-600">Supervisor Name</label>
                                    <p class="text-sm font-bold text-gray-900 mt-1">{{ $selectedApplication->supervisor_name ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-600">Supervisor Phone</label>
                                    <p class="text-sm font-bold text-gray-900 mt-1">{{ $selectedApplication->supervisor_phone ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Previous Employment (if applicable) -->
                    @if($selectedApplication->previous_employer_name)
                        <div class="mt-8">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Previous Employment</h3>
                            <div class="bg-gray-50 rounded-lg p-6">
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                    <div>
                                        <label class="text-sm font-medium text-gray-600">Previous Employer</label>
                                        <p class="text-sm font-bold text-gray-900 mt-1">{{ $selectedApplication->previous_employer_name }}</p>
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium text-gray-600">Previous Job Title</label>
                                        <p class="text-sm font-bold text-gray-900 mt-1">{{ $selectedApplication->previous_job_title ?? 'N/A' }}</p>
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium text-gray-600">Duration</label>
                                        <p class="text-sm font-bold text-gray-900 mt-1">{{ $selectedApplication->previous_employment_duration ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                @elseif($this->tabName=="documents")

                <!-- Documents Tab -->
                <div id="content-documents" class="tab-content ">
                    <div class="space-y-6">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900">Uploaded Documents</h3>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                {{ $selectedApplication->documents->count() }} Documents
                            </span>
                        </div>

                        @if($selectedApplication->documents->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                @foreach($selectedApplication->documents as $document)
                                    <div class="bg-gray-50 rounded-lg p-6 hover:bg-gray-100 transition-colors cursor-pointer">
                                        <div class="flex items-center space-x-4">
                                            <div class="flex-shrink-0">
                                                @php
                                                    $extension = pathinfo($document->file_name, PATHINFO_EXTENSION);
                                                @endphp
                                                @if(in_array($extension, ['jpg', 'jpeg', 'png', 'gif']))
                                                    <div class="h-12 w-12 bg-green-100 rounded-lg flex items-center justify-center">
                                                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                        </svg>
                                                    </div>
                                                @elseif($extension === 'pdf')
                                                    <div class="h-12 w-12 bg-red-100 rounded-lg flex items-center justify-center">
                                                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                                        </svg>
                                                    </div>
                                                @else
                                                    <div class="h-12 w-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                        </svg>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-gray-900 truncate">{{ $document->document_type }}</p>
                                                <p class="text-sm text-gray-500 truncate">{{ $document->file_name }}</p>
                                                <p class="text-xs text-gray-400">{{ number_format($document->file_size / 1024, 1) }} KB</p>
                                            </div>
                                        </div>
                                        <div class="mt-4 flex items-center justify-between">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $document->verification_status === 'verified' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                {{ ucfirst($document->verification_status) }}
                                            </span>
                                            <button class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                                Download
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-12">
                                <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">No documents uploaded</h3>
                                <p class="mt-1 text-sm text-gray-500">Documents will appear here once uploaded by the applicant.</p>
                            </div>
                        @endif
                    </div>
                </div>

                @elseif($this->tabName=="history")

                <!-- History & Notes Tab -->
                <div id="content-history" class="tab-content ">
                    <div class="space-y-6">
                        <!-- Application Notes -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Application Notes</h3>
                            
                            <!-- Add New Note -->
                            <div class="bg-gray-50 rounded-lg p-6 mb-6">
                                <div class="space-y-4">
                                    <div>
                                        <label for="applicationNotes" class="block text-sm font-medium text-gray-700 mb-2">Add Note</label>
                                        <textarea wire:model="applicationNotes" id="applicationNotes" rows="4" 
                                                  class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                  placeholder="Add your notes about this application..."></textarea>
                                    </div>
                                    <div class="flex items-center space-x-3">
                                        <button type="button" 
                                                class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors">
                                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                            </svg>
                                            Add Note
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Existing Notes -->
                            @if($selectedApplication->notes)
                                <div class="bg-white border border-gray-200 rounded-lg p-6">
                                    <div class="flex items-start space-x-4">
                                        <div class="h-10 w-10 bg-gray-200 rounded-full flex items-center justify-center">
                                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                            </svg>
                                        </div>
                                        <div class="flex-1">
                                            <div class="flex items-center space-x-2 mb-2">
                                                <h4 class="text-sm font-medium text-gray-900">{{ $selectedApplication->reviewedByUser->name ?? 'System' }}</h4>
                                                <span class="text-xs text-gray-500">{{ $selectedApplication->updated_at->diffForHumans() }}</span>
                                            </div>
                                            <p class="text-sm text-gray-700">{{ $selectedApplication->notes }}</p>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="text-center py-8">
                                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">No notes yet</h3>
                                    <p class="mt-1 text-sm text-gray-500">Add notes to track application progress and decisions.</p>
                                </div>
                            @endif
                        </div>

                        <!-- Status History -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Status History</h3>
                            <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                                <ul class="divide-y divide-gray-200">
                                    <li class="p-4">
                                        <div class="flex items-center space-x-4">
                                            <div class="flex-shrink-0">
                                                <span class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center">
                                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                                    </svg>
                                                </span>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-gray-900">Application submitted</p>
                                                <p class="text-sm text-gray-500">{{ $selectedApplication->created_at->format('M d, Y H:i') }}</p>
                                            </div>
                                            <div class="text-right">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    Submitted
                                                </span>
                                            </div>
                                        </div>
                                    </li>

                                    @if($selectedApplication->reviewed_at)
                                        <li class="p-4">
                                            <div class="flex items-center space-x-4">
                                                <div class="flex-shrink-0">
                                                    <span class="h-8 w-8 rounded-full bg-yellow-500 flex items-center justify-center">
                                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                        </svg>
                                                    </span>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-sm font-medium text-gray-900">Review started by {{ $selectedApplication->reviewedByUser->name ?? 'Unknown' }}</p>
                                                    <p class="text-sm text-gray-500">{{ $selectedApplication->reviewed_at->format('M d, Y H:i') }}</p>
                                                </div>
                                                <div class="text-right">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                        Under Review
                                                    </span>
                                                </div>
                                            </div>
                                        </li>
                                    @endif

                                    @if($selectedApplication->approved_at)
                                        <li class="p-4">
                                            <div class="flex items-center space-x-4">
                                                <div class="flex-shrink-0">
                                                    <span class="h-8 w-8 rounded-full bg-green-500 flex items-center justify-center">
                                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                        </svg>
                                                    </span>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-sm font-medium text-gray-900">Application approved</p>
                                                    <p class="text-sm text-gray-500">{{ $selectedApplication->approved_at->format('M d, Y H:i') }}</p>
                                                </div>
                                                <div class="text-right">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        Approved
                                                    </span>
                                                </div>
                                            </div>
                                        </li>
                                    @endif

                                    @if($selectedApplication->status === 'rejected')
                                        <li class="p-4">
                                            <div class="flex items-center space-x-4">
                                                <div class="flex-shrink-0">
                                                    <span class="h-8 w-8 rounded-full bg-red-500 flex items-center justify-center">
                                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                        </svg>
                                                    </span>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-sm font-medium text-gray-900">Application rejected</p>
                                                    <p class="text-sm text-gray-500">{{ $selectedApplication->reviewed_at ? $selectedApplication->reviewed_at->format('M d, Y H:i') : 'N/A' }}</p>
                                                    @if($selectedApplication->rejection_reasons && count($selectedApplication->rejection_reasons) > 0)
                                                        <div class="mt-2">
                                                            <p class="text-xs text-gray-600 mb-1">Rejection reasons:</p>
                                                            <ul class="text-xs text-red-600 list-disc list-inside">
                                                                @foreach($selectedApplication->rejection_reasons as $reason)
                                                                    <li>{{ $reason }}</li>
                                                                @endforeach
                                                            </ul>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="text-right">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                        Rejected
                                                    </span>
                                                </div>
                                            </div>
                                        </li>
                                    @endif

                                    @if($selectedApplication->disbursed_at)
                                        <li class="p-4">
                                            <div class="flex items-center space-x-4">
                                                <div class="flex-shrink-0">
                                                    <span class="h-8 w-8 rounded-full bg-purple-500 flex items-center justify-center">
                                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                                        </svg>
                                                    </span>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-sm font-medium text-gray-900">Loan disbursed</p>
                                                    <p class="text-sm text-gray-500">{{ $selectedApplication->disbursed_at->format('M d, Y H:i') }}</p>
                                                </div>
                                                <div class="text-right">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                        Disbursed
                                                    </span>
                                                </div>
                                            </div>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </div>

                        <!-- Rejection Reasons (if applicable) -->
                        @if($selectedApplication->status === 'rejected' && $selectedApplication->rejection_reasons)
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Rejection Details</h3>
                                <div class="bg-red-50 border border-red-200 rounded-lg p-6">
                                    <div class="flex items-start space-x-3">
                                        <div class="flex-shrink-0">
                                            <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <h4 class="text-sm font-medium text-red-800 mb-2">Application was rejected for the following reasons:</h4>
                                            <ul class="text-sm text-red-700 list-disc list-inside space-y-1">
                                                @foreach($selectedApplication->rejection_reasons as $reason)
                                                    <li>{{ $reason }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Communication Log -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Communication Log</h3>
                            <div class="bg-gray-50 rounded-lg p-6">
                                <div class="text-center py-8">
                                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">No communications yet</h3>
                                    <p class="mt-1 text-sm text-gray-500">Email and SMS communications with the applicant will appear here.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

              



                @elseif($this->tabName=="creditReport")
                <div id="content-history" class="tab-content ">
                <div class="space-y-6">

                @php  
                
                $applicationId= $selectedApplication->id;
                  @endphp


{{ $applicationId }}

                <livewire:credit-info-component  :applicationId="$applicationId" />

                </div>
                </div>

                @endif



                


            </div>
        </div>

        <!-- Action Panel -->
        @if(in_array($selectedApplication->status, ['submitted', 'under_review']))
            <div class="mt-8 bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Review Actions</h3>
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Approval Section -->
                    <div class="space-y-4">
                        <h4 class="text-md font-medium text-green-700">Approve Application</h4>
                        <div class="bg-green-50 rounded-lg p-4 space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Approved Amount</label>
                                    <input type="number" wire:model="approvalAmount" 
                                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500"
                                           placeholder="{{ $selectedApplication->requested_amount }}">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Tenure (months)</label>
                                    <input type="number" wire:model="approvalTenure" 
                                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500"
                                           placeholder="{{ $selectedApplication->requested_tenure_months }}">
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Interest Rate (%)</label>
                                <input type="number" step="0.1" wire:model="approvalInterestRate" 
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500"
                                       placeholder="Enter interest rate">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                                <textarea wire:model="applicationNotes" rows="3" 
                                          class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500"
                                          placeholder="Add approval notes..."></textarea>
                            </div>
                            <button wire:click="approveApplication({{ $selectedApplication->id }})" 
                                    class="w-full inline-flex justify-center items-center px-4 py-2 bg-green-600 text-white rounded-lg text-sm font-medium hover:bg-green-700 transition-colors">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Approve Application
                            </button>
                        </div>
                    </div>

                    <!-- Rejection Section -->
                    <div class="space-y-4">
                        <h4 class="text-md font-medium text-red-700">Reject Application</h4>
                        <div class="bg-red-50 rounded-lg p-4 space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Rejection Reasons</label>
                                <div class="space-y-2">
                                    <label class="flex items-center">
                                        <input type="checkbox" wire:model="rejectionReasons" value="insufficient_income" 
                                               class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                                        <span class="ml-2 text-sm text-gray-700">Insufficient Income</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" wire:model="rejectionReasons" value="poor_credit_score" 
                                               class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                                        <span class="ml-2 text-sm text-gray-700">Poor Credit Score</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" wire:model="rejectionReasons" value="high_dsr" 
                                               class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                                        <span class="ml-2 text-sm text-gray-700">High Debt-to-Income Ratio</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" wire:model="rejectionReasons" value="incomplete_documentation" 
                                               class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                                        <span class="ml-2 text-sm text-gray-700">Incomplete Documentation</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" wire:model="rejectionReasons" value="employment_verification_failed" 
                                               class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                                        <span class="ml-2 text-sm text-gray-700">Employment Verification Failed</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" wire:model="rejectionReasons" value="other" 
                                               class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                                        <span class="ml-2 text-sm text-gray-700">Other</span>
                                    </label>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Additional Notes</label>
                                <textarea wire:model="applicationNotes" rows="3" 
                                          class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-red-500"
                                          placeholder="Add rejection notes..."></textarea>
                            </div>
                            <button wire:click="rejectApplication({{ $selectedApplication->id }})" 
                                    class="w-full inline-flex justify-center items-center px-4 py-2 bg-red-600 text-white rounded-lg text-sm font-medium hover:bg-red-700 transition-colors">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                Reject Application
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
