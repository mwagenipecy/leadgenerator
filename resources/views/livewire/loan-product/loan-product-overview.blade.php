<div>
<div class="w-full">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between mb-6">
            <div>
                <div class="flex items-center space-x-3 mb-2">
                    <h1 class="text-4xl font-bold text-black">{{ $product->name }}</h1>
                    @if($product->promotional_tag)
                        <span class="bg-sidebar-green-100 text-sidebar-green-800 text-sm font-semibold px-3 py-1 rounded-full">{{ $product->promotional_tag }}</span>
                    @endif
                    @if($product->is_active)
                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold bg-green-100 text-green-800 border border-green-200">
                            Active
                        </span>
                    @else
                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold bg-gray-100 text-gray-800 border border-gray-200">
                            Inactive
                        </span>
                    @endif
                </div>
                <div class="flex items-center space-x-4 mt-2">
                    <p class="text-gray-600 text-lg">Product Code: {{ $product->product_code }}</p>
                    @if($product->loanCategory)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 border border-blue-200">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                            {{ $product->loanCategory->name }}
                        </span>
                    @endif
                </div>
                <p class="text-gray-500 text-sm mt-2">Created {{ $product->created_at->diffForHumans() }}</p>
            </div>
            <div class="flex items-center space-x-3">
                <button wire:click="editProduct" class="bg-sidebar-green text-white px-6 py-2 rounded-lg font-semibold hover:bg-sidebar-green-light transition-all duration-200 shadow-sm flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit Product
                </button>
                <button wire:click="backToList" class="text-black hover:text-gray-600 p-2 rounded-lg hover:bg-gray-100 transition-all duration-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Application Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Total Applications -->
        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100 hover:shadow-sm transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Total Applications</p>
                    <p class="text-3xl font-bold text-black">{{ $applicationStats['total_applications'] }}</p>
                </div>
                <div class="w-12 h-12 bg-black rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Approved Applications -->
        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100 hover:shadow-sm transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Approved</p>
                    <p class="text-3xl font-bold text-green-600">{{ $applicationStats['approved_applications'] }}</p>
                </div>
                <div class="w-12 h-12 bg-green-600 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Success Rate -->
        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100 hover:shadow-sm transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Success Rate</p>
                    <p class="text-3xl font-bold text-sidebar-green">{{ $applicationStats['success_rate'] }}%</p>
                </div>
                <div class="w-12 h-12 bg-sidebar-green rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Disbursed -->
        <!-- <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100 hover:shadow-sm transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Total Disbursed</p>
                    <p class="text-2xl font-bold text-black">TSh {{ number_format($applicationStats['total_disbursed_amount']) }}</p>
                </div>
                <div class="w-12 h-12 bg-black rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                    </svg>
                </div>
            </div>
        </div> -->
    </div>

    <!-- Main Content Area -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column - Product Details -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Basic Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100 bg-gray-50">
                    <h3 class="text-xl font-bold text-black flex items-center">
                        <svg class="w-6 h-6 text-sidebar-green mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Product Information
                    </h3>
                </div>
                <div class="p-6 space-y-6">
                    @if($product->loanCategory)
                        <div>
                            <h4 class="text-sm font-semibold text-black mb-2">Loan Category</h4>
                            <div class="flex items-center">
                                <span class="inline-flex items-center px-3 py-2 rounded-lg text-sm font-medium bg-blue-50 text-blue-800 border border-blue-200">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                    </svg>
                                    {{ $product->loanCategory->name }}
                                </span>
                                @if($product->loanCategory->description)
                                    <p class="text-sm text-gray-600 ml-3">{{ $product->loanCategory->description }}</p>
                                @endif
                            </div>
                        </div>
                    @endif

                    @if($product->description)
                        <div>
                            <h4 class="text-sm font-semibold text-black mb-2">Description</h4>
                            <p class="text-gray-600 leading-relaxed">{{ $product->description }}</p>
                        </div>
                    @endif

                    @if($product->key_features && count($product->key_features) > 0)
                        <div>
                            <h4 class="text-sm font-semibold text-black mb-3">Key Features</h4>
                            <div class="flex flex-wrap gap-2">
                                @foreach($product->key_features as $feature)
                                    <span class="inline-flex items-center bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        {{ $feature }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="text-sm font-semibold text-black mb-2">Amount Range</h4>
                            <p class="text-sm font-bold text-sidebar-green">{{ $product->amount_range }}</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="text-sm font-semibold text-black mb-2">Interest Rate</h4>
                            <p class="text-sm font-bold text-sidebar-green">{{ $product->interest_range }}</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="text-sm font-semibold text-black mb-2">Tenure</h4>
                            <p class="text-sm font-bold text-black">{{ $product->tenure_range }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Eligibility Criteria -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100 bg-gray-50">
                    <h3 class="text-xl font-bold text-black flex items-center">
                        <svg class="w-6 h-6 text-sidebar-green mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Eligibility Criteria
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="text-sm font-semibold text-black mb-3">Age Range</h4>
                            <p class="text-gray-600 font-medium">{{ $product->min_age }} - {{ $product->max_age }} years</p>
                        </div>

                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="text-sm font-semibold text-black mb-3">Employment</h4>
                            <p class="text-gray-600 font-medium">{{ ucfirst(str_replace('_', ' ', $product->employment_requirement)) }}</p>
                            @if($product->min_employment_months)
                                <p class="text-sm text-gray-500 mt-1">Min {{ $product->min_employment_months }} months</p>
                            @endif
                        </div>

                        @if($product->min_monthly_income)
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="text-sm font-semibold text-black mb-3">Minimum Income</h4>
                            <p class="text-gray-600 font-medium">TSh {{ number_format($product->min_monthly_income) }}/month</p>
                        </div>
                        @endif

                        @if($product->min_credit_score)
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="text-sm font-semibold text-black mb-3">Credit Score</h4>
                            <p class="text-gray-600 font-medium">Minimum: {{ $product->min_credit_score }}</p>
                        </div>
                        @endif


                        @if($product->minimum_dsr)
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="text-sm font-semibold text-black mb-3">Maximum DSR </h4>
                            <p class="text-gray-600 font-medium">Less or Equal: {{ $product->minimum_dsr }}</p>
                        </div>
                        @endif





                    </div>
                </div>
            </div>

            <!-- Requirements -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100 bg-gray-50">
                    <h3 class="text-xl font-bold text-black flex items-center">
                        <svg class="w-6 h-6 text-sidebar-green mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Requirements & Documentation
                    </h3>
                </div>
                <div class="p-6 space-y-6">
                    <!-- Required Documents -->
                    @if($product->required_documents && count($product->required_documents) > 0)
                        <div>
                            <h4 class="text-sm font-semibold text-black mb-3">Required Documents</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                @foreach($product->required_documents as $doc)
                                    <div class="flex items-center bg-gray-50 rounded-lg p-3">
                                        <svg class="w-5 h-5 text-sidebar-green mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        <span class="text-sm font-medium text-black">{{ $documentTypes[$doc] ?? ucwords(str_replace('_', ' ', $doc)) }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Collateral & Guarantor Requirements -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="text-sm font-semibold text-black mb-3">Collateral</h4>
                            @if($product->requires_collateral)
                                <p class="text-sidebar-green font-medium mb-2">Required</p>
                                @if($product->collateral_types && count($product->collateral_types) > 0)
                                    <div class="space-y-1">
                                        @foreach($product->collateral_types as $type)
                                            <p class="text-sm text-gray-600">• {{ $collateralTypes[$type] ?? ucwords(str_replace('_', ' ', $type)) }}</p>
                                        @endforeach
                                    </div>
                                @endif
                            @else
                                <p class="text-gray-600  text-sm  font-medium">Not Required</p>
                            @endif
                        </div>

                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="text-sm font-semibold text-black mb-3">Guarantors</h4>
                            @if($product->requires_guarantor)
                                <p class="text-sidebar-green font-medium">Required</p>
                                <p class="text-sm text-gray-600 mt-1">Minimum: {{ $product->min_guarantors }} guarantor(s)</p>
                            @else
                                <p class="text-gray-600 text-sm  font-medium">Not Required</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column - Statistics & Actions -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100 bg-gray-50">
                    <h3 class="text-lg font-bold text-black">Quick Actions</h3>
                </div>
                <div class="p-6 space-y-3">
                    <button wire:click="editProduct" class="w-full bg-sidebar-green-50 text-sidebar-green-light py-3 px-4 rounded-lg font-semibold hover:bg-sidebar-green-100 transition-colors flex items-center justify-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit Product
                    </button>

                    <button wire:click="confirmActivate" class="w-full {{ $product->is_active ? 'bg-gray-50 text-gray-700 hover:bg-gray-100' : 'bg-green-50 text-green-700 hover:bg-green-100' }} py-3 px-4 rounded-lg font-semibold transition-colors flex items-center justify-center">
                        @if($product->is_active)
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Deactivate Product
                        @else
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h.01M15 14h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Activate Product
                        @endif
                    </button>

                    <div class="border-t border-gray-200 pt-3 mt-4">
                        <button wire:click="confirmDelete" class="w-full bg-sidebar-green-50 text-sidebar-green-light py-3 px-4 rounded-lg font-semibold hover:bg-sidebar-green-100 transition-colors flex items-center justify-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Delete Product
                        </button>
                    </div>
                </div>
            </div>

            <!-- Performance Rating -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100 bg-gray-50">
                    <h3 class="text-lg font-bold text-black">Performance Rating</h3>
                </div>
                <div class="p-6">
                    <div class="text-center">
                        <div class="flex items-center justify-center space-x-1 mb-3">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="w-6 h-6 {{ $i <= $performanceMetrics['rating'] ? 'text-sidebar-green' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            @endfor
                        </div>
                        <p class="text-2xl font-bold text-black mb-1">{{ $performanceMetrics['rating'] }}</p>
                        <p class="text-sm text-gray-500">out of 5 stars</p>
                    </div>
                    
                    <div class="mt-6 space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Success Rate</span>
                            <span class="text-sm font-bold text-sidebar-green">{{ $performanceMetrics['success_rate'] }}%</span>
                        </div>
                        <!-- <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Avg. Amount</span>
                            <span class="text-sm font-bold text-black">TSh {{ number_format($performanceMetrics['average_amount']) }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Total Volume</span>
                            <span class="text-sm font-bold text-black">TSh {{ number_format($performanceMetrics['total_volume']) }}</span>
                        </div> -->
                    </div>
                </div>
            </div>

            <!-- Processing Details -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100 bg-gray-50">
                    <h3 class="text-lg font-bold text-black">Processing Details</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Approval Time</span>
                        <span class="text-sm font-bold text-black">{{ $product->approval_time_days }} days</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Disbursement Time</span>
                        <span class="text-sm font-bold text-black">{{ $product->disbursement_time_days }} days</span>
                    </div>
                    <!-- <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Avg. Processing</span>
                        <span class="text-sm font-bold text-sidebar-green">{{ $applicationStats['average_processing_time'] }} days</span>
                    </div> -->
                    @if($product->auto_approval_eligible)
                    <div class="pt-3 border-t border-gray-200">
                        <div class="flex items-center text-sidebar-green">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-sm font-medium">Auto-approval enabled</span>
                        </div>
                        @if($product->auto_approval_max_amount)
                            <p class="text-xs text-gray-500 mt-1">Up to TSh {{ number_format($product->auto_approval_max_amount) }}</p>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Activity Log Section -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden mt-8">
        <div class="p-6 border-b border-gray-100 bg-gray-50">
            <h3 class="text-xl font-bold text-black flex items-center">
                <svg class="w-6 h-6 text-sidebar-green mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Activity Log
            </h3>
            <p class="text-sm text-gray-600 mt-1">Recent actions and changes for this loan product</p>
        </div>
        <div class="p-6">
            @if($activityLogs && $activityLogs->count() > 0)
                <div class="space-y-4">
                    @foreach($activityLogs as $log)
                        <div class="flex items-start space-x-4 p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors border-l-4 
                            @if($log->severity === 'critical') border-red-500
                            @elseif($log->severity === 'high') border-orange-500
                            @elseif($log->severity === 'medium') border-blue-500
                            @else border-gray-300
                            @endif">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center
                                    @if($log->action === 'created' || $log->action === 'loan_product_created') bg-green-100 text-green-600
                                    @elseif($log->action === 'updated' || $log->action === 'loan_product_updated') bg-blue-100 text-blue-600
                                    @elseif($log->action === 'deleted' || $log->action === 'loan_product_deleted') bg-red-100 text-red-600
                                    @elseif(str_contains($log->action, 'activate') || str_contains($log->action, 'status')) bg-yellow-100 text-yellow-600
                                    @else bg-gray-100 text-gray-600
                                    @endif">
                                    @if($log->action === 'created' || $log->action === 'loan_product_created')
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                        </svg>
                                    @elseif($log->action === 'updated' || $log->action === 'loan_product_updated')
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    @elseif($log->action === 'deleted' || $log->action === 'loan_product_deleted')
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    @else
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    @endif
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between mb-1">
                                    <p class="text-sm font-semibold text-black">
                                        {{ ucwords(str_replace('_', ' ', $log->action)) }}
                                    </p>
                                    <span class="text-xs text-gray-500">{{ $log->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-sm text-gray-600 mb-2">{{ $log->description ?? 'No description available' }}</p>
                                <div class="flex items-center space-x-4 text-xs text-gray-500">
                                    @if($log->user)
                                        <span class="flex items-center">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                            </svg>
                                            {{ $log->user->name ?? $log->user->email ?? 'System' }}
                                        </span>
                                    @else
                                        <span class="flex items-center">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                            </svg>
                                            System
                                        </span>
                                    @endif
                                    @if($log->ip_address)
                                        <span class="flex items-center">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                                            </svg>
                                            {{ $log->ip_address }}
                                        </span>
                                    @endif
                                    <span class="px-2 py-0.5 rounded text-xs font-medium
                                        @if($log->severity === 'critical') bg-red-100 text-red-800
                                        @elseif($log->severity === 'high') bg-orange-100 text-orange-800
                                        @elseif($log->severity === 'medium') bg-blue-100 text-blue-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ ucfirst($log->severity) }}
                                    </span>
                                </div>
                                @if($log->new_values && count($log->new_values) > 0)
                                    <div class="mt-2 p-2 bg-white rounded text-xs">
                                        <p class="font-semibold text-gray-700 mb-1">Changes:</p>
                                        <ul class="space-y-1 text-gray-600">
                                            @foreach(array_slice($log->new_values, 0, 3) as $key => $value)
                                                <li><span class="font-medium">{{ ucwords(str_replace('_', ' ', $key)) }}:</span> 
                                                    @if(is_bool($value))
                                                        {{ $value ? 'Yes' : 'No' }}
                                                    @elseif(is_array($value))
                                                        {{ count($value) }} item(s)
                                                    @else
                                                        {{ strlen($value) > 50 ? substr($value, 0, 50) . '...' : $value }}
                                                    @endif
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <p class="text-gray-500 text-lg font-medium mb-2">No Activity Logs</p>
                    <p class="text-gray-400 text-sm">No activity has been recorded for this loan product yet.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    @if($showDeleteModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-sidebar-green-100 rounded-lg flex items-center justify-center mr-4">
                        <svg class="w-6 h-6 text-sidebar-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-black">Delete Product</h3>
                </div>
                <p class="text-gray-600 mb-6">Are you sure you want to delete "{{ $product->name }}"? This action cannot be undone and will affect {{ $applicationStats['total_applications'] }} applications.</p>
                <div class="flex justify-end space-x-3">
                    <button wire:click="closeDeleteModal" class="px-4 py-2 text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                        Cancel
                    </button>
                    <button wire:click="deleteProduct" class="px-4 py-2 bg-sidebar-green text-white rounded-lg hover:bg-sidebar-green-light transition-colors">
                        Delete Product
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Activate/Deactivate Confirmation Modal -->
    @if($showActivateModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-sidebar-green-100 rounded-lg flex items-center justify-center mr-4">
                        <svg class="w-6 h-6 text-sidebar-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-black">Toggle Product Status</h3>
                </div>
                <p class="text-gray-600 mb-6">Are you sure you want to {{ $product->is_active ? 'deactivate' : 'activate' }} "{{ $product->name }}"?</p>
                <div class="flex justify-end space-x-3">
                    <button wire:click="closeActivateModal" class="px-4 py-2 text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                        Cancel
                    </button>
                    <button wire:click="toggleProductStatus" class="px-4 py-2 bg-sidebar-green text-white rounded-lg hover:bg-sidebar-green-light transition-colors">
                        {{ $product->is_active ? 'Deactivate' : 'Activate' }}
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>

</div>
