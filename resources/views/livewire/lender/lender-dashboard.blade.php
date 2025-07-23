{{-- resources/views/livewire/lender/dashboard.blade.php --}}
<div>
    <div class="p-8">
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-4xl font-bold text-gray-900 mb-2">Lender Dashboard</h1>
                    <p class="text-gray-600 text-lg">Manage loan applications and monitor your lending performance</p>
                </div>
                <div class="flex items-center space-x-3">
                    <div class="flex items-center space-x-2 bg-green-50 px-4 py-2 rounded-full">
                        <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
                        <span class="text-sm font-medium text-green-700">Active Lending</span>
                    </div>
                    <button class="bg-white border border-gray-200 text-gray-700 px-4 py-2 rounded-lg font-medium hover:bg-gray-50 transition-colors">
                        This Month
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
            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100 hover:shadow-md transition-all duration-300 group hover:border-red-500/20">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-14 h-14 bg-red-50 rounded-2xl flex items-center justify-center group-hover:bg-red-100 transition-colors">
                        <svg class="w-7 h-7 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                    </div>
                    <div class="text-right">
                        <p class="text-xs font-medium text-gray-600 uppercase tracking-wide">New Applications</p>
                        <p class="text-2xl font-bold text-gray-900 group-hover:text-red-600 transition-colors">{{ number_format($newApplications) }}</p>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-1 text-red-600">
                        <svg class="w-3 h-3 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="text-xs font-semibold">Awaiting Review</span>
                    </div>
                    <span class="text-xs text-gray-500">{{ $pendingApplications }} in review</span>
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
                        <p class="text-xs font-medium text-gray-600 uppercase tracking-wide">Total Applications</p>
                        <p class="text-2xl font-bold text-gray-900 group-hover:text-black transition-colors">{{ number_format($totalApplications) }}</p>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-1 text-gray-700">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span class="text-xs font-semibold">{{ $approvedApplications }} approved</span>
                    </div>
                    <span class="text-xs text-gray-500">{{ $conversionRate }}% rate</span>
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
                        <p class="text-xs font-medium text-gray-600 uppercase tracking-wide">Approved Loans</p>
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
                    <span class="text-xs text-gray-500">conversion rate</span>
                </div>
            </div>

            <!-- Total Disbursed Card -->
            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100 hover:shadow-md transition-all duration-300 group hover:border-red-500/20">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-14 h-14 bg-red-50 rounded-2xl flex items-center justify-center group-hover:bg-red-100 transition-colors">
                        <svg class="w-7 h-7 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                        </svg>
                    </div>
                    <div class="text-right">
                        <p class="text-xs font-medium text-gray-600 uppercase tracking-wide">Total Disbursed</p>
                        <p class="text-2xl font-bold text-gray-900 group-hover:text-red-600 transition-colors">
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
                    <span class="text-xs text-gray-500">this month</span>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 gap-6 mb-8">
            <!-- Monthly Applications Trend Line Chart -->
            <div class="bg-white rounded-lg shadow-sm p-8 border border-gray-100">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">Application Trends</h3>
                        <p class="text-gray-600">Monthly application volume and approval rates</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center space-x-2">
                            <div class="w-3 h-3 bg-red-600 rounded-full"></div>
                            <span class="text-sm font-medium text-gray-600">Applications</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <div class="w-3 h-3 bg-black rounded-full"></div>
                            <span class="text-sm font-medium text-gray-600">Approved</span>
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
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">Product Performance</h3>
                        <p class="text-gray-600">Applications by loan product</p>
                    </div>
                    <div class="text-sm font-medium text-gray-500">Total: {{ number_format($totalApplications) }}</div>
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
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">Application Status Distribution</h3>
                        <p class="text-gray-600">Current status breakdown of all applications</p>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="relative h-64">
                        <canvas id="statusDistributionChart"></canvas>
                    </div>
                    <div class="space-y-4">
                        @php
                            $statuses = [
                                'submitted' => ['name' => 'Submitted', 'color' => '#dc2626', 'count' => $applicationsByStatus['submitted'] ?? 0],
                                'under_review' => ['name' => 'Under Review', 'color' => '#f59e0b', 'count' => $applicationsByStatus['under_review'] ?? 0],
                                'approved' => ['name' => 'Approved', 'color' => '#000000', 'count' => $applicationsByStatus['approved'] ?? 0],
                                'disbursed' => ['name' => 'Disbursed', 'color' => '#6b7280', 'count' => $applicationsByStatus['disbursed'] ?? 0],
                                'rejected' => ['name' => 'Rejected', 'color' => '#ef4444', 'count' => $applicationsByStatus['rejected'] ?? 0]
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
                    <h3 class="text-xl font-bold text-gray-900">Key Insights</h3>
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
                                    <h4 class="text-sm font-bold text-green-900">Top Product</h4>
                                    <p class="text-xs text-green-700">{{ $topPerformingProducts->first()->name }}</p>
                                </div>
                            </div>
                            <p class="text-xs text-green-600">{{ $topPerformingProducts->first()->applications_count }} applications with {{ $topPerformingProducts->first()->applications_count > 0 ? round(($topPerformingProducts->first()->approved_count / $topPerformingProducts->first()->applications_count) * 100) : 0 }}% approval rate</p>
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
                                <h4 class="text-sm font-bold text-blue-900">Conversion Rate</h4>
                                <p class="text-xs text-blue-700">{{ $conversionRate }}% approval rate</p>
                            </div>
                        </div>
                        <p class="text-xs text-blue-600">
                            @if($conversionRate >= 70)
                                Excellent approval rate! Your products are well-matched to applicants.
                            @elseif($conversionRate >= 50)
                                Good approval rate. Consider reviewing criteria for improvement.
                            @else
                                Review your loan criteria to improve approval rates.
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
                                <h4 class="text-sm font-bold text-purple-900">Pending Review</h4>
                                <p class="text-xs text-purple-700">{{ $pendingApplications }} applications</p>
                            </div>
                        </div>
                        <p class="text-xs text-purple-600">
                            @if($pendingApplications > 10)
                                High volume of pending applications. Consider prioritizing reviews.
                            @elseif($pendingApplications > 0)
                                {{ $pendingApplications }} applications awaiting your review.
                            @else
                                All applications are up to date!
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
                        <button class="bg-red-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-red-700 transition-all duration-200 shadow-lg shadow-red-600/25">
                            View All Applications
                        </button>
                        
                    </div>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-8 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Applicant Information</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Loan Details</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Product</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Applied Date</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($recentApplications as $application)
                            <tr class="hover:bg-gray-50 transition-colors duration-200 group">
                                <td class="px-8 py-6 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="relative">
                                            <div class="w-12 h-12 bg-gradient-to-br from-red-500 to-red-600 rounded-2xl flex items-center justify-center shadow-md">
                                                <span class="text-white text-sm font-bold">{{ substr($application->first_name, 0, 1) }}{{ substr($application->last_name, 0, 1) }}</span>
                                            </div>
                                            @if($application->user && $application->user->nida_verified_at)
                                                <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-400 rounded-full border-2 border-white"></div>
                                            @endif
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-bold text-gray-900 group-hover:text-red-600 transition-colors">{{ $application->first_name }} {{ $application->last_name }}</div>
                                            <div class="text-xs text-gray-500">{{ $application->email }}</div>
                                            <div class="text-xs text-blue-600 font-medium mt-1">#{{ $application->application_number }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-6 whitespace-nowrap">
                                    <div class="text-sm font-bold text-gray-900">TSh {{ number_format($application->requested_amount) }}</div>
                                    <div class="text-xs text-gray-500">{{ $application->requested_tenure_months }} months tenure</div>
                                    <div class="text-xs text-gray-500 mt-1">{{ $application->loan_purpose ?? 'General purpose' }}</div>
                                </td>
                                <td class="px-6 py-6 whitespace-nowrap">
                                    @if($application->loanProduct)
                                        <div class="text-sm text-gray-900">{{ $application->loanProduct->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $application->loanProduct->interest_rate_min }}% - {{ $application->loanProduct->interest_rate_max }}%</div>
                                    @else
                                        <span class="text-xs text-gray-400">No Product Assigned</span>
                                    @endif
                                </td>
                                <td class="px-6 py-6 whitespace-nowrap">
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold 
                                        @if($application->status === 'approved') bg-green-100 text-green-800 border border-green-200
                                        @elseif($application->status === 'rejected') bg-red-100 text-red-800 border border-red-200
                                        @elseif($application->status === 'under_review') bg-yellow-100 text-yellow-800 border border-yellow-200
                                        @elseif($application->status === 'submitted') bg-blue-100 text-blue-800 border border-blue-200
                                        @elseif($application->status === 'disbursed') bg-purple-100 text-purple-800 border border-purple-200
                                        @else bg-gray-100 text-gray-800 border border-gray-200
                                        @endif">
                                        {{ ucfirst(str_replace('_', ' ', $application->status)) }}
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
                                    <h4 class="text-lg font-semibold text-gray-900 mb-2">No Applications Found</h4>
                                    <p class="text-gray-500">There are no loan applications to display at this time.</p>
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
                    labels: [
                        @if(isset($applicationTrends) && $applicationTrends->count() > 0)
                            @foreach($applicationTrends as $trend)
                                '{{ $trend['month'] }}',
                            @endforeach
                        @else
                            'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
                        @endif
                    ],
                    datasets: [{
                        label: 'Total Applications',
                        data: [
                            @if(isset($applicationTrends) && $applicationTrends->count() > 0)
                                @foreach($applicationTrends as $trend)
                                    {{ $trend['applications'] }},
                                @endforeach
                            @else
                                45, 52, 38, 65, 72, 58, 63, 71, 55, 68, 74, 82
                            @endif
                        ],
                        borderColor: '#dc2626',
                        backgroundColor: 'rgba(220, 38, 38, 0.1)',
                        tension: 0.4,
                        fill: false,
                        pointBackgroundColor: '#dc2626',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        pointRadius: 5
                    }, {
                        label: 'Approved Applications',
                        data: [
                            @if(isset($applicationTrends) && $applicationTrends->count() > 0)
                                @foreach($applicationTrends as $trend)
                                    {{ $trend['approved'] }},
                                @endforeach
                            @else
                                32, 38, 25, 48, 54, 41, 45, 52, 38, 49, 55, 62
                            @endif
                        ],
                        borderColor: '#000000',
                        backgroundColor: 'rgba(0, 0, 0, 0.1)',
                        tension: 0.4,
                        fill: false,
                        pointBackgroundColor: '#000000',
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
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleColor: '#ffffff',
                            bodyColor: '#ffffff',
                            borderColor: 'rgba(255, 255, 255, 0.1)',
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
                    labels: [
                        @if($topPerformingProducts->count() > 0)
                            @foreach($topPerformingProducts as $product)
                                '{{ $product->name }}',
                            @endforeach
                        @else
                            'Personal Loan', 'Business Loan', 'Emergency Loan', 'Asset Finance', 'Working Capital'
                        @endif
                    ],
                    datasets: [{
                        label: 'Total Applications',
                        data: [
                            @if($topPerformingProducts->count() > 0)
                                @foreach($topPerformingProducts as $product)
                                    {{ $product->applications_count }},
                                @endforeach
                            @else
                                24, 18, 15, 12, 8
                            @endif
                        ],
                        backgroundColor: '#dc2626',
                        borderRadius: 6
                    }, {
                        label: 'Approved',
                        data: [
                            @if($topPerformingProducts->count() > 0)
                                @foreach($topPerformingProducts as $product)
                                    {{ $product->approved_count }},
                                @endforeach
                            @else
                                18, 12, 10, 8, 5
                            @endif
                        ],
                        backgroundColor: '#000000',
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
                    labels: ['Submitted', 'Under Review', 'Approved', 'Disbursed', 'Rejected'],
                    datasets: [{
                        data: [
                            {{ $applicationsByStatus['submitted'] ?? 15 }},
                            {{ $applicationsByStatus['under_review'] ?? 12 }},
                            {{ $applicationsByStatus['approved'] ?? 25 }},
                            {{ $applicationsByStatus['disbursed'] ?? 20 }},
                            {{ $applicationsByStatus['rejected'] ?? 8 }}
                        ],
                        backgroundColor: [
                            '#dc2626',
                            '#f59e0b',
                            '#000000',
                            '#6b7280',
                            '#ef4444'
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