<div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50 py-8">
    <div class="max-w-7xl mx-auto px-6">
        <!-- Header Section -->
        <div class="bg-white rounded-2xl shadow-xl border border-slate-200 mb-8 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-8 py-6">
                <h1 class="text-3xl font-bold text-white mb-2">TRA Motor Vehicle Details Lookup</h1>
                <p class="text-blue-100">Enter vehicle registration information to retrieve comprehensive details from Tanzania Revenue Authority</p>
            </div>
        </div>

        <!-- Input Form -->
        <div class="bg-white rounded-2xl shadow-xl border border-slate-200 p-8 mb-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="space-y-2">
                    <label for="vehicleRegistrationPlate" class="block text-sm font-semibold text-slate-700">
                        Vehicle Registration Plate *
                    </label>
                    <input 
                        type="text" 
                        id="vehicleRegistrationPlate"
                        wire:model="vehicleRegistrationPlate" 
                        class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 uppercase font-medium"
                        placeholder="Enter registration plate (e.g., T115DYF)"
                        :disabled="$wire.isLoading"
                    >
                    @error('vehicleRegistrationPlate') 
                        <span class="text-red-500 text-sm font-medium">{{ $message }}</span> 
                    @enderror
                </div>
                
                <div class="space-y-2">
                    <label for="dateOfRegistration" class="block text-sm font-semibold text-slate-700">
                        Date of Registration *
                    </label>
                    <input 
                        type="date" 
                        id="dateOfRegistration"
                        wire:model="dateOfRegistration" 
                        class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                        :disabled="$wire.isLoading"
                    >
                    @error('dateOfRegistration') 
                        <span class="text-red-500 text-sm font-medium">{{ $message }}</span> 
                    @enderror
                </div>
            </div>
            
            <div class="flex gap-4">
                <button 
                    wire:click="getVehicleDetails" 
                    class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold py-3 px-8 rounded-xl transition-all duration-200 flex items-center shadow-lg hover:shadow-xl transform hover:-translate-y-0.5"
                    :disabled="$wire.isLoading"
                >
                    <div wire:loading wire:target="getVehicleDetails" class="animate-spin rounded-full h-5 w-5 border-b-2 border-white mr-3"></div>
                    <svg wire:loading.remove wire:target="getVehicleDetails" class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <span wire:loading.remove wire:target="getVehicleDetails">Search Vehicle</span>
                    <span wire:loading wire:target="getVehicleDetails">Searching...</span>
                </button>
                
                @if($response || $error || $rawResponse)
                    <button 
                        wire:click="clearResults" 
                        class="bg-slate-500 hover:bg-slate-600 text-white font-semibold py-3 px-6 rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5"
                    >
                        <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        Clear Results
                    </button>
                @endif
            </div>
        </div>

        <!-- Loading State -->
        <div wire:loading wire:target="getVehicleDetails" class="mb-8">
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border-2 border-blue-200 rounded-2xl p-6 shadow-lg">
                <div class="flex items-center">
                    <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-600 mr-4"></div>
                    <span class="text-blue-800 font-semibold">Connecting to TRA service and retrieving vehicle details...</span>
                </div>
            </div>
        </div>

        <!-- Error Display -->
        @if($error)
            <div class="mb-8 bg-gradient-to-r from-red-50 to-pink-50 border-2 border-red-200 rounded-2xl p-6 shadow-lg">
                <div class="flex items-center mb-3">
                    <svg class="w-6 h-6 text-red-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h3 class="text-xl font-bold text-red-800">Error Occurred</h3>
                </div>
                <p class="text-red-700 font-medium">{{ $error }}</p>
            </div>
        @endif

        <!-- Success Response -->
        @if($response)
            <div class="mb-8 bg-gradient-to-r from-green-50 to-emerald-50 border-2 border-green-200 rounded-2xl p-6 shadow-lg">
                <div class="flex items-center mb-6">
                    <svg class="w-6 h-6 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h3 class="text-xl font-bold text-green-800">Vehicle Details Retrieved Successfully</h3>
                </div>
                
                <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
                    <!-- Vehicle Basic Information -->
                    <div class="bg-white p-6 rounded-xl border-2 border-slate-200 shadow-lg hover:shadow-xl transition-all duration-200">
                        <div class="flex items-center mb-4">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <h4 class="font-bold text-slate-800 text-sm uppercase tracking-wide">Vehicle Registration</h4>
                        </div>
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between items-center py-2 border-b border-slate-100">
                                <span class="text-slate-600 font-medium">Registration No:</span>
                                <span class="font-bold text-blue-700 bg-blue-50 px-2 py-1 rounded">{{ $response['registrationNo'] }}</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-slate-100">
                                <span class="text-slate-600 font-medium">Certificate No:</span>
                                <span class="font-semibold text-slate-800">{{ $response['registrationCertificateNo'] }}</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-slate-100">
                                <span class="text-slate-600 font-medium">Registered On:</span>
                                <span class="font-semibold text-slate-800">{{ $response['registeredOn'] ? date('Y-m-d', strtotime($response['registeredOn'])) : 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-slate-100">
                                <span class="text-slate-600 font-medium">Purpose:</span>
                                <span class="font-semibold text-slate-800">{{ $response['registrationPurpose'] ?: 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between items-center py-2">
                                <span class="text-slate-600 font-medium">Usage:</span>
                                <span class="font-semibold text-slate-800">{{ $response['vehicleUsage'] ?: 'N/A' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Vehicle Specifications -->
                    <div class="bg-white p-6 rounded-xl border-2 border-slate-200 shadow-lg hover:shadow-xl transition-all duration-200">
                        <div class="flex items-center mb-4">
                            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                            </div>
                            <h4 class="font-bold text-slate-800 text-sm uppercase tracking-wide">Vehicle Specifications</h4>
                        </div>
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between items-center py-2 border-b border-slate-100">
                                <span class="text-slate-600 font-medium">Make:</span>
                                <span class="font-semibold text-slate-800">{{ $response['vehicleMake'] ?: 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-slate-100">
                                <span class="text-slate-600 font-medium">Model:</span>
                                <span class="font-semibold text-slate-800">{{ $response['vehicleModel'] ?: 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-slate-100">
                                <span class="text-slate-600 font-medium">Year:</span>
                                <span class="font-semibold text-slate-800">{{ $response['yearOfMake'] ?: 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-slate-100">
                                <span class="text-slate-600 font-medium">Body Type:</span>
                                <span class="font-semibold text-slate-800">{{ $response['bodyType'] ?: 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-slate-100">
                                <span class="text-slate-600 font-medium">Colour:</span>
                                <span class="font-semibold text-slate-800">{{ $response['colour'] ?: 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between items-center py-2">
                                <span class="text-slate-600 font-medium">Category:</span>
                                <span class="font-semibold text-slate-800">{{ $response['vehCategory'] ?: 'N/A' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Engine & Technical Details -->
                    <div class="bg-white p-6 rounded-xl border-2 border-slate-200 shadow-lg hover:shadow-xl transition-all duration-200">
                        <div class="flex items-center mb-4">
                            <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                            <h4 class="font-bold text-slate-800 text-sm uppercase tracking-wide">Engine & Technical</h4>
                        </div>
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between items-center py-2 border-b border-slate-100">
                                <span class="text-slate-600 font-medium">Chassis No:</span>
                                <span class="font-semibold text-slate-800 text-xs">{{ $response['chassisNumber'] ?: 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-slate-100">
                                <span class="text-slate-600 font-medium">Engine No:</span>
                                <span class="font-semibold text-slate-800 text-xs">{{ $response['engineNumber'] ?: 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-slate-100">
                                <span class="text-slate-600 font-medium">Engine CC:</span>
                                <span class="font-semibold text-slate-800">{{ $response['engineCubicCapacity'] ? $response['engineCubicCapacity'] . ' cc' : 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-slate-100">
                                <span class="text-slate-600 font-medium">Fuel Type:</span>
                                <span class="font-semibold text-slate-800">{{ $response['fuelType'] ?: 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-slate-100">
                                <span class="text-slate-600 font-medium">Transmission:</span>
                                <span class="font-semibold text-slate-800">{{ $response['transmissionBy'] ?: 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between items-center py-2">
                                <span class="text-slate-600 font-medium">Propelled By:</span>
                                <span class="font-semibold text-slate-800">{{ $response['propelledBy'] ?: 'N/A' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Weight & Capacity -->
                    <div class="bg-white p-6 rounded-xl border-2 border-slate-200 shadow-lg hover:shadow-xl transition-all duration-200">
                        <div class="flex items-center mb-4">
                            <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16l3-1m-3 1l-3-1"></path>
                                </svg>
                            </div>
                            <h4 class="font-bold text-slate-800 text-sm uppercase tracking-wide">Weight & Capacity</h4>
                        </div>
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between items-center py-2 border-b border-slate-100">
                                <span class="text-slate-600 font-medium">Gross Weight:</span>
                                <span class="font-semibold text-slate-800">{{ $response['grossWeight'] ? $response['grossWeight'] . ' kg' : 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-slate-100">
                                <span class="text-slate-600 font-medium">Tare Weight:</span>
                                <span class="font-semibold text-slate-800">{{ $response['tareWeight'] ? $response['tareWeight'] . ' kg' : 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-slate-100">
                                <span class="text-slate-600 font-medium">Seating Capacity:</span>
                                <span class="font-semibold text-slate-800">{{ $response['seatingCapacity'] ? $response['seatingCapacity'] . ' persons' : 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-slate-100">
                                <span class="text-slate-600 font-medium">Number of Axles:</span>
                                <span class="font-semibold text-slate-800">{{ $response['numberOfAxles'] ?: 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between items-center py-2">
                                <span class="text-slate-600 font-medium">Custom Duty Exempted:</span>
                                <span class="font-semibold px-2 py-1 rounded-full text-xs {{ $response['customDutyExempted'] == 'true' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    {{ $response['customDutyExempted'] == 'true' ? 'Yes' : 'No' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Current Owner Information -->
                    <div class="bg-white p-6 rounded-xl border-2 border-slate-200 shadow-lg hover:shadow-xl transition-all duration-200">
                        <div class="flex items-center mb-4">
                            <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <h4 class="font-bold text-slate-800 text-sm uppercase tracking-wide">Current Owner</h4>
                        </div>
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between items-center py-2 border-b border-slate-100">
                                <span class="text-slate-600 font-medium">Full Name:</span>
                                <span class="font-semibold text-slate-800 text-right">{{ trim($response['firstName'] . ' ' . $response['middleName'] . ' ' . $response['lastName']) ?: $response['titleHolderName'] }}</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-slate-100">
                                <span class="text-slate-600 font-medium">Category:</span>
                                <span class="font-semibold text-slate-800">{{ $response['titleHolderCategory'] ?: 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-slate-100">
                                <span class="text-slate-600 font-medium">Date of Birth:</span>
                                <span class="font-semibold text-slate-800">{{ $response['titleHolderDOB'] ? date('Y-m-d', strtotime($response['titleHolderDOB'])) : 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-slate-100">
                                <span class="text-slate-600 font-medium">Identity No:</span>
                                <span class="font-semibold text-slate-800">{{ $response['ownerIdentityNo'] ?: 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between items-center py-2">
                                <span class="text-slate-600 font-medium">Identity Type:</span>
                                <span class="font-semibold text-slate-800">{{ $response['ownerIdentityNoType'] ?: 'N/A' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Previous Owner Information -->
                    @if($response['previousOwnerName'] || $response['previousOwnerTin'])
                    <div class="bg-white p-6 rounded-xl border-2 border-slate-200 shadow-lg hover:shadow-xl transition-all duration-200">
                        <div class="flex items-center mb-4">
                            <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h4 class="font-bold text-slate-800 text-sm uppercase tracking-wide">Previous Owner</h4>
                        </div>
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between items-center py-2 border-b border-slate-100">
                                <span class="text-slate-600 font-medium">Name:</span>
                                <span class="font-semibold text-slate-800">{{ $response['previousOwnerName'] ?: 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-slate-100">
                                <span class="text-slate-600 font-medium">TIN:</span>
                                <span class="font-semibold text-slate-800">{{ $response['previousOwnerTin'] ?: 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-slate-100">
                                <span class="text-slate-600 font-medium">Change Date:</span>
                                <span class="font-semibold text-slate-800">{{ $response['changeOwnerDate'] ? date('Y-m-d', strtotime($response['changeOwnerDate'])) : 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between items-center py-2">
                                <span class="text-slate-600 font-medium">Change Reason:</span>
                                <span class="font-semibold text-slate-800">{{ $response['changeOwnerReason'] ?: 'N/A' }}</span>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Postal Address (Full Width) -->
                @if($response['postalAddress'])
                <div class="mt-6 bg-white p-6 rounded-xl border-2 border-slate-200 shadow-lg">
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 bg-teal-100 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <h4 class="font-bold text-slate-800 text-sm uppercase tracking-wide">Address Information</h4>
                    </div>
                    <div class="text-sm">
                        <span class="text-slate-600 font-medium">Postal Address:</span>
                        <p class="font-semibold text-slate-800 mt-2 p-3 bg-slate-50 rounded-lg">{{ $response['postalAddress'] }}</p>
                    </div>
                </div>
                @endif
            </div>
        @endif

        <!-- Raw Response (for debugging) -->
        @if($rawResponse && config('app.debug'))
            <div class="mb-8">
                <details class="bg-white border-2 border-slate-200 rounded-2xl p-6 shadow-lg">
                    <summary class="cursor-pointer text-slate-700 font-semibold hover:text-blue-600 transition-colors duration-200 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                        </svg>
                        Raw Response (Debug Mode)
                    </summary>
                    <div class="mt-4 p-4 bg-slate-50 rounded-xl border border-slate-200">
                        <pre class="text-xs text-slate-600 whitespace-pre-wrap overflow-x-auto font-mono">{{ $rawResponse }}</pre>
                    </div>
                </details>
            </div>
        @endif
    </div>

    <style>
    [x-cloak] { display: none !important; }
    
    /* Custom scrollbar for better aesthetics */
    ::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }
    
    ::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 4px;
    }
    
    ::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 4px;
    }
    
    ::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
    
    /* Smooth transitions for all interactive elements */
    button, input, details {
        transition: all 0.2s ease-in-out;
    }
    
    /* Custom focus styles */
    button:focus, input:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    
    /* Hover effects for cards */
    .bg-white:hover {
        transform: translateY(-2px);
    }
    
    /* Loading animation enhancement */
    @keyframes pulse-bg {
        0%, 100% {
            background-color: rgba(59, 130, 246, 0.05);
        }
        50% {
            background-color: rgba(59, 130, 246, 0.1);
        }
    }
    
    .animate-pulse-bg {
        animation: pulse-bg 2s ease-in-out infinite;
    }
</style>


</div>

