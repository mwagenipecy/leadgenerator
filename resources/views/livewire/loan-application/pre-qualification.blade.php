<div>
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        {{-- LOAN CATEGORY SELECTION --}}
        @if($currentStep === 'category')
            <div class="mb-8">
                <div class="text-center mb-8">
                    <h1 class="text-4xl font-bold text-gray-900 mb-4">Find Your Perfect Loan</h1>
                    <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                        Choose the type of loan that best fits your needs. We'll help you find the right lenders with the best terms.
                    </p>
                </div>

                <!-- Loan Categories Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    @foreach($loanCategories as $key => $name)
                        <div wire:click="selectLoanCategory('{{ $key }}')" 
                             class="bg-white rounded-2xl p-6 border-2 border-gray-100 hover:border-brand-red hover:shadow-lg transition-all duration-300 cursor-pointer group {{ $loan_category === $key ? 'border-brand-red bg-red-50' : '' }}">
                            <div class="text-center">
                                <!-- Category Icon -->
                                <div class="w-16 h-16 mx-auto mb-4 rounded-full flex items-center justify-center {{ $loan_category === $key ? 'bg-brand-red text-white' : 'bg-gray-100 text-gray-600 group-hover:bg-brand-red group-hover:text-white' }} transition-colors">
                                    @switch($key)
                                        @case('personal')
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                            </svg>
                                            @break
                                        @case('business')
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                            </svg>
                                            @break
                                        @case('auto')
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h8a2 2 0 012 2v9a2 2 0 01-2 2H8a2 2 0 01-2-2V9a2 2 0 012-2z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 7V3m0 18v-4"/>
                                            </svg>
                                            @break
                                        @case('home')
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                            </svg>
                                            @break
                                        @case('education')
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                            </svg>
                                            @break
                                        @case('agriculture')
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                            </svg>
                                            @break
                                        @case('emergency')
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            @break
                                        @default
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                            </svg>
                                    @endswitch
                                </div>
                                
                                <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $name }}</h3>
                                <p class="text-sm text-gray-600 leading-relaxed">{{ $this->getLoanCategoryDescription($key) }}</p>
                                
                                @if($loan_category === $key)
                                    <div class="mt-4">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-brand-red text-white">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            Selected
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Loan Type Selection (shows after category selection) -->
                @if($loan_category)
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
                        <div class="text-center mb-6">
                            <h2 class="text-2xl font-bold text-gray-900 mb-2">Choose Loan Security Type</h2>
                            <p class="text-gray-600">Select whether you prefer a secured or unsecured loan</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach($loanTypes as $key => $name)
                                <div wire:click="selectLoanType('{{ $key }}')" 
                                     class="border-2 rounded-xl p-6 cursor-pointer transition-all duration-300 {{ $loan_type === $key ? 'border-brand-red bg-red-50' : 'border-gray-200 hover:border-brand-red hover:bg-gray-50' }}">
                                    <div class="flex items-start space-x-4">
                                        <div class="flex-shrink-0">
                                            <div class="w-12 h-12 rounded-full flex items-center justify-center {{ $loan_type === $key ? 'bg-brand-red text-white' : 'bg-gray-100 text-gray-600' }}">
                                                @if($key === 'secured')
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                                    </svg>
                                                @else
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                                    </svg>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="flex-1">
                                            <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $name }}</h3>
                                            <p class="text-sm text-gray-600 mb-4">{{ $this->getLoanTypeDescription($key) }}</p>
                                            
                                            <!-- Benefits -->
                                            <div class="space-y-2">
                                                @if($key === 'secured')
                                                    <div class="flex items-center text-sm text-green-600">
                                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                        </svg>
                                                        Lower interest rates
                                                    </div>
                                                    <div class="flex items-center text-sm text-green-600">
                                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                        </svg>
                                                        Higher loan amounts
                                                    </div>
                                                    <div class="flex items-center text-sm text-orange-600">
                                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                                        </svg>
                                                        Requires collateral
                                                    </div>
                                                @else
                                                    <div class="flex items-center text-sm text-green-600">
                                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                        </svg>
                                                        No collateral needed
                                                    </div>
                                                    <div class="flex items-center text-sm text-green-600">
                                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                        </svg>
                                                        Faster approval
                                                    </div>
                                                    <div class="flex items-center text-sm text-orange-600">
                                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                                        </svg>
                                                        Higher interest rates
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

        {{-- CRITERIA INPUT --}}
        @elseif($currentStep === 'criteria')
            <div class="mb-8">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h1 class="text-4xl font-bold text-gray-900 mb-2">Loan Requirements</h1>
                        <p class="text-gray-600 text-lg">Enter your loan details to find matching lenders</p>
                        <div class="mt-2 flex items-center space-x-4 text-sm">
                            <span class="text-gray-500">Category: <span class="font-medium text-brand-red">{{ $loanCategories[$loan_category] ?? '' }}</span></span>
                            <span class="text-gray-500">Type: <span class="font-medium text-gray-900">{{ $loanTypes[$loan_type] ?? '' }}</span></span>
                        </div>
                    </div>
                    <button wire:click="backToCategory" class="text-gray-600 hover:text-gray-800 px-4 py-2 rounded-lg hover:bg-gray-100 transition-all duration-200 flex items-center text-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                        Change Category
                    </button>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Left Column - Input Form -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-brand-red to-brand-dark-red text-white">
                            <h3 class="text-xl font-bold mb-2">Loan Details</h3>
                            <p class="text-red-100 text-sm">Specify your loan requirements</p>
                        </div>
                        
                        <div class="p-6 space-y-6">
                            <!-- Loan Amount -->
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">
                                    Requested Amount (TSh) *
                                </label>
                                <div class="relative">
                                    <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500 font-medium">TSh</span>
                                    <input wire:model.live="requested_amount" 
                                           type="number" 
                                           step="1000" 
                                           min="1000" 
                                           class="w-full pl-12 pr-4 py-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-red focus:border-brand-red text-lg font-bold bg-gray-50"
                                           placeholder="50,000">
                                </div>
                                @error('requested_amount') 
                                    <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> 
                                @enderror
                            </div>

                            <!-- Loan Period -->
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">
                                    Loan Period (Months) *
                                </label>
                                <select wire:model.live="requested_tenure" 
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
                                @error('requested_tenure') 
                                    <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> 
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Right Column - Financial Information -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-blue-50 to-indigo-50">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900 mb-2">Financial Information</h3>
                                    <p class="text-gray-600 text-sm">Your income and existing obligations</p>
                                </div>
                                @if($userProfile)
                                    <button wire:click="toggleProfileData" 
                                            class="text-sm {{ $use_profile_data ? 'text-green-600' : 'text-gray-600' }} hover:text-green-800 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $use_profile_data ? 'M5 13l4 4L19 7' : 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z' }}"/>
                                        </svg>
                                        {{ $use_profile_data ? 'Using Profile Data' : 'Use Profile Data' }}
                                    </button>
                                @endif
                            </div>
                        </div>
                        
                        <div class="p-6 space-y-6">
                            <!-- Monthly Income -->
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">
                                    Monthly Income (TSh) *
                                </label>
                                <div class="relative">
                                    <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500 font-medium">TSh</span>
                                    <input wire:model.live="monthly_income" 
                                           type="number" 
                                           step="1000" 
                                           min="0" 
                                           {{ $use_profile_data && $userProfile ? 'readonly' : '' }}
                                           class="w-full pl-12 pr-4 py-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-red focus:border-brand-red text-lg font-bold {{ $use_profile_data && $userProfile ? 'bg-gray-100' : 'bg-gray-50' }}"
                                           placeholder="500,000">
                                </div>
                                @error('monthly_income') 
                                    <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> 
                                @enderror
                                @if($use_profile_data && $userProfile)
                                    <p class="text-sm text-blue-600 mt-1">From your profile</p>
                                @endif
                            </div>

                            <!-- Existing Loans -->
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">
                                    Existing Monthly Loan Payments (TSh) *
                                </label>
                                <div class="relative">
                                    <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500 font-medium">TSh</span>
                                    <input wire:model.live="existing_loans" 
                                           type="number" 
                                           step="1000" 
                                           min="0" 
                                           {{ $use_profile_data && $userProfile ? 'readonly' : '' }}
                                           class="w-full pl-12 pr-4 py-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-red focus:border-brand-red text-lg font-bold {{ $use_profile_data && $userProfile ? 'bg-gray-100' : 'bg-gray-50' }}"
                                           placeholder="0">
                                </div>
                                @error('existing_loans') 
                                    <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> 
                                @enderror
                                @if($use_profile_data && $userProfile)
                                    <p class="text-sm text-blue-600 mt-1">From your profile</p>
                                @endif
                            </div>

                            <!-- Profile Status -->
                            @if($userProfile)
                                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                                    <div class="flex items-center space-x-3">
                                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <div>
                                            <p class="text-sm font-medium text-green-800">Profile {{ $userProfile->profile_completion_percentage }}% Complete</p>
                                            <p class="text-xs text-green-600">Data automatically loaded from your profile</p>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                    <div class="flex items-center space-x-3">
                                        <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                        </svg>
                                        <div>
                                           <a href="{{ route('loan-application.profile') }}">  <p class="text-sm font-medium text-yellow-800">No Profile Found</p>   </a> 
                                            <p class="text-xs text-yellow-600">Create a profile for faster future applications</p>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Calculate Button -->
                            <div class="pt-4">
                                <button wire:click="calculateEligibility" 
                                        class="w-full bg-gradient-to-r from-brand-red to-brand-dark-red text-white py-4 px-6 rounded-lg font-bold hover:from-brand-dark-red hover:to-red-700 transition-all duration-200 shadow-lg shadow-brand-red/25 flex items-center justify-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                    </svg>
                                    Find Matching Lenders
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        {{-- RESULTS --}}
        @elseif($currentStep === 'results')
            <div class="mb-8">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h1 class="text-4xl font-bold text-gray-900 mb-2">Pre-Qualification Results</h1>
                        <p class="text-gray-600 text-lg">Found {{ count($availableProducts) }} matching loan products</p>
                        <div class="mt-2 flex items-center space-x-4 text-sm">
                            <span class="text-gray-500">Amount: <span class="font-medium text-brand-red">TSh {{ number_format($requested_amount) }}</span></span>
                            <span class="text-gray-500">Period: <span class="font-medium text-gray-900">{{ $requested_tenure }} months</span></span>
                            <span class="text-gray-500">DSR: <span class="font-medium {{ $calculated_dsr <= 30 ? 'text-green-600' : ($calculated_dsr <= 40 ? 'text-yellow-600' : 'text-red-600') }}">{{ number_format($calculated_dsr, 1) }}%</span></span>
                        </div>
                    </div>
                    <button wire:click="backToCriteria" class="text-gray-600 hover:text-gray-800 px-4 py-2 rounded-lg hover:bg-gray-100 transition-all duration-200 flex items-center text-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                        Modify Criteria
                    </button>
                </div>

                <!-- Filters and Controls -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between space-y-4 lg:space-y-0">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">Available Products</h3>
                            <p class="text-sm text-gray-600">{{ count($availableProducts) }} products match your criteria</p>
                        </div>
                        <div class="flex flex-wrap items-center gap-3">
                            <!-- Filters -->
                            <select wire:model.live="filter_by_eligibility" 
                                    class="bg-gray-50 border border-gray-200 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-brand-red focus:border-brand-red">
                                <option value="all">All Products</option>
                                <option value="eligible">Eligible Only</option>
                                <option value="not_eligible">Not Eligible</option>
                            </select>
                            
                            <select wire:model.live="sort_by" 
                                    class="bg-gray-50 border border-gray-200 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-brand-red focus:border-brand-red">
                                <option value="score">Best Match</option>
                                <option value="interest_rate">Lowest Rate</option>
                                <option value="processing_time">Fastest Processing</option>
                            </select>
                            
                            <!-- Selection Controls -->
                            <button wire:click="selectAllEligible" 
                                    class="bg-blue-100 text-blue-700 px-4 py-2 rounded-lg font-medium hover:bg-blue-200 transition-colors text-sm">
                                Select All Eligible
                            </button>
                            <button wire:click="clearSelection" 
                                    class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg font-medium hover:bg-gray-200 transition-colors text-sm">
                                Clear Selection
                            </button>
                        </div>
                    </div>
                    
                    <!-- Selected Lenders Display -->
                    @if(!empty($selected_lenders))
                        <div class="mt-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                            <p class="text-sm font-medium text-green-800 mb-2">
                                {{ count($selected_lenders) }} lender(s) selected for application
                            </p>
                            <div class="flex flex-wrap gap-2">
                                @foreach($availableProducts as $product)
                                    @if(in_array($product['lender_id'], $selected_lenders))
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                            {{ $product['lender_name'] }}
                                            <button wire:click="selectLender({{ $product['lender_id'] }})" class="ml-2 text-green-600 hover:text-green-800">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                            </button>
                                        </span>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Products Grid -->
                @if(!empty($availableProducts))
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                        @foreach($availableProducts as $index => $product)
                            @php
                                $isSelected = in_array($product['lender_id'], $selected_lenders);
                                $isEligible = $product['eligible'];
                                $isTopMatch = $index === 0;
                            @endphp
                            
                            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg transition-all duration-300 
                                        {{ $isSelected ? 'ring-2 ring-brand-red' : '' }}
                                        {{ !$isEligible ? 'opacity-75' : '' }}">
                                
                                <!-- Product Header -->
                                <div class="p-6 {{ $isTopMatch && $isEligible ? 'bg-gradient-to-r from-brand-red to-brand-dark-red text-white' : 'bg-gradient-to-r from-gray-50 to-gray-100' }}">
                                    <div class="flex items-center justify-between mb-3">
                                        <div>
                                            <h3 class="text-xl font-bold {{ $isTopMatch && $isEligible ? 'text-white' : 'text-gray-900' }}">
                                                {{ $product['lender_name'] }}
                                            </h3>
                                            <p class="text-sm {{ $isTopMatch && $isEligible ? 'text-red-100' : 'text-gray-600' }}">
                                                {{ $product['product_name'] }}
                                            </p>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            @if($isTopMatch && $isEligible)
                                                <span class="bg-white text-brand-red px-3 py-1 rounded-full text-xs font-bold">BEST MATCH</span>
                                            @endif
                                            @if(!$isEligible)
                                                <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-bold">NOT ELIGIBLE</span>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <!-- Key Metrics -->
                                    <div class="grid grid-cols-3 gap-4">
                                        <div>
                                            <p class="text-xs {{ $isTopMatch && $isEligible ? 'text-red-200' : 'text-gray-500' }}">Eligibility Score</p>
                                            <p class="text-lg font-bold {{ $isTopMatch && $isEligible ? 'text-white' : 'text-gray-900' }}">{{ $product['eligibility_score'] }}%</p>
                                        </div>
                                        <div>
                                            <p class="text-xs {{ $isTopMatch && $isEligible ? 'text-red-200' : 'text-gray-500' }}">Interest Rate</p>
                                            <p class="text-lg font-bold {{ $isTopMatch && $isEligible ? 'text-white' : 'text-gray-900' }}">
                                                {{ $product['interest_rate_min'] }}%
                                                @if($product['interest_rate_min'] != $product['interest_rate_max'])
                                                    <span class="text-sm">- {{ $product['interest_rate_max'] }}%</span>
                                                @endif
                                            </p>
                                        </div>
                                        <div>
                                            <p class="text-xs {{ $isTopMatch && $isEligible ? 'text-red-200' : 'text-gray-500' }}">Monthly Payment</p>
                                            <p class="text-lg font-bold {{ $isTopMatch && $isEligible ? 'text-white' : 'text-gray-900' }}">
                                                TSh {{ number_format($product['monthly_payment'] ?? 0) }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Product Details -->
                                <div class="p-6">
                                    <div class="space-y-4">
                                        <!-- Key Information -->
                                        <div class="grid grid-cols-2 gap-4 text-sm">
                                            <div>
                                                <span class="text-gray-500">Processing Time:</span>
                                                <p class="font-medium text-gray-900">{{ $product['approval_time_days'] }} days</p>
                                            </div>
                                            <div>
                                                <span class="text-gray-500">Disbursement:</span>
                                                <p class="font-medium text-gray-900">{{ $product['disbursement_time_days'] }} days</p>
                                            </div>
                                            <div>
                                                <span class="text-gray-500">Your DSR:</span>
                                                <p class="font-medium {{ $product['dsr'] <= 30 ? 'text-green-600' : ($product['dsr'] <= 40 ? 'text-yellow-600' : 'text-red-600') }}">
                                                    {{ number_format($product['dsr'], 1) }}%
                                                </p>
                                            </div>
                                            <div>
                                                <span class="text-gray-500">Max DSR:</span>
                                                <p class="font-medium text-gray-900">{{ $product['max_dsr_allowed'] }}%</p>
                                            </div>
                                        </div>

                                        <!-- Collateral Information -->
                                        @if($product['collateral_required'])
                                            <div class="bg-yellow-50 rounded-lg p-3">
                                                <div class="flex items-center space-x-2 mb-2">
                                                    <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                                    </svg>
                                                    <span class="text-sm font-medium text-yellow-800">Collateral Required</span>
                                                </div>
                                                @if(!empty($product['collateral_requirements']))
                                                    <ul class="text-xs text-yellow-700 space-y-1">
                                                        @foreach($product['collateral_requirements'] as $requirement)
                                                            <li>• {{ $requirement }}</li>
                                                        @endforeach
                                                    </ul>
                                                @endif
                                            </div>
                                        @endif

                                        <!-- Eligibility Status -->
                                        @if($isEligible)
                                            <div class="bg-green-50 rounded-lg p-3">
                                                <div class="flex items-center space-x-2">
                                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                    </svg>
                                                    <span class="text-green-700 font-medium text-sm">You qualify for this product</span>
                                                </div>
                                            </div>
                                        @else
                                            <div class="bg-red-50 rounded-lg p-3">
                                                <div class="flex items-center space-x-2 mb-2">
                                                    <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                    </svg>
                                                    <span class="text-red-700 font-medium text-sm">Eligibility Issues</span>
                                                </div>
                                                @if(!empty($product['eligibility_issues']))
                                                    <ul class="text-xs text-red-700 space-y-1">
                                                        @foreach($product['eligibility_issues'] as $issue)
                                                            <li>• {{ $issue }}</li>
                                                        @endforeach
                                                    </ul>
                                                @endif
                                            </div>
                                        @endif

                                        <!-- Selection Checkbox -->
                                        @if($isEligible)
                                            <div class="pt-2">
                                                <label class="flex items-center cursor-pointer">
                                                    <input type="checkbox" 
                                                           wire:click="selectLender({{ $product['lender_id'] }})"
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

                    <!-- Proceed to Application -->
                    @if(!empty($selected_lenders))
                        <div class="bg-gradient-to-r from-brand-red to-brand-dark-red rounded-2xl shadow-lg p-8 text-white text-center">
                            <div class="max-w-2xl mx-auto">
                                <h3 class="text-2xl font-bold mb-2">Ready to Apply?</h3>
                                <p class="text-red-100 mb-6">
                                    You've selected {{ count($selected_lenders) }} lender(s). 
                                    Continue to complete your full loan application.
                                </p>
                                
                                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                                    <button wire:click="proceedToApplication" 
                                            class="bg-white text-brand-red px-8 py-3 rounded-lg font-bold hover:bg-gray-50 transition-all duration-200 shadow-lg flex items-center justify-center">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                        </svg>
                                        Complete Application
                                    </button>
                                    <button wire:click="clearSelection" 
                                            class="bg-transparent border-2 border-white text-white px-6 py-3 rounded-lg font-bold hover:bg-white hover:text-brand-red transition-all duration-200">
                                        Change Selection
                                    </button>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="bg-yellow-50 border border-yellow-200 rounded-2xl p-8 text-center">
                            <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-yellow-900 mb-2">No Lenders Selected</h3>
                            <p class="text-yellow-800 mb-4">Please select at least one eligible lender to proceed with your application.</p>
                            <button wire:click="selectAllEligible" 
                                    class="bg-yellow-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-yellow-700 transition-colors">
                                Select All Eligible Lenders
                            </button>
                        </div>
                    @endif
                @else
                    <!-- No Products Found -->
                    <div class="bg-white rounded-2xl shadow-sm p-12 text-center border border-gray-100">
                        <div class="w-20 h-20 bg-gradient-to-br from-gray-400 to-gray-600 rounded-lg flex items-center justify-center mx-auto mb-6 shadow-lg">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">No Matching Products Found</h3>
                        <p class="text-gray-500 mb-6">Unfortunately, no loan products match your current criteria. Consider adjusting your loan amount, period, or improving your financial profile.</p>
                        
                        <div class="flex flex-col sm:flex-row gap-3 justify-center">
                            <button wire:click="backToCriteria" 
                                    class="bg-brand-red text-white px-6 py-3 rounded-lg font-semibold hover:bg-brand-dark-red transition-colors">
                                Adjust Criteria
                            </button>
                            <button wire:click="backToCategory" 
                                    class="bg-gray-100 text-gray-700 px-6 py-3 rounded-lg font-semibold hover:bg-gray-200 transition-colors">
                                Try Different Loan Type
                            </button>
                        </div>
                    </div>
                @endif
            </div>
        @endif

      
    </div>
</div>

</div>
