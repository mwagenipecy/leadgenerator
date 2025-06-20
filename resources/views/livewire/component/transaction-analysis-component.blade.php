<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50">
    <!-- Header with Logo and Title -->
    <div class="bg-white shadow-lg border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <div class="flex items-center space-x-4">
                    <!-- Logo placeholder - replace with your actual logo -->
                    <div class="flex-shrink-0">
                        <!-- <div class="w-12 h-12 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg"> -->
                            <!-- <i class="fas fa-chart-line text-white text-xl"></i> -->
                            <img src="{{ asset('/logo/cit_logo.svg') }}" class="text-white text-xl" />
                        <!-- </div> -->
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Transaction Analytics</h1>
                        <p class="text-sm text-gray-500">Financial Analysis Dashboard</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="hidden md:flex items-center space-x-2 text-sm text-gray-500">
                        <i class="fas fa-clock"></i>
                        <span>Last updated: {{ now()->format('M d, Y H:i') }}</span>
                    </div>
                    <button class="p-2 rounded-lg bg-gray-100 hover:bg-gray-200 transition-colors">
                        <i class="fas fa-bell text-gray-600"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Search and Filter Section -->
        <div class="mb-8">
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-6 backdrop-blur-sm">
                <div class="flex items-center mb-6">
                    <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-lg flex items-center justify-center mr-4">
                        <i class="fas fa-search text-white"></i>
                    </div>
                    <h2 class="text-xl font-semibold text-gray-800">Search & Filter</h2>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="space-y-2">
                        <label for="search" class="block text-sm font-medium text-gray-700">
                            <i class="fas fa-money mr-2"></i>Search Account Number
                        </label>
                        <div class="relative">
                            <input wire:model.live="search" 
                                   type="text" 
                                   id="search" 
                                   class="w-full pl-10 pr-4 py-3 rounded-xl border-2 border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200" 
                                   placeholder="Enter account number...">
                            <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        </div>
                    </div>
                    
                    <div class="space-y-2">
                        <label for="status" class="block text-sm font-medium text-gray-700">
                            <i class="fas fa-filter mr-2"></i>Status Filter
                        </label>
                        <div class="relative">
                            <select wire:model.live="statusFilter" 
                                    id="status" 
                                    class="w-full pl-10 pr-4 py-3 rounded-xl border-2 border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200 appearance-none">
                                <option value="">All Status</option>
                                <option value="success">Success</option>
                                <option value="failed">Failed</option>
                            </select>
                            <i class="fas fa-chevron-down absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            <i class="fas fa-flag absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        </div>
                    </div>
                    
                    <div class="flex items-end">
                        <button wire:click="$refresh" 
                                class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold py-3 px-6 rounded-xl transition-all duration-200 transform hover:scale-105 shadow-lg hover:shadow-xl">
                            <i class="fas fa-sync-alt mr-2"></i>Refresh Data
                        </button>
                    </div>
                </div>
            </div>
        </div>

       

        <!-- Main Table -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
            <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                    <i class="fas fa-table mr-3 text-gray-600"></i>
                    Transaction Analysis Records
                </h3>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <i class="fas fa-hashtag mr-2"></i>Account Number
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <i class="fas fa-building mr-2"></i>Company
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <i class="fas fa-flag mr-2"></i>Status
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <i class="fas fa-chart-line mr-2"></i>Total Turnover
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <i class="fas fa-wallet mr-2"></i>Wallet Balance
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <i class="fas fa-exchange-alt mr-2"></i>Transactions
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <i class="fas fa-calendar mr-2"></i>Created At
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <i class="fas fa-cogs mr-2"></i>Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($analyses as $analysis)
                            <tr class="hover:bg-gray-50 transition-colors duration-200">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    <div class="flex items-center">
                                        <div class="w-2 h-8 bg-blue-500 rounded-full mr-3"></div>
                                        {{ $analysis->account_number }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center mr-3">
                                            <i class="fas fa-building text-gray-500 text-xs"></i>
                                        </div>
                                        {{ $analysis->company }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $analysis->status === 'success' ? 'bg-green-100 text-green-800 border border-green-200' : 'bg-red-100 text-red-800 border border-red-200' }}">
                                        <i class="fas {{ $analysis->status === 'success' ? 'fa-check' : 'fa-times' }} mr-1"></i>
                                        {{ ucfirst($analysis->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                                    {{ $analysis->formatCurrency($analysis->total_turnover) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                                    {{ $analysis->formatCurrency($analysis->wallet_balance) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <div class="flex items-center">
                                        <span class="bg-purple-100 text-purple-800 px-2 py-1 rounded-full text-xs font-medium">
                                            {{ number_format($analysis->total_transactions) }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <div class="flex items-center">
                                        <i class="fas fa-clock mr-2 text-gray-400"></i>
                                        {{ $analysis->created_at->format('d M Y H:i') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <button wire:click="viewDetails({{ $analysis->id }})" 
                                            class="bg-gradient-to-r from-indigo-500 to-blue-600 hover:from-indigo-600 hover:to-blue-700 text-white px-4 py-2 rounded-lg transition-all duration-200 transform hover:scale-105 shadow-md hover:shadow-lg">
                                        <i class="fas fa-eye mr-2"></i>View Details
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                                    <div class="flex flex-col items-center">
                                        <i class="fas fa-inbox text-6xl text-gray-300 mb-4"></i>
                                        <p class="text-lg font-medium text-gray-400">No transaction analyses found</p>
                                        <p class="text-sm text-gray-400 mt-2">Try adjusting your search filters or create a new analysis</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div class="mt-6 flex justify-center">
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-2">
                {{ $analyses->links() }}
            </div>
        </div>
    </div>

    <!-- Details Modal -->
    @if($showDetails && $selectedAnalysis)
        <div class="fixed inset-0 bg-black bg-opacity-50 overflow-y-auto h-full w-full z-50 backdrop-blur-sm">
            <div class="relative top-10 mx-auto p-0 w-11/12 md:w-4/5 lg:w-3/4 xl:w-2/3 shadow-2xl rounded-3xl bg-white max-h-[90vh] overflow-hidden">
                
                <!-- Modal Header with Logo -->
                <div class="bg-gradient-to-r from-red-600 to-red-700 px-8 py-6 relative">
                    <!-- Logo and Company Info -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <!-- Company Logo -->
                            <div class=" bg-gray-800 rounded-xl flex items-center justify-center shadow-lg">
                            <img src="{{ asset('/logo/creditinfo_logo-2.png') }}" class=" rounded-full shadow-lg" alt="Company Logo" />
                            </div>



                            <div class="text-white">
                                <h2 class="text-2xl font-bold">FinanceAnalytics Pro</h2>
                                <p class="text-blue-100 text-sm">Transaction Analysis Report</p>
                            </div>
                        </div>
                        
                        <!-- Close Button -->
                        <button wire:click="closeDetails" 
                                class="text-white hover:text-gray-200 transition-colors p-2 rounded-full hover:bg-white hover:bg-opacity-20">
                            <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    
                    <!-- Account Info Banner -->
                    <div class="mt-4 bg-white bg-opacity-20 rounded-xl p-4 backdrop-blur-sm">
                        <div class="flex items-center justify-between text-white">
                            <div>
                                <p class="text-sm text-blue-100">Account Number</p>
                                <p class="text-xl font-bold">{{ $selectedAnalysis->account_number }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-blue-100">Company</p>
                                <p class="text-xl font-bold">{{ $selectedAnalysis->company }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-blue-100">Status</p>
                                <span class="px-3 py-1 rounded-full text-sm font-semibold {{ $selectedAnalysis->status === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white' }}">
                                    {{ ucfirst($selectedAnalysis->status) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Content -->
                <div class="px-8 py-6 max-h-[60vh] overflow-y-auto">
                    <div class="space-y-8">
                        <!-- Analysis Period Info -->
                        <div class="bg-gray-50 rounded-xl p-6">
                            <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                                <i class="fas fa-calendar-alt mr-3 text-blue-600"></i>
                                Analysis Period
                            </h4>
                            <div class="bg-white rounded-lg p-4 shadow-sm">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm text-gray-600">Period</p>
                                        <p class="font-semibold text-gray-800">
                                            @if($selectedAnalysis->analysis_period)
                                                {{ $selectedAnalysis->analysis_period['start'] }} - {{ $selectedAnalysis->analysis_period['end'] }}
                                            @else
                                                <span class="text-gray-400">N/A</span>
                                            @endif
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm text-gray-600">Generated</p>
                                        <p class="font-semibold text-gray-800">{{ $selectedAnalysis->created_at->format('d M Y H:i') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Customer Profile -->
                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-6">
                            <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                                <i class="fas fa-user-chart mr-3 text-blue-600"></i>
                                Financial Overview
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div class="bg-white rounded-xl p-6 shadow-sm border-l-4 border-blue-500 hover:shadow-md transition-shadow">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-sm text-blue-600 font-medium">Total Turnover</p>
                                            <p class="text-2xl font-bold text-blue-800 mt-1">{{ $selectedAnalysis->formatCurrency($selectedAnalysis->total_turnover) }}</p>
                                        </div>
                                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                                            <i class="fas fa-arrow-trend-up text-blue-600"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-white rounded-xl p-6 shadow-sm border-l-4 border-green-500 hover:shadow-md transition-shadow">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-sm text-green-600 font-medium">Wallet Balance</p>
                                            <p class="text-2xl font-bold text-green-800 mt-1">{{ $selectedAnalysis->formatCurrency($selectedAnalysis->wallet_balance) }}</p>
                                        </div>
                                        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                                            <i class="fas fa-wallet text-green-600"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-white rounded-xl p-6 shadow-sm border-l-4 border-purple-500 hover:shadow-md transition-shadow">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-sm text-purple-600 font-medium">Total Transactions</p>
                                            <p class="text-2xl font-bold text-purple-800 mt-1">{{ number_format($selectedAnalysis->total_transactions) }}</p>
                                        </div>
                                        <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                                            <i class="fas fa-exchange-alt text-purple-600"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Cash Flow Summary -->
                        @if(isset($selectedAnalysis->analysis_1d['cash_flow_summary']))
                            <div class="bg-gray-50 rounded-xl p-6">
                                <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                                    <i class="fas fa-chart-line mr-3 text-blue-600"></i>
                                    Cash Flow Analysis
                                </h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="bg-white rounded-xl p-6 shadow-sm border-l-4 border-green-500">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <p class="text-sm text-green-600 font-medium flex items-center">
                                                    <i class="fas fa-arrow-down mr-2"></i>Total Cash In
                                                </p>
                                                <p class="text-2xl font-bold text-green-700 mt-1">{{ $selectedAnalysis->formatCurrency($selectedAnalysis->analysis_1d['cash_flow_summary']['total_cashin']) }}</p>
                                            </div>
                                            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                                                <i class="fas fa-plus text-green-600"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="bg-white rounded-xl p-6 shadow-sm border-l-4 border-red-500">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <p class="text-sm text-red-600 font-medium flex items-center">
                                                    <i class="fas fa-arrow-up mr-2"></i>Total Cash Out
                                                </p>
                                                <p class="text-2xl font-bold text-red-700 mt-1">{{ $selectedAnalysis->formatCurrency($selectedAnalysis->analysis_1d['cash_flow_summary']['total_cashout']) }}</p>
                                            </div>
                                            <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                                                <i class="fas fa-minus text-red-600"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Affordability Scores -->
                        @if($selectedAnalysis->affordability_scores)
                            <div class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-xl p-6">
                                <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                                    <i class="fas fa-star mr-3 text-indigo-600"></i>
                                    Affordability Assessment
                                </h4>
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                    <div class="bg-white rounded-xl p-6 shadow-sm text-center border-2 border-yellow-200">
                                        <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                            <span class="text-2xl font-bold text-yellow-600">{{ $selectedAnalysis->affordability_rank }}</span>
                                        </div>
                                        <p class="text-sm text-yellow-600 font-medium">Affordability Rank</p>
                                    </div>
                                    <div class="bg-white rounded-xl p-4 shadow-sm text-center">
                                        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-2">
                                            <i class="fas fa-arrow-up text-green-600"></i>
                                        </div>
                                        <p class="text-xs text-green-600 font-medium">High Capacity</p>
                                        <p class="text-lg font-bold text-green-700">{{ $selectedAnalysis->formatCurrency($selectedAnalysis->affordability_scores['high']) }}</p>
                                    </div>
                                    <div class="bg-white rounded-xl p-4 shadow-sm text-center">
                                        <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-2">
                                            <i class="fas fa-minus text-yellow-600"></i>
                                        </div>
                                        <p class="text-xs text-yellow-600 font-medium">Moderate</p>
                                        <p class="text-lg font-bold text-yellow-700">{{ $selectedAnalysis->formatCurrency($selectedAnalysis->affordability_scores['moderate']) }}</p>
                                    </div>
                                    <div class="bg-white rounded-xl p-4 shadow-sm text-center">
                                        <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-2">
                                            <i class="fas fa-arrow-down text-red-600"></i>
                                        </div>
                                        <p class="text-xs text-red-600 font-medium">Low Capacity</p>
                                        <p class="text-lg font-bold text-red-700">{{ $selectedAnalysis->formatCurrency($selectedAnalysis->affordability_scores['low']) }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                    
                <!-- Modal Footer -->
                <div class="border-t border-gray-200 px-8 py-4 bg-gray-50 rounded-b-3xl">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center text-sm text-gray-500">
                            <i class="fas fa-shield-alt mr-2"></i>
                            <span>Report generated securely by FinanceAnalytics Pro</span>
                        </div>
                        <button wire:click="closeDetails" 
                                class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-xl transition-colors duration-200">
                            <i class="fas fa-times mr-2"></i>Close Report
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>