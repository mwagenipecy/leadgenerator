<div class="min-h-screen bg-gray-50">
    <div class="p-4 sm:p-6 lg:p-8">
        <!-- Mobile-First Header -->
        <div class="mb-6 sm:mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0">
                <div>
                    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-black mb-2">My Loan Dashboard</h1>
                    <p class="text-gray-600 text-sm sm:text-base lg:text-lg">Track your loan applications and manage your borrowing journey</p>
                </div>
                <div class="flex flex-col sm:flex-row items-start sm:items-center space-y-3 sm:space-y-0 sm:space-x-3">
                    <!-- NIDA Status Badge -->
                    <div class="flex items-center space-x-2 bg-{{ $nidaVerificationStatus === 'verified' ? 'green' : 'red' }}-50 px-3 py-2 rounded-full">
                        <div class="w-2 h-2 bg-{{ $nidaVerificationStatus === 'verified' ? 'green' : 'red' }}-500 rounded-full animate-pulse"></div>
                        <span class="text-xs sm:text-sm font-medium text-{{ $nidaVerificationStatus === 'verified' ? 'green' : 'red' }}-700">
                            {{ $nidaVerificationStatus === 'verified' ? 'NIDA Verified' : 'Verification Pending' }}
                        </span>
                    </div>
                    <!-- Apply Button -->
                    <button wire:click="applyForLoan" class="w-full sm:w-auto bg-red-600 text-white px-4 sm:px-6 py-2 rounded-lg font-semibold hover:bg-red-700 transition-all duration-200 shadow-lg text-sm sm:text-base">
                        Apply for Loan
                    </button>
                </div>
            </div>
        </div>

        <!-- Flash Messages -->
        @if (session()->has('message'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6 flex items-center space-x-3">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <span class="text-sm">{{ session('message') }}</span>
            </div>
        @endif

        <!-- Key Performance Metrics - Mobile Responsive Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-6 sm:mb-8">
            <!-- Total Applications Card -->
            <div class="bg-white rounded-lg shadow-sm p-4 sm:p-6 border border-gray-100 hover:shadow-md transition-all duration-300">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 sm:w-14 sm:h-14 bg-black rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 sm:w-7 sm:h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div class="text-right">
                        <p class="text-xs sm:text-sm font-medium text-gray-500">Total Applications</p>
                        <p class="text-2xl sm:text-3xl font-bold text-black">{{ $totalApplications }}</p>
                    </div>
                </div>
                <div class="flex items-center justify-between text-xs sm:text-sm">
                    <div class="flex items-center space-x-1 text-red-600">
                        <svg class="w-3 h-3 sm:w-4 sm:h-4 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="font-semibold">{{ $pendingApplications }} pending</span>
                    </div>
                    <span class="text-gray-500">{{ $approvedApplications }} approved</span>
                </div>
            </div>

            <!-- Approved Amount Card -->
            <div class="bg-white rounded-lg shadow-sm p-4 sm:p-6 border border-gray-100 hover:shadow-md transition-all duration-300">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 sm:w-14 sm:h-14 bg-red-600 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 sm:w-7 sm:h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="text-right">
                        <p class="text-xs sm:text-sm font-medium text-gray-500">Approved Amount</p>
                        <p class="text-xl sm:text-2xl lg:text-3xl font-bold text-red-600">
                            @if($totalApprovedAmount >= 1000000)
                                TSh {{ number_format($totalApprovedAmount/1000000, 1) }}M
                            @else
                                TSh {{ number_format($totalApprovedAmount/1000) }}K
                            @endif
                        </p>
                    </div>
                </div>
                <div class="flex items-center justify-between text-xs sm:text-sm">
                    <div class="flex items-center space-x-1 text-green-600">
                        <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span class="font-semibold">{{ $approvedApplications }} loans</span>
                    </div>
                    <span class="text-gray-500">approved</span>
                </div>
            </div>

            <!-- Credit Score Card -->
            <div class="bg-white rounded-lg shadow-sm p-4 sm:p-6 border border-gray-100 hover:shadow-md transition-all duration-300">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 sm:w-14 sm:h-14 bg-{{ $this->getCreditScoreColor() }}-600 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 sm:w-7 sm:h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <div class="text-right">
                        <p class="text-xs sm:text-sm font-medium text-gray-500">Credit Score</p>
                        <p class="text-2xl sm:text-3xl font-bold text-black">{{ $creditScore ?? 'N/A' }}</p>
                    </div>
                </div>
                <div class="flex items-center justify-between text-xs sm:text-sm">
                    @if($creditScore)
                        <div class="flex items-center space-x-1 text-{{ $this->getCreditScoreColor() }}-600">
                            <div class="w-2 h-2 sm:w-3 sm:h-3 bg-{{ $this->getCreditScoreColor() }}-500 rounded-full"></div>
                            <span class="font-semibold">{{ $this->getCreditScoreLabel() }}</span>
                        </div>
                        <span class="text-gray-500">rating</span>
                    @else
                        <span class="text-gray-500 text-xs">No credit data</span>
                    @endif
                </div>
            </div>

            <!-- Outstanding Balance Card -->
            <div class="bg-white rounded-lg shadow-sm p-4 sm:p-6 border border-gray-100 hover:shadow-md transition-all duration-300">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 sm:w-14 sm:h-14 bg-black rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 sm:w-7 sm:h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                        </svg>
                    </div>
                    <div class="text-right">
                        <p class="text-xs sm:text-sm font-medium text-gray-500">Outstanding Balance</p>
                        <p class="text-xl sm:text-2xl lg:text-3xl font-bold text-black">
                            @if($outstandingBalance >= 1000000)
                                TSh {{ number_format($outstandingBalance/1000000, 1) }}M
                            @else
                                TSh {{ number_format($outstandingBalance/1000) }}K
                            @endif
                        </p>
                    </div>
                </div>
                <div class="flex items-center justify-between text-xs sm:text-sm">
                    @if($nextPaymentDue)
                        <div class="flex items-center space-x-1 text-red-600">
                            <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="font-semibold">{{ $nextPaymentDue->diffForHumans() }}</span>
                        </div>
                        <span class="text-gray-500">next payment</span>
                    @else
                        <span class="text-gray-500 text-xs">No active loans</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Main Content Grid - Mobile Responsive -->
        <div class="grid grid-cols-1 lg:grid-cols-7 gap-6 mb-6 sm:mb-8">
            <!-- Application Status Distribution -->
            <div class="lg:col-span-4 bg-white rounded-lg shadow-sm p-4 sm:p-6 lg:p-8 border border-gray-100">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 sm:mb-8">
                    <div>
                        <h3 class="text-xl sm:text-2xl font-bold text-black mb-2">Application Status</h3>
                        <p class="text-gray-600 text-sm sm:text-base">Track the progress of all your loan applications</p>
                    </div>
                </div>
                
                @if(array_sum($applicationsByStatus) > 0)
                    <div class="space-y-3 sm:space-y-4">
                        @foreach($applicationsByStatus as $status => $count)
                            <div class="flex items-center justify-between p-3 sm:p-4 rounded-lg bg-gray-50 hover:bg-gray-100 transition-colors">
                                <div class="flex items-center space-x-3">
                                    <div class="w-3 h-3 rounded-full 
                                        @if($status === 'approved') bg-green-500
                                        @elseif($status === 'rejected') bg-red-500
                                        @elseif($status === 'under_review') bg-yellow-500
                                        @elseif($status === 'submitted') bg-blue-500
                                        @elseif($status === 'disbursed') bg-red-600
                                        @elseif($status === 'cancelled') bg-gray-500
                                        @else bg-gray-400
                                        @endif">
                                    </div>
                                    <span class="text-sm font-semibold text-black">{{ ucfirst(str_replace('_', ' ', $status)) }}</span>
                                </div>
                                <div class="flex items-center space-x-3 sm:space-x-4">
                                    <div class="w-20 sm:w-32 bg-gray-200 rounded-full h-2">
                                        <div class="h-2 rounded-full 
                                            @if($status === 'approved') bg-green-500
                                            @elseif($status === 'rejected') bg-red-500
                                            @elseif($status === 'under_review') bg-yellow-500
                                            @elseif($status === 'submitted') bg-blue-500
                                            @elseif($status === 'disbursed') bg-red-600
                                            @elseif($status === 'cancelled') bg-gray-500
                                            @else bg-gray-400
                                            @endif" 
                                            style="width: {{ $totalApplications > 0 ? ($count / $totalApplications) * 100 : 0 }}%">
                                        </div>
                                    </div>
                                    <span class="text-sm font-bold text-black w-6 sm:w-8 text-right">{{ $count }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 sm:py-12">
                        <div class="w-16 h-16 sm:w-20 sm:h-20 bg-gray-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 sm:w-10 sm:h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <h4 class="text-lg font-semibold text-black mb-2">No Applications Yet</h4>
                        <p class="text-gray-500 mb-4 text-sm sm:text-base">Start your borrowing journey by applying for your first loan.</p>
                        <button wire:click="applyForLoan" class="bg-red-600 text-white px-4 sm:px-6 py-2 rounded-lg font-semibold hover:bg-red-700 transition-colors text-sm sm:text-base">
                            Apply for Loan
                        </button>
                    </div>
                @endif
            </div>

            <!-- Recent Activity Feed -->
            <div class="lg:col-span-3 bg-white rounded-lg shadow-sm p-4 sm:p-6 border border-gray-100">
                <div class="flex items-center justify-between mb-4 sm:mb-6">
                    <h3 class="text-lg sm:text-xl font-bold text-black">Recent Activity</h3>
                </div>
                <div class="space-y-3 sm:space-y-4 max-h-64 sm:max-h-80 overflow-y-auto">
                    @forelse($recentActivity as $activity)
                        <div class="flex items-start space-x-3 p-3 rounded-lg hover:bg-gray-50 transition-colors">
                            <div class="w-8 h-8 sm:w-10 sm:h-10 bg-{{ $activity['color'] }}-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <div class="w-2 h-2 sm:w-3 sm:h-3 bg-{{ $activity['color'] }}-500 rounded-full"></div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-black">{{ $activity['message'] }}</p>
                                <p class="text-xs text-gray-600 mt-1 break-words">{{ $activity['details'] }}</p>
                                <p class="text-xs text-gray-400 mt-2">{{ $activity['time'] }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <p class="text-gray-500 text-sm">No recent activity</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- My Applications Table - Mobile Responsive -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden mb-6 sm:mb-8">
            <div class="px-4 sm:px-6 lg:px-8 py-4 sm:py-6 border-b border-gray-100 bg-gray-50">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-3 sm:space-y-0">
                    <div>
                        <h3 class="text-xl sm:text-2xl font-bold text-black mb-1">My Loan Applications</h3>
                        <p class="text-gray-600 text-sm sm:text-base">Track the status and details of your loan applications</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <button wire:click="applyForLoan" class="bg-red-600 text-white px-4 sm:px-6 py-2 rounded-lg font-semibold hover:bg-red-700 transition-all duration-200 shadow-lg text-sm sm:text-base">
                            New Application
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mobile Applications View -->
            <div class="block sm:hidden">
                @forelse($myApplications as $application)
                    <div class="border-b border-gray-100 p-4 hover:bg-gray-50 transition-colors">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center space-x-2">
                                <span class="text-sm font-bold text-black">TSh {{ number_format($application->requested_amount) }}</span>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold 
                                    @if($application->status === 'approved') bg-green-100 text-green-800
                                    @elseif($application->status === 'rejected') bg-red-100 text-red-800
                                    @elseif($application->status === 'under_review') bg-yellow-100 text-yellow-800
                                    @elseif($application->status === 'submitted') bg-blue-100 text-blue-800
                                    @elseif($application->status === 'disbursed') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ ucfirst(str_replace('_', ' ', $application->status)) }}
                                </span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <button wire:click="viewApplication({{ $application->id }})" class="text-red-600 hover:text-red-700 p-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </button>
                                @if(in_array($application->status, ['submitted', 'under_review']))
                                    <button wire:click="confirmWithdraw({{ $application->id }}, '{{ $application->application_number }}')" class="text-gray-400 hover:text-red-600 p-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                @endif
                            </div>
                        </div>
                        <div class="space-y-2 text-xs text-gray-600">
                            <div class="flex justify-between">
                                <span>Application #:</span>
                                <span class="font-medium text-blue-600">{{ $application->application_number }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Tenure:</span>
                                <span>{{ $application->requested_tenure_months }} months</span>
                            </div>
                            @if($application->lender)
                                <div class="flex justify-between">
                                    <span>Lender:</span>
                                    <span>{{ $application->lender->company_name }}</span>
                                </div>
                            @endif
                            @if($application->loanProduct)
                                <div class="flex justify-between">
                                    <span>Product:</span>
                                    <span>{{ $application->loanProduct->name }}</span>
                                </div>
                            @endif
                            <div class="flex justify-between">
                                <span>Applied:</span>
                                <span>{{ $application->created_at->format('M d, Y') }}</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center">
                        <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <h4 class="text-lg font-semibold text-black mb-2">No Applications Found</h4>
                        <p class="text-gray-500 mb-4 text-sm">You haven't submitted any loan applications yet.</p>
                        <button wire:click="applyForLoan" class="bg-red-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-red-700 transition-colors">
                            Apply for Your First Loan
                        </button>
                    </div>
                @endforelse
            </div>

            <!-- Desktop Applications Table -->
            <div class="hidden sm:block overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-black uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($myApplications as $application)
                            <tr class="hover:bg-gray-50 transition-colors duration-200">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div>
                                        <div class="text-sm font-bold text-black">TSh {{ number_format($application->requested_amount) }}</div>
                                        <div class="text-xs text-gray-500">{{ $application->requested_tenure_months }} months tenure</div>
                                        <div class="text-xs text-red-600 font-medium mt-1">#{{ $application->application_number }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($application->lender)
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 bg-red-600 rounded-lg flex items-center justify-center">
                                                <span class="text-white text-xs font-bold">{{ substr($application->lender->company_name, 0, 2) }}</span>
                                            </div>
                                            <div class="ml-3">
                                                <div class="text-sm text-black font-medium">{{ $application->lender->company_name }}</div>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-xs text-gray-400">Pending Assignment</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($application->loanProduct)
                                        <div class="text-sm text-black font-medium">{{ $application->loanProduct->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $application->loanProduct->interest_rate_min }}% - {{ $application->loanProduct->interest_rate_max }}%</div>
                                    @else
                                        <span class="text-xs text-gray-400">General Application</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold 
                                        @if($application->status === 'approved') bg-green-100 text-green-800 border border-green-200
                                        @elseif($application->status === 'rejected') bg-red-100 text-red-800 border border-red-200
                                        @elseif($application->status === 'under_review') bg-yellow-100 text-yellow-800 border border-yellow-200
                                        @elseif($application->status === 'submitted') bg-blue-100 text-blue-800 border border-blue-200
                                        @elseif($application->status === 'disbursed') bg-red-100 text-red-800 border border-red-200
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
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-black">{{ $application->created_at->format('M d, Y') }}</div>
                                    <div class="text-xs text-gray-500">{{ $application->created_at->format('g:i A') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center space-x-2">
                                        <button wire:click="viewApplication({{ $application->id }})" class="text-red-600 hover:text-red-700 p-2 rounded-lg hover:bg-red-50 transition-all duration-200">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </button>
                                        @if(in_array($application->status, ['submitted', 'under_review']))
                                            <button wire:click="confirmWithdraw({{ $application->id }}, '{{ $application->application_number }}')" class="text-gray-400 hover:text-red-600 p-2 rounded-lg hover:bg-red-50 transition-all duration-200">
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
                                    <div class="w-20 h-20 bg-gray-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    </div>
                                    <h4 class="text-lg font-semibold text-black mb-2">No Applications Found</h4>
                                    <p class="text-gray-500 mb-4">You haven't submitted any loan applications yet.</p>
                                    <button wire:click="applyForLoan" class="bg-red-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-red-700 transition-colors">
                                        Apply for Your First Loan
                                    </button>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Available Loan Products - Table View -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-4 sm:px-6 lg:px-8 py-4 sm:py-6 border-b border-gray-100 bg-gray-50">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-3 sm:space-y-0">
                    <div>
                        <h3 class="text-xl sm:text-2xl font-bold text-black mb-1">Available Loan Products</h3>
                        <p class="text-gray-600 text-sm sm:text-base">Explore loan products that match your profile</p>
                    </div>
                </div>
            </div>

            <!-- Mobile Product List -->
            <div class="block sm:hidden">
                @forelse($availableLoanProducts as $product)
                    <div class="border-b border-gray-100 p-4 hover:bg-gray-50 transition-colors">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-red-600 rounded-lg flex items-center justify-center">
                                    <span class="text-white text-xs font-bold">{{ substr($product->name, 0, 2) }}</span>
                                </div>
                                <div>
                                    <h4 class="text-sm font-bold text-black">{{ $product->name }}</h4>
                                    <p class="text-xs text-gray-500">{{ $product->lender->company_name }}</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <button wire:click="viewProductDetails({{ $product->id }})" class="text-red-600 hover:text-red-700 p-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </button>
                                <button wire:click="applyForLoan({{ $product->id }})" class="bg-red-600 text-white px-3 py-1 rounded text-xs font-semibold hover:bg-red-700 transition-colors">
                                    Apply
                                </button>
                            </div>
                        </div>
                        <div class="space-y-1 text-xs text-gray-600">
                            <div class="flex justify-between">
                                <span>Amount:</span>
                                <span class="font-medium">TSh {{ number_format($product->min_amount/1000) }}K - 
                                    @if($product->max_amount >= 1000000){{ number_format($product->max_amount/1000000, 1) }}M
                                    @else{{ number_format($product->max_amount/1000) }}K@endif
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span>Interest:</span>
                                <span class="font-medium text-red-600">{{ $product->interest_rate_min }}% - {{ $product->interest_rate_max }}%</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center">
                        <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                        </div>
                        <h4 class="text-lg font-semibold text-black mb-2">No Products Available</h4>
                        <p class="text-gray-500 text-sm">No loan products are currently available for your profile.</p>
                    </div>
                @endforelse
            </div>

            <!-- Desktop Product Table -->
            <div class="hidden sm:block overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-black uppercase tracking-wider">Product</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-black uppercase tracking-wider">Lender</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-black uppercase tracking-wider">Amount Range</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-black uppercase tracking-wider">Interest Rate</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-black uppercase tracking-wider">Tenure</th>
                            <th class="px-6 py-4 text-center text-xs font-bold text-black uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($availableLoanProducts as $product)
                            <tr class="hover:bg-gray-50 transition-colors duration-200">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-red-600 rounded-lg flex items-center justify-center flex-shrink-0">
                                            <span class="text-white text-xs font-bold">{{ substr($product->name, 0, 2) }}</span>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-bold text-black">{{ $product->name }}</div>
                                            @if($product->promotional_tag)
                                                <span class="text-xs font-medium text-red-600 bg-red-100 px-2 py-1 rounded-full">{{ $product->promotional_tag }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-black">{{ $product->lender->company_name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-semibold text-black">
                                        TSh {{ number_format($product->min_amount/1000) }}K - 
                                        @if($product->max_amount >= 1000000)
                                            {{ number_format($product->max_amount/1000000, 1) }}M
                                        @else
                                            {{ number_format($product->max_amount/1000) }}K
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-semibold text-red-600">{{ $product->interest_rate_min }}% - {{ $product->interest_rate_max }}%</div>
                                    <div class="text-xs text-gray-500">{{ ucfirst($product->interest_type) }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-black">{{ $product->min_tenure_months }} - {{ $product->max_tenure_months }} months</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="flex items-center justify-center space-x-2">
                                        <button wire:click="viewProductDetails({{ $product->id }})" class="text-black hover:text-red-600 p-2 rounded-lg hover:bg-gray-100 transition-all duration-200" title="View Details">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        </button>
                                        <button wire:click="applyForLoan({{ $product->id }})" class="bg-red-600 text-white px-4 py-2 rounded-lg font-semibold hover:bg-red-700 transition-colors text-sm">
                                            Apply Now
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-8 py-12 text-center">
                                    <div class="w-20 h-20 bg-gray-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                        </svg>
                                    </div>
                                    <h4 class="text-lg font-semibold text-black mb-2">No Products Available</h4>
                                    <p class="text-gray-500">No loan products are currently available for your profile.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Product Details Modal -->
    @if($showProductModal && $selectedProduct)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-lg p-6 max-w-lg w-full mx-4 max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-red-600 rounded-lg flex items-center justify-center">
                            <span class="text-white text-sm font-bold">{{ substr($selectedProduct->name, 0, 2) }}</span>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-black">{{ $selectedProduct->name }}</h3>
                            <p class="text-sm text-gray-600">{{ $selectedProduct->lender->company_name }}</p>
                        </div>
                    </div>
                    <button wire:click="closeProductModal" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <!-- Product Details -->
                <div class="space-y-4">
                    <!-- Amount Range -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h4 class="text-sm font-semibold text-black mb-2">Loan Amount</h4>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Range:</span>
                            <span class="text-sm font-bold text-black">
                                TSh {{ number_format($selectedProduct->min_amount/1000) }}K - 
                                @if($selectedProduct->max_amount >= 1000000)
                                    {{ number_format($selectedProduct->max_amount/1000000, 1) }}M
                                @else
                                    {{ number_format($selectedProduct->max_amount/1000) }}K
                                @endif
                            </span>
                        </div>
                    </div>

                    <!-- Interest Rate -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h4 class="text-sm font-semibold text-black mb-2">Interest Rate</h4>
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm text-gray-600">Rate:</span>
                            <span class="text-sm font-bold text-red-600">{{ $selectedProduct->interest_rate_min }}% - {{ $selectedProduct->interest_rate_max }}%</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Type:</span>
                            <span class="text-sm font-medium text-black">{{ ucfirst($selectedProduct->interest_type) }}</span>
                        </div>
                    </div>

                    <!-- Tenure -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h4 class="text-sm font-semibold text-black mb-2">Loan Tenure</h4>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Duration:</span>
                            <span class="text-sm font-bold text-black">{{ $selectedProduct->min_tenure_months }} - {{ $selectedProduct->max_tenure_months }} months</span>
                        </div>
                    </div>

                    <!-- DSR & Credit Score -->
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="text-sm font-semibold text-black mb-2">Max DSR</h4>
                            <div class="text-center">
                                <span class="text-lg font-bold text-red-600">{{ $selectedProduct->minimum_dsr ?? 'N/A' }}%</span>
                            </div>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="text-sm font-semibold text-black mb-2">Min Credit Score</h4>
                            <div class="text-center">
                                <span class="text-lg font-bold text-black">{{ $selectedProduct->min_credit_score ?? 'No requirement' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Processing Time -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h4 class="text-sm font-semibold text-black mb-2">Processing Timeline</h4>
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm text-gray-600">Approval:</span>
                            <span class="text-sm font-medium text-black">{{ $selectedProduct->approval_time_days }} days</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Disbursement:</span>
                            <span class="text-sm font-medium text-black">{{ $selectedProduct->disbursement_time_days }} days</span>
                        </div>
                    </div>

                    <!-- Additional Features -->
                    @if($selectedProduct->promotional_tag)
                        <div class="bg-red-50 rounded-lg p-4 border border-red-200">
                            <div class="flex items-center space-x-2">
                                <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span class="text-sm font-semibold text-red-700">{{ $selectedProduct->promotional_tag }}</span>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Action Buttons -->
                <div class="flex space-x-3 mt-6">
                    <button wire:click="closeProductModal" class="flex-1 px-4 py-2 text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                        Close
                    </button>
                    <button wire:click="applyForLoan({{ $selectedProduct->id }})" class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-semibold">
                        Apply Now
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Withdraw Application Modal -->
    @if($showWithdrawModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center mr-4">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-black">Withdraw Application</h3>
                </div>
                <p class="text-gray-600 mb-6">Are you sure you want to withdraw application "{{ $selectedApplicationNumber }}"? This action cannot be undone.</p>
                <div class="flex justify-end space-x-3">
                    <button wire:click="closeWithdrawModal" class="px-4 py-2 text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                        Cancel
                    </button>
                    <button wire:click="withdrawApplication" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                        Withdraw Application
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>