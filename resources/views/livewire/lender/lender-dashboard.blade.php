<div>
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
                    <button class="bg-white border border-gray-200 text-gray-700 px-4 py-2 rounded-xl font-medium hover:bg-gray-50 transition-colors">
                        This Month
                    </button>
                </div>
            </div>
        </div>

        <!-- Flash Message -->
        @if (session()->has('message'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-6 py-4 rounded-3xl mb-6 flex items-center space-x-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <span>{{ session('message') }}</span>
            </div>
        @endif

        <!-- Key Performance Metrics -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- New Applications Card -->
            <div class="bg-white rounded-3xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-all duration-300 group hover:border-red-500/20">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-14 h-14 bg-red-100 rounded-2xl flex items-center justify-center group-hover:bg-red-200 transition-colors">
                        <svg class="w-7 h-7 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-medium text-gray-500">New Applications</p>
                        <p class="text-3xl font-bold text-gray-900 group-hover:text-red-600 transition-colors">{{ number_format($newApplications) }}</p>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-1 text-red-600">
                        <svg class="w-4 h-4 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="text-sm font-semibold">Awaiting Review</span>
                    </div>
                    <span class="text-sm text-gray-500">{{ $pendingApplications }} in review</span>
                </div>
            </div>

            <!-- Total Applications Card -->
            <div class="bg-white rounded-3xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-all duration-300 group hover:border-blue-500/20">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-14 h-14 bg-blue-100 rounded-2xl flex items-center justify-center group-hover:bg-blue-200 transition-colors">
                        <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-medium text-gray-500">Total Applications</p>
                        <p class="text-3xl font-bold text-gray-900 group-hover:text-blue-600 transition-colors">{{ number_format($totalApplications) }}</p>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-1 text-green-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span class="text-sm font-semibold">{{ $approvedApplications }} approved</span>
                    </div>
                    <span class="text-sm text-gray-500">{{ $conversionRate }}% rate</span>
                </div>
            </div>

            <!-- Approved Applications Card -->
            <div class="bg-white rounded-3xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-all duration-300 group hover:border-green-500/20">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-14 h-14 bg-green-100 rounded-2xl flex items-center justify-center group-hover:bg-green-200 transition-colors">
                        <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-medium text-gray-500">Approved Loans</p>
                        <p class="text-3xl font-bold text-gray-900 group-hover:text-green-600 transition-colors">{{ number_format($approvedApplications) }}</p>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-1 text-green-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                        </svg>
                        <span class="text-sm font-semibold">{{ $conversionRate }}%</span>
                    </div>
                    <span class="text-sm text-gray-500">conversion rate</span>
                </div>
            </div>

            <!-- Total Disbursed Card -->
            <div class="bg-white rounded-3xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-all duration-300 group hover:border-purple-500/20">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-14 h-14 bg-purple-100 rounded-2xl flex items-center justify-center group-hover:bg-purple-200 transition-colors">
                        <svg class="w-7 h-7 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                        </svg>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-medium text-gray-500">Total Disbursed</p>
                        <p class="text-3xl font-bold text-gray-900 group-hover:text-purple-600 transition-colors">TSh {{ number_format($totalDisbursed/1000000, 1) }}M</p>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-1 text-green-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                        </svg>
                        <span class="text-sm font-semibold">TSh {{ number_format($monthlyDisbursed/1000000, 1) }}M</span>
                    </div>
                    <span class="text-sm text-gray-500">this month</span>
                </div>
            </div>
        </div>

        <!-- Charts and Products Section -->
        <div class="grid grid-cols-1 lg:grid-cols-7 gap-6 mb-8">
            <!-- Application Status Distribution -->
            <div class="lg:col-span-4 bg-white rounded-3xl shadow-sm p-8 border border-gray-100">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">Application Pipeline</h3>
                        <p class="text-gray-600">Track your loan application processing status</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <button class="bg-red-600 text-white px-4 py-2 rounded-xl font-semibold text-sm shadow-lg shadow-red-600/25">Live View</button>
                    </div>
                </div>
                <div class="space-y-4">
                    @foreach($applicationsByStatus as $status => $count)
                        <div class="flex items-center justify-between p-4 rounded-2xl bg-gray-50 hover:bg-gray-100 transition-colors">
                            <div class="flex items-center space-x-3">
                                <div class="w-3 h-3 rounded-full 
                                    @if($status === 'approved') bg-green-500
                                    @elseif($status === 'rejected') bg-red-500
                                    @elseif($status === 'under_review') bg-yellow-500
                                    @elseif($status === 'submitted') bg-blue-500
                                    @elseif($status === 'disbursed') bg-purple-500
                                    @else bg-gray-500
                                    @endif">
                                </div>
                                <span class="text-sm font-semibold text-gray-700">{{ ucfirst(str_replace('_', ' ', $status)) }}</span>
                            </div>
                            <div class="flex items-center space-x-4">
                                <div class="w-32 bg-gray-200 rounded-full h-2">
                                    <div class="h-2 rounded-full 
                                        @if($status === 'approved') bg-green-500
                                        @elseif($status === 'rejected') bg-red-500
                                        @elseif($status === 'under_review') bg-yellow-500
                                        @elseif($status === 'submitted') bg-blue-500
                                        @elseif($status === 'disbursed') bg-purple-500
                                        @else bg-gray-500
                                        @endif" 
                                        style="width: {{ $totalApplications > 0 ? ($count / $totalApplications) * 100 : 0 }}%">
                                    </div>
                                </div>
                                <span class="text-sm font-bold text-gray-900 w-12 text-right">{{ $count }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Loan Products Performance -->
            <div class="lg:col-span-3 bg-white rounded-3xl shadow-sm p-6 border border-gray-100">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-gray-900">Product Performance</h3>
                    <button class="text-red-600 hover:text-red-700 text-sm font-medium">View All</button>
                </div>
                <div class="space-y-4 max-h-80 overflow-y-auto">
                    @forelse($topPerformingProducts as $product)
                        <div class="flex items-center justify-between p-4 rounded-2xl hover:bg-gray-50 transition-colors group">
                            <div class="flex-1">
                                <div class="flex items-center justify-between mb-2">
                                    <h4 class="text-sm font-semibold text-gray-900 group-hover:text-red-600 transition-colors">{{ $product->name }}</h4>
                                    <span class="text-xs text-gray-500">{{ $product->applications_count }} apps</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <div class="flex-1 bg-gray-200 rounded-full h-2">
                                        <div class="bg-gradient-to-r from-red-400 to-red-500 h-2 rounded-full" 
                                             style="width: {{ $product->applications_count > 0 ? ($product->approved_count / $product->applications_count) * 100 : 0 }}%">
                                        </div>
                                    </div>
                                    <span class="text-xs font-bold text-gray-600">{{ $product->applications_count > 0 ? round(($product->approved_count / $product->applications_count) * 100) : 0 }}%</span>
                                </div>
                                <div class="mt-2 flex items-center justify-between text-xs text-gray-500">
                                    <span>TSh {{ number_format($product->min_amount) }} - {{ number_format($product->max_amount) }}</span>
                                    <span>{{ $product->approved_count }} approved</span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                            </div>
                            <h4 class="text-sm font-semibold text-gray-900 mb-2">No Products Found</h4>
                            <p class="text-xs text-gray-500">Create your first loan product to start receiving applications.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Recent Applications Table -->
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-8 py-6 border-b border-gray-100 bg-gradient-to-r from-white to-gray-50">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-1">Recent Applications</h3>
                        <p class="text-gray-600">Latest loan applications requiring your attention</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <button class="bg-gray-100 text-gray-600 px-4 py-2 rounded-xl font-medium hover:bg-gray-200 transition-colors">
                            Filter
                        </button>
                        <button class="bg-red-600 text-white px-6 py-2 rounded-xl font-semibold hover:bg-red-700 transition-all duration-200 shadow-lg shadow-red-600/25">
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
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Actions</th>
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
                                        @if($application->status === 'approved')
                                            <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        @elseif($application->status === 'under_review')
                                            <svg class="w-3 h-3 mr-1.5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        @elseif($application->status === 'submitted')
                                            <svg class="w-3 h-3 mr-1.5 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                            </svg>
                                        @endif
                                        {{ ucfirst(str_replace('_', ' ', $application->status)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-6 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $application->created_at->format('M d, Y') }}</div>
                                    <div class="text-xs text-gray-500">{{ $application->created_at->format('g:i A') }}</div>
                                </td>
                                <td class="px-6 py-6 whitespace-nowrap">
                                    <div class="flex items-center space-x-2">
                                        @if($application->status === 'submitted')
                                            <button wire:click="reviewApplication({{ $application->id }})" 
                                                class="bg-yellow-600 text-white px-3 py-1.5 rounded-lg font-medium text-xs hover:bg-yellow-700 transition-colors">
                                                Review
                                            </button>
                                            <button wire:click="approveApplication({{ $application->id }})" 
                                                class="bg-green-600 text-white px-3 py-1.5 rounded-lg font-medium text-xs hover:bg-green-700 transition-colors">
                                                Approve
                                            </button>
                                        @elseif($application->status === 'under_review')
                                            <button wire:click="approveApplication({{ $application->id }})" 
                                                class="bg-green-600 text-white px-3 py-1.5 rounded-lg font-medium text-xs hover:bg-green-700 transition-colors">
                                                Approve
                                            </button>
                                            <button wire:click="rejectApplication({{ $application->id }})" 
                                                class="bg-red-600 text-white px-3 py-1.5 rounded-lg font-medium text-xs hover:bg-red-700 transition-colors">
                                                Reject
                                            </button>
                                        @else
                                            <button class="text-red-600 hover:text-red-700 p-2 rounded-xl hover:bg-red-50 transition-all duration-200">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-8 py-12 text-center">
                                    <div class="w-20 h-20 bg-gray-100 rounded-3xl flex items-center justify-center mx-auto mb-4">
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
</div>
</div>
