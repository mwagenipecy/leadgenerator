<div>
<div class="min-h-screen bg-gray-50">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- Header -->
        <div class="mb-8">
            <div class="text-center mb-6">
                <h1 class="text-4xl font-bold text-gray-900 mb-2">Complete Your Loan Application</h1>
                <p class="text-xl text-gray-600">Final steps to submit your application to selected lenders</p>
            </div>

            <!-- Progress Steps -->
            <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 mb-6">
                <div class="flex items-center justify-between">
                    @for($i = 1; $i <= 3; $i++)
                        <div class="flex items-center {{ $i < 3 ? 'flex-1' : '' }}">
                            <div class="flex items-center space-x-3">
                                <div class="w-12 h-12 rounded-full flex items-center justify-center font-bold text-sm transition-all duration-200 relative
                                    {{ $currentStep >= $i ? 'bg-sidebar-green text-white shadow-lg shadow-sidebar-green/25' : 'bg-gray-200 text-gray-600' }}">
                                    @if($currentStep > $i)
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    @else
                                        {{ $i }}
                                    @endif
                                </div>
                                <div class="hidden sm:block">
                                    <p class="text-sm font-bold {{ $currentStep >= $i ? 'text-sidebar-green' : 'text-gray-500' }}">
                                        @switch($i)
                                            @case(1) Upload Documents @break
                                            @case(2) Review Application @break
                                            @case(3) Submit & Confirm @break
                                        @endswitch
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        @switch($i)
                                            @case(1) Required documents @break
                                            @case(2) Verify details @break
                                            @case(3) Final submission @break
                                        @endswitch
                                    </p>
                                </div>
                            </div>
                            @if($i < 3)
                                <div class="flex-1 h-1 mx-4 rounded-full {{ $currentStep > $i ? 'bg-sidebar-green' : 'bg-gray-200' }}"></div>
                            @endif
                        </div>
                    @endfor
                </div>
            </div>

            <!-- Flash Messages -->
            @if (session()->has('message'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg" role="alert">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        {{ session('message') }}
                    </div>
                </div>
            @endif

            @if (session()->has('error'))
                <div class="mb-6 bg-sidebar-green-50 border border-sidebar-green-200 text-sidebar-green-light px-4 py-3 rounded-lg" role="alert">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        {{ session('error') }}
                    </div>
                </div>
            @endif

            @if (session()->has('success'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg" role="alert">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        {{ session('success') }}
                    </div>
                </div>
            @endif
        </div>

        {{-- STEP 1: DOCUMENT UPLOAD --}}
        @if($currentStep === 1)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-blue-50 to-indigo-50">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900 mb-2">Upload Required Documents</h2>
                            <p class="text-gray-600">Please upload clear, readable copies of the required documents for all selected lenders</p>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-blue-600">{{ number_format($this->calculateRequiredDocumentsProgress()) }}%</div>
                            <div class="text-sm text-gray-600">Complete</div>
                        </div>
                    </div>
                </div>
                
                <div class="p-6">
                    <!-- Application Summary -->
                    <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-2xl p-6 border border-purple-100 mb-8">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Application Summary</h3>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div class="bg-white rounded-lg p-3">
                                <p class="text-xs text-gray-600">Loan Amount</p>
                                <p class="text-sm font-bold text-gray-900">TSh {{ number_format($prequalificationData['requested_amount'] ?? 0) }}</p>
                            </div>
                            <div class="bg-white rounded-lg p-3">
                                <p class="text-xs text-gray-600">Loan Type</p>
                                <p class="text-sm font-bold text-gray-900">{{ ucwords(str_replace('_', ' ', $prequalificationData['loan_category'] ?? '')) }}</p>
                            </div>
                            <div class="bg-white rounded-lg p-3">
                                <p class="text-xs text-gray-600">Selected Lenders</p>
                                <p class="text-sm font-bold text-gray-900">{{ count($selectedLenders) }} lenders</p>
                            </div>
                            <div class="bg-white rounded-lg p-3">
                                <p class="text-xs text-gray-600">Processing Time</p>
                                <p class="text-sm font-bold text-gray-900">{{ $estimatedProcessingTime }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Selected Lenders Info -->
                    <div class="bg-gradient-to-r from-indigo-50 to-blue-50 rounded-2xl p-6 border border-indigo-100 mb-8">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Selected Lenders & Products</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($selectedLenders as $lender)
                                <div class="bg-white rounded-lg p-4 border border-indigo-200">
                                    <div class="flex items-center space-x-3 mb-2">
                                        <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center">
                                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-2m-2 0H7m5 0v-9a2 2 0 00-2-2v0a2 2 0 00-2 2v9m4 0V9a2 2 0 012-2v0a2 2 0 012 2v16"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-900">{{ $lender['lender_name'] ?? 'Unknown Lender' }}</p>
                                            <p class="text-sm text-gray-600">{{ $lender['product_name'] ?? 'Product' }}</p>
                                        </div>
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        Interest: {{ $lender['interest_rate_min'] ?? 'N/A' }}% - {{ $lender['interest_rate_max'] ?? 'N/A' }}% | 
                                        Monthly: TSh {{ number_format($lender['monthly_payment'] ?? 0) }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Document Upload Grid -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        @foreach($requiredDocuments as $type => $document)
                            <div class="border border-gray-200 rounded-xl p-6 {{ $document['uploaded'] ? 'bg-green-50 border-green-200' : ($document['required'] ? 'bg-sidebar-green-50 border-sidebar-green-200' : 'bg-gray-50') }}">
                                <div class="flex items-start space-x-4">
                                    <div class="flex-shrink-0">
                                        @if($document['uploaded'])
                                            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                            </div>
                                        @else
                                            <div class="w-12 h-12 {{ $document['required'] ? 'bg-sidebar-green-100' : 'bg-gray-100' }} rounded-full flex items-center justify-center">
                                                <svg class="w-6 h-6 {{ $document['required'] ? 'text-sidebar-green' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center space-x-2 mb-2">
                                            <h4 class="text-lg font-semibold text-gray-900">{{ $document['name'] }}</h4>
                                            @if($document['required'])
                                                <span class="bg-sidebar-green-100 text-sidebar-green-800 text-xs font-medium px-2 py-1 rounded-full">Required</span>
                                            @else
                                                <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2 py-1 rounded-full">Optional</span>
                                            @endif
                                        </div>
                                        
                                        <p class="text-sm text-gray-600 mb-2">{{ $document['description'] }}</p>
                                        
                                        <!-- Show which lenders require this document -->
                                        <div class="text-xs text-blue-600 mb-3">
                                            <span class="font-medium">Requested by:</span> {{ $this->getDocumentRequestedBy($type) }}
                                        </div>
                                        
                                        @if($document['uploaded'])
                                            <div class="bg-white rounded-lg p-3 border border-green-200 mb-3">
                                                <div class="flex items-center justify-between">
                                                    <div class="flex items-center space-x-2">
                                                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                        </svg>
                                                        <span class="text-sm font-medium text-gray-900">{{ $uploadedDocuments[$type]['name'] ?? 'Uploaded' }}</span>
                                                    </div>
                                                    <button wire:click="removeDocument('{{ $type }}')" class="text-sidebar-green hover:text-sidebar-green-800 text-sm font-medium">
                                                        Remove
                                                    </button>
                                                </div>
                                                <p class="text-xs text-gray-500 mt-1">
                                                    Size: {{ number_format(($uploadedDocuments[$type]['size'] ?? 0) / 1024, 1) }} KB | 
                                                    Uploaded: {{ isset($uploadedDocuments[$type]['uploaded_at']) ? $uploadedDocuments[$type]['uploaded_at']->format('M j, Y H:i') : 'Recently' }}
                                                </p>
                                            </div>
                                        @else
                                            <div class="space-y-3">
                                                <input type="file" 
                                                       wire:model="documents.{{ $type }}" 
                                                       accept=".pdf,.jpg,.jpeg,.png" 
                                                       class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-sidebar-green file:text-white hover:file:bg-sidebar-green-light">
                                                
                                                @if(isset($documents[$type]))
                                                    <button wire:click="uploadDocument('{{ $type }}')" 
                                                            wire:loading.attr="disabled"
                                                            wire:target="uploadDocument('{{ $type }}')"
                                                            class="w-full bg-sidebar-green text-white py-2 px-4 rounded-lg font-medium hover:bg-sidebar-green-light disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                                                        <span wire:loading.remove wire:target="uploadDocument('{{ $type }}')">Upload Document</span>
                                                        <span wire:loading wire:target="uploadDocument('{{ $type }}')">Uploading...</span>
                                                    </button>
                                                @endif
                                            </div>
                                        @endif
                                        
                                        @error('documents.' . $type)
                                            <p class="text-sidebar-green text-sm mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Document Upload Summary -->
                    <div class="mt-8 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <div class="flex items-center space-x-3">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div>
                                <p class="text-sm font-medium text-blue-900">Document Requirements Aggregated</p>
                                <p class="text-xs text-blue-700">We've combined all document requirements from your selected lenders to ensure you only upload each document once.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Next Step Button -->
                    <div class="flex justify-between items-center mt-8 pt-6 border-t border-gray-200">
                        <button wire:click="backToPrequalification" class="bg-gray-100 text-gray-700 px-6 py-3 rounded-lg font-medium hover:bg-gray-200 transition-colors">
                            ← Back to Pre-qualification
                        </button>
                        
                        <button wire:click="nextStep" class="bg-sidebar-green text-white px-8 py-3 rounded-lg font-medium hover:bg-sidebar-green-light transition-colors">
                            Continue to Review →
                        </button>
                    </div>
                </div>
            </div>
        @endif

        {{-- STEP 2: REVIEW APPLICATION --}}
        @if($currentStep === 2)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-green-50 to-emerald-50">
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Review Your Application</h2>
                    <p class="text-gray-600">Please review all information before submitting to lenders</p>
                </div>
                
                <div class="p-6 space-y-8">
                    <!-- Loan Details -->
                    <div class="bg-gradient-to-r from-blue-50 to-cyan-50 rounded-xl p-6 border border-blue-100">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Loan Details</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="bg-white rounded-lg p-4">
                                <p class="text-sm text-gray-600">Loan Category</p>
                                <p class="font-semibold text-gray-900">{{ ucwords(str_replace('_', ' ', $applicationData['loan_category'])) }}</p>
                            </div>
                            <div class="bg-white rounded-lg p-4">
                                <p class="text-sm text-gray-600">Loan Type</p>
                                <p class="font-semibold text-gray-900">{{ ucwords($applicationData['loan_type']) }}</p>
                            </div>
                            <div class="bg-white rounded-lg p-4">
                                <p class="text-sm text-gray-600">Requested Amount</p>
                                <p class="font-semibold text-gray-900">TSh {{ number_format($applicationData['requested_amount']) }}</p>
                            </div>
                            <div class="bg-white rounded-lg p-4">
                                <p class="text-sm text-gray-600">Loan Tenure</p>
                                <p class="font-semibold text-gray-900">{{ $applicationData['requested_tenure_months'] }} months</p>
                            </div>
                            <div class="bg-white rounded-lg p-4">
                                <p class="text-sm text-gray-600">Debt-to-Income Ratio</p>
                                <p class="font-semibold text-gray-900">{{ number_format($applicationData['debt_to_income_ratio'], 1) }}%</p>
                            </div>
                            <div class="bg-white rounded-lg p-4">
                                <p class="text-sm text-gray-600">Selected Lenders</p>
                                <p class="font-semibold text-gray-900">{{ count($selectedLenders) }} lenders</p>
                            </div>
                        </div>
                    </div>

                    <!-- Personal Information -->
                    <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl p-6 border border-purple-100">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Personal Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="bg-white rounded-lg p-4">
                                <p class="text-sm text-gray-600">Full Name</p>
                                <p class="font-semibold text-gray-900">{{ $applicationData['first_name'] }} {{ $applicationData['middle_name'] }} {{ $applicationData['last_name'] }}</p>
                            </div>
                            <div class="bg-white rounded-lg p-4">
                                <p class="text-sm text-gray-600">Date of Birth</p>
                                <p class="font-semibold text-gray-900">{{ \Carbon\Carbon::parse($applicationData['date_of_birth'])->format('M d, Y') }}</p>
                            </div>
                            <div class="bg-white rounded-lg p-4">
                                <p class="text-sm text-gray-600">National ID</p>
                                <p class="font-semibold text-gray-900">{{ $applicationData['national_id'] }}</p>
                            </div>
                            <div class="bg-white rounded-lg p-4">
                                <p class="text-sm text-gray-600">Phone Number</p>
                                <p class="font-semibold text-gray-900">{{ $applicationData['phone_number'] }}</p>
                            </div>
                            <div class="bg-white rounded-lg p-4">
                                <p class="text-sm text-gray-600">Email</p>
                                <p class="font-semibold text-gray-900">{{ $applicationData['email'] }}</p>
                            </div>
                            <div class="bg-white rounded-lg p-4">
                                <p class="text-sm text-gray-600">Marital Status</p>
                                <p class="font-semibold text-gray-900">{{ ucwords(str_replace('_', ' ', $applicationData['marital_status'])) }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Employment Information -->
                    <div class="bg-gradient-to-r from-orange-50 to-yellow-50 rounded-xl p-6 border border-orange-100">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Employment Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="bg-white rounded-lg p-4">
                                <p class="text-sm text-gray-600">Employment Status</p>
                                <p class="font-semibold text-gray-900">{{ ucwords(str_replace('_', ' ', $applicationData['employment_status'])) }}</p>
                            </div>
                            @if($applicationData['employment_status'] === 'employed')
                                <div class="bg-white rounded-lg p-4">
                                    <p class="text-sm text-gray-600">Employer Name</p>
                                    <p class="font-semibold text-gray-900">{{ $applicationData['employer_name'] ?? 'N/A' }}</p>
                                </div>
                                <div class="bg-white rounded-lg p-4">
                                    <p class="text-sm text-gray-600">Job Title</p>
                                    <p class="font-semibold text-gray-900">{{ $applicationData['job_title'] ?? 'N/A' }}</p>
                                </div>
                                <div class="bg-white rounded-lg p-4">
                                    <p class="text-sm text-gray-600">Monthly Salary</p>
                                    <p class="font-semibold text-gray-900">TSh {{ number_format($applicationData['monthly_salary'] ?? 0) }}</p>
                                </div>
                            @endif
                            @if($applicationData['employment_status'] === 'self_employed')
                                <div class="bg-white rounded-lg p-4">
                                    <p class="text-sm text-gray-600">Business Name</p>
                                    <p class="font-semibold text-gray-900">{{ $applicationData['business_name'] ?? 'N/A' }}</p>
                                </div>
                                <div class="bg-white rounded-lg p-4">
                                    <p class="text-sm text-gray-600">Business Type</p>
                                    <p class="font-semibold text-gray-900">{{ $applicationData['business_type'] ?? 'N/A' }}</p>
                                </div>
                                <div class="bg-white rounded-lg p-4">
                                    <p class="text-sm text-gray-600">Monthly Business Income</p>
                                    <p class="font-semibold text-gray-900">TSh {{ number_format($applicationData['monthly_business_income'] ?? 0) }}</p>
                                </div>
                            @endif
                            <div class="bg-white rounded-lg p-4">
                                <p class="text-sm text-gray-600">Total Monthly Income</p>
                                <p class="font-semibold text-gray-900">TSh {{ number_format($applicationData['total_monthly_income'] ?? 0) }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Selected Lenders -->
                    <div class="bg-gradient-to-r from-indigo-50 to-blue-50 rounded-xl p-6 border border-indigo-100">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Selected Lenders ({{ count($selectedLenders) }})</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($selectedLenders as $lender)
                                <div class="bg-white rounded-lg p-4 border border-indigo-200">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center">
                                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-2m-2 0H7m5 0v-9a2 2 0 00-2-2v0a2 2 0 00-2 2v9m4 0V9a2 2 0 012-2v0a2 2 0 012 2v16"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-900">{{ $lender['lender_name'] ?? 'Unknown Lender' }}</p>
                                            <p class="text-sm text-gray-600">{{ $lender['product_name'] ?? 'Product' }}</p>
                                        </div>
                                    </div>
                                    <div class="mt-3 grid grid-cols-2 gap-2 text-sm">
                                        <div>
                                            <p class="text-gray-600">Interest Rate</p>
                                            <p class="font-medium">{{ $lender['interest_rate_min'] ?? 'N/A' }}% - {{ $lender['interest_rate_max'] ?? 'N/A' }}%</p>
                                        </div>
                                        <div>
                                            <p class="text-gray-600">Monthly Payment</p>
                                            <p class="font-medium">TSh {{ number_format($lender['monthly_payment'] ?? 0) }}</p>
                                        </div>
                                        <div>
                                            <p class="text-gray-600">Processing Time</p>
                                            <p class="font-medium">{{ $lender['approval_time_days'] ?? 'N/A' }} days</p>
                                        </div>
                                        <div>
                                            <p class="text-gray-600">Eligibility Score</p>
                                            <p class="font-medium">{{ number_format($lender['eligibility_score'] ?? 0, 1) }}%</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Uploaded Documents -->
                    <div class="bg-gradient-to-r from-green-50 to-teal-50 rounded-xl p-6 border border-green-100">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Uploaded Documents</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($uploadedDocuments as $type => $document)
                                <div class="bg-white rounded-lg p-4 border border-green-200">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-900">{{ $requiredDocuments[$type]['name'] }}</p>
                                            <p class="text-sm text-gray-600">{{ $document['name'] }}</p>
                                            <p class="text-xs text-gray-500">{{ $this->getDocumentRequestedBy($type) }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Navigation Buttons -->
                    <div class="flex justify-between items-center pt-6 border-t border-gray-200">
                        <button wire:click="previousStep" class="bg-gray-100 text-gray-700 px-6 py-3 rounded-lg font-medium hover:bg-gray-200 transition-colors">
                            ← Back to Documents
                        </button>
                        
                        <button wire:click="nextStep" 
                                wire:loading.attr="disabled"
                                wire:target="nextStep"
                                class="bg-sidebar-green text-white px-8 py-3 rounded-lg font-medium hover:bg-sidebar-green-light transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                            <span wire:loading.remove wire:target="nextStep">Submit Application →</span>
                            <span wire:loading wire:target="nextStep">Submitting...</span>
                        </button>
                    </div>
                </div>
            </div>
        @endif

        {{-- STEP 3: SUBMISSION CONFIRMATION --}}
        @if($currentStep === 3)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-green-50 to-emerald-50">
                    <div class="text-center">
                        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">Application Submitted Successfully!</h2>
                        <p class="text-gray-600">Your loan application has been submitted to selected lenders</p>
                    </div>
                </div>
                
                <div class="p-6">
                    @if($finalApplication)
                        <!-- Application Details -->
                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-6 border border-blue-100 mb-8">
                            <h3 class="text-lg font-bold text-gray-900 mb-4">Application Details</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="bg-white rounded-lg p-4">
                                    <p class="text-sm text-gray-600">Application Number</p>
                                    <p class="font-semibold text-gray-900">{{ $finalApplication->application_number ?? 'Generated' }}</p>
                                </div>
                                <div class="bg-white rounded-lg p-4">
                                    <p class="text-sm text-gray-600">Loan Amount</p>
                                    <p class="font-semibold text-gray-900">TSh {{ number_format($finalApplication->requested_amount) }}</p>
                                </div>
                                <div class="bg-white rounded-lg p-4">
                                    <p class="text-sm text-gray-600">Loan Type</p>
                                    <p class="font-semibold text-gray-900">{{ ucwords($finalApplication->loan_type) }}</p>
                                </div>
                                <div class="bg-white rounded-lg p-4">
                                    <p class="text-sm text-gray-600">Submitted At</p>
                                    <p class="font-semibold text-gray-900">{{ $finalApplication->submitted_at->format('M d, Y H:i') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Submission Results -->
                    @if(!empty($submissionResults))
                        <div class="bg-gradient-to-r from-purple-50 to-indigo-50 rounded-xl p-6 border border-purple-100 mb-6">
                            <h3 class="text-lg font-bold text-gray-900 mb-4">Submission Status</h3>
                            <div class="space-y-4">
                                @foreach($submissionResults as $result)
                                    <div class="bg-white rounded-lg p-4 border border-indigo-200 flex justify-between items-center">
                                        <div>
                                            <p class="font-semibold text-gray-900">{{ $result['lender_name'] }}</p>
                                            <p class="text-sm text-gray-600">{{ $result['product_name'] }}</p>
                                            <p class="text-xs text-gray-500 mt-1">Ref: {{ $result['reference'] }}</p>
                                        </div>
                                        <div class="text-sm font-medium text-green-600 flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            {{ ucfirst($result['status']) }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Next Steps Information -->
                    <div class="bg-gradient-to-r from-yellow-50 to-orange-50 rounded-xl p-6 border border-yellow-100 mb-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">What Happens Next?</h3>
                        <div class="space-y-3">
                            <div class="flex items-start space-x-3">
                                <div class="w-6 h-6 bg-yellow-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <span class="text-xs font-bold text-yellow-800">1</span>
                                </div>
                                <p class="text-sm text-gray-700">Lenders will review your application and documents within their processing timeframes.</p>
                            </div>
                            <div class="flex items-start space-x-3">
                                <div class="w-6 h-6 bg-yellow-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <span class="text-xs font-bold text-yellow-800">2</span>
                                </div>
                                <p class="text-sm text-gray-700">You'll receive notifications about application status updates via SMS and email.</p>
                            </div>
                            <div class="flex items-start space-x-3">
                                <div class="w-6 h-6 bg-yellow-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <span class="text-xs font-bold text-yellow-800">3</span>
                                </div>
                                <p class="text-sm text-gray-700">Approved offers will be available in your dashboard for comparison and acceptance.</p>
                            </div>
                            <div class="flex items-start space-x-3">
                                <div class="w-6 h-6 bg-yellow-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <span class="text-xs font-bold text-yellow-800">4</span>
                                </div>
                                <p class="text-sm text-gray-700">Choose the best offer and complete the final steps for loan disbursement.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-center space-x-4 mt-8">
                        <button wire:click="viewApplications"
                                class="bg-sidebar-green text-white px-6 py-3 rounded-lg font-medium hover:bg-sidebar-green-light transition-colors">
                            View My Applications
                        </button>
                        <a href="{{ route('dashboard') }}" 
                           class="bg-gray-100 text-gray-700 px-6 py-3 rounded-lg font-medium hover:bg-gray-200 transition-colors">
                            Go to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>