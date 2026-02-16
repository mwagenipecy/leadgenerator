<div>
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Current Employment -->
    <div>
        <h3 class="text-lg font-semibold text-black mb-4">{{ __('leads.current_employment') }}</h3>
        <div class="bg-gray-50 rounded-lg p-6 space-y-4">
            <div>
                <label class="text-sm font-medium text-gray-600">{{ __('leads.employment_status') }}</label>
                <p class="text-sm font-bold text-black mt-1 {{ $isAvailable ? 'blur-sm' : '' }}">
                    @if($isAvailable)
                        {{ substr($application->employment_status, 0, 3) }}***
                    @else
                        {{ ucwords(str_replace('_', ' ', $application->employment_status ?? 'N/A')) }}
                    @endif
                </p>
            </div>
            <div>
                    <label class="text-sm font-medium text-gray-600">{{ __('leads.company_name') }}</label>
                    <p class="text-sm font-bold text-black mt-1 {{ $isAvailable ? 'blur-sm' : '' }}">
                        {{ $this->getBlurredValue($application->employer_name ?? 'N/A', 'company') }}
                    </p>
                </div>
            <div>
                <label class="text-sm font-medium text-gray-600">{{ __('leads.job_title') }}</label>
                <p class="text-sm font-bold text-black mt-1 {{ $isAvailable ? 'blur-sm' : '' }}">
                    @if($isAvailable)
                        ***Position
                    @else
                        {{ $application->job_title ?? 'N/A' }}
                    @endif
                </p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-600">{{ __('leads.industry_sector') }}</label>
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
                    <label class="text-sm font-medium text-gray-600">{{ __('leads.years_with_employer') }}</label>
                    <p class="text-sm font-bold text-black mt-1">
                        {{ $application->years_with_current_employer ?? 'N/A' }} {{ __('leads.years') }}
                    </p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-600">{{ __('leads.employment_type') }}</label>
                    <p class="text-sm font-bold text-black mt-1">
                        {{ ucwords(str_replace('_', ' ', $application->employment_type ?? 'N/A')) }}
                    </p>
                </div>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-600">{{ __('leads.employee_id') }}</label>
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
        <h3 class="text-lg font-semibold text-black mb-4">{{ __('leads.employer_information') }}</h3>
        <div class="bg-gray-50 rounded-lg p-6 space-y-4">
            <div>
                <label class="text-sm font-medium text-gray-600">{{ __('leads.company_address') }}</label>
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
                    <label class="text-sm font-medium text-gray-600">{{ __('leads.company_phone') }}</label>
                    <p class="text-sm font-bold text-black mt-1 {{ $isAvailable ? 'blur-sm' : '' }}">
                        {{ $this->getBlurredValue($application->employer_phone ?? 'N/A', 'phone') }}
                    </p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-600">{{ __('leads.company_email') }}</label>
                    <p class="text-sm font-bold text-black mt-1 {{ $isAvailable ? 'blur-sm' : '' }}">
                        {{ $this->getBlurredValue($application->employer_email ?? 'N/A', 'email') }}
                    </p>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-medium text-gray-600">{{ __('leads.hr_contact_name') }}</label>
                    <p class="text-sm font-bold text-black mt-1 {{ $isAvailable ? 'blur-sm' : '' }}">
                        {{ $this->getBlurredValue($application->hr_contact_name ?? 'N/A', 'name') }}
                    </p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-600">{{ __('leads.hr_contact_phone') }}</label>
                    <p class="text-sm font-bold text-black mt-1 {{ $isAvailable ? 'blur-sm' : '' }}">
                        {{ $this->getBlurredValue($application->hr_contact_phone ?? 'N/A', 'phone') }}
                    </p>
                </div>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-600">{{ __('leads.hr_email') }}</label>
                <p class="text-sm font-bold text-black mt-1 {{ $isAvailable ? 'blur-sm' : '' }}">
                    {{ $this->getBlurredValue($application->hr_contact_email ?? 'N/A', 'email') }}
                </p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-600">{{ __('leads.supervisor_name') }}</label>
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
        <h3 class="text-lg font-semibold text-black mb-4">{{ __('leads.previous_employment') }}</h3>
        <div class="bg-gray-50 rounded-lg p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="text-sm font-medium text-gray-600">{{ __('leads.previous_employer') }}</label>
                    <p class="text-sm font-bold text-black mt-1">{{ $application->previous_employer_name }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-600">{{ __('leads.previous_job_title') }}</label>
                    <p class="text-sm font-bold text-black mt-1">{{ $application->previous_job_title ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-600">{{ __('leads.duration') }}</label>
                    <p class="text-sm font-bold text-black mt-1">{{ $application->previous_employment_duration ?? 'N/A' }}</p>
                </div>
            </div>
            @if($application->previous_employer_reason_for_leaving)
                <div class="mt-4">
                    <label class="text-sm font-medium text-gray-600">{{ __('leads.reason_for_leaving') }}</label>
                    <p class="text-sm font-bold text-black mt-1">{{ $application->previous_employer_reason_for_leaving }}</p>
                </div>
            @endif
        </div>
    </div>
@elseif($isAvailable)
    <div class="mt-8">
        <h3 class="text-lg font-semibold text-black mb-4">{{ __('leads.previous_employment') }}</h3>
        <div class="bg-gray-50 rounded-lg p-6">
            <div class="text-center py-8">
                <div class="text-gray-400 mb-4">
                    <svg class="mx-auto h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">{{ __('leads.employment_history_protected') }}</h3>
                <p class="text-sm text-gray-500">{{ __('leads.book_to_view_employment_history') }}</p>
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
                    <label class="text-sm font-medium text-gray-600">{{ __('leads.business_name') }}</label>
                    <p class="text-sm font-bold text-black mt-1">{{ $application->business_name ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-600">{{ __('leads.business_type') }}</label>
                    <p class="text-sm font-bold text-black mt-1">{{ ucwords(str_replace('_', ' ', $application->business_type ?? 'N/A')) }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-600">{{ __('leads.business_registration_number') }}</label>
                    <p class="text-sm font-bold text-black mt-1">{{ $application->business_registration_number ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-600">{{ __('leads.years_in_business') }}</label>
                    <p class="text-sm font-bold text-black mt-1">{{ $application->years_in_business ?? 'N/A' }} {{ __('leads.years') }}</p>
                </div>
            </div>
            
            <div class="bg-gray-50 rounded-lg p-6 space-y-4">
                <div>
                    <label class="text-sm font-medium text-gray-600">{{ __('leads.business_address') }}</label>
                    <p class="text-sm font-bold text-black mt-1">{{ $application->business_address ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-600">{{ __('leads.business_phone') }}</label>
                    <p class="text-sm font-bold text-black mt-1">{{ $application->business_phone ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-600">{{ __('leads.tin_number') }}</label>
                    <p class="text-sm font-bold text-black mt-1">{{ $application->business_tin ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-600">{{ __('leads.vat_number') }}</label>
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
                <h3 class="text-lg font-medium text-gray-900 mb-2">{{ __('leads.business_information_protected') }}</h3>
                <p class="text-sm text-gray-500">{{ __('leads.book_to_view_business_details') }}</p>
            </div>
        </div>
    </div>
@endif

<!-- Employment Verification Status -->
<div class="mt-8">
    
</div>

</div>
