<div>
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Personal Information -->
    <div>
        <h3 class="text-lg font-semibold text-black mb-4">Personal Information</h3>
        <div class="bg-gray-50 rounded-lg p-6 space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-medium text-gray-600">First Name</label>
                    <p class="text-sm font-bold text-black mt-1 {{ $isAvailable ? 'blur-sm' : '' }}">
                        {{ $this->getBlurredValue($application->first_name ?? 'N/A', 'name') }}
                    </p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-600">Last Name</label>
                    <p class="text-sm font-bold text-black mt-1 {{ $isAvailable ? 'blur-sm' : '' }}">
                        {{ $this->getBlurredValue($application->last_name ?? 'N/A', 'name') }}
                    </p>
                </div>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-600">Date of Birth</label>
                <p class="text-sm font-bold text-black mt-1 {{ $isAvailable ? 'blur-sm' : '' }}">
                    @if($isAvailable)
                        ****-**-**
                    @else
                        {{ $application->date_of_birth ? \Carbon\Carbon::parse($application->date_of_birth)->format('M d, Y') : 'N/A' }}
                    @endif
                </p>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-medium text-gray-600">Gender</label>
                    <p class="text-sm font-bold text-black mt-1">
                        {{ ucfirst($application->gender ?? 'N/A') }}
                    </p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-600">Marital Status</label>
                    <p class="text-sm font-bold text-black mt-1">
                        {{ ucwords(str_replace('_', ' ', $application->marital_status ?? 'N/A')) }}
                    </p>
                </div>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-600">National ID</label>
                <p class="text-sm font-bold text-black mt-1 {{ $isAvailable ? 'blur-sm' : '' }}">
                    @if($isAvailable)
                        NIDA****
                    @else
                        {{ $application->national_id ?? 'N/A' }}
                    @endif
                </p>
            </div>
        </div>
    </div>

    <!-- Contact Information -->
    <div>
        <h3 class="text-lg font-semibold text-black mb-4">Contact Information</h3>
        <div class="bg-gray-50 rounded-lg p-6 space-y-4">
            <div>
                <label class="text-sm font-medium text-gray-600">Email Address</label>
                <p class="text-sm font-bold text-black mt-1 {{ $isAvailable ? 'blur-sm' : '' }}">
                    @if($isAvailable)
                        ***@***.***
                    @else
                        {{ $application->email ?? 'N/A' }}
                    @endif
                </p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-600">Phone Number</label>
                <p class="text-sm font-bold text-black mt-1 {{ $isAvailable ? 'blur-sm' : '' }}">
                    @if($isAvailable)
                        +255***-***-***
                    @else
                        {{ $application->phone_number ?? 'N/A' }}
                    @endif
                </p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-600">Alternative Phone</label>
                <p class="text-sm font-bold text-black mt-1 {{ $isAvailable ? 'blur-sm' : '' }}">
                    @if($isAvailable)
                        +255***-***-***
                    @else
                        {{ $application->alternative_phone ?? 'N/A' }}
                    @endif
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Address Information -->
<div class="mt-8">
    <h3 class="text-lg font-semibold text-black mb-4">Address Information</h3>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Current Address -->
        <div>
            <h4 class="text-md font-medium text-gray-700 mb-3">Current Residence</h4>
            <div class="bg-gray-50 rounded-lg p-6 space-y-4">
                <div>
                    <label class="text-sm font-medium text-gray-600">Address</label>
                    <p class="text-sm font-bold text-black mt-1 {{ $isAvailable ? 'blur-sm' : '' }}">
                        {{ $this->getBlurredValue($application->current_address ?? 'N/A', 'address') }}
                    </p>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-medium text-gray-600">City</label>
                        <p class="text-sm font-bold text-black mt-1 {{ $isAvailable ? 'blur-sm' : '' }}">
                            {{ $this->getBlurredValue($application->current_city ?? 'N/A', 'city') }}
                        </p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-600">Region</label>
                        <p class="text-sm font-bold text-black mt-1 {{ $isAvailable ? 'blur-sm' : '' }}">
                            {{ $this->getBlurredValue($application->current_region ?? 'N/A', 'region') }}
                        </p>
                    </div>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-600">Postal Code</label>
                    <p class="text-sm font-bold text-black mt-1 {{ $isAvailable ? 'blur-sm' : '' }}">
                        @if($isAvailable)
                            *****
                        @else
                            {{ $application->postal_code ?? 'N/A' }}
                        @endif
                    </p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-600">Years at Address</label>
                    <p class="text-sm font-bold text-black mt-1">
                        {{ $application->years_at_current_address ?? 'N/A' }} years
                    </p>
                </div>
            </div>
        </div>

        <!-- Previous Address (if applicable) -->
        @if($application->previous_address && !$isAvailable)
            <div>
                <h4 class="text-md font-medium text-gray-700 mb-3">Previous Residence</h4>
                <div class="bg-gray-50 rounded-lg p-6 space-y-4">
                    <div>
                        <label class="text-sm font-medium text-gray-600">Address</label>
                        <p class="text-sm font-bold text-black mt-1">{{ $application->previous_address }}</p>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-medium text-gray-600">City</label>
                            <p class="text-sm font-bold text-black mt-1">{{ $application->previous_city ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600">Region</label>
                            <p class="text-sm font-bold text-black mt-1">{{ $application->previous_region ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Emergency Contact -->
<div class="mt-8">
    <h3 class="text-lg font-semibold text-black mb-4">Emergency Contact</h3>
    <div class="bg-gray-50 rounded-lg p-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <label class="text-sm font-medium text-gray-600">Full Name</label>
                <p class="text-sm font-bold text-black mt-1 {{ $isAvailable ? 'blur-sm' : '' }}">
                    @if($isAvailable)
                        {{ substr($application->emergency_contact_name ?? 'N/A', 0, 1) }}***
                    @else
                        {{ $application->emergency_contact_name ?? 'N/A' }}
                    @endif
                </p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-600">Relationship</label>
                <p class="text-sm font-bold text-black mt-1">{{ ucwords(str_replace('_', ' ', $application->emergency_contact_relationship ?? 'N/A')) }}</p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-600">Phone Number</label>
                <p class="text-sm font-bold text-black mt-1 {{ $isAvailable ? 'blur-sm' : '' }}">
                    @if($isAvailable)
                        +255***-***-***
                    @else
                        {{ $application->emergency_contact_phone ?? 'N/A' }}
                    @endif
                </p>
            </div>
        </div>
    </div>
</div>

</div>
