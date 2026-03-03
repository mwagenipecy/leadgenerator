<div class="w-full">
    <!-- Form Header with Progress -->
    <div class="mb-8">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-4xl font-bold text-black mb-2">
                    {{ $mode === 'create' ? __('admin.create_new_loan_product') : __('admin.edit_loan_product') }}
                </h1>
                <p class="text-gray-600 text-lg">{{ __('admin.step_of', ['current' => $currentStep, 'total' => 4]) }} - {{ __('admin.complete_all_steps') }}</p>
            </div>
            <div class="flex items-center space-x-3">
                <button wire:click="cancel" class="text-black hover:text-gray-600 p-2 rounded-lg hover:bg-gray-100 transition-all duration-200" title="Cancel">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Progress Steps -->
        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                @for($i = 1; $i <= 4; $i++)
                    <div class="flex items-center {{ $i < 4 ? 'flex-1' : '' }}">
                        <div class="flex items-center space-x-3">
                            <button wire:click="goToStep({{ $i }})" class="w-12 h-12 rounded-full flex items-center justify-center font-bold text-sm transition-all duration-200 relative
                                {{ $currentStep >= $i ? 'bg-sidebar-green text-white shadow-lg' : 'bg-gray-200 text-gray-600 hover:bg-gray-300' }}">
                                @if($currentStep > $i)
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                @else
                                    {{ $i }}
                                @endif
                            </button>
                            <div class="hidden sm:block">
                                <p class="text-sm font-bold {{ $currentStep >= $i ? 'text-sidebar-green' : 'text-gray-500' }}">
                                    @switch($i)
                                        @case(1) {{ __('admin.basic_information') }} @break
                                        @case(2) {{ __('admin.amount_terms') }} @break
                                        @case(3) {{ __('admin.eligibility_criteria') }} @break
                                        @case(4) {{ __('admin.requirements_fees') }} @break
                                    @endswitch
                                </p>
                                <p class="text-xs text-gray-400">
                                    @switch($i)
                                        @case(1) {{ __('admin.product_details_features') }} @break
                                        @case(2) {{ __('admin.loan_limits_interest_rates') }} @break
                                        @case(3) {{ __('admin.who_can_apply') }} @break
                                        @case(4) {{ __('admin.documents_charges') }} @break
                                    @endswitch
                                </p>
                            </div>
                        </div>
                        @if($i < 4)
                            <div class="flex-1 h-1 mx-4 rounded-full {{ $currentStep > $i ? 'bg-sidebar-green' : 'bg-gray-200' }}"></div>
                        @endif
                    </div>
                @endfor
            </div>
        </div>
    </div>

    <!-- Form Content -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
        <form wire:submit="saveProduct">
            <!-- Step 1: Basic Information -->
            @if($currentStep === 1)
                <div class="p-8">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-2xl font-bold text-black">{{ __('admin.basic_information') }}</h2>
                        <div class="bg-sidebar-green-50 px-4 py-2 rounded-full">
                            <span class="text-sidebar-green-light text-sm font-medium">{{ __('admin.step_of', ['current' => 1, 'total' => 4]) }}</span>
                        </div>
                    </div>
                    
                    <div class="space-y-8">
                        <!-- Product Name & Status -->
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                            <div class="lg:col-span-2">
                                <label class="block text-sm font-medium text-black mb-2">{{ __('admin.product_name') }} *</label>
                                <input wire:model.live="name" type="text" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green text-lg font-medium" placeholder="e.g., Personal Loan, Business Loan, Emergency Loan">
                                @error('name') <span class="text-sidebar-green text-sm mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-black mb-2">Status</label>
                                <div class="flex items-center space-x-4 pt-3">
                                    <label class="flex items-center cursor-pointer">
                                        <input wire:model.live="is_active" type="radio" value="1" class="text-sidebar-green focus:ring-sidebar-green">
                                        <span class="ml-2 text-sm font-medium text-black">Active</span>
                                    </label>
                                    <label class="flex items-center cursor-pointer">
                                        <input wire:model.live="is_active" type="radio" value="0" class="text-sidebar-green focus:ring-sidebar-green">
                                        <span class="ml-2 text-sm font-medium text-black">Inactive</span>
                                    </label>
                                </div>
                            </div>



                        </div>

                        <!-- Loan Type & Promotional Tag -->
                        <div class="flex space-x-4">
                            <div class="w-1/2">
                                <label class="block text-sm font-medium text-black mb-2">{{ __('admin.loan_category') }} *</label>
                                <select wire:model.live="loan_category_id" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green">
                                    <option value="">{{ __('admin.select_loan_category') }}</option>
                                    @foreach($loanCategories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                @error('loan_category_id') <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div class="w-1/2">
                                <label class="block text-sm font-medium text-black mb-2">{{ __('admin.promotional_tag') }}</label>
                                <input wire:model.live="promotional_tag" type="text" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green" placeholder="e.g., Best Rate, Quick Approval, No Collateral">
                                @error('promotional_tag') <span class="text-sidebar-green text-sm mt-1 block">{{ $message }}</span> @enderror
                                <p class="text-sm text-gray-500 mt-1">This will appear as a badge on your product card</p>
                            </div>


                            
                        </div>



                        <div>
                                <label class="block text-sm font-medium text-black mb-2">{{ __('admin.loan_type') }} *</label>
                                <div class="flex items-center space-x-4 pt-3">
                                    <label class="flex items-center cursor-pointer">
                                        <input wire:model.live="loan_type" type="radio" name="loan_type" value="unsecured" class="text-sidebar-green focus:ring-sidebar-green">
                                        <span class="ml-2 text-sm font-medium text-black">Unsecured</span>
                                    </label>
                                    <label class="flex items-center cursor-pointer">
                                        <input wire:model.live="loan_type" type="radio" name="loan_type" value="secured" class="text-sidebar-green focus:ring-sidebar-green">
                                        <span class="ml-2 text-sm font-medium text-black">Secured</span>
                                    </label>
                                </div>

                                @error('loan_type') <span class="text-sidebar-green text-sm mt-1 block">{{ $message }}</span> @enderror


                            </div>


                        <!-- Description -->
                        <div>
                            <label class="block text-sm font-medium text-black mb-2">{{ __('admin.product_description') }}</label>
                            <textarea wire:model.live="description" rows="4" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green" placeholder="Describe your loan product, its benefits, and who it's designed for..."></textarea>
                            @error('description') <span class="text-sidebar-green text-sm mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Key Features -->
                        <div>
                            <label class="block text-sm font-medium text-black mb-2">{{ __('admin.key_features') }}</label>
                            <div class="space-y-4">
                                <div class="flex items-center space-x-3">
                                    <input wire:model.live="newKeyFeature" wire:keydown.enter="addKeyFeature" type="text" class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green" placeholder="Add a key feature (e.g., No hidden fees, Fast approval)">
                                    <button type="button" wire:click="addKeyFeature" class="bg-sidebar-green text-white px-6 py-3 rounded-lg hover:bg-sidebar-green-light transition-colors font-semibold">
                                        Add Feature
                                    </button>
                                </div>
                                
                                @if(count($key_features) > 0)
                                    <div class="bg-gray-50 rounded-lg p-4">
                                        <h4 class="text-sm font-medium text-black mb-3">{{ __('admin.added_features') }}</h4>
                                        <div class="flex flex-wrap gap-2">
                                            @foreach($key_features as $index => $feature)
                                                <span class="inline-flex items-center bg-green-100 text-green-800 px-3 py-2 rounded-full text-sm font-medium">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                    </svg>
                                                    {{ $feature }}
                                                    <button type="button" wire:click="removeKeyFeature({{ $index }})" class="ml-2 text-green-600 hover:text-green-800">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                        </svg>
                                                    </button>
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                                <p class="text-sm text-gray-500">Add key selling points that make your loan product attractive to customers</p>
                            </div>
                        </div>

                        <!-- Regions: where product is shown and can lend -->
                        <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                            <h3 class="text-lg font-bold text-black mb-2">{{ __('admin.regions_product_available') }}</h3>
                            <p class="text-sm text-gray-600 mb-4">{{ __('admin.regions_product_available_hint') }}</p>
                            <div class="flex flex-wrap gap-2 mb-4">
                                <button type="button" wire:click="selectAllRegions" class="text-sm px-3 py-1.5 rounded-lg bg-sidebar-green/10 text-sidebar-green hover:bg-sidebar-green/20 font-medium">
                                    {{ __('admin.select_all_regions') }}
                                </button>
                                <button type="button" wire:click="clearRegions" class="text-sm px-3 py-1.5 rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300 font-medium">
                                    {{ __('admin.clear_regions') }}
                                </button>
                            </div>
                            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3 max-h-48 overflow-y-auto">
                                @foreach($regions as $region)
                                    <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-white cursor-pointer transition-all duration-200 {{ in_array($region->id, $selectedRegions) ? 'bg-sidebar-green-50 border-sidebar-green-300 text-sidebar-green-light' : '' }}">
                                        <input wire:click="toggleRegion({{ $region->id }})" type="checkbox" {{ in_array($region->id, $selectedRegions) ? 'checked' : '' }} class="text-sidebar-green focus:ring-sidebar-green rounded">
                                        <span class="ml-2 text-sm font-medium truncate" title="{{ $region->name }}">{{ $region->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                            @if(count($selectedRegions) > 0)
                                <p class="text-sm text-gray-500 mt-3">{{ __('admin.regions_selected_count', ['count' => count($selectedRegions)]) }}</p>
                            @else
                                <p class="text-sm text-gray-500 mt-3">{{ __('admin.regions_all_if_empty') }}</p>
                            @endif
                        </div>
                    </div>
                </div>

            <!-- Step 2: Amount & Terms -->
            @elseif($currentStep === 2)
                <div class="p-8">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-2xl font-bold text-black">Amount & Terms</h2>
                        <div class="bg-sidebar-green-50 px-4 py-2 rounded-full">
                            <span class="text-sidebar-green-light text-sm font-medium">{{ __('admin.step_of', ['current' => 2, 'total' => 4]) }}</span>
                        </div>
                    </div>
                    
                    <div class="space-y-8">
                        <!-- Loan Amount Range -->
                        <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                            <h3 class="text-lg font-bold text-black mb-4 flex items-center">
                                <svg class="w-6 h-6 text-sidebar-green mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                </svg>
                                Loan Amount Range
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-black mb-2">{{ __('admin.minimum_amount') }} *</label>
                                    <input wire:model.live="min_amount" type="number" step="1000" min="1000" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green text-lg font-bold">
                                    @error('min_amount') <span class="text-sidebar-green text-sm mt-1 block">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-black mb-2">{{ __('admin.maximum_amount') }} *</label>
                                    <input wire:model.live="max_amount" type="number" step="1000" min="1000" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green text-lg font-bold">
                                    @error('max_amount') <span class="text-sidebar-green text-sm mt-1 block">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            @if($min_amount && $max_amount && $min_amount <= $max_amount)
                                <div class="mt-4 p-3 bg-white rounded-lg border border-gray-200">
                                    <p class="text-sm text-sidebar-green-light">
                                        <strong>Range Preview:</strong> TSh {{ number_format($min_amount) }} - TSh {{ number_format($max_amount) }}
                                    </p>
                                </div>
                            @endif
                        </div>

                        <!-- Loan Tenure Range -->
                        <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                            <h3 class="text-lg font-bold text-black mb-4 flex items-center">
                                <svg class="w-6 h-6 text-sidebar-green mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                Loan Tenure Range
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-black mb-2">{{ __('admin.minimum_tenure') }} *</label>
                                    <input wire:model.live="min_tenure_months" type="number" min="1" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green text-lg font-bold">
                                    @error('min_tenure_months') <span class="text-sidebar-green text-sm mt-1 block">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-black mb-2">{{ __('admin.maximum_tenure') }} *</label>
                                    <input wire:model.live="max_tenure_months" type="number" min="1" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green text-lg font-bold">
                                    @error('max_tenure_months') <span class="text-sidebar-green text-sm mt-1 block">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Interest Rates -->
                        <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                            <h3 class="text-lg font-bold text-black mb-4 flex items-center">
                                <svg class="w-6 h-6 text-sidebar-green mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                </svg>
                                Interest Rates
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-black mb-2">{{ __('admin.minimum_rate') }} *</label>
                                    <input wire:model.live="interest_rate_min" type="number" step="0.01" min="0" max="100" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green text-lg font-bold">
                                    @error('interest_rate_min') <span class="text-sidebar-green text-sm mt-1 block">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-black mb-2">{{ __('admin.maximum_rate') }} *</label>
                                    <input wire:model.live="interest_rate_max" type="number" step="0.01" min="0" max="100" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green text-lg font-bold">
                                    @error('interest_rate_max') <span class="text-sidebar-green text-sm mt-1 block">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-black mb-2">{{ __('admin.interest_type') }} *</label>
                                    <select wire:model.live="interest_type" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green text-lg font-bold">
                                        <option value="reducing">Reducing Balance</option>
                                        <option value="fixed">Fixed Rate</option>
                                    </select>
                                    @error('interest_type') <span class="text-sidebar-green text-sm mt-1 block">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Processing Timeline & DSR -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                                <h3 class="text-lg font-bold text-black mb-4">{{ __('admin.processing_timeline') }}</h3>
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-black mb-2">{{ __('admin.approval_time') }} *</label>
                                        <input wire:model.live="approval_time_days" type="number" min="1" max="90" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green text-lg font-bold">
                                        @error('approval_time_days') <span class="text-sidebar-green text-sm mt-1 block">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-black mb-2">{{ __('admin.disbursement_time') }} *</label>
                                        <input wire:model.live="disbursement_time_days" type="number" min="1" max="30" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green text-lg font-bold">
                                        @error('disbursement_time_days') <span class="text-sidebar-green text-sm mt-1 block">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                                <h3 class="text-lg font-bold text-black mb-4">Maximum DSR</h3>
                                <div>
                                    <label class="block text-sm font-medium text-black mb-2">Maximum DSR (%) *</label>
                                    <input wire:model.live="minimum_dsr" type="number" min="0" max="100" step="0.1" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green text-lg font-bold">
                                    @error('minimum_dsr') <span class="text-sidebar-green text-sm mt-1 block">{{ $message }}</span> @enderror
                                    <p class="text-sm text-gray-500 mt-1">What is the maximum Debt Service Ratio required for approval?</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            <!-- Step 3: Eligibility Criteria -->
            @elseif($currentStep === 3)
                <div class="p-8">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-2xl font-bold text-black">{{ __('admin.eligibility_criteria') }}</h2>
                        <div class="bg-sidebar-green-50 px-4 py-2 rounded-full">
                            <span class="text-sidebar-green-light text-sm font-medium">{{ __('admin.step_of', ['current' => 3, 'total' => 4]) }}</span>
                        </div>
                    </div>
                    
                    <div class="space-y-8">
                        <!-- Employment Requirements -->
                        <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                            <h3 class="text-lg font-bold text-black mb-4">{{ __('admin.employment_requirements') }}</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-black mb-2">{{ __('admin.employment_type') }} *</label>
                                    <select wire:model.live="employment_requirement" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green font-medium">
                                        <option value="all">{{ __('admin.all_employment_types') }}</option>
                                        <option value="employed">Employed</option>
                                        <option value="business">Business</option>
                                    </select>
                                    @error('employment_requirement') <span class="text-sidebar-green text-sm mt-1 block">{{ $message }}</span> @enderror
                                </div>
                                @if($employment_requirement === 'employed')
                                    <div>
                                        <label class="block text-sm font-medium text-black mb-2">{{ __('admin.minimum_employment_period') }}</label>
                                        <input wire:model.live="min_employment_months" type="number" min="1" max="120" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green font-medium">
                                        @error('min_employment_months') <span class="text-sidebar-green text-sm mt-1 block">{{ $message }}</span> @enderror
                                        <p class="text-sm text-gray-500 mt-1">How long must they be employed?</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Age Requirements -->
                        <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                            <h3 class="text-lg font-bold text-black mb-4">{{ __('admin.age_requirements') }}</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-black mb-2">{{ __('admin.minimum_age') }} *</label>
                                    <input wire:model.live="min_age" type="number" min="18" max="100" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green font-medium">
                                    @error('min_age') <span class="text-sidebar-green text-sm mt-1 block">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-black mb-2">{{ __('admin.maximum_age') }} *</label>
                                    <input wire:model.live="max_age" type="number" min="18" max="100" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green font-medium">
                                    @error('max_age') <span class="text-sidebar-green text-sm mt-1 block">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Income & Credit Requirements -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                                <h3 class="text-lg font-bold text-black mb-4">{{ __('admin.income_requirements') }}</h3>
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-black mb-2">{{ __('admin.minimum_monthly_income') }}</label>
                                        <input wire:model.live="min_monthly_income" type="number" step="1000" min="0" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green font-medium">
                                        @error('min_monthly_income') <span class="text-sidebar-green text-sm mt-1 block">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                                <h3 class="text-lg font-bold text-black mb-4">{{ __('admin.credit_requirements') }}</h3>
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-black mb-2">{{ __('admin.minimum_credit_score') }}</label>
                                        <input wire:model.live="min_credit_score" type="number" min="300" max="850" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green font-medium">
                                        @error('min_credit_score') <span class="text-sidebar-green text-sm mt-1 block">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Business Sectors -->
                        <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                            <h3 class="text-lg font-bold text-black mb-4">{{ __('admin.allowed_business_sectors') }}</h3>
                            <p class="text-sm text-gray-600 mb-4">Select business sectors eligible for this loan product. Leave empty to allow all sectors.</p>
                            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                                @foreach($businessSectors as $key => $sector)
                                    <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-white cursor-pointer transition-all duration-200 {{ in_array($key, $selectedBusinessSectors) ? 'bg-sidebar-green-50 border-sidebar-green-300 text-sidebar-green-light' : '' }}">
                                        <input wire:click="toggleBusinessSector('{{ $key }}')" type="checkbox" {{ in_array($key, $selectedBusinessSectors) ? 'checked' : '' }} class="text-sidebar-green focus:ring-sidebar-green rounded">
                                        <span class="ml-2 text-sm font-medium">{{ $sector }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

            <!-- Step 4: Requirements & Fees -->
            @elseif($currentStep === 4)
                <div class="p-8">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-2xl font-bold text-black">Requirements & Fees</h2>
                        <div class="bg-sidebar-green-50 px-4 py-2 rounded-full">
                            <span class="text-sidebar-green-light text-sm font-medium">{{ __('admin.step_of', ['current' => 4, 'total' => 4]) }}</span>
                        </div>
                    </div>
                    
                    <div class="space-y-8">
                        <!-- Fees & Charges -->
                        <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                            <h3 class="text-lg font-bold text-black mb-4">Fees & Charges</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-black mb-2">{{ __('admin.processing_fee_percent') }}</label>
                                    <input wire:model.live="processing_fee_percentage" type="number" step="0.01" min="0" max="100" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green font-medium">
                                    @error('processing_fee_percentage') <span class="text-sidebar-green text-sm mt-1 block">{{ $message }}</span> @enderror
                                    <p class="text-sm text-gray-500 mt-1">Percentage of loan amount</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-black mb-2">{{ __('admin.processing_fee_fixed') }}</label>
                                    <input wire:model.live="processing_fee_fixed" type="number" step="1000" min="0" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green font-medium">
                                    @error('processing_fee_fixed') <span class="text-sidebar-green text-sm mt-1 block">{{ $message }}</span> @enderror
                                    <p class="text-sm text-gray-500 mt-1">Fixed amount in TSh</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-black mb-2">{{ __('admin.late_payment_fee') }}</label>
                                    <input wire:model.live="late_payment_fee" type="number" step="1000" min="0" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green font-medium">
                                    @error('late_payment_fee') <span class="text-sidebar-green text-sm mt-1 block">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-black mb-2">{{ __('admin.early_repayment_fee') }}</label>
                                    <input wire:model.live="early_repayment_fee_percentage" type="number" step="0.01" min="0" max="100" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green font-medium">
                                    @error('early_repayment_fee_percentage') <span class="text-sidebar-green text-sm mt-1 block">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Collateral & Guarantor Requirements -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                                <h3 class="text-lg font-bold text-black mb-4">{{ __('admin.collateral_requirements') }}</h3>
                                <div class="space-y-6">
                                    <label class="flex items-center cursor-pointer bg-white p-4 rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors">
                                        <input wire:model.live="requires_collateral" type="checkbox" class="text-sidebar-green focus:ring-sidebar-green rounded h-5 w-5">
                                        <span class="ml-3 text-sm font-bold text-black">This loan requires collateral</span>
                                    </label>
                                    
                                    @if($requires_collateral)
                                        <div>
                                            <label class="block text-sm font-medium text-black mb-3">{{ __('admin.accepted_collateral_types') }}</label>
                                            <div class="grid grid-cols-1 gap-3">
                                                @foreach($collateralTypes as $key => $type)
                                                    <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-white cursor-pointer transition-all duration-200 {{ in_array($key, $selectedCollateralTypes) ? 'bg-sidebar-green-50 border-sidebar-green-300 text-sidebar-green-light' : '' }}">
                                                        <input wire:click="toggleCollateralType('{{ $key }}')" type="checkbox" {{ in_array($key, $selectedCollateralTypes) ? 'checked' : '' }} class="text-sidebar-green focus:ring-sidebar-green rounded">
                                                        <span class="ml-2 text-sm font-medium">{{ $type }}</span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                                <h3 class="text-lg font-bold text-black mb-4">{{ __('admin.guarantor_requirements') }}</h3>
                                <div class="space-y-4">
                                    <label class="flex items-center cursor-pointer bg-white p-4 rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors">
                                        <input wire:model.live="requires_guarantor" type="checkbox" class="text-sidebar-green focus:ring-sidebar-green rounded h-5 w-5">
                                        <span class="ml-3 text-sm font-bold text-black">This loan requires guarantors</span>
                                    </label>
                                    
                                    @if($requires_guarantor)
                                        <div>
                                            <label class="block text-sm font-medium text-black mb-2">Minimum Number of Guarantors</label>
                                            <input wire:model.live="min_guarantors" type="number" min="1" max="5" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green font-medium">
                                            @error('min_guarantors') <span class="text-sidebar-green text-sm mt-1 block">{{ $message }}</span> @enderror
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Required Documents -->
                        <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                            <h3 class="text-lg font-bold text-black mb-4">Required Documents</h3>
                            <p class="text-sm text-gray-600 mb-4">Select all documents that applicants must provide for this loan product.</p>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                                @foreach($documentTypes as $key => $type)
                                    <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-white cursor-pointer transition-all duration-200 {{ in_array($key, $selectedDocuments) ? 'bg-sidebar-green-50 border-sidebar-green-300 text-sidebar-green-light' : '' }}">
                                        <input wire:click="toggleDocument('{{ $key }}')" type="checkbox" {{ in_array($key, $selectedDocuments) ? 'checked' : '' }} class="text-sidebar-green focus:ring-sidebar-green rounded">
                                        <span class="ml-2 text-sm font-medium">{{ $type }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Disbursement Methods -->
                        <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                            <h3 class="text-lg font-bold text-black mb-4">Disbursement Methods</h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-white cursor-pointer transition-all duration-200 {{ in_array('bank_transfer', $selectedDisbursementMethods) ? 'bg-sidebar-green-50 border-sidebar-green-300 text-sidebar-green-light' : '' }}">
                                    <input wire:click="toggleDisbursementMethod('bank_transfer')" type="checkbox" {{ in_array('bank_transfer', $selectedDisbursementMethods) ? 'checked' : '' }} class="text-sidebar-green focus:ring-sidebar-green rounded">
                                    <span class="ml-3 text-sm font-bold">Bank Transfer</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-white cursor-pointer transition-all duration-200 {{ in_array('mobile_money', $selectedDisbursementMethods) ? 'bg-sidebar-green-50 border-sidebar-green-300 text-sidebar-green-light' : '' }}">
                                    <input wire:click="toggleDisbursementMethod('mobile_money')" type="checkbox" {{ in_array('mobile_money', $selectedDisbursementMethods) ? 'checked' : '' }} class="text-sidebar-green focus:ring-sidebar-green rounded">
                                    <span class="ml-3 text-sm font-bold">Mobile Money</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-white cursor-pointer transition-all duration-200 {{ in_array('cash', $selectedDisbursementMethods) ? 'bg-sidebar-green-50 border-sidebar-green-300 text-sidebar-green-light' : '' }}">
                                    <input wire:click="toggleDisbursementMethod('cash')" type="checkbox" {{ in_array('cash', $selectedDisbursementMethods) ? 'checked' : '' }} class="text-sidebar-green focus:ring-sidebar-green rounded">
                                    <span class="ml-3 text-sm font-bold">Cash Pickup</span>
                                </label>
                            </div>
                        </div>

                        <!-- Auto Approval Settings -->
                        <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                            <h3 class="text-lg font-bold text-black mb-4">Auto Approval Settings</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-4">
                                    <label class="flex items-center cursor-pointer bg-white p-4 rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors">
                                        <input wire:model.live="auto_approval_eligible" type="checkbox" class="text-sidebar-green focus:ring-sidebar-green rounded h-5 w-5">
                                        <span class="ml-3 text-sm font-bold text-black">Enable auto approval for qualified applicants</span>
                                    </label>
                                </div>
                                
                                @if($auto_approval_eligible)
                                    <div>
                                        <label class="block text-sm font-medium text-black mb-2">Auto Approval Maximum Amount (TSh)</label>
                                        <input wire:model.live="auto_approval_max_amount" type="number" step="1000" min="0" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green font-medium">
                                        @error('auto_approval_max_amount') <span class="text-sidebar-green text-sm mt-1 block">{{ $message }}</span> @enderror
                                        <p class="text-sm text-gray-500 mt-1">Maximum loan amount for auto approval</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Terms and Conditions -->
                        <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                            <h3 class="text-lg font-bold text-black mb-4">{{ __('admin.terms_and_conditions') }}</h3>
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-black mb-2">{{ __('admin.terms_and_conditions') }}</label>
                                    <textarea wire:model.live="terms_and_conditions" rows="4" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green" placeholder="{{ __('admin.enter_terms_conditions') }}"></textarea>
                                    @error('terms_and_conditions') <span class="text-sidebar-green text-sm mt-1 block">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-black mb-2">{{ __('admin.eligibility_criteria_summary') }}</label>
                                    <textarea wire:model.live="eligibility_criteria" rows="4" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green" placeholder="{{ __('admin.summarize_eligibility_criteria') }}"></textarea>
                                    @error('eligibility_criteria') <span class="text-sidebar-green text-sm mt-1 block">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Form Navigation -->
            <div class="px-8 py-6 bg-gray-50 border-t border-gray-200">
                <div class="flex items-center justify-between">
                    <!-- Previous Button -->
                    <div>
                        @if($currentStep > 1)
                            <button type="button" wire:click="previousStep" @click.prevent class="bg-gray-100 text-black px-6 py-3 rounded-lg font-semibold hover:bg-gray-200 transition-colors flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                </svg>
                                {{ __('admin.previous_step') }}
                            </button>
                        @else
                            <button type="button" wire:click="cancel" @click.prevent class="bg-gray-100 text-black px-6 py-3 rounded-lg font-semibold hover:bg-gray-200 transition-colors flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                </svg>
                                Cancel
                            </button>
                        @endif
                    </div>

                    <!-- Step Indicator -->
                    <div class="hidden md:flex items-center space-x-2">
                        <span class="text-sm text-gray-500">{{ __('admin.step_of', ['current' => $currentStep, 'total' => 4]) }}</span>
                        <div class="flex space-x-1">
                            @for($i = 1; $i <= 4; $i++)
                                <div class="w-2 h-2 rounded-full {{ $currentStep >= $i ? 'bg-sidebar-green' : 'bg-gray-300' }}"></div>
                            @endfor
                        </div>
                    </div>

                    <!-- Next/Save Button -->
                    <div>
                        @if($currentStep < 4)
                            <button type="button" wire:click="nextStep" @click.prevent class="bg-sidebar-green text-white px-6 py-3 rounded-lg font-semibold hover:bg-sidebar-green-light transition-all duration-200 shadow-lg flex items-center">
                                {{ __('admin.next_step') }}
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </button>
                        @else
                            <div class="flex items-center space-x-3">
                                <button type="button" wire:click="cancel" @click.prevent class="bg-gray-100 text-black px-6 py-3 rounded-lg font-semibold hover:bg-gray-200 transition-colors">
                                    Cancel
                                </button>
                                <button type="submit" class="bg-sidebar-green text-white px-8 py-3 rounded-lg font-bold hover:bg-sidebar-green-light transition-all duration-200 shadow-lg flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    {{ $mode === 'create' ? 'Create Product' : 'Update Product' }}
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>