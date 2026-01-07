
<div class="bg-white rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-all duration-200 overflow-hidden {{ $isAvailable ? 'hover:border-sidebar-green-200' : '' }}">
    <!-- Card Header -->
    <div class="p-6 pb-4">
        <div class="flex items-start justify-between mb-4">
            <div class="flex items-center space-x-3">
                <div class="h-12 w-12 rounded-full {{ $isAvailable ? 'bg-gradient-to-br from-sidebar-green-400 to-sidebar-green' : 'bg-gradient-to-br from-gray-400 to-gray-600' }} flex items-center justify-center {{ $isAvailable ? 'blur-sm' : '' }}">
                    <span class="text-lg font-bold text-white">
                        {{ substr($application->first_name, 0, 1) }}{{ substr($application->last_name, 0, 1) }}
                    </span>
                </div>
            </div>
            <div class="flex flex-col items-end space-y-2">
                @if($isAvailable)
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-sidebar-green-100 text-sidebar-green-800">
                        Available
                    </span>
                @else
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                        @switch($lead->status)
                            @case('submitted') bg-sidebar-green-100 text-sidebar-green-800 @break
                            @case('approved') bg-green-100 text-green-800 @break
                            @case('rejected') bg-gray-100 text-gray-800 @break
                            @default bg-gray-100 text-gray-800
                        @endswitch">
                        {{ ucwords(str_replace('_', ' ', $lead->status)) }}
                    </span>
                @endif
            </div>
        </div>

        <!-- Applicant Info -->
        <div class="mb-4">
            <h3 class="text-lg font-semibold text-black {{ $isAvailable ? 'blur-sm' : '' }}">
                {{ $this->getBlurredName() }}
            </h3>
            <p class="text-sm text-gray-600 {{ $isAvailable ? 'blur-sm' : '' }}">
                {{ $this->getBlurredEmail() }}
            </p>
            <p class="text-xs text-gray-500">{{ $application->application_number }}</p>
            <p class="text-xs text-gray-500 {{ $isAvailable ? 'blur-sm' : '' }}">
                {{ $this->getBlurredPhone() }}
            </p>
        </div>

        <!-- Key Metrics -->
        <div class="grid grid-cols-2 gap-4 mb-4">
            <div class="text-center p-3 bg-gray-50 rounded-lg">
                <div class="text-xs text-gray-500 mb-1">Amount</div>
                <div class="text-sm font-bold text-black {{ $isAvailable ? 'blur-sm' : '' }}">
                    @if($isAvailable)
                        TSh ***K
                    @else
                        TSh {{ number_format($application->requested_amount/1000) }}K
                    @endif
                </div>
            </div>
            <div class="text-center p-3 bg-gray-50 rounded-lg">
                <div class="text-xs text-gray-500 mb-1">CRB Score</div>
                @if($application->credit_score)
                    <div class="text-sm font-bold {{ $application->credit_score >= 650 ? 'text-green-600' : ($application->credit_score >= 550 ? 'text-yellow-600' : 'text-sidebar-green') }}">
                        {{ $application->credit_score }}
                    </div>
                @else
                    <div class="text-sm text-gray-400">N/A</div>
                @endif
            </div>
        </div>

        <!-- Additional Info -->
        <div class="space-y-2 text-sm">
            <div class="flex justify-between">
                <span class="text-gray-500">Income:</span>
                <span class="font-medium {{ $isAvailable ? 'blur-sm' : '' }} text-black">
                    @if($isAvailable)
                        TSh ***K
                    @else
                        TSh {{ number_format($application->total_monthly_income/1000) }}K
                    @endif
                </span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Tenure:</span>
                <span class="font-medium text-black">{{ $application->requested_tenure_months }} months</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Product:</span>
                <span class="font-medium text-xs text-black">{{ $application->loanProduct->name ?? 'N/A' }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Applied:</span>
                <span class="font-medium text-xs text-black">{{ $application->created_at->format('M d, Y') }}</span>
            </div>
            @if($application->debt_to_income_ratio && !$isAvailable)
                <div class="flex justify-between">
                    <span class="text-gray-500">DSR:</span>
                    <span class="font-medium text-black">{{ number_format($application->debt_to_income_ratio, 1) }}%</span>
                </div>
            @endif
            @if($application->employment_status)
                <div class="flex justify-between">
                    <span class="text-gray-500">Employment:</span>
                    <span class="font-medium text-xs text-black {{ $isAvailable ? 'blur-sm' : '' }}">
                        @if($isAvailable)
                            {{ substr($application->employment_status, 0, 3) }}***
                        @else
                            {{ ucwords(str_replace('_', ' ', $application->employment_status)) }}
                        @endif
                    </span>
                </div>
            @endif
        </div>
    </div>

    <!-- Card Actions -->
    <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
        @if($isAvailable)
            <div class="flex items-center justify-between">
                <button wire:click="viewLead" 
                        class="text-sm font-medium text-black hover:text-gray-700 transition-colors">
                    View Details
                </button>
                
                <button wire:click="bookLead" 
                        class="inline-flex items-center px-4 py-2 bg-sidebar-green text-white rounded-lg text-sm font-medium hover:bg-sidebar-green-light transition-all duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                    Book Lead
                </button>
            </div>
        @else
            <div class="flex items-center justify-between">
                <button wire:click="viewLead" 
                        class="text-sm font-medium text-black hover:text-gray-700 transition-colors">
                    View Details
                </button>
                
                <div class="flex items-center space-x-2">
                    @if($lead->status === 'submitted')
                        <button wire:click="processLead('approve')" 
                                class="text-green-600 hover:text-green-800 p-2 rounded-lg hover:bg-green-100 transition-all duration-200"
                                title="Approve">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </button>
                        <button wire:click="processLead('reject')" 
                                class="text-sidebar-green hover:text-sidebar-green-800 p-2 rounded-lg hover:bg-sidebar-green-100 transition-all duration-200"
                                title="Reject">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    @endif

                    @if($lead->status === 'approved')
                        <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded">
                            {{ $lead->offer_summary ?? 'Approved' }}
                        </span>
                    @endif

                    @if($lead->status === 'rejected')
                        <span class="text-xs bg-gray-100 text-gray-800 px-2 py-1 rounded">
                            Rejected
                        </span>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>

