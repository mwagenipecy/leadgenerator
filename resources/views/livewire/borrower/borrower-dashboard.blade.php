<div>
{{-- resources/views/livewire/borrower/dashboard.blade.php --}}
<div>
    <div class="p-8">
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-4xl font-bold text-gray-900 mb-2">My Loan Dashboard</h1>
                    <p class="text-gray-600 text-lg">Track your loan applications and manage your borrowing journey</p>
                </div>
                <div class="flex items-center space-x-3">
                    <div class="flex items-center space-x-2 bg-{{ $nidaVerificationStatus === 'verified' ? 'green' : 'yellow' }}-50 px-4 py-2 rounded-full">
                        <div class="w-2 h-2 bg-{{ $nidaVerificationStatus === 'verified' ? 'green' : 'yellow' }}-400 rounded-full animate-pulse"></div>
                        <span class="text-sm font-medium text-{{ $nidaVerificationStatus === 'verified' ? 'green' : 'yellow' }}-700">
                            {{ $nidaVerificationStatus === 'verified' ? 'NIDA Verified' : 'Verification Pending' }}
                        </span>
                    </div>
                    <button class="bg-red-600 text-white px-6 py-2 rounded-xl font-semibold hover:bg-red-700 transition-all duration-200 shadow-lg shadow-red-600/25">
                        Apply for Loan
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

        <!-- Profile Completion Alert -->
        @if($completionPercentage < 80)
            <div class="bg-blue-50 border border-blue-200 rounded-3xl p-6 mb-8">
                <div class="flex items-start space-x-4">
                    <div class="w-12 h-12 bg-blue-100 rounded-2xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-blue-900 mb-2">Complete Your Profile</h3>
                        <p class="text-blue-700 mb-4">Complete your profile to improve your loan approval chances and unlock better rates.</p>
                        <div class="flex items-center space-x-4">
                            <div class="flex-1 bg-blue-200 rounded-full h-3">
                                <div class="bg-blue-600 h-3 rounded-full transition-all duration-500" style="width: {{ $completionPercentage }}%"></div>
                            </div>
                            <span class="text-sm font-bold text-blue-600">{{ $completionPercentage }}%</span>
                        </div>
                    </div>
                    <button class="bg-blue-600 text-white px-4 py-2 rounded-xl font-medium hover:bg-blue-700 transition-colors">
                        Complete Now
                    </button>
                </div>
            </div>
        @endif

        <!-- Key Performance Metrics -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
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
                    <div class="flex items-center space-x-1 text-orange-600">
                        <svg class="w-4 h-4 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="text-sm font-semibold">{{ $pendingApplications }} pending</span>
                    </div>
                    <span class="text-sm text-gray-500">{{ $approvedApplications }} approved</span>
                </div>
            </div>

            <!-- Approved Amount Card -->
            <div class="bg-white rounded-3xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-all duration-300 group hover:border-green-500/20">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-14 h-14 bg-green-100 rounded-2xl flex items-center justify-center group-hover:bg-green-200 transition-colors">
                        <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-medium text-gray-500">Approved Amount</p>
                        <p class="text-3xl font-bold text-gray-900 group-hover:text-green-600 transition-colors">TSh {{ number_format($totalApprovedAmount/1000000, 1) }}M</p>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-1 text-green-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span class="text-sm font-semibold">{{ $approvedApplications }} loans</span>
                    </div>
                    <span class="text-sm text-gray-500">approved</span>
                </div>
            </div>

            <!-- Credit Score Card -->
            <div class="bg-white rounded-3xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-all duration-300 group hover:border-purple-500/20">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-14 h-14 bg-purple-100 rounded-2xl flex items-center justify-center group-hover:bg-purple-200 transition-colors">
                        <svg class="w-7 h-7 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-medium text-gray-500">Credit Score</p>
                        <p class="text-3xl font-bold text-gray-900 group-hover:text-purple-600 transition-colors">{{ $creditScore }}</p>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-1 text-{{ $creditScore >= 650 ? 'green' : 'yellow' }}-600">
                        <div class="w-3 h-3 bg-{{ $creditScore >= 650 ? 'green' : 'yellow' }}-500 rounded-full"></div>
                        <span class="text-sm font-semibold">{{ $creditScore >= 700 ? 'Excellent' : ($creditScore >= 650 ? 'Good' : 'Fair') }}</span>
                    </div>
                    <span class="text-sm text-gray-500">rating</span>
                </div>
            </div>

            <!-- Outstanding Balance Card -->
            <div class="bg-white rounded-3xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-all duration-300 group hover:border-red-500/20">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-14 h-14 bg-red-100 rounded-2xl flex items-center justify-center group-hover:bg-red-200 transition-colors">
                        <svg class="w-7 h-7 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                        </svg>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-medium text-gray-500">Outstanding Balance</p>
                        <p class="text-3xl font-bold text-gray-900 group-hover:text-red-600 transition-colors">TSh {{ number_format($outstandingBalance/1000000, 1) }}M</p>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-1 text-red-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="text-sm font-semibold">{{ $nextPaymentDue->diffForHumans() }}</span>
                    </div>
                    <span class="text-sm text-gray-500">next payment</span>
                </div>
            </div>
        </div>

        <!-- Application Status and Activity Section -->
        <div class="grid grid-cols-1 lg:grid-cols-7 gap-6 mb-8">
            <!-- Application Status Distribution -->
            <div class="lg:col-span-4 bg-white rounded-3xl shadow-sm p-8 border border-gray-100">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">My Application Status</h3>
                        <p class="text-gray-600">Track the progress of all your loan applications</p>
                    </div>
                </div>
                @if($applicationsByStatus->count() > 0)
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
                                    <span class="text-sm font-bold text-gray-900 w-8 text-right">{{ $count }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="w-20 h-20 bg-gray-100 rounded-3xl flex items-center justify-center mx-auto mb-4">
                            <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <h4 class="text-lg font-semibold text-gray-900 mb-2">No Applications Yet</h4>
                        <p class="text-gray-500 mb-4">Start your borrowing journey by applying for your first loan.</p>
                        <button class="bg-red-600 text-white px-6 py-2 rounded-xl font-semibold hover:bg-red-700 transition-colors">
                            Apply for Loan
                        </button>
                    </div>
                @endif
            </div>

            <!-- Recent Activity Feed -->
            <div class="lg:col-span-3 bg-white rounded-3xl shadow-sm p-6 border border-gray-100">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-gray-900">Recent Activity</h3>
                </div>
                <div class="space-y-4 max-h-80 overflow-y-auto">
                    @foreach($recentActivity as $activity)
                        <div class="flex items-start space-x-3 p-3 rounded-2xl hover:bg-gray-50 transition-colors group">
                            <div class="w-10 h-10 bg-{{ $activity['color'] }}-100 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:bg-{{ $activity['color'] }}-200 transition-colors">
                                <div class="w-3 h-3 bg-{{ $activity['color'] }}-500 rounded-full"></div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-900">{{ $activity['message'] }}</p>
                                <p class="text-xs text-gray-500 mt-1">{{ $activity['details'] }}</p>
                                <p class="text-xs text-gray-400 mt-2">{{ $activity['time'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- My Applications Table -->
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden mb-8">
            <div class="px-8 py-6 border-b border-gray-100 bg-gradient-to-r from-white to-gray-50">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-1">My Loan Applications</h3>
                        <p class="text-gray-600">Track the status and details of your loan applications</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <button class="bg-gray-100 text-gray-600 px-4 py-2 rounded-xl font-medium hover:bg-gray-200 transition-colors">
                            Filter
                        </button>
                        <button class="bg-red-600 text-white px-6 py-2 rounded-xl font-semibold hover:bg-red-700 transition-all duration-200 shadow-lg shadow-red-600/25">
                            New Application
                        </button>
                    </div>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-8 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Application Details</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Lender</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Product</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Applied Date</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($myApplications as $application)
                            <tr class="hover:bg-gray-50 transition-colors duration-200 group">
                                <td class="px-8 py-6 whitespace-nowrap">
                                    <div>
                                        <div class="text-sm font-bold text-gray-900">TSh {{ number_format($application->requested_amount) }}</div>
                                        <div class="text-xs text-gray-500">{{ $application->requested_tenure_months }} months tenure</div>
                                        <div class="text-xs text-blue-600 font-medium mt-1">#{{ $application->application_number }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-6 whitespace-nowrap">
                                    @if($application->lender)
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center">
                                                <span class="text-white text-xs font-bold">{{ substr($application->lender->company_name, 0, 2) }}</span>
                                            </div>
                                            <div class="ml-3">
                                                <div class="text-sm text-gray-900">{{ $application->lender->company_name }}</div>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-xs text-gray-400">Pending Assignment</span>
                                    @endif
                                </td>
                                <td class="px-6 py-6 whitespace-nowrap">
                                    @if($application->loanProduct)
                                        <div class="text-sm text-gray-900">{{ $application->loanProduct->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $application->loanProduct->interest_rate_min }}% - {{ $application->loanProduct->interest_rate_max }}%</div>
                                    @else
                                        <span class="text-xs text-gray-400">General Application</span>
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
                                        <button class="text-red-600 hover:text-red-700 p-2 rounded-xl hover:bg-red-50 transition-all duration-200">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </button>
                                        @if(in_array($application->status, ['submitted', 'under_review']))
                                            <button wire:click="withdrawApplication({{ $application->id }})" 
                                                class="text-gray-400 hover:text-red-600 p-2 rounded-xl hover:bg-red-50 transition-all duration-200">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
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
                                    <p class="text-gray-500 mb-4">You haven't submitted any loan applications yet.</p>
                                    <button class="bg-red-600 text-white px-6 py-2 rounded-xl font-semibold hover:bg-red-700 transition-colors">
                                        Apply for Your First Loan
                                    </button>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Available Loan Products -->
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-8 py-6 border-b border-gray-100 bg-gradient-to-r from-white to-gray-50">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-1">Available Loan Products</h3>
                        <p class="text-gray-600">Explore loan products that match your needs</p>
                    </div>
                    <button class="bg-red-600 text-white px-6 py-2 rounded-xl font-semibold hover:bg-red-700 transition-all duration-200 shadow-lg shadow-red-600/25">
                        View All Products
                    </button>
                </div>
            </div>
            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($availableLoanProducts as $product)
                        <div class="bg-gradient-to-br from-white to-gray-50 rounded-2xl p-6 border border-gray-100 hover:shadow-lg transition-all duration-300 group hover:border-red-500/20">
                            <div class="flex items-center justify-between mb-4">
                                <div class="w-12 h-12 bg-gradient-to-br from-red-500 to-red-600 rounded-xl flex items-center justify-center">
                                    <span class="text-white text-sm font-bold">{{ substr($product->name, 0, 2) }}</span>
                                </div>
                                <span class="text-xs font-semibold text-green-600 bg-green-100 px-2 py-1 rounded-full">
                                    {{ $product->interest_rate_min }}% - {{ $product->interest_rate_max }}%
                                </span>
                            </div>
                            <h4 class="text-lg font-bold text-gray-900 mb-2 group-hover:text-red-600 transition-colors">{{ $product->name }}</h4>
                            <p class="text-sm text-gray-600 mb-4 line-clamp-2">{{ $product->description ?? 'Flexible loan product with competitive rates and easy application process.' }}</p>
                            
                            <div class="space-y-2 mb-4">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">Amount Range:</span>
                                    <span class="font-semibold text-gray-900">TSh {{ number_format($product->min_amount/1000) }}K - {{ number_format($product->max_amount/1000000) }}M</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">Tenure:</span>
                                    <span class="font-semibold text-gray-900">{{ $product->min_tenure_months }} - {{ $product->max_tenure_months }} months</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">Lender:</span>
                                    <span class="font-semibold text-gray-900">{{ $product->lender->company_name }}</span>
                                </div>
                            </div>
                            
                            <button class="w-full bg-red-600 text-white py-2 rounded-xl font-semibold hover:bg-red-700 transition-colors group-hover:shadow-lg group-hover:shadow-red-600/25">
                                Apply Now
                            </button>
                        </div>
                    @empty
                        <div class="col-span-full text-center py-12">
                            <div class="w-20 h-20 bg-gray-100 rounded-3xl flex items-center justify-center mx-auto mb-4">
                                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                            </div>
                            <h4 class="text-lg font-semibold text-gray-900 mb-2">No Products Available</h4>
                            <p class="text-gray-500">No loan products are currently available. Please check back later.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

</div>
