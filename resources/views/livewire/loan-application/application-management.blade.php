<div>

<div class="w-full">
    {{-- LIST VIEW --}}
    @if($currentStep === 'list')
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-4xl font-bold text-gray-900 mb-2">My Loan Applications</h1>
                    <p class="text-gray-600 text-lg">Track and manage your loan applications</p>
                </div>
                <div class="flex items-center space-x-3">
                    <button wire:click="startNewApplication" class="bg-brand-red text-white px-6 py-2 rounded-xl font-semibold hover:bg-brand-dark-red transition-all duration-200 shadow-lg shadow-brand-red/25">
                        + New Application
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

        <!-- Search and Filters -->
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
                        <input wire:model.live="search" type="text" class="block w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-red focus:border-brand-red placeholder-gray-500 text-gray-900 text-sm transition-all duration-200" placeholder="Search applications...">
                    </div>
                </div>

                <!-- Filters -->
                <div class="flex items-center space-x-4">
                    <select wire:model.live="statusFilter" class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-brand-red focus:border-brand-red">
                        <option value="all">All Status</option>
                        <option value="draft">Draft</option>
                        <option value="submitted">Submitted</option>
                        <option value="under_review">Under Review</option>
                        <option value="approved">Approved</option>
                        <option value="rejected">Rejected</option>
                        <option value="disbursed">Disbursed</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Applications List -->
        <div class="space-y-6">
            @forelse($applications as $application)
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg transition-all duration-300">
                    <!-- Application Header -->
                    <div class="p-6 border-b border-gray-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="flex items-center space-x-3 mb-2">
                                    <h3 class="text-xl font-bold text-gray-900">{{ $application->application_number }}</h3>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-{{ $application->status_badge['color'] }}-100 text-{{ $application->status_badge['color'] }}-800">
                                        {{ $application->status_badge['text'] }}
                                    </span>
                                </div>
                                <p class="text-gray-600">{{ $application->full_name }}</p>
                                <p class="text-sm text-gray-500">Applied {{ $application->created_at->diffForHumans() }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-2xl font-bold text-gray-900">TSh {{ number_format($application->requested_amount) }}</p>
                                <p class="text-sm text-gray-600">{{ $application->requested_tenure_months }} months</p>
                                @if($application->loanProduct)
                                    <p class="text-sm text-blue-600 font-medium">{{ $application->loanProduct->name }}</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Application Details -->
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <h4 class="text-sm font-semibold text-gray-700 mb-2">Personal Information</h4>
                                <p class="text-sm text-gray-600">{{ $application->employment_status }}</p>
                                <p class="text-sm text-gray-600">{{ $application->age }} years old</p>
                                <p class="text-sm text-gray-600">{{ $application->current_city }}, {{ $application->current_region }}</p>
                            </div>
                            <div>
                                <h4 class="text-sm font-semibold text-gray-700 mb-2">Financial Information</h4>
                                <p class="text-sm text-gray-600">Monthly Income: TSh {{ number_format($application->total_monthly_income) }}</p>
                                <p class="text-sm text-gray-600">Monthly Expenses: TSh {{ number_format($application->monthly_expenses) }}</p>
                                @if($application->debt_to_income_ratio)
                                    <p class="text-sm text-gray-600">Debt Ratio: {{ $application->debt_to_income_ratio }}%</p>
                                @endif
                            </div>
                            <div>
                                <h4 class="text-sm font-semibold text-gray-700 mb-2">Application Progress</h4>
                                <div class="flex items-center space-x-2 mb-2">
                                    <div class="flex-1 bg-gray-200 rounded-full h-2">
                                        <div class="bg-brand-red h-2 rounded-full" style="width: {{ $application->completion_percentage }}%"></div>
                                    </div>
                                    <span class="text-sm text-gray-600">{{ $application->completion_percentage }}%</span>
                                </div>
                                @if($application->submitted_at)
                                    <p class="text-sm text-gray-600">Submitted: {{ $application->submitted_at->format('M d, Y') }}</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-2">
                                <button wire:click="viewApplication({{ $application->id }})" class="text-blue-600 hover:text-blue-800 p-2 rounded-xl hover:bg-blue-50 transition-all duration-200" title="View Details">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </button>

                                @if($application->isEditable())
                                    <button wire:click="editApplication({{ $application->id }})" class="text-gray-600 hover:text-gray-800 p-2 rounded-xl hover:bg-gray-100 transition-all duration-200" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                @endif

                                @if($application->status === 'submitted' && $application->matching_products)
                                    <button wire:click="viewMatchingProducts({{ $application->id }})" class="text-green-600 hover:text-green-800 p-2 rounded-xl hover:bg-green-50 transition-all duration-200" title="View Matching Products">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </button>
                                @endif

                                @if(in_array($application->status, ['draft', 'submitted']))
                                    <button wire:click="cancelApplication({{ $application->id }})" 
                                            wire:confirm="Are you sure you want to cancel this application?"
                                            class="text-red-600 hover:text-red-800 p-2 rounded-xl hover:bg-red-50 transition-all duration-200" title="Cancel">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                @endif
                            </div>

                            @if($application->matching_products && count($application->matching_products) > 0)
                                <div class="flex items-center space-x-2">
                                    <span class="text-sm text-green-600 font-medium">{{ count($application->matching_products) }} matching products</span>
                                    <button wire:click="viewMatchingProducts({{ $application->id }})" class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm font-medium hover:bg-green-200 transition-colors">
                                        View Options
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-3xl shadow-sm p-12 text-center border border-gray-100">
                    <div class="w-20 h-20 bg-gradient-to-br from-brand-red to-brand-dark-red rounded-3xl flex items-center justify-center mx-auto mb-6 shadow-lg">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">No applications found</h3>
                    <p class="text-gray-500 mb-6">Start your loan application journey today.</p>
                    <button wire:click="startNewApplication" class="bg-brand-red text-white px-8 py-3 rounded-xl font-semibold hover:bg-brand-dark-red transition-all duration-200 shadow-lg shadow-brand-red/25">
                        Start Application
                    </button>
                </div>
            @endforelse
        </div>

    {{-- CREATE/EDIT APPLICATION FORM --}}
    @elseif(in_array($currentStep, ['create', 'edit']))
        <!-- Form Header with Progress -->
        <div class="mb-8">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-4xl font-bold text-gray-900 mb-2">
                        {{ $currentStep === 'create' ? 'New' : 'Edit' }} Loan Application
                    </h1>
                    <p class="text-gray-600 text-lg">Step {{ $currentFormStep }} of 5 - Complete all steps to submit your application</p>
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
                    @for($i = 1; $i <= 5; $i++)
                        <div class="flex items-center {{ $i < 5 ? 'flex-1' : '' }}">
                            <div class="flex items-center space-x-3">
                                <button wire:click="goToStep({{ $i }})" class="w-12 h-12 rounded-full flex items-center justify-center font-bold text-sm transition-all duration-200 relative
                                    {{ $currentFormStep >= $i ? 'bg-brand-red text-white shadow-lg shadow-brand-red/25' : 'bg-gray-200 text-gray-600 hover:bg-gray-300' }}">
                                    @if($currentFormStep > $i)
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    @else
                                        {{ $i }}
                                    @endif
                                </button>
                                <div class="hidden sm:block">
                                    <p class="text-sm font-bold {{ $currentFormStep >= $i ? 'text-brand-red' : 'text-gray-500' }}">
                                        @switch($i)
                                            @case(1) Loan Details @break
                                            @case(2) Personal Info @break
                                            @case(3) Address @break
                                            @case(4) Employment @break
                                            @case(5) Emergency Contact @break
                                        @endswitch
                                    </p>
                                </div>
                            </div>
                            @if($i < 5)
                                <div class="flex-1 h-1 mx-4 rounded-full {{ $currentFormStep > $i ? 'bg-brand-red' : 'bg-gray-200' }}"></div>
                            @endif
                        </div>
                    @endfor
                </div>
            </div>
        </div>

        <!-- Form Content -->
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <form wire:submit="saveApplication">
                <!-- Step 1: Loan Details -->
                @if($currentFormStep === 1)
                    <div class="p-8">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-2xl font-bold text-gray-900">Loan Details</h2>
                            <div class="bg-blue-50 px-4 py-2 rounded-full">
                                <span class="text-blue-700 text-sm font-medium">Step 1 of 5</span>
                            </div>
                        </div>
                        
                        <div class="space-y-8">
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Requested Amount (TSh) *</label>
                                    <input wire:model="requested_amount" type="number" step="1000" min="1000" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-brand-red focus:border-brand-red text-lg font-bold">
                                    @error('requested_amount') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Loan Period (Months) *</label>
                                    <input wire:model="requested_tenure_months" type="number" min="1" max="120" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-brand-red focus:border-brand-red text-lg font-bold">
                                    @error('requested_tenure_months') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Loan Purpose</label>
                                <select wire:model="loan_purpose" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-brand-red focus:border-brand-red">
                                    <option value="">Select purpose</option>
                                    <option value="business">Business Investment</option>
                                    <option value="education">Education</option>
                                    <option value="home_improvement">Home Improvement</option>
                                    <option value="medical">Medical Expenses</option>
                                    <option value="debt_consolidation">Debt Consolidation</option>
                                    <option value="emergency">Emergency</option>
                                    <option value="other">Other</option>
                                </select>
                                @error('loan_purpose') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                <!-- Step 2: Personal Information -->
                @elseif($currentFormStep === 2)
                    <div class="p-8">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-2xl font-bold text-gray-900">Personal Information</h2>
                            <div class="bg-blue-50 px-4 py-2 rounded-full">
                                <span class="text-blue-700 text-sm font-medium">Step 2 of 5</span>
                            </div>
                        </div>
                        
                        <div class="space-y-8">
                            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">First Name *</label>
                                    <input wire:model="first_name" type="text" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-brand-red focus:border-brand-red">
                                    @error('first_name') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Middle Name</label>
                                    <input wire:model="middle_name" type="text" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-brand-red focus:border-brand-red">
                                    @error('middle_name') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Last Name *</label>
                                    <input wire:model="last_name" type="text" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-brand-red focus:border-brand-red">
                                    @error('last_name') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Date of Birth *</label>
                                    <input wire:model="date_of_birth" type="date" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-brand-red focus:border-brand-red">
                                    @error('date_of_birth') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Gender *</label>
                                    <select wire:model="gender" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-brand-red focus:border-brand-red">
                                        <option value="">Select gender</option>
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                        <option value="other">Other</option>
                                    </select>
                                    @error('gender') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Marital Status *</label>
                                    <select wire:model="marital_status" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-brand-red focus:border-brand-red">
                                        <option value="">Select status</option>
                                        @foreach($maritalStatuses as $key => $status)
                                            <option value="{{ $key }}">{{ $status }}</option>
                                        @endforeach
                                    </select>
                                    @error('marital_status') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">National ID *</label>
                                    <input wire:model="national_id" type="text" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-brand-red focus:border-brand-red">
                                    @error('national_id') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number *</label>
                                    <input wire:model="phone_number" type="tel" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-brand-red focus:border-brand-red">
                                    @error('phone_number') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                                    <input wire:model="email" type="email" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-brand-red focus:border-brand-red">
                                    @error('email') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                <!-- Step 3: Address Information -->
                @elseif($currentFormStep === 3)
                    <div class="p-8">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-2xl font-bold text-gray-900">Address Information</h2>
                            <div class="bg-blue-50 px-4 py-2 rounded-full">
                                <span class="text-blue-700 text-sm font-medium">Step 3 of 5</span>
                            </div>
                        </div>
                        
                        <div class="space-y-8">
                            <!-- Current Address -->
                            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl p-6 border border-blue-100">
                                <h3 class="text-lg font-bold text-gray-900 mb-4">Current Address</h3>
                                <div class="space-y-6">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Street Address *</label>
                                        <textarea wire:model="current_address" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-brand-red focus:border-brand-red"></textarea>
                                        @error('current_address') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">City *</label>
                                            <input wire:model="current_city" type="text" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-brand-red focus:border-brand-red">
                                            @error('current_city') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Region *</label>
                                            <input wire:model="current_region" type="text" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-brand-red focus:border-brand-red">
                                            @error('current_region') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Postal Code</label>
                                            <input wire:model="current_postal_code" type="text" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-brand-red focus:border-brand-red">
                                            @error('current_postal_code') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Years at Current Address *</label>
                                        <input wire:model="years_at_current_address" type="number" min="0" max="50" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-brand-red focus:border-brand-red">
                                        @error('years_at_current_address') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Permanent Address -->
                            <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-2xl p-6 border border-green-100">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="text-lg font-bold text-gray-900">Permanent Address</h3>
                                    <label class="flex items-center cursor-pointer">
                                        <input wire:model="is_permanent_same_as_current" type="checkbox" class="text-brand-red focus:ring-brand-red rounded">
                                        <span class="ml-2 text-sm font-medium text-gray-700">Same as current address</span>
                                    </label>
                                </div>
                                
                                @if(!$is_permanent_same_as_current)
                                    <div class="space-y-6">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Permanent Address</label>
                                            <textarea wire:model="permanent_address" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-brand-red focus:border-brand-red"></textarea>
                                        </div>
                                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">City</label>
                                                <input wire:model="permanent_city" type="text" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-brand-red focus:border-brand-red">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Region</label>
                                                <input wire:model="permanent_region" type="text" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-brand-red focus:border-brand-red">
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                <!-- Step 4: Employment & Financial Information -->
                @elseif($currentFormStep === 4)
                    <div class="p-8">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-2xl font-bold text-gray-900">Employment & Financial Information</h2>
                            <div class="bg-blue-50 px-4 py-2 rounded-full">
                                <span class="text-blue-700 text-sm font-medium">Step 4 of 5</span>
                            </div>
                        </div>
                        
                        <div class="space-y-8">
                            <!-- Employment Status -->
                            <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-2xl p-6 border border-purple-100">
                                <h3 class="text-lg font-bold text-gray-900 mb-4">Employment Status</h3>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Current Employment Status *</label>
                                    <select wire:model="employment_status" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-brand-red focus:border-brand-red">
                                        <option value="">Select employment status</option>
                                        <option value="employed">Employed</option>
                                        <option value="self_employed">Self Employed</option>
                                        <option value="unemployed">Unemployed</option>
                                        <option value="retired">Retired</option>
                                        <option value="student">Student</option>
                                    </select>
                                    @error('employment_status') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <!-- Employment Details (if employed) -->
                            @if($employment_status === 'employed')
                                <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl p-6 border border-blue-100">
                                    <h3 class="text-lg font-bold text-gray-900 mb-4">Employment Details</h3>
                                    <div class="space-y-6">
                                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Employer Name *</label>
                                                <input wire:model="employer_name" type="text" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-brand-red focus:border-brand-red">
                                                @error('employer_name') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Job Title *</label>
                                                <input wire:model="job_title" type="text" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-brand-red focus:border-brand-red">
                                                @error('job_title') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Employment Sector</label>
                                                <select wire:model="employment_sector" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-brand-red focus:border-brand-red">
                                                    <option value="">Select sector</option>
                                                    @foreach($employmentSectors as $key => $sector)
                                                        <option value="{{ $key }}">{{ $sector }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Months with Current Employer</label>
                                                <input wire:model="months_with_current_employer" type="number" min="0" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-brand-red focus:border-brand-red">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Business Details (if self-employed) -->
                            @if($employment_status === 'self_employed')
                                <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-2xl p-6 border border-green-100">
                                    <h3 class="text-lg font-bold text-gray-900 mb-4">Business Details</h3>
                                    <div class="space-y-6">
                                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Business Name *</label>
                                                <input wire:model="business_name" type="text" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-brand-red focus:border-brand-red">
                                                @error('business_name') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Business Type *</label>
                                                <select wire:model="business_type" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-brand-red focus:border-brand-red">
                                                    <option value="">Select business type</option>
                                                    @foreach($businessTypes as $key => $type)
                                                        <option value="{{ $key }}">{{ $type }}</option>
                                                    @endforeach
                                                </select>
                                                @error('business_type') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Registration Number</label>
                                                <input wire:model="business_registration_number" type="text" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-brand-red focus:border-brand-red">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Years in Business</label>
                                                <input wire:model="years_in_business" type="number" min="0" max="50" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-brand-red focus:border-brand-red">
                                            </div>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Business Address</label>
                                            <textarea wire:model="business_address" rows="2" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-brand-red focus:border-brand-red"></textarea>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Financial Information -->
                            <div class="bg-gradient-to-br from-yellow-50 to-orange-50 rounded-2xl p-6 border border-yellow-100">
                                <h3 class="text-lg font-bold text-gray-900 mb-4">Financial Information</h3>
                                <div class="space-y-6">
                                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                        @if($employment_status === 'employed')
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Monthly Salary (TSh) *</label>
                                                <input wire:model="monthly_salary" type="number" step="1000" min="0" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-brand-red focus:border-brand-red">
                                                @error('monthly_salary') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                                            </div>
                                        @endif
                                        
                                        @if($employment_status === 'self_employed')
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Monthly Business Income (TSh) *</label>
                                                <input wire:model="monthly_business_income" type="number" step="1000" min="0" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-brand-red focus:border-brand-red">
                                                @error('monthly_business_income') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                                            </div>
                                        @endif
                                        
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Other Monthly Income (TSh)</label>
                                            <input wire:model="other_monthly_income" type="number" step="1000" min="0" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-brand-red focus:border-brand-red">
                                        </div>
                                    </div>
                                    
                                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Monthly Expenses (TSh) *</label>
                                            <input wire:model="monthly_expenses" type="number" step="1000" min="0" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-brand-red focus:border-brand-red">
                                            @error('monthly_expenses') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Existing Loan Payments (TSh)</label>
                                            <input wire:model="existing_loan_payments" type="number" step="1000" min="0" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-brand-red focus:border-brand-red">
                                        </div>
                                    </div>

                                    <!-- Display calculated total income -->
                                    @if($total_monthly_income > 0)
                                        <div class="bg-white rounded-xl p-4 border border-yellow-200">
                                            <p class="text-sm text-yellow-700">
                                                <strong>Total Monthly Income:</strong> TSh {{ number_format($total_monthly_income) }}
                                            </p>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Bank Information -->
                            <div class="bg-gradient-to-br from-indigo-50 to-blue-50 rounded-2xl p-6 border border-indigo-100">
                                <h3 class="text-lg font-bold text-gray-900 mb-4">Bank Information</h3>
                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Bank Name</label>
                                        <input wire:model="bank_name" type="text" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-brand-red focus:border-brand-red">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Account Number</label>
                                        <input wire:model="account_number" type="text" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-brand-red focus:border-brand-red">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Account Name</label>
                                        <input wire:model="account_name" type="text" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-brand-red focus:border-brand-red">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Account Type</label>
                                        <select wire:model="account_type" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-brand-red focus:border-brand-red">
                                            <option value="savings">Savings</option>
                                            <option value="current">Current</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Credit Information -->
                            <div class="bg-gradient-to-br from-red-50 to-pink-50 rounded-2xl p-6 border border-red-100">
                                <h3 class="text-lg font-bold text-gray-900 mb-4">Credit Information</h3>
                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Credit Score (if known)</label>
                                        <input wire:model="credit_score" type="number" min="300" max="850" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-brand-red focus:border-brand-red">
                                        <p class="text-sm text-gray-500 mt-1">Leave empty if you don't know your credit score</p>
                                    </div>
                                    <div class="flex items-center pt-8">
                                        <label class="flex items-center cursor-pointer">
                                            <input wire:model="has_bad_credit_history" type="checkbox" class="text-brand-red focus:ring-brand-red rounded">
                                            <span class="ml-2 text-sm font-medium text-gray-700">I have a bad credit history</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                <!-- Step 5: Emergency Contact & Additional Info -->
                @elseif($currentFormStep === 5)
                    <div class="p-8">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-2xl font-bold text-gray-900">Emergency Contact & Additional Information</h2>
                            <div class="bg-blue-50 px-4 py-2 rounded-full">
                                <span class="text-blue-700 text-sm font-medium">Step 5 of 5</span>
                            </div>
                        </div>
                        
                        <div class="space-y-8">
                            <!-- Emergency Contact -->
                            <div class="bg-gradient-to-br from-red-50 to-pink-50 rounded-2xl p-6 border border-red-100">
                                <h3 class="text-lg font-bold text-gray-900 mb-4">Emergency Contact</h3>
                                <div class="space-y-6">
                                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Contact Name *</label>
                                            <input wire:model="emergency_contact_name" type="text" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-brand-red focus:border-brand-red">
                                            @error('emergency_contact_name') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Relationship *</label>
                                            <select wire:model="emergency_contact_relationship" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-brand-red focus:border-brand-red">
                                                <option value="">Select relationship</option>
                                                <option value="spouse">Spouse</option>
                                                <option value="parent">Parent</option>
                                                <option value="sibling">Sibling</option>
                                                <option value="child">Child</option>
                                                <option value="friend">Friend</option>
                                                <option value="colleague">Colleague</option>
                                                <option value="other">Other</option>
                                            </select>
                                            @error('emergency_contact_relationship') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number *</label>
                                            <input wire:model="emergency_contact_phone" type="tel" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-brand-red focus:border-brand-red">
                                            @error('emergency_contact_phone') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                                            <input wire:model="emergency_contact_address" type="text" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-brand-red focus:border-brand-red">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Disbursement Preference -->
                            <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-2xl p-6 border border-green-100">
                                <h3 class="text-lg font-bold text-gray-900 mb-4">Disbursement Preference</h3>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Preferred Disbursement Method</label>
                                    <select wire:model="preferred_disbursement_method" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-brand-red focus:border-brand-red">
                                        <option value="bank_transfer">Bank Transfer</option>
                                        <option value="mobile_money">Mobile Money</option>
                                        <option value="cash">Cash Pickup</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Document Upload Section -->
                            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl p-6 border border-blue-100">
                                <h3 class="text-lg font-bold text-gray-900 mb-4">Required Documents</h3>
                                <p class="text-sm text-gray-600 mb-4">Upload the required documents for your loan application.</p>
                                
                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                    @foreach($requiredDocuments as $key => $name)
                                        <div class="border border-gray-200 rounded-xl p-4">
                                            <h4 class="font-semibold text-gray-700 mb-2">{{ $name }}</h4>
                                            
                                            @if(isset($uploadedDocuments[$key]))
                                                <!-- File uploaded -->
                                                <div class="flex items-center justify-between bg-green-50 p-3 rounded-lg">
                                                    <div class="flex items-center space-x-2">
                                                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                        </svg>
                                                        <span class="text-sm text-green-700 font-medium">{{ $uploadedDocuments[$key]['name'] }}</span>
                                                    </div>
                                                    <button wire:click="removeDocument('{{ $key }}')" class="text-red-600 hover:text-red-800">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                        </svg>
                                                    </button>
                                                </div>
                                            @else
                                                <!-- Upload interface -->
                                                <div class="space-y-2">
                                                    <input wire:model="documents.{{ $key }}" type="file" class="block w-full text-sm text-gray-500
                                                        file:mr-4 file:py-2 file:px-4
                                                        file:rounded-full file:border-0
                                                        file:text-sm file:font-semibold
                                                        file:bg-brand-red file:text-white
                                                        hover:file:bg-brand-dark-red
                                                        file:cursor-pointer"
                                                        accept=".pdf,.jpg,.jpeg,.png">
                                                    @if(isset($documents[$key]))
                                                        <button wire:click="uploadDocument('{{ $key }}')" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                                            Upload File
                                                        </button>
                                                    @endif
                                                    @error('documents.' . $key) 
                                                        <span class="text-red-500 text-sm">{{ $message }}</span> 
                                                    @enderror
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                                
                                <p class="text-sm text-gray-500 mt-4">
                                    Accepted formats: PDF, JPG, PNG. Maximum file size: 5MB per file.
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Form Navigation -->
                <div class="px-8 py-6 bg-gray-50 border-t border-gray-200">
                    <div class="flex items-center justify-between">
                        <!-- Previous Button -->
                        <div>
                            @if($currentFormStep > 1)
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
                            <span class="text-sm text-gray-500">Step {{ $currentFormStep }} of 5</span>
                            <div class="flex space-x-1">
                                @for($i = 1; $i <= 5; $i++)
                                    <div class="w-2 h-2 rounded-full {{ $currentFormStep >= $i ? 'bg-brand-red' : 'bg-gray-300' }}"></div>
                                @endfor
                            </div>
                        </div>

                        <!-- Next/Save/Submit Buttons -->
                        <div class="flex items-center space-x-3">
                            @if($currentFormStep < 5)
                                <!-- Save Draft Button -->
                                <button type="button" wire:click="saveApplication(false)" class="bg-gray-100 text-gray-700 px-6 py-3 rounded-xl font-semibold hover:bg-gray-200 transition-colors">
                                    Save Draft
                                </button>
                                <!-- Next Step Button -->
                                <button type="button" wire:click="nextStep" class="bg-brand-red text-white px-6 py-3 rounded-xl font-semibold hover:bg-brand-dark-red transition-all duration-200 shadow-lg shadow-brand-red/25 flex items-center">
                                    Next Step
                                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </button>
                            @else
                                <!-- Final Step Buttons -->
                                <button type="button" wire:click="saveApplication(false)" class="bg-gray-100 text-gray-700 px-6 py-3 rounded-xl font-semibold hover:bg-gray-200 transition-colors">
                                    Save Draft
                                </button>
                                <button type="button" wire:click="saveApplication(true)" class="bg-gradient-to-r from-brand-red to-brand-dark-red text-white px-8 py-3 rounded-xl font-bold hover:from-brand-dark-red hover:to-red-700 transition-all duration-200 shadow-lg shadow-brand-red/25 flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                    </svg>
                                    Submit Application
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </form>
        </div>

    {{-- VIEW APPLICATION DETAILS --}}
    @elseif($currentStep === 'view' && $selectedApplication)
        <!-- View Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <div class="flex items-center space-x-3 mb-2">
                        <h1 class="text-4xl font-bold text-gray-900">{{ $selectedApplication->application_number }}</h1>
                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold bg-{{ $selectedApplication->status_badge['color'] }}-100 text-{{ $selectedApplication->status_badge['color'] }}-800">
                            {{ $selectedApplication->status_badge['text'] }}
                        </span>
                    </div>
                    <p class="text-gray-600 text-lg">{{ $selectedApplication->full_name }}</p>
                    <p class="text-gray-500 text-sm">Applied {{ $selectedApplication->created_at->diffForHumans() }}</p>
                </div>
                <div class="flex items-center space-x-3">
                    @if($selectedApplication->isEditable())
                        <button wire:click="editApplication({{ $selectedApplication->id }})" class="bg-brand-red text-white px-6 py-2 rounded-xl font-semibold hover:bg-brand-dark-red transition-all duration-200 shadow-lg shadow-brand-red/25">
                            Edit Application
                        </button>
                    @endif
                    <button wire:click="backToList" class="text-gray-600 hover:text-gray-800 p-2 rounded-xl hover:bg-gray-100 transition-all duration-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Application Details Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column - Application Information -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Loan Details -->
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-blue-50 to-indigo-50">
                        <h3 class="text-xl font-bold text-gray-900">Loan Details</h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <h4 class="text-sm font-semibold text-gray-700 mb-2">Requested Amount</h4>
                                <p class="text-2xl font-bold text-brand-red">TSh {{ number_format($selectedApplication->requested_amount) }}</p>
                            </div>
                            <div>
                                <h4 class="text-sm font-semibold text-gray-700 mb-2">Loan Period</h4>
                                <p class="text-2xl font-bold text-gray-900">{{ $selectedApplication->requested_tenure_months }} months</p>
                            </div>
                            <div>
                                <h4 class="text-sm font-semibold text-gray-700 mb-2">Purpose</h4>
                                <p class="text-lg font-medium text-gray-600">{{ ucwords(str_replace('_', ' ', $selectedApplication->loan_purpose)) }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Personal Information -->
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-green-50 to-emerald-50">
                        <h3 class="text-xl font-bold text-gray-900">Personal Information</h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h4 class="text-sm font-semibold text-gray-700 mb-2">Full Name</h4>
                                <p class="text-gray-900">{{ $selectedApplication->full_name }}</p>
                            </div>
                            <div>
                                <h4 class="text-sm font-semibold text-gray-700 mb-2">Date of Birth</h4>
                                <p class="text-gray-900">{{ $selectedApplication->date_of_birth->format('M d, Y') }} ({{ $selectedApplication->age }} years)</p>
                            </div>
                            <div>
                                <h4 class="text-sm font-semibold text-gray-700 mb-2">Gender</h4>
                                <p class="text-gray-900">{{ ucfirst($selectedApplication->gender) }}</p>
                            </div>
                            <div>
                                <h4 class="text-sm font-semibold text-gray-700 mb-2">Marital Status</h4>
                                <p class="text-gray-900">{{ ucfirst($selectedApplication->marital_status) }}</p>
                            </div>
                            <div>
                                <h4 class="text-sm font-semibold text-gray-700 mb-2">National ID</h4>
                                <p class="text-gray-900">{{ $selectedApplication->national_id }}</p>
                            </div>
                            <div>
                                <h4 class="text-sm font-semibold text-gray-700 mb-2">Phone Number</h4>
                                <p class="text-gray-900">{{ $selectedApplication->phone_number }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Employment Information -->
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-purple-50 to-pink-50">
                        <h3 class="text-xl font-bold text-gray-900">Employment & Financial Information</h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h4 class="text-sm font-semibold text-gray-700 mb-2">Employment Status</h4>
                                <p class="text-gray-900">{{ ucwords(str_replace('_', ' ', $selectedApplication->employment_status)) }}</p>
                            </div>
                            @if($selectedApplication->employer_name)
                                <div>
                                    <h4 class="text-sm font-semibold text-gray-700 mb-2">Employer</h4>
                                    <p class="text-gray-900">{{ $selectedApplication->employer_name }}</p>
                                </div>
                            @endif
                            @if($selectedApplication->business_name)
                                <div>
                                    <h4 class="text-sm font-semibold text-gray-700 mb-2">Business Name</h4>
                                    <p class="text-gray-900">{{ $selectedApplication->business_name }}</p>
                                </div>
                            @endif
                            <div>
                                <h4 class="text-sm font-semibold text-gray-700 mb-2">Monthly Income</h4>
                                <p class="text-lg font-bold text-green-600">TSh {{ number_format($selectedApplication->total_monthly_income) }}</p>
                            </div>
                            <div>
                                <h4 class="text-sm font-semibold text-gray-700 mb-2">Monthly Expenses</h4>
                                <p class="text-lg font-bold text-red-600">TSh {{ number_format($selectedApplication->monthly_expenses) }}</p>
                            </div>
                            <div>
                                <h4 class="text-sm font-semibold text-gray-700 mb-2">Net Income</h4>
                                <p class="text-lg font-bold text-blue-600">TSh {{ number_format($selectedApplication->net_income) }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Documents -->
                @if($selectedApplication->documents->count() > 0)
                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-yellow-50 to-orange-50">
                            <h3 class="text-xl font-bold text-gray-900">Uploaded Documents</h3>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($selectedApplication->documents as $document)
                                    <div class="flex items-center justify-between bg-gray-50 rounded-xl p-4">
                                        <div class="flex items-center space-x-3">
                                            @if($document->is_image)
                                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                            @else
                                                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                </svg>
                                            @endif
                                            <div>
                                                <p class="font-medium text-gray-900">{{ $document->type_display_name }}</p>
                                                <p class="text-sm text-gray-500">{{ $document->file_size_human }}</p>
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-{{ $document->status_badge['color'] }}-100 text-{{ $document->status_badge['color'] }}-800">
                                                {{ $document->status_badge['text'] }}
                                            </span>
                                            <a href="{{ $document->file_url }}" target="_blank" class="text-blue-600 hover:text-blue-800">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Right Column - Status & Actions -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Status Card -->
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden sticky top-6">
                    <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-brand-red to-brand-dark-red text-white">
                        <h3 class="text-xl font-bold">Application Status</h3>
                    </div>
                    <div class="p-6">
                        <div class="text-center mb-6">
                            <div class="w-16 h-16 bg-{{ $selectedApplication->status_badge['color'] }}-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-{{ $selectedApplication->status_badge['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    @switch($selectedApplication->status)
                                        @case('draft')
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            @break
                                        @case('submitted')
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                            @break
                                        @case('under_review')
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            @break
                                        @case('approved')
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            @break
                                        @case('rejected')
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            @break
                                        @case('disbursed')
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                            @break
                                    @endswitch
                                </svg>
                            </div>
                            <p class="text-xl font-bold text-gray-900">{{ $selectedApplication->status_badge['text'] }}</p>
                        </div>

                        <!-- Timeline -->
                        <div class="space-y-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                                <div>
                                    <p class="text-sm font-medium text-gray-700">Application Created</p>
                                    <p class="text-xs text-gray-500">{{ $selectedApplication->created_at->format('M d, Y H:i') }}</p>
                                </div>
                            </div>
                            
                            @if($selectedApplication->submitted_at)
                                <div class="flex items-center space-x-3">
                                    <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-700">Application Submitted</p>
                                        <p class="text-xs text-gray-500">{{ $selectedApplication->submitted_at->format('M d, Y H:i') }}</p>
                                    </div>
                                </div>
                            @endif
                            
                            @if($selectedApplication->reviewed_at)
                                <div class="flex items-center space-x-3">
                                    <div class="w-3 h-3 bg-yellow-500 rounded-full"></div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-700">Under Review</p>
                                        <p class="text-xs text-gray-500">{{ $selectedApplication->reviewed_at->format('M d, Y H:i') }}</p>
                                    </div>
                                </div>
                            @endif
                            
                            @if($selectedApplication->approved_at)
                                <div class="flex items-center space-x-3">
                                    <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-700">Approved</p>
                                        <p class="text-xs text-gray-500">{{ $selectedApplication->approved_at->format('M d, Y H:i') }}</p>
                                    </div>
                                </div>
                            @endif
                            
                            @if($selectedApplication->disbursed_at)
                                <div class="flex items-center space-x-3">
                                    <div class="w-3 h-3 bg-purple-500 rounded-full"></div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-700">Loan Disbursed</p>
                                        <p class="text-xs text-gray-500">{{ $selectedApplication->disbursed_at->format('M d, Y H:i') }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Lender Information -->
                        @if($selectedApplication->lender)
                            <div class="mt-6 pt-6 border-t border-gray-200">
                                <h4 class="text-sm font-semibold text-gray-700 mb-2">Lender</h4>
                                <p class="text-gray-900 font-medium">{{ $selectedApplication->lender->name }}</p>
                                @if($selectedApplication->loanProduct)
                                    <p class="text-sm text-blue-600">{{ $selectedApplication->loanProduct->name }}</p>
                                @endif
                            </div>
                        @endif

                        <!-- Actions -->
                        <div class="mt-6 space-y-3">
                            @if($selectedApplication->status === 'submitted' && $selectedApplication->matching_products)
                                <button wire:click="viewMatchingProducts({{ $selectedApplication->id }})" class="w-full bg-green-100 text-green-700 py-3 px-4 rounded-xl font-semibold hover:bg-green-200 transition-colors">
                                    View Matching Products
                                </button>
                            @endif
                            
                            @if($selectedApplication->isEditable())
                                <button wire:click="editApplication({{ $selectedApplication->id }})" class="w-full bg-blue-100 text-blue-700 py-3 px-4 rounded-xl font-semibold hover:bg-blue-200 transition-colors">
                                    Edit Application
                                </button>
                            @endif
                            
                            @if(in_array($selectedApplication->status, ['draft', 'submitted']))
                                <button wire:click="cancelApplication({{ $selectedApplication->id }})" 
                                        wire:confirm="Are you sure you want to cancel this application?"
                                        class="w-full bg-red-100 text-red-700 py-3 px-4 rounded-xl font-semibold hover:bg-red-200 transition-colors">
                                    Cancel Application
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

    {{-- MATCHING PRODUCTS VIEW --}}
    @elseif($currentStep === 'products' && $selectedApplication)
        <!-- Products Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-4xl font-bold text-gray-900 mb-2">Matching Loan Products</h1>
                    <p class="text-gray-600 text-lg">Choose the best loan product for your application</p>
                    <p class="text-sm text-gray-500">Application: {{ $selectedApplication->application_number }}</p>
                </div>
                <div class="flex items-center space-x-3">
                    <button wire:click="viewApplication({{ $selectedApplication->id }})" class="text-gray-600 hover:text-gray-800 p-2 rounded-xl hover:bg-gray-100 transition-all duration-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Matching Products Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            @forelse($matchingProducts as $index => $product)
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg transition-all duration-300 {{ $index === 0 ? 'ring-2 ring-brand-red' : '' }}">
                    <!-- Product Header -->
                    <div class="p-6 {{ $index === 0 ? 'bg-gradient-to-r from-brand-red to-brand-dark-red text-white' : 'bg-gradient-to-r from-blue-50 to-indigo-50' }}">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="text-xl font-bold">{{ $product['product_name'] }}</h3>
                            @if($index === 0)
                                <span class="bg-white text-brand-red px-3 py-1 rounded-full text-sm font-bold">BEST MATCH</span>
                            @endif
                        </div>
                        <p class="{{ $index === 0 ? 'text-red-100' : 'text-gray-600' }}">{{ $product['lender_name'] }}</p>
                        <div class="mt-4">
                            <div class="flex items-center space-x-4">
                                <div>
                                    <p class="text-sm {{ $index === 0 ? 'text-red-200' : 'text-gray-500' }}">Eligibility Score</p>
                                    <p class="text-2xl font-bold">{{ $product['eligibility_score'] }}%</p>
                                </div>
                                <div>
                                    <p class="text-sm {{ $index === 0 ? 'text-red-200' : 'text-gray-500' }}">Interest Rate</p>
                                    <p class="text-xl font-bold">{{ $product['interest_rate'] }}%</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Product Details -->
                    <div class="p-6">
                        <div class="space-y-4">
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-gray-500">Recommended Amount:</span>
                                <span class="text-lg font-bold text-gray-900">TSh {{ number_format($product['recommended_amount']) }}</span>
                            </div>

                            <!-- Matched Criteria -->
                            @if(count($product['matched_criteria']) > 0)
                                <div>
                                    <h4 class="text-sm font-semibold text-green-700 mb-2">✓ You qualify for:</h4>
                                    <ul class="space-y-1">
                                        @foreach($product['matched_criteria'] as $criteria)
                                            <li class="text-sm text-green-600 flex items-center">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                </svg>
                                                {{ $criteria }}
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <!-- Unmatched Criteria -->
                            @if(count($product['unmatched_criteria']) > 0)
                                <div>
                                    <h4 class="text-sm font-semibold text-red-700 mb-2">⚠ Note:</h4>
                                    <ul class="space-y-1">
                                        @foreach($product['unmatched_criteria'] as $criteria)
                                            <li class="text-sm text-red-600 flex items-center">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                                </svg>
                                                {{ $criteria }}
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>

                        <!-- Apply Button -->
                        <div class="mt-6">
                            <button wire:click="applyToProduct({{ $product['product_id'] }})" 
                                    class="w-full {{ $index === 0 ? 'bg-gradient-to-r from-brand-red to-brand-dark-red' : 'bg-blue-600' }} text-white py-3 px-4 rounded-xl font-bold hover:opacity-90 transition-all duration-200 shadow-lg">
                                Apply to {{ $product['lender_name'] }}
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full bg-white rounded-3xl shadow-sm p-12 text-center border border-gray-100">
                    <div class="w-20 h-20 bg-gradient-to-br from-gray-400 to-gray-600 rounded-3xl flex items-center justify-center mx-auto mb-6 shadow-lg">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">No Matching Products Found</h3>
                    <p class="text-gray-500 mb-6">Unfortunately, no loan products match your current criteria. You may need to adjust your application details.</p>
                   

                    <button wire:click="backToList" class="bg-gray-100 text-gray-700 px-8 py-3 rounded-xl font-semibold hover:bg-gray-200 transition-all duration-200 shadow-lg">
                        Back to Applications
                    </button>
                    </div>
                </div>

            @endforelse
        </div>
        
        @endif
    </div>
</div>
