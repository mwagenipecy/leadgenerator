<div>

<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($currentStep === 'list')
       

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
                            <input wire:model.live="search" type="text" class="block w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-red-600 focus:border-red-600 placeholder-gray-500 text-gray-900 text-sm transition-all duration-200" placeholder="Search applications...">
                        </div>
                    </div>

                    <!-- Filters -->
                    <div class="flex items-center space-x-4">
                        <select wire:model.live="statusFilter" class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-red-600 focus:border-red-600">
                            <option value="all">All Status</option>
                            <option value="draft">Draft</option>
                            <option value="submitted">Submitted</option>
                            <option value="under_review">Under Review</option>
                            <option value="approved">Approved</option>
                            <option value="rejected">Rejected</option>
                            <option value="disbursed">Disbursed</option>
                            <option value="cancelled">Cancelled</option>
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
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold 
                                            @if($application->status === 'draft') bg-gray-100 text-gray-800
                                            @elseif($application->status === 'submitted') bg-blue-100 text-blue-800
                                            @elseif($application->status === 'under_review') bg-yellow-100 text-yellow-800
                                            @elseif($application->status === 'approved') bg-green-100 text-green-800
                                            @elseif($application->status === 'rejected') bg-red-100 text-red-800
                                            @elseif($application->status === 'disbursed') bg-purple-100 text-purple-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            {{ ucwords(str_replace('_', ' ', $application->status)) }}
                                        </span>
                                    </div>
                                    <p class="text-gray-600">{{ $application->first_name }} {{ $application->last_name }}</p>
                                    <p class="text-sm text-gray-500">Applied {{ $application->created_at->diffForHumans() }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-2xl font-bold text-gray-900">TSh {{ number_format($application->requested_amount, 0) }}</p>
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
                                    <p class="text-sm text-gray-600">{{ ucwords(str_replace('_', ' ', $application->employment_status)) }}</p>
                                    <p class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($application->date_of_birth)->age }} years old</p>
                                    <p class="text-sm text-gray-600">{{ $application->current_city }}, {{ $application->current_region }}</p>
                                </div>
                                <div>
                                    <h4 class="text-sm font-semibold text-gray-700 mb-2">Financial Information</h4>
                                    <p class="text-sm text-gray-600">Monthly Income: TSh {{ number_format($application->total_monthly_income, 0) }}</p>
                                    <p class="text-sm text-gray-600">Monthly Expenses: TSh {{ number_format($application->monthly_expenses, 0) }}</p>
                                    @if($application->debt_to_income_ratio)
                                        <p class="text-sm text-gray-600">Debt Ratio: {{ $application->debt_to_income_ratio }}%</p>
                                    @endif
                                </div>
                                <div>
                                    <h4 class="text-sm font-semibold text-gray-700 mb-2">Application Progress</h4>
                                    @php
                                        $completionPercentage = 0;
                                        if ($application->status === 'draft') $completionPercentage = 25;
                                        elseif ($application->status === 'submitted') $completionPercentage = 50;
                                        elseif ($application->status === 'under_review') $completionPercentage = 75;
                                        elseif (in_array($application->status, ['approved', 'disbursed'])) $completionPercentage = 100;
                                    @endphp
                                    <div class="flex items-center space-x-2 mb-2">
                                        <div class="flex-1 bg-gray-200 rounded-full h-2">
                                            <div class="bg-red-600 h-2 rounded-full" style="width: {{ $completionPercentage }}%"></div>
                                        </div>
                                        <span class="text-sm text-gray-600">{{ $completionPercentage }}%</span>
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

                                    @if(in_array($application->status, ['draft']))
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

                                @if($application->matching_products && is_array(json_decode($application->matching_products, true)) && count(json_decode($application->matching_products, true)) > 0)
                                    <div class="flex items-center space-x-2">
                                        <span class="text-sm text-green-600 font-medium">{{ count(json_decode($application->matching_products, true)) }} matching products</span>
                                        <button wire:click="viewMatchingProducts({{ $application->id }})" class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm font-medium hover:bg-green-200 transition-colors">
                                            View Options
                                        </button>
                                    </div>
                                @elseif($application->status === 'submitted')
                                    <div class="flex items-center space-x-2">
                                        <span class="text-sm text-yellow-600 font-medium">Matching in progress...</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No applications found</h3>
                        <p class="text-gray-600 mb-6">You haven't submitted any loan applications yet.</p>
                        <button wire:click="startNewApplication" class="bg-red-600 text-white px-6 py-2 rounded-xl font-semibold hover:bg-red-700 transition-all duration-200">
                            Create Your First Application
                        </button>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($applications->hasPages())
                <div class="mt-8">
                    {{ $applications->links() }}
                </div>
            @endif

        @elseif($currentStep === 'view' && $selectedApplication)
            <!-- Application Details View -->
            <div class="mb-6">
                <button wire:click="backToList" class="flex items-center text-gray-600 hover:text-gray-800 mb-4">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Back to Applications
                </button>
                
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
                    <div class="mb-6">
                        <div class="flex items-center justify-between mb-4">
                            <h1 class="text-3xl font-bold text-gray-900">{{ $selectedApplication->application_number }}</h1>
                            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold 
                                @if($selectedApplication->status === 'draft') bg-gray-100 text-gray-800
                                @elseif($selectedApplication->status === 'submitted') bg-blue-100 text-blue-800
                                @elseif($selectedApplication->status === 'under_review') bg-yellow-100 text-yellow-800
                                @elseif($selectedApplication->status === 'approved') bg-green-100 text-green-800
                                @elseif($selectedApplication->status === 'rejected') bg-red-100 text-red-800
                                @elseif($selectedApplication->status === 'disbursed') bg-purple-100 text-purple-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ ucwords(str_replace('_', ' ', $selectedApplication->status)) }}
                            </span>
                        </div>
                        <p class="text-xl text-gray-600">TSh {{ number_format($selectedApplication->requested_amount, 0) }} for {{ $selectedApplication->requested_tenure_months }} months</p>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Personal Information -->
                        <div class="space-y-6">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Personal Information</h3>
                                <div class="space-y-3">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Full Name:</span>
                                        <span class="font-medium">{{ $selectedApplication->first_name }} {{ $selectedApplication->middle_name }} {{ $selectedApplication->last_name }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Email:</span>
                                        <span class="font-medium">{{ $selectedApplication->email }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Phone:</span>
                                        <span class="font-medium">{{ $selectedApplication->phone_number }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Date of Birth:</span>
                                        <span class="font-medium">{{ \Carbon\Carbon::parse($selectedApplication->date_of_birth)->format('M d, Y') }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Gender:</span>
                                        <span class="font-medium">{{ ucfirst($selectedApplication->gender) }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Marital Status:</span>
                                        <span class="font-medium">{{ ucfirst($selectedApplication->marital_status) }}</span>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Employment Information</h3>
                                <div class="space-y-3">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Employment Status:</span>
                                        <span class="font-medium">{{ ucwords(str_replace('_', ' ', $selectedApplication->employment_status)) }}</span>
                                    </div>
                                    @if($selectedApplication->employer_name)
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Employer:</span>
                                            <span class="font-medium">{{ $selectedApplication->employer_name }}</span>
                                        </div>
                                    @endif
                                    @if($selectedApplication->job_title)
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Job Title:</span>
                                            <span class="font-medium">{{ $selectedApplication->job_title }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Financial Information -->
                        <div class="space-y-6">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Financial Information</h3>
                                <div class="space-y-3">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Monthly Income:</span>
                                        <span class="font-medium">TSh {{ number_format($selectedApplication->total_monthly_income, 0) }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Monthly Expenses:</span>
                                        <span class="font-medium">TSh {{ number_format($selectedApplication->monthly_expenses, 0) }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Existing Loans:</span>
                                        <span class="font-medium">TSh {{ number_format($selectedApplication->existing_loan_payments, 0) }}</span>
                                    </div>
                                    @if($selectedApplication->debt_to_income_ratio)
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Debt to Income Ratio:</span>
                                            <span class="font-medium">{{ $selectedApplication->debt_to_income_ratio }}%</span>
                                        </div>
                                    @endif
                                    @if($selectedApplication->credit_score)
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Credit Score:</span>
                                            <span class="font-medium">{{ $selectedApplication->credit_score }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Application Timeline</h3>
                                <div class="space-y-3">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Created:</span>
                                        <span class="font-medium">{{ $selectedApplication->created_at->format('M d, Y H:i') }}</span>
                                    </div>
                                    @if($selectedApplication->submitted_at)
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Submitted:</span>
                                            <span class="font-medium">{{ $selectedApplication->submitted_at->format('M d, Y H:i') }}</span>
                                        </div>
                                    @endif
                                    @if($selectedApplication->reviewed_at)
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Reviewed:</span>
                                            <span class="font-medium">{{ $selectedApplication->reviewed_at->format('M d, Y H:i') }}</span>
                                        </div>
                                    @endif
                                    @if($selectedApplication->approved_at)
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Approved:</span>
                                            <span class="font-medium">{{ $selectedApplication->approved_at->format('M d, Y H:i') }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

</div>
