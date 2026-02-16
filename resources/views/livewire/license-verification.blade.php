<div>
<div class="max-w-4xl mx-auto p-6 bg-white rounded-lg shadow-lg">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-black mb-2">{{ __('verification.tanzania_driving_license') }}</h2>
        <p class="text-gray-700">{{ __('verification.verify_license_description') }}</p>
    </div>

    <!-- Input Form -->
    <div class="bg-gray-50 p-4 rounded-lg mb-6 border border-gray-200">
        <div class="grid grid-cols-1 gap-4 mb-4">
            <div>
                <label for="licenseNumber" class="block text-sm font-medium text-black mb-1">
                    {{ __('verification.driving_license_number') }} *
                </label>
                <input 
                    type="text" 
                    id="licenseNumber"
                    wire:model.live="licenseNumber" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-sidebar-green focus:border-transparent"
                    placeholder="{{ __('verification.enter_license_number_placeholder') }}"
                    maxlength="20"
                    wire:loading.attr="disabled"
                >
                @error('licenseNumber') 
                    <span class="text-sidebar-green text-sm">{{ $message }}</span> 
                @enderror
            </div>
        </div>
        
        <div class="flex gap-3">
            <button 
                wire:click="verifyLicense" 
                class="bg-sidebar-green hover:bg-sidebar-green-light text-white font-medium py-2 px-6 rounded-md transition duration-200 flex items-center"
                wire:loading.attr="disabled"
            >
                <div wire:loading wire:target="verifyLicense" class="animate-spin rounded-full h-4 w-4 border-b-2 border-white mr-2"></div>
                <span wire:loading.remove wire:target="verifyLicense">{{ __('verification.verify_license_button') }}</span>
                <span wire:loading wire:target="verifyLicense">{{ __('verification.verifying') }}</span>
            </button>
            
            <button
                wire:click="resetForm"
                class="bg-black hover:bg-gray-800 text-white font-medium py-2 px-4 rounded-md transition duration-200"
            >
                {{ __('verification.reset') }}
            </button>
        </div>

        @if($verificationResult || $error || $rawResponse)
            <div class="flex gap-3 mt-4">
                <button
                    wire:click="clearResults"
                    class="bg-black hover:bg-gray-800 text-white font-medium py-2 px-4 rounded-md transition duration-200"
                >
                    {{ __('verification.clear_results') }}
                </button>
            </div>
        @endif
    </div>

    <!-- Loading State -->
    <div wire:loading wire:target="verifyLicense" class="mb-6">
        <div class="bg-sidebar-green-50 border border-sidebar-green-200 rounded-lg p-4">
            <div class="flex items-center">
                <div class="animate-spin rounded-full h-5 w-5 border-b-2 border-sidebar-green mr-3"></div>
                <span class="text-sidebar-green-light">{{ __('verification.connecting_to_tra') }}</span>
            </div>
        </div>
    </div>

    <!-- Error Display -->
    @if($error)
        <div class="mb-6 bg-sidebar-green-50 border border-sidebar-green-200 rounded-lg p-4">
            <h3 class="text-lg font-semibold text-sidebar-green-800 mb-2">{{ __('verification.verification_failed') }}</h3>
            <p class="text-sidebar-green-light">{{ $error }}</p>
        </div>
    @endif

    <!-- Success Response -->
    @if($showResult && $verificationResult)
        <div class="mb-6 bg-white border border-gray-200 rounded-lg p-4">
            <div class="flex items-center mb-4 pb-3 border-b border-gray-200">
                <div class="w-3 h-3 bg-sidebar-green rounded-full mr-3"></div>
                <h3 class="text-lg font-semibold text-black">{{ __('verification.license_verification_successful') }}</h3>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Driver Information -->
                <div class="bg-white p-4 rounded-lg border-2 border-sidebar-green-100">
                    <h4 class="font-semibold text-black mb-3 text-sm uppercase tracking-wide pb-2 border-b border-sidebar-green-200">{{ __('verification.driver_information') }}</h4>
                    
                    <!-- Driver Photo -->
                    <div class="mb-4">
                        @if(isset($verificationResult['driver_picture']) && !empty($verificationResult['driver_picture']))
                            <img 
                                src="data:image/jpeg;base64,{{ $verificationResult['driver_picture'] }}"
                                alt="{{ __('verification.driver_photo') }}"
                                class="w-32 h-32 object-cover rounded-md border border-gray-300 mx-auto"
                            >
                        @else
                            <div class="w-32 h-32 bg-gray-200 rounded-md flex items-center justify-center mx-auto border border-gray-300">
                                <span class="text-gray-500 text-sm">{{ __('verification.no_photo') }}</span>
                            </div>
                        @endif
                    </div>

                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between py-1">
                            <span class="text-gray-700">{{ __('verification.full_name') }}:</span>
                            <span class="font-medium text-black">{{ $verificationResult['driver_name'] ?? __('verification.n_a') }}</span>
                        </div>
                        <div class="flex justify-between py-1">
                            <span class="text-gray-700">{{ __('verification.age') }}:</span>
                            <span class="font-medium text-black">{{ $verificationResult['driver_age'] ?? __('verification.n_a') }} {{ __('verification.years') }}</span>
                        </div>
                    </div>
                </div>

                <!-- License Details -->
                <div class="bg-white p-4 rounded-lg border-2 border-sidebar-green-100">
                    <h4 class="font-semibold text-black mb-3 text-sm uppercase tracking-wide pb-2 border-b border-sidebar-green-200">{{ __('verification.license_details') }}</h4>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between py-1">
                            <span class="text-gray-700">{{ __('verification.license_number') }}:</span>
                            <span class="font-medium text-black">{{ $verificationResult['license_no'] ?? __('verification.n_a') }}</span>
                        </div>
                        <div class="flex justify-between py-1">
                            <span class="text-gray-700">{{ __('verification.status') }}:</span>
                            <span class="font-medium {{ $verificationResult['is_expired'] ? 'text-sidebar-green' : 'text-black' }}">
                                {{ $verificationResult['is_expired'] ? __('verification.expired') : __('verification.valid') }}
                            </span>
                        </div>
                        <div class="flex justify-between py-1">
                            <span class="text-gray-700">{{ __('verification.issue_date') }}:</span>
                            <span class="font-medium text-black">{{ $verificationResult['issue_date_formatted'] ?? __('verification.n_a') }}</span>
                        </div>
                        <div class="py-1">
                            <span class="text-gray-700">{{ __('verification.license_categories') }}:</span>
                            <div class="mt-1">
                                @if(isset($verificationResult['license_categories_array']) && count($verificationResult['license_categories_array']) > 0)
                                    @foreach($verificationResult['license_categories_array'] as $category)
                                        <span class="inline-block px-2 py-1 bg-sidebar-green-100 text-sidebar-green-800 rounded text-xs font-medium mr-1 mb-1">
                                            {{ $category }}
                                        </span>
                                    @endforeach
                                @else
                                    <span class="font-medium text-black">{{ __('verification.n_a') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Verification Information -->
                <div class="bg-white p-4 rounded-lg border-2 border-sidebar-green-100 md:col-span-2">
                    <h4 class="font-semibold text-black mb-3 text-sm uppercase tracking-wide pb-2 border-b border-sidebar-green-200">Verification Information</h4>
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
                                class="bg-sidebar-green hover:bg-sidebar-green-light text-white text-sm font-medium py-1 px-3 rounded transition duration-200"
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

    <!-- Raw Response (for debugging) -->
    @if($rawResponse && config('app.debug'))
        <div class="mb-6">
            <details class="bg-gray-50 border border-gray-300 rounded-lg p-4">
                <summary class="cursor-pointer text-black font-medium">{{ __('verification.raw_response_debug_mode') }}</summary>
                <pre class="mt-3 text-xs text-gray-700 whitespace-pre-wrap overflow-x-auto">{{ $rawResponse }}</pre>
            </details>
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