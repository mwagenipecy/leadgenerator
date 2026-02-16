<div>
<div class="max-w-4xl mx-auto p-6 bg-white rounded-lg shadow-lg">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-black mb-2">{{ __('verification.tra_taxpayer_lookup') }}</h2>
        <p class="text-gray-700">{{ __('verification.enter_taxpayer_info') }}</p>
    </div>

    <!-- Input Form -->
    <div class="bg-gray-50 p-4 rounded-lg mb-6 border border-gray-200">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label for="taxpayerNumber" class="block text-sm font-medium text-black mb-1">
                    {{ __('verification.taxpayer_number') }} *
                </label>
                <input 
                    type="text" 
                    id="taxpayerNumber"
                    wire:model="taxpayerNumber" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-sidebar-green focus:border-transparent"
                    placeholder="{{ __('verification.enter_taxpayer_number') }}"
                    :disabled="$wire.isLoading"
                >
                @error('taxpayerNumber') 
                    <span class="text-sidebar-green text-sm">{{ $message }}</span> 
                @enderror
            </div>
        </div>
        
        <div class="flex gap-3">
            <button 
                wire:click="getTaxpayerDetails" 
                class="bg-sidebar-green hover:bg-sidebar-green-light text-white font-medium py-2 px-6 rounded-md transition duration-200 flex items-center"
                :disabled="$wire.isLoading"
            >
                <div wire:loading wire:target="getTaxpayerDetails" class="animate-spin rounded-full h-4 w-4 border-b-2 border-white mr-2"></div>
                <span wire:loading.remove wire:target="getTaxpayerDetails">{{ __('verification.search_taxpayer') }}</span>
                <span wire:loading wire:target="getTaxpayerDetails">{{ __('verification.searching') }}</span>
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
    <div wire:loading wire:target="getTaxpayerDetails" class="mb-6">
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
                <h3 class="text-lg font-semibold text-black">{{ __('verification.taxpayer_details_retrieved') }}</h3>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Personal Information -->
                <div class="bg-white p-4 rounded-lg border-2 border-sidebar-green-100">
                    <h4 class="font-semibold text-black mb-3 text-sm uppercase tracking-wide pb-2 border-b border-sidebar-green-200">{{ __('verification.personal_information') }}</h4>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between py-1">
                            <span class="text-gray-700">{{ __('verification.taxpayer_id') }}:</span>
                            <span class="font-medium text-black">{{ $response['taxpayerId'] }}</span>
                        </div>
                        <div class="flex justify-between py-1">
                            <span class="text-gray-700">{{ __('verification.full_name') }}:</span>
                            <span class="font-medium text-black">{{ trim($response['firstName'] . ' ' . $response['middleName'] . ' ' . $response['lastName']) }}</span>
                        </div>
                        <div class="flex justify-between py-1">
                            <span class="text-gray-700">{{ __('verification.date_of_birth') }}:</span>
                            <span class="font-medium text-black">{{ $response['dateOfBirth'] ? date('Y-m-d', strtotime($response['dateOfBirth'])) : __('verification.n_a') }}</span>
                        </div>
                        <div class="flex justify-between py-1">
                            <span class="text-gray-700">{{ __('verification.gender') }}:</span>
                            <span class="font-medium text-black">{{ $response['gender'] == 'M' ? __('verification.male') : ($response['gender'] == 'F' ? __('verification.female') : __('verification.n_a')) }}</span>
                        </div>
                        <div class="flex justify-between py-1">
                            <span class="text-gray-700">{{ __('verification.is_person') }}:</span>
                            <span class="font-medium text-black">{{ $response['isPerson'] == 'true' ? __('verification.yes') : __('verification.no') }}</span>
                        </div>
                        <div class="flex justify-between py-1">
                            <span class="text-gray-700">{{ __('verification.registration_date') }}:</span>
                            <span class="font-medium text-black">{{ $response['dateOfRegistration'] ? date('Y-m-d', strtotime($response['dateOfRegistration'])) : __('verification.n_a') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="bg-white p-4 rounded-lg border-2 border-sidebar-green-100">
                    <h4 class="font-semibold text-black mb-3 text-sm uppercase tracking-wide pb-2 border-b border-sidebar-green-200">{{ __('verification.contact_information') }}</h4>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between py-1">
                            <span class="text-gray-700">{{ __('verification.mobile') }}:</span>
                            <span class="font-medium text-black">{{ $response['mobile'] ?: __('verification.n_a') }}</span>
                        </div>
                        <div class="flex justify-between py-1">
                            <span class="text-gray-700">{{ __('verification.tel_1') }}:</span>
                            <span class="font-medium text-black">{{ $response['tel1'] ?: __('verification.n_a') }}</span>
                        </div>
                        <div class="flex justify-between py-1">
                            <span class="text-gray-700">{{ __('verification.tel_2') }}:</span>
                            <span class="font-medium text-black">{{ $response['tel2'] ?: __('verification.n_a') }}</span>
                        </div>
                        <div class="flex justify-between py-1">
                            <span class="text-gray-700">{{ __('verification.email') }}:</span>
                            <span class="font-medium text-black">{{ $response['email'] ?: __('verification.n_a') }}</span>
                        </div>
                        <div class="flex justify-between py-1">
                            <span class="text-gray-700">{{ __('verification.fax') }}:</span>
                            <span class="font-medium text-black">{{ $response['fax'] ?: __('verification.n_a') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Address Information -->
                <div class="bg-white p-4 rounded-lg border-2 border-sidebar-green-100">
                    <h4 class="font-semibold text-black mb-3 text-sm uppercase tracking-wide pb-2 border-b border-sidebar-green-200">{{ __('verification.address_information') }}</h4>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between py-1">
                            <span class="text-gray-700">{{ __('verification.region') }}:</span>
                            <span class="font-medium text-black">{{ $response['region'] ?: __('verification.n_a') }}</span>
                        </div>
                        <div class="flex justify-between py-1">
                            <span class="text-gray-700">{{ __('verification.district') }}:</span>
                            <span class="font-medium text-black">{{ $response['district'] ?: __('verification.n_a') }}</span>
                        </div>
                        <div class="flex justify-between py-1">
                            <span class="text-gray-700">{{ __('verification.street') }}:</span>
                            <span class="font-medium text-black">{{ $response['street'] ?: __('verification.n_a') }}</span>
                        </div>
                        <div class="flex justify-between py-1">
                            <span class="text-gray-700">Plot Number:</span>
                            <span class="font-medium text-black">{{ $response['plotNumber'] ?: 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between py-1">
                            <span class="text-gray-700">Block Number:</span>
                            <span class="font-medium text-black">{{ $response['blockNumber'] ?: 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between py-1">
                            <span class="text-gray-700">Postal Address:</span>
                            <span class="font-medium text-black">{{ $response['postalAddress'] ?: 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between py-1">
                            <span class="text-gray-700">Postal City:</span>
                            <span class="font-medium text-black">{{ $response['postalCity'] ?: 'N/A' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Business Information -->
                <div class="bg-white p-4 rounded-lg border-2 border-sidebar-green-100">
                    <h4 class="font-semibold text-black mb-3 text-sm uppercase tracking-wide pb-2 border-b border-sidebar-green-200">Business Information</h4>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between py-1">
                            <span class="text-gray-700">Taxpayer Name:</span>
                            <span class="font-medium text-black">{{ $response['taxpayerName'] ?: 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between py-1">
                            <span class="text-gray-700">Number of Employees:</span>
                            <span class="font-medium text-black">{{ $response['numberOfEmployees'] }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Raw Response (for debugging) -->
   
</div>

<style>
    [x-cloak] { display: none !important; }
</style>
</div>