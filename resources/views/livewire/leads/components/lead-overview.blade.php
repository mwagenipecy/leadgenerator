<div>
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Loan Details -->
    <div>
        <h3 class="text-lg font-semibold text-black mb-4">{{ __('leads.loan_application_details') }}</h3>
        <div class="bg-gray-50 rounded-lg p-6 space-y-4">
            <div class="flex justify-between items-center py-3 border-b border-gray-200">
                <span class="text-sm font-medium text-gray-600">{{ __('leads.application_number') }}</span>
                <span class="text-sm font-bold text-black">{{ $application->application_number }}</span>
            </div>
            <div class="flex justify-between items-center py-3 border-b border-gray-200">
                <span class="text-sm font-medium text-gray-600">{{ __('leads.loan_product') }}</span>
                <span class="text-sm font-bold text-black">{{ $application->loanProduct->name ?? 'N/A' }}</span>
            </div>
            <div class="flex justify-between items-center py-3 border-b border-gray-200">
                <span class="text-sm font-medium text-gray-600">{{ __('leads.requested_amount') }}</span>
                <span class="text-sm font-bold text-black {{ $isAvailable ? '-sm' : '' }}">
                    @if($isAvailable)
                    TSh {{ number_format($application->requested_amount) }}
                    @else
                        TSh {{ number_format($application->requested_amount) }}
                    @endif
                </span>
            </div>
            <div class="flex justify-between items-center py-3 border-b border-gray-200">
                <span class="text-sm font-medium text-gray-600">{{ __('leads.tenure') }}</span>
                <span class="text-sm font-bold text-black">{{ $application->requested_tenure_months }} {{ __('leads.months') }}</span>
            </div>
            <div class="flex justify-between items-center py-3 border-b border-gray-200">
                <span class="text-sm font-medium text-gray-600">{{ __('leads.purpose') }}</span>
                <span class="text-sm font-bold text-black">{{ ucwords(str_replace('_', ' ', $application->loan_purpose ?? 'N/A')) }}</span>
            </div>
            <div class="flex justify-between items-center py-3">
                <span class="text-sm font-medium text-gray-600">{{ __('leads.application_date') }}</span>
                <span class="text-sm font-bold text-black">{{ $application->created_at->format('M d, Y H:i') }}</span>
            </div>
        </div>
    </div>

    <!-- Risk Assessment -->
    <div>
        <h3 class="text-lg font-semibold text-black mb-4">{{ __('leads.risk_assessment') }}</h3>
        <div class="bg-gray-50 rounded-lg p-6 space-y-4">
            <div class="flex justify-between items-center py-3 border-b border-gray-200">
                <span class="text-sm font-medium text-gray-600">{{ __('leads.crb_score') }}</span>
                <span class="text-sm font-bold text-black">{{ $application->credit_score ?? 'N/A' }}</span>
            </div>
            <div class="flex justify-between items-center py-3 border-b border-gray-200">
                <span class="text-sm font-medium text-gray-600">{{ __('leads.debt_to_income_ratio') }}</span>
                @if($application->debt_to_income_ratio && !$isAvailable)
                    <span class="text-sm font-bold {{ $application->debt_to_income_ratio <= 30 ? 'text-green-600' : ($application->debt_to_income_ratio <= 40 ? 'text-yellow-600' : 'text-sidebar-green') }}">
                        {{ number_format($application->debt_to_income_ratio, 1) }}%
                    </span>
                @else
                    <span class="text-sm text-gray-400 {{ $isAvailable ? '-sm' : '' }}">

                        {{ number_format($application->debt_to_income_ratio, 1) }}%

                    </span>
                @endif
            </div>
            <div class="flex justify-between items-center py-3 border-b border-gray-200">
                <span class="text-sm font-medium text-gray-600">{{ __('leads.monthly_obligations') }}</span>
                <span class="text-sm font-bold text-black {{ $isAvailable ? '-sm' : '' }}">
                    @if($isAvailable)
                    TSh {{ number_format($application->total_monthly_income ?? 0) }}
                    @else
                        TSh {{ number_format($application->total_monthly_income ?? 0) }}
                    @endif
                </span>
            </div>

        



        </div>
    </div>



</div>

<!-- Application Timeline -->
<div class="mt-8">
    <h3 class="text-lg font-semibold text-black mb-4">{{ __('leads.application_timeline') }}</h3>
    <div class="bg-gray-50 rounded-lg p-6">
        <div class="flow-root">
            <ul class="-mb-8">
                <li>
                    <div class="relative pb-8">
                        <div class="relative flex space-x-3">
                            <div>
                                <span class="h-8 w-8 rounded-full bg-sidebar-green flex items-center justify-center ring-8 ring-white">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                    </svg>
                                </span>
                            </div>
                            <div class="min-w-0 flex-1 pt-1.5">
                                <div>
                                    <p class="text-sm text-gray-500">{{ __('leads.application_submitted') }}</p>
                                    <p class="text-xs text-gray-400">{{ $application->created_at->format('M d, Y H:i') }}</p>
                                </div>
                            </div>
                        </div>
                        @if($application->reviewed_at || $application->approved_at || $application->disbursed_at)
                            <div class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200"></div>
                        @endif
                    </div>
                </li>

                @if($application->reviewed_at)
                    <li>
                        <div class="relative pb-8">
                            <div class="relative flex space-x-3">
                                <div>
                                    <span class="h-8 w-8 rounded-full bg-yellow-500 flex items-center justify-center ring-8 ring-white">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                    </span>
                                </div>
                                <div class="min-w-0 flex-1 pt-1.5">
                                    <div>
                                        <p class="text-sm text-gray-500">{{ __('leads.review_started') }}</p>
                                        <p class="text-xs text-gray-400">{{ $application->reviewed_at->format('M d, Y H:i') }}</p>
                                    </div>
                                </div>
                            </div>
                            @if($application->approved_at || $application->disbursed_at)
                                <div class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200"></div>
                            @endif
                        </div>
                    </li>
                @endif

                @if($application->approved_at)
                    <li>
                        <div class="relative pb-8">
                            <div class="relative flex space-x-3">
                                <div>
                                    <span class="h-8 w-8 rounded-full bg-green-500 flex items-center justify-center ring-8 ring-white">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </span>
                                </div>
                                <div class="min-w-0 flex-1 pt-1.5">
                                    <div>
                                        <p class="text-sm text-gray-500">{{ __('leads.application_approved') }}</p>
                                        <p class="text-xs text-gray-400">{{ $application->approved_at->format('M d, Y H:i') }}</p>
                                    </div>
                                </div>
                            </div>
                            @if($application->disbursed_at)
                                <div class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200"></div>
                            @endif
                        </div>
                    </li>
                @endif

                @if($application->disbursed_at)
                    <li>
                        <div class="relative">
                            <div class="relative flex space-x-3">
                                <div>
                                    <span class="h-8 w-8 rounded-full bg-sidebar-green flex items-center justify-center ring-8 ring-white">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                        </svg>
                                    </span>
                                </div>
                                <div class="min-w-0 flex-1 pt-1.5">
                                    <div>
                                        <p class="text-sm text-gray-500">{{ __('leads.loan_disbursed') }}</p>
                                        <p class="text-xs text-gray-400">{{ $application->disbursed_at->format('M d, Y H:i') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</div>
</div>
