<div>
<div class="max-w-7xl mx-auto">
    <!-- Header with Back Button -->
    <div class="flex items-center justify-between mb-8">
        <div class="flex items-center space-x-4">
            <a href="{{ route('application.list') }}" 
                    class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Leads
            </a>
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
                    @switch($this->application->status)
                        @case('under_review') bg-red-100 text-red-800 @break
                        @case('approved') bg-green-100 text-green-800 @break
                        @case('rejected') bg-gray-100 text-gray-800 @break
                        @default bg-gray-100 text-gray-800
                    @endswitch">
                    {{ ucwords(str_replace('_', ' ', $this->application->status)) }}
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
                    @if($this->application->status === 'under_review')
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


                    @if($this->application->status === 'approved')

                    <button wire:click="processLead('disbursed')" 
                                class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg text-sm font-medium hover:bg-green-700 transition-colors">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Disburse
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
                    <div class="text-2xl font-bold text-red-600 {{ $isAvailable ? '-sm' : '' }}">
                        @if($isAvailable)
                        TSh {{ number_format($application->requested_amount, 1) }}
                        @else
                            TSh {{ number_format($application->requested_amount, 1) }}M
                        @endif
                    </div>
                    <div class="text-sm text-red-700">Requested Amount</div>
                </div>
                <div class="p-4 bg-gray-50 rounded-lg">
                    <div class="text-2xl font-bold text-black {{ $isAvailable ? '-sm' : '' }}">
                        @if($isAvailable)
                        TSh {{ number_format($application->total_monthly_income/1000, 0) }}K
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

    <!-- Tabbed Content -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <!-- Tab Navigation -->
        <div class="border-b border-gray-200">
            <nav class="flex space-x-8 px-6" aria-label="Tabs">
                <button wire:click="switchTab('overview')" 
                        class="@if($activeTab == 'overview') border-b-2 border-red-500 text-red-600 @else border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif py-4 px-1 text-sm font-medium transition-colors">
                    Overview
                </button>
                <button wire:click="switchTab('personal')" 
                        class="@if($activeTab == 'personal') border-b-2 border-red-500 text-red-600 @else border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif py-4 px-1 text-sm font-medium transition-colors">
                    Personal Details
                </button>
                <button wire:click="switchTab('financial')" 
                        class="@if($activeTab == 'financial') border-b-2 border-red-500 text-red-600 @else border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif py-4 px-1 text-sm font-medium transition-colors">
                    Financial Information
                </button>
                <button wire:click="switchTab('employment')" 
                        class="@if($activeTab == 'employment') border-b-2 border-red-500 text-red-600 @else border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif py-4 px-1 text-sm font-medium transition-colors">
                    Employment
                </button>
                <button wire:click="switchTab('documents')" 
                        class="@if($activeTab == 'documents') border-b-2 border-red-500 text-red-600 @else border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif py-4 px-1 text-sm font-medium transition-colors">
                    Documents
                    <span class="ml-2 bg-gray-100 text-gray-600 py-0.5 px-2 rounded-full text-xs">{{ $application->documents->count() ?? 0 }}</span>
                </button>
                <button wire:click="switchTab('history')" 
                        class="@if($activeTab == 'history') border-b-2 border-red-500 text-red-600 @else border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif py-4 px-1 text-sm font-medium transition-colors">
                    Timeline
                </button>
                <button wire:click="switchTab('creditReport')" 
                        class="@if($activeTab == 'creditReport') border-b-2 border-red-500 text-red-600 @else border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif py-4 px-1 text-sm font-medium transition-colors">
                    CRB Report
                </button>

                <button wire:click="switchTab('statementAnalyser')" 
                        class="@if($activeTab == 'statementAnalyser') border-b-2 border-red-500 text-red-600 @else border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif py-4 px-1 text-sm font-medium transition-colors">
                   Statement Analyser
                </button>
            </nav>
        </div>

        <!-- Tab Content -->
        <div class="p-6">
            @if($activeTab == 'overview')
                <livewire:leads.components.lead-overview :application="$application" :isAvailable="$isAvailable" />
            @elseif($activeTab == 'personal')
                <livewire:leads.components.lead-personal :application="$application" :isAvailable="$isAvailable" />
            @elseif($activeTab == 'financial')
                <livewire:leads.components.lead-financial :application="$application" :isAvailable="$isAvailable" />
            @elseif($activeTab == 'employment')
                <livewire:leads.components.lead-employment :application="$application" :isAvailable="$isAvailable" />
            @elseif($activeTab == 'documents')
                <livewire:leads.components.lead-documents :application="$application" :isAvailable="$isAvailable" />
            @elseif($activeTab == 'history')
                <livewire:leads.components.lead-timeline :application="$application" :lead="$lead" :isAvailable="$isAvailable" />
            @elseif($activeTab == 'creditReport')
                <livewire:credit-info-component  :applicationId="$application->id" :isAvailable="$isAvailable" />
          @elseif($activeTab == 'statementAnalyser')
          <livewire:component.transaction-analysis-component />

            @endif
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
</div>
</div>