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
                {{ __('leads.back_to_leads') }}
            </a>
            <div>
                <h1 class="text-2xl font-bold text-black">{{ __('leads.lead_details') }}</h1>
                <p class="text-sm text-gray-600">{{ $application->application_number }}</p>
            </div>
        </div>
        
        <div class="flex items-center space-x-3">
            @if($isAvailable)
                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-sidebar-green-100 text-sidebar-green-800">
                    {{ __('leads.available_lead') }}
                </span>
            @else


            
                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium
                    @switch($this->application->status)
                        @case('under_review') bg-sidebar-green-100 text-sidebar-green-800 @break
                        @case('approved') bg-green-100 text-green-800 @break
                        @case('rejected') bg-gray-100 text-gray-800 @break
                        @default bg-gray-100 text-gray-800
                    @endswitch">
                    @if($this->application->status === 'under_review')
                        {{ __('leads.under_review') }}
                    @elseif($this->application->status === 'approved')
                        {{ __('leads.approved') }}
                    @elseif($this->application->status === 'rejected')
                        {{ __('leads.rejected') }}
                    @else
                        {{ ucwords(str_replace('_', ' ', $this->application->status)) }}
                    @endif
                </span>
            @endif

            <!-- Quick Actions -->
            <div class="flex items-center space-x-2">
                @if($isAvailable)
                    <button wire:click="bookLead" 
                            class="inline-flex items-center px-4 py-2 bg-sidebar-green text-white rounded-lg text-sm font-medium hover:bg-sidebar-green-light transition-colors">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        {{ __('leads.book_this_lead') }}
                    </button>
                @else
                    @if($this->application->status === 'under_review')
                        <button wire:click="processLead('approve')" 
                                class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg text-sm font-medium hover:bg-green-700 transition-colors">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            {{ __('leads.approve') }}
                        </button>
                        <button wire:click="processLead('reject')" 
                                class="inline-flex items-center px-4 py-2 bg-sidebar-green text-white rounded-lg text-sm font-medium hover:bg-sidebar-green-light transition-colors">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            {{ __('leads.reject') }}
                        </button>
                    @endif


                    @if($this->application->status === 'approved')

                    <button wire:click="processLead('disbursed')" 
                                class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg text-sm font-medium hover:bg-green-700 transition-colors">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            {{ __('leads.disburse') }}
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
                <div class="h-20 w-20 rounded-full {{ $isAvailable ? 'bg-gradient-to-br from-sidebar-green-400 to-sidebar-green blur-sm' : 'bg-gradient-to-br from-gray-400 to-gray-600' }} flex items-center justify-center">
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
                            {{ __('leads.national_id') }}: {{ $isAvailable ? 'NIDA****' : $application->national_id }}
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Key Metrics -->
            <div class="grid grid-cols-3 gap-6 text-center">
                <div class="p-4 bg-sidebar-green-50 rounded-lg">
                    <div class="text-2xl font-bold text-sidebar-green {{ $isAvailable ? '-sm' : '' }}">
                        @if($isAvailable)
                        TSh {{ number_format($application->requested_amount, 1) }}
                        @else
                            TSh {{ number_format($application->requested_amount, 1) }}
                        @endif
                    </div>
                    <div class="text-sm text-sidebar-green-light">{{ __('leads.requested_amount') }}</div>
                </div>
                <div class="p-4 bg-gray-50 rounded-lg">
                    <div class="text-2xl font-bold text-black {{ $isAvailable ? '-sm' : '' }}">
                        @if($isAvailable)
                        TSh {{ number_format($application->total_monthly_income/1000, 0) }}K
                        @else
                            TSh {{ number_format($application->total_monthly_income/1000, 0) }}K
                        @endif
                    </div>
                    <div class="text-sm text-gray-700">{{ __('leads.monthly_income') }}</div>
                </div>
                <div class="p-4 {{ $application->credit_score >= 650 ? 'bg-green-50' : ($application->credit_score >= 550 ? 'bg-yellow-50' : 'bg-sidebar-green-50') }} rounded-lg">
                    <div class="text-2xl font-bold {{ $application->credit_score >= 650 ? 'text-green-600' : ($application->credit_score >= 550 ? 'text-yellow-600' : 'text-sidebar-green') }}">
                        {{ $application->credit_score ?? 'N/A' }}
                    </div>
                    <div class="text-sm {{ $application->credit_score >= 650 ? 'text-green-700' : ($application->credit_score >= 550 ? 'text-yellow-700' : 'text-sidebar-green-light') }}">{{ __('leads.crb_score') }}</div>
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
                        class="@if($activeTab == 'overview') border-b-2 border-sidebar-green text-sidebar-green @else border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif py-4 px-1 text-sm font-medium transition-colors">
                    {{ __('leads.overview') }}
                </button>
                <button wire:click="switchTab('personal')" 
                        class="@if($activeTab == 'personal') border-b-2 border-sidebar-green text-sidebar-green @else border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif py-4 px-1 text-sm font-medium transition-colors">
                    {{ __('leads.personal_details') }}
                </button>
                <button wire:click="switchTab('financial')" 
                        class="@if($activeTab == 'financial') border-b-2 border-sidebar-green text-sidebar-green @else border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif py-4 px-1 text-sm font-medium transition-colors">
                    {{ __('leads.financial_information') }}
                </button>
                <button wire:click="switchTab('employment')" 
                        class="@if($activeTab == 'employment') border-b-2 border-sidebar-green text-sidebar-green @else border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif py-4 px-1 text-sm font-medium transition-colors">
                    {{ __('leads.employment') }}
                </button>
                <button wire:click="switchTab('documents')" 
                        class="@if($activeTab == 'documents') border-b-2 border-sidebar-green text-sidebar-green @else border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif py-4 px-1 text-sm font-medium transition-colors">
                    {{ __('leads.documents') }}
                    <span class="ml-2 bg-gray-100 text-gray-600 py-0.5 px-2 rounded-full text-xs">{{ $application->documents->count() ?? 0 }}</span>
                </button>
                <button wire:click="switchTab('history')" 
                        class="@if($activeTab == 'history') border-b-2 border-sidebar-green text-sidebar-green @else border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif py-4 px-1 text-sm font-medium transition-colors">
                    {{ __('leads.timeline') }}
                </button>
                <button wire:click="switchTab('creditReport')" 
                        class="@if($activeTab == 'creditReport') border-b-2 border-sidebar-green text-sidebar-green @else border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif py-4 px-1 text-sm font-medium transition-colors">
                    {{ __('leads.crb_report') }}
                </button>

                <button wire:click="switchTab('statementAnalyser')" 
                        class="@if($activeTab == 'statementAnalyser') border-b-2 border-sidebar-green text-sidebar-green @else border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif py-4 px-1 text-sm font-medium transition-colors">
                   {{ __('leads.statement_analyser') }}
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
                <livewire:application-credit-info :applicationId="$application->id" :isAvailable="$isAvailable" />
          @elseif($activeTab == 'statementAnalyser')
          <livewire:component.transaction-analysis-component />

            @endif
        </div>
    </div>

    <!-- Action Panel for Booked Leads -->
    @if(!$isAvailable && $lead->status === 'submitted')
        <div class="mt-8 bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-black mb-4">{{ __('leads.process_lead') }}</h3>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Approval Section -->
                <div class="space-y-4">
                    <h4 class="text-md font-medium text-green-700">{{ __('leads.approve_lead') }}</h4>
                    <div class="bg-green-50 rounded-lg p-4 space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('leads.offer_amount') }}</label>
                                <input type="number" wire:model="offerAmount" 
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500"
                                       placeholder="{{ $application->requested_amount }}">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('leads.interest_rate') }}</label>
                                <input type="number" step="0.1" wire:model="offerInterestRate" 
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500"
                                       placeholder="{{ __('leads.enter_rate') }}">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('leads.tenure_months') }}</label>
                            <input type="number" wire:model="offerTenure" 
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500"
                                   placeholder="{{ $application->requested_tenure_months }}">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('leads.notes') }}</label>
                            <textarea wire:model="leadNotes" rows="3" 
                                      class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500"
                                      placeholder="{{ __('leads.add_approval_notes') }}"></textarea>
                        </div>
                        <button wire:click="processLead('approve')" 
                                class="w-full inline-flex justify-center items-center px-4 py-2 bg-green-600 text-white rounded-lg text-sm font-medium hover:bg-green-700 transition-colors">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            {{ __('leads.approve_lead') }}
                        </button>
                    </div>
                </div>

                <!-- Rejection Section -->
                <div class="space-y-4">
                    <h4 class="text-md font-medium text-sidebar-green-light">{{ __('leads.reject_lead') }}</h4>
                    <div class="bg-sidebar-green-50 rounded-lg p-4 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('leads.rejection_reason') }}</label>
                            <textarea wire:model="leadNotes" rows="6" 
                                      class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-sidebar-green"
                                      placeholder="{{ __('leads.provide_rejection_reason') }}"></textarea>
                        </div>
                        <button wire:click="processLead('reject')" 
                                class="w-full inline-flex justify-center items-center px-4 py-2 bg-sidebar-green text-white rounded-lg text-sm font-medium hover:bg-sidebar-green-light transition-colors">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            {{ __('leads.reject_lead') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
</div>