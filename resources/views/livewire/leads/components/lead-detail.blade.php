<div>
<div class="max-w-7xl mx-auto">
    <!-- Header with Back Button -->
    <div class="flex items-center justify-between mb-8">
        <div class="flex items-center space-x-4">
            <button wire:click="backToList" 
                    class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Leads
            </button>
            <div>
                <h1 class="text-2xl font-bold text-black">Lead Details</h1>
                <p class="text-sm text-gray-600">{{ $application->application_number }}</p>
            </div>
        </div>
        
        <div class="flex items-center space-x-3">
            @if($isAvailable)
                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-red-100 text-red-800">
                    Available Lead
                </span>
            @else
                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium
                    @switch($lead->status)
                        @case('submitted') bg-red-100 text-red-800 @break
                        @case('approved') bg-green-100 text-green-800 @break
                        @case('rejected') bg-gray-100 text-gray-800 @break
                        @default bg-gray-100 text-gray-800
                    @endswitch">
                    {{ ucwords(str_replace('_', ' ', $lead->status)) }}
                </span>
            @endif

            <!-- Quick Actions -->
            <div class="flex items-center space-x-2">
                @if($isAvailable)
                    <button wire:click="bookLead" 
                            class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg text-sm font-medium hover:bg-red-700 transition-colors">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        Book This Lead
                    </button>
                @else
                    @if($lead->status === 'submitted')
                        <button wire:click="processLead('approve')" 
                                class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg text-sm font-medium hover:bg-green-700 transition-colors">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Approve
                        </button>
                        <button wire:click="processLead('reject')" 
                                class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg text-sm font-medium hover:bg-red-700 transition-colors">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Reject
                        </button>
                    @endif
                @endif
            </div>
        </div>
    </div>

    <!-- Applicant Summary Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-8">
        <div class="flex items-start justify-between">
            <div class="flex items-center space-x-6">
                <div class="h-20 w-20 rounded-full {{ $isAvailable ? 'bg-gradient-to-br from-red-400 to-red-600 blur-sm' : 'bg-gradient-to-br from-gray-400 to-gray-600' }} flex items-center justify-center">
                    <span class="text-2xl font-bold text-white">
                        {{ substr($application->first_name, 0, 1) }}{{ substr($application->last_name, 0, 1) }}
                    </span>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-black {{ $isAvailable ? 'blur-sm' : '' }}">
                        {{ $this->getBlurredName() }}
                    </h2>
                    <div class="mt-2 space-y-1">
                        <p class="text-gray-600 flex items-center {{ $isAvailable ? 'blur-sm' : '' }}">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            {{ $this->getBlurredEmail() }}
                        </p>
                        <p class="text-gray-600 flex items-center {{ $isAvailable ? 'blur-sm' : '' }}">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            {{ $this->getBlurredPhone() }}
                        </p>
                        <p class="text-gray-600 flex items-center {{ $isAvailable ? 'blur-sm' : '' }}">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/>
                            </svg>
                            National ID: {{ $isAvailable ? 'NIDA****' : $application->national_id }}
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Key Metrics -->
            <div class="grid grid-cols-3 gap-6 text-center">
                <div class="p-4 bg-red-50 rounded-lg">
                    <div class="text-2xl font-bold text-red-600 {{ $isAvailable ? 'blur-sm' : '' }}">
                        @if($isAvailable)
                            TSh *.* M
                        @else
                            TSh {{ number_format($application->requested_amount/1000000, 1) }}M
                        @endif
                    </div>
                    <div class="text-sm text-red-700">Requested Amount</div>
                </div>
                <div class="p-4 bg-gray-50 rounded-lg">
                    <div class="text-2xl font-bold text-black {{ $isAvailable ? 'blur-sm' : '' }}">
                        @if($isAvailable)
                            TSh ***K
                        @else
                            TSh {{ number_format($application->total_monthly_income/1000, 0) }}K
                        @endif
                    </div>
                    <div class="text-sm text-gray-700">Monthly Income</div>
                </div>
                <div class="p-4 {{ $application->credit_score >= 650 ? 'bg-green-50' : ($application->credit_score >= 550 ? 'bg-yellow-50' : 'bg-red-50') }} rounded-lg">
                    <div class="text-2xl font-bold {{ $application->credit_score >= 650 ? 'text-green-600' : ($application->credit_score >= 550 ? 'text-yellow-600' : 'text-red-600') }}">
                        {{ $application->credit_score ?? 'N/A' }}
                    </div>
                    <div class="text-sm {{ $application->credit_score >= 650 ? 'text-green-700' : ($application->credit_score >= 550 ? 'text-yellow-700' : 'text-red-700') }}">CRB Score</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Information -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Loan Details -->
                <div>
                    <h3 class="text-lg font-semibold text-black mb-4">Loan Application Details</h3>
                    <div class="bg-gray-50 rounded-lg p-6 space-y-4">
                        <div class="flex justify-between items-center py-3 border-b border-gray-200">
                            <span class="text-sm font-medium text-gray-600">Application Number</span>
                            <span class="text-sm font-bold text-black">{{ $application->application_number }}</span>
                        </div>
                        <div class="flex justify-between items-center py-3 border-b border-gray-200">
                            <span class="text-sm font-medium text-gray-600">Loan Product</span>
                            <span class="text-sm font-bold text-black">{{ $application->loanProduct->name ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between items-center py-3 border-b border-gray-200">
                            <span class="text-sm font-medium text-gray-600">Requested Amount</span>
                            <span class="text-sm font-bold text-black {{ $isAvailable ? 'blur-sm' : '' }}">
                                @if($isAvailable)
                                    TSh ***,***
                                @else
                                    TSh {{ number_format($application->requested_amount) }}
                                @endif
                            </span>
                        </div>
                        <div class="flex justify-between items-center py-3 border-b border-gray-200">
                            <span class="text-sm font-medium text-gray-600">Tenure</span>
                            <span class="text-sm font-bold text-black">{{ $application->requested_tenure_months }} months</span>
                        </div>
                        <div class="flex justify-between items-center py-3 border-b border-gray-200">
                            <span class="text-sm font-medium text-gray-600">Purpose</span>
                            <span class="text-sm font-bold text-black">{{ ucwords(str_replace('_', ' ', $application->loan_purpose ?? 'N/A')) }}</span>
                        </div>
                        <div class="flex justify-between items-center py-3">
                            <span class="text-sm font-medium text-gray-600">Application Date</span>
                            <span class="text-sm font-bold text-black">{{ $application->created_at->format('M d, Y H:i') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Financial Profile -->
                <div>
                    <h3 class="text-lg font-semibold text-black mb-4">Financial Profile</h3>
                    <div class="bg-gray-50 rounded-lg p-6 space-y-4">
                        <div class="flex justify-between items-center py-3 border-b border-gray-200">
                            <span class="text-sm font-medium text-gray-600">Monthly Income</span>
                            <span class="text-sm font-bold text-black {{ $isAvailable ? 'blur-sm' : '' }}">
                                @if($isAvailable)
                                    TSh ***,***
                                @else
                                    TSh {{ number_format($application->total_monthly_income) }}
                                @endif
                            </span>
                        </div>
                        <div class="flex justify-between items-center py-3 border-b border-gray-200">
                            <span class="text-sm font-medium text-gray-600">Employment Status</span>
                            <span class="text-sm font-bold text-black {{ $isAvailable ? 'blur-sm' : '' }}">
                                @if($isAvailable)
                                    {{ substr($application->employment_status, 0, 3) }}***
                                @else
                                    {{ ucwords(str_replace('_', ' ', $application->employment_status)) }}
                                @endif
                            </span>
                        </div>
                        <div class="flex justify-between items-center py-3 border-b border-gray-200">
                            <span class="text-sm font-medium text-gray-600">DSR</span>
                            @if($application->debt_to_income_ratio && !$isAvailable)
                                <span class="text-sm font-bold {{ $application->debt_to_income_ratio <= 30 ? 'text-green-600' : ($application->debt_to_income_ratio <= 40 ? 'text-yellow-600' : 'text-red-600') }}">
                                    {{ number_format($application->debt_to_income_ratio, 1) }}%
                                </span>
                            @else
                                <span class="text-sm text-gray-400 {{ $isAvailable ? 'blur-sm' : '' }}">
                                    {{ $isAvailable ? '**.*%' : 'N/A' }}
                                </span>
                            @endif
                        </div>
                        <div class="flex justify-between items-center py-3 border-b border-gray-200">
                            <span class="text-sm font-medium text-gray-600">Credit Score</span>
                            <span class="text-sm font-bold text-black">{{ $application->credit_score ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between items-center py-3">
                            <span class="text-sm font-medium text-gray-600">Bank</span>
                            <span class="text-sm font-bold text-black {{ $isAvailable ? 'blur-sm' : '' }}">
                                @if($isAvailable)
                                    ***Bank
                                @else
                                    {{ $application->bank_name ?? 'N/A' }}
                                @endif
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Information -->
            <div class="mt-8">
                <h3 class="text-lg font-semibold text-black mb-4">Additional Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h4 class="font-medium text-black mb-2">Contact Information</h4>
                        <div class="space-y-2 text-sm">
                            <div><span class="text-gray-600">Address:</span> 
                                <span class="font-medium text-black {{ $isAvailable ? 'blur-sm' : '' }}">
                                    @if($isAvailable)
                                        ***Address
                                    @else
                                        {{ $application->current_address ?? 'N/A' }}
                                    @endif
                                </span>
                            </div>
                            <div><span class="text-gray-600">City:</span> 
                                <span class="font-medium text-black {{ $isAvailable ? 'blur-sm' : '' }}">
                                    @if($isAvailable)
                                        ***City
                                    @else
                                        {{ $application->current_city ?? 'N/A' }}
                                    @endif
                                </span>
                            </div>
                            <div><span class="text-gray-600">Region:</span> 
                                <span class="font-medium text-black {{ $isAvailable ? 'blur-sm' : '' }}">
                                    @if($isAvailable)
                                        ***Region
                                    @else
                                        {{ $application->current_region ?? 'N/A' }}
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Panel for Booked Leads -->
    @if(!$isAvailable && $lead->status === 'submitted')
        <div class="mt-8 bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-black mb-4">Process Lead</h3>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Approval Section -->
                <div class="space-y-4">
                    <h4 class="text-md font-medium text-green-700">Approve Lead</h4>
                    <div class="bg-green-50 rounded-lg p-4 space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Offer Amount</label>
                                <input type="number" wire:model="offerAmount" 
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500"
                                       placeholder="{{ $application->requested_amount }}">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Interest Rate (%)</label>
                                <input type="number" step="0.1" wire:model="offerInterestRate" 
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500"
                                       placeholder="Enter rate">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tenure (months)</label>
                            <input type="number" wire:model="offerTenure" 
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500"
                                   placeholder="{{ $application->requested_tenure_months }}">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                            <textarea wire:model="leadNotes" rows="3" 
                                      class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500"
                                      placeholder="Add approval notes..."></textarea>
                        </div>
                        <button wire:click="processLead('approve')" 
                                class="w-full inline-flex justify-center items-center px-4 py-2 bg-green-600 text-white rounded-lg text-sm font-medium hover:bg-green-700 transition-colors">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Approve Lead
                        </button>
                    </div>
                </div>

                <!-- Rejection Section -->
                <div class="space-y-4">
                    <h4 class="text-md font-medium text-red-700">Reject Lead</h4>
                    <div class="bg-red-50 rounded-lg p-4 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Rejection Reason</label>
                            <textarea wire:model="leadNotes" rows="6" 
                                      class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-red-500"
                                      placeholder="Please provide reason for rejection..."></textarea>
                        </div>
                        <button wire:click="processLead('reject')" 
                                class="w-full inline-flex justify-center items-center px-4 py-2 bg-red-600 text-white rounded-lg text-sm font-medium hover:bg-red-700 transition-colors">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Reject Lead
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Lead History Section for Booked Leads -->
    @if(!$isAvailable)
        <div class="mt-8 bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-black mb-4">Lead Timeline</h3>
            
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
                                        <p class="text-xs text-gray-400">{{ $application->created_at->format('M d, Y H:i') }}</p>
                                    </div>
                                </div>
                            </div>
                            @if($lead->submitted_at)
                                <div class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200"></div>
                            @endif
                        </div>
                    </li>

                    @if($lead->submitted_at)
                        <li>
                            <div class="relative pb-8">
                                <div class="relative flex space-x-3">
                                    <div>
                                        <span class="h-8 w-8 rounded-full bg-red-500 flex items-center justify-center ring-8 ring-white">
                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                            </svg>
                                        </span>
                                    </div>
                                    <div class="min-w-0 flex-1 pt-1.5">
                                        <div>
                                            <p class="text-sm text-gray-500">Lead booked by {{ Auth::user()->lender->company_name }}</p>
                                            <p class="text-xs text-gray-400">{{ $lead->submitted_at }}</p>
                                        </div>
                                    </div>
                                </div>
                                @if($lead->decision_at)
                                    <div class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200"></div>
                                @endif
                            </div>
                        </li>
                    @endif

                    @if($lead->decision_at)
                        <li>
                            <div class="relative">
                                <div class="relative flex space-x-3">
                                    <div>
                                        <span class="h-8 w-8 rounded-full {{ $lead->status === 'approved' ? 'bg-green-500' : 'bg-gray-500' }} flex items-center justify-center ring-8 ring-white">
                                            @if($lead->status === 'approved')
                                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                </svg>
                                            @else
                                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                            @endif
                                        </span>
                                    </div>
                                    <div class="min-w-0 flex-1 pt-1.5">
                                        <div>
                                            <p class="text-sm text-gray-500">Lead {{ $lead->status }}</p>
                                            <p class="text-xs text-gray-400">{{ $lead->decision_at }}</p>
                                            @if($lead->status === 'approved' && $lead->offer_summary)
                                                <p class="text-xs text-green-600 mt-1">{{ $lead->offer_summary }}</p>
                                            @endif
                                            @if($lead->status === 'rejected' && $lead->rejection_reason)
                                                <p class="text-xs text-red-600 mt-1">{{ $lead->rejection_reason }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    @endif
</div>

</div>
