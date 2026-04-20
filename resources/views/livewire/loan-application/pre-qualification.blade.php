<div>
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        {{-- LOAN CATEGORY SELECTION --}}
        @if($currentStep === 'category')
            <div class="mb-8">
                <div class="text-center mb-8">
                    <h1 class="text-4xl font-bold text-gray-900 mb-4">{{ __('loan.find_perfect_loan') }}</h1>
                    <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                        {{ __('loan.choose_loan_type') }}
                    </p>
                </div>

                <!-- Loan Categories Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    @foreach($loanCategories as $category)
                        <div wire:click="selectLoanCategory('{{ $category->slug }}')" 
                             class="bg-white rounded-2xl p-6 border-2 border-gray-100 hover:border-sidebar-green hover:shadow-lg transition-all duration-300 cursor-pointer group {{ $loan_category === $category->name || $loan_category === $category->slug ? 'border-sidebar-green bg-sidebar-green-50' : '' }}">
                            <div class="text-center">
                                <!-- Category Image/Icon -->
                                @if($category->image_path)
                                    <div class="w-16 h-16 mx-auto mb-4 rounded-full overflow-hidden border border-gray-200 bg-white">
                                        <img src="{{ asset('storage/' . $category->image_path) }}"
                                             alt="{{ $category->localized_name }}"
                                             class="w-full h-full object-cover">
                                    </div>
                                @else
                                    <div class="w-16 h-16 mx-auto mb-4 rounded-full flex items-center justify-center {{ ($loan_category === $category->name || $loan_category === $category->slug) ? 'bg-sidebar-green text-white' : 'bg-gray-100 text-gray-600 group-hover:bg-sidebar-green group-hover:text-white' }} transition-colors">
                                        @switch($category->slug)
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
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                                </svg>
                                        @endswitch
                                    </div>
                                @endif

                                <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $category->localized_name }}</h3>
                                <p class="text-sm text-gray-600 leading-relaxed">{{ $category->localized_description ?: $this->getLoanCategoryDescription($category->slug) }}</p>
                                
                                @if($loan_category === $category->name || $loan_category === $category->slug)
                                    <div class="mt-4">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-sidebar-green text-white">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            {{ __('common.selected') }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        {{-- CRITERIA INPUT --}}
        @elseif($currentStep === 'criteria')
            <div class="mb-8">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h1 class="text-4xl font-bold text-gray-900 mb-2">{{ __('loan.loan_requirements') }}</h1>
                        <p class="text-gray-600 text-lg">{{ __('loan.enter_loan_details') }}</p>
                        <div class="mt-2 flex items-center space-x-4 text-sm">
                            @php
                                $selectedCategory = $loanCategories->firstWhere('name', $loan_category) ?? $loanCategories->firstWhere('slug', $loan_category);
                            @endphp
                            <span class="text-gray-500">{{ __('admin.category') }}: <span class="font-medium text-sidebar-green">{{ $selectedCategory->localized_name ?? $loan_category }}</span></span>
                        </div>
                    </div>
                    <button wire:click="backToCategory" class="text-gray-600 hover:text-gray-800 px-4 py-2 rounded-lg hover:bg-gray-100 transition-all duration-200 flex items-center text-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                        {{ __('loan.change_category') }}
                    </button>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Left Column - Input Form -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-sidebar-green to-sidebar-green-light text-white">
                            <h3 class="text-xl font-bold mb-2">{{ __('loan.loan_details') }}</h3>
                            <p class="text-white text-sm opacity-90">{{ __('loan.specify_loan_requirements') }}</p>
                        </div>
                        
                        <div class="p-6 space-y-6">
                            <!-- Loan Amount -->
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">
                                    {{ __('loan.requested_amount') }} (TSh) *
                                </label>
                                <div class="relative">
                                    <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500 font-medium">TSh</span>
                                    <input wire:model.live="requested_amount" 
                                           type="number" 
                                           step="1000" 
                                           min="1000" 
                                           class="w-full pl-12 pr-4 py-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green text-lg font-bold bg-gray-50"
                                           placeholder="50,000">
                                </div>
                                @error('requested_amount') 
                                    <span class="text-sidebar-green text-sm mt-1 block">{{ $message }}</span> 
                                @enderror
                            </div>

                            <!-- Loan Period -->
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">
                                    {{ __('loan.loan_period') }} ({{ __('dashboard.months') }}) *
                                </label>
                                <select wire:model.live="requested_tenure" 
                                        class="w-full px-4 py-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green text-lg font-bold bg-gray-50">
                                    <option value="">{{ __('loan.select_period') }}</option>
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
                                    <span class="text-sidebar-green text-sm mt-1 block">{{ $message }}</span> 
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
                                            class="text-sm {{ $use_profile_data ? 'text-green-600' : 'text-gray-600' }} hover:text-green-800 flex items-center transition-colors">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $use_profile_data ? 'M5 13l4 4L19 7' : 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z' }}"/>
                                        </svg>
                                        {{ $use_profile_data ? 'Using Profile Data (Editable)' : 'Load Profile Data' }}
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
                                           class="w-full pl-12 pr-4 py-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green text-lg font-bold bg-gray-50"
                                           placeholder="500,000">
                                </div>
                                @error('monthly_income') 
                                    <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span> 
                                @enderror
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
                                           class="w-full pl-12 pr-4 py-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green text-lg font-bold bg-gray-50"
                                           placeholder="0">
                                </div>
                                @error('existing_loans') 
                                    <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span> 
                                @enderror
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
                                        class="w-full bg-gradient-to-r from-sidebar-green to-sidebar-green-light text-white py-4 px-6 rounded-lg font-bold hover:from-sidebar-green-light hover:to-sidebar-green-dark transition-all duration-200 shadow-lg shadow-sidebar-green/25 flex items-center justify-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                    </svg>
                                    Find Matching Products
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
                        <h1 class="text-4xl font-bold text-gray-900 mb-2">Eligible Loan Products</h1>
                        <p class="text-gray-600 text-lg">Found {{ count($availableProducts) }} products that match your criteria and DSR requirements</p>
                        <div class="mt-2 flex items-center space-x-4 text-sm">
                            <span class="text-gray-500">Amount: <span class="font-medium text-sidebar-green">TSh {{ number_format($requested_amount) }}</span></span>
                            <span class="text-gray-500">Period: <span class="font-medium text-gray-900">{{ $requested_tenure }} months</span></span>
                            <span class="text-gray-500">Income: <span class="font-medium text-green-600">TSh {{ number_format($monthly_income) }}</span></span>
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
                            @php
                                $eligibleCount = collect($availableProducts)->where('eligible', true)->count();
                                $ineligibleCount = collect($availableProducts)->where('eligible', false)->count();
                            @endphp
                            <div class="text-sm text-gray-600">
                                <div class="mb-2">
                                    <strong>Your criteria:</strong><br>
                                    Amount: TSh {{ number_format($requested_amount) }} |
                                    Period: {{ $requested_tenure }} months |
                                    Income: TSh {{ number_format($monthly_income) }}
                                </div>
                                <div>
                                    {{ count($availableProducts) }} products found
                                    @if($eligibleCount > 0)
                                        - {{ $eligibleCount }} match your criteria
                                    @endif
                                    @if($ineligibleCount > 0 && $eligibleCount > 0)
                                        , {{ $ineligibleCount }} shown for reference
                                    @elseif($ineligibleCount > 0 && $eligibleCount === 0)
                                        - none match, showing similar options
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="flex flex-wrap items-center gap-3">
                            <!-- Sort Options -->
                            <select wire:model.live="sort_by" 
                                    class="bg-gray-50 border border-gray-200 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green">
                                <option value="score">Best Match</option>
                                <option value="interest_rate">Lowest Rate</option>
                                <option value="processing_time">Fastest Processing</option>
                            </select>
                            
                            <!-- Selection Controls -->
                            <button wire:click="selectAllEligible" 
                                    class="bg-blue-100 text-blue-700 px-4 py-2 rounded-lg font-medium hover:bg-blue-200 transition-colors text-sm">
                                Select Best from Each Lender
                            </button>
                            <button wire:click="clearSelection" 
                                    class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg font-medium hover:bg-gray-200 transition-colors text-sm">
                                Clear Selection
                            </button>
                        </div>
                    </div>
                    
                    <!-- Selected Products Display -->
                    @if(!empty($selected_products))
                        <div class="mt-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                            <p class="text-sm font-medium text-green-800 mb-2">
                                {{ count($selected_products) }} product(s) selected from {{ $this->getSelectedLendersCount() }} lender(s)
                            </p>
                            <div class="flex flex-wrap gap-2">
                                @foreach($availableProducts as $product)
                                    @if(in_array($product['product_id'], $selected_products))
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                            {{ $product['lender_name'] }} - {{ $product['product_name'] }}
                                            <button wire:click="selectProduct('{{ $product['product_id'] }}')" class="ml-2 text-green-600 hover:text-green-800">
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
                                $isSelected = in_array($product['product_id'], $selected_products);
                                $isLenderSelected = $product['is_lender_selected'] ?? false;
                                $isTopMatch = $index === 0 && $product['eligible'];
                                $isDisabled = ($isLenderSelected && !$isSelected) || !$product['eligible'];
                                $isEligible = $product['eligible'] ?? false;
                            @endphp
                            
                            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg transition-all duration-300 
                                        {{ $isSelected ? 'ring-2 ring-sidebar-green' : '' }}
                                        {{ $isDisabled || !$isEligible ? 'opacity-75' : '' }}
                                        {{ !$isEligible ? 'border-red-200' : '' }}">
                                
                                <!-- Product Header -->
                                <div class="p-6 {{ $isTopMatch ? 'bg-gradient-to-r from-sidebar-green to-sidebar-green-light text-white' : 'bg-gradient-to-r from-gray-50 to-gray-100' }}">
                                    <div class="flex items-center justify-between mb-3">
                                        <div>
                                            <h3 class="text-xl font-bold {{ $isTopMatch ? 'text-white' : 'text-gray-900' }}">
                                                {{ $product['lender_name'] }}
                                            </h3>
                                            <p class="text-sm {{ $isTopMatch ? 'text-sidebar-green-100' : 'text-gray-600' }}">
                                                {{ $product['product_name'] }}
                                            </p>
                                        </div>
                                        <div class="flex items-center space-x-2 flex-wrap">
                                            @if(!$isEligible)
                                                <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-xs font-bold">NOT ELIGIBLE</span>
                                            @elseif($isTopMatch)
                                                <span class="bg-white text-sidebar-green px-3 py-1 rounded-full text-xs font-bold">BEST MATCH</span>
                                            @endif
                                            @if($isLenderSelected && !$isSelected && $isEligible)
                                                <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-xs font-bold">LENDER SELECTED</span>
                                            @endif
                                            <!-- Security Type Badge -->
                                            <span class="px-3 py-1 rounded-full text-xs font-bold {{ $product['is_secured'] ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800' }}">
                                                {{ $product['is_secured'] ? 'SECURED' : 'UNSECURED' }}
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <!-- Key Metrics -->
                                    <div class="grid grid-cols-3 gap-4">
                                        <div>
                                            <p class="text-xs {{ $isTopMatch ? 'text-sidebar-green-200' : 'text-gray-500' }}">Eligibility Score</p>
                                            <p class="text-lg font-bold {{ $isTopMatch ? 'text-white' : 'text-gray-900' }}">{{ number_format($product['eligibility_score'], 1) }}%</p>
                                        </div>
                                        <div>
                                            <p class="text-xs {{ $isTopMatch ? 'text-sidebar-green-200' : 'text-gray-500' }}">Interest Rate</p>
                                            <p class="text-lg font-bold {{ $isTopMatch ? 'text-white' : 'text-gray-900' }}">
                                                {{ $product['interest_rate_min'] }}%
                                                @if($product['interest_rate_min'] != $product['interest_rate_max'])
                                                    <span class="text-sm">- {{ $product['interest_rate_max'] }}%</span>
                                                @endif
                                            </p>
                                        </div>
                                        <div>
                                            <p class="text-xs {{ $isTopMatch ? 'text-sidebar-green-200' : 'text-gray-500' }}">Monthly Payment</p>
                                            <p class="text-lg font-bold {{ $isTopMatch ? 'text-white' : 'text-gray-900' }}">
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
                                                <p class="font-medium text-green-600">
                                                    {{ number_format($product['dsr'], 1) }}%
                                                </p>
                                            </div>
                                            <div>
                                                <span class="text-gray-500">Max DSR:</span>
                                                <p class="font-medium text-gray-900">{{ $product['max_dsr_allowed'] }}%</p>
                                            </div>
                                        </div>

                                        <!-- Amount Range -->
                                        <div class="bg-blue-50 rounded-lg p-3">
                                            <div class="flex items-center space-x-2 mb-2">
                                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                                </svg>
                                                <span class="text-sm font-medium text-blue-800">Loan Range</span>
                                            </div>
                                            <p class="text-sm text-blue-700">
                                                TSh {{ number_format($product['min_amount']) }} - TSh {{ number_format($product['max_amount']) }}
                                            </p>
                                            <p class="text-xs text-blue-600 mt-1">
                                                {{ $product['min_tenure_months'] }} - {{ $product['max_tenure_months'] }} months
                                            </p>
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
                                            <div class="bg-red-50 rounded-lg p-3 border border-red-200">
                                                <div class="flex items-start space-x-2 mb-2">
                                                    <svg class="w-4 h-4 text-red-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                                    </svg>
                                                    <div class="flex-1">
                                                        <span class="text-red-800 font-medium text-sm block mb-1">Not Eligible - Criteria Not Met</span>
                                                        @if(!empty($product['eligibility_issues']))
                                                            <ul class="text-xs text-red-700 space-y-1 mt-1">
                                                                @foreach($product['eligibility_issues'] as $issue)
                                                                    <li class="flex items-start">
                                                                        <span class="mr-1">•</span>
                                                                        <span>{{ $issue }}</span>
                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                        @else
                                                            <p class="text-xs text-red-600 mt-1">You do not meet the eligibility criteria for this product.</p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        <!-- Action Buttons -->
                                        <div class="flex space-x-3 pt-2">
                                            <!-- Selection Checkbox -->
                                            <label class="flex items-center flex-1 {{ !$isEligible || $isDisabled ? 'cursor-not-allowed opacity-60' : 'cursor-pointer' }}">
                                                <input type="checkbox"
                                                       wire:click="selectProduct('{{ $product['product_id'] }}')"
                                                       {{ $isSelected ? 'checked' : '' }}
                                                       {{ !$isEligible || $isDisabled ? 'disabled' : '' }}
                                                       class="text-sidebar-green focus:ring-sidebar-green rounded {{ !$isEligible || $isDisabled ? 'cursor-not-allowed' : '' }}">
                                                <span class="ml-3 text-sm font-medium {{ !$isEligible ? 'text-gray-500' : 'text-gray-700' }}">
                                                    @if(!$isEligible)
                                                        Cannot select - criteria not met
                                                    @elseif($isDisabled)
                                                        Another product selected from this lender
                                                    @else
                                                        Select for application
                                                    @endif
                                                </span>
                                            </label>
                                            
                                            <button wire:click="viewMatchingCriteria('{{ $product['product_id'] }}')"
                                                    class="text-amber-600 hover:text-amber-700 text-sm font-medium flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                                                {{ __('matching.view_matching_criteria') }}
                                            </button>
                                            <button wire:click="showProductDetails('{{ $product['product_id'] }}')"
                                                    class="text-sidebar-green hover:text-sidebar-green-light text-sm font-medium flex items-center {{ !$isEligible ? 'opacity-60' : '' }}">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                Details
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Proceed to Application -->
                    @if(!empty($selected_products))
                        <div class="bg-gradient-to-r from-sidebar-green to-sidebar-green-light rounded-2xl shadow-lg p-8 text-white text-center">
                            <div class="max-w-2xl mx-auto">
                                <h3 class="text-2xl font-bold mb-2">Ready to Apply?</h3>
                                <p class="text-sidebar-green-100 mb-6">
                                    You've selected {{ count($selected_products) }} product(s) from {{ $this->getSelectedLendersCount() }} lender(s). 
                                    Continue to complete your full loan application.
                                </p>
                                
                                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                                    <button wire:click="proceedToApplication" 
                                            class="bg-white text-sidebar-green px-8 py-3 rounded-lg font-bold hover:bg-gray-50 transition-all duration-200 shadow-lg flex items-center justify-center">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                        </svg>
                                        Complete Application
                                    </button>
                                    <button wire:click="clearSelection" 
                                            class="bg-transparent border-2 border-white text-white px-6 py-3 rounded-lg font-bold hover:bg-white hover:text-sidebar-green transition-all duration-200">
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
                            <h3 class="text-xl font-bold text-yellow-900 mb-2">No Products Selected</h3>
                            <p class="text-yellow-800 mb-4">Please select at least one product to proceed with your application.</p>
                            <button wire:click="selectAllEligible" 
                                    class="bg-yellow-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-yellow-700 transition-colors">
                                Select Best from Each Lender
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
                        <h3 class="text-xl font-bold text-gray-900 mb-2">No Eligible Products Found</h3>
                        <p class="text-gray-500 mb-6">Unfortunately, no loan products match your current financial profile and DSR requirements. Consider adjusting your loan amount, period, or improving your debt-to-income ratio.</p>
                        
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6 text-left max-w-md mx-auto">
                            <h4 class="font-semibold text-blue-900 mb-2">Suggestions to improve eligibility:</h4>
                            <ul class="text-sm text-blue-800 space-y-1">
                                <li>• Reduce the loan amount</li>
                                <li>• Extend the loan period (lower monthly payments)</li>
                                <li>• Pay down existing debts to improve DSR</li>
                                <li>• Consider a different loan category</li>
                                <li>• Provide additional income sources</li>
                            </ul>
                        </div>
                        
                        <div class="flex flex-col sm:flex-row gap-3 justify-center">
                            <button wire:click="backToCriteria" 
                                    class="bg-sidebar-green text-white px-6 py-3 rounded-lg font-semibold hover:bg-sidebar-green-light transition-colors">
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

<!-- Product Details Modal -->
@if($showProductDetails && $selectedProductForDetails)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
        <div class="bg-white rounded-2xl shadow-2xl max-w-4xl w-full max-h-screen overflow-y-auto">
            <!-- Modal Header -->
            <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-sidebar-green to-sidebar-green-light text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-2xl font-bold">{{ $selectedProductForDetails['product_name'] }}</h2>
                        <p class="text-sidebar-green-100">{{ $selectedProductForDetails['lender_name'] }}</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <span class="px-3 py-1 rounded-full text-xs font-bold {{ $selectedProductForDetails['is_secured'] ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800' }}">
                            {{ $selectedProductForDetails['is_secured'] ? 'SECURED' : 'UNSECURED' }}
                        </span>
                        <button wire:click="closeProductDetails" class="text-white hover:text-sidebar-green-200 p-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Modal Content -->
            <div class="p-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Left Column - Key Details -->
                    <div class="space-y-6">
                        <!-- Interest & Payment Info -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h3 class="font-bold text-gray-900 mb-3">Interest & Payment Details</h3>
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Interest Rate Range:</span>
                                    <span class="font-semibold">{{ $selectedProductForDetails['interest_rate_min'] }}% - {{ $selectedProductForDetails['interest_rate_max'] }}%</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Monthly Payment:</span>
                                    <span class="font-semibold text-sidebar-green">TSh {{ number_format($selectedProductForDetails['monthly_payment']) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Your DSR:</span>
                                    <span class="font-semibold text-green-600">{{ number_format($selectedProductForDetails['dsr'], 1) }}%</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Maximum DSR:</span>
                                    <span class="font-semibold">{{ $selectedProductForDetails['max_dsr_allowed'] }}%</span>
                                </div>
                            </div>
                        </div>

                        <!-- Loan Limits -->
                        <div class="bg-blue-50 rounded-lg p-4">
                            <h3 class="font-bold text-gray-900 mb-3">Loan Limits</h3>
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Amount Range:</span>
                                    <span class="font-semibold">TSh {{ number_format($selectedProductForDetails['min_amount']) }} - TSh {{ number_format($selectedProductForDetails['max_amount']) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Tenure Range:</span>
                                    <span class="font-semibold">{{ $selectedProductForDetails['min_tenure_months'] }} - {{ $selectedProductForDetails['max_tenure_months'] }} months</span>
                                </div>
                            </div>
                        </div>

                        <!-- Processing Info -->
                        <div class="bg-green-50 rounded-lg p-4">
                            <h3 class="font-bold text-gray-900 mb-3">Processing Timeline</h3>
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Approval Time:</span>
                                    <span class="font-semibold">{{ $selectedProductForDetails['approval_time_days'] }} days</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Disbursement Time:</span>
                                    <span class="font-semibold">{{ $selectedProductForDetails['disbursement_time_days'] }} days</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Processing Fee:</span>
                                    <span class="font-semibold">
                                        @if($selectedProductForDetails['processing_fee_percentage'] > 0)
                                            {{ $selectedProductForDetails['processing_fee_percentage'] }}%
                                        @endif
                                        @if($selectedProductForDetails['processing_fee_fixed'] > 0)
                                            @if($selectedProductForDetails['processing_fee_percentage'] > 0) + @endif
                                            TSh {{ number_format($selectedProductForDetails['processing_fee_fixed']) }}
                                        @endif
                                        @if($selectedProductForDetails['processing_fee_percentage'] == 0 && $selectedProductForDetails['processing_fee_fixed'] == 0)
                                            Free
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column - Additional Details -->
                    <div class="space-y-6">
                        <!-- Product Description -->
                        @if(!empty($selectedProductForDetails['description']))
                            <div>
                                <h3 class="font-bold text-gray-900 mb-3">Product Description</h3>
                                <p class="text-gray-600 leading-relaxed">{{ $selectedProductForDetails['description'] }}</p>
                            </div>
                        @endif

                        <!-- Features -->
                        @if(!empty($selectedProductForDetails['features']))
                            <div>
                                <h3 class="font-bold text-gray-900 mb-3">Key Features</h3>
                                <ul class="space-y-2">
                                    @foreach($selectedProductForDetails['features'] as $feature)
                                        <li class="flex items-start space-x-2">
                                            <svg class="w-4 h-4 text-green-600 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            <span class="text-gray-600">{{ $feature }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- Collateral Requirements -->
                        @if($selectedProductForDetails['collateral_required'] && !empty($selectedProductForDetails['collateral_requirements']))
                            <div>
                                <h3 class="font-bold text-gray-900 mb-3">Collateral Requirements</h3>
                                <ul class="space-y-2">
                                    @foreach($selectedProductForDetails['collateral_requirements'] as $requirement)
                                        <li class="flex items-start space-x-2">
                                            <svg class="w-4 h-4 text-yellow-600 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                            </svg>
                                            <span class="text-gray-600">{{ $requirement }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- Required Documents -->
                        @if(!empty($selectedProductForDetails['required_documents']))
                            <div>
                                <h3 class="font-bold text-gray-900 mb-3">Required Documents</h3>
                                <ul class="space-y-2">
                                    @foreach($selectedProductForDetails['required_documents'] as $document)
                                        <li class="flex items-start space-x-2">
                                            <svg class="w-4 h-4 text-blue-600 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                            <span class="text-gray-600">{{ $document }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Modal Actions -->
                <div class="mt-8 flex flex-col sm:flex-row gap-4 justify-center pt-6 border-t border-gray-200">
                    <button wire:click="selectProduct('{{ $selectedProductForDetails['product_id'] }}')"
                            {{ !($selectedProductForDetails['can_apply'] ?? true) ? 'disabled' : '' }}
                            class="bg-sidebar-green text-white px-8 py-3 rounded-lg font-bold hover:bg-sidebar-green-light transition-colors flex items-center justify-center {{ !($selectedProductForDetails['can_apply'] ?? true) ? 'opacity-50 cursor-not-allowed' : '' }}">
                        @if(in_array($selectedProductForDetails['product_id'], $selected_products))
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Selected for Application
                        @else
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Select for Application
                        @endif
                    </button>
                    <button wire:click="closeProductDetails"
                            class="bg-gray-100 text-gray-700 px-6 py-3 rounded-lg font-semibold hover:bg-gray-200 transition-colors">
                        Close Details
                    </button>
                </div>
            </div>
        </div>
    </div>
@endif

{{-- View matching criteria modal (where you match / where you don't match) --}}
@if($showCriteriaModal && $selectedMatchItem)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50" wire:click.self="closeCriteriaModal">
        <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b border-gray-200 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-bold text-gray-900">{{ $selectedMatchItem['product_name'] }}</h3>
                    <p class="text-sm text-gray-500">{{ $selectedMatchItem['lender_name'] }}</p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="px-3 py-1 rounded-full text-sm font-bold {{ ($selectedMatchItem['match_percent'] ?? 0) >= 70 ? 'bg-green-100 text-green-800' : (($selectedMatchItem['match_percent'] ?? 0) >= 40 ? 'bg-amber-100 text-amber-800' : 'bg-gray-100 text-gray-700') }}">
                        {{ $selectedMatchItem['match_percent'] ?? 0 }}% {{ __('matching.match_percent') }}
                    </span>
                    <button type="button" wire:click="closeCriteriaModal" class="p-2 text-gray-400 hover:text-gray-600 rounded-lg hover:bg-gray-100">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>
            <div class="p-6 space-y-6">
                <div>
                    <h4 class="text-sm font-bold text-green-700 mb-2 flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        {{ __('matching.where_you_match') }}
                    </h4>
                    @if(!empty($selectedMatchItem['matched_criteria']))
                        <ul class="space-y-1.5">
                            @foreach($selectedMatchItem['matched_criteria'] as $c)
                                <li class="text-sm text-gray-800 flex items-start"><span class="text-green-500 mr-2">•</span>{{ $c }}</li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-sm text-gray-500">No matching criteria yet.</p>
                    @endif
                </div>
                <div>
                    <h4 class="text-sm font-bold text-red-700 mb-2 flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        {{ __('matching.where_you_dont_match') }}
                    </h4>
                    @if(!empty($selectedMatchItem['unmatched_criteria']))
                        <ul class="space-y-1.5">
                            @foreach($selectedMatchItem['unmatched_criteria'] as $c)
                                <li class="text-sm text-gray-800 flex items-start"><span class="text-red-500 mr-2">•</span>{{ $c }}</li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-sm text-gray-500">No criteria missing.</p>
                    @endif
                </div>
                @if(!empty($selectedMatchItem['product_info'] ?? []))
                    <div>
                        <h4 class="text-sm font-bold text-gray-700 mb-2 flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            {{ __('matching.about_this_product') }}
                        </h4>
                        <ul class="space-y-1.5">
                            @foreach($selectedMatchItem['product_info'] as $info)
                                <li class="text-sm text-gray-600 flex items-start"><span class="text-gray-400 mr-2">•</span>{{ $info }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @if(empty($selectedMatchItem['can_apply']))
                    <p class="text-sm text-amber-700 bg-amber-50 p-3 rounded-lg">{{ __('matching.cannot_apply') }}</p>
                @endif
            </div>
        </div>
    </div>
@endif

</div>