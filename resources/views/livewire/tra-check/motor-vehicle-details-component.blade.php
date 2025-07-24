<div>
<div class="max-w-4xl mx-auto p-6 bg-white rounded-lg shadow-lg">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-black mb-2">TRA Motor Vehicle Details Lookup</h2>
        <p class="text-gray-700">Enter vehicle registration information to retrieve details from TRA</p>
    </div>

    <!-- Input Form -->
    <div class="bg-gray-50 p-4 rounded-lg mb-6 border border-gray-200">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label for="vehicleRegistrationPlate" class="block text-sm font-medium text-black mb-1">
                    Vehicle Registration Plate *
                </label>
                <input 
                    type="text" 
                    id="vehicleRegistrationPlate"
                    wire:model="vehicleRegistrationPlate" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent uppercase font-medium"
                    placeholder="Enter registration plate (e.g., T115DYF)"
                    :disabled="$wire.isLoading"
                >
                @error('vehicleRegistrationPlate') 
                    <span class="text-red-600 text-sm">{{ $message }}</span> 
                @enderror
            </div>
            
            <div>
                <label for="dateOfRegistration" class="block text-sm font-medium text-black mb-1">
                    Date of Registration *
                </label>
                <input 
                    type="date" 
                    id="dateOfRegistration"
                    wire:model="dateOfRegistration" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
                    :disabled="$wire.isLoading"
                >
                @error('dateOfRegistration') 
                    <span class="text-red-600 text-sm">{{ $message }}</span> 
                @enderror
            </div>
        </div>
        
        <div class="flex gap-3">
            <button 
                wire:click="getVehicleDetails" 
                class="bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-6 rounded-md transition duration-200 flex items-center"
                :disabled="$wire.isLoading"
            >
                <div wire:loading wire:target="getVehicleDetails" class="animate-spin rounded-full h-4 w-4 border-b-2 border-white mr-2"></div>
                <span wire:loading.remove wire:target="getVehicleDetails">Search Vehicle</span>
                <span wire:loading wire:target="getVehicleDetails">Searching...</span>
            </button>
            
            @if($response || $error || $rawResponse)
                <button 
                    wire:click="clearResults" 
                    class="bg-black hover:bg-gray-800 text-white font-medium py-2 px-4 rounded-md transition duration-200"
                >
                    Clear Results
                </button>
            @endif
        </div>
    </div>

    <!-- Loading State -->
    <div wire:loading wire:target="getVehicleDetails" class="mb-6">
        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
            <div class="flex items-center">
                <div class="animate-spin rounded-full h-5 w-5 border-b-2 border-red-600 mr-3"></div>
                <span class="text-red-700">Sending request to TRA service...</span>
            </div>
        </div>
    </div>

    <!-- Error Display -->
    @if($error)
        <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
            <h3 class="text-lg font-semibold text-red-800 mb-2">Error</h3>
            <p class="text-red-700">{{ $error }}</p>
        </div>
    @endif

    <!-- Success Response -->
    @if($response)
        <div class="mb-6 bg-white border border-gray-200 rounded-lg p-4">
            <div class="flex items-center mb-4 pb-3 border-b border-gray-200">
                <div class="w-3 h-3 bg-red-600 rounded-full mr-3"></div>
                <h3 class="text-lg font-semibold text-black">Vehicle Details Retrieved Successfully</h3>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Vehicle Registration Information -->
                <div class="bg-white p-4 rounded-lg border-2 border-red-100">
                    <h4 class="font-semibold text-black mb-3 text-sm uppercase tracking-wide pb-2 border-b border-red-200">Vehicle Registration</h4>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between py-1">
                            <span class="text-gray-700">Registration No:</span>
                            <span class="font-medium text-black">{{ $response['registrationNo'] }}</span>
                        </div>
                        <div class="flex justify-between py-1">
                            <span class="text-gray-700">Certificate No:</span>
                            <span class="font-medium text-black">{{ $response['registrationCertificateNo'] ?: 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between py-1">
                            <span class="text-gray-700">Registered On:</span>
                            <span class="font-medium text-black">{{ $response['registeredOn'] ? date('Y-m-d', strtotime($response['registeredOn'])) : 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between py-1">
                            <span class="text-gray-700">Purpose:</span>
                            <span class="font-medium text-black">{{ $response['registrationPurpose'] ?: 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between py-1">
                            <span class="text-gray-700">Usage:</span>
                            <span class="font-medium text-black">{{ $response['vehicleUsage'] ?: 'N/A' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Vehicle Specifications -->
                <div class="bg-white p-4 rounded-lg border-2 border-red-100">
                    <h4 class="font-semibold text-black mb-3 text-sm uppercase tracking-wide pb-2 border-b border-red-200">Vehicle Specifications</h4>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between py-1">
                            <span class="text-gray-700">Make:</span>
                            <span class="font-medium text-black">{{ $response['vehicleMake'] ?: 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between py-1">
                            <span class="text-gray-700">Model:</span>
                            <span class="font-medium text-black">{{ $response['vehicleModel'] ?: 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between py-1">
                            <span class="text-gray-700">Year:</span>
                            <span class="font-medium text-black">{{ $response['yearOfMake'] ?: 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between py-1">
                            <span class="text-gray-700">Body Type:</span>
                            <span class="font-medium text-black">{{ $response['bodyType'] ?: 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between py-1">
                            <span class="text-gray-700">Colour:</span>
                            <span class="font-medium text-black">{{ $response['colour'] ?: 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between py-1">
                            <span class="text-gray-700">Category:</span>
                            <span class="font-medium text-black">{{ $response['vehCategory'] ?: 'N/A' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Engine & Technical Details -->
                <div class="bg-white p-4 rounded-lg border-2 border-red-100">
                    <h4 class="font-semibold text-black mb-3 text-sm uppercase tracking-wide pb-2 border-b border-red-200">Engine & Technical</h4>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between py-1">
                            <span class="text-gray-700">Chassis No:</span>
                            <span class="font-medium text-black">{{ $response['chassisNumber'] ?: 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between py-1">
                            <span class="text-gray-700">Engine No:</span>
                            <span class="font-medium text-black">{{ $response['engineNumber'] ?: 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between py-1">
                            <span class="text-gray-700">Engine CC:</span>
                            <span class="font-medium text-black">{{ $response['engineCubicCapacity'] ? $response['engineCubicCapacity'] . ' cc' : 'N/A' }}</span>
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
                <div class="bg-white p-4 rounded-lg border-2 border-red-100">
                    <h4 class="font-semibold text-black mb-3 text-sm uppercase tracking-wide pb-2 border-b border-red-200">Weight & Capacity</h4>
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
                <div class="bg-white p-4 rounded-lg border-2 border-red-100">
                    <h4 class="font-semibold text-black mb-3 text-sm uppercase tracking-wide pb-2 border-b border-red-200">Current Owner</h4>
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
                <div class="bg-white p-4 rounded-lg border-2 border-red-100">
                    <h4 class="font-semibold text-black mb-3 text-sm uppercase tracking-wide pb-2 border-b border-red-200">Previous Owner</h4>
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
                <div class="bg-white p-4 rounded-lg border-2 border-red-100">
                    <h4 class="font-semibold text-black mb-3 text-sm uppercase tracking-wide pb-2 border-b border-red-200">Address Information</h4>
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
    @if($rawResponse && config('app.debug'))
        <div class="mb-6">
            <details class="bg-gray-50 border border-gray-300 rounded-lg p-4">
                <summary class="cursor-pointer text-black font-medium">Raw Response (Debug Mode)</summary>
                <pre class="mt-3 text-xs text-gray-700 whitespace-pre-wrap overflow-x-auto">{{ $rawResponse }}</pre>
            </details>
        </div>
    @endif
</div>

<style>
    [x-cloak] { display: none !important; }
</style>
</div>