<div>
<div class="max-w-4xl mx-auto p-6 bg-white rounded-lg shadow-lg">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-black mb-2">{{ __('verification.tra_motor_vehicle_lookup') }}</h2>
        <p class="text-gray-700">{{ __('verification.enter_vehicle_info') }}</p>
    </div>

    <!-- Input Form -->
    <div class="bg-gray-50 p-4 rounded-lg mb-6 border border-gray-200">
        <div class="mb-4">
            <div>
                <label for="vehicleRegistrationPlate" class="block text-sm font-medium text-black mb-1">
                    {{ __('verification.vehicle_registration_plate') }} *
                </label>
                <input 
                    type="text" 
                    id="vehicleRegistrationPlate"
                    wire:model="vehicleRegistrationPlate" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-sidebar-green focus:border-transparent uppercase font-medium"
                    placeholder="{{ __('verification.enter_registration_plate') }}"
                    :disabled="$wire.isLoading"
                >
                @error('vehicleRegistrationPlate') 
                    <span class="text-sidebar-green text-sm">{{ $message }}</span> 
                @enderror
            </div>
        </div>
        
        <div class="flex gap-3">
            <button 
                wire:click="getVehicleDetails" 
                class="bg-sidebar-green hover:bg-sidebar-green-light text-white font-medium py-2 px-6 rounded-md transition duration-200 flex items-center"
                :disabled="$wire.isLoading"
            >
                <div wire:loading wire:target="getVehicleDetails" class="animate-spin rounded-full h-4 w-4 border-b-2 border-white mr-2"></div>
                <span wire:loading.remove wire:target="getVehicleDetails">{{ __('verification.search_vehicle') }}</span>
                <span wire:loading wire:target="getVehicleDetails">{{ __('verification.searching') }}</span>
            </button>
            
            @if($response || $error || $rawResponse)
                <button 
                    wire:click="clearResults" 
                    class="bg-black hover:bg-gray-800 text-white font-medium py-2 px-4 rounded-md transition duration-200"
                >
                    {{ __('verification.clear_results') }}
                </button>
            @endif
        </div>
    </div>

    <!-- Loading State -->
    <div wire:loading wire:target="getVehicleDetails" class="mb-6">
        <div class="bg-sidebar-green-50 border border-sidebar-green-200 rounded-lg p-4">
            <div class="flex items-center">
                <div class="animate-spin rounded-full h-5 w-5 border-b-2 border-sidebar-green mr-3"></div>
                <span class="text-sidebar-green-light">{{ __('verification.sending_request') }}</span>
            </div>
        </div>
    </div>

    <!-- Error Display -->
    @if($error)
        <div class="mb-6 bg-sidebar-green-50 border border-sidebar-green-200 rounded-lg p-4">
            <h3 class="text-lg font-semibold text-sidebar-green-800 mb-2">{{ __('verification.error') }}</h3>
            <p class="text-sidebar-green-light">{{ $error }}</p>
        </div>
    @endif

    <!-- Success Response -->
    @if($response)
        <div class="mb-6 bg-white border border-gray-200 rounded-lg p-4">
            <div class="flex items-center mb-4 pb-3 border-b border-gray-200">
                <div class="w-3 h-3 bg-sidebar-green rounded-full mr-3"></div>
                <h3 class="text-lg font-semibold text-black">{{ __('verification.vehicle_details_retrieved') }}</h3>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Vehicle Registration Information -->
                <div class="bg-white p-4 rounded-lg border-2 border-sidebar-green-100">
                    <h4 class="font-semibold text-black mb-3 text-sm uppercase tracking-wide pb-2 border-b border-sidebar-green-200">{{ __('verification.vehicle_registration') }}</h4>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between py-1">
                            <span class="text-gray-700">{{ __('verification.registration_no') }}:</span>
                            <span class="font-medium text-black">{{ $response['registrationNo'] }}</span>
                        </div>
                        <div class="flex justify-between py-1">
                            <span class="text-gray-700">{{ __('verification.certificate_no') }}:</span>
                            <span class="font-medium text-black">{{ $response['registrationCertificateNo'] ?: __('verification.n_a') }}</span>
                        </div>
                        <div class="flex justify-between py-1">
                            <span class="text-gray-700">{{ __('verification.registered_on') }}:</span>
                            <span class="font-medium text-black">{{ $response['registeredOn'] ? date('Y-m-d', strtotime($response['registeredOn'])) : __('verification.n_a') }}</span>
                        </div>
                        <div class="flex justify-between py-1">
                            <span class="text-gray-700">{{ __('verification.purpose') }}:</span>
                            <span class="font-medium text-black">{{ $response['registrationPurpose'] ?: __('verification.n_a') }}</span>
                        </div>
                        <div class="flex justify-between py-1">
                            <span class="text-gray-700">{{ __('verification.usage') }}:</span>
                            <span class="font-medium text-black">{{ $response['vehicleUsage'] ?: __('verification.n_a') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Vehicle Specifications -->
                <div class="bg-white p-4 rounded-lg border-2 border-sidebar-green-100">
                    <h4 class="font-semibold text-black mb-3 text-sm uppercase tracking-wide pb-2 border-b border-sidebar-green-200">{{ __('verification.vehicle_specifications') }}</h4>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between py-1">
                            <span class="text-gray-700">{{ __('verification.make') }}:</span>
                            <span class="font-medium text-black">{{ $response['vehicleMake'] ?: __('verification.n_a') }}</span>
                        </div>
                        <div class="flex justify-between py-1">
                            <span class="text-gray-700">{{ __('verification.model') }}:</span>
                            <span class="font-medium text-black">{{ $response['vehicleModel'] ?: __('verification.n_a') }}</span>
                        </div>
                        <div class="flex justify-between py-1">
                            <span class="text-gray-700">{{ __('verification.year') }}:</span>
                            <span class="font-medium text-black">{{ $response['yearOfMake'] ?: __('verification.n_a') }}</span>
                        </div>
                        <div class="flex justify-between py-1">
                            <span class="text-gray-700">{{ __('verification.body_type') }}:</span>
                            <span class="font-medium text-black">{{ $response['bodyType'] ?: __('verification.n_a') }}</span>
                        </div>
                        <div class="flex justify-between py-1">
                            <span class="text-gray-700">{{ __('verification.colour') }}:</span>
                            <span class="font-medium text-black">{{ $response['colour'] ?: __('verification.n_a') }}</span>
                        </div>
                        <div class="flex justify-between py-1">
                            <span class="text-gray-700">{{ __('verification.category') }}:</span>
                            <span class="font-medium text-black">{{ $response['vehCategory'] ?: __('verification.n_a') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Engine & Technical Details -->
                <div class="bg-white p-4 rounded-lg border-2 border-sidebar-green-100">
                    <h4 class="font-semibold text-black mb-3 text-sm uppercase tracking-wide pb-2 border-b border-sidebar-green-200">{{ __('verification.engine_technical') }}</h4>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between py-1">
                            <span class="text-gray-700">{{ __('verification.chassis_no') }}:</span>
                            <span class="font-medium text-black">{{ $response['chassisNumber'] ?: __('verification.n_a') }}</span>
                        </div>
                        <div class="flex justify-between py-1">
                            <span class="text-gray-700">{{ __('verification.engine_no') }}:</span>
                            <span class="font-medium text-black">{{ $response['engineNumber'] ?: __('verification.n_a') }}</span>
                        </div>
                        <div class="flex justify-between py-1">
                            <span class="text-gray-700">{{ __('verification.engine_cc') }}:</span>
                            <span class="font-medium text-black">{{ $response['engineCubicCapacity'] ? $response['engineCubicCapacity'] . ' ' . __('verification.cc') : __('verification.n_a') }}</span>
                        </div>
                        <div class="flex justify-between py-1">
                            <span class="text-gray-700">Fuel Type:</span>
                            <span class="font-medium text-black">{{ $response['fuelType'] ?: 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between py-1">
                            <span class="text-gray-700">Transmission:</span>
                            <span class="font-medium text-black">{{ $response['transmissionBy'] ?: 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between py-1">
                            <span class="text-gray-700">Propelled By:</span>
                            <span class="font-medium text-black">{{ $response['propelledBy'] ?: 'N/A' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Weight & Capacity -->
                <div class="bg-white p-4 rounded-lg border-2 border-sidebar-green-100">
                    <h4 class="font-semibold text-black mb-3 text-sm uppercase tracking-wide pb-2 border-b border-sidebar-green-200">Weight & Capacity</h4>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between py-1">
                            <span class="text-gray-700">Gross Weight:</span>
                            <span class="font-medium text-black">{{ $response['grossWeight'] ? $response['grossWeight'] . ' kg' : 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between py-1">
                            <span class="text-gray-700">Tare Weight:</span>
                            <span class="font-medium text-black">{{ $response['tareWeight'] ? $response['tareWeight'] . ' kg' : 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between py-1">
                            <span class="text-gray-700">Seating Capacity:</span>
                            <span class="font-medium text-black">{{ $response['seatingCapacity'] ? $response['seatingCapacity'] . ' persons' : 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between py-1">
                            <span class="text-gray-700">Number of Axles:</span>
                            <span class="font-medium text-black">{{ $response['numberOfAxles'] ?: 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between py-1">
                            <span class="text-gray-700">Custom Duty Exempted:</span>
                            <span class="font-medium text-black">{{ $response['customDutyExempted'] == 'true' ? 'Yes' : 'No' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Current Owner Information -->
                <div class="bg-white p-4 rounded-lg border-2 border-sidebar-green-100">
                    <h4 class="font-semibold text-black mb-3 text-sm uppercase tracking-wide pb-2 border-b border-sidebar-green-200">Current Owner</h4>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between py-1">
                            <span class="text-gray-700">Full Name:</span>
                            <span class="font-medium text-black">{{ trim($response['firstName'] . ' ' . $response['middleName'] . ' ' . $response['lastName']) ?: $response['titleHolderName'] }}</span>
                        </div>
                        <div class="flex justify-between py-1">
                            <span class="text-gray-700">Category:</span>
                            <span class="font-medium text-black">{{ $response['titleHolderCategory'] ?: 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between py-1">
                            <span class="text-gray-700">Date of Birth:</span>
                            <span class="font-medium text-black">{{ $response['titleHolderDOB'] ? date('Y-m-d', strtotime($response['titleHolderDOB'])) : 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between py-1">
                            <span class="text-gray-700">Identity No:</span>
                            <span class="font-medium text-black">{{ $response['ownerIdentityNo'] ?: 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between py-1">
                            <span class="text-gray-700">Identity Type:</span>
                            <span class="font-medium text-black">{{ $response['ownerIdentityNoType'] ?: 'N/A' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Previous Owner Information -->
                @if($response['previousOwnerName'] || $response['previousOwnerTin'])
                <div class="bg-white p-4 rounded-lg border-2 border-sidebar-green-100">
                    <h4 class="font-semibold text-black mb-3 text-sm uppercase tracking-wide pb-2 border-b border-sidebar-green-200">Previous Owner</h4>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between py-1">
                            <span class="text-gray-700">Name:</span>
                            <span class="font-medium text-black">{{ $response['previousOwnerName'] ?: 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between py-1">
                            <span class="text-gray-700">TIN:</span>
                            <span class="font-medium text-black">{{ $response['previousOwnerTin'] ?: 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between py-1">
                            <span class="text-gray-700">Change Date:</span>
                            <span class="font-medium text-black">{{ $response['changeOwnerDate'] ? date('Y-m-d', strtotime($response['changeOwnerDate'])) : 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between py-1">
                            <span class="text-gray-700">Change Reason:</span>
                            <span class="font-medium text-black">{{ $response['changeOwnerReason'] ?: 'N/A' }}</span>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Address Information -->
                @if($response['postalAddress'])
                <div class="bg-white p-4 rounded-lg border-2 border-sidebar-green-100">
                    <h4 class="font-semibold text-black mb-3 text-sm uppercase tracking-wide pb-2 border-b border-sidebar-green-200">Address Information</h4>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between py-1">
                            <span class="text-gray-700">Postal Address:</span>
                            <span class="font-medium text-black">{{ $response['postalAddress'] ?: 'N/A' }}</span>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    @endif

    <!-- Raw Response (for debugging) -->
    
</div>

<style>
    [x-cloak] { display: none !important; }
</style>
</div>