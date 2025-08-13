<div>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center justify-between mb-6">
            <div>
                <div class="flex items-center space-x-3 mb-2">
                    <button wire:click="backToList" 
                            class="text-gray-600 hover:text-gray-800 p-2 rounded-lg hover:bg-gray-100 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </button>
                    <h1 class="text-4xl font-bold text-gray-900">{{ $application->application_number }}</h1>
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold {{ $this->getStatusBadgeClass($application->status) }}">
                        {{ ucwords(str_replace('_', ' ', $application->status)) }}
                    </span>
                </div>
                <div class="flex items-center space-x-6 text-sm text-gray-600">
                    <span class="flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        {{ $application->first_name }} {{ $application->last_name }}
                    </span>
                    <span class="flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Applied {{ $application->created_at->diffForHumans() }}
                    </span>
                    @if($application->submitted_at)
                        <span class="flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                            </svg>
                            Submitted {{ $application->submitted_at->format('M d, Y') }}
                        </span>
                    @endif
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="flex items-center space-x-3">
                @if($application->status === 'draft')
                    <button wire:click="editApplication" 
                            class="inline-flex items-center px-4 py-2 bg-gray-600 text-white font-medium rounded-lg hover:bg-gray-700 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Continue Editing
                    </button>
                @endif

                @if($application->status === 'submitted' && !$application->lender_id)
                    <button wire:click="selectLenders" 
                            class="inline-flex items-center px-4 py-2 bg-gray-600 text-white font-medium rounded-lg hover:bg-gray-700 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Choose Lenders
                    </button>
                @endif

                <button wire:click="downloadApplication" 
                        class="inline-flex items-center px-4 py-2 bg-gray-600 text-white font-medium rounded-lg hover:bg-gray-700 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Download PDF
                </button>

                

                @if(in_array($application->status, ['draft', 'submitted', 'under_review']))
                    <button wire:click="showCancelConfirmation" 
                            class="inline-flex items-center px-4 py-2 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Cancel Application
                    </button>
                @endif

               
            </div>
        </div>
    </div>

    <!-- Flash Messages -->
    @if (session()->has('message'))
        <div class="mb-6 bg-gray-50 border border-gray-200 text-gray-700 px-4 py-3 rounded-lg">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                {{ session('message') }}
            </div>
        </div>
    @endif

    <!-- Loading Indicator -->
    <div wire:loading class="fixed top-4 left-1/2 transform -translate-x-1/2 bg-white px-4 py-2 rounded-lg shadow-lg border border-gray-200 z-50">
        <div class="flex items-center space-x-2">
            <div class="animate-spin rounded-full h-4 w-4 border-2 border-red-600 border-t-transparent"></div>
            <span class="text-sm text-gray-600">Loading...</span>
        </div>
    </div>

    <!-- Quick Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Loan Amount</p>
                    <p class="text-2xl font-bold text-red-600">TSh {{ number_format($application->requested_amount) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Loan Period</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $application->requested_tenure_months }}</p>
                    <p class="text-xs text-gray-500">months</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Estimated Payment</p>
                    <p class="text-2xl font-bold text-gray-600">TSh {{ number_format($this->calculateEstimatedPayment()) }}</p>
                    <p class="text-xs text-gray-500">per month</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $this->getStatusIcon($application->status) }}"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Progress</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $this->getProgressPercentage($application->status) }}%</p>
                    <p class="text-xs text-gray-500">complete</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Progress Bar -->
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 mb-8">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-gray-900">Application Progress</h3>
            <span class="text-sm text-gray-600">{{ $this->getProgressPercentage($application->status) }}% Complete</span>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-3 mb-4">
            <div class="bg-gradient-to-r from-red-600 to-red-700 h-3 rounded-full transition-all duration-500" 
                 style="width: {{ $this->getProgressPercentage($application->status) }}%"></div>
        </div>
        
        <!-- Status Timeline -->
        <div class="flex items-center justify-between text-sm">
            <div class="flex flex-col items-center {{ $application->status === 'draft' ? 'text-red-600 font-medium' : ($this->getProgressPercentage($application->status) > 20 ? 'text-gray-600' : 'text-gray-400') }}">
                <div class="w-3 h-3 rounded-full {{ $application->status === 'draft' ? 'bg-red-600' : ($this->getProgressPercentage($application->status) > 20 ? 'bg-gray-600' : 'bg-gray-300') }} mb-1"></div>
                <span>Draft</span>
            </div>
            <div class="flex flex-col items-center {{ $application->status === 'submitted' ? 'text-red-600 font-medium' : ($this->getProgressPercentage($application->status) > 40 ? 'text-gray-600' : 'text-gray-400') }}">
                <div class="w-3 h-3 rounded-full {{ $application->status === 'submitted' ? 'bg-red-600' : ($this->getProgressPercentage($application->status) > 40 ? 'bg-gray-600' : 'bg-gray-300') }} mb-1"></div>
                <span>Submitted</span>
            </div>
            <div class="flex flex-col items-center {{ $application->status === 'under_review' ? 'text-red-600 font-medium' : ($this->getProgressPercentage($application->status) > 60 ? 'text-gray-600' : 'text-gray-400') }}">
                <div class="w-3 h-3 rounded-full {{ $application->status === 'under_review' ? 'bg-red-600' : ($this->getProgressPercentage($application->status) > 60 ? 'bg-gray-600' : 'bg-gray-300') }} mb-1"></div>
                <span>Under Review</span>
            </div>
            <div class="flex flex-col items-center {{ $application->status === 'approved' ? 'text-red-600 font-medium' : ($this->getProgressPercentage($application->status) > 80 ? 'text-gray-600' : 'text-gray-400') }}">
                <div class="w-3 h-3 rounded-full {{ $application->status === 'approved' ? 'bg-red-600' : ($this->getProgressPercentage($application->status) > 80 ? 'bg-gray-600' : 'bg-gray-300') }} mb-1"></div>
                <span>Approved</span>
            </div>
            <div class="flex flex-col items-center {{ $application->status === 'disbursed' ? 'text-red-600 font-medium' : ($this->getProgressPercentage($application->status) >= 100 ? 'text-gray-600' : 'text-gray-400') }}">
                <div class="w-3 h-3 rounded-full {{ $application->status === 'disbursed' ? 'bg-red-600' : ($this->getProgressPercentage($application->status) >= 100 ? 'bg-gray-600' : 'bg-gray-300') }} mb-1"></div>
                <span>Disbursed</span>
            </div>
        </div>
    </div>

    <!-- Tab Navigation -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 mb-8">
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex space-x-8 px-6" aria-label="Tabs">
                <button wire:click="switchTab('overview')" 
                        class="py-4 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'overview' ? 'border-red-500 text-red-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} transition-all">
                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    Overview
                </button>
                <button wire:click="switchTab('personal')" 
                        class="py-4 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'personal' ? 'border-red-500 text-red-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} transition-all">
                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Personal Info
                </button>
                <button wire:click="switchTab('financial')" 
                        class="py-4 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'financial' ? 'border-red-500 text-red-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} transition-all">
                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                    </svg>
                    Financial
                </button>
                <button wire:click="switchTab('documents')" 
                        class="py-4 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'documents' ? 'border-red-500 text-red-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} transition-all">
                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Documents
                    @if($application->documents && $application->documents->count() > 0)
                        <span class="ml-2 bg-red-100 text-red-600 text-xs px-2 py-1 rounded-full">{{ $application->documents->count() }}</span>
                    @endif
                </button>
                <button wire:click="switchTab('timeline')" 
                        class="py-4 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'timeline' ? 'border-red-500 text-red-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} transition-all">
                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Timeline
                </button>
            </nav>
        </div>

        <!-- Tab Content -->
        <div class="p-6">
            <!-- Overview Tab -->
            @if($activeTab === 'overview')
                <div class="space-y-8">
                    <!-- Loan Details -->
                    <div class="bg-gradient-to-br from-red-50 to-pink-50 rounded-2xl p-6 border border-red-100">
                        <h3 class="text-xl font-bold text-gray-900 mb-4">Loan Details</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <div class="mb-4">
                                    <div class="text-sm font-medium text-gray-600 mb-1">Requested Amount</div>
                                    <div class="text-3xl font-bold text-red-600">TSh {{ number_format($application->requested_amount) }}</div>
                                </div>
                                <div class="mb-4">
                                    <div class="text-sm font-medium text-gray-600 mb-1">Loan Period</div>
                                    <div class="text-xl font-bold text-gray-900">{{ $application->requested_tenure_months }} months</div>
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-600 mb-1">Purpose</div>
                                    <div class="text-lg text-gray-900">{{ $application->loan_purpose ? ucwords(str_replace('_', ' ', $application->loan_purpose)) : 'Not specified' }}</div>
                                </div>
                            </div>
                            <div>
                                <div class="mb-4">
                                    <div class="text-sm font-medium text-gray-600 mb-1">Estimated Monthly Payment</div>
                                    <div class="text-2xl font-bold text-gray-600">TSh {{ number_format($this->calculateEstimatedPayment()) }}</div>
                                </div>
                                <div class="mb-4">
                                    <div class="text-sm font-medium text-gray-600 mb-1">Total Amount Payable</div>
                                    <div class="text-xl font-bold text-gray-900">TSh {{ number_format($this->calculateEstimatedPayment() * $application->requested_tenure_months) }}</div>
                                </div>
                                @if($application->debt_to_income_ratio)
                                    <div>
                                        <div class="text-sm font-medium text-gray-600 mb-1">Debt Service Ratio</div>
                                        <div class="text-lg font-bold {{ $application->debt_to_income_ratio <= 30 ? 'text-gray-600' : ($application->debt_to_income_ratio <= 40 ? 'text-yellow-600' : 'text-red-600') }}">
                                            {{ number_format($application->debt_to_income_ratio, 1) }}%
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div>
                                @if($application->lender)
                                    <div class="mb-4">
                                        <div class="text-sm font-medium text-gray-600 mb-1">Lender</div>
                                        <div class="text-lg font-bold text-gray-900">{{ $application->lender->company_name }}</div>
                                        @if($application->loanProduct)
                                            <div class="text-sm text-gray-600">{{ $application->loanProduct->name }}</div>
                                        @endif
                                    </div>
                                @endif
                                @if($application->loanProduct)
                                    <div class="mb-4">
                                        <div class="text-sm font-medium text-gray-600 mb-1">Interest Rate Range</div>
                                        <div class="text-lg text-gray-900">{{ $application->loanProduct->interest_rate_min }}% - {{ $application->loanProduct->interest_rate_max }}%</div>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-600 mb-1">Processing Time</div>
                                        <div class="text-lg text-gray-900">{{ $application->loanProduct->approval_time_days }} days</div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Application Status -->
                    <div class="bg-gradient-to-br from-gray-50 to-indigo-50 rounded-2xl p-6 border border-gray-100">
                        <h3 class="text-xl font-bold text-gray-900 mb-4">Current Status</h3>
                        <div class="flex items-center space-x-4">
                            <div class="w-16 h-16 rounded-full flex items-center justify-center {{ $this->getStatusBadgeClass($application->status) }}">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $this->getStatusIcon($application->status) }}"/>
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-xl font-bold text-gray-900">{{ ucwords(str_replace('_', ' ', $application->status)) }}</h4>
                                <p class="text-gray-600 mt-1">
                                    @switch($application->status)
                                        @case('draft')
                                            Your application is being prepared. Continue editing to complete all required information.
                                            @break
                                        @case('submitted')
                                            Your application has been submitted and is awaiting lender assignment or review.
                                            @break
                                        @case('under_review')
                                            Your application is being evaluated by {{ $application->lender->company_name ?? 'the lender' }}.
                                            @break
                                        @case('approved')
                                            Congratulations! Your loan has been approved and is awaiting disbursement.
                                            @break
                                        @case('rejected')
                                            Your application was not approved. See the timeline for details.
                                            @break
                                        @case('disbursed')
                                            Your loan has been successfully disbursed. Funds should be available in your account.
                                            @break
                                        @case('cancelled')
                                            This application has been cancelled.
                                            @break
                                        @default
                                            Application status is being updated.
                                    @endswitch
                                </p>
                                @if($application->status === 'submitted' && !$application->lender_id)
                                    <div class="mt-3">
                                        <button wire:click="selectLenders" 
                                                class="bg-gray-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-gray-700 transition-colors">
                                            Choose Lenders Now
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Quick Summary -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-white rounded-xl p-6 border border-gray-100">
                            <h4 class="text-lg font-bold text-gray-900 mb-4">Financial Summary</h4>
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Monthly Income:</span>
                                    <span class="font-medium text-gray-600">TSh {{ number_format($application->total_monthly_income) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Monthly Expenses:</span>
                                    <span class="font-medium text-red-600">TSh {{ number_format($application->monthly_expenses) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Existing Loans:</span>
                                    <span class="font-medium text-orange-600">TSh {{ number_format($application->existing_loan_payments) }}</span>
                                </div>
                                <hr class="border-gray-200">
                                <div class="flex justify-between">
                                    <span class="text-gray-900 font-medium">Net Available:</span>
                                    <span class="font-bold {{ $this->getNetAvailableIncome() > 0 ? 'text-gray-600' : 'text-red-600' }}">
                                        TSh {{ number_format($this->getNetAvailableIncome()) }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-xl p-6 border border-gray-100">
                            <h4 class="text-lg font-bold text-gray-900 mb-4">Application Info</h4>
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Application Date:</span>
                                    <span class="font-medium">{{ $application->created_at->format('M d, Y') }}</span>
                                </div>
                                @if($application->submitted_at)
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Submission Date:</span>
                                        <span class="font-medium">{{ $application->submitted_at->format('M d, Y') }}</span>
                                    </div>
                                @endif
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Employment:</span>
                                    <span class="font-medium">{{ ucwords(str_replace('_', ' ', $application->employment_status)) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Documents:</span>
                                    <span class="font-medium">{{ $application->documents ? $application->documents->count() : 0 }} uploaded</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Personal Info Tab -->
            @if($activeTab === 'personal')
                <div class="space-y-8">
                    <!-- Personal Details -->
                    <div class="bg-gradient-to-br from-gray-50 to-emerald-50 rounded-2xl p-6 border border-gray-100">
                        <h3 class="text-xl font-bold text-gray-900 mb-6">Personal Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div>
                                <h4 class="text-lg font-semibold text-gray-900 mb-4">Basic Details</h4>
                                <div class="space-y-4">
                                    <div>
                                        <div class="text-sm font-medium text-gray-600">Full Name</div>
                                        <div class="text-lg text-gray-900">
                                            {{ $application->first_name }} 
                                            {{ $application->middle_name ? $application->middle_name . ' ' : '' }}
                                            {{ $application->last_name }}
                                        </div>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-600">Date of Birth</div>
                                        <div class="text-lg text-gray-900">
                                            {{ $application->date_of_birth ? $application->date_of_birth->format('F d, Y') : 'Not provided' }}
                                            @if($application->date_of_birth)
                                                <span class="text-sm text-gray-500">({{ $application->date_of_birth->age }} years old)</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-600">Gender</div>
                                        <div class="text-lg text-gray-900">{{ $application->gender ? ucfirst($application->gender) : 'Not specified' }}</div>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-600">Marital Status</div>
                                        <div class="text-lg text-gray-900">{{ $application->marital_status ? ucfirst($application->marital_status) : 'Not specified' }}</div>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <h4 class="text-lg font-semibold text-gray-900 mb-4">Contact Information</h4>
                                <div class="space-y-4">
                                    <div>
                                        <div class="text-sm font-medium text-gray-600">National ID</div>
                                        <div class="text-lg text-gray-900 font-mono">{{ $application->national_id ?: 'Not provided' }}</div>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-600">Phone Number</div>
                                        <div class="text-lg text-gray-900">{{ $application->phone_number ?: 'Not provided' }}</div>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-600">Email Address</div>
                                        <div class="text-lg text-gray-900">{{ $application->email ?: 'Not provided' }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Address Information -->
                    <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-2xl p-6 border border-purple-100">
                        <h3 class="text-xl font-bold text-gray-900 mb-6">Address Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div>
                                <h4 class="text-lg font-semibold text-gray-900 mb-4">Current Address</h4>
                                <div class="space-y-4">
                                    <div>
                                        <div class="text-sm font-medium text-gray-600">Street Address</div>
                                        <div class="text-lg text-gray-900">{{ $application->current_address ?: 'Not provided' }}</div>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <div class="text-sm font-medium text-gray-600">City</div>
                                            <div class="text-lg text-gray-900">{{ $application->current_city }}</div>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-600">Region</div>
                                            <div class="text-lg text-gray-900">{{ $application->current_region }}</div>
                                        </div>
                                    </div>
                                    @if($application->current_postal_code)
                                        <div>
                                            <div class="text-sm font-medium text-gray-600">Postal Code</div>
                                            <div class="text-lg text-gray-900">{{ $application->current_postal_code }}</div>
                                        </div>
                                    @endif
                                    @if($application->years_at_current_address)
                                        <div>
                                            <div class="text-sm font-medium text-gray-600">Years at Address</div>
                                            <div class="text-lg text-gray-900">{{ $application->years_at_current_address }} years</div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div>
                                <h4 class="text-lg font-semibold text-gray-900 mb-4">Permanent Address</h4>
                                @if($application->is_permanent_same_as_current)
                                    <div class="bg-white rounded-lg p-4 border border-purple-200">
                                        <div class="flex items-center text-purple-700">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            Same as current address
                                        </div>
                                    </div>
                                @else
                                    <div class="space-y-4">
                                        <div>
                                            <div class="text-sm font-medium text-gray-600">Street Address</div>
                                            <div class="text-lg text-gray-900">{{ $application->permanent_address ?: 'Not provided' }}</div>
                                        </div>
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <div class="text-sm font-medium text-gray-600">City</div>
                                                <div class="text-lg text-gray-900">{{ $application->permanent_city ?: 'Not provided' }}</div>
                                            </div>
                                            <div>
                                                <div class="text-sm font-medium text-gray-600">Region</div>
                                                <div class="text-lg text-gray-900">{{ $application->permanent_region ?: 'Not provided' }}</div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Emergency Contact -->
                    @if($application->emergency_contact_name)
                        <div class="bg-gradient-to-br from-orange-50 to-red-50 rounded-2xl p-6 border border-orange-100">
                            <h3 class="text-xl font-bold text-gray-900 mb-6">Emergency Contact</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div>
                                    <div class="space-y-4">
                                        <div>
                                            <div class="text-sm font-medium text-gray-600">Contact Name</div>
                                            <div class="text-lg text-gray-900">{{ $application->emergency_contact_name }}</div>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-600">Relationship</div>
                                            <div class="text-lg text-gray-900">{{ $application->emergency_contact_relationship ? ucfirst($application->emergency_contact_relationship) : 'Not specified' }}</div>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <div class="space-y-4">
                                        <div>
                                            <div class="text-sm font-medium text-gray-600">Phone Number</div>
                                            <div class="text-lg text-gray-900">{{ $application->emergency_contact_phone ?: 'Not provided' }}</div>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-600">Address</div>
                                            <div class="text-lg text-gray-900">{{ $application->emergency_contact_address ?: 'Not provided' }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            @endif

            <!-- Financial Tab -->
            @if($activeTab === 'financial')
                <div class="space-y-8">
                    <!-- Employment Information -->
                    <div class="bg-gradient-to-br from-gray-50 to-indigo-50 rounded-2xl p-6 border border-gray-100">
                        <h3 class="text-xl font-bold text-gray-900 mb-6">Employment Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div>
                                <h4 class="text-lg font-semibold text-gray-900 mb-4">Employment Status</h4>
                                <div class="space-y-4">
                                    <div>
                                        <div class="text-sm font-medium text-gray-600">Current Status</div>
                                        <div class="text-lg text-gray-900">{{ ucwords(str_replace('_', ' ', $application->employment_status)) }}</div>
                                    </div>
                                    @if($application->employment_status === 'employed')
                                        <div>
                                            <div class="text-sm font-medium text-gray-600">Employer</div>
                                            <div class="text-lg text-gray-900">{{ $application->employer_name ?: 'Not provided' }}</div>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-600">Job Title</div>
                                            <div class="text-lg text-gray-900">{{ $application->job_title ?: 'Not provided' }}</div>
                                        </div>
                                        @if($application->employment_sector)
                                            <div>
                                                <div class="text-sm font-medium text-gray-600">Sector</div>
                                                <div class="text-lg text-gray-900">{{ ucwords(str_replace('_', ' ', $application->employment_sector)) }}</div>
                                            </div>
                                        @endif
                                        @if($application->months_with_current_employer)
                                            <div>
                                                <div class="text-sm font-medium text-gray-600">Time with Employer</div>
                                                <div class="text-lg text-gray-900">{{ $application->months_with_current_employer }} months</div>
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            </div>
                            @if($application->employment_status === 'self_employed')
                                <div>
                                    <h4 class="text-lg font-semibold text-gray-900 mb-4">Business Information</h4>
                                    <div class="space-y-4">
                                        <div>
                                            <div class="text-sm font-medium text-gray-600">Business Name</div>
                                            <div class="text-lg text-gray-900">{{ $application->business_name ?: 'Not provided' }}</div>
                                        </div>
                                        @if($application->business_type)
                                            <div>
                                                <div class="text-sm font-medium text-gray-600">Business Type</div>
                                                <div class="text-lg text-gray-900">{{ ucwords(str_replace('_', ' ', $application->business_type)) }}</div>
                                            </div>
                                        @endif
                                        @if($application->business_registration_number)
                                            <div>
                                                <div class="text-sm font-medium text-gray-600">Registration Number</div>
                                                <div class="text-lg text-gray-900 font-mono">{{ $application->business_registration_number }}</div>
                                            </div>
                                        @endif
                                        @if($application->years_in_business)
                                            <div>
                                                <div class="text-sm font-medium text-gray-600">Years in Business</div>
                                                <div class="text-lg text-gray-900">{{ $application->years_in_business }} years</div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Financial Summary -->
                        <div class="mt-6 bg-gray-50 rounded-lg p-6">
                            <h4 class="text-lg font-bold text-gray-900 mb-4">Financial Summary</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                <div>
                                    <h5 class="text-sm font-semibold text-gray-700 mb-2">Monthly Income</h5>
                                    <p class="text-lg font-bold text-gray-600">TSh {{ number_format($application->total_monthly_income) }}</p>
                                </div>
                                <div>
                                    <h5 class="text-sm font-semibold text-gray-700 mb-2">Monthly Expenses</h5>
                                    <p class="text-lg font-bold text-red-600">TSh {{ number_format($application->monthly_expenses) }}</p>
                                </div>
                                <div>
                                    <h5 class="text-sm font-semibold text-gray-700 mb-2">Existing Loans</h5>
                                    <p class="text-lg font-bold text-orange-600">TSh {{ number_format($application->existing_loan_payments) }}</p>
                                </div>
                                <div>
                                    <h5 class="text-sm font-semibold text-gray-700 mb-2">Net Income</h5>
                                    @php
                                        $netIncome = $application->total_monthly_income - $application->monthly_expenses - $application->existing_loan_payments;
                                    @endphp
                                    <p class="text-lg font-bold {{ $netIncome > 0 ? 'text-gray-600' : 'text-red-600' }}">TSh {{ number_format($netIncome) }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Bank Information -->
                        @if($application->bank_name)
                            <div class="mt-6 bg-gray-50 rounded-lg p-4">
                                <h4 class="text-sm font-semibold text-gray-700 mb-2">Bank Information</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-sm text-gray-600">Bank Name</p>
                                        <p class="font-medium text-gray-900">{{ $application->bank_name }}</p>
                                    </div>
                                    @if($application->account_type)
                                        <div>
                                            <p class="text-sm text-gray-600">Account Type</p>
                                            <p class="font-medium text-gray-900">{{ ucfirst($application->account_type) }} Account</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <!-- Credit Information -->
                        @if($application->credit_score || $application->has_bad_credit_history)
                            <div class="mt-6 bg-red-50 rounded-lg p-4">
                                <h4 class="text-sm font-semibold text-gray-700 mb-2">Credit Information</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @if($application->credit_score)
                                        <div>
                                            <p class="text-sm text-gray-600">Credit Score</p>
                                            <p class="font-medium text-gray-900">{{ $application->credit_score }}</p>
                                        </div>
                                    @endif
                                    <div>
                                        <p class="text-sm text-gray-600">Credit History</p>
                                        <p class="font-medium {{ $application->has_bad_credit_history ? 'text-red-600' : 'text-gray-600' }}">
                                            {{ $application->has_bad_credit_history ? 'Has issues' : 'Good standing' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Documents Tab -->
            @if($activeTab === 'documents')
                <div class="space-y-8">
                    <div class="bg-gradient-to-br from-indigo-50 to-gray-50 rounded-2xl p-6 border border-indigo-100">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-xl font-bold text-gray-900">Uploaded Documents</h3>
                            <span class="text-sm text-gray-600">{{ $application->documents ? $application->documents->count() : 0 }} document(s)</span>
                        </div>
                        
                        @if($application->documents && $application->documents->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach($application->documents as $document)
                                    <div class="bg-white rounded-lg p-4 border border-gray-200 hover:border-gray-300 transition-colors">
                                        <div class="flex items-center space-x-3">
                                            @if(in_array(strtolower($document->file_type), ['jpg', 'jpeg', 'png']))
                                                <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                            @else
                                                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                </svg>
                                            @endif
                                            <div class="flex-1">
                                                <p class="font-medium text-gray-900">{{ ucwords(str_replace('_', ' ', $document->document_type)) }}</p>
                                                <p class="text-sm text-gray-500">{{ number_format($document->file_size / 1024, 1) }} KB</p>
                                                <p class="text-xs text-gray-400">{{ $document->created_at->format('M d, Y') }}</p>
                                            </div>
                                        </div>
                                        
                                        <div class="mt-3 flex items-center justify-between">
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                                @switch($document->status)
                                                    @case('uploaded') bg-gray-100 text-gray-800 @break
                                                    @case('verified') bg-gray-100 text-gray-800 @break
                                                    @case('rejected') bg-red-100 text-red-800 @break
                                                    @default bg-gray-100 text-gray-800
                                                @endswitch">
                                                {{ ucfirst($document->status) }}
                                            </span>
                                            
                                            <div class="flex items-center space-x-2">
                                                <button wire:click="viewDocument({{ $document->id }})" 
                                                        class="text-gray-600 hover:text-gray-800 p-1 rounded hover:bg-gray-50 transition-colors"
                                                        title="View Document">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                    </svg>
                                                </button>
                                                <button wire:click="downloadDocument({{ $document->id }})" 
                                                        class="text-gray-600 hover:text-gray-800 p-1 rounded hover:bg-gray-50 transition-colors"
                                                        title="Download Document">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <h4 class="text-lg font-semibold text-gray-900 mb-2">No documents uploaded</h4>
                                <p class="text-gray-600">Documents will appear here once uploaded during the application process.</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Timeline Tab -->
            @if($activeTab === 'timeline')
                <div class="space-y-8">
                    <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl p-6 border border-gray-200">
                        <h3 class="text-xl font-bold text-gray-900 mb-6">Application Timeline</h3>
                        
                        <div class="space-y-6">
                            <!-- Application Created -->
                            <div class="flex items-start space-x-4">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center">
                                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between">
                                        <p class="text-sm font-medium text-gray-900">Application Created</p>
                                        <p class="text-sm text-gray-500">{{ $application->created_at->format('M d, Y H:i') }}</p>
                                    </div>
                                    <p class="text-sm text-gray-600">Application {{ $application->application_number }} was created and saved as draft.</p>
                                    <p class="text-xs text-gray-500 mt-1">{{ $application->created_at->diffForHumans() }}</p>
                                </div>
                            </div>

                            <!-- Application Submitted -->
                            @if($application->submitted_at)
                                <div class="flex items-start space-x-4">
                                    <div class="flex-shrink-0">
                                        <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center">
                                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between">
                                            <p class="text-sm font-medium text-gray-900">Application Submitted</p>
                                            <p class="text-sm text-gray-500">{{ $application->submitted_at->format('M d, Y H:i') }}</p>
                                        </div>
                                        <p class="text-sm text-gray-600">Application was completed and submitted for processing.</p>
                                        <p class="text-xs text-gray-500 mt-1">{{ $application->submitted_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                            @endif

                            <!-- Under Review -->
                            @if($application->status === 'under_review' || in_array($application->status, ['approved', 'rejected', 'disbursed']))
                                <div class="flex items-start space-x-4">
                                    <div class="flex-shrink-0">
                                        <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center">
                                            <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between">
                                            <p class="text-sm font-medium text-gray-900">Review Started</p>
                                            <p class="text-sm text-gray-500">
                                                {{ $application->reviewed_at ? $application->reviewed_at->format('M d, Y H:i') : 'In progress' }}
                                            </p>
                                        </div>
                                        <p class="text-sm text-gray-600">
                                            Application is being reviewed by {{ $application->lender->company_name ?? 'the lender' }}.
                                        </p>
                                        @if($application->reviewed_at)
                                            <p class="text-xs text-gray-500 mt-1">{{ $application->reviewed_at->diffForHumans() }}</p>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            <!-- Approved -->
                            @if($application->status === 'approved' || $application->status === 'disbursed')
                                <div class="flex items-start space-x-4">
                                    <div class="flex-shrink-0">
                                        <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center">
                                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between">
                                            <p class="text-sm font-medium text-gray-900">Application Approved</p>
                                            <p class="text-sm text-gray-500">
                                                {{ $application->approved_at ? $application->approved_at->format('M d, Y H:i') : 'Recently' }}
                                            </p>
                                        </div>
                                        <p class="text-sm text-gray-600">
                                            Congratulations! Your loan application has been approved by {{ $application->lender->company_name ?? 'the lender' }}.
                                        </p>
                                        @if($application->approved_at)
                                            <p class="text-xs text-gray-500 mt-1">{{ $application->approved_at->diffForHumans() }}</p>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            <!-- Rejected -->
                            @if($application->status === 'rejected')
                                <div class="flex items-start space-x-4">
                                    <div class="flex-shrink-0">
                                        <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between">
                                            <p class="text-sm font-medium text-gray-900">Application Rejected</p>
                                            <p class="text-sm text-gray-500">
                                                {{ $application->rejected_at ? $application->rejected_at->format('M d, Y H:i') : 'Recently' }}
                                            </p>
                                        </div>
                                        <p class="text-sm text-gray-600">
                                            Unfortunately, your application was not approved by {{ $application->lender->company_name ?? 'the lender' }}.
                                        </p>
                                        @if($application->rejection_reasons)
                                            <div class="mt-2 p-2 bg-red-50 rounded-lg">
                                                <p class="text-xs text-red-700 font-medium">Rejection Reasons:</p>
                                                @php
                                                    $reasons = is_string($application->rejection_reasons) 
                                                        ? json_decode($application->rejection_reasons, true) 
                                                        : $application->rejection_reasons;
                                                @endphp
                                                @if(is_array($reasons))
                                                    <ul class="text-xs text-red-600 mt-1 list-disc list-inside">
                                                        @foreach($reasons as $reason)
                                                            <li>{{ $reason }}</li>
                                                        @endforeach
                                                    </ul>
                                                @else
                                                    <p class="text-xs text-red-600 mt-1">{{ $application->rejection_reasons }}</p>
                                                @endif
                                            </div>
                                        @endif
                                        @if($application->rejected_at)
                                            <p class="text-xs text-gray-500 mt-1">{{ $application->rejected_at->diffForHumans() }}</p>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            <!-- Disbursed -->
                            @if($application->status === 'disbursed')
                                <div class="flex items-start space-x-4">
                                    <div class="flex-shrink-0">
                                        <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
                                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between">
                                            <p class="text-sm font-medium text-gray-900">Loan Disbursed</p>
                                            <p class="text-sm text-gray-500">
                                                {{ $application->disbursed_at ? $application->disbursed_at->format('M d, Y H:i') : 'Recently' }}
                                            </p>
                                        </div>
                                        <p class="text-sm text-gray-600">
                                            Your loan has been successfully disbursed. Funds should be available in your account.
                                        </p>
                                        <div class="mt-2 p-2 bg-purple-50 rounded-lg">
                                            <p class="text-xs text-purple-700">
                                                <strong>Amount:</strong> TSh {{ number_format($application->requested_amount) }}
                                            </p>
                                            @if($application->preferred_disbursement_method)
                                                <p class="text-xs text-purple-700">
                                                    <strong>Method:</strong> {{ ucwords(str_replace('_', ' ', $application->preferred_disbursement_method)) }}
                                                </p>
                                            @endif
                                        </div>
                                        @if($application->disbursed_at)
                                            <p class="text-xs text-gray-500 mt-1">{{ $application->disbursed_at->diffForHumans() }}</p>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            <!-- Cancelled -->
                            @if($application->status === 'cancelled')
                                <div class="flex items-start space-x-4">
                                    <div class="flex-shrink-0">
                                        <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center">
                                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between">
                                            <p class="text-sm font-medium text-gray-900">Application Cancelled</p>
                                            <p class="text-sm text-gray-500">
                                                {{ $application->cancelled_at ? $application->cancelled_at->format('M d, Y H:i') : 'Recently' }}
                                            </p>
                                        </div>
                                        <p class="text-sm text-gray-600">
                                            This application has been cancelled.
                                        </p>
                                        @if($application->cancellation_reason)
                                            <p class="text-xs text-gray-500 mt-1">
                                                <strong>Reason:</strong> {{ $application->cancellation_reason }}
                                            </p>
                                        @endif
                                        @if($application->cancelled_at)
                                            <p class="text-xs text-gray-500 mt-1">{{ $application->cancelled_at->diffForHumans() }}</p>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            <!-- Pending Status Indicators -->
                            @if($application->status === 'draft')
                                <div class="flex items-start space-x-4 opacity-50">
                                    <div class="flex-shrink-0">
                                        <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center">
                                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-500">Pending Submission</p>
                                        <p class="text-sm text-gray-400">Complete and submit your application to proceed.</p>
                                    </div>
                                </div>
                            @elseif($application->status === 'submitted')
                                <div class="flex items-start space-x-4 opacity-50">
                                    <div class="flex-shrink-0">
                                        <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center">
                                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-500">Awaiting Review</p>
                                        <p class="text-sm text-gray-400">Your application is waiting to be reviewed by the lender.</p>
                                    </div>
                                </div>
                            @elseif($application->status === 'under_review')
                                <div class="flex items-start space-x-4 opacity-50">
                                    <div class="flex-shrink-0">
                                        <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center">
                                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-500">Awaiting Decision</p>
                                        <p class="text-sm text-gray-400">The lender will make a decision on your application soon.</p>
                                    </div>
                                </div>
                            @elseif($application->status === 'approved')
                                <div class="flex items-start space-x-4 opacity-50">
                                    <div class="flex-shrink-0">
                                        <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center">
                                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-500">Awaiting Disbursement</p>
                                        <p class="text-sm text-gray-400">Your loan will be disbursed shortly.</p>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Additional Notes -->
                        @if($application->notes)
                            <div class="mt-8 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                                <h4 class="text-sm font-semibold text-yellow-800 mb-2">Additional Notes</h4>
                                <p class="text-sm text-yellow-700">{{ $application->notes }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Modals -->
    
    <!-- Cancel Confirmation Modal -->
    @if($showCancelModal)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" wire:click="closeCancelModal">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-lg bg-white" wire:click.stop>
                <div class="mt-3 text-center">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                        <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mt-2">Cancel Application</h3>
                    <div class="mt-2 px-7 py-3">
                        <p class="text-sm text-gray-500">
                            Are you sure you want to cancel this application? This action cannot be undone.
                        </p>
                    </div>
                    <div class="flex items-center justify-center gap-4 mt-4">
                        <button wire:click="closeCancelModal" 
                                class="px-4 py-2 bg-gray-300 text-gray-800 text-sm font-medium rounded-md hover:bg-gray-400 transition-colors">
                            No, Keep It
                        </button>
                        <button wire:click="confirmCancel" 
                                class="px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700 transition-colors">
                            Yes, Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Document Modal -->
    @if($showDocumentModal && $selectedDocument)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" wire:click="closeDocumentModal">
            <div class="relative top-10 mx-auto p-5 border max-w-4xl shadow-lg rounded-lg bg-white" wire:click.stop>
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold text-gray-900">
                        {{ ucwords(str_replace('_', ' ', $selectedDocument->document_type)) }}
                    </h3>
                    <button wire:click="closeDocumentModal" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                
                <div class="text-center">
                    @if(in_array(strtolower($selectedDocument->file_type), ['jpg', 'jpeg', 'png']))
                        <img src="{{ Storage::url($selectedDocument->file_path) }}" 
                             alt="{{ $selectedDocument->document_name }}" 
                             class="max-w-full max-h-96 mx-auto rounded-lg">
                    @else
                        <div class="p-8">
                            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <p class="text-gray-600">PDF file cannot be previewed here</p>
                            <a href="{{ Storage::url($selectedDocument->file_path) }}" 
                               target="_blank" 
                               class="mt-4 inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                Open in New Tab
                            </a>
                        </div>
                    @endif
                </div>

                <div class="mt-4 flex justify-center">
                    <button wire:click="downloadDocument({{ $selectedDocument->id }})" 
                            class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Download
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Toast Notification -->
    @if($showToast)
        <div class="fixed top-4 right-4 z-50" x-data="{ show: @entangle('showToast') }" x-show="show" x-transition>
            <div class="bg-{{ $toastType === 'success' ? 'gray' : 'red' }}-50 border border-{{ $toastType === 'success' ? 'gray' : 'red' }}-200 text-{{ $toastType === 'success' ? 'gray' : 'red' }}-700 px-4 py-3 rounded-lg shadow-lg">
                <div class="flex items-center">
                    @if($toastType === 'success')
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    @else
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    @endif
                    <span>{{ $toastMessage }}</span>
                    <button wire:click="hideToast" class="ml-4 text-{{ $toastType === 'success' ? 'gray' : 'red' }}-600 hover:text-{{ $toastType === 'success' ? 'gray' : 'red' }}-800">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>

<!-- JavaScript for copy to clipboard functionality -->
<script>
    document.addEventListener('livewire:initialized', () => {
        Livewire.on('copy-to-clipboard', (event) => {
            navigator.clipboard.writeText(event.text).then(() => {
                console.log('Copied to clipboard');
            }).catch(err => {
                console.error('Failed to copy: ', err);
            });
        });

        Livewire.on('hide-toast-after-delay', () => {
            setTimeout(() => {
                Livewire.dispatch('hideToast');
            }, 5000);
        });
    });
</script>


</div>