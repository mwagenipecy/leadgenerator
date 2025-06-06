<div>


<div class="w-full">
    {{-- LIST VIEW --}}
       @if($currentStep === 'list')
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-4xl font-bold text-gray-900 mb-2">Loan Products</h1>
                    <p class="text-gray-600 text-lg">Manage your loan products and settings</p>
                </div>
                <div class="flex items-center space-x-3">
                    <div class="flex items-center space-x-2 bg-blue-50 px-4 py-2 rounded-full">
                        <div class="w-2 h-2 bg-blue-400 rounded-full animate-pulse"></div>
                        <span class="text-sm font-medium text-blue-700">{{ $stats['active'] ?? 0 }} Active Products</span>
                    </div>
                    <button wire:click="showCreateForm" class="bg-brand-red text-white px-6 py-2 rounded-xl font-semibold hover:bg-brand-dark-red transition-all duration-200 shadow-lg shadow-brand-red/25">
                        + Create Product
                    </button>
                </div>
            </div>
        </div>

        <!-- Flash Messages -->
        @if (session()->has('message'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl" role="alert">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    {{ session('message') }}
                </div>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl" role="alert">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    {{ session('error') }}
                </div>
            </div>
        @endif

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

            <!-- Total Products -->
            <div class="bg-white rounded-3xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-all duration-300">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-14 h-14 bg-blue-100 rounded-2xl flex items-center justify-center">
                        <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-medium text-gray-500">Total Products</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['total'] ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <!-- Active Products -->
            <div class="bg-white rounded-3xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-all duration-300">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-14 h-14 bg-green-100 rounded-2xl flex items-center justify-center">
                        <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-medium text-gray-500">Active</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['active'] ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <!-- Inactive Products -->
            <div class="bg-white rounded-3xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-all duration-300">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-14 h-14 bg-red-100 rounded-2xl flex items-center justify-center">
                        <svg class="w-7 h-7 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-medium text-gray-500">Inactive</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['inactive'] ?? 0 }}</p>
                    </div>
                </div>
            </div>

        </div>

        <!-- Filters and Search -->
        <div class="bg-white rounded-3xl shadow-sm p-6 border border-gray-100 mb-6">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between space-y-4 lg:space-y-0">
                <!-- Search -->
                <div class="flex-1 max-w-md">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <input wire:model.live="search" type="text" class="block w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-red focus:border-brand-red placeholder-gray-500 text-gray-900 text-sm transition-all duration-200" placeholder="Search products...">
                    </div>
                </div>

                <!-- Filters -->
                <div class="flex items-center space-x-4">
                    <select wire:model.live="statusFilter" class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-brand-red focus:border-brand-red">
                        <option value="all">All Status</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>

                    <select wire:model.live="employmentFilter" class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-brand-red focus:border-brand-red">
                        <option value="all">All Employment Types</option>
                        <option value="employed">Employed Only</option>
                        <option value="unemployed">Unemployed/Self-Employed</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6 mb-8">
            @forelse($products as $product)
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg transition-all duration-300 group">
                    <!-- Product Header -->
                    <div class="p-6 pb-4">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex-1">
                                <div class="flex items-center space-x-2 mb-2">
                                    <h3 class="text-lg font-bold text-gray-900 group-hover:text-brand-red transition-colors">{{ $product->name }}</h3>
                                    @if($product->promotional_tag)
                                        <span class="bg-yellow-100 text-yellow-800 text-xs font-semibold px-2 py-1 rounded-lg">{{ $product->promotional_tag }}</span>
                                    @endif
                                </div>
                                <p class="text-sm text-gray-500 mb-2">{{ $product->product_code }}</p>
                                @if($product->description)
                                    <p class="text-sm text-gray-600 line-clamp-2">{{ $product->description }}</p>
                                @endif
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-{{ $product->status_badge_color }}-100 text-{{ $product->status_badge_color }}-800 border border-{{ $product->status_badge_color }}-200">
                                    {{ $product->status_badge_text }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Product Details -->
                    <div class="px-6 pb-4">
                        <div class="space-y-3">
                            <!-- Amount Range -->
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-500">Amount Range:</span>
                                <span class="text-sm font-bold text-gray-900">{{ $product->amount_range }}</span>
                            </div>

                            <!-- Tenure -->
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-500">Tenure:</span>
                                <span class="text-sm font-bold text-gray-900">{{ $product->tenure_range }}</span>
                            </div>

                            <!-- Interest Rate -->
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-500">Interest Rate:</span>
                                <span class="text-sm font-bold text-gray-900">{{ $product->interest_range }}</span>
                            </div>

                            <!-- Employment Requirement -->
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-500">Employment:</span>
                                <span class="text-sm font-medium text-blue-600">{{ $product->employment_requirement_label }}</span>
                            </div>

                            <!-- Processing Fee -->
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-500">Processing Fee:</span>
                                <span class="text-sm font-bold text-gray-900">{{ $product->processing_fee }}</span>
                            </div>

                            <!-- Approval Time -->
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-500">Approval Time:</span>
                                <span class="text-sm font-medium text-green-600">{{ $product->approval_time_days }} days</span>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-2">
                                <!-- View Button -->
                                <button wire:click="viewProduct({{ $product->id }})" class="text-blue-600 hover:text-blue-800 p-2 rounded-xl hover:bg-blue-50 transition-all duration-200" title="View Details">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </button>

                                <!-- Edit Button -->
                                <button wire:click="editProduct({{ $product->id }})" class="text-gray-600 hover:text-gray-800 p-2 rounded-xl hover:bg-gray-100 transition-all duration-200" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>

                                <!-- Copy/Duplicate Button -->
                                <button wire:click="duplicateProduct({{ $product->id }})" class="text-purple-600 hover:text-purple-800 p-2 rounded-xl hover:bg-purple-50 transition-all duration-200" title="Duplicate">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                    </svg>
                                </button>

                                <!-- Delete Button -->
                                <button wire:click="deleteProduct({{ $product->id }})" 
                                        wire:confirm="Are you sure you want to delete this product? This action cannot be undone."
                                        class="text-red-600 hover:text-red-800 p-2 rounded-xl hover:bg-red-50 transition-all duration-200" title="Delete">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>

                            <!-- Toggle Status -->
                            <div class="flex items-center">
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" wire:click="toggleProductStatus({{ $product->id }})" {{ $product->is_active ? 'checked' : '' }} class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-brand-red/25 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-brand-red"></div>
                                    <span class="ms-3 text-sm font-medium text-gray-500">{{ $product->is_active ? 'Active' : 'Inactive' }}</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full">
                    <div class="bg-white rounded-3xl shadow-sm p-12 text-center border border-gray-100">
                        <div class="w-20 h-20 bg-gradient-to-br from-brand-red to-brand-dark-red rounded-3xl flex items-center justify-center mx-auto mb-6 shadow-lg">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">No loan products found</h3>
                        <p class="text-gray-500 mb-6">Get started by creating your first loan product to offer to your customers.</p>
                        <button wire:click="showCreateForm" class="bg-brand-red text-white px-8 py-3 rounded-xl font-semibold hover:bg-brand-dark-red transition-all duration-200 shadow-lg shadow-brand-red/25">
                            Create First Product
                        </button>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($products->hasPages())
            <div class="flex justify-center mt-8">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 px-6 py-4">
                    {{ $products->links() }}
                </div>
            </div>
        @endif

    
        @elseif(in_array($currentStep, ['create', 'edit']))
        <!-- Form Header with Progress -->
        <div class="mb-8">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-4xl font-bold text-gray-900 mb-2">
                        {{ $currentStep === 'create' ? 'Create New' : 'Edit' }} Loan Product
                    </h1>
                    <p class="text-gray-600 text-lg">Step {{ $currentEditStep }} of 4 - Complete all steps to publish your loan product</p>
                </div>
                <div class="flex items-center space-x-3">
                    <button wire:click="backToList" class="text-gray-600 hover:text-gray-800 p-2 rounded-xl hover:bg-gray-100 transition-all duration-200" title="Back to List">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Progress Steps -->
            <div class="bg-white rounded-3xl shadow-sm p-6 border border-gray-100">
                <div class="flex items-center justify-between">
                    @for($i = 1; $i <= 4; $i++)
                        <div class="flex items-center {{ $i < 4 ? 'flex-1' : '' }}">
                            <div class="flex items-center space-x-3">
                                <button wire:click="goToStep({{ $i }})" class="w-12 h-12 rounded-full flex items-center justify-center font-bold text-sm transition-all duration-200 relative
                                    {{ $currentEditStep >= $i ? 'bg-brand-red text-white shadow-lg shadow-brand-red/25' : 'bg-gray-200 text-gray-600 hover:bg-gray-300' }}">
                                    @if($currentEditStep > $i)
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    @else
                                        {{ $i }}
                                    @endif
                                </button>
                                <div class="hidden sm:block">
                                    <p class="text-sm font-bold {{ $currentEditStep >= $i ? 'text-brand-red' : 'text-gray-500' }}">
                                        @switch($i)
                                            @case(1) Basic Information @break
                                            @case(2) Amount & Terms @break
                                            @case(3) Eligibility Criteria @break
                                            @case(4) Requirements & Fees @break
                                        @endswitch
                                    </p>
                                    <p class="text-xs text-gray-400">
                                        @switch($i)
                                            @case(1) Product details & features @break
                                            @case(2) Loan limits & interest rates @break
                                            @case(3) Who can apply @break
                                            @case(4) Documents & charges @break
                                        @endswitch
                                    </p>
                                </div>
                            </div>
                            @if($i < 4)
                                <div class="flex-1 h-1 mx-4 rounded-full {{ $currentEditStep > $i ? 'bg-brand-red' : 'bg-gray-200' }}"></div>
                            @endif
                        </div>
                    @endfor
                </div>
            </div>
        </div>

        <!-- Form Content -->
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <form wire:submit="saveProduct">
                <!-- Step 1: Basic Information -->
                @if($currentEditStep === 1)
                    <div class="p-8">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-2xl font-bold text-gray-900">Basic Information</h2>
                            <div class="bg-blue-50 px-4 py-2 rounded-full">
                                <span class="text-blue-700 text-sm font-medium">Step 1 of 4</span>
                            </div>
                        </div>
                        
                        <div class="space-y-8">
                            <!-- Product Name & Status -->
                            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                                <div class="lg:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Product Name *</label>
                                    <input wire:model="name" type="text" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-brand-red focus:border-brand-red text-lg font-medium" placeholder="e.g., Personal Loan, Business Loan, Emergency Loan">
                                    @error('name') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                                    <div class="flex items-center space-x-4 pt-3">
                                        <label class="flex items-center cursor-pointer">
                                            <input wire:model="is_active" type="radio" value="1" class="text-brand-red focus:ring-brand-red">
                                            <span class="ml-2 text-sm font-medium text-gray-700">Active</span>
                                        </label>
                                        <label class="flex items-center cursor-pointer">
                                            <input wire:model="is_active" type="radio" value="0" class="text-brand-red focus:ring-brand-red">
                                            <span class="ml-2 text-sm font-medium text-gray-700">Inactive</span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Promotional Tag -->

                            <div class="flex   space-x-4">


                         
                            <div class="w-1/2 ">

                            <label class="block text-sm font-medium text-gray-700 mb-2">Loan Type</label>
                            <select wire:model="loan_type" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-brand-red focus:border-brand-red">
                                <option value=""  >Select Loan Type</option>
                                <option value="personal">Personal Loan</option>
                                <option value="business">Business Loan</option>
                                <option value="mortgage">Mortgage Loan</option>
                                <option value="auto">Auto Loan</option>
                                <option value="student">Student Loan</option>
                            </select>
                                @error('loan_type') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                            </div>


                            <div class="w-1/2 ">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Promotional Tag (Optional)</label>
                                <input wire:model="promotional_tag" type="text" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-brand-red focus:border-brand-red" placeholder="e.g., Best Rate, Quick Approval, No Collateral">
                                @error('promotional_tag') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                                <p class="text-sm text-gray-500 mt-1">This will appear as a badge on your product card</p>
                            </div>


                               
                            </div>

                            <!-- Description -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Product Description</label>
                                <textarea wire:model="description" rows="4" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-brand-red focus:border-brand-red" placeholder="Describe your loan product, its benefits, and who it's designed for..."></textarea>
                                @error('description') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <!-- Key Features -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Key Features</label>
                                <div class="space-y-4">
                                    <div class="flex items-center space-x-3">
                                        <input wire:model="newKeyFeature" wire:keydown.enter="addKeyFeature" type="text" class="flex-1 px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-brand-red focus:border-brand-red" placeholder="Add a key feature (e.g., No hidden fees, Fast approval)">
                                        <button type="button" wire:click="addKeyFeature" class="bg-brand-red text-white px-6 py-3 rounded-xl hover:bg-brand-dark-red transition-colors font-semibold">
                                            Add Feature
                                        </button>
                                    </div>
                                    
                                    @if(count($key_features) > 0)
                                        <div class="bg-gray-50 rounded-2xl p-4">
                                            <h4 class="text-sm font-medium text-gray-700 mb-3">Added Features:</h4>
                                            <div class="flex flex-wrap gap-2">
                                                @foreach($key_features as $index => $feature)
                                                    <span class="inline-flex items-center bg-blue-100 text-blue-800 px-3 py-2 rounded-full text-sm font-medium">
                                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                        </svg>
                                                        {{ $feature }}
                                                        <button type="button" wire:click="removeKeyFeature({{ $index }})" class="ml-2 text-blue-600 hover:text-blue-800">
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
                        </div>
                    </div>

                <!-- Step 2: Amount & Terms -->
                @elseif($currentEditStep === 2)
                    <div class="p-8">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-2xl font-bold text-gray-900">Amount & Terms</h2>
                            <div class="bg-blue-50 px-4 py-2 rounded-full">
                                <span class="text-blue-700 text-sm font-medium">Step 2 of 4</span>
                            </div>
                        </div>
                        
                        <div class="space-y-8">
                            <!-- Loan Amount Range -->
                            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl p-6 border border-blue-100">
                                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                                    <svg class="w-6 h-6 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                    </svg>
                                    Loan Amount Range
                                </h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Minimum Amount (TSh) *</label>
                                        <input wire:model="min_amount" type="number" step="1000" min="1000" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-brand-red focus:border-brand-red text-lg font-bold">
                                        @error('min_amount') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Maximum Amount (TSh) *</label>
                                        <input wire:model="max_amount" type="number" step="1000" min="1000" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-brand-red focus:border-brand-red text-lg font-bold">
                                        @error('max_amount') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                @if($min_amount && $max_amount && $min_amount <= $max_amount)
                                    <div class="mt-4 p-3 bg-white rounded-xl border border-blue-200">
                                        <p class="text-sm text-blue-700">
                                            <strong>Range Preview:</strong> TSh {{ number_format($min_amount) }} - TSh {{ number_format($max_amount) }}
                                        </p>
                                    </div>
                                @endif
                            </div>

                            <!-- Loan Tenure Range -->
                            <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-2xl p-6 border border-green-100">
                                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                                    <svg class="w-6 h-6 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    Loan Tenure Range
                                </h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Minimum Tenure (Months) *</label>
                                        <input wire:model="min_tenure_months" type="number" min="1" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-brand-red focus:border-brand-red text-lg font-bold">
                                        @error('min_tenure_months') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Maximum Tenure (Months) *</label>
                                        <input wire:model="max_tenure_months" type="number" min="1" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-brand-red focus:border-brand-red text-lg font-bold">
                                        @error('max_tenure_months') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                @if($min_tenure_months && $max_tenure_months && $min_tenure_months <= $max_tenure_months)
                                    <div class="mt-4 p-3 bg-white rounded-xl border border-green-200">
                                        <p class="text-sm text-green-700">
                                            <strong>Range Preview:</strong> {{ $min_tenure_months }} - {{ $max_tenure_months }} months 
                                            ({{ round($min_tenure_months/12, 1) }} - {{ round($max_tenure_months/12, 1) }} years)
                                        </p>
                                    </div>
                                @endif
                            </div>

                            <!-- Interest Rates -->
                            <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-2xl p-6 border border-purple-100">
                                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                                    <svg class="w-6 h-6 text-purple-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                    </svg>
                                    Interest Rates
                                </h3>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Minimum Rate (%) *</label>
                                        <input wire:model="interest_rate_min" type="number" step="0.01" min="0" max="100" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-brand-red focus:border-brand-red text-lg font-bold">
                                        @error('interest_rate_min') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Maximum Rate (%) *</label>
                                        <input wire:model="interest_rate_max" type="number" step="0.01" min="0" max="100" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-brand-red focus:border-brand-red text-lg font-bold">
                                        @error('interest_rate_max') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Interest Type *</label>
                                        <select wire:model="interest_type" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-brand-red focus:border-brand-red text-lg font-bold">
                                            <option value="reducing">Reducing Balance</option>
                                            <option value="flat">Flat Rate</option>
                                            <option value="fixed">Fixed Rate</option>
                                        </select>
                                        @error('interest_type') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                @if($interest_rate_min && $interest_rate_max && $interest_rate_min <= $interest_rate_max)
                                    <div class="mt-4 p-3 bg-white rounded-xl border border-purple-200">
                                        <p class="text-sm text-purple-700">
                                            <strong>Rate Preview:</strong> {{ $interest_rate_min }}% - {{ $interest_rate_max }}% {{ ucfirst($interest_type) }}
                                        </p>
                                    </div>
                                @endif
                            </div>

                            <!-- Processing Timeline -->
                            <div class="bg-gradient-to-br from-orange-50 to-red-50 rounded-2xl p-6 border border-orange-100">
                                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                                    <svg class="w-6 h-6 text-orange-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Processing Timeline
                                </h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Approval Time (Days) *</label>
                                        <input wire:model="approval_time_days" type="number" min="1" max="90" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-brand-red focus:border-brand-red text-lg font-bold">
                                        @error('approval_time_days') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                                        <p class="text-sm text-gray-500 mt-1">How long does it take to approve applications?</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Disbursement Time (Days) *</label>
                                        <input wire:model="disbursement_time_days" type="number" min="1" max="30" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-brand-red focus:border-brand-red text-lg font-bold">
                                        @error('disbursement_time_days') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                                        <p class="text-sm text-gray-500 mt-1">How long after approval to disburse funds?</p>
                                    </div>
                                </div>
                            </div>





                              <!-- DSR -->
                              <div class="bg-gradient-to-br from-orange-50 to-red-50 rounded-2xl p-6 border border-orange-100">
    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
        <svg class="w-6 h-6 text-orange-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
        </svg>
        Minimum DSR
    </h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Minimum DSR (%) *</label>
            <input wire:model="minimum_dsr" type="number" min="0" max="100" step="0.1" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-brand-red focus:border-brand-red text-lg font-bold">
            @error('minimum_dsr') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
            <p class="text-sm text-gray-500 mt-1">What is the minimum Debt Service Ratio required for approval?</p>
        </div>
    </div>
</div>






                        </div>
                    </div>

                <!-- Step 3: Eligibility Criteria -->
                @elseif($currentEditStep === 3)
                    <div class="p-8">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-2xl font-bold text-gray-900">Eligibility Criteria</h2>
                            <div class="bg-blue-50 px-4 py-2 rounded-full">
                                <span class="text-blue-700 text-sm font-medium">Step 3 of 4</span>
                            </div>
                        </div>
                        
                        <div class="space-y-8">
                            <!-- Employment Requirements -->
                            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl p-6 border border-blue-100">
                                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                                    <svg class="w-6 h-6 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2V6z"/>
                                    </svg>
                                    Employment Requirements
                                </h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Employment Type *</label>
                                        <select wire:model="employment_requirement" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-brand-red focus:border-brand-red font-medium">
                                            <option value="all">All Employment Types</option>
                                            <option value="employed">Employed Only</option>
                                            <option value="unemployed">Unemployed/Self-Employed</option>
                                        </select>
                                        @error('employment_requirement') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                                    </div>
                                    @if($employment_requirement === 'employed')
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Minimum Employment Period (Months)</label>
                                            <input wire:model="min_employment_months" type="number" min="1" max="120" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-brand-red focus:border-brand-red font-medium">
                                            @error('min_employment_months') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                                            <p class="text-sm text-gray-500 mt-1">How long must they be employed?</p>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Age Requirements -->
                            <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-2xl p-6 border border-green-100">
                                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                                    <svg class="w-6 h-6 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    Age Requirements
                                </h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Minimum Age *</label>
                                        <input wire:model="min_age" type="number" min="18" max="100" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-brand-red focus:border-brand-red font-medium">
                                        @error('min_age') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Maximum Age *</label>
                                        <input wire:model="max_age" type="number" min="18" max="100" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-brand-red focus:border-brand-red font-medium">
                                        @error('max_age') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                @if($min_age && $max_age && $min_age <= $max_age)
                                    <div class="mt-4 p-3 bg-white rounded-xl border border-green-200">
                                        <p class="text-sm text-green-700">
                                            <strong>Age Range:</strong> {{ $min_age }} - {{ $max_age }} years old
                                        </p>
                                    </div>
                                @endif
                            </div>

                            <!-- Income Requirements -->
                            <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-2xl p-6 border border-purple-100">
                                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                                    <svg class="w-6 h-6 text-purple-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                    </svg>
                                    Income Requirements
                                </h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Minimum Monthly Income (TSh)</label>
                                        <input wire:model="min_monthly_income" type="number" step="1000" min="0" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-brand-red focus:border-brand-red font-medium">
                                        @error('min_monthly_income') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                                        <p class="text-sm text-gray-500 mt-1">Leave empty if no minimum income required</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Max Debt-to-Income Ratio (%)</label>
                                        <input wire:model="max_debt_to_income_ratio" type="number" step="0.1" min="0" max="100" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-brand-red focus:border-brand-red font-medium">
                                        @error('max_debt_to_income_ratio') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                                        <p class="text-sm text-gray-500 mt-1">Maximum percentage of income that can go to debt payments</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Credit Requirements -->
                            <div class="bg-gradient-to-br from-yellow-50 to-orange-50 rounded-2xl p-6 border border-yellow-100">
                                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                                    <svg class="w-6 h-6 text-yellow-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                    </svg>
                                    Credit Requirements
                                </h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Minimum Credit Score</label>
                                        <input wire:model="min_credit_score" type="number" min="300" max="850" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-brand-red focus:border-brand-red font-medium">
                                        @error('min_credit_score') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                                        <p class="text-sm text-gray-500 mt-1">Leave empty if no credit score requirement</p>
                                    </div>
                                    <div class="flex items-center pt-8">
                                        <label class="flex items-center cursor-pointer bg-white p-4 rounded-xl border border-gray-200 hover:bg-gray-50 transition-colors w-full">
                                            <input wire:model="allow_bad_credit" type="checkbox" class="text-brand-red focus:ring-brand-red rounded h-5 w-5">
                                            <span class="ml-3 text-sm font-medium text-gray-700">Accept applications with bad credit history</span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Business Sectors -->
                            <div class="bg-gradient-to-br from-indigo-50 to-blue-50 rounded-2xl p-6 border border-indigo-100">
                                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                                    <svg class="w-6 h-6 text-indigo-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                    Allowed Business Sectors (Optional)
                                </h3>
                                <p class="text-sm text-gray-600 mb-4">Select business sectors eligible for this loan product. Leave empty to allow all sectors.</p>
                                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                                    @foreach($businessSectors as $key => $sector)
                                        <label class="flex items-center p-3 border border-gray-200 rounded-xl hover:bg-white hover:border-indigo-300 cursor-pointer transition-all duration-200 {{ in_array($key, $selectedBusinessSectors) ? 'bg-indigo-50 border-indigo-300 text-indigo-700' : '' }}">
                                            <input wire:click="toggleBusinessSector('{{ $key }}')" type="checkbox" {{ in_array($key, $selectedBusinessSectors) ? 'checked' : '' }} class="text-brand-red focus:ring-brand-red rounded">
                                            <span class="ml-2 text-sm font-medium">{{ $sector }}</span>
                                        </label>
                                    @endforeach
                                </div>
                                @if(count($selectedBusinessSectors) > 0)
                                    <div class="mt-4 p-3 bg-white rounded-xl border border-indigo-200">
                                        <p class="text-sm text-indigo-700">
                                            <strong>Selected Sectors:</strong> {{ count($selectedBusinessSectors) }} sector(s) selected
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                <!-- Step 4: Requirements & Fees -->
                @elseif($currentEditStep === 4)
                    <div class="p-8">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-2xl font-bold text-gray-900">Requirements & Fees</h2>
                            <div class="bg-blue-50 px-4 py-2 rounded-full">
                                <span class="text-blue-700 text-sm font-medium">Step 4 of 4</span>
                            </div>
                        </div>
                        
                        <div class="space-y-8">
                            <!-- Fees & Charges -->
                            <div class="bg-gradient-to-br from-red-50 to-pink-50 rounded-2xl p-6 border border-red-100">
                                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                                    <svg class="w-6 h-6 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                    </svg>
                                    Fees & Charges
                                </h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Processing Fee (%)</label>
                                        <input wire:model="processing_fee_percentage" type="number" step="0.01" min="0" max="100" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-brand-red focus:border-brand-red font-medium">
                                        @error('processing_fee_percentage') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                                        <p class="text-sm text-gray-500 mt-1">Percentage of loan amount</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Processing Fee (Fixed TSh)</label>
                                        <input wire:model="processing_fee_fixed" type="number" step="1000" min="0" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-brand-red focus:border-brand-red font-medium">
                                        @error('processing_fee_fixed') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                                        <p class="text-sm text-gray-500 mt-1">Fixed amount in TSh</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Late Payment Fee (TSh)</label>
                                        <input wire:model="late_payment_fee" type="number" step="1000" min="0" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-brand-red focus:border-brand-red font-medium">
                                        @error('late_payment_fee') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Early Repayment Fee (%)</label>
                                        <input wire:model="early_repayment_fee_percentage" type="number" step="0.01" min="0" max="100" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-brand-red focus:border-brand-red font-medium">
                                        @error('early_repayment_fee_percentage') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Collateral Requirements -->
                            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl p-6 border border-blue-100">
                                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                                    <svg class="w-6 h-6 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    </svg>
                                    Collateral Requirements
                                </h3>
                                <div class="space-y-6">
                                    <label class="flex items-center cursor-pointer bg-white p-4 rounded-xl border border-gray-200 hover:bg-gray-50 transition-colors">
                                        <input wire:model="requires_collateral" type="checkbox" class="text-brand-red focus:ring-brand-red rounded h-5 w-5">
                                        <span class="ml-3 text-sm font-bold text-gray-700">This loan requires collateral</span>
                                    </label>
                                    
                                    @if($requires_collateral)
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-3">Accepted Collateral Types</label>
                                            <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                                                @foreach($collateralTypes as $key => $type)
                                                    <label class="flex items-center p-3 border border-gray-200 rounded-xl hover:bg-white hover:border-blue-300 cursor-pointer transition-all duration-200 {{ in_array($key, $selectedCollateralTypes) ? 'bg-blue-50 border-blue-300 text-blue-700' : '' }}">
                                                        <input wire:click="toggleCollateralType('{{ $key }}')" type="checkbox" {{ in_array($key, $selectedCollateralTypes) ? 'checked' : '' }} class="text-brand-red focus:ring-brand-red rounded">
                                                        <span class="ml-2 text-sm font-medium">{{ $type }}</span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Guarantor Requirements -->
                            <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-2xl p-6 border border-green-100">
                                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                                    <svg class="w-6 h-6 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                    Guarantor Requirements
                                </h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="space-y-4">
                                        <label class="flex items-center cursor-pointer bg-white p-4 rounded-xl border border-gray-200 hover:bg-gray-50 transition-colors">
                                            <input wire:model="requires_guarantor" type="checkbox" class="text-brand-red focus:ring-brand-red rounded h-5 w-5">
                                            <span class="ml-3 text-sm font-bold text-gray-700">This loan requires guarantors</span>
                                        </label>
                                    </div>
                                    
                                    @if($requires_guarantor)
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Minimum Number of Guarantors</label>
                                            <input wire:model="min_guarantors" type="number" min="1" max="5" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-brand-red focus:border-brand-red font-medium">
                                            @error('min_guarantors') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Required Documents -->
                            <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-2xl p-6 border border-purple-100">
                                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                                    <svg class="w-6 h-6 text-purple-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    Required Documents
                                </h3>
                                <p class="text-sm text-gray-600 mb-4">Select all documents that applicants must provide for this loan product.</p>
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                                    @foreach($documentTypes as $key => $type)
                                        <label class="flex items-center p-3 border border-gray-200 rounded-xl hover:bg-white hover:border-purple-300 cursor-pointer transition-all duration-200 {{ in_array($key, $selectedDocuments) ? 'bg-purple-50 border-purple-300 text-purple-700' : '' }}">
                                            <input wire:click="toggleDocument('{{ $key }}')" type="checkbox" {{ in_array($key, $selectedDocuments) ? 'checked' : '' }} class="text-brand-red focus:ring-brand-red rounded">
                                            <span class="ml-2 text-sm font-medium">{{ $type }}</span>
                                        </label>
                                    @endforeach
                                </div>
                                @if(count($selectedDocuments) > 0)
                                    <div class="mt-4 p-3 bg-white rounded-xl border border-purple-200">
                                        <p class="text-sm text-purple-700">
                                            <strong>Selected Documents:</strong> {{ count($selectedDocuments) }} document(s) required
                                        </p>
                                    </div>
                                @endif
                            </div>

                            <!-- Disbursement Methods -->
                            <div class="bg-gradient-to-br from-yellow-50 to-orange-50 rounded-2xl p-6 border border-yellow-100">
                                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                                    <svg class="w-6 h-6 text-yellow-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                    </svg>
                                    Disbursement Methods
                                </h3>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <label class="flex items-center p-4 border border-gray-200 rounded-xl hover:bg-white hover:border-yellow-300 cursor-pointer transition-all duration-200 {{ in_array('bank_transfer', $selectedDisbursementMethods) ? 'bg-yellow-50 border-yellow-300 text-yellow-700' : '' }}">
                                        <input wire:click="toggleDisbursementMethod('bank_transfer')" type="checkbox" {{ in_array('bank_transfer', $selectedDisbursementMethods) ? 'checked' : '' }} class="text-brand-red focus:ring-brand-red rounded">
                                        <span class="ml-3 text-sm font-bold">Bank Transfer</span>
                                    </label>
                                    <label class="flex items-center p-4 border border-gray-200 rounded-xl hover:bg-white hover:border-yellow-300 cursor-pointer transition-all duration-200 {{ in_array('mobile_money', $selectedDisbursementMethods) ? 'bg-yellow-50 border-yellow-300 text-yellow-700' : '' }}">
                                        <input wire:click="toggleDisbursementMethod('mobile_money')" type="checkbox" {{ in_array('mobile_money', $selectedDisbursementMethods) ? 'checked' : '' }} class="text-brand-red focus:ring-brand-red rounded">
                                        <span class="ml-3 text-sm font-bold">Mobile Money</span>
                                    </label>
                                    <label class="flex items-center p-4 border border-gray-200 rounded-xl hover:bg-white hover:border-yellow-300 cursor-pointer transition-all duration-200 {{ in_array('cash', $selectedDisbursementMethods) ? 'bg-yellow-50 border-yellow-300 text-yellow-700' : '' }}">
                                        <input wire:click="toggleDisbursementMethod('cash')" type="checkbox" {{ in_array('cash', $selectedDisbursementMethods) ? 'checked' : '' }} class="text-brand-red focus:ring-brand-red rounded">
                                        <span class="ml-3 text-sm font-bold">Cash Pickup</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Auto Approval Settings -->
                            <div class="bg-gradient-to-br from-indigo-50 to-blue-50 rounded-2xl p-6 border border-indigo-100">
                                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                                    <svg class="w-6 h-6 text-indigo-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.385l-.548-.547z"/>
                                    </svg>
                                    Auto Approval Settings
                                </h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="space-y-4">
                                        <label class="flex items-center cursor-pointer bg-white p-4 rounded-xl border border-gray-200 hover:bg-gray-50 transition-colors">
                                            <input wire:model="auto_approval_eligible" type="checkbox" class="text-brand-red focus:ring-brand-red rounded h-5 w-5">
                                            <span class="ml-3 text-sm font-bold text-gray-700">Enable auto approval for qualified applicants</span>
                                        </label>
                                    </div>
                                    
                                    @if($auto_approval_eligible)
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Auto Approval Maximum Amount (TSh)</label>
                                            <input wire:model="auto_approval_max_amount" type="number" step="1000" min="0" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-brand-red focus:border-brand-red font-medium">
                                            @error('auto_approval_max_amount') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                                            <p class="text-sm text-gray-500 mt-1">Maximum loan amount for auto approval</p>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Terms and Conditions -->
                            <div class="bg-gradient-to-br from-gray-50 to-slate-50 rounded-2xl p-6 border border-gray-200">
                                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                                    <svg class="w-6 h-6 text-gray-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    Terms and Conditions
                                </h3>
                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Terms and Conditions</label>
                                        <textarea wire:model="terms_and_conditions" rows="4" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-brand-red focus:border-brand-red" placeholder="Enter the terms and conditions for this loan product..."></textarea>
                                        @error('terms_and_conditions') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Eligibility Criteria Summary</label>
                                        <textarea wire:model="eligibility_criteria" rows="4" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-brand-red focus:border-brand-red" placeholder="Summarize the eligibility criteria for this loan..."></textarea>
                                        @error('eligibility_criteria') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
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
                            @if($currentEditStep > 1)
                                <button type="button" wire:click="previousStep" class="bg-gray-100 text-gray-700 px-6 py-3 rounded-xl font-semibold hover:bg-gray-200 transition-colors flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                    </svg>
                                    Previous Step
                                </button>
                            @else
                                <button type="button" wire:click="backToList" class="bg-gray-100 text-gray-700 px-6 py-3 rounded-xl font-semibold hover:bg-gray-200 transition-colors flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                    </svg>
                                    Back to List
                                </button>
                            @endif
                        </div>

                        <!-- Step Indicator -->
                        <div class="hidden md:flex items-center space-x-2">
                            <span class="text-sm text-gray-500">Step {{ $currentEditStep }} of 4</span>
                            <div class="flex space-x-1">
                                @for($i = 1; $i <= 4; $i++)
                                    <div class="w-2 h-2 rounded-full {{ $currentEditStep >= $i ? 'bg-brand-red' : 'bg-gray-300' }}"></div>
                                @endfor
                            </div>
                        </div>

                        <!-- Next/Save Button -->
                        <div>
                            @if($currentEditStep < 4)
                                <button type="button" wire:click="nextStep" class="bg-brand-red text-white px-6 py-3 rounded-xl font-semibold hover:bg-brand-dark-red transition-all duration-200 shadow-lg shadow-brand-red/25 flex items-center">
                                    Next Step
                                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </button>
                            @else
                                <div class="flex items-center space-x-3">
                                    <button type="button" wire:click="backToList" class="bg-gray-100 text-gray-700 px-6 py-3 rounded-xl font-semibold hover:bg-gray-200 transition-colors">
                                        Cancel
                                    </button>
                                    <button type="submit" wire:click="saveProduct" class="bg-gradient-to-r from-brand-red to-brand-dark-red text-white px-8 py-3 rounded-xl font-bold hover:from-brand-dark-red hover:to-red-700 transition-all duration-200 shadow-lg shadow-brand-red/25 flex items-center">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        {{ $currentStep === 'create' ? 'Create Product' : 'Update Product' }}
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                              
                              



                          
        {{-- VIEW PRODUCT DETAILS --}}
    @elseif($currentStep === 'view' && $selectedProduct)
        <!-- View Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <div class="flex items-center space-x-3 mb-2">
                        <h1 class="text-4xl font-bold text-gray-900">{{ $selectedProduct->name }}</h1>
                        @if($selectedProduct->promotional_tag)
                            <span class="bg-yellow-100 text-yellow-800 text-sm font-semibold px-3 py-1 rounded-full">{{ $selectedProduct->promotional_tag }}</span>
                        @endif
                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold bg-{{ $selectedProduct->status_badge_color }}-100 text-{{ $selectedProduct->status_badge_color }}-800 border border-{{ $selectedProduct->status_badge_color }}-200">
                            {{ $selectedProduct->status_badge_text }}
                        </span>
                    </div>
                    <p class="text-gray-600 text-lg">Product Code: {{ $selectedProduct->product_code }}</p>
                    <p class="text-gray-500 text-sm">Created {{ $selectedProduct->created_at->diffForHumans() }}</p>
                </div>
                <div class="flex items-center space-x-3">
                    <button wire:click="editProduct({{ $selectedProduct->id }})" class="bg-brand-red text-white px-6 py-2 rounded-xl font-semibold hover:bg-brand-dark-red transition-all duration-200 shadow-lg shadow-brand-red/25 flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit Product
                    </button>
                    <button wire:click="backToList" class="text-gray-600 hover:text-gray-800 p-2 rounded-xl hover:bg-gray-100 transition-all duration-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Quick Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <!-- Amount Range -->
            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-3xl p-6 border border-blue-100">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-12 h-12 bg-blue-100 rounded-2xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                        </svg>
                    </div>
                    <span class="text-xs font-medium text-blue-600 bg-blue-100 px-2 py-1 rounded-full">AMOUNT</span>
                </div>
                <p class="text-sm font-medium text-gray-600 mb-1">Loan Range</p>
                <p class="text-lg font-bold text-gray-900">{{ $selectedProduct->amount_range }}</p>
            </div>

            <!-- Interest Rate -->
            <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-3xl p-6 border border-green-100">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-12 h-12 bg-green-100 rounded-2xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                        </svg>
                    </div>
                    <span class="text-xs font-medium text-green-600 bg-green-100 px-2 py-1 rounded-full">RATE</span>
                </div>
                <p class="text-sm font-medium text-gray-600 mb-1">Interest Rate</p>
                <p class="text-lg font-bold text-gray-900">{{ $selectedProduct->interest_range }}</p>
            </div>

            <!-- Tenure -->
            <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-3xl p-6 border border-purple-100">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-12 h-12 bg-purple-100 rounded-2xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <span class="text-xs font-medium text-purple-600 bg-purple-100 px-2 py-1 rounded-full">TENURE</span>
                </div>
                <p class="text-sm font-medium text-gray-600 mb-1">Loan Period</p>
                <p class="text-lg font-bold text-gray-900">{{ $selectedProduct->tenure_range }}</p>
            </div>

            <!-- Processing Time -->
            <div class="bg-gradient-to-br from-orange-50 to-red-50 rounded-3xl p-6 border border-orange-100">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-12 h-12 bg-orange-100 rounded-2xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <span class="text-xs font-medium text-orange-600 bg-orange-100 px-2 py-1 rounded-full">TIME</span>
                </div>
                <p class="text-sm font-medium text-gray-600 mb-1">Processing</p>
                <p class="text-lg font-bold text-gray-900">{{ $selectedProduct->approval_time_days }}d approval</p>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column - Detailed Information -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Basic Information -->
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-blue-50 to-indigo-50">
                        <h3 class="text-xl font-bold text-gray-900 flex items-center">
                            <svg class="w-6 h-6 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Product Information
                        </h3>
                    </div>
                    <div class="p-6 space-y-6">
                        @if($selectedProduct->description)
                            <div>
                                <h4 class="text-sm font-semibold text-gray-700 mb-2">Description</h4>
                                <p class="text-gray-600 leading-relaxed">{{ $selectedProduct->description }}</p>
                            </div>
                        @endif

                        @if($selectedProduct->key_features && count($selectedProduct->key_features) > 0)
                            <div>
                                <h4 class="text-sm font-semibold text-gray-700 mb-3">Key Features</h4>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($selectedProduct->key_features as $feature)
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

                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <h4 class="text-sm font-semibold text-gray-700 mb-2">Employment Requirement</h4>
                                <p class="text-gray-600">{{ $selectedProduct->employment_requirement_label }}</p>
                                @if($selectedProduct->min_employment_months)
                                    <p class="text-sm text-gray-500 mt-1">Minimum {{ $selectedProduct->min_employment_months }} months experience</p>
                                @endif
                            </div>
                            <div>
                                <h4 class="text-sm font-semibold text-gray-700 mb-2">Processing Fee</h4>
                                <p class="text-gray-600">{{ $selectedProduct->processing_fee }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Eligibility Criteria -->
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-green-50 to-emerald-50">
                        <h3 class="text-xl font-bold text-gray-900 flex items-center">
                            <svg class="w-6 h-6 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Eligibility Criteria
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Age Requirements -->
                            <div class="bg-gray-50 rounded-2xl p-4">
                                <h4 class="text-sm font-semibold text-gray-700 mb-3 flex items-center">
                                    <svg class="w-4 h-4 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    Age Range
                                </h4>
                                <p class="text-gray-600 font-medium">{{ $selectedProduct->min_age }} - {{ $selectedProduct->max_age }} years</p>
                            </div>

                            <!-- Income Requirements -->
                            <div class="bg-gray-50 rounded-2xl p-4">
                                <h4 class="text-sm font-semibold text-gray-700 mb-3 flex items-center">
                                    <svg class="w-4 h-4 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                    </svg>
                                    Income
                                </h4>
                                @if($selectedProduct->min_monthly_income)
                                    <p class="text-gray-600">Min: TSh {{ number_format($selectedProduct->min_monthly_income) }}/month</p>
                                @else
                                    <p class="text-gray-500">No minimum income requirement</p>
                                @endif
                                @if($selectedProduct->max_debt_to_income_ratio)
                                    <p class="text-gray-500 text-sm mt-1">Max debt ratio: {{ $selectedProduct->max_debt_to_income_ratio }}%</p>
                                @endif
                            </div>

                            <!-- Credit Requirements -->
                            <div class="bg-gray-50 rounded-2xl p-4">
                                <h4 class="text-sm font-semibold text-gray-700 mb-3 flex items-center">
                                    <svg class="w-4 h-4 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                    </svg>
                                    Credit Score
                                </h4>
                                @if($selectedProduct->min_credit_score)
                                    <p class="text-gray-600">Minimum: {{ $selectedProduct->min_credit_score }}</p>
                                @else
                                    <p class="text-gray-500">No credit score requirement</p>
                                @endif
                                @if($selectedProduct->allow_bad_credit)
                                    <span class="inline-flex items-center bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full text-xs font-medium mt-2">
                                        Bad credit accepted
                                    </span>
                                @endif
                            </div>

                            <!-- Business Sectors -->
                            @if($selectedProduct->business_sectors_allowed && count($selectedProduct->business_sectors_allowed) > 0)
                                <div class="bg-gray-50 rounded-2xl p-4">
                                    <h4 class="text-sm font-semibold text-gray-700 mb-3 flex items-center">
                                        <svg class="w-4 h-4 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                        </svg>
                                        Allowed Sectors
                                    </h4>
                                    <p class="text-gray-600 text-sm">{{ count($selectedProduct->business_sectors_allowed) }} sectors allowed</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Requirements -->
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-purple-50 to-pink-50">
                        <h3 class="text-xl font-bold text-gray-900 flex items-center">
                            <svg class="w-6 h-6 text-purple-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Requirements & Documentation
                        </h3>
                    </div>
                    <div class="p-6 space-y-6">
                        <!-- Required Documents -->
                        @if($selectedProduct->required_documents && count($selectedProduct->required_documents) > 0)
                            <div>
                                <h4 class="text-sm font-semibold text-gray-700 mb-3">Required Documents</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                    @foreach($selectedProduct->required_documents as $doc)
                                        <div class="flex items-center bg-gray-50 rounded-xl p-3">
                                            <svg class="w-5 h-5 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                            <span class="text-sm font-medium text-gray-700">{{ $documentTypes[$doc] ?? ucwords(str_replace('_', ' ', $doc)) }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Collateral & Guarantor Requirements -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="bg-gray-50 rounded-2xl p-4">
                                <h4 class="text-sm font-semibold text-gray-700 mb-3 flex items-center">
                                    <svg class="w-4 h-4 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    </svg>
                                    Collateral
                                </h4>
                                @if($selectedProduct->requires_collateral)
                                    <p class="text-red-600 font-medium mb-2">Required</p>
                                    @if($selectedProduct->collateral_types && count($selectedProduct->collateral_types) > 0)
                                        <div class="space-y-1">
                                            @foreach($selectedProduct->collateral_types as $type)
                                                <p class="text-sm text-gray-600">• {{ $collateralTypes[$type] ?? ucwords(str_replace('_', ' ', $type)) }}</p>
                                            @endforeach
                                        </div>
                                    @endif
                                @else
                                    <p class="text-green-600 font-medium">Not Required</p>
                                @endif
                            </div>

                            <div class="bg-gray-50 rounded-2xl p-4">
                                <h4 class="text-sm font-semibold text-gray-700 mb-3 flex items-center">
                                    <svg class="w-4 h-4 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                    Guarantors
                                </h4>
                                @if($selectedProduct->requires_guarantor)
                                    <p class="text-red-600 font-medium">Required</p>
                                    <p class="text-sm text-gray-600 mt-1">Minimum: {{ $selectedProduct->min_guarantors }} guarantor(s)</p>
                                @else
                                    <p class="text-green-600 font-medium">Not Required</p>
                                @endif
                            </div>
                        </div>

                        <!-- Disbursement Methods -->
                        @if($selectedProduct->disbursement_methods && count($selectedProduct->disbursement_methods) > 0)
                            <div>
                                <h4 class="text-sm font-semibold text-gray-700 mb-3">Disbursement Methods</h4>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($selectedProduct->disbursement_methods as $method)
                                        <span class="inline-flex items-center bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">
                                            @switch($method)
                                                @case('bank_transfer')
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                                    </svg>
                                                    Bank Transfer
                                                    @break
                                                @case('mobile_money')
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                                    </svg>
                                                    Mobile Money
                                                    @break
                                                @case('cash')
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                                    </svg>
                                                    Cash Pickup
                                                    @break
                                            @endswitch
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Right Column - Loan Calculator & Quick Actions -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Loan Calculator -->
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden sticky top-6">
                    <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-brand-red to-brand-dark-red text-white">
                        <h3 class="text-xl font-bold flex items-center">
                            <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                            Loan Calculator
                        </h3>
                        <p class="text-red-100 text-sm mt-1">Calculate monthly payments</p>
                    </div>
                    <div class="p-6" x-data="{ 
                        amount: {{ $selectedProduct->min_amount }}, 
                        tenure: {{ $selectedProduct->min_tenure_months }}, 
                        rate: {{ $selectedProduct->interest_rate_min }},
                        monthlyPayment: 0,
                        totalPayment: 0,
                        totalInterest: 0,
                        calculatePayment() {
                            const monthlyRate = this.rate / (100 * 12);
                            if (monthlyRate === 0) {
                                this.monthlyPayment = this.amount / this.tenure;
                            } else {
                                this.monthlyPayment = this.amount * (monthlyRate * Math.pow(1 + monthlyRate, this.tenure)) / (Math.pow(1 + monthlyRate, this.tenure) - 1);
                            }
                            this.totalPayment = this.monthlyPayment * this.tenure;
                            this.totalInterest = this.totalPayment - this.amount;
                        }
                    }" x-init="calculatePayment()">
                        <div class="space-y-4">
                            <!-- Loan Amount -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Loan Amount (TSh)</label>
                                <input x-model="amount" @input="calculatePayment()" type="range" 
                                       min="{{ $selectedProduct->min_amount }}" 
                                       max="{{ $selectedProduct->max_amount }}" 
                                       step="1000"
                                       class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer slider">
                                <div class="flex justify-between text-xs text-gray-500 mt-1">
                                    <span>{{ number_format($selectedProduct->min_amount) }}</span>
                                    <span class="font-bold text-brand-red" x-text="amount.toLocaleString()"></span>
                                    <span>{{ number_format($selectedProduct->max_amount) }}</span>
                                </div>
                            </div>

                            <!-- Loan Tenure -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Loan Period (Months)</label>
                                <input x-model="tenure" @input="calculatePayment()" type="range" 
                                       min="{{ $selectedProduct->min_tenure_months }}" 
                                       max="{{ $selectedProduct->max_tenure_months }}" 
                                       class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer slider">
                                <div class="flex justify-between text-xs text-gray-500 mt-1">
                                    <span>{{ $selectedProduct->min_tenure_months }}m</span>
                                    <span class="font-bold text-brand-red" x-text="tenure + 'm'"></span>
                                    <span>{{ $selectedProduct->max_tenure_months }}m</span>
                                </div>
                            </div>

                            <!-- Interest Rate -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Interest Rate (%)</label>
                                <input x-model="rate" @input="calculatePayment()" type="range" 
                                       min="{{ $selectedProduct->interest_rate_min }}" 
                                       max="{{ $selectedProduct->interest_rate_max }}" 
                                       step="0.1"
                                       class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer slider">
                                <div class="flex justify-between text-xs text-gray-500 mt-1">
                                    <span>{{ $selectedProduct->interest_rate_min }}%</span>
                                    <span class="font-bold text-brand-red" x-text="rate + '%'"></span>
                                    <span>{{ $selectedProduct->interest_rate_max }}%</span>
                                </div>
                            </div>

                            <!-- Results -->
                            <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl p-4 mt-6">
                                <h4 class="text-sm font-bold text-gray-700 mb-3">Payment Breakdown</h4>
                                <div class="space-y-3">
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-600">Monthly Payment</span>
                                        <span class="text-lg font-bold text-brand-red" x-text="'TSh ' + Math.round(monthlyPayment).toLocaleString()"></span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-600">Total Payment</span>
                                        <span class="text-sm font-semibold text-gray-900" x-text="'TSh ' + Math.round(totalPayment).toLocaleString()"></span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-600">Total Interest</span>
                                        <span class="text-sm font-semibold text-gray-900" x-text="'TSh ' + Math.round(totalInterest).toLocaleString()"></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Apply Button -->
                            <button class="w-full bg-gradient-to-r from-brand-red to-brand-dark-red text-white py-3 rounded-xl font-bold hover:from-brand-dark-red hover:to-red-700 transition-all duration-200 shadow-lg shadow-brand-red/25">
                                Apply for This Loan
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-100">
                        <h3 class="text-lg font-bold text-gray-900">Quick Actions</h3>
                    </div>
                    <div class="p-6 space-y-3">
                        <button wire:click="editProduct({{ $selectedProduct->id }})" class="w-full bg-blue-50 text-blue-700 py-3 px-4 rounded-xl font-semibold hover:bg-blue-100 transition-colors flex items-center justify-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Edit Product
                        </button>

                        <button wire:click="duplicateProduct({{ $selectedProduct->id }})" class="w-full bg-purple-50 text-purple-700 py-3 px-4 rounded-xl font-semibold hover:bg-purple-100 transition-colors flex items-center justify-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                            </svg>
                            Duplicate Product
                        </button>

                        <button wire:click="toggleProductStatus({{ $selectedProduct->id }})" class="w-full {{ $selectedProduct->is_active ? 'bg-yellow-50 text-yellow-700 hover:bg-yellow-100' : 'bg-green-50 text-green-700 hover:bg-green-100' }} py-3 px-4 rounded-xl font-semibold transition-colors flex items-center justify-center">
                            @if($selectedProduct->is_active)
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
                            <button onclick="window.print()" class="w-full bg-gray-50 text-gray-700 py-3 px-4 rounded-xl font-semibold hover:bg-gray-100 transition-colors flex items-center justify-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                                </svg>
                                Print Details
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Product Statistics -->
                <div class="bg-gradient-to-br from-indigo-50 to-purple-50 rounded-3xl shadow-sm border border-indigo-100 overflow-hidden">
                    <div class="p-6 border-b border-indigo-100">
                        <h3 class="text-lg font-bold text-gray-900 flex items-center">
                            <svg class="w-5 h-5 text-indigo-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                            Product Statistics
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Applications</span>
                                <span class="text-lg font-bold text-gray-900">{{ rand(50, 500) }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Approved</span>
                                <span class="text-lg font-bold text-green-600">{{ rand(30, 200) }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Success Rate</span>
                                <span class="text-lg font-bold text-blue-600">{{ rand(60, 85) }}%</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Avg. Amount</span>
                                <span class="text-lg font-bold text-purple-600">TSh {{ number_format(rand($selectedProduct->min_amount, $selectedProduct->max_amount)) }}</span>
                            </div>
                        </div>
                        
                        <div class="mt-6 pt-4 border-t border-indigo-200">
                            <div class="text-center">
                                <p class="text-sm text-gray-600 mb-2">Performance Rating</p>
                                <div class="flex items-center justify-center space-x-1">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="w-5 h-5 {{ $i <= 4 ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    @endfor
                                </div>
                                <p class="text-xs text-gray-500 mt-1">4.0 out of 5 stars</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Details Section -->
        <div class="mt-8">
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100">
                    <h3 class="text-xl font-bold text-gray-900 flex items-center">
                        <svg class="w-6 h-6 text-gray-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Additional Information
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Terms and Conditions -->
                        @if($selectedProduct->terms_and_conditions)
                            <div>
                                <h4 class="text-lg font-semibold text-gray-700 mb-3">Terms and Conditions</h4>
                                <div class="bg-gray-50 rounded-2xl p-4">
                                    <p class="text-gray-600 text-sm leading-relaxed">{{ $selectedProduct->terms_and_conditions }}</p>
                                </div>
                            </div>
                        @endif

                        <!-- Eligibility Summary -->
                        @if($selectedProduct->eligibility_criteria)
                            <div>
                                <h4 class="text-lg font-semibold text-gray-700 mb-3">Eligibility Summary</h4>
                                <div class="bg-gray-50 rounded-2xl p-4">
                                    <p class="text-gray-600 text-sm leading-relaxed">{{ $selectedProduct->eligibility_criteria }}</p>
                                </div>
                            </div>
                        @endif

                        <!-- Fee Breakdown -->
                        <div>
                            <h4 class="text-lg font-semibold text-gray-700 mb-3">Fee Structure</h4>
                            <div class="bg-gray-50 rounded-2xl p-4 space-y-3">
                                @if($selectedProduct->processing_fee_percentage > 0 || $selectedProduct->processing_fee_fixed > 0)
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-600">Processing Fee</span>
                                        <span class="text-sm font-semibold text-gray-900">{{ $selectedProduct->processing_fee }}</span>
                                    </div>
                                @endif
                                @if($selectedProduct->late_payment_fee > 0)
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-600">Late Payment Fee</span>
                                        <span class="text-sm font-semibold text-gray-900">TSh {{ number_format($selectedProduct->late_payment_fee) }}</span>
                                    </div>
                                @endif
                                @if($selectedProduct->early_repayment_fee_percentage > 0)
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-600">Early Repayment Fee</span>
                                        <span class="text-sm font-semibold text-gray-900">{{ $selectedProduct->early_repayment_fee_percentage }}%</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Auto Approval Settings -->
                        <div>
                            <h4 class="text-lg font-semibold text-gray-700 mb-3">Auto Approval</h4>
                            <div class="bg-gray-50 rounded-2xl p-4">
                                @if($selectedProduct->auto_approval_eligible)
                                    <div class="flex items-center text-green-600 mb-2">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        <span class="font-semibold">Enabled</span>
                                    </div>
                                    @if($selectedProduct->auto_approval_max_amount)
                                        <p class="text-sm text-gray-600">Up to TSh {{ number_format($selectedProduct->auto_approval_max_amount) }}</p>
                                    @endif
                                @else
                                    <div class="flex items-center text-gray-500">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                        <span class="font-semibold">Disabled</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    @endif

<style>
    /* Custom slider styling */
    .slider::-webkit-slider-thumb {
        appearance: none;
        height: 20px;
        width: 20px;
        background: #C40F12;
        border-radius: 50%;
        cursor: pointer;
        box-shadow: 0 2px 6px rgba(196, 15, 18, 0.3);
    }

    .slider::-moz-range-thumb {
        height: 20px;
        width: 20px;
        background: #C40F12;
        border-radius: 50%;
        cursor: pointer;
        border: none;
        box-shadow: 0 2px 6px rgba(196, 15, 18, 0.3);
    }

    .slider::-webkit-slider-track {
        background: #E5E7EB;
        height: 8px;
        border-radius: 4px;
    }

    .slider::-moz-range-track {
        background: #E5E7EB;
        height: 8px;
        border-radius: 4px;
        border: none;
    }

    /* Print styles */
    @media print {
        .sticky { position: static !important; }
        button { display: none !important; }
        .shadow-sm, .shadow-lg { box-shadow: none !important; }
    }

    /* Enhanced animations */
    .animate-fade-in-up {
        animation: fadeInUp 0.6s ease-out;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Card hover effects */
    .hover-lift:hover {
        transform: translateY(-4px);
        transition: transform 0.3s ease;
    }
</style>

<script>
    // Add interactive features
    document.addEventListener('livewire:navigated', function() {
        // Add smooth scroll behavior
        document.documentElement.style.scrollBehavior = 'smooth';

        // Add hover effects to cards
        const cards = document.querySelectorAll('.bg-white.rounded-3xl');
        cards.forEach(card => {
            card.classList.add('hover-lift');
        });

        // Add click-to-copy functionality for product code
        const productCode = document.querySelector('[data-product-code]');
        if (productCode) {
            productCode.addEventListener('click', function() {
                navigator.clipboard.writeText(this.textContent);
                // Show toast notification
                const toast = document.createElement('div');
                toast.className = 'fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded-xl z-50';
                toast.textContent = 'Product code copied to clipboard!';
                document.body.appendChild(toast);
                setTimeout(() => toast.remove(), 3000);
            });
        }

        // Initialize Alpine.js data
        if (window.Alpine) {
            window.Alpine.start();
        }
    });

    // Enhanced calculator functionality
    function initLoanCalculator() {
        const calculator = document.querySelector('[x-data]');
        if (calculator) {
            // Add keyboard navigation for sliders
            const sliders = calculator.querySelectorAll('input[type="range"]');
            sliders.forEach(slider => {
                slider.addEventListener('keydown', function(e) {
                    const step = parseFloat(this.step) || 1;
                    const currentValue = parseFloat(this.value);
                    
                    switch(e.key) {
                        case 'ArrowLeft':
                        case 'ArrowDown':
                            this.value = Math.max(this.min, currentValue - step);
                            this.dispatchEvent(new Event('input'));
                            break;
                        case 'ArrowRight':
                        case 'ArrowUp':
                            this.value = Math.min(this.max, currentValue + step);
                            this.dispatchEvent(new Event('input'));
                            break;
                    }
                });
            });
        }
    }

    // Initialize when DOM is loaded
    document.addEventListener('DOMContentLoaded', initLoanCalculator);
</script>









</div>

<script>
    // Auto-hide flash messages after 5 seconds
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {
            const alerts = document.querySelectorAll('[role="alert"]');
            alerts.forEach(function(alert) {
                alert.style.transition = 'opacity 0.5s ease-out';
                alert.style.opacity = '0';
                setTimeout(function() {
                    alert.remove();
                }, 500);
            });
        }, 5000);
    });

    // Add hover effects for product cards
    document.addEventListener('livewire:navigated', function() {
        const productCards = document.querySelectorAll('.group');
        productCards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-4px)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });
    });
</script>


</div>
