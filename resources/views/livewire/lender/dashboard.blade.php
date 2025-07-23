<div>
<div>
<div class="w-full">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <div class="flex items-center space-x-3 mb-2">
                    <a href="{{ route('lenders.index') }}" class="text-gray-500 hover:text-gray-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </a>
                    <h1 class="text-4xl font-bold text-gray-900">{{ $lender->company_name }}</h1>
                </div>
                <p class="text-gray-600 text-lg">Lender Dashboard & Analytics</p>
                <div class="flex items-center space-x-4 mt-2">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        {{ ucfirst($lender->status) }}
                    </span>
                    @if($lender->license_number)
                        <span class="text-sm text-gray-500">License: {{ $lender->license_number }}</span>
                    @endif
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <!-- Date Range Filter -->
                <select wire:model.live="dateRange" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="7">Last 7 days</option>
                    <option value="30">Last 30 days</option>
                    <option value="90">Last 3 months</option>
                    <option value="365">Last year</option>
                    <option value="">All time</option>
                </select>
                
                <button wire:click="exportApplications" class="bg-blue-600 text-white px-4 py-2 rounded-lg font-semibold hover:bg-blue-700 transition-all duration-200">
                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Export Data
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

    <!-- Key Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Applications -->
        <div class="bg-white rounded-xl p-6 border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300 group">
            <div class="flex items-center justify-between">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center shadow-md group-hover:scale-105 transition-transform duration-300">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div class="text-right">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Total Applications</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_applications']) }}</p>
                </div>
            </div>
        </div>

        <!-- Approval Rate -->
        <div class="bg-white rounded-xl p-6 border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300 group">
            <div class="flex items-center justify-between">
                <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-lg flex items-center justify-center shadow-md group-hover:scale-105 transition-transform duration-300">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="text-right">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Approval Rate</p>
                    <p class="text-2xl font-bold text-green-600">{{ $stats['approval_rate'] }}%</p>
                </div>
            </div>
        </div>

        <!-- Total Disbursed -->
        <div class="bg-white rounded-xl p-6 border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300 group">
            <div class="flex items-center justify-between">
                <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg flex items-center justify-center shadow-md group-hover:scale-105 transition-transform duration-300">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                    </svg>
                </div>
                <div class="text-right">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Total Disbursed</p>
                    <p class="text-2xl font-bold text-purple-600">TZS {{ number_format($stats['total_loan_amount']) }}</p>
                    <p class="text-xs text-gray-500">Avg: TZS {{ number_format($stats['avg_loan_amount']) }}</p>
                </div>
            </div>
        </div>

        <!-- Pending Review -->
        <div class="bg-white rounded-xl p-6 border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300 group">
            <div class="flex items-center justify-between">
                <div class="w-12 h-12 bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-lg flex items-center justify-center shadow-md group-hover:scale-105 transition-transform duration-300">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="text-right">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Pending Review</p>
                    <p class="text-2xl font-bold text-yellow-600">{{ number_format($stats['pending_review']) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Secondary Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Application Status Breakdown -->
        <div class="bg-white rounded-xl p-6 border border-gray-100 shadow-sm">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Application Status</h3>
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Approved</span>
                    <span class="text-sm font-semibold text-green-600">{{ number_format($stats['approved']) }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Disbursed</span>
                    <span class="text-sm font-semibold text-blue-600">{{ number_format($stats['disbursed']) }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Rejected</span>
                    <span class="text-sm font-semibold text-red-600">{{ number_format($stats['rejected']) }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Under Review</span>
                    <span class="text-sm font-semibold text-yellow-600">{{ number_format($stats['pending_review']) }}</span>
                </div>
            </div>
        </div>

        <!-- Commission Summary -->
        <div class="bg-white rounded-xl p-6 border border-gray-100 shadow-sm">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Commission Summary</h3>
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Total Commission</span>
                    <span class="text-sm font-semibold text-gray-900">TZS {{ number_format($commissionStats['total_commission']) }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Paid</span>
                    <span class="text-sm font-semibold text-green-600">TZS {{ number_format($commissionStats['paid_commission']) }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Pending</span>
                    <span class="text-sm font-semibold text-yellow-600">TZS {{ number_format($commissionStats['pending_commission']) }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Overdue</span>
                    <span class="text-sm font-semibold text-red-600">TZS {{ number_format($commissionStats['overdue_commission']) }}</span>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="bg-white rounded-xl p-6 border border-gray-100 shadow-sm">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Applications</h3>
            <div class="space-y-3">
                @forelse($recentApplications as $app)
                    <div class="flex justify-between items-center">
                        <div>
                            <div class="text-sm font-medium text-gray-900">{{ $app->first_name }} {{ $app->last_name }}</div>
                            <div class="text-xs text-gray-500">{{ $app->created_at->diffForHumans() }}</div>
                        </div>
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                            @if($app->status === 'approved') bg-green-100 text-green-800
                            @elseif($app->status === 'pending') bg-yellow-100 text-yellow-800
                            @elseif($app->status === 'rejected') bg-red-100 text-red-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ ucfirst($app->status) }}
                        </span>
                    </div>
                @empty
                    <p class="text-sm text-gray-500">No recent applications</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Monthly Performance Chart -->
    <div class="bg-white rounded-xl p-6 border border-gray-100 shadow-sm mb-8">
        <h3 class="text-lg font-semibold text-gray-900 mb-6">Monthly Performance</h3>
        <div class="grid grid-cols-6 gap-4 mb-4">
            @foreach($monthlyStats as $month)
                <div class="text-center">
                    <div class="text-xs text-gray-500 mb-2">{{ $month['short_month'] }}</div>
                    <div class="space-y-2">
                        <div class="bg-blue-100 rounded p-2">
                            <div class="text-sm font-semibold text-blue-600">{{ $month['total_applications'] }}</div>
                            <div class="text-xs text-blue-500">Apps</div>
                        </div>
                        <div class="bg-green-100 rounded p-2">
                            <div class="text-sm font-semibold text-green-600">{{ $month['approved_applications'] }}</div>
                            <div class="text-xs text-green-500">Approved</div>
                        </div>
                        <div class="bg-red-100 rounded p-2">
                            <div class="text-xs font-semibold text-red-600">{{ number_format($month['disbursed_amount']/1000000, 1) }}M</div>
                            <div class="text-xs text-red-500">TZS</div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Top Performing Products -->
    <div class="bg-white rounded-xl p-6 border border-gray-100 shadow-sm mb-8">
        <h3 class="text-lg font-semibold text-gray-900 mb-6">Top Performing Products</h3>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-200">
                        <th class="text-left py-3 text-sm font-medium text-gray-500">Product Name</th>
                        <th class="text-left py-3 text-sm font-medium text-gray-500">Applications</th>
                        <th class="text-left py-3 text-sm font-medium text-gray-500">Approved</th>
                        <th class="text-left py-3 text-sm font-medium text-gray-500">Total Amount</th>
                        <th class="text-left py-3 text-sm font-medium text-gray-500">Approval Rate</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($topProducts as $product)
                        <tr class="hover:bg-gray-50">
                            <td class="py-3">
                                <div class="text-sm font-medium text-gray-900">{{ $product->name }}</div>
                                <div class="text-xs text-gray-500">{{ $product->product_code }}</div>
                            </td>
                            <td class="py-3 text-sm text-gray-900">{{ number_format($product->applications_count) }}</td>
                            <td class="py-3 text-sm text-gray-900">{{ number_format($product->approved_count) }}</td>
                            <td class="py-3 text-sm text-gray-900">TZS {{ number_format($product->total_amount) }}</td>
                            <td class="py-3">
                                @php
                                    $rate = $product->applications_count > 0 ? round(($product->approved_count / $product->applications_count) * 100, 1) : 0;
                                @endphp
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                                    @if($rate >= 80) bg-green-100 text-green-800
                                    @elseif($rate >= 60) bg-yellow-100 text-yellow-800
                                    @else bg-red-100 text-red-800 @endif">
                                    {{ $rate }}%
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-6 text-center text-gray-500">No products found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Applications Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-white to-gray-50">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-xl font-bold text-gray-900">Loan Applications</h3>
                    <p class="text-gray-600">All applications for this lender</p>
                </div>
                
                <!-- Search and Filter -->
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <input wire:model.live="search" type="text" placeholder="Search applications..." class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <svg class="w-4 h-4 text-gray-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    
                    <select wire:model.live="statusFilter" class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All Status</option>
                        @foreach($application_statuses as $status)
                            <option value="{{ $status }}">{{ ucfirst(str_replace('_', ' ', $status)) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Application</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Applicant</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($applications as $application)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $application->application_number }}</div>
                                <div class="text-sm text-gray-500">{{ $application->loan_purpose ?? 'Not specified' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $application->first_name }} {{ $application->last_name }}</div>
                                <div class="text-sm text-gray-500">{{ $application->email }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">TZS {{ number_format($application->requested_amount) }}</div>
                                <div class="text-sm text-gray-500">{{ $application->requested_tenure_months }} months</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($application->status === 'approved') bg-green-100 text-green-800
                                    @elseif($application->status === 'disbursed') bg-blue-100 text-blue-800
                                    @elseif($application->status === 'under_review') bg-yellow-100 text-yellow-800
                                    @elseif($application->status === 'rejected') bg-red-100 text-red-800
                                    @elseif($application->status === 'submitted') bg-purple-100 text-purple-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ ucfirst(str_replace('_', ' ', $application->status)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $application->created_at->format('M d, Y') }}</div>
                                <div class="text-sm text-gray-500">{{ $application->created_at->format('g:i A') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button wire:click="viewApplication({{ $application->id }})" class="text-blue-600 hover:text-blue-900 mr-3">
                                    <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    View
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">No applications found</h3>
                                    <p class="text-gray-500">
                                        @if($search || $statusFilter)
                                            Try adjusting your search criteria or filters.
                                        @else
                                            No loan applications have been submitted for this lender yet.
                                        @endif
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $applications->links() }}
        </div>
    </div>

    <!-- Application Details Modal -->
    @if($showApplicationModal && $selectedApplication)
        <div class="fixed inset-0 bg-black bg-opacity-50 z-50">
            <div class="flex items-center justify-center min-h-screen p-4">
                <div class="bg-white rounded-3xl p-8 w-full max-w-4xl max-h-screen overflow-y-auto">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-2xl font-bold text-gray-900">Application Details</h2>
                        <button wire:click="closeApplicationModal" class="text-gray-400 hover:text-gray-600 p-2 rounded-xl hover:bg-gray-100 transition-all duration-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Basic Information -->
                        <div class="space-y-6">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Application Info</h3>
                                <div class="space-y-3">
                                    <div>
                                        <label class="text-sm font-medium text-gray-500">Application Number</label>
                                        <p class="text-gray-900">{{ $selectedApplication->application_number }}</p>
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium text-gray-500">Status</label>
                                        <p class="text-gray-900">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                @if($selectedApplication->status === 'approved') bg-green-100 text-green-800
                                                @elseif($selectedApplication->status === 'disbursed') bg-blue-100 text-blue-800
                                                @elseif($selectedApplication->status === 'under_review') bg-yellow-100 text-yellow-800
                                                @elseif($selectedApplication->status === 'rejected') bg-red-100 text-red-800
                                                @else bg-gray-100 text-gray-800 @endif">
                                                {{ ucfirst(str_replace('_', ' ', $selectedApplication->status)) }}
                                            </span>
                                        </p>
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium text-gray-500">Requested Amount</label>
                                        <p class="text-gray-900">TZS {{ number_format($selectedApplication->requested_amount) }}</p>
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium text-gray-500">Tenure</label>
                                        <p class="text-gray-900">{{ $selectedApplication->requested_tenure_months }} months</p>
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium text-gray-500">Loan Purpose</label>
                                        <p class="text-gray-900">{{ $selectedApplication->loan_purpose ?? 'Not specified' }}</p>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Personal Information</h3>
                                <div class="space-y-3">
                                    <div>
                                        <label class="text-sm font-medium text-gray-500">Full Name</label>
                                        <p class="text-gray-900">{{ $selectedApplication->first_name }} {{ $selectedApplication->middle_name }} {{ $selectedApplication->last_name }}</p>
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium text-gray-500">Email</label>
                                        <p class="text-gray-900">{{ $selectedApplication->email }}</p>
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium text-gray-500">Phone</label>
                                        <p class="text-gray-900">{{ $selectedApplication->phone_number }}</p>
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium text-gray-500">National ID</label>
                                        <p class="text-gray-900">{{ $selectedApplication->national_id }}</p>
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium text-gray-500">Date of Birth</label>
                                        <p class="text-gray-900">{{ $selectedApplication->date_of_birth ? $selectedApplication->date_of_birth->format('M d, Y') : 'Not provided' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Financial Information -->
                        <div class="space-y-6">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Financial Information</h3>
                                <div class="space-y-3">
                                    <div>
                                        <label class="text-sm font-medium text-gray-500">Employment Status</label>
                                        <p class="text-gray-900">{{ ucfirst($selectedApplication->employment_status) }}</p>
                                    </div>
                                    @if($selectedApplication->employer_name)
                                        <div>
                                            <label class="text-sm font-medium text-gray-500">Employer</label>
                                            <p class="text-gray-900">{{ $selectedApplication->employer_name }}</p>
                                        </div>
                                    @endif
                                    <div>
                                        <label class="text-sm font-medium text-gray-500">Monthly Income</label>
                                        <p class="text-gray-900">TZS {{ number_format($selectedApplication->total_monthly_income) }}</p>
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium text-gray-500">Monthly Expenses</label>
                                        <p class="text-gray-900">TZS {{ number_format($selectedApplication->monthly_expenses) }}</p>
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium text-gray-500">Existing Loan Payments</label>
                                        <p class="text-gray-900">TZS {{ number_format($selectedApplication->existing_loan_payments) }}</p>
                                    </div>
                                    @if($selectedApplication->debt_to_income_ratio)
                                        <div>
                                            <label class="text-sm font-medium text-gray-500">Debt-to-Income Ratio</label>
                                            <p class="text-gray-900">{{ $selectedApplication->debt_to_income_ratio }}%</p>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Address Information</h3>
                                <div class="space-y-3">
                                    <div>
                                        <label class="text-sm font-medium text-gray-500">Current Address</label>
                                        <p class="text-gray-900">{{ $selectedApplication->current_address }}</p>
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium text-gray-500">City, Region</label>
                                        <p class="text-gray-900">{{ $selectedApplication->current_city }}, {{ $selectedApplication->current_region }}</p>
                                    </div>
                                    @if($selectedApplication->current_postal_code)
                                        <div>
                                            <label class="text-sm font-medium text-gray-500">Postal Code</label>
                                            <p class="text-gray-900">{{ $selectedApplication->current_postal_code }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Application Timeline</h3>
                                <div class="space-y-4">
                                    <div class="flex items-start space-x-3">
                                        <div class="w-3 h-3 bg-blue-500 rounded-full mt-2"></div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">Application Submitted</p>
                                            <p class="text-xs text-gray-500">{{ $selectedApplication->created_at->format('M d, Y g:i A') }}</p>
                                        </div>
                                    </div>

                                    @if($selectedApplication->submitted_at)
                                        <div class="flex items-start space-x-3">
                                            <div class="w-3 h-3 bg-purple-500 rounded-full mt-2"></div>
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">Submitted for Review</p>
                                                <p class="text-xs text-gray-500">{{ $selectedApplication->submitted_at->format('M d, Y g:i A') }}</p>
                                            </div>
                                        </div>
                                    @endif

                                    @if($selectedApplication->reviewed_at)
                                        <div class="flex items-start space-x-3">
                                            <div class="w-3 h-3 bg-yellow-500 rounded-full mt-2"></div>
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">Under Review</p>
                                                <p class="text-xs text-gray-500">{{ $selectedApplication->reviewed_at->format('M d, Y g:i A') }}</p>
                                            </div>
                                        </div>
                                    @endif

                                    @if($selectedApplication->approved_at)
                                        <div class="flex items-start space-x-3">
                                            <div class="w-3 h-3 bg-green-500 rounded-full mt-2"></div>
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">Application Approved</p>
                                                <p class="text-xs text-gray-500">{{ $selectedApplication->approved_at->format('M d, Y g:i A') }}</p>
                                            </div>
                                        </div>
                                    @endif

                                    @if($selectedApplication->disbursed_at)
                                        <div class="flex items-start space-x-3">
                                            <div class="w-3 h-3 bg-blue-600 rounded-full mt-2"></div>
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">Loan Disbursed</p>
                                                <p class="text-xs text-gray-500">{{ $selectedApplication->disbursed_at->format('M d, Y g:i A') }}</p>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <div class="flex justify-end">
                            <button wire:click="closeApplicationModal" class="px-6 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                                Close
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
</div>

</div>
