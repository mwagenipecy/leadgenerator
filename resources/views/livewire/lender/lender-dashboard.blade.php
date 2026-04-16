<div>
    <div class="p-8">
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-4xl font-bold text-gray-900 mb-2">{{ __('dashboard.lender_dashboard') }}</h1>
                    <p class="text-gray-600 text-lg">{{ __('dashboard.lender_dashboard_description') }}</p>
                </div>
                <div class="flex items-center space-x-3">
                    <div class="flex items-center space-x-2 bg-green-50 px-4 py-2 rounded-full">
                        <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
                        <span class="text-sm font-medium text-green-700">{{ __('dashboard.active_lending') }}</span>
                    </div>
                    <button class="bg-white border border-gray-200 text-gray-700 px-4 py-2 rounded-lg font-medium hover:bg-gray-50 transition-colors">
                        {{ __('dashboard.this_month') }}
                    </button>
                </div>
            </div>
        </div>

        <!-- Flash Message -->
        @if (session()->has('message'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-6 py-4 rounded-lg mb-6 flex items-center space-x-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <span>{{ session('message') }}</span>
            </div>
        @endif

        <!-- Key Performance Metrics -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- New Applications Card -->
            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100 hover:shadow-md transition-all duration-300 group hover:border-sidebar-green/20">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-14 h-14 bg-sidebar-green-50 rounded-2xl flex items-center justify-center group-hover:bg-sidebar-green-100 transition-colors">
                        <svg class="w-7 h-7 text-sidebar-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                    </div>
                    <div class="text-right">
                        <p class="text-xs font-medium text-gray-600 uppercase tracking-wide">{{ __('dashboard.new_applications') }}</p>
                        <p class="text-2xl font-bold text-gray-900 group-hover:text-sidebar-green transition-colors">{{ number_format($newApplications) }}</p>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-1 text-sidebar-green">
                        <svg class="w-3 h-3 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="text-xs font-semibold">{{ __('dashboard.awaiting_review') }}</span>
                    </div>
                    <span class="text-xs text-gray-500">{{ $pendingApplications }} {{ __('dashboard.in_review') }}</span>
                </div>
            </div>

            <!-- Total Applications Card -->
            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100 hover:shadow-md transition-all duration-300 group hover:border-black/20">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-14 h-14 bg-gray-50 rounded-2xl flex items-center justify-center group-hover:bg-gray-100 transition-colors">
                        <svg class="w-7 h-7 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div class="text-right">
                        <p class="text-xs font-medium text-gray-600 uppercase tracking-wide">{{ __('dashboard.total_applications') }}</p>
                        <p class="text-2xl font-bold text-gray-900 group-hover:text-black transition-colors">{{ number_format($totalApplications) }}</p>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-1 text-gray-700">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span class="text-xs font-semibold">{{ $approvedApplications }} {{ __('dashboard.approved') }}</span>
                    </div>
                    <span class="text-xs text-gray-500">{{ $conversionRate }}% {{ __('dashboard.conversion_rate') }}</span>
                </div>
            </div>

            <!-- Approved Applications Card -->
            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100 hover:shadow-md transition-all duration-300 group hover:border-black/20">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-14 h-14 bg-gray-50 rounded-2xl flex items-center justify-center group-hover:bg-gray-100 transition-colors">
                        <svg class="w-7 h-7 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="text-right">
                        <p class="text-xs font-medium text-gray-600 uppercase tracking-wide">{{ __('dashboard.approved_loans') }}</p>
                        <p class="text-2xl font-bold text-gray-900 group-hover:text-black transition-colors">{{ number_format($approvedApplications) }}</p>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-1 text-gray-700">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                        </svg>
                        <span class="text-xs font-semibold">{{ $conversionRate }}%</span>
                    </div>
                    <span class="text-xs text-gray-500">{{ __('dashboard.conversion_rate_label') }}</span>
                </div>
            </div>

            <!-- Total Disbursed Card -->
            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100 hover:shadow-md transition-all duration-300 group hover:border-sidebar-green/20">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-14 h-14 bg-sidebar-green-50 rounded-2xl flex items-center justify-center group-hover:bg-sidebar-green-100 transition-colors">
                        <svg class="w-7 h-7 text-sidebar-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                        </svg>
                    </div>
                    <div class="text-right">
                        <p class="text-xs font-medium text-gray-600 uppercase tracking-wide">{{ __('dashboard.total_disbursed') }}</p>
                        <p class="text-2xl font-bold text-gray-900 group-hover:text-sidebar-green transition-colors">
                            @if($totalDisbursed >= 1000000000)
                                TSh {{ number_format($totalDisbursed/1000000000, 1) }}B
                            @elseif($totalDisbursed >= 1000000)
                                TSh {{ number_format($totalDisbursed/1000000, 1) }}M
                            @elseif($totalDisbursed >= 1000)
                                TSh {{ number_format($totalDisbursed/1000, 1) }}K
                            @else
                                TSh {{ number_format($totalDisbursed) }}
                            @endif
                        </p>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-1 text-gray-700">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                        </svg>
                        <span class="text-xs font-semibold">
                            @if($monthlyDisbursed >= 1000000000)
                                TSh {{ number_format($monthlyDisbursed/1000000000, 1) }}B
                            @elseif($monthlyDisbursed >= 1000000)
                                TSh {{ number_format($monthlyDisbursed/1000000, 1) }}M
                            @elseif($monthlyDisbursed >= 1000)
                                TSh {{ number_format($monthlyDisbursed/1000, 1) }}K
                            @else
                                TSh {{ number_format($monthlyDisbursed) }}
                            @endif
                        </span>
                    </div>
                    <span class="text-xs text-gray-500">{{ __('dashboard.this_month') }}</span>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 gap-6 mb-8">
            <!-- Monthly Applications Trend Line Chart -->
            <div class="bg-white rounded-lg shadow-sm p-8 border border-gray-100">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ __('dashboard.application_trends') }}</h3>
                        <p class="text-gray-600">{{ __('dashboard.application_trends_description') }}</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center space-x-2">
                            <div class="w-3 h-3 bg-sidebar-green rounded-full"></div>
                            <span class="text-sm font-medium text-gray-600">{{ __('dashboard.chart_applications') }}</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <div class="w-3 h-3 bg-black rounded-full"></div>
                            <span class="text-sm font-medium text-gray-600">{{ __('dashboard.chart_approved') }}</span>
                        </div>
                    </div>
                </div>
                <div class="relative h-80">
                    <canvas id="applicationTrendsChart"></canvas>
                </div>
            </div>

            <!-- Product Performance Bar Chart -->
            <div class="bg-white rounded-lg shadow-sm p-8 border border-gray-100">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ __('dashboard.product_performance') }}</h3>
                        <p class="text-gray-600">{{ __('dashboard.product_performance_description') }}</p>
                    </div>
                    <div class="text-sm font-medium text-gray-500">{{ __('dashboard.total') }}: {{ number_format($totalApplications) }}</div>
                </div>
                <div class="relative h-80">
                    <canvas id="productPerformanceChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Application Status Distribution and Insights -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <!-- Application Status Pie Chart -->
            <div class="lg:col-span-2 bg-white rounded-lg shadow-sm p-8 border border-gray-100">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ __('dashboard.application_status_distribution_title') }}</h3>
                        <p class="text-gray-600">{{ __('dashboard.application_status_distribution_description') }}</p>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="relative h-64">
                        <canvas id="statusDistributionChart"></canvas>
                    </div>
                    <div class="space-y-4">
                        @php
                            $statuses = [
                                'submitted' => ['name' => __('dashboard.status_submitted'), 'color' => '#3b82f6', 'count' => $applicationsByStatus['submitted'] ?? 0],
                                'under_review' => ['name' => __('dashboard.status_under_review'), 'color' => '#f59e0b', 'count' => $applicationsByStatus['under_review'] ?? 0],
                                'approved' => ['name' => __('dashboard.status_approved'), 'color' => '#22c55e', 'count' => $applicationsByStatus['approved'] ?? 0],
                                'disbursed' => ['name' => __('dashboard.status_disbursed'), 'color' => '#a855f7', 'count' => $applicationsByStatus['disbursed'] ?? 0],
                                'rejected' => ['name' => __('dashboard.status_rejected'), 'color' => '#C40F11', 'count' => $applicationsByStatus['rejected'] ?? 0]
                            ];
                        @endphp
                        @foreach($statuses as $status => $config)
                            @php
                                $percentage = $totalApplications > 0 ? round(($config['count'] / $totalApplications) * 100, 1) : 0;
                            @endphp
                            <div class="flex items-center justify-between p-3 rounded-lg hover:bg-gray-50 transition-colors">
                                <div class="flex items-center space-x-3">
                                    <div class="w-4 h-4 rounded-full" style="background-color: {{ $config['color'] }}"></div>
                                    <span class="text-sm font-semibold text-gray-700">{{ $config['name'] }}</span>
                                </div>
                                <div class="text-right">
                                    <span class="text-sm font-bold text-gray-900">{{ $config['count'] }}</span>
                                    <span class="text-xs text-gray-500 ml-2">({{ $percentage }}%)</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Key Insights -->
            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-gray-900">{{ __('dashboard.key_insights_title') }}</h3>
                    <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
                </div>
                <div class="space-y-6">
                    <!-- Top Performing Product -->
                    @if($topPerformingProducts->count() > 0)
                        <div class="p-4 rounded-xl bg-green-50 border border-green-100">
                            <div class="flex items-center space-x-3 mb-2">
                                <div class="w-8 h-8 bg-green-500 rounded-lg flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-sm font-bold text-green-900">{{ __('dashboard.top_product_title') }}</h4>
                                    <p class="text-xs text-green-700">{{ $topPerformingProducts->first()->name }}</p>
                                </div>
                            </div>
                            @php
                                $topProduct = $topPerformingProducts->first();
                                $topApprovalRate = $topProduct->applications_count > 0
                                    ? round(($topProduct->approved_count / $topProduct->applications_count) * 100)
                                    : 0;
                            @endphp
                            <p class="text-xs text-green-600">
                                {{ __('dashboard.top_product_summary', ['count' => $topProduct->applications_count, 'approval_rate' => $topApprovalRate]) }}
                            </p>
                        </div>
                    @endif

                    <!-- Conversion Rate Insight -->
                    <div class="p-4 rounded-xl bg-blue-50 border border-blue-100">
                        <div class="flex items-center space-x-3 mb-2">
                            <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-blue-900">{{ __('dashboard.conversion_rate_title') }}</h4>
                                <p class="text-xs text-blue-700">{{ __('dashboard.approval_rate_label', ['rate' => $conversionRate]) }}</p>
                            </div>
                        </div>
                        <p class="text-xs text-blue-600">
                            @if($conversionRate >= 70)
                                {{ __('dashboard.conversion_rate_excellent') }}
                            @elseif($conversionRate >= 50)
                                {{ __('dashboard.conversion_rate_good') }}
                            @else
                                {{ __('dashboard.conversion_rate_needs_review') }}
                            @endif
                        </p>
                    </div>

                    <!-- Application Volume -->
                    <div class="p-4 rounded-xl bg-purple-50 border border-purple-100">
                        <div class="flex items-center space-x-3 mb-2">
                            <div class="w-8 h-8 bg-purple-500 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-purple-900">{{ __('dashboard.pending_review_title') }}</h4>
                                <p class="text-xs text-purple-700">{{ $pendingApplications }} {{ __('dashboard.applications') }}</p>
                            </div>
                        </div>
                        <p class="text-xs text-purple-600">
                            @if($pendingApplications > 10)
                                {{ __('dashboard.pending_review_many') }}
                            @elseif($pendingApplications > 0)
                                {{ __('dashboard.pending_review_some', ['count' => $pendingApplications]) }}
                            @else
                                {{ __('dashboard.pending_review_none') }}
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Applications Table -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-8 py-6 border-b border-gray-100 bg-gradient-to-r from-white to-gray-50">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-1">Recent Applications</h3>
                        <p class="text-gray-600">Latest loan applications requiring your attention</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <!-- <button class="bg-gray-100 text-gray-600 px-4 py-2 rounded-lg font-medium hover:bg-gray-200 transition-colors">
                            Filter
                        </button> -->
                        <a  href="{{ route('application.list') }}" class="bg-sidebar-green text-white px-6 py-2 rounded-lg font-semibold hover:bg-sidebar-green-light transition-all duration-200 shadow-lg shadow-sidebar-green/25">
                            {{ __('dashboard.view_all_applications') }}
                        </a>
                        
                    </div>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-8 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('dashboard.table_applicant_information') }}</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('dashboard.table_loan_details') }}</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('dashboard.table_product') }}</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('dashboard.table_status') }}</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('dashboard.table_applied_date') }}</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($recentApplications as $application)
                            <tr class="hover:bg-gray-50 transition-colors duration-200 group">
                                <td class="px-8 py-6 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="relative">
                                            <div class="w-12 h-12 bg-gradient-to-br from-sidebar-green to-sidebar-green rounded-2xl flex items-center justify-center shadow-md">
                                                <span class="text-white text-sm font-bold">{{ substr($application->first_name, 0, 1) }}{{ substr($application->last_name, 0, 1) }}</span>
                                            </div>
                                            @if($application->user && $application->user->nida_verified_at)
                                                <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-400 rounded-full border-2 border-white"></div>
                                            @endif
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-bold text-gray-900 group-hover:text-sidebar-green transition-colors">{{ $application->first_name }} {{ $application->last_name }}</div>
                                            <div class="text-xs text-gray-500">{{ $application->email }}</div>
                                            <div class="text-xs text-blue-600 font-medium mt-1">#{{ $application->application_number }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-6 whitespace-nowrap">
                                    <div class="text-sm font-bold text-gray-900">TSh {{ number_format($application->requested_amount) }}</div>
                                    <div class="text-xs text-gray-500">{{ $application->requested_tenure_months }} {{ __('dashboard.months') }} tenure</div>
                                    <div class="text-xs text-gray-500 mt-1">{{ $application->loan_purpose ?? __('dashboard.loan_purpose_general') }}</div>
                                </td>
                                <td class="px-6 py-6 whitespace-nowrap">
                                    @if($application->loanProduct)
                                        <div class="text-sm text-gray-900">{{ $application->loanProduct->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $application->loanProduct->interest_rate_min }}% - {{ $application->loanProduct->interest_rate_max }}%</div>
                                    @else
                                        <span class="text-xs text-gray-400">{{ __('dashboard.no_product_assigned') }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-6 whitespace-nowrap">
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold 
                                        @if($application->status === 'approved') bg-green-100 text-green-800 border border-green-200
                                        @elseif($application->status === 'rejected') bg-sidebar-green-100 text-sidebar-green-800 border border-sidebar-green-200
                                        @elseif($application->status === 'under_review') bg-yellow-100 text-yellow-800 border border-yellow-200
                                        @elseif($application->status === 'submitted') bg-blue-100 text-blue-800 border border-blue-200
                                        @elseif($application->status === 'disbursed') bg-purple-100 text-purple-800 border border-purple-200
                                        @else bg-gray-100 text-gray-800 border border-gray-200
                                        @endif">
                                        {{ __('dashboard.status_' . $application->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-6 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $application->created_at->format('M d, Y') }}</div>
                                    <div class="text-xs text-gray-500">{{ $application->created_at->format('g:i A') }}</div>
                                </td>
                              
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-8 py-12 text-center">
                                    <div class="w-20 h-20 bg-gray-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    </div>
                                    <h4 class="text-lg font-semibold text-gray-900 mb-2">{{ __('dashboard.no_applications_found') }}</h4>
                                    <p class="text-gray-500">{{ __('dashboard.no_applications_found_description') }}</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Chart.js Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Application Trends Line Chart
            const trendsCtx = document.getElementById('applicationTrendsChart').getContext('2d');
            const trendsChart = new Chart(trendsCtx, {
                type: 'line',
                data: {
                    labels: @json($applicationTrends?->pluck('month')->toArray() ?? []),
                    datasets: [{
                        label: @json(__('dashboard.chart_total_applications')),
                        data: @json($applicationTrends?->pluck('applications')->toArray() ?? []),
                        borderColor: '#C40F11',
                        backgroundColor: 'rgba(196, 15, 17, 0.10)',
                        tension: 0.4,
                        fill: false,
                        pointBackgroundColor: '#C40F11',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        pointRadius: 5
                    }, {
                        label: @json(__('dashboard.chart_approved_applications')),
                        data: @json($applicationTrends?->pluck('approved')->toArray() ?? []),
                        borderColor: '#22c55e',
                        backgroundColor: 'rgba(34, 197, 94, 0.10)',
                        tension: 0.4,
                        fill: false,
                        pointBackgroundColor: '#22c55e',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        pointRadius: 5
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                            backgroundColor: 'rgba(196, 15, 17, 0.92)',
                            titleColor: '#ffffff',
                            bodyColor: '#ffffff',
                            borderColor: 'rgba(255, 255, 255, 0.2)',
                            borderWidth: 1
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            },
                            ticks: {
                                color: '#6b7280'
                            }
                        },
                        x: {
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            },
                            ticks: {
                                color: '#6b7280'
                            }
                        }
                    },
                    interaction: {
                        intersect: false,
                        mode: 'index'
                    }
                }
            });

            // Product Performance Bar Chart
            const productCtx = document.getElementById('productPerformanceChart').getContext('2d');
            const productChart = new Chart(productCtx, {
                type: 'bar',
                data: {
                    labels: @json($topPerformingProducts?->pluck('name')->toArray() ?? []),
                    datasets: [{
                        label: @json(__('dashboard.chart_total_applications')),
                        data: @json($topPerformingProducts?->pluck('applications_count')->toArray() ?? []),
                        backgroundColor: '#C40F11',
                        borderRadius: 6
                    }, {
                        label: @json(__('dashboard.chart_approved_applications')),
                        data: @json($topPerformingProducts?->pluck('approved_count')->toArray() ?? []),
                        backgroundColor: '#22c55e',
                        borderRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });

            // Status Distribution Pie Chart
            const statusCtx = document.getElementById('statusDistributionChart').getContext('2d');
            const statusChart = new Chart(statusCtx, {
                type: 'doughnut',
                data: {
                    labels: [
                        @json(__('dashboard.status_submitted')),
                        @json(__('dashboard.status_under_review')),
                        @json(__('dashboard.status_approved')),
                        @json(__('dashboard.status_disbursed')),
                        @json(__('dashboard.status_rejected'))
                    ],
                    datasets: [{
                        data: [
                            {{ $applicationsByStatus['submitted'] ?? 0 }},
                            {{ $applicationsByStatus['under_review'] ?? 0 }},
                            {{ $applicationsByStatus['approved'] ?? 0 }},
                            {{ $applicationsByStatus['disbursed'] ?? 0 }},
                            {{ $applicationsByStatus['rejected'] ?? 0 }}
                        ],
                        backgroundColor: [
                            '#3b82f6',  // submitted
                            '#f59e0b',  // under_review
                            '#22c55e',  // approved
                            '#a855f7',  // disbursed
                            '#C40F11'   // rejected
                        ],
                        borderWidth: 0,
                        cutout: '60%'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });

            // Update charts when Livewire updates
            Livewire.on('dashboardUpdated', () => {
                trendsChart.update();
                productChart.update();
                statusChart.update();
            });
        });
    </script>
</div>