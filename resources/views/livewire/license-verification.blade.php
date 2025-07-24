<div>
<div class="max-w-4xl mx-auto p-6 bg-white rounded-lg shadow-lg">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-black mb-2">Tanzania Driving License Verification</h2>
        <p class="text-gray-700">Verify driving license through the official TRA database</p>
    </div>

    <!-- Input Form -->
    <div class="bg-gray-50 p-4 rounded-lg mb-6 border border-gray-200">
        <div class="grid grid-cols-1 gap-4 mb-4">
            <div>
                <label for="licenseNumber" class="block text-sm font-medium text-black mb-1">
                    Driving License Number *
                </label>
                <input 
                    type="text" 
                    id="licenseNumber"
                    wire:model.live="licenseNumber" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
                    placeholder="Enter license number (e.g., 4002014677)"
                    maxlength="20"
                    wire:loading.attr="disabled"
                >
                @error('licenseNumber') 
                    <span class="text-red-600 text-sm">{{ $message }}</span> 
                @enderror
            </div>
        </div>
        
        <div class="flex gap-3">
            <button 
                wire:click="verifyLicense" 
                class="bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-6 rounded-md transition duration-200 flex items-center"
                wire:loading.attr="disabled"
            >
                <div wire:loading wire:target="verifyLicense" class="animate-spin rounded-full h-4 w-4 border-b-2 border-white mr-2"></div>
                <span wire:loading.remove wire:target="verifyLicense">Verify License</span>
                <span wire:loading wire:target="verifyLicense">Verifying...</span>
            </button>
            
            <button 
                wire:click="resetForm" 
                class="bg-black hover:bg-gray-800 text-white font-medium py-2 px-4 rounded-md transition duration-200"
            >
                Reset
            </button>
        </div>
    </div>

    <!-- Loading State -->
    <div wire:loading wire:target="verifyLicense" class="mb-6">
        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
            <div class="flex items-center">
                <div class="animate-spin rounded-full h-5 w-5 border-b-2 border-red-600 mr-3"></div>
                <span class="text-red-700">Connecting to TRA database for license verification...</span>
            </div>
        </div>
    </div>

    <!-- Error Display -->
    @if($error)
        <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
            <h3 class="text-lg font-semibold text-red-800 mb-2">Verification Failed</h3>
            <p class="text-red-700">{{ $error }}</p>
        </div>
    @endif

    <!-- Success Response -->
    @if($showResult && $verificationResult)
        <div class="mb-6 bg-white border border-gray-200 rounded-lg p-4">
            <div class="flex items-center mb-4 pb-3 border-b border-gray-200">
                <div class="w-3 h-3 bg-red-600 rounded-full mr-3"></div>
                <h3 class="text-lg font-semibold text-black">License Verification Successful</h3>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Driver Information -->
                <div class="bg-white p-4 rounded-lg border-2 border-red-100">
                    <h4 class="font-semibold text-black mb-3 text-sm uppercase tracking-wide pb-2 border-b border-red-200">Driver Information</h4>
                    
                    <!-- Driver Photo -->
                    <div class="mb-4">
                        @if(isset($verificationResult['driver_picture']) && !empty($verificationResult['driver_picture']))
                            <img 
                                src="data:image/jpeg;base64,{{ $verificationResult['driver_picture'] }}"
                                alt="Driver Photo"
                                class="w-32 h-32 object-cover rounded-md border border-gray-300 mx-auto"
                            >
                        @else
                            <div class="w-32 h-32 bg-gray-200 rounded-md flex items-center justify-center mx-auto border border-gray-300">
                                <span class="text-gray-500 text-sm">No Photo</span>
                            </div>
                        @endif
                    </div>

                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between py-1">
                            <span class="text-gray-700">Full Name:</span>
                            <span class="font-medium text-black">{{ $verificationResult['driver_name'] ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between py-1">
                            <span class="text-gray-700">Age:</span>
                            <span class="font-medium text-black">{{ $verificationResult['driver_age'] ?? 'N/A' }} years</span>
                        </div>
                    </div>
                </div>

                <!-- License Details -->
                <div class="bg-white p-4 rounded-lg border-2 border-red-100">
                    <h4 class="font-semibold text-black mb-3 text-sm uppercase tracking-wide pb-2 border-b border-red-200">License Details</h4>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between py-1">
                            <span class="text-gray-700">License Number:</span>
                            <span class="font-medium text-black">{{ $verificationResult['license_no'] ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between py-1">
                            <span class="text-gray-700">Status:</span>
                            <span class="font-medium {{ $verificationResult['is_expired'] ? 'text-red-600' : 'text-black' }}">
                                {{ $verificationResult['is_expired'] ? 'Expired' : 'Valid' }}
                            </span>
                        </div>
                        <div class="flex justify-between py-1">
                            <span class="text-gray-700">Issue Date:</span>
                            <span class="font-medium text-black">{{ $verificationResult['issue_date_formatted'] ?? 'N/A' }}</span>
                        </div>
                        <div class="py-1">
                            <span class="text-gray-700">License Categories:</span>
                            <div class="mt-1">
                                @if(isset($verificationResult['license_categories_array']) && count($verificationResult['license_categories_array']) > 0)
                                    @foreach($verificationResult['license_categories_array'] as $category)
                                        <span class="inline-block px-2 py-1 bg-red-100 text-red-800 rounded text-xs font-medium mr-1 mb-1">
                                            {{ $category }}
                                        </span>
                                    @endforeach
                                @else
                                    <span class="font-medium text-black">N/A</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Verification Information -->
                <div class="bg-white p-4 rounded-lg border-2 border-red-100 md:col-span-2">
                    <h4 class="font-semibold text-black mb-3 text-sm uppercase tracking-wide pb-2 border-b border-red-200">Verification Information</h4>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between py-1">
                            <span class="text-gray-700">Verified On:</span>
                            <span class="font-medium text-black">{{ now()->format('F d, Y \a\t g:i A') }}</span>
                        </div>
                        <div class="flex justify-between py-1">
                            <span class="text-gray-700">Data Source:</span>
                            <span class="font-medium text-black">Tanzania Revenue Authority (TRA)</span>
                        </div>
                        <div class="flex gap-3 pt-2">
                            <button
                                wire:click="verifyLicense"
                                class="bg-red-600 hover:bg-red-700 text-white text-sm font-medium py-1 px-3 rounded transition duration-200"
                            >
                                Re-verify
                            </button>
                            <button
                                onclick="window.print()"
                                class="bg-black hover:bg-gray-800 text-white text-sm font-medium py-1 px-3 rounded transition duration-200"
                            >
                                Print
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<style>
    [x-cloak] { display: none !important; }
    
    @media print {
        body * {
            visibility: hidden;
        }
        .print-section, .print-section * {
            visibility: visible;
        }
        .print-section {
            position: absolute;
            left: 0;
            top: 0;
        }
    }
</style>
</div>