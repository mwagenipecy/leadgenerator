<div class="max-w-4xl mx-auto p-6">
    <!-- Header -->
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Tanzania Driving License Verification</h1>
        <p class="text-gray-600">Verify driving license through the official TRA database</p>
    </div>

    <!-- Main Form -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">License Verification</h2>
        
        <form wire:submit.prevent="verifyLicense" class="space-y-4">
            <div>
                <label for="licenseNumber" class="block text-sm font-medium text-gray-700 mb-2">
                    Driving License Number
                </label>
                <input
                    type="text"
                    id="licenseNumber"
                    wire:model.live="licenseNumber"
                    placeholder="Enter license number (e.g., 4002014677)"
                    maxlength="20"
                    class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('licenseNumber') border-red-500 @enderror"
                >
                @error('licenseNumber')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-3">
                <button
                    type="submit"
                    wire:loading.attr="disabled"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-md font-medium disabled:opacity-50 flex items-center"
                >
                    <span wire:loading.remove wire:target="verifyLicense">Verify License</span>
                    <span wire:loading wire:target="verifyLicense">Verifying...</span>
                </button>
                <button
                    type="button"
                    wire:click="resetForm"
                    class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-md font-medium"
                >
                    Reset
                </button>
            </div>
        </form>
    </div>

    <!-- Error Message -->
    @if($error)
        <div class="bg-red-50 border border-red-200 rounded-md p-4 mb-6">
            <h3 class="text-lg font-semibold text-red-800">Verification Failed</h3>
            <p class="text-red-700">{{ $error }}</p>
        </div>
    @endif

    <!-- Results -->
    @if($showResult && $verificationResult)
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-xl font-semibold text-green-800 mb-6">Verification Successful</h3>
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Driver Photo -->
                <div class="lg:col-span-1">
                    <h4 class="text-lg font-medium text-gray-800 mb-3">Driver Information</h4>
                    
                    <div class="mb-4">
                        @if(isset($verificationResult['driver_picture']) && !empty($verificationResult['driver_picture']))
                            <img 
                                src="data:image/jpeg;base64,{{ $verificationResult['driver_picture'] }}"
                                alt="Driver Photo"
                                class="w-48 h-48 object-cover rounded-md border"
                            >
                        @else
                            <div class="w-48 h-48 bg-gray-200 rounded-md flex items-center justify-center">
                                <span class="text-gray-500">No Photo</span>
                            </div>
                        @endif
                    </div>

                    <div class="space-y-2">
                        <p class="text-lg font-semibold text-gray-900">{{ $verificationResult['driver_name'] ?? 'N/A' }}</p>
                        <p class="text-gray-600">Age: {{ $verificationResult['driver_age'] ?? 'N/A' }} years</p>
                    </div>
                </div>

                <!-- License Details -->
                <div class="lg:col-span-2">
                    <h4 class="text-lg font-medium text-gray-800 mb-3">License Details</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-gray-50 p-4 rounded-md">
                            <p class="text-sm text-gray-600">License Number</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $verificationResult['license_no'] ?? 'N/A' }}</p>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-md">
                            <p class="text-sm text-gray-600">Status</p>
                            <p class="text-lg font-semibold {{ $verificationResult['is_expired'] ? 'text-red-600' : 'text-green-600' }}">
                                {{ $verificationResult['is_expired'] ? 'Expired' : 'Valid' }}
                            </p>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-md">
                            <p class="text-sm text-gray-600">Issue Date</p>
                            <p class="text-lg font-semibold text-gray-900">
                                {{ $verificationResult['issue_date_formatted'] ?? 'N/A' }}
                            </p>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-md">
                            <p class="text-sm text-gray-600">License Categories</p>
                            <div class="mt-1">
                                @if(isset($verificationResult['license_categories_array']) && count($verificationResult['license_categories_array']) > 0)
                                    @foreach($verificationResult['license_categories_array'] as $category)
                                        <span class="inline-block px-2 py-1 bg-blue-100 text-blue-800 rounded text-sm font-medium mr-1 mb-1">
                                            {{ $category }}
                                        </span>
                                    @endforeach
                                @else
                                    <span class="text-gray-500">N/A</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="mt-6 pt-4 border-t border-gray-200">
                        <div class="flex justify-between items-center">
                            <p class="text-sm text-gray-600">Verified on {{ now()->format('F d, Y \a\t g:i A') }}</p>
                            <div class="flex gap-3">
                                <button
                                    wire:click="verifyLicense"
                                    class="text-blue-600 hover:text-blue-800 text-sm font-medium"
                                >
                                    Re-verify
                                </button>
                                <button
                                    onclick="window.print()"
                                    class="text-gray-600 hover:text-gray-800 text-sm font-medium"
                                >
                                    Print
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Footer -->
    <div class="mt-8 text-center text-gray-500 text-sm">
        <p>This service is powered by the Tanzania Revenue Authority (TRA)</p>
    </div>
</div>