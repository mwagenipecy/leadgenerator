<div>
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Current Employment -->
    <div>
        <h3 class="text-lg font-semibold text-black mb-4">Current Employment</h3>
        <div class="bg-gray-50 rounded-lg p-6 space-y-4">
            <div>
                <label class="text-sm font-medium text-gray-600">Employment Status</label>
                <p class="text-sm font-bold text-black mt-1 {{ $isAvailable ? 'blur-sm' : '' }}">
                    @if($isAvailable)
                        {{ substr($application->employment_status, 0, 3) }}***
                    @else
                        {{ ucwords(str_replace('_', ' ', $application->employment_status ?? 'N/A')) }}
                    @endif
                </p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-600">Company Name</label>
                <p class="text-sm font-bold text-black mt-1 {{ $isAvailable ? 'blur-sm' : '' }}">
                    {{ $this->getBlurredValue($application->employer_name ?? 'N/A', 'company') }}
                </p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-600">Job Title</label>
                <p class="text-sm font-bold text-black mt-1 {{ $isAvailable ? 'blur-sm' : '' }}">
                    @if($isAvailable)
                        ***Position
                    @else
                        {{ $application->job_title ?? 'N/A' }}
                    @endif
                </p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-600">Industry/Sector</label>
                <p class="text-sm font-bold text-black mt-1 {{ $isAvailable ? 'blur-sm' : '' }}">
                    @if($isAvailable)
                        ***Sector
                    @else
                        {{ $application->employment_sector ?? 'N/A' }}
                    @endif
                </p>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-medium text-gray-600">Years with Employer</label>
                    <p class="text-sm font-bold text-black mt-1">
                        {{ $application->years_with_current_employer ?? 'N/A' }} years
                    </p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-600">Employment Type</label>
                    <p class="text-sm font-bold text-black mt-1">
                        {{ ucwords(str_replace('_', ' ', $application->employment_type ?? 'N/A')) }}
                    </p>
                </div>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-600">Employee ID</label>
                <p class="text-sm font-bold text-black mt-1 {{ $isAvailable ? 'blur-sm' : '' }}">
                    @if($isAvailable)
                        EMP****
                    @else
                        {{ $application->employee_id ?? 'N/A' }}
                    @endif
                </p>
            </div>
        </div>
    </div>

    <!-- Employer Contact Information -->
    <div>
        <h3 class="text-lg font-semibold text-black mb-4">Employer Information</h3>
        <div class="bg-gray-50 rounded-lg p-6 space-y-4">
            <div>
                <label class="text-sm font-medium text-gray-600">Company Address</label>
                <p class="text-sm font-bold text-black mt-1 {{ $isAvailable ? 'blur-sm' : '' }}">
                    @if($isAvailable)
                        ***Company Address
                    @else
                        {{ $application->employer_address ?? 'N/A' }}
                    @endif
                </p>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-medium text-gray-600">Company Phone</label>
                    <p class="text-sm font-bold text-black mt-1 {{ $isAvailable ? 'blur-sm' : '' }}">
                        {{ $this->getBlurredValue($application->employer_phone ?? 'N/A', 'phone') }}
                    </p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-600">Company Email</label>
                    <p class="text-sm font-bold text-black mt-1 {{ $isAvailable ? 'blur-sm' : '' }}">
                        {{ $this->getBlurredValue($application->employer_email ?? 'N/A', 'email') }}
                    </p>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-medium text-gray-600">HR Contact Name</label>
                    <p class="text-sm font-bold text-black mt-1 {{ $isAvailable ? 'blur-sm' : '' }}">
                        {{ $this->getBlurredValue($application->hr_contact_name ?? 'N/A', 'name') }}
                    </p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-600">HR Contact Phone</label>
                    <p class="text-sm font-bold text-black mt-1 {{ $isAvailable ? 'blur-sm' : '' }}">
                        {{ $this->getBlurredValue($application->hr_contact_phone ?? 'N/A', 'phone') }}
                    </p>
                </div>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-600">HR Email</label>
                <p class="text-sm font-bold text-black mt-1 {{ $isAvailable ? 'blur-sm' : '' }}">
                    {{ $this->getBlurredValue($application->hr_contact_email ?? 'N/A', 'email') }}
                </p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-600">Supervisor Name</label>
                <p class="text-sm font-bold text-black mt-1 {{ $isAvailable ? 'blur-sm' : '' }}">
                    {{ $this->getBlurredValue($application->supervisor_name ?? 'N/A', 'name') }}
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Employment History -->
@if($application->previous_employer_name && !$isAvailable)
    <div class="mt-8">
        <h3 class="text-lg font-semibold text-black mb-4">Previous Employment</h3>
        <div class="bg-gray-50 rounded-lg p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="text-sm font-medium text-gray-600">Previous Employer</label>
                    <p class="text-sm font-bold text-black mt-1">{{ $application->previous_employer_name }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-600">Previous Job Title</label>
                    <p class="text-sm font-bold text-black mt-1">{{ $application->previous_job_title ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-600">Duration</label>
                    <p class="text-sm font-bold text-black mt-1">{{ $application->previous_employment_duration ?? 'N/A' }}</p>
                </div>
            </div>
            @if($application->previous_employer_reason_for_leaving)
                <div class="mt-4">
                    <label class="text-sm font-medium text-gray-600">Reason for Leaving</label>
                    <p class="text-sm font-bold text-black mt-1">{{ $application->previous_employer_reason_for_leaving }}</p>
                </div>
            @endif
        </div>
    </div>
@elseif($isAvailable)
    <div class="mt-8">
        <h3 class="text-lg font-semibold text-black mb-4">Previous Employment</h3>
        <div class="bg-gray-50 rounded-lg p-6">
            <div class="text-center py-8">
                <div class="text-gray-400 mb-4">
                    <svg class="mx-auto h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Employment History Protected</h3>
                <p class="text-sm text-gray-500">Book this lead to view employment history details.</p>
            </div>
        </div>
    </div>
@endif

<!-- Business Information (for self-employed) -->
@if($application->employment_status === 'self_employed' && !$isAvailable)
    <div class="mt-8">
        <h3 class="text-lg font-semibold text-black mb-4">Business Information</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-gray-50 rounded-lg p-6 space-y-4">
                <div>
                    <label class="text-sm font-medium text-gray-600">Business Name</label>
                    <p class="text-sm font-bold text-black mt-1">{{ $application->business_name ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-600">Business Type</label>
                    <p class="text-sm font-bold text-black mt-1">{{ ucwords(str_replace('_', ' ', $application->business_type ?? 'N/A')) }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-600">Business Registration Number</label>
                    <p class="text-sm font-bold text-black mt-1">{{ $application->business_registration_number ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-600">Years in Business</label>
                    <p class="text-sm font-bold text-black mt-1">{{ $application->years_in_business ?? 'N/A' }} years</p>
                </div>
            </div>
            
            <div class="bg-gray-50 rounded-lg p-6 space-y-4">
                <div>
                    <label class="text-sm font-medium text-gray-600">Business Address</label>
                    <p class="text-sm font-bold text-black mt-1">{{ $application->business_address ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-600">Business Phone</label>
                    <p class="text-sm font-bold text-black mt-1">{{ $application->business_phone ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-600">TIN Number</label>
                    <p class="text-sm font-bold text-black mt-1">{{ $application->business_tin ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-600">VAT Number</label>
                    <p class="text-sm font-bold text-black mt-1">{{ $application->business_vat ?? 'N/A' }}</p>
                </div>
            </div>
        </div>
    </div>
@elseif($application->employment_status === 'self_employed' && $isAvailable)
    <div class="mt-8">
        <h3 class="text-lg font-semibold text-black mb-4">Business Information</h3>
        <div class="bg-gray-50 rounded-lg p-6">
            <div class="text-center py-8">
                <div class="text-gray-400 mb-4">
                    <svg class="mx-auto h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Business Information Protected</h3>
                <p class="text-sm text-gray-500">Book this lead to view business details.</p>
            </div>
        </div>
    </div>
@endif

<!-- Employment Verification Status -->
<div class="mt-8">
    <h3 class="text-lg font-semibold text-black mb-4">Verification Status</h3>
    <div class="bg-gray-50 rounded-lg p-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="text-center">
                <div class="text-2xl font-bold text-yellow-600 mb-2">
                    @if($application->employment_verified)
                        <svg class="w-8 h-8 mx-auto text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    @else
                        <svg class="w-8 h-8 mx-auto text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    @endif
                </div>
                <p class="text-sm font-medium text-gray-700">Employment</p>
                <p class="text-xs text-gray-500">
                    {{ $application->employment_verified ? 'Verified' : 'Pending' }}
                </p>
            </div>
            
            <div class="text-center">
                <div class="text-2xl font-bold text-yellow-600 mb-2">
                    @if($application->income_verified)
                        <svg class="w-8 h-8 mx-auto text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    @else
                        <svg class="w-8 h-8 mx-auto text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    @endif
                </div>
                <p class="text-sm font-medium text-gray-700">Income</p>
                <p class="text-xs text-gray-500">
                    {{ $application->income_verified ? 'Verified' : 'Pending' }}
                </p>
            </div>
            
            <div class="text-center">
                <div class="text-2xl font-bold text-yellow-600 mb-2">
                    @if($application->reference_verified)
                        <svg class="w-8 h-8 mx-auto text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    @else
                        <svg class="w-8 h-8 mx-auto text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    @endif
                </div>
                <p class="text-sm font-medium text-gray-700">References</p>
                <p class="text-xs text-gray-500">
                    {{ $application->reference_verified ? 'Verified' : 'Pending' }}
                </p>
            </div>
        </div>
        
        @if($application->employment_verification_notes)
            <div class="mt-6 pt-6 border-t border-gray-200">
                <h4 class="text-sm font-medium text-gray-700 mb-2">Verification Notes</h4>
                <p class="text-sm text-gray-600">{{ $application->employment_verification_notes }}</p>
            </div>
        @endif
    </div>
</div>

</div>
