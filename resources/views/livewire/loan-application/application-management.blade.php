<div>
<div class="w-full">
    {{-- LIST VIEW --}}
      @if($currentStep === 'list')
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-4xl font-bold text-gray-900 mb-2">My Loan Applications</h1>
                    <p class="text-gray-600 text-lg">Track and manage your loan applications</p>
                </div>
                <div class="flex items-center space-x-3">
                    <button wire:click="startNewApplication" class="bg-gradient-to-r from-brand-red to-brand-dark-red text-white px-8 py-3 rounded-lg font-bold hover:from-brand-dark-red hover:to-red-700 transition-all duration-200 shadow-lg shadow-brand-red/25 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        New Application
                    </button>
                </div>
            </div>
        </div>

        <!-- Flash Messages -->
        @if (session()->has('message'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg" role="alert">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    {{ session('message') }}
                </div>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg" role="alert">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    {{ session('error') }}
                </div>
            </div>
        @endif

        <!-- Search and Filters -->
        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100 mb-6">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between space-y-4 lg:space-y-0">
                <!-- Search -->
                <div class="flex-1 max-w-md">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <input wire:model.live.live="search" type="text" class="block w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-brand-red focus:border-brand-red placeholder-gray-500 text-gray-900 text-sm transition-all duration-200" placeholder="Search applications...">
                    </div>
                </div>

                <!-- Filters -->
                <div class="flex items-center space-x-4">
                    <select wire:model.live.live="statusFilter" class="bg-gray-50 border border-gray-200 rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-brand-red focus:border-brand-red">
                        <option value="all">All Status</option>
                        <option value="draft">Draft</option>
                        <option value="submitted">Submitted</option>
                        <option value="under_review">Under Review</option>
                        <option value="approved">Approved</option>
                        <option value="rejected">Rejected</option>
                        <option value="disbursed">Disbursed</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Applications List -->
        <div class="space-y-6">
            @forelse($applications as $application)
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg transition-all duration-300">
                    <!-- Application Header -->
                    <div class="p-6 border-b border-gray-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="flex items-center space-x-3 mb-2">
                                    <h3 class="text-xl font-bold text-gray-900">{{ $application->application_number }}</h3>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold 
                                        @switch($application->status)
                                            @case('draft') bg-gray-100 text-gray-800 @break
                                            @case('submitted') bg-blue-100 text-blue-800 @break
                                            @case('under_review') bg-yellow-100 text-yellow-800 @break
                                            @case('approved') bg-green-100 text-green-800 @break
                                            @case('rejected') bg-red-100 text-red-800 @break
                                            @case('disbursed') bg-purple-100 text-purple-800 @break
                                            @case('cancelled') bg-gray-100 text-gray-800 @break
                                            @default bg-gray-100 text-gray-800
                                        @endswitch">
                                        {{ ucwords(str_replace('_', ' ', $application->status)) }}
                                    </span>
                                </div>
                                <p class="text-gray-600">{{ $application->first_name }} {{ $application->last_name }}</p>
                                <p class="text-sm text-gray-500">Applied {{ $application->created_at->diffForHumans() }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-2xl font-bold text-brand-red">TSh {{ number_format($application->requested_amount) }}</p>
                                <p class="text-sm text-gray-600">{{ $application->requested_tenure_months }} months</p>
                                @if($application->loanProduct)
                                    <p class="text-sm text-blue-600 font-medium">{{ $application->loanProduct->name }}</p>
                                @endif
                                @if($application->lender)
                                    <p class="text-xs text-gray-500">{{ $application->lender->company_name }}</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Application Details -->
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <h4 class="text-sm font-semibold text-gray-700 mb-2">Personal Information</h4>
                                <p class="text-sm text-gray-600">{{ ucwords(str_replace('_', ' ', $application->employment_status)) }}</p>
                                <p class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($application->date_of_birth)->age }} years old</p>
                                <p class="text-sm text-gray-600">{{ $application->current_city }}, {{ $application->current_region }}</p>
                            </div>
                            <div>
                                <h4 class="text-sm font-semibold text-gray-700 mb-2">Financial Information</h4>
                                <p class="text-sm text-gray-600">Monthly Income: TSh {{ number_format($application->total_monthly_income) }}</p>
                                <p class="text-sm text-gray-600">Monthly Expenses: TSh {{ number_format($application->monthly_expenses) }}</p>
                                @if($application->debt_to_income_ratio)
                                    <p class="text-sm text-gray-600">DSR: {{ number_format($application->debt_to_income_ratio, 1) }}%</p>
                                @endif
                                @if($application->existing_loan_payments > 0)
                                    <p class="text-sm text-gray-600">Existing Loans: TSh {{ number_format($application->existing_loan_payments) }}</p>
                                @endif
                            </div>
                            <div>
                                <h4 class="text-sm font-semibold text-gray-700 mb-2">Application Progress</h4>
                                @php
                                    $completionPercentage = 0;
                                    if ($application->status === 'draft') $completionPercentage = 25;
                                    elseif ($application->status === 'submitted') $completionPercentage = 50;
                                    elseif ($application->status === 'under_review') $completionPercentage = 75;
                                    elseif (in_array($application->status, ['approved', 'disbursed'])) $completionPercentage = 100;
                                    elseif (in_array($application->status, ['rejected', 'cancelled'])) $completionPercentage = 100;
                                @endphp
                                <div class="flex items-center space-x-2 mb-2">
                                    <div class="flex-1 bg-gray-200 rounded-full h-2">
                                        <div class="bg-brand-red h-2 rounded-full transition-all duration-300" style="width: {{ $completionPercentage }}%"></div>
                                    </div>
                                    <span class="text-sm text-gray-600">{{ $completionPercentage }}%</span>
                                </div>
                                @if($application->submitted_at)
                                    <p class="text-sm text-gray-600">Submitted: {{ $application->submitted_at->format('M d, Y') }}</p>
                                @endif
                                @if($application->loan_purpose)
                                    <p class="text-sm text-gray-600">Purpose: {{ ucwords(str_replace('_', ' ', $application->loan_purpose)) }}</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-2">
                                <button wire:click="viewApplication({{ $application->id }})" class="text-blue-600 hover:text-blue-800 p-2 rounded-lg hover:bg-blue-50 transition-all duration-200" title="View Details">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </button>

                                @if(in_array($application->status, ['draft']))
                                    <button wire:click="editApplication({{ $application->id }})" class="text-gray-600 hover:text-gray-800 p-2 rounded-lg hover:bg-gray-100 transition-all duration-200" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                @endif

                                @if($application->status === 'submitted' && !$application->lender_id)
                                    <button wire:click="viewMatchingProducts({{ $application->id }})" class="text-green-600 hover:text-green-800 p-2 rounded-lg hover:bg-green-50 transition-all duration-200" title="View Matching Products">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </button>
                                @endif

                                @if(in_array($application->status, ['draft', 'submitted']))
                                    <button wire:click="cancelApplication({{ $application->id }})" 
                                            wire:confirm="Are you sure you want to cancel this application?"
                                            class="text-red-600 hover:text-red-800 p-2 rounded-lg hover:bg-red-50 transition-all duration-200" title="Cancel">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                @endif
                            </div>

                            <div class="flex items-center space-x-2">
                                @if($application->status === 'submitted' && !$application->lender_id)
                                    <span class="text-sm text-blue-600 font-medium">Ready for lender selection</span>
                                    <button wire:click="viewMatchingProducts({{ $application->id }})" class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm font-medium hover:bg-blue-200 transition-colors">
                                        Choose Lender
                                    </button>
                                @elseif($application->status === 'under_review')
                                    <span class="text-sm text-yellow-600 font-medium">Under review by {{ $application->lender->company_name ?? 'lender' }}</span>
                                @elseif($application->status === 'approved')
                                    <span class="text-sm text-green-600 font-medium">Approved - Awaiting disbursement</span>
                                @elseif($application->status === 'disbursed')
                                    <span class="text-sm text-purple-600 font-medium">Loan disbursed successfully</span>
                                @elseif($application->status === 'rejected')
                                    <span class="text-sm text-red-600 font-medium">Application rejected</span>
                                @elseif($application->status === 'draft')
                                    <span class="text-sm text-gray-600 font-medium">Draft - Continue editing</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-lg shadow-sm p-12 text-center border border-gray-100">
                    <div class="w-20 h-20 bg-gradient-to-br from-brand-red to-brand-dark-red rounded-lg flex items-center justify-center mx-auto mb-6 shadow-lg">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">No applications found</h3>
                    <p class="text-gray-500 mb-6">Start your loan application journey today. Check your eligibility first!</p>
                    <button wire:click="startNewApplication" class="bg-gradient-to-r from-brand-red to-brand-dark-red text-white px-8 py-3 rounded-lg font-bold hover:from-brand-dark-red hover:to-red-700 transition-all duration-200 shadow-lg shadow-brand-red/25">
                        Start Application
                    </button>
                </div>
            @endforelse
        </div>



        @elseif($currentStep === 'prequalify')
        <!-- Pre-qualification Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-4xl font-bold text-gray-900 mb-2">Loan Pre-Qualification</h1>
                    <p class="text-gray-600 text-lg">Check your eligibility and find matching lenders before applying</p>
                </div>
                <div class="flex items-center space-x-3">
                    <button wire:click="backToList" class="text-gray-600 hover:text-gray-800 p-2 rounded-lg hover:bg-gray-100 transition-all duration-200" title="Back to List">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Info Banner -->
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-2xl p-6 mb-6">
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-blue-900 mb-2">How Pre-Qualification Works</h3>
                        <p class="text-blue-800 text-sm leading-relaxed">
                            Enter your basic financial information to calculate your Debt Service Ratio (DSR) and see which lenders you qualify for. 
                            This won't affect your credit score and helps you make informed decisions.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pre-qualification Form -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Left Column - Input Form -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-brand-red to-brand-dark-red text-white">
                    <h3 class="text-xl font-bold mb-2">Your Financial Information</h3>
                    <p class="text-red-100 text-sm">Fill in your details to check eligibility</p>
                </div>
                
                <div class="p-6 space-y-6">
                    <!-- Loan Amount -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            Requested Loan Amount (TSh) *
                        </label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500 font-medium">TSh</span>
                            <input wire:model.live.live="prequalify_amount" 
                                   type="number" 
                                   step="1000" 
                                   min="1000" 
                                   class="w-full pl-12 pr-4 py-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-red focus:border-brand-red text-lg font-bold bg-gray-50"
                                   placeholder="50,000">
                        </div>
                        @error('prequalify_amount') 
                            <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> 
                        @enderror
                    </div>

                    <!-- Loan Period -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            Loan Period (Months) *
                        </label>
                        <select wire:model.live.live="prequalify_tenure" 
                                class="w-full px-4 py-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-red focus:border-brand-red text-lg font-bold bg-gray-50">
                            <option value="">Select period</option>
                            <option value="6">6 months</option>
                            <option value="12">12 months</option>
                            <option value="18">18 months</option>
                            <option value="24">24 months</option>
                            <option value="36">36 months</option>
                            <option value="48">48 months</option>
                            <option value="60">60 months</option>
                            <option value="72">72 months</option>
                            <option value="84">84 months</option>
                            <option value="96">96 months</option>
                            <option value="120">120 months</option>
                        </select>
                        @error('prequalify_tenure') 
                            <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> 
                        @enderror
                    </div>

                    <!-- Monthly Income -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            Your Monthly Income (TSh) *
                        </label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500 font-medium">TSh</span>
                            <input wire:model.live.live="prequalify_monthly_income" 
                                   type="number" 
                                   step="1000" 
                                   min="0" 
                                   class="w-full pl-12 pr-4 py-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-red focus:border-brand-red text-lg font-bold bg-gray-50"
                                   placeholder="500,000">
                        </div>
                        @error('prequalify_monthly_income') 
                            <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> 
                        @enderror
                        <p class="text-sm text-gray-500 mt-1">Include salary, business income, and other regular income</p>
                    </div>

                    <!-- Existing Loans -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            Existing Monthly Loan Payments (TSh) *
                        </label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500 font-medium">TSh</span>
                            <input wire:model.live.live="prequalify_existing_loans" 
                                   type="number" 
                                   step="1000" 
                                   min="0" 
                                   class="w-full pl-12 pr-4 py-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-red focus:border-brand-red text-lg font-bold bg-gray-50"
                                   placeholder="0">
                        </div>
                        @error('prequalify_existing_loans') 
                            <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> 
                        @enderror
                        <p class="text-sm text-gray-500 mt-1">Total monthly payments for all existing loans</p>
                    </div>

                    <!-- Calculate Button -->
                    <div class="pt-4">
                        <button wire:click="calculateDSR" 
                                class="w-full bg-gradient-to-r from-brand-red to-brand-dark-red text-white py-4 px-6 rounded-lg font-bold hover:from-brand-dark-red hover:to-red-700 transition-all duration-200 shadow-lg shadow-brand-red/25 flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                            Calculate Eligibility
                        </button>
                    </div>
                </div>
            </div>

            <!-- Right Column - Results -->
            <div class="space-y-6">
                <!-- DSR Calculator Result -->
                @if($calculated_dsr > 0)
                    <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
                        <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-purple-50 to-pink-50">
                            <h3 class="text-xl font-bold text-gray-900 mb-2">Your Debt Service Ratio (DSR)</h3>
                            <p class="text-gray-600 text-sm">This measures your ability to service the loan</p>
                        </div>
                        
                        <div class="p-6">
                            <div class="text-center mb-6">
                                <div class="w-24 h-24 mx-auto mb-4 relative">
                                    <svg class="w-24 h-24 transform -rotate-90" viewBox="0 0 100 100">
                                        <circle cx="50" cy="50" r="40" stroke="#e5e7eb" stroke-width="8" fill="none"/>
                                        <circle cx="50" cy="50" r="40" stroke="{{ $calculated_dsr <= 30 ? '#10b981' : ($calculated_dsr <= 40 ? '#f59e0b' : '#ef4444') }}" 
                                                stroke-width="8" fill="none" stroke-linecap="round"
                                                stroke-dasharray="{{ 2 * pi() * 40 }}" 
                                                stroke-dashoffset="{{ 2 * pi() * 40 * (1 - min($calculated_dsr, 100) / 100) }}"/>
                                    </svg>
                                    <div class="absolute inset-0 flex items-center justify-center">
                                        <span class="text-2xl font-bold {{ $calculated_dsr <= 30 ? 'text-green-600' : ($calculated_dsr <= 40 ? 'text-yellow-600' : 'text-red-600') }}">
                                            {{ number_format($calculated_dsr, 1) }}%
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="space-y-2">
                                    @if($calculated_dsr <= 30)
                                        <p class="text-green-700 font-bold">Excellent DSR!</p>
                                        <p class="text-sm text-green-600">You have great loan affordability</p>
                                    @elseif($calculated_dsr <= 40)
                                        <p class="text-yellow-700 font-bold">Good DSR</p>
                                        <p class="text-sm text-yellow-600">You qualify for most loan products</p>
                                    @elseif($calculated_dsr <= 50)
                                        <p class="text-orange-700 font-bold">Fair DSR</p>
                                        <p class="text-sm text-orange-600">Limited loan options available</p>
                                    @else
                                        <p class="text-red-700 font-bold">High DSR</p>
                                        <p class="text-sm text-red-600">Consider reducing loan amount</p>
                                    @endif
                                </div>
                            </div>

                            <!-- DSR Breakdown -->
                            <div class="bg-gray-50 rounded-lg p-4 space-y-3">
                                <h4 class="font-bold text-gray-700 text-sm">Calculation Breakdown:</h4>
                                @php
                                    $estimatedPayment = $prequalify_amount > 0 && $prequalify_tenure > 0 ? 
                                        $prequalify_amount * (0.15/12 * pow(1 + 0.15/12, $prequalify_tenure)) / (pow(1 + 0.15/12, $prequalify_tenure) - 1) : 0;
                                    $totalDebt = $prequalify_existing_loans + $estimatedPayment;
                                @endphp
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Monthly Income:</span>
                                    <span class="font-medium">TSh {{ number_format($prequalify_monthly_income) }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Existing Loans:</span>
                                    <span class="font-medium">TSh {{ number_format($prequalify_existing_loans) }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Estimated New Payment:</span>
                                    <span class="font-medium">TSh {{ number_format($estimatedPayment) }}</span>
                                </div>
                                <hr class="border-gray-200">
                                <div class="flex justify-between text-sm font-bold">
                                    <span class="text-gray-700">Total Monthly Debt:</span>
                                    <span class="text-brand-red">TSh {{ number_format($totalDebt) }}</span>
                                </div>
                                <div class="flex justify-between text-sm font-bold">
                                    <span class="text-gray-700">DSR Calculation:</span>
                                    <span class="text-brand-red">{{ number_format($totalDebt) }} ÷ {{ number_format($prequalify_monthly_income) }} × 100</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Eligibility Message -->
                @if($dsr_message)
                    <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
                        <div class="p-6">
                            <div class="flex items-start space-x-4">
                                <div class="flex-shrink-0">
                                    @if($can_proceed)
                                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    @else
                                        <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    @endif
                                </div>
                                <div>
                                    <p class="text-sm {{ $can_proceed ? 'text-green-700' : 'text-red-700' }} font-medium">
                                        {{ $dsr_message }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- DSR Guidelines -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-blue-50 to-indigo-50">
                        <h3 class="text-lg font-bold text-gray-900">DSR Guidelines</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-4 h-4 bg-green-500 rounded-full"></div>
                            <span class="text-sm text-gray-700"><strong>0-30%:</strong> Excellent - Qualify for all loan products</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="w-4 h-4 bg-yellow-500 rounded-full"></div>
                            <span class="text-sm text-gray-700"><strong>31-40%:</strong> Good - Qualify for most loan products</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="w-4 h-4 bg-orange-500 rounded-full"></div>
                            <span class="text-sm text-gray-700"><strong>41-50%:</strong> Fair - Limited loan options</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="w-4 h-4 bg-red-500 rounded-full"></div>
                            <span class="text-sm text-gray-700"><strong>50%+:</strong> High - Consider reducing loan amount</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Matching Lenders Section -->
        @if(!empty($preQualificationResults))
            <div class="mt-8">
                <div class="mb-6">
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Matching Lenders</h2>
                    <p class="text-gray-600">Based on your financial profile, here are the lenders you qualify for:</p>
                </div>

                <!-- Lender Selection Controls -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">Select Lenders</h3>
                            <p class="text-sm text-gray-600">Choose which lenders to apply to</p>
                        </div>
                        <div class="flex items-center space-x-3">
                            <button wire:click="selectAllEligibleLenders" 
                                    class="bg-blue-100 text-blue-700 px-4 py-2 rounded-lg font-medium hover:bg-blue-200 transition-colors text-sm">
                                Select All Eligible
                            </button>
                            <button wire:click="clearSelectedLenders" 
                                    class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg font-medium hover:bg-gray-200 transition-colors text-sm">
                                Clear Selection
                            </button>
                        </div>
                    </div>
                    
                    @if(!empty($selectedLenders))
                        <div class="mt-4 p-4 bg-green-50 rounded-lg">
                            <p class="text-sm text-green-700 font-medium">
                                {{ count($selectedLenders) }} lender(s) selected for application
                            </p>
                        </div>
                    @endif
                </div>

                <!-- Lenders Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($preQualificationResults as $index => $result)
                        @php
                            $isSelected = in_array($result['lender_id'], $selectedLenders);
                            $isEligible = $result['eligible'];
                        @endphp
                        
                        <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg transition-all duration-300 
                                    {{ $isSelected ? 'ring-2 ring-brand-red' : '' }}
                                    {{ !$isEligible ? 'opacity-75' : '' }}">
                            
                            <!-- Lender Header -->
                            <div class="p-6 {{ $index === 0 && $isEligible ? 'bg-gradient-to-r from-brand-red to-brand-dark-red text-white' : 'bg-gradient-to-r from-gray-50 to-gray-100' }}">
                                <div class="flex items-center justify-between mb-3">
                                    <div>
                                        <h3 class="text-lg font-bold {{ $index === 0 && $isEligible ? 'text-white' : 'text-gray-900' }}">
                                            {{ $result['lender_name'] }}
                                        </h3>
                                        <p class="text-sm {{ $index === 0 && $isEligible ? 'text-red-100' : 'text-gray-600' }}">
                                            {{ $result['product_name'] }}
                                        </p>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        @if($index === 0 && $isEligible)
                                            <span class="bg-white text-brand-red px-3 py-1 rounded-full text-xs font-bold">BEST MATCH</span>
                                        @endif
                                        @if(!$isEligible)
                                            <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-bold">NOT ELIGIBLE</span>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="grid grid-cols-3 gap-4">
                                    <div>
                                        <p class="text-xs {{ $index === 0 && $isEligible ? 'text-red-200' : 'text-gray-500' }}">Interest Rate</p>
                                        <p class="text-lg font-bold {{ $index === 0 && $isEligible ? 'text-white' : 'text-gray-900' }}">
                                            {{ $result['interest_rate_min'] }}%
                                            @if($result['interest_rate_min'] != $result['interest_rate_max'])
                                                - {{ $result['interest_rate_max'] }}%
                                            @endif
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-xs {{ $index === 0 && $isEligible ? 'text-red-200' : 'text-gray-500' }}">Your DSR</p>
                                        <p class="text-lg font-bold {{ $index === 0 && $isEligible ? 'text-white' : 'text-gray-900' }}">
                                            {{ number_format($result['actual_dsr'], 1) }}%
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-xs {{ $index === 0 && $isEligible ? 'text-red-200' : 'text-gray-500' }}">Monthly Payment</p>
                                        <p class="text-lg font-bold {{ $index === 0 && $isEligible ? 'text-white' : 'text-gray-900' }}">
                                            TSh {{ number_format($result['monthly_payment']) }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Lender Details -->
                            <div class="p-6">
                                <div class="space-y-4">
                                    <!-- Key Information -->
                                    <div class="grid grid-cols-2 gap-4 text-sm">
                                        <div>
                                            <span class="text-gray-500">Processing Fee:</span>
                                            <p class="font-medium text-gray-900">
                                                @if($result['processing_fee_percentage'] > 0)
                                                    {{ $result['processing_fee_percentage'] }}%
                                                @endif
                                                @if($result['processing_fee_fixed'] > 0)
                                                    {{ $result['processing_fee_percentage'] > 0 ? ' + ' : '' }}TSh {{ number_format($result['processing_fee_fixed']) }}
                                                @endif
                                                @if($result['processing_fee_percentage'] == 0 && $result['processing_fee_fixed'] == 0)
                                                    No fees
                                                @endif
                                            </p>
                                        </div>
                                        <div>
                                            <span class="text-gray-500">Max DSR Allowed:</span>
                                            <p class="font-medium text-gray-900">{{ $result['max_dsr_allowed'] }}%</p>
                                        </div>
                                        <div>
                                            <span class="text-gray-500">Approval Time:</span>
                                            <p class="font-medium text-gray-900">{{ $result['approval_time_days'] }} days</p>
                                        </div>
                                        <div>
                                            <span class="text-gray-500">Disbursement:</span>
                                            <p class="font-medium text-gray-900">{{ $result['disbursement_time_days'] }} days</p>
                                        </div>
                                    </div>

                                    <!-- Eligibility Status -->
                                    @if($isEligible)
                                        <div class="bg-green-50 rounded-lg p-4">
                                            <div class="flex items-center space-x-2">
                                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                </svg>
                                                <span class="text-green-700 font-medium text-sm">You qualify for this loan product</span>
                                            </div>
                                        </div>
                                    @else
                                        <div class="bg-red-50 rounded-lg p-4">
                                            <div class="flex items-center space-x-2">
                                                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                                <span class="text-red-700 font-medium text-sm">
                                                    DSR too high ({{ number_format($result['actual_dsr'], 1) }}% > {{ $result['max_dsr_allowed'] }}%)
                                                </span>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Selection Checkbox -->
                                    @if($isEligible)
                                        <div class="pt-2">
                                            <label class="flex items-center cursor-pointer">
                                                <input type="checkbox" 
                                                       wire:click="selectLender({{ $result['lender_id'] }})"
                                                       {{ $isSelected ? 'checked' : '' }}
                                                       class="text-brand-red focus:ring-brand-red rounded">
                                                <span class="ml-3 text-sm font-medium text-gray-700">
                                                    Select this lender for application
                                                </span>
                                            </label>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Proceed to Application Button -->
                @if($can_proceed && !empty($selectedLenders))
                    <div class="mt-8 text-center">
                        <button wire:click="proceedToApplication" 
                                class="bg-gradient-to-r from-brand-red to-brand-dark-red text-white px-12 py-4 rounded-lg font-bold hover:from-brand-dark-red hover:to-red-700 transition-all duration-200 shadow-lg shadow-brand-red/25 flex items-center mx-auto">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                            </svg>
                            Proceed to Full Application
                            <span class="ml-2 bg-white bg-opacity-20 px-2 py-1 rounded-full text-sm">
                                {{ count($selectedLenders) }} lender{{ count($selectedLenders) > 1 ? 's' : '' }}
                            </span>
                        </button>
                        <p class="text-sm text-gray-500 mt-2">
                            You'll complete one application that will be sent to your selected lender(s)
                        </p>
                    </div>
                @endif
            </div>
        @endif


            {{-- CREATE/EDIT APPLICATION FORM --}}
       @elseif(in_array($currentStep, ['create', 'edit']))
        <!-- Form Header with Progress -->
        <div class="mb-8">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-4xl font-bold text-gray-900 mb-2">
                        {{ $currentStep === 'create' ? 'New' : 'Edit' }} Loan Application
                    </h1>
                    <p class="text-gray-600 text-lg">Step {{ $currentFormStep }} of 6 - Complete all steps to submit your application</p>
                    @if($currentStep === 'create' && !empty($selectedLenders))
                        <div class="mt-2">
                            <p class="text-sm text-brand-red font-medium">
                                Will be sent to {{ count($selectedLenders) }} selected lender{{ count($selectedLenders) > 1 ? 's' : '' }}
                            </p>
                        </div>
                    @endif
                </div>
                <div class="flex items-center space-x-3">
                    @if($currentStep === 'create')
                        <button wire:click="backToPreQualification" class="text-gray-600 hover:text-gray-800 px-4 py-2 rounded-lg hover:bg-gray-100 transition-all duration-200 flex items-center text-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                            Back to Pre-qualification
                        </button>
                    @endif
                    <button wire:click="backToList" class="text-gray-600 hover:text-gray-800 p-2 rounded-lg hover:bg-gray-100 transition-all duration-200" title="Back to List">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Selected Lenders Display (for new applications) -->
            @if($currentStep === 'create' && !empty($selectedLenders))
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-2xl p-6 mb-6">
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-bold text-blue-900 mb-2">Selected Lenders</h3>
                            <p class="text-blue-800 text-sm mb-3">Your application will be submitted to the following lenders:</p>
                            <div class="flex flex-wrap gap-2">
                                @foreach($preQualificationResults as $result)
                                    @if(in_array($result['lender_id'], $selectedLenders))
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                            {{ $result['lender_name'] }}
                                            <span class="ml-1 text-xs">({{ $result['interest_rate_min'] }}%)</span>
                                        </span>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Progress Steps -->
            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
                <div class="flex items-center justify-between">
                    @for($i = 1; $i <= 6; $i++)
                        <div class="flex items-center {{ $i < 6 ? 'flex-1' : '' }}">
                            <div class="flex items-center space-x-3">
                                <button wire:click="goToStep({{ $i }})" class="w-12 h-12 rounded-full flex items-center justify-center font-bold text-sm transition-all duration-200 relative
                                    {{ $currentFormStep >= $i ? 'bg-brand-red text-white shadow-lg shadow-brand-red/25' : 'bg-gray-200 text-gray-600 hover:bg-gray-300' }}">
                                    @if($currentFormStep > $i)
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    @else
                                        {{ $i }}
                                    @endif
                                </button>
                                <div class="hidden sm:block">
                                    <p class="text-sm font-bold {{ $currentFormStep >= $i ? 'text-brand-red' : 'text-gray-500' }}">
                                        @switch($i)
                                            @case(1) Loan Details @break
                                            @case(2) Personal Info @break
                                            @case(3) Address @break
                                            @case(4) Employment @break
                                            @case(5) Emergency Contact @break
                                            @case(6) Documents @break
                                        @endswitch
                                    </p>
                                </div>
                            </div>
                            @if($i < 6)
                                <div class="flex-1 h-1 mx-4 rounded-full {{ $currentFormStep > $i ? 'bg-brand-red' : 'bg-gray-200' }}"></div>
                            @endif
                        </div>
                    @endfor
                </div>
            </div>
        </div>

        <!-- Form Content -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
            <form wire:submit="saveApplication">
                <!-- Step 1: Loan Details -->
                @if($currentFormStep === 1)
                    <div class="p-8">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-2xl font-bold text-gray-900">Loan Details</h2>
                            <div class="bg-blue-50 px-4 py-2 rounded-full">
                                <span class="text-blue-700 text-sm font-medium">Step 1 of 6</span>
                            </div>
                        </div>
                        
                        <!-- Pre-qualification Summary (if coming from pre-qualification) -->
                        @if($calculated_dsr > 0)
                            <div class="bg-green-50 border border-green-200 rounded-2xl p-6 mb-8">
                                <div class="flex items-center space-x-4">
                                    <div class="flex-shrink-0">
                                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-bold text-green-900 mb-1">Pre-qualification Completed</h3>
                                        <p class="text-green-800 text-sm">Your DSR: {{ number_format($calculated_dsr, 1) }}% - You qualify for {{ count($preQualificationResults) }} loan products</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                        
                        <div class="space-y-8">
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Requested Amount (TSh) *</label>
                                    <div class="relative">
                                        <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500 font-medium">TSh</span>
                                        <input wire:model.live="requested_amount" type="number" step="1000" min="1000" 
                                               class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-red focus:border-brand-red text-lg font-bold"
                                               placeholder="50,000">
                                    </div>
                                    @error('requested_amount') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Loan Period (Months) *</label>
                                    <select wire:model.live="requested_tenure_months" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-red focus:border-brand-red text-lg font-bold">
                                        <option value="">Select period</option>
                                        <option value="6">6 months</option>
                                        <option value="12">12 months</option>
                                        <option value="18">18 months</option>
                                        <option value="24">24 months</option>
                                        <option value="36">36 months</option>
                                        <option value="48">48 months</option>
                                        <option value="60">60 months</option>
                                        <option value="72">72 months</option>
                                        <option value="84">84 months</option>
                                        <option value="96">96 months</option>
                                        <option value="120">120 months</option>
                                    </select>
                                    @error('requested_tenure_months') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Loan Purpose</label>
                                <select wire:model.live="loan_purpose" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-red focus:border-brand-red">
                                    <option value="">Select purpose</option>
                                    <option value="business">Business Investment</option>
                                    <option value="education">Education</option>
                                    <option value="home_improvement">Home Improvement</option>
                                    <option value="medical">Medical Expenses</option>
                                    <option value="debt_consolidation">Debt Consolidation</option>
                                    <option value="emergency">Emergency</option>
                                    <option value="agriculture">Agriculture</option>
                                    <option value="vehicle">Vehicle Purchase</option>
                                    <option value="working_capital">Working Capital</option>
                                    <option value="other">Other</option>
                                </select>
                                @error('loan_purpose') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <!-- DSR Impact Calculator (if amount or tenure changes) -->
                            @if($requested_amount > 0 && $requested_tenure_months > 0 && $total_monthly_income > 0)
                                @php
                                    $newEstimatedPayment = $requested_amount * (0.15/12 * pow(1 + 0.15/12, $requested_tenure_months)) / (pow(1 + 0.15/12, $requested_tenure_months) - 1);
                                    $newTotalDebt = $existing_loan_payments + $newEstimatedPayment;
                                    $newDSR = ($newTotalDebt / $total_monthly_income) * 100;
                                @endphp
                                <div class="bg-blue-50 border border-blue-200 rounded-2xl p-6">
                                    <h3 class="text-lg font-bold text-blue-900 mb-4">Loan Impact Summary</h3>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div>
                                            <p class="text-sm text-blue-700">Estimated Monthly Payment</p>
                                            <p class="text-lg font-bold text-blue-900">TSh {{ number_format($newEstimatedPayment) }}</p>
                                        </div>
                                        <div>
                                            <p class="text-sm text-blue-700">New Total Monthly Debt</p>
                                            <p class="text-lg font-bold text-blue-900">TSh {{ number_format($newTotalDebt) }}</p>
                                        </div>
                                        <div>
                                            <p class="text-sm text-blue-700">Updated DSR</p>
                                            <p class="text-lg font-bold {{ $newDSR <= 40 ? 'text-green-600' : 'text-red-600' }}">{{ number_format($newDSR, 1) }}%</p>
                                        </div>
                                    </div>
                                    @if($newDSR > 40)
                                        <div class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                                            <p class="text-sm text-yellow-800">
                                                ⚠️ Your DSR may be too high for some lenders. Consider reducing the loan amount or extending the period.
                                            </p>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>

                <!-- Step 2: Personal Information -->
                @elseif($currentFormStep === 2)
                    <div class="p-8">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-2xl font-bold text-gray-900">Personal Information</h2>
                            <div class="bg-blue-50 px-4 py-2 rounded-full">
                                <span class="text-blue-700 text-sm font-medium">Step 2 of 6</span>
                            </div>
                        </div>
                        
                        <div class="space-y-8">
                            <!-- Name Section -->
                            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl p-6 border border-blue-100">
                                <h3 class="text-lg font-bold text-gray-900 mb-4">Full Name</h3>
                                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">First Name *</label>
                                        <input wire:model.live="first_name" type="text" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-red focus:border-brand-red">
                                        @error('first_name') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Middle Name</label>
                                        <input wire:model.live="middle_name" type="text" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-red focus:border-brand-red">
                                        @error('middle_name') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Last Name *</label>
                                        <input wire:model.live="last_name" type="text" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-red focus:border-brand-red">
                                        @error('last_name') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Personal Details Section -->
                            <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-2xl p-6 border border-green-100">
                                <h3 class="text-lg font-bold text-gray-900 mb-4">Personal Details</h3>
                                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Date of Birth *</label>
                                        <input wire:model.live="date_of_birth" type="date" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-red focus:border-brand-red">
                                        @error('date_of_birth') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Gender *</label>
                                        <select wire:model.live="gender" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-red focus:border-brand-red">
                                            <option value="">Select gender</option>
                                            <option value="male">Male</option>
                                            <option value="female">Female</option>
                                            <option value="other">Other</option>
                                        </select>
                                        @error('gender') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Marital Status *</label>
                                        <select wire:model.live="marital_status" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-red focus:border-brand-red">
                                            <option value="">Select status</option>
                                            @foreach($maritalStatuses as $key => $status)
                                                <option value="{{ $key }}">{{ $status }}</option>
                                            @endforeach
                                        </select>
                                        @error('marital_status') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Contact Information Section -->
                            <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-2xl p-6 border border-purple-100">
                                <h3 class="text-lg font-bold text-gray-900 mb-4">Contact Information</h3>
                                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">National ID *</label>
                                        <input wire:model.live="national_id" type="text" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-red focus:border-brand-red">
                                        @error('national_id') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number *</label>
                                        <input wire:model.live="phone_number" type="tel" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-red focus:border-brand-red">
                                        @error('phone_number') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                                        <input wire:model.live="email" type="email" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-red focus:border-brand-red">
                                        @error('email') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                <!-- Step 3: Address Information -->
                @elseif($currentFormStep === 3)
                    <div class="p-8">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-2xl font-bold text-gray-900">Address Information</h2>
                            <div class="bg-blue-50 px-4 py-2 rounded-full">
                                <span class="text-blue-700 text-sm font-medium">Step 3 of 6</span>
                            </div>
                        </div>
                        
                        <div class="space-y-8">
                            <!-- Current Address -->
                            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl p-6 border border-blue-100">
                                <h3 class="text-lg font-bold text-gray-900 mb-4">Current Address</h3>
                                <div class="space-y-6">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Street Address *</label>
                                        <textarea wire:model.live="current_address" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-red focus:border-brand-red" placeholder="Enter your current street address"></textarea>
                                        @error('current_address') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">City *</label>
                                            <input wire:model.live="current_city" type="text" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-red focus:border-brand-red">
                                            @error('current_city') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Region *</label>
                                            <input wire:model.live="current_region" type="text" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-red focus:border-brand-red">
                                            @error('current_region') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Postal Code</label>
                                            <input wire:model.live="current_postal_code" type="text" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-red focus:border-brand-red">
                                            @error('current_postal_code') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Years at Current Address *</label>
                                        <input wire:model.live="years_at_current_address" type="number" min="0" max="50" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-red focus:border-brand-red">
                                        @error('years_at_current_address') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Permanent Address -->
                            <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-2xl p-6 border border-green-100">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="text-lg font-bold text-gray-900">Permanent Address</h3>
                                    <label class="flex items-center cursor-pointer">
                                        <input wire:model.live="is_permanent_same_as_current" type="checkbox" class="text-brand-red focus:ring-brand-red rounded">
                                        <span class="ml-2 text-sm font-medium text-gray-700">Same as current address</span>
                                    </label>
                                </div>
                                
                                @if(!$is_permanent_same_as_current)
                                    <div class="space-y-6">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Permanent Address</label>
                                            <textarea wire:model.live="permanent_address" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-red focus:border-brand-red" placeholder="Enter your permanent address"></textarea>
                                        </div>
                                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">City</label>
                                                <input wire:model.live="permanent_city" type="text" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-red focus:border-brand-red">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Region</label>
                                                <input wire:model.live="permanent_region" type="text" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-red focus:border-brand-red">
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="bg-white rounded-lg p-4 border border-green-200">
                                        <p class="text-sm text-green-700">
                                            ✓ Permanent address will be the same as your current address
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                <!-- Step 4: Employment & Financial Information -->
                @elseif($currentFormStep === 4)
                    <div class="p-8">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-2xl font-bold text-gray-900">Employment & Financial Information</h2>
                            <div class="bg-blue-50 px-4 py-2 rounded-full">
                                <span class="text-blue-700 text-sm font-medium">Step 4 of 6</span>
                            </div>
                        </div>
                        
                        <div class="space-y-8">
                            <!-- Employment Status -->
                            <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-2xl p-6 border border-purple-100">
                                <h3 class="text-lg font-bold text-gray-900 mb-4">Employment Status</h3>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Current Employment Status *</label>
                                    <select wire:model.live="employment_status" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-red focus:border-brand-red">
                                        <option value="">Select employment status</option>
                                        <option value="employed">Employed</option>
                                        <option value="self_employed">Self Employed</option>
                                        <option value="unemployed">Unemployed</option>
                                        <option value="retired">Retired</option>
                                        <option value="student">Student</option>
                                    </select>
                                    @error('employment_status') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <!-- Employment Details (if employed) -->
                            @if($employment_status === 'employed')
                                <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl p-6 border border-blue-100">
                                    <h3 class="text-lg font-bold text-gray-900 mb-4">Employment Details</h3>
                                    <div class="space-y-6">
                                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Employer Name *</label>
                                                <input wire:model.live="employer_name" type="text" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-red focus:border-brand-red">
                                                @error('employer_name') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Job Title *</label>
                                                <input wire:model.live="job_title" type="text" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-red focus:border-brand-red">
                                                @error('job_title') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Employment Sector</label>
                                                <select wire:model.live="employment_sector" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-red focus:border-brand-red">
                                                    <option value="">Select sector</option>
                                                    @foreach($employmentSectors as $key => $sector)
                                                        <option value="{{ $key }}">{{ $sector }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Months with Current Employer</label>
                                                <input wire:model.live="months_with_current_employer" type="number" min="0" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-red focus:border-brand-red">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Business Details (if self-employed) -->
                            @if($employment_status === 'self_employed')
                                <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-2xl p-6 border border-green-100">
                                    <h3 class="text-lg font-bold text-gray-900 mb-4">Business Details</h3>
                                    <div class="space-y-6">
                                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Business Name *</label>
                                                <input wire:model.live="business_name" type="text" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-red focus:border-brand-red">
                                                @error('business_name') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Business Type *</label>
                                                <select wire:model.live="business_type" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-red focus:border-brand-red">
                                                    <option value="">Select business type</option>
                                                    @foreach($businessTypes as $key => $type)
                                                        <option value="{{ $key }}">{{ $type }}</option>
                                                    @endforeach
                                                </select>
                                                @error('business_type') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Registration Number</label>
                                                <input wire:model.live="business_registration_number" type="text" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-red focus:border-brand-red">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Years in Business</label>
                                                <input wire:model.live="years_in_business" type="number" min="0" max="50" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-red focus:border-brand-red">
                                            </div>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Business Address</label>
                                            <textarea wire:model.live="business_address" rows="2" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-red focus:border-brand-red"></textarea>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Financial Information -->
                            <div class="bg-gradient-to-br from-yellow-50 to-orange-50 rounded-2xl p-6 border border-yellow-100">
                                <h3 class="text-lg font-bold text-gray-900 mb-4">Financial Information</h3>
                                <div class="space-y-6">
                                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                        @if($employment_status === 'employed')
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Monthly Salary (TSh) *</label>
                                                <div class="relative">
                                                    <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500 font-medium">TSh</span>
                                                    <input wire:model.live="monthly_salary" type="number" step="1000" min="0" class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-red focus:border-brand-red">
                                                </div>
                                                @error('monthly_salary') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                                            </div>
                                        @endif
                                        
                                        @if($employment_status === 'self_employed')
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Monthly Business Income (TSh) *</label>
                                                <div class="relative">
                                                    <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500 font-medium">TSh</span>
                                                    <input wire:model.live="monthly_business_income" type="number" step="1000" min="0" class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-red focus:border-brand-red">
                                                </div>
                                                @error('monthly_business_income') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                                            </div>
                                        @endif
                                        
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Other Monthly Income (TSh)</label>
                                            <div class="relative">
                                                <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500 font-medium">TSh</span>
                                                <input wire:model.live="other_monthly_income" type="number" step="1000" min="0" class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-red focus:border-brand-red">
                                            </div>
                                            <p class="text-sm text-gray-500 mt-1">Rental income, investments, etc.</p>
                                        </div>
                                    </div>
                                    
                                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Monthly Expenses (TSh) *</label>
                                            <div class="relative">
                                                <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500 font-medium">TSh</span>
                                                <input wire:model.live="monthly_expenses" type="number" step="1000" min="0" class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-red focus:border-brand-red">
                                            </div>
                                            @error('monthly_expenses') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Existing Loan Payments (TSh)</label>
                                            <div class="relative">
                                                <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500 font-medium">TSh</span>
                                                <input wire:model.live="existing_loan_payments" type="number" step="1000" min="0" class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-red focus:border-brand-red">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Display calculated total income -->
                                    @if($total_monthly_income > 0)
                                        <div class="bg-white rounded-lg p-4 border border-yellow-200">
                                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                                <div>
                                                    <p class="text-sm text-yellow-700">Total Monthly Income:</p>
                                                    <p class="text-lg font-bold text-green-600">TSh {{ number_format($total_monthly_income) }}</p>
                                                </div>
                                                <div>
                                                    <p class="text-sm text-yellow-700">Net Income:</p>
                                                    <p class="text-lg font-bold text-blue-600">TSh {{ number_format($total_monthly_income - $monthly_expenses) }}</p>
                                                </div>
                                                @if($existing_loan_payments > 0)
                                                    <div>
                                                        <p class="text-sm text-yellow-700">Available for New Loan:</p>
                                                        <p class="text-lg font-bold text-purple-600">TSh {{ number_format($total_monthly_income - $monthly_expenses - $existing_loan_payments) }}</p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Bank Information -->
                            <div class="bg-gradient-to-br from-indigo-50 to-blue-50 rounded-2xl p-6 border border-indigo-100 mb-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Do you have a bank account?</h3>
            
            <div class="flex gap-4">
                <label class="flex items-center">
                    <input wire:model.live="has_bank_account" type="radio" value="1" class="mr-2 text-brand-red focus:ring-brand-red">
                    <span class="text-gray-700">Yes, I have a bank account</span>
                </label>
                <label class="flex items-center">
                    <input wire:model.live="has_bank_account" type="radio" value="0" class="mr-2 text-brand-red focus:ring-brand-red">
                    <span class="text-gray-700">No, I don't have a bank account</span>
                </label>
            </div>
            
            @error('has_bank_account')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Bank Information Section (Conditional) -->
        @if($has_bank_account === true || $has_bank_account === 1)
            <div class="bg-gradient-to-br from-indigo-50 to-blue-50 rounded-2xl p-6 border border-indigo-100">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Bank Information</h3>
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Bank Name -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Bank Name <span class="text-red-500">*</span>
                        </label>
                        <input 
                            wire:model.live="bank_name" 
                            type="text" 
                            placeholder="Enter your bank name"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-red focus:border-brand-red @error('bank_name') border-red-300 @enderror"
                        >
                        @error('bank_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Account Number -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Account Number <span class="text-red-500">*</span>
                        </label>
                        <input 
                            wire:model.live="account_number" 
                            type="text" 
                            placeholder="Enter your account number"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-red focus:border-brand-red @error('account_number') border-red-300 @enderror"
                        >
                        @error('account_number')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Account Name -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Account Name <span class="text-red-500">*</span>
                        </label>
                        <input 
                            wire:model.live="account_name" 
                            type="text" 
                            placeholder="Enter account holder name"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-red focus:border-brand-red @error('account_name') border-red-300 @enderror"
                        >
                        @error('account_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Account Type -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Account Type <span class="text-red-500">*</span>
                        </label>
                        <select 
                            wire:model.live="account_type" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-red focus:border-brand-red @error('account_type') border-red-300 @enderror"
                        >
                            <option value="">Select account type</option>
                            <option value="savings">Savings Account</option>
                            <option value="current">Current Account</option>
                        </select>
                        @error('account_type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        @endif

        <!-- No Bank Account Message -->
        @if($has_bank_account === false || $has_bank_account === 0)
            <div class="bg-yellow-50 border border-yellow-200 rounded-2xl p-6">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-yellow-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                    <div>
                        <h4 class="text-sm font-medium text-yellow-800">No Bank Account</h4>
                        <p class="text-sm text-yellow-700 mt-1">You've indicated that you don't have a bank account. You may need to open one to receive payments or transfers.</p>
                    </div>
                </div>
            </div>
        @endif



                            <!-- Credit Information -->
                            <div class="bg-gradient-to-br from-red-50 to-pink-50 rounded-2xl p-6 border border-red-100">
                                <h3 class="text-lg font-bold text-gray-900 mb-4">Credit Information</h3>
                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Credit Score (if known)</label>
                                        <input wire:model.live="credit_score" type="number" min="300" max="850" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-red focus:border-brand-red">
                                        <p class="text-sm text-gray-500 mt-1">Leave empty if you don't know your credit score</p>
                                    </div>
                                    <div class="flex items-center pt-8">
                                        <label class="flex items-center cursor-pointer">
                                            <input wire:model.live="has_bad_credit_history" type="checkbox" class="text-brand-red focus:ring-brand-red rounded">
                                            <span class="ml-2 text-sm font-medium text-gray-700">I have a bad credit history</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                <!-- Step 5: Emergency Contact & Additional Info -->
                @elseif($currentFormStep === 5)
                    <div class="p-8">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-2xl font-bold text-gray-900">Emergency Contact & Additional Information</h2>
                            <div class="bg-blue-50 px-4 py-2 rounded-full">
                                <span class="text-blue-700 text-sm font-medium">Step 5 of 6</span>
                            </div>
                        </div>
                        
                        <div class="space-y-8">
                            <!-- Emergency Contact -->
                            <div class="bg-gradient-to-br from-red-50 to-pink-50 rounded-2xl p-6 border border-red-100">
                                <h3 class="text-lg font-bold text-gray-900 mb-4">Emergency Contact</h3>
                                <div class="space-y-6">
                                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Contact Name *</label>
                                            <input wire:model.live="emergency_contact_name" type="text" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-red focus:border-brand-red">
                                            @error('emergency_contact_name') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Relationship *</label>
                                            <select wire:model.live="emergency_contact_relationship" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-red focus:border-brand-red">
                                                <option value="">Select relationship</option>
                                                <option value="spouse">Spouse</option>
                                                <option value="parent">Parent</option>
                                                <option value="sibling">Sibling</option>
                                                <option value="child">Child</option>
                                                <option value="friend">Friend</option>
                                                <option value="colleague">Colleague</option>
                                                <option value="other">Other</option>
                                            </select>
                                            @error('emergency_contact_relationship') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number *</label>
                                            <input wire:model.live="emergency_contact_phone" type="tel" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-red focus:border-brand-red">
                                            @error('emergency_contact_phone') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                                            <input wire:model.live="emergency_contact_address" type="text" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-red focus:border-brand-red">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Disbursement Preference -->
                            <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-2xl p-6 border border-green-100">
                                <h3 class="text-lg font-bold text-gray-900 mb-4">Disbursement Preference</h3>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Preferred Disbursement Method</label>
                                    <select wire:model.live="preferred_disbursement_method" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-red focus:border-brand-red">
                                        <option value="bank_transfer">Bank Transfer</option>
                                        <option value="mobile_money">Mobile Money</option>
                                        <option value="cash">Cash Pickup</option>
                                        <option value="check">Check</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                <!-- Step 6: Document Upload -->
                @elseif($currentFormStep === 6)
                    <div class="p-8">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-2xl font-bold text-gray-900">Required Documents</h2>
                            <div class="bg-blue-50 px-4 py-2 rounded-full">
                                <span class="text-blue-700 text-sm font-medium">Step 6 of 6</span>
                            </div>
                        </div>
                        
                        <!-- Document Upload Section -->
                        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl p-6 border border-blue-100">
                            <div class="mb-6">
                                <h3 class="text-lg font-bold text-gray-900 mb-2">Upload Required Documents</h3>
                                <p class="text-sm text-gray-600">Please upload clear, readable copies of the following documents to complete your application.</p>
                            </div>
                            
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                @foreach($requiredDocuments as $key => $name)
                                    <div class="border border-gray-200 rounded-lg p-6 bg-white">
                                        <div class="flex items-start space-x-4">
                                            <div class="flex-shrink-0">
                                                @if(isset($uploadedDocuments[$key]))
                                                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                                                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                        </svg>
                                                    </div>
                                                @else
                                                    <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center">
                                                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 2z"/>
                                                        </svg>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="flex-1">
                                                <h4 class="font-semibold text-gray-700 mb-2">{{ $name }}</h4>
                                                
                                                @if(isset($uploadedDocuments[$key]))
                                                    <!-- File uploaded -->
                                                    <div class="bg-green-50 border border-green-200 rounded-lg p-3 mb-3">
                                                        <div class="flex items-center justify-between">
                                                            <div class="flex items-center space-x-2">
                                                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                                </svg>
                                                                <span class="text-sm text-green-700 font-medium">{{ $uploadedDocuments[$key]['name'] }}</span>
                                                            </div>
                                                            <button wire:click="removeDocument('{{ $key }}')" class="text-red-600 hover:text-red-800 p-1 rounded">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                                </svg>
                                                            </button>
                                                        </div>
                                                        <p class="text-xs text-green-600 mt-1">
                                                            Size: {{ number_format($uploadedDocuments[$key]['size'] / 1024, 1) }} KB
                                                        </p>
                                                    </div>
                                                @else
                                                    <!-- Upload interface -->
                                                    <div class="space-y-3">
                                                        <input wire:model.live="documents.{{ $key }}" type="file" 
                                                               class="block w-full text-sm text-gray-500
                                                                    file:mr-4 file:py-2 file:px-4
                                                                    file:rounded-full file:border-0
                                                                    file:text-sm file:font-semibold
                                                                    file:bg-brand-red file:text-white
                                                                    hover:file:bg-brand-dark-red
                                                                    file:cursor-pointer"
                                                               accept=".pdf,.jpg,.jpeg,.png">
                                                        @if(isset($documents[$key]))
                                                            <button wire:click="uploadDocument('{{ $key }}')" 
                                                                    type="button"
                                                                    class="bg-blue-100 text-blue-700 px-3 py-1 rounded-lg text-sm font-medium hover:bg-blue-200 transition-colors">
                                                                Upload File
                                                            </button>
                                                        @endif
                                                        @error('documents.' . $key) 
                                                            <span class="text-red-500 text-sm block">{{ $message }}</span> 
                                                        @enderror
                                                    </div>
                                                @endif

                                                <!-- Document requirements -->
                                                <div class="mt-3 text-xs text-gray-500">
                                                    @switch($key)
                                                        @case('national_id')
                                                            <p>Clear photo of both sides of your National ID</p>
                                                            @break
                                                        @case('salary_slip')
                                                            <p>Most recent 3 months salary slips</p>
                                                            @break
                                                        @case('bank_statement')
                                                            <p>Last 6 months bank statements</p>
                                                            @break
                                                        @case('employment_letter')
                                                            <p>Employment letter or contract from employer</p>
                                                            @break
                                                        @case('utility_bill')
                                                            <p>Recent utility bill (electricity, water, etc.)</p>
                                                            @break
                                                        @case('business_license')
                                                            <p>Valid business license or registration certificate</p>
                                                            @break
                                                        @case('tax_certificate')
                                                            <p>Tax compliance certificate or recent tax returns</p>
                                                            @break
                                                        @default
                                                            <p>Clear, readable document required</p>
                                                    @endswitch
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            
                            <div class="mt-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                                <div class="flex items-start space-x-3">
                                    <svg class="w-5 h-5 text-yellow-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                    </svg>
                                    <div>
                                    <p class="text-sm text-yellow-800 font-medium mb-1">Document Guidelines:</p>
                                        <ul class="text-sm text-yellow-700 space-y-1">
                                            <li>• Accepted formats: PDF, JPG, PNG</li>
                                            <li>• Maximum file size: 5MB per file</li>
                                            <li>• Ensure documents are clear and readable</li>
                                            <li>• All text and details must be visible</li>
                                            <li>• Avoid blurry or dark photos</li>
                                            <li>• Upload separate files for multi-page documents</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- Application Summary -->
                            <div class="mt-8 bg-white border border-gray-200 rounded-lg p-6">
                                <h4 class="text-lg font-bold text-gray-900 mb-4">Application Summary</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 text-sm">
                                    <div>
                                        <p class="text-gray-600">Loan Amount:</p>
                                        <p class="font-bold text-gray-900">TSh {{ number_format($requested_amount) }}</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-600">Loan Period:</p>
                                        <p class="font-bold text-gray-900">{{ $requested_tenure_months }} months</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-600">Monthly Income:</p>
                                        <p class="font-bold text-gray-900">TSh {{ number_format($total_monthly_income) }}</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-600">Selected Lenders:</p>
                                        <p class="font-bold text-gray-900">{{ count($selectedLenders) }} lender{{ count($selectedLenders) > 1 ? 's' : '' }}</p>
                                    </div>
                                </div>
                                
                                @if($calculated_dsr > 0)
                                    <div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                                        <p class="text-sm text-blue-800">
                                            <strong>Your DSR:</strong> {{ number_format($calculated_dsr, 1) }}% 
                                            - {{ $calculated_dsr <= 30 ? 'Excellent' : ($calculated_dsr <= 40 ? 'Good' : 'Fair') }} loan affordability
                                        </p>
                                    </div>
                                @endif

                                <!-- Selected Lenders Summary -->
                                @if(!empty($selectedLenders) && !empty($preQualificationResults))
                                    <div class="mt-4">
                                        <p class="text-sm font-medium text-gray-700 mb-2">Your application will be sent to:</p>
                                        <div class="flex flex-wrap gap-2">
                                            @foreach($preQualificationResults as $result)
                                                @if(in_array($result['lender_id'], $selectedLenders))
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                                        {{ $result['lender_name'] }}
                                                        <span class="ml-1 text-xs">({{ $result['interest_rate_min'] }}%)</span>
                                                    </span>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif



                <div class="px-8 py-6 bg-gray-50 border-t border-gray-200">
                    <div class="flex items-center justify-between">
                        <!-- Previous Button -->
                        <div>
                            @if($currentFormStep > 1)
                                <button type="button" wire:click="previousStep" class="bg-gray-100 text-gray-700 px-6 py-3 rounded-lg font-semibold hover:bg-gray-200 transition-colors flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                    </svg>
                                    Previous Step
                                </button>
                            @else
                                <button type="button" wire:click="backToPreQualification" class="bg-gray-100 text-gray-700 px-6 py-3 rounded-lg font-semibold hover:bg-gray-200 transition-colors flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                    </svg>
                                    Back to Pre-qualification
                                </button>
                            @endif
                        </div>

                        <!-- Step Indicator and Status -->
                        <div class="hidden md:flex items-center space-x-4">
                            <!-- Form Completion Status -->
                            @if($currentFormStep === 6)
                                @php
                                    $requiredDocs = [ 'bank_statement'];
                                    $requiredUploaded = count(array_intersect($requiredDocs, array_keys($uploadedDocuments)));
                                    $allRequiredUploaded = $requiredUploaded === count($requiredDocs);
                                @endphp
                                <div class="text-center">
                                    <p class="text-xs text-gray-500">Required Documents</p>
                                    <div class="flex items-center space-x-1">
                                        <span class="text-sm {{ $allRequiredUploaded ? 'text-green-600' : 'text-red-600' }} font-medium">
                                            {{ $requiredUploaded }}/{{ count($requiredDocs) }}
                                        </span>
                                        @if($allRequiredUploaded)
                                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        @else
                                            <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            <!-- Step Progress Indicator -->
                            <div class="text-center">
                                <p class="text-xs text-gray-500">Progress</p>
                                <div class="flex items-center space-x-2">
                                    <span class="text-sm text-gray-600 font-medium">Step {{ $currentFormStep }} of 6</span>
                                    <div class="flex space-x-1">
                                        @for($i = 1; $i <= 6; $i++)
                                            <div class="w-2 h-2 rounded-full transition-all duration-200 {{ $currentFormStep >= $i ? 'bg-brand-red' : 'bg-gray-300' }}"></div>
                                        @endfor
                                    </div>
                                </div>
                            </div>

                            <!-- Overall Completion -->
                            @php
                                $overallProgress = ($currentFormStep / 6) * 100;
                                if($currentFormStep === 6) {
                                    $docProgress = count($uploadedDocuments) > 0 ? (count($uploadedDocuments) / count($requiredDocuments)) * 20 : 0;
                                    $overallProgress = 80 + $docProgress;
                                }
                            @endphp
                            <div class="text-center">
                                <p class="text-xs text-gray-500">Completion</p>
                                <div class="flex items-center space-x-2">
                                    <span class="text-sm text-gray-600 font-medium">{{ number_format($overallProgress) }}%</span>
                                    <div class="w-16 bg-gray-200 rounded-full h-2">
                                        <div class="bg-brand-red h-2 rounded-full transition-all duration-300" style="width: {{ $overallProgress }}%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Next/Save/Submit Buttons -->
                        <div class="flex items-center space-x-3">
                            @if($currentFormStep < 6)
                                <!-- Save Draft Button -->
                                <button type="button" wire:click="saveApplication(false)" class="bg-gray-100 text-gray-700 px-6 py-3 rounded-lg font-semibold hover:bg-gray-200 transition-colors flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3-3m0 0l-3 3m3-3v12"/>
                                    </svg>
                                    Save Draft
                                </button>
                                <!-- Next Step Button -->
                                <button type="button" wire:click="nextStep" class="bg-brand-red text-white px-6 py-3 rounded-lg font-semibold hover:bg-brand-dark-red transition-all duration-200 shadow-lg shadow-brand-red/25 flex items-center">
                                    Next Step
                                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </button>
                            @else
                                <!-- Final Step Buttons -->
                                @php
                                    $requiredDocs = [ 'bank_statement'];
                                    $requiredUploaded = count(array_intersect($requiredDocs, array_keys($uploadedDocuments)));
                                    $canSubmit = $requiredUploaded === count($requiredDocs);
                                @endphp

                                <!-- Save Draft Button -->
                                <button type="button" wire:click="saveApplication(false)" class="bg-gray-100 text-gray-700 px-6 py-3 rounded-lg font-semibold hover:bg-gray-200 transition-colors flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3-3m0 0l-3 3m3-3v12"/>
                                    </svg>
                                    Save Draft
                                </button>

                                <!-- Submit Application Button -->
                                @if($canSubmit)
                                    <button type="button" wire:click="saveApplication(true)" class="bg-gradient-to-r from-brand-red to-brand-dark-red text-white px-8 py-3 rounded-lg font-bold hover:from-brand-dark-red hover:to-red-700 transition-all duration-200 shadow-lg shadow-brand-red/25 flex items-center">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                        </svg>
                                        Submit Application
                                        @if(!empty($selectedLenders))
                                            <span class="ml-2 bg-white bg-opacity-20 px-2 py-1 rounded-full text-sm">
                                                to {{ count($selectedLenders) }} lender{{ count($selectedLenders) > 1 ? 's' : '' }}
                                            </span>
                                        @endif
                                    </button>
                                @endif

                            @endif    


                         </div>
                         
                     </div>

                 </div>

            </form>
        </div>




        {{-- VIEW APPLICATION DETAILS --}}
    @elseif($currentStep === 'view' && $selectedApplication)
        <!-- View Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <div class="flex items-center space-x-3 mb-2">
                        <h1 class="text-4xl font-bold text-gray-900">{{ $selectedApplication->application_number }}</h1>
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold
                            @switch($selectedApplication->status)
                                @case('draft') bg-gray-100 text-gray-800 @break
                                @case('submitted') bg-blue-100 text-blue-800 @break
                                @case('under_review') bg-yellow-100 text-yellow-800 @break
                                @case('approved') bg-green-100 text-green-800 @break
                                @case('rejected') bg-red-100 text-red-800 @break
                                @case('disbursed') bg-purple-100 text-purple-800 @break
                                @case('cancelled') bg-gray-100 text-gray-800 @break
                                @default bg-gray-100 text-gray-800
                            @endswitch">
                            {{ ucwords(str_replace('_', ' ', $selectedApplication->status)) }}
                        </span>
                    </div>
                    <p class="text-gray-600 text-lg">{{ $selectedApplication->first_name }} {{ $selectedApplication->last_name }}</p>
                    <p class="text-gray-500 text-sm">Applied {{ $selectedApplication->created_at->diffForHumans() }}</p>
                </div>
                <div class="flex items-center space-x-3">
                    @if(in_array($selectedApplication->status, ['draft']))
                        <button wire:click="editApplication({{ $selectedApplication->id }})" class="bg-brand-red text-white px-6 py-3 rounded-lg font-semibold hover:bg-brand-dark-red transition-all duration-200 shadow-lg shadow-brand-red/25 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Edit Application
                        </button>
                    @endif
                    <button wire:click="backToList" class="text-gray-600 hover:text-gray-800 p-2 rounded-lg hover:bg-gray-100 transition-all duration-200" title="Back to List">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Application Details Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column - Application Information -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Loan Details -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-blue-50 to-indigo-50">
                        <div class="flex items-center justify-between">
                            <h3 class="text-xl font-bold text-gray-900">Loan Details</h3>
                            @if($selectedApplication->debt_to_income_ratio)
                                <div class="text-right">
                                    <p class="text-sm text-gray-600">DSR</p>
                                    <p class="text-lg font-bold {{ $selectedApplication->debt_to_income_ratio <= 30 ? 'text-green-600' : ($selectedApplication->debt_to_income_ratio <= 40 ? 'text-yellow-600' : 'text-red-600') }}">
                                        {{ number_format($selectedApplication->debt_to_income_ratio, 1) }}%
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <h4 class="text-sm font-semibold text-gray-700 mb-2">Requested Amount</h4>
                                <p class="text-2xl font-bold text-brand-red">TSh {{ number_format($selectedApplication->requested_amount) }}</p>
                            </div>
                            <div>
                                <h4 class="text-sm font-semibold text-gray-700 mb-2">Loan Period</h4>
                                <p class="text-2xl font-bold text-gray-900">{{ $selectedApplication->requested_tenure_months }} months</p>
                            </div>
                            <div>
                                <h4 class="text-sm font-semibold text-gray-700 mb-2">Purpose</h4>
                                <p class="text-lg font-medium text-gray-600">
                                    {{ $selectedApplication->loan_purpose ? ucwords(str_replace('_', ' ', $selectedApplication->loan_purpose)) : 'Not specified' }}
                                </p>
                            </div>
                        </div>

                        <!-- Estimated Payment Calculation -->
                        @if($selectedApplication->requested_amount && $selectedApplication->requested_tenure_months)
                            @php
                                $interestRate = 0.15; // Default 15% if no specific rate
                                if($selectedApplication->loanProduct) {
                                    $interestRate = $selectedApplication->loanProduct->interest_rate_min / 100;
                                }
                                $monthlyRate = $interestRate / 12;
                                $estimatedPayment = $selectedApplication->requested_amount * 
                                    ($monthlyRate * pow(1 + $monthlyRate, $selectedApplication->requested_tenure_months)) / 
                                    (pow(1 + $monthlyRate, $selectedApplication->requested_tenure_months) - 1);
                            @endphp
                            <div class="mt-6 bg-blue-50 rounded-lg p-4">
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <p class="text-sm text-blue-700">Estimated Monthly Payment</p>
                                        <p class="text-lg font-bold text-blue-900">TSh {{ number_format($estimatedPayment) }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-blue-700">Total Amount Payable</p>
                                        <p class="text-lg font-bold text-blue-900">TSh {{ number_format($estimatedPayment * $selectedApplication->requested_tenure_months) }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-blue-700">Interest Rate Used</p>
                                        <p class="text-lg font-bold text-blue-900">{{ number_format($interestRate * 100, 1) }}%</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Personal Information -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-green-50 to-emerald-50">
                        <h3 class="text-xl font-bold text-gray-900">Personal Information</h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h4 class="text-sm font-semibold text-gray-700 mb-2">Full Name</h4>
                                <p class="text-gray-900">
                                    {{ $selectedApplication->first_name }} 
                                    {{ $selectedApplication->middle_name ? $selectedApplication->middle_name . ' ' : '' }}
                                    {{ $selectedApplication->last_name }}
                                </p>
                            </div>
                            <div>
                                <h4 class="text-sm font-semibold text-gray-700 mb-2">Date of Birth</h4>
                                <p class="text-gray-900">
                                    {{ $selectedApplication->date_of_birth ? $selectedApplication->date_of_birth->format('M d, Y') : 'Not provided' }}
                                    @if($selectedApplication->date_of_birth)
                                        ({{ $selectedApplication->date_of_birth->age }} years)
                                    @endif
                                </p>
                            </div>
                            <div>
                                <h4 class="text-sm font-semibold text-gray-700 mb-2">Gender</h4>
                                <p class="text-gray-900">{{ $selectedApplication->gender ? ucfirst($selectedApplication->gender) : 'Not specified' }}</p>
                            </div>
                            <div>
                                <h4 class="text-sm font-semibold text-gray-700 mb-2">Marital Status</h4>
                                <p class="text-gray-900">{{ $selectedApplication->marital_status ? ucfirst($selectedApplication->marital_status) : 'Not specified' }}</p>
                            </div>
                            <div>
                                <h4 class="text-sm font-semibold text-gray-700 mb-2">National ID</h4>
                                <p class="text-gray-900">{{ $selectedApplication->national_id ?: 'Not provided' }}</p>
                            </div>
                            <div>
                                <h4 class="text-sm font-semibold text-gray-700 mb-2">Phone Number</h4>
                                <p class="text-gray-900">{{ $selectedApplication->phone_number ?: 'Not provided' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Address Information -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-purple-50 to-pink-50">
                        <h3 class="text-xl font-bold text-gray-900">Address Information</h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h4 class="text-sm font-semibold text-gray-700 mb-2">Current Address</h4>
                                <div class="text-gray-900 space-y-1">
                                    <p>{{ $selectedApplication->current_address ?: 'Not provided' }}</p>
                                    <p>{{ $selectedApplication->current_city }}, {{ $selectedApplication->current_region }}</p>
                                    @if($selectedApplication->current_postal_code)
                                        <p>{{ $selectedApplication->current_postal_code }}</p>
                                    @endif
                                </div>
                                @if($selectedApplication->years_at_current_address)
                                    <p class="text-sm text-gray-600 mt-2">
                                        {{ $selectedApplication->years_at_current_address }} year{{ $selectedApplication->years_at_current_address > 1 ? 's' : '' }} at this address
                                    </p>
                                @endif
                            </div>
                            <div>
                                <h4 class="text-sm font-semibold text-gray-700 mb-2">Permanent Address</h4>
                                @if($selectedApplication->is_permanent_same_as_current)
                                    <p class="text-gray-600 italic">Same as current address</p>
                                @else
                                    <div class="text-gray-900 space-y-1">
                                        <p>{{ $selectedApplication->permanent_address ?: 'Not provided' }}</p>
                                        @if($selectedApplication->permanent_city || $selectedApplication->permanent_region)
                                            <p>{{ $selectedApplication->permanent_city }}, {{ $selectedApplication->permanent_region }}</p>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Employment Information -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-yellow-50 to-orange-50">
                        <h3 class="text-xl font-bold text-gray-900">Employment & Financial Information</h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h4 class="text-sm font-semibold text-gray-700 mb-2">Employment Status</h4>
                                <p class="text-gray-900">{{ $selectedApplication->employment_status ? ucwords(str_replace('_', ' ', $selectedApplication->employment_status)) : 'Not specified' }}</p>
                            </div>

                            @if($selectedApplication->employment_status === 'employed')
                                <div>
                                    <h4 class="text-sm font-semibold text-gray-700 mb-2">Employer</h4>
                                    <p class="text-gray-900">{{ $selectedApplication->employer_name ?: 'Not provided' }}</p>
                                    @if($selectedApplication->job_title)
                                        <p class="text-sm text-gray-600">{{ $selectedApplication->job_title }}</p>
                                    @endif
                                </div>
                                @if($selectedApplication->employment_sector)
                                    <div>
                                        <h4 class="text-sm font-semibold text-gray-700 mb-2">Employment Sector</h4>
                                        <p class="text-gray-900">{{ ucwords(str_replace('_', ' ', $selectedApplication->employment_sector)) }}</p>
                                    </div>
                                @endif
                                @if($selectedApplication->months_with_current_employer)
                                    <div>
                                        <h4 class="text-sm font-semibold text-gray-700 mb-2">Time with Current Employer</h4>
                                        <p class="text-gray-900">{{ $selectedApplication->months_with_current_employer }} months</p>
                                    </div>
                                @endif
                            @endif

                            @if($selectedApplication->employment_status === 'self_employed')
                                <div>
                                    <h4 class="text-sm font-semibold text-gray-700 mb-2">Business Name</h4>
                                    <p class="text-gray-900">{{ $selectedApplication->business_name ?: 'Not provided' }}</p>
                                    @if($selectedApplication->business_type)
                                        <p class="text-sm text-gray-600">{{ ucwords(str_replace('_', ' ', $selectedApplication->business_type)) }}</p>
                                    @endif
                                </div>
                                @if($selectedApplication->business_registration_number)
                                    <div>
                                        <h4 class="text-sm font-semibold text-gray-700 mb-2">Registration Number</h4>
                                        <p class="text-gray-900">{{ $selectedApplication->business_registration_number }}</p>
                                    </div>
                                @endif
                                @if($selectedApplication->years_in_business)
                                    <div>
                                        <h4 class="text-sm font-semibold text-gray-700 mb-2">Years in Business</h4>
                                        <p class="text-gray-900">{{ $selectedApplication->years_in_business }} years</p>
                                    </div>
                                @endif
                            @endif
                        </div>

                        <!-- Financial Summary -->
                        <div class="mt-6 bg-gray-50 rounded-lg p-6">
                            <h4 class="text-lg font-bold text-gray-900 mb-4">Financial Summary</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                <div>
                                    <h5 class="text-sm font-semibold text-gray-700 mb-2">Monthly Income</h5>
                                    <p class="text-lg font-bold text-green-600">TSh {{ number_format($selectedApplication->total_monthly_income) }}</p>
                                </div>
                                <div>
                                    <h5 class="text-sm font-semibold text-gray-700 mb-2">Monthly Expenses</h5>
                                    <p class="text-lg font-bold text-red-600">TSh {{ number_format($selectedApplication->monthly_expenses) }}</p>
                                </div>
                                <div>
                                    <h5 class="text-sm font-semibold text-gray-700 mb-2">Existing Loans</h5>
                                    <p class="text-lg font-bold text-orange-600">TSh {{ number_format($selectedApplication->existing_loan_payments) }}</p>
                                </div>
                                <div>
                                    <h5 class="text-sm font-semibold text-gray-700 mb-2">Net Income</h5>
                                    @php
                                        $netIncome = $selectedApplication->total_monthly_income - $selectedApplication->monthly_expenses - $selectedApplication->existing_loan_payments;
                                    @endphp
                                    <p class="text-lg font-bold {{ $netIncome > 0 ? 'text-blue-600' : 'text-red-600' }}">TSh {{ number_format($netIncome) }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Bank Information -->
                        @if($selectedApplication->bank_name)
                            <div class="mt-6 bg-blue-50 rounded-lg p-4">
                                <h4 class="text-sm font-semibold text-gray-700 mb-2">Bank Information</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-sm text-gray-600">Bank Name</p>
                                        <p class="font-medium text-gray-900">{{ $selectedApplication->bank_name }}</p>
                                    </div>
                                    @if($selectedApplication->account_type)
                                        <div>
                                            <p class="text-sm text-gray-600">Account Type</p>
                                            <p class="font-medium text-gray-900">{{ ucfirst($selectedApplication->account_type) }} Account</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <!-- Credit Information -->
                        @if($selectedApplication->credit_score || $selectedApplication->has_bad_credit_history)
                            <div class="mt-6 bg-red-50 rounded-lg p-4">
                                <h4 class="text-sm font-semibold text-gray-700 mb-2">Credit Information</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @if($selectedApplication->credit_score)
                                        <div>
                                            <p class="text-sm text-gray-600">Credit Score</p>
                                            <p class="font-medium text-gray-900">{{ $selectedApplication->credit_score }}</p>
                                        </div>
                                    @endif
                                    <div>
                                        <p class="text-sm text-gray-600">Credit History</p>
                                        <p class="font-medium {{ $selectedApplication->has_bad_credit_history ? 'text-red-600' : 'text-green-600' }}">
                                            {{ $selectedApplication->has_bad_credit_history ? 'Has issues' : 'Good standing' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Emergency Contact -->
                @if($selectedApplication->emergency_contact_name)
                    <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
                        <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-red-50 to-pink-50">
                            <h3 class="text-xl font-bold text-gray-900">Emergency Contact</h3>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <h4 class="text-sm font-semibold text-gray-700 mb-2">Contact Person</h4>
                                    <p class="text-gray-900">{{ $selectedApplication->emergency_contact_name }}</p>
                                    @if($selectedApplication->emergency_contact_relationship)
                                        <p class="text-sm text-gray-600">{{ ucfirst($selectedApplication->emergency_contact_relationship) }}</p>
                                    @endif
                                </div>
                                <div>
                                    <h4 class="text-sm font-semibold text-gray-700 mb-2">Contact Information</h4>
                                    <p class="text-gray-900">{{ $selectedApplication->emergency_contact_phone }}</p>
                                    @if($selectedApplication->emergency_contact_address)
                                        <p class="text-sm text-gray-600">{{ $selectedApplication->emergency_contact_address }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Documents -->
                @if($selectedApplication->documents && $selectedApplication->documents->count() > 0)
                    <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
                        <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-indigo-50 to-blue-50">
                            <div class="flex items-center justify-between">
                                <h3 class="text-xl font-bold text-gray-900">Uploaded Documents</h3>
                                <span class="text-sm text-gray-600">{{ $selectedApplication->documents->count() }} document(s)</span>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($selectedApplication->documents as $document)
                                    <div class="flex items-center justify-between bg-gray-50 rounded-lg p-4 hover:bg-gray-100 transition-colors">
                                        <div class="flex items-center space-x-3">
                                            @if(in_array(strtolower($document->file_type), ['jpg', 'jpeg', 'png']))
                                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                            @else
                                                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                </svg>
                                            @endif
                                            <div>
                                                <p class="font-medium text-gray-900">{{ ucwords(str_replace('_', ' ', $document->document_type)) }}</p>
                                                <p class="text-sm text-gray-500">{{ number_format($document->file_size / 1024, 1) }} KB</p>
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                                @switch($document->status)
                                                    @case('uploaded') bg-blue-100 text-blue-800 @break
                                                    @case('verified') bg-green-100 text-green-800 @break
                                                    @case('rejected') bg-red-100 text-red-800 @break
                                                    @default bg-gray-100 text-gray-800
                                                @endswitch">
                                                {{ ucfirst($document->status) }}
                                            </span>
                                            <a href="{{ Storage::url($document->file_path) }}" target="_blank" 
                                               class="text-blue-600 hover:text-blue-800 p-1 rounded hover:bg-blue-50 transition-colors"
                                               title="View Document">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Right Column - Status & Actions -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Status Card -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden sticky top-6">
                    <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-brand-red to-brand-dark-red text-white">
                        <h3 class="text-xl font-bold">Application Status</h3>
                    </div>
                    <div class="p-6">
                        <div class="text-center mb-6">
                            <div class="w-16 h-16 mx-auto mb-4 rounded-full flex items-center justify-center
                                @switch($selectedApplication->status)
                                    @case('draft') bg-gray-100 @break
                                    @case('submitted') bg-blue-100 @break
                                    @case('under_review') bg-yellow-100 @break
                                    @case('approved') bg-green-100 @break
                                    @case('rejected') bg-red-100 @break
                                    @case('disbursed') bg-purple-100 @break
                                    @case('cancelled') bg-gray-100 @break
                                    @default bg-gray-100
                                @endswitch">
                                <svg class="w-8 h-8 
                                    @switch($selectedApplication->status)
                                        @case('draft') text-gray-600 @break
                                        @case('submitted') text-blue-600 @break
                                        @case('under_review') text-yellow-600 @break
                                        @case('approved') text-green-600 @break
                                        @case('rejected') text-red-600 @break
                                        @case('disbursed') text-purple-600 @break
                                        @case('cancelled') text-gray-600 @break
                                        @default text-gray-600
                                    @endswitch" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    @switch($selectedApplication->status)
                                        @case('draft')
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            @break
                                        @case('submitted')
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                            @break
                                        @case('under_review')
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            @break
                                        @case('approved')
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            @break
                                        @case('rejected')
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            @break
                                        @case('disbursed')
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                            @break
                                        @case('cancelled')
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            @break
                                        @default
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    @endswitch
                                </svg>
                            </div>
                            <p class="text-xl font-bold text-gray-900">{{ ucwords(str_replace('_', ' ', $selectedApplication->status)) }}</p>
                            @switch($selectedApplication->status)
                                @case('draft')
                                    <p class="text-sm text-gray-600">Application is being prepared</p>
                                    @break
                                @case('submitted')
                                    <p class="text-sm text-gray-600">Waiting for lender review</p>
                                    @break
                                @case('under_review')
                                    <p class="text-sm text-gray-600">Being evaluated by lender</p>
                                    @break
                                @case('approved')
                                    <p class="text-sm text-gray-600">Congratulations! Loan approved</p>
                                    @break
                                @case('rejected')
                                    <p class="text-sm text-gray-600">Application was not approved</p>
                                    @break
                                @case('disbursed')
                                    <p class="text-sm text-gray-600">Loan funds have been released</p>
                                    @break
                                @case('cancelled')
                                    <p class="text-sm text-gray-600">Application was cancelled</p>
                                    @break
                            @endswitch
                        </div>

                        <!-- Timeline -->
                        <div class="space-y-4">
                            <h4 class="font-semibold text-gray-700 mb-3">Application Timeline</h4>
                            
                            <!-- Created -->
                            <div class="flex items-center space-x-3">
                                <div class="w-3 h-3 bg-green-500 rounded-full flex-shrink-0"></div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-700">Application Created</p>
                                    <p class="text-xs text-gray-500">{{ $selectedApplication->created_at->format('M d, Y H:i') }}</p>
                                </div>
                            </div>
                            
                            <!-- Submitted -->
                            @if($selectedApplication->submitted_at)
                                <div class="flex items-center space-x-3">
                                    <div class="w-3 h-3 bg-blue-500 rounded-full flex-shrink-0"></div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-700">Application Submitted</p>
                                        <p class="text-xs text-gray-500">{{ $selectedApplication->submitted_at->format('M d, Y H:i') }}</p>
                                    </div>
                                </div>
                            @endif
                            
                            <!-- Under Review -->
                            @if($selectedApplication->reviewed_at)
                                <div class="flex items-center space-x-3">
                                    <div class="w-3 h-3 bg-yellow-500 rounded-full flex-shrink-0"></div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-700">Review Started</p>
                                        <p class="text-xs text-gray-500">{{ $selectedApplication->reviewed_at->format('M d, Y H:i') }}</p>
                                        @if($selectedApplication->reviewed_by)
                                            <p class="text-xs text-gray-400">Reviewed by staff</p>
                                        @endif
                                    </div>
                                </div>
                            @endif
                            
                            <!-- Approved -->
                            @if($selectedApplication->approved_at)
                                <div class="flex items-center space-x-3">
                                    <div class="w-3 h-3 bg-green-500 rounded-full flex-shrink-0"></div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-700">Application Approved</p>
                                        <p class="text-xs text-gray-500">{{ $selectedApplication->approved_at->format('M d, Y H:i') }}</p>
                                    </div>
                                </div>
                            @endif
                            
                            <!-- Disbursed -->
                            @if($selectedApplication->disbursed_at)
                                <div class="flex items-center space-x-3">
                                    <div class="w-3 h-3 bg-purple-500 rounded-full flex-shrink-0"></div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-700">Loan Disbursed</p>
                                        <p class="text-xs text-gray-500">{{ $selectedApplication->disbursed_at->format('M d, Y H:i') }}</p>
                                    </div>
                                </div>
                            @endif

                            <!-- Pending Steps -->
                            @if(!$selectedApplication->submitted_at && $selectedApplication->status === 'draft')
                                <div class="flex items-center space-x-3 opacity-50">
                                    <div class="w-3 h-3 bg-gray-300 rounded-full flex-shrink-0"></div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm text-gray-500">Pending submission</p>
                                    </div>
                                </div>
                            @elseif($selectedApplication->status === 'submitted')
                                <div class="flex items-center space-x-3 opacity-50">
                                    <div class="w-3 h-3 bg-gray-300 rounded-full flex-shrink-0"></div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm text-gray-500">Awaiting review</p>
                                    </div>
                                </div>
                            @elseif($selectedApplication->status === 'under_review')
                                <div class="flex items-center space-x-3 opacity-50">
                                    <div class="w-3 h-3 bg-gray-300 rounded-full flex-shrink-0"></div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm text-gray-500">Awaiting decision</p>
                                    </div>
                                </div>
                            @elseif($selectedApplication->status === 'approved')
                                <div class="flex items-center space-x-3 opacity-50">
                                    <div class="w-3 h-3 bg-gray-300 rounded-full flex-shrink-0"></div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm text-gray-500">Awaiting disbursement</p>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Lender Information -->
                        @if($selectedApplication->lender)
                            <div class="mt-6 pt-6 border-t border-gray-200">
                                <h4 class="text-sm font-semibold text-gray-700 mb-3">Lender Details</h4>
                                <div class="bg-blue-50 rounded-lg p-4">
                                    <p class="text-gray-900 font-medium">{{ $selectedApplication->lender->company_name }}</p>
                                    @if($selectedApplication->loanProduct)
                                        <p class="text-sm text-blue-600 font-medium">{{ $selectedApplication->loanProduct->name }}</p>
                                        <div class="mt-2 grid grid-cols-2 gap-2 text-sm">
                                            <div>
                                                <p class="text-gray-600">Interest Rate:</p>
                                                <p class="font-medium">{{ $selectedApplication->loanProduct->interest_rate_min }}% - {{ $selectedApplication->loanProduct->interest_rate_max }}%</p>
                                            </div>
                                            <div>
                                                <p class="text-gray-600">Processing Time:</p>
                                                <p class="font-medium">{{ $selectedApplication->loanProduct->approval_time_days }} days</p>
                                            </div>
                                        </div>
                                    @endif
                                    @if($selectedApplication->lender->phone)
                                        <p class="text-sm text-gray-600 mt-2">{{ $selectedApplication->lender->phone }}</p>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <!-- Rejection Reasons -->
                        @if($selectedApplication->status === 'rejected' && $selectedApplication->rejection_reasons)
                            <div class="mt-6 pt-6 border-t border-gray-200">
                                <h4 class="text-sm font-semibold text-gray-700 mb-3">Rejection Reasons</h4>
                                <div class="bg-red-50 rounded-lg p-4">
                                    @php
                                        $reasons = is_string($selectedApplication->rejection_reasons) 
                                            ? json_decode($selectedApplication->rejection_reasons, true) 
                                            : $selectedApplication->rejection_reasons;
                                    @endphp
                                    @if(is_array($reasons))
                                        <ul class="space-y-1">
                                            @foreach($reasons as $reason)
                                                <li class="text-sm text-red-700 flex items-start space-x-2">
                                                    <span class="text-red-500 mt-1">•</span>
                                                    <span>{{ $reason }}</span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <p class="text-sm text-red-700">{{ $selectedApplication->rejection_reasons }}</p>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <!-- Notes -->
                        @if($selectedApplication->notes)
                            <div class="mt-6 pt-6 border-t border-gray-200">
                                <h4 class="text-sm font-semibold text-gray-700 mb-3">Additional Notes</h4>
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <p class="text-sm text-gray-700">{{ $selectedApplication->notes }}</p>
                                </div>
                            </div>
                        @endif

                        <!-- Actions -->
                        <div class="mt-6 space-y-3">
                            @if($selectedApplication->status === 'submitted' && !$selectedApplication->lender_id)
                                <button wire:click="viewMatchingProducts({{ $selectedApplication->id }})" class="w-full bg-green-100 text-green-700 py-3 px-4 rounded-lg font-semibold hover:bg-green-200 transition-colors flex items-center justify-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    View Matching Lenders
                                </button>
                            @endif
                            
                            @if($selectedApplication->status === 'draft')
                                <button wire:click="editApplication({{ $selectedApplication->id }})" class="w-full bg-blue-100 text-blue-700 py-3 px-4 rounded-lg font-semibold hover:bg-blue-200 transition-colors flex items-center justify-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    Continue Editing
                                </button>
                            @endif
                            
                            @if(in_array($selectedApplication->status, ['draft', 'submitted']))
                                <button wire:click="cancelApplication({{ $selectedApplication->id }})" 
                                        wire:confirm="Are you sure you want to cancel this application? This action cannot be undone."
                                        class="w-full bg-red-100 text-red-700 py-3 px-4 rounded-lg font-semibold hover:bg-red-200 transition-colors flex items-center justify-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    Cancel Application
                                </button>
                            @endif

                            <!-- Print/Download Application -->
                            <button onclick="window.print()" class="w-full bg-gray-100 text-gray-700 py-3 px-4 rounded-lg font-semibold hover:bg-gray-200 transition-colors flex items-center justify-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                                </svg>
                                Print Application
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-purple-50 to-indigo-50">
                        <h3 class="text-lg font-bold text-gray-900">Quick Stats</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Application Age</span>
                            <span class="font-medium text-gray-900">{{ $selectedApplication->created_at->diffForHumans() }}</span>
                        </div>
                        
                        @if($selectedApplication->submitted_at)
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Processing Time</span>
                                <span class="font-medium text-gray-900">{{ $selectedApplication->submitted_at->diffForHumans() }}</span>
                            </div>
                        @endif

                        @if($selectedApplication->debt_to_income_ratio)
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">DSR Rating</span>
                                <span class="font-medium {{ $selectedApplication->debt_to_income_ratio <= 30 ? 'text-green-600' : ($selectedApplication->debt_to_income_ratio <= 40 ? 'text-yellow-600' : 'text-red-600') }}">
                                    {{ $selectedApplication->debt_to_income_ratio <= 30 ? 'Excellent' : ($selectedApplication->debt_to_income_ratio <= 40 ? 'Good' : 'Fair') }}
                                </span>
                            </div>
                        @endif

                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Documents</span>
                            <span class="font-medium text-gray-900">{{ $selectedApplication->documents ? $selectedApplication->documents->count() : 0 }} uploaded</span>
                        </div>

                        @if($selectedApplication->loanProduct)
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Expected Processing</span>
                                <span class="font-medium text-gray-900">{{ $selectedApplication->loanProduct->approval_time_days }} days</span>
                            </div>
                        @endif

                        @if($selectedApplication->preferred_disbursement_method)
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Disbursement Method</span>
                                <span class="font-medium text-gray-900">{{ ucwords(str_replace('_', ' ', $selectedApplication->preferred_disbursement_method)) }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Help & Support -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-green-50 to-emerald-50">
                        <h3 class="text-lg font-bold text-gray-900">Need Help?</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="text-center">
                            <svg class="w-12 h-12 text-green-600 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-sm text-gray-600 mb-4">Have questions about your application? We're here to help!</p>
                            
                            <div class="space-y-2">
                                <a href="tel:+255123456789" class="block w-full bg-green-100 text-green-700 py-2 px-4 rounded-lg font-medium hover:bg-green-200 transition-colors text-sm">
                                    📞 Call Support
                                </a>
                                <a href="mailto:support@loanplatform.com" class="block w-full bg-blue-100 text-blue-700 py-2 px-4 rounded-lg font-medium hover:bg-blue-200 transition-colors text-sm">
                                    ✉️ Email Support
                                </a>
                                <!-- <button class="w-full bg-purple-100 text-purple-700 py-2 px-4 rounded-lg font-medium hover:bg-purple-200 transition-colors text-sm">
                                    💬 Live Chat
                                </button> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>






        {{-- MATCHING PRODUCTS VIEW --}}
    @elseif($currentStep === 'products' && $selectedApplication)
        <!-- Products Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-4xl font-bold text-gray-900 mb-2">Matching Loan Products</h1>
                    <p class="text-gray-600 text-lg">Choose the best loan product for your application</p>
                    <div class="mt-2 flex items-center space-x-4 text-sm">
                        <span class="text-gray-500">Application: <span class="font-medium text-gray-900">{{ $selectedApplication->application_number }}</span></span>
                        <span class="text-gray-500">Amount: <span class="font-medium text-brand-red">TSh {{ number_format($selectedApplication->requested_amount) }}</span></span>
                        <span class="text-gray-500">Period: <span class="font-medium text-gray-900">{{ $selectedApplication->requested_tenure_months }} months</span></span>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <button wire:click="viewApplication({{ $selectedApplication->id }})" 
                            class="text-gray-600 hover:text-gray-800 px-4 py-2 rounded-lg hover:bg-gray-100 transition-all duration-200 flex items-center text-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                        Back to Application
                    </button>
                    <button wire:click="backToList" class="text-gray-600 hover:text-gray-800 p-2 rounded-lg hover:bg-gray-100 transition-all duration-200" title="Back to List">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Application Summary Banner -->
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-2xl p-6 mb-6">
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-bold text-blue-900 mb-2">Application Ready for Lender Selection</h3>
                        <p class="text-blue-800 text-sm mb-3">Your application has been processed and is ready to be sent to lenders. Choose from the matching products below based on your profile and preferences.</p>
                        
                        <!-- Quick Application Stats -->
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div class="bg-blue-100 rounded-lg p-3">
                                <p class="text-xs text-blue-700">Monthly Income</p>
                                <p class="text-sm font-bold text-blue-900">TSh {{ number_format($selectedApplication->total_monthly_income) }}</p>
                            </div>
                            @if($selectedApplication->debt_to_income_ratio)
                                <div class="bg-blue-100 rounded-lg p-3">
                                    <p class="text-xs text-blue-700">Your DSR</p>
                                    <p class="text-sm font-bold text-blue-900">{{ number_format($selectedApplication->debt_to_income_ratio, 1) }}%</p>
                                </div>
                            @endif
                            <div class="bg-blue-100 rounded-lg p-3">
                                <p class="text-xs text-blue-700">Employment</p>
                                <p class="text-sm font-bold text-blue-900">{{ ucwords(str_replace('_', ' ', $selectedApplication->employment_status)) }}</p>
                            </div>
                            <div class="bg-blue-100 rounded-lg p-3">
                                <p class="text-xs text-blue-700">Available Products</p>
                                <p class="text-sm font-bold text-blue-900">{{ count($matchingProducts) }} match{{ count($matchingProducts) !== 1 ? 'es' : '' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Matching Products Grid -->
        @if(!empty($matchingProducts))
            <!-- Filter and Sort Options -->
            <div class="mb-6 bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Available Loan Products</h3>
                        <p class="text-sm text-gray-600">{{ count($matchingProducts) }} lender{{ count($matchingProducts) !== 1 ? 's' : '' }} match your criteria</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <!-- Sort Options -->
                        <select class="bg-gray-50 border border-gray-200 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-brand-red focus:border-brand-red">
                            <option value="eligibility">Best Match</option>
                            <option value="interest_rate">Lowest Interest Rate</option>
                            <option value="processing_time">Fastest Processing</option>
                            <option value="amount">Highest Amount</option>
                        </select>
                        
                        <!-- View Toggle -->
                        <div class="flex items-center bg-gray-100 rounded-lg p-1">
                            <button class="px-3 py-1 bg-white rounded-lg shadow-sm text-sm">Grid</button>
                            <button class="px-3 py-1 text-gray-600 text-sm">List</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                @foreach($matchingProducts as $index => $product)
                    @php
                        $isTopMatch = $index === 0;
                        $eligibilityScore = $product['eligibility_score'] ?? 85;
                        $isEligible = $eligibilityScore >= 70;
                    @endphp
                    
                    <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg transition-all duration-300 
                                {{ $isTopMatch ? 'ring-2 ring-brand-red' : '' }}
                                {{ !$isEligible ? 'opacity-75' : '' }}">
                        
                        <!-- Product Header -->
                        <div class="p-6 {{ $isTopMatch ? 'bg-gradient-to-r from-brand-red to-brand-dark-red text-white' : 'bg-gradient-to-r from-blue-50 to-indigo-50' }}">
                            <div class="flex items-center justify-between mb-3">
                                <div>
                                    <h3 class="text-xl font-bold {{ $isTopMatch ? 'text-white' : 'text-gray-900' }}">
                                        {{ $product['lender_name'] }}
                                    </h3>
                                    <p class="text-sm {{ $isTopMatch ? 'text-red-100' : 'text-gray-600' }}">
                                        {{ $product['product_name'] }}
                                    </p>
                                </div>
                                <div class="flex items-center space-x-2">
                                    @if($isTopMatch)
                                        <span class="bg-white text-brand-red px-3 py-1 rounded-full text-xs font-bold">BEST MATCH</span>
                                    @endif
                                    @if(!$isEligible)
                                        <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-bold">LOW MATCH</span>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Key Metrics -->
                            <div class="grid grid-cols-3 gap-4">
                                <div>
                                    <p class="text-xs {{ $isTopMatch ? 'text-red-200' : 'text-gray-500' }}">Eligibility Score</p>
                                    <div class="flex items-center space-x-2">
                                        <p class="text-xl font-bold {{ $isTopMatch ? 'text-white' : 'text-gray-900' }}">{{ $eligibilityScore }}%</p>
                                        <div class="flex-1 bg-{{ $isTopMatch ? 'white bg-opacity-20' : 'gray-200' }} rounded-full h-2">
                                            <div class="bg-{{ $isTopMatch ? 'white' : 'brand-red' }} h-2 rounded-full" style="width: {{ $eligibilityScore }}%"></div>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <p class="text-xs {{ $isTopMatch ? 'text-red-200' : 'text-gray-500' }}">Interest Rate</p>
                                    <p class="text-xl font-bold {{ $isTopMatch ? 'text-white' : 'text-gray-900' }}">
                                        {{ $product['interest_rate'] }}%
                                        @if(isset($product['interest_rate_max']) && $product['interest_rate'] != $product['interest_rate_max'])
                                            <span class="text-sm">- {{ $product['interest_rate_max'] }}%</span>
                                        @endif
                                    </p>
                                </div>
                                <div>
                                    <p class="text-xs {{ $isTopMatch ? 'text-red-200' : 'text-gray-500' }}">Monthly Payment</p>
                                    <p class="text-xl font-bold {{ $isTopMatch ? 'text-white' : 'text-gray-900' }}">
                                        TSh {{ number_format($product['monthly_payment'] ?? 0) }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Product Details -->
                        <div class="p-6">
                            <div class="space-y-6">
                                <!-- Loan Terms -->
                                <div>
                                    <h4 class="text-sm font-bold text-gray-700 mb-3">Loan Terms & Conditions</h4>
                                    <div class="grid grid-cols-2 gap-4 text-sm">
                                        <div>
                                            <span class="text-gray-500">Amount Range:</span>
                                            <p class="font-medium text-gray-900">
                                                TSh {{ number_format($product['min_amount'] ?? 0) }} - {{ number_format($product['max_amount'] ?? 0) }}
                                            </p>
                                        </div>
                                        <div>
                                            <span class="text-gray-500">Tenure Options:</span>
                                            <p class="font-medium text-gray-900">
                                                {{ $product['min_tenure'] ?? 1 }} - {{ $product['max_tenure'] ?? 60 }} months
                                            </p>
                                        </div>
                                        <div>
                                            <span class="text-gray-500">Processing Fee:</span>
                                            <p class="font-medium text-gray-900">
                                                @if(isset($product['processing_fee_percentage']) && $product['processing_fee_percentage'] > 0)
                                                    {{ $product['processing_fee_percentage'] }}%
                                                @endif
                                                @if(isset($product['processing_fee_fixed']) && $product['processing_fee_fixed'] > 0)
                                                    {{ isset($product['processing_fee_percentage']) && $product['processing_fee_percentage'] > 0 ? ' + ' : '' }}TSh {{ number_format($product['processing_fee_fixed']) }}
                                                @endif
                                                @if((!isset($product['processing_fee_percentage']) || $product['processing_fee_percentage'] == 0) && 
                                                    (!isset($product['processing_fee_fixed']) || $product['processing_fee_fixed'] == 0))
                                                    No fees
                                                @endif
                                            </p>
                                        </div>
                                        <div>
                                            <span class="text-gray-500">Interest Type:</span>
                                            <p class="font-medium text-gray-900">{{ ucfirst($product['interest_type'] ?? 'reducing') }}</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Processing Information -->
                                <div>
                                    <h4 class="text-sm font-bold text-gray-700 mb-3">Processing Timeline</h4>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div class="bg-blue-50 rounded-lg p-3">
                                            <div class="flex items-center space-x-2">
                                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                <div>
                                                    <p class="text-xs text-blue-600">Approval Time</p>
                                                    <p class="text-sm font-bold text-blue-900">{{ $product['approval_time_days'] ?? 7 }} days</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="bg-green-50 rounded-lg p-3">
                                            <div class="flex items-center space-x-2">
                                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                                </svg>
                                                <div>
                                                    <p class="text-xs text-green-600">Disbursement</p>
                                                    <p class="text-sm font-bold text-green-900">{{ $product['disbursement_time_days'] ?? 3 }} days</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Eligibility Status -->
                                <div>
                                    <h4 class="text-sm font-bold text-gray-700 mb-3">Eligibility Assessment</h4>
                                    
                                    @if($isEligible)
                                        <!-- Matched Criteria -->
                                        @if(isset($product['matched_criteria']) && count($product['matched_criteria']) > 0)
                                            <div class="bg-green-50 rounded-lg p-4 mb-3">
                                                <h5 class="text-sm font-semibold text-green-800 mb-2 flex items-center">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                    </svg>
                                                    You qualify for:
                                                </h5>
                                                <ul class="space-y-1">
                                                    @foreach($product['matched_criteria'] as $criteria)
                                                        <li class="text-sm text-green-700 flex items-center">
                                                            <svg class="w-3 h-3 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                            </svg>
                                                            {{ $criteria }}
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif

                                        <!-- Minor Concerns -->
                                        @if(isset($product['minor_concerns']) && count($product['minor_concerns']) > 0)
                                            <div class="bg-yellow-50 rounded-lg p-4">
                                                <h5 class="text-sm font-semibold text-yellow-800 mb-2 flex items-center">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                                    </svg>
                                                    Minor considerations:
                                                </h5>
                                                <ul class="space-y-1">
                                                    @foreach($product['minor_concerns'] as $concern)
                                                        <li class="text-sm text-yellow-700 flex items-center">
                                                            <svg class="w-3 h-3 mr-2 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                                            </svg>
                                                            {{ $concern }}
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif
                                    @else
                                        <!-- Not Eligible -->
                                        <div class="bg-red-50 rounded-lg p-4">
                                            <h5 class="text-sm font-semibold text-red-800 mb-2 flex items-center">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                                Eligibility concerns:
                                            </h5>
                                            @if(isset($product['unmatched_criteria']))
                                                <ul class="space-y-1">
                                                    @foreach($product['unmatched_criteria'] as $criteria)
                                                        <li class="text-sm text-red-700 flex items-center">
                                                            <svg class="w-3 h-3 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                            </svg>
                                                            {{ $criteria }}
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @else
                                                <p class="text-sm text-red-700">Your profile doesn't fully match this product's requirements.</p>
                                            @endif
                                        </div>
                                    @endif
                                </div>

                                <!-- Key Features -->
                                @if(isset($product['key_features']))
                                    <div>
                                        <h4 class="text-sm font-bold text-gray-700 mb-3">Key Features</h4>
                                        <div class="bg-gray-50 rounded-lg p-4">
                                            <ul class="space-y-2">
                                                @foreach(explode(',', $product['key_features']) as $feature)
                                                    <li class="text-sm text-gray-700 flex items-center">
                                                        <svg class="w-3 h-3 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                                        </svg>
                                                        {{ trim($feature) }}
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                @endif

                                <!-- Required Documents -->
                                @if(isset($product['required_documents']))
                                    <div>
                                        <h4 class="text-sm font-bold text-gray-700 mb-3">Required Documents</h4>
                                        <div class="flex flex-wrap gap-2">
                                            @php
                                                $documents = is_string($product['required_documents']) 
                                                    ? json_decode($product['required_documents'], true) 
                                                    : $product['required_documents'];
                                            @endphp
                                            @if(is_array($documents))
                                                @foreach($documents as $doc)
                                                    <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded-full">
                                                        {{ ucwords(str_replace('_', ' ', $doc)) }}
                                                    </span>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <!-- Apply Button -->
                            <div class="mt-6 pt-6 border-t border-gray-200">
                                @if($isEligible)
                                    <button wire:click="applyToProduct({{ $product['product_id'] }})" 
                                            class="w-full {{ $isTopMatch ? 'bg-gradient-to-r from-brand-red to-brand-dark-red' : 'bg-blue-600 hover:bg-blue-700' }} text-white py-3 px-4 rounded-lg font-bold transition-all duration-200 shadow-lg flex items-center justify-center">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                        </svg>
                                        Submit to {{ $product['lender_name'] }}
                                        @if($isTopMatch)
                                            <span class="ml-2 bg-white bg-opacity-20 px-2 py-1 rounded-full text-xs">RECOMMENDED</span>
                                        @endif
                                    </button>
                                @else
                                    <button disabled class="w-full bg-gray-300 text-gray-500 py-3 px-4 rounded-lg font-bold cursor-not-allowed flex items-center justify-center">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728"/>
                                        </svg>
                                        Not Eligible
                                    </button>
                                    <p class="text-xs text-gray-500 text-center mt-2">Consider improving your profile or try a different loan amount</p>
                                @endif

                                <!-- Comparison Option -->
                                <div class="mt-3 text-center">
                                    <button class="text-gray-600 hover:text-gray-800 text-sm underline">
                                        Add to comparison
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Multiple Application Option -->
            @php
                $eligibleProducts = collect($matchingProducts)->where('eligibility_score', '>=', 70);
            @endphp
            @if($eligibleProducts->count() > 1)
                <div class="mt-8 bg-gradient-to-r from-purple-50 to-pink-50 border border-purple-200 rounded-2xl p-6">
                    <div class="text-center">
                        <svg class="w-12 h-12 text-purple-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Apply to Multiple Lenders</h3>
                        <p class="text-gray-600 mb-4">Increase your chances of approval by applying to multiple eligible lenders simultaneously.</p>
                        
                        <div class="flex flex-col sm:flex-row gap-3 justify-center items-center">
                            <button class="bg-purple-600 text-white px-6 py-3 rounded-lg font-bold hover:bg-purple-700 transition-colors flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                </svg>
                                Apply to All Eligible ({{ $eligibleProducts->count() }})
                            </button>
                            <span class="text-sm text-gray-500">or choose individual lenders above</span>
                        </div>
                    </div>
                </div>
            @endif

        @else
        <!-- No Matching Products -->
        <div class="col-span-full bg-white rounded-lg shadow-sm p-12 text-center border border-gray-100">
                <div class="w-20 h-20 bg-gradient-to-br from-gray-400 to-gray-600 rounded-lg flex items-center justify-center mx-auto mb-6 shadow-lg">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">No Matching Products Found</h3>
                <p class="text-gray-500 mb-6">Unfortunately, no loan products match your current criteria. Consider adjusting your application details.</p>
                
          </div>


          @endif 

       

    {{-- LENDER SELECTION VIEW --}}
    @elseif($currentStep === 'lender_selection' && $selectedApplication)
        <!-- Lender Selection Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-4xl font-bold text-gray-900 mb-2">Select Your Preferred Lenders</h1>
                    <p class="text-gray-600 text-lg">Choose one or multiple lenders to send your application to</p>
                    <div class="mt-2 flex items-center space-x-4 text-sm">
                        <span class="text-gray-500">Application: <span class="font-medium text-gray-900">{{ $selectedApplication->application_number }}</span></span>
                        <span class="text-gray-500">Amount: <span class="font-medium text-brand-red">TSh {{ number_format($selectedApplication->requested_amount) }}</span></span>
                        <span class="text-gray-500">Available Lenders: <span class="font-medium text-green-600">{{ count($preQualificationResults) }}</span></span>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <button wire:click="backToPreQualification" 
                            class="text-gray-600 hover:text-gray-800 px-4 py-2 rounded-lg hover:bg-gray-100 transition-all duration-200 flex items-center text-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                        Back to Pre-qualification
                    </button>
                    <button wire:click="backToList" class="text-gray-600 hover:text-gray-800 p-2 rounded-lg hover:bg-gray-100 transition-all duration-200" title="Back to List">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Selection Summary -->
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-2xl p-6 mb-6">
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-bold text-green-900 mb-2">Your Loan Profile Summary</h3>
                        <p class="text-green-800 text-sm mb-4">Based on your financial information, we've found matching lenders. Select your preferred options below.</p>
                        
                        <!-- Profile Summary Grid -->
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div class="bg-green-100 rounded-lg p-3">
                                <p class="text-xs text-green-700">Your DSR</p>
                                <p class="text-sm font-bold text-green-900">{{ number_format($calculated_dsr, 1) }}%</p>
                            </div>
                            <div class="bg-green-100 rounded-lg p-3">
                                <p class="text-xs text-green-700">Monthly Income</p>
                                <p class="text-sm font-bold text-green-900">TSh {{ number_format($prequalify_monthly_income) }}</p>
                            </div>
                            <div class="bg-green-100 rounded-lg p-3">
                                <p class="text-xs text-green-700">Matching Lenders</p>
                                <p class="text-sm font-bold text-green-900">{{ count($preQualificationResults) }} available</p>
                            </div>
                            <div class="bg-green-100 rounded-lg p-3">
                                <p class="text-xs text-green-700">Selected</p>
                                <p class="text-sm font-bold text-green-900">{{ count($selectedLenders) }} chosen</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Selection Controls -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between space-y-4 lg:space-y-0">
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Lender Selection</h3>
                    <p class="text-sm text-gray-600">Choose your preferred lenders and compare their offerings</p>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <!-- Quick Selection Buttons -->
                    <button wire:click="selectAllEligibleLenders" 
                            class="bg-blue-100 text-blue-700 px-4 py-2 rounded-lg font-medium hover:bg-blue-200 transition-colors text-sm flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Select All Eligible
                    </button>
                    <button wire:click="clearSelectedLenders" 
                            class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg font-medium hover:bg-gray-200 transition-colors text-sm flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Clear All
                    </button>
                    
                    <!-- Sort Options -->
                    <select class="bg-gray-50 border border-gray-200 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-brand-red focus:border-brand-red">
                        <option value="dsr">Best DSR Match</option>
                        <option value="rate">Lowest Interest Rate</option>
                        <option value="speed">Fastest Processing</option>
                        <option value="amount">Highest Loan Amount</option>
                    </select>
                </div>
            </div>
            
            <!-- Selected Lenders Summary -->
            @if(!empty($selectedLenders))
                <div class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-blue-800">
                                {{ count($selectedLenders) }} lender{{ count($selectedLenders) > 1 ? 's' : '' }} selected
                            </p>
                            <div class="flex flex-wrap gap-2 mt-2">
                                @foreach($preQualificationResults as $result)
                                    @if(in_array($result['lender_id'], $selectedLenders))
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                            {{ $result['lender_name'] }}
                                            <button wire:click="selectLender({{ $result['lender_id'] }})" class="ml-2 text-blue-600 hover:text-blue-800">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                            </button>
                                        </span>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                        @if(count($selectedLenders) > 0)
                            <button wire:click="proceedToApplication" 
                                    class="bg-blue-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-blue-700 transition-colors text-sm">
                                Proceed with Selected
                            </button>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <!-- Lenders Comparison Table -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden mb-6">
            <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-purple-50 to-indigo-50">
                <h3 class="text-xl font-bold text-gray-900">Lender Comparison</h3>
                <p class="text-sm text-gray-600 mt-1">Compare key features across all available lenders</p>
            </div>
            
            <!-- Desktop Table View -->
            <div class="hidden lg:block overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Select</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lender</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Interest Rate</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Monthly Payment</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">DSR Impact</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Processing Time</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Eligibility</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($preQualificationResults as $index => $result)
                            @php
                                $isSelected = in_array($result['lender_id'], $selectedLenders);
                                $isEligible = $result['eligible'] ?? true;
                                $isTopMatch = $index === 0;
                            @endphp
                            <tr class="hover:bg-gray-50 {{ $isSelected ? 'bg-blue-50' : '' }} {{ !$isEligible ? 'opacity-60' : '' }}">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($isEligible)
                                        <input type="checkbox" 
                                               wire:click="selectLender({{ $result['lender_id'] }})"
                                               {{ $isSelected ? 'checked' : '' }}
                                               class="text-brand-red focus:ring-brand-red rounded">
                                    @else
                                        <span class="text-gray-400">—</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div>
                                            <div class="flex items-center space-x-2">
                                                <p class="text-sm font-medium text-gray-900">{{ $result['lender_name'] }}</p>
                                                @if($isTopMatch)
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-bold bg-brand-red text-white">
                                                        BEST MATCH
                                                    </span>
                                                @endif
                                            </div>
                                            <p class="text-sm text-gray-500">{{ $result['product_name'] }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 font-medium">{{ $result['interest_rate_min'] }}%</div>
                                    @if($result['interest_rate_min'] != $result['interest_rate_max'])
                                        <div class="text-xs text-gray-500">up to {{ $result['interest_rate_max'] }}%</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">TSh {{ number_format($result['monthly_payment']) }}</div>
                                    <div class="text-xs text-gray-500">estimated</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium {{ $result['actual_dsr'] <= 30 ? 'text-green-600' : ($result['actual_dsr'] <= 40 ? 'text-yellow-600' : 'text-red-600') }}">
                                        {{ number_format($result['actual_dsr'], 1) }}%
                                    </div>
                                    <div class="text-xs text-gray-500">vs {{ $result['max_dsr_allowed'] }}% max</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $result['approval_time_days'] }} days</div>
                                    <div class="text-xs text-gray-500">approval</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($isEligible)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            Eligible
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                            Not Eligible
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Mobile Card View -->
            <div class="lg:hidden p-6 space-y-4">
                @foreach($preQualificationResults as $index => $result)
                    @php
                        $isSelected = in_array($result['lender_id'], $selectedLenders);
                        $isEligible = $result['eligible'] ?? true;
                        $isTopMatch = $index === 0;
                    @endphp
                    <div class="border border-gray-200 rounded-lg p-4 {{ $isSelected ? 'ring-2 ring-blue-500 bg-blue-50' : '' }} {{ !$isEligible ? 'opacity-60' : '' }}">
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex-1">
                                <div class="flex items-center space-x-2 mb-1">
                                    <h4 class="font-medium text-gray-900">{{ $result['lender_name'] }}</h4>
                                    @if($isTopMatch)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-bold bg-brand-red text-white">
                                            BEST
                                        </span>
                                    @endif
                                </div>
                                <p class="text-sm text-gray-600">{{ $result['product_name'] }}</p>
                            </div>
                            @if($isEligible)
                                <input type="checkbox" 
                                       wire:click="selectLender({{ $result['lender_id'] }})"
                                       {{ $isSelected ? 'checked' : '' }}
                                       class="text-brand-red focus:ring-brand-red rounded mt-1">
                            @endif
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <p class="text-gray-500">Interest Rate</p>
                                <p class="font-medium">{{ $result['interest_rate_min'] }}%</p>
                            </div>
                            <div>
                                <p class="text-gray-500">Monthly Payment</p>
                                <p class="font-medium">TSh {{ number_format($result['monthly_payment']) }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500">DSR Impact</p>
                                <p class="font-medium {{ $result['actual_dsr'] <= 30 ? 'text-green-600' : ($result['actual_dsr'] <= 40 ? 'text-yellow-600' : 'text-red-600') }}">
                                    {{ number_format($result['actual_dsr'], 1) }}%
                                </p>
                            </div>
                            <div>
                                <p class="text-gray-500">Processing</p>
                                <p class="font-medium">{{ $result['approval_time_days'] }} days</p>
                            </div>
                        </div>

                        <div class="mt-3 pt-3 border-t border-gray-200">
                            @if($isEligible)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Eligible for this product
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    Does not meet requirements
                                </span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Selection Strategy Guide -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden mb-6">
            <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-yellow-50 to-orange-50">
                <h3 class="text-xl font-bold text-gray-900">Selection Strategy Guide</h3>
                <p class="text-sm text-gray-600 mt-1">Tips to help you choose the right lenders</p>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="text-center">
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                        <h4 class="font-bold text-gray-900 mb-2">Single Lender Strategy</h4>
                        <p class="text-sm text-gray-600">Choose the best match for focused application. Lower risk of multiple credit checks.</p>
                        <div class="mt-3">
                            <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded-full">Recommended for good profiles</span>
                        </div>
                    </div>
                    
                    <div class="text-center">
                        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                            </svg>
                        </div>
                        <h4 class="font-bold text-gray-900 mb-2">Multiple Lender Strategy</h4>
                        <p class="text-sm text-gray-600">Apply to 2-3 lenders to increase approval chances. Compare final offers before accepting.</p>
                        <div class="mt-3">
                            <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded-full">Higher approval probability</span>
                        </div>
                    </div>
                    
                    <div class="text-center">
                        <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </div>
                        <h4 class="font-bold text-gray-900 mb-2">Balanced Approach</h4>
                        <p class="text-sm text-gray-600">Select based on different strengths: best rate, fastest processing, highest amount.</p>
                        <div class="mt-3">
                            <span class="text-xs bg-purple-100 text-purple-800 px-2 py-1 rounded-full">Diversified options</span>
                        </div>
                    </div>
                </div>

                <!-- Key Considerations -->
                <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                    <h5 class="font-semibold text-gray-900 mb-3">Key Considerations:</h5>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div class="space-y-2">
                            <div class="flex items-start space-x-2">
                                <span class="text-green-500 mt-1">•</span>
                                <span class="text-gray-700"><strong>Interest Rate:</strong> Lower rates reduce total cost</span>
                            </div>
                            <div class="flex items-start space-x-2">
                                <span class="text-green-500 mt-1">•</span>
                                <span class="text-gray-700"><strong>Processing Speed:</strong> Faster approval for urgent needs</span>
                            </div>
                            <div class="flex items-start space-x-2">
                                <span class="text-green-500 mt-1">•</span>
                                <span class="text-gray-700"><strong>DSR Impact:</strong> Lower DSR means easier approval</span>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <div class="flex items-start space-x-2">
                                <span class="text-orange-500 mt-1">•</span>
                                <span class="text-gray-700"><strong>Fees:</strong> Consider processing and other charges</span>
                            </div>
                            <div class="flex items-start space-x-2">
                                <span class="text-orange-500 mt-1">•</span>
                                <span class="text-gray-700"><strong>Reputation:</strong> Choose established, reliable lenders</span>
                            </div>
                            <div class="flex items-start space-x-2">
                                <span class="text-orange-500 mt-1">•</span>
                                <span class="text-gray-700"><strong>Terms:</strong> Read conditions carefully before applying</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Proceed to Application -->
        @if(!empty($selectedLenders))
            <div class="bg-gradient-to-r from-brand-red to-brand-dark-red rounded-lg shadow-lg p-8 text-white text-center">
                <div class="max-w-2xl mx-auto">
                    <h3 class="text-2xl font-bold mb-2">Ready to Proceed?</h3>
                    <p class="text-red-100 mb-6">
                        You've selected {{ count($selectedLenders) }} lender{{ count($selectedLenders) > 1 ? 's' : '' }}. 
                        Click below to continue with your full application.
                    </p>
                    
                    <!-- Selected Lenders Preview -->
                    <div class="flex flex-wrap justify-center gap-2 mb-6">
                        @foreach($preQualificationResults as $result)
                            @if(in_array($result['lender_id'], $selectedLenders))
                                <span class="bg-white bg-opacity-20 px-3 py-2 rounded-lg text-sm font-medium">
                                    {{ $result['lender_name'] }} ({{ $result['interest_rate_min'] }}%)
                                </span>
                            @endif
                        @endforeach
                    </div>
                    
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <button wire:click="proceedToApplication" 
                                class="bg-white text-brand-red px-8 py-3 rounded-lg font-bold hover:bg-gray-50 transition-all duration-200 shadow-lg flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                            </svg>
                            Continue to Application
                        </button>
                        <button wire:click="clearSelectedLenders" 
                                class="bg-transparent border-2 border-white text-white px-6 py-3 rounded-lg font-bold hover:bg-white hover:text-brand-red transition-all duration-200">
                            Change Selection
                        </button>
                    </div>
                </div>
            </div>
        @else
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-8 text-center">
                <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-yellow-900 mb-2">No Lenders Selected</h3>
                <p class="text-yellow-800 mb-4">Please select at least one lender to proceed with your application.</p>
                <button wire:click="selectAllEligibleLenders" 
                        class="bg-yellow-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-yellow-700 transition-colors">
                    Select All Eligible Lenders
                </button>
            </div>
        @endif

        <!-- Additional Information Panel -->
        <div class="mt-8 bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-start space-x-4">
                <div class="flex-shrink-0">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-bold text-gray-900 mb-2">What Happens Next?</h3>
                    <div class="space-y-3 text-sm text-gray-700">
                        <div class="flex items-start space-x-3">
                            <span class="bg-blue-100 text-blue-800 rounded-full w-6 h-6 flex items-center justify-center text-xs font-bold">1</span>
                            <p><strong>Complete Application:</strong> Fill in detailed information across 6 steps including personal, employment, and financial details.</p>
                        </div>
                        <div class="flex items-start space-x-3">
                            <span class="bg-blue-100 text-blue-800 rounded-full w-6 h-6 flex items-center justify-center text-xs font-bold">2</span>
                            <p><strong>Document Upload:</strong> Upload required documents like ID, salary slips, and bank statements.</p>
                        </div>
                        <div class="flex items-start space-x-3">
                            <span class="bg-blue-100 text-blue-800 rounded-full w-6 h-6 flex items-center justify-center text-xs font-bold">3</span>
                            <p><strong>Submit to Lenders:</strong> Your application will be sent to all selected lenders simultaneously.</p>
                        </div>
                        <div class="flex items-start space-x-3">
                            <span class="bg-blue-100 text-blue-800 rounded-full w-6 h-6 flex items-center justify-center text-xs font-bold">4</span>
                            <p><strong>Lender Review:</strong> Each lender will review your application and may request additional information.</p>
                        </div>
                        <div class="flex items-start space-x-3">
                            <span class="bg-blue-100 text-blue-800 rounded-full w-6 h-6 flex items-center justify-center text-xs font-bold">5</span>
                            <p><strong>Decision & Disbursement:</strong> Approved loans will be disbursed according to your preferred method.</p>
                        </div>
                    </div>
                    
                    <div class="mt-4 p-4 bg-blue-50 rounded-lg">
                        <p class="text-sm text-blue-800">
                            <strong>Tip:</strong> Multiple applications increase your chances of approval, but each lender may perform a credit check. 
                            Consider your credit situation when deciding how many lenders to select.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- FAQs Section -->
        <div class="mt-8 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-gray-100">
                <h3 class="text-lg font-bold text-gray-900">Frequently Asked Questions</h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <details class="group">
                        <summary class="flex items-center justify-between cursor-pointer p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            <span class="font-medium text-gray-900">Can I apply to multiple lenders at once?</span>
                            <svg class="w-5 h-5 text-gray-500 group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </summary>
                        <div class="mt-3 p-3 text-sm text-gray-700 bg-white border-l-4 border-blue-500">
                            Yes, you can apply to multiple lenders simultaneously. This increases your chances of approval and allows you to compare offers. However, each lender may perform a credit check, which could temporarily affect your credit score.
                        </div>
                    </details>

                    <details class="group">
                        <summary class="flex items-center justify-between cursor-pointer p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            <span class="font-medium text-gray-900">How long does the approval process take?</span>
                            <svg class="w-5 h-5 text-gray-500 group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </summary>
                        <div class="mt-3 p-3 text-sm text-gray-700 bg-white border-l-4 border-blue-500">
                            Approval times vary by lender, typically ranging from 3-14 days. The timeline shown for each lender is an estimate based on their standard process. Complete applications with all required documents are processed faster.
                        </div>
                    </details>

                    <details class="group">
                        <summary class="flex items-center justify-between cursor-pointer p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            <span class="font-medium text-gray-900">What if I'm not eligible for any lenders?</span>
                            <svg class="w-5 h-5 text-gray-500 group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </summary>
                        <div class="mt-3 p-3 text-sm text-gray-700 bg-white border-l-4 border-blue-500">
                            If you're not eligible for any lenders, consider reducing your loan amount, extending the repayment period, or improving your debt-to-income ratio. You can also contact our support team for personalized advice on improving your loan profile.
                        </div>
                    </details>

                    <details class="group">
                        <summary class="flex items-center justify-between cursor-pointer p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            <span class="font-medium text-gray-900">Are the interest rates final?</span>
                            <svg class="w-5 h-5 text-gray-500 group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </summary>
                        <div class="mt-3 p-3 text-sm text-gray-700 bg-white border-l-4 border-blue-500">
                            The interest rates shown are indicative ranges. Final rates will be determined by each lender based on their detailed assessment of your creditworthiness, employment stability, and other risk factors.
                        </div>
                    </details>

                    <details class="group">
                        <summary class="flex items-center justify-between cursor-pointer p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            <span class="font-medium text-gray-900">Can I change my lender selection later?</span>
                            <svg class="w-5 h-5 text-gray-500 group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </summary>
                        <div class="mt-3 p-3 text-sm text-gray-700 bg-white border-l-4 border-blue-500">
                            You can modify your lender selection before submitting your application. Once submitted, you cannot add more lenders, but you can withdraw applications that haven't been processed yet by contacting the lenders directly.
                        </div>
                    </details>
                </div>
            </div>
        </div>

        <!-- Support Contact -->
        <div class="mt-8 bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-2xl p-6">
            <div class="text-center">
                <svg class="w-12 h-12 text-green-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <h3 class="text-xl font-bold text-green-900 mb-2">Need Help Choosing?</h3>
                <p class="text-green-800 mb-4">Our loan experts are here to help you make the best decision for your financial situation.</p>
                
                <div class="flex flex-col sm:flex-row gap-3 justify-center">
                    <a href="tel:+255123456789" class="bg-green-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-green-700 transition-colors flex items-center justify-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        Call Us Now
                    </a>
                    <button class="bg-white text-green-700 border border-green-300 px-6 py-3 rounded-lg font-semibold hover:bg-green-50 transition-colors flex items-center justify-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                        Live Chat
                    </button>
                </div>
            </div>
        </div>

     @endif    


            
    

</div>
</div>












