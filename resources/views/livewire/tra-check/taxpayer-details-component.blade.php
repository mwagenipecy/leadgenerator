<div>
<div class="max-w-4xl mx-auto p-6 bg-white rounded-lg shadow-lg">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-2">TRA Taxpayer Details Lookup</h2>
        <p class="text-gray-600">Enter taxpayer information to retrieve details from TRA</p>
    </div>

    <!-- Input Form -->
    <div class="bg-gray-50 p-4 rounded-lg mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label for="taxpayerNumber" class="block text-sm font-medium text-gray-700 mb-1">
                    Taxpayer Number *
                </label>
                <input 
                    type="text" 
                    id="taxpayerNumber"
                    wire:model="taxpayerNumber" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="Enter taxpayer number"
                    :disabled="$wire.isLoading"
                >
                @error('taxpayerNumber') 
                    <span class="text-red-500 text-sm">{{ $message }}</span> 
                @enderror
            </div>
            
            
        </div>
        
        <div class="flex gap-3">
            <button 
                wire:click="getTaxpayerDetails" 
                class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-md transition duration-200 flex items-center"
                :disabled="$wire.isLoading"
            >
                <div wire:loading wire:target="getTaxpayerDetails" class="animate-spin rounded-full h-4 w-4 border-b-2 border-white mr-2"></div>
                <span wire:loading.remove wire:target="getTaxpayerDetails">Search Taxpayer</span>
                <span wire:loading wire:target="getTaxpayerDetails">Searching...</span>
            </button>
            
            @if($response || $error || $rawResponse)
                <button 
                    wire:click="clearResults" 
                    class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded-md transition duration-200"
                >
                    Clear Results
                </button>
            @endif
        </div>
    </div>

    <!-- Loading State -->
    <div wire:loading wire:target="getTaxpayerDetails" class="mb-6">
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex items-center">
                <div class="animate-spin rounded-full h-5 w-5 border-b-2 border-blue-600 mr-3"></div>
                <span class="text-blue-700">Sending request to TRA service...</span>
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
        <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4">
            <h3 class="text-lg font-semibold text-green-800 mb-4">Taxpayer Details Retrieved Successfully</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Personal Information -->
                <div class="bg-white p-4 rounded-lg border">
                    <h4 class="font-semibold text-gray-800 mb-3 text-sm uppercase tracking-wide">Personal Information</h4>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Taxpayer ID:</span>
                            <span class="font-medium">{{ $response['taxpayerId'] }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Full Name:</span>
                            <span class="font-medium">{{ trim($response['firstName'] . ' ' . $response['middleName'] . ' ' . $response['lastName']) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Date of Birth:</span>
                            <span class="font-medium">{{ $response['dateOfBirth'] ? date('Y-m-d', strtotime($response['dateOfBirth'])) : 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Gender:</span>
                            <span class="font-medium">{{ $response['gender'] == 'M' ? 'Male' : ($response['gender'] == 'F' ? 'Female' : 'N/A') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Is Person:</span>
                            <span class="font-medium">{{ $response['isPerson'] == 'true' ? 'Yes' : 'No' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Registration Date:</span>
                            <span class="font-medium">{{ $response['dateOfRegistration'] ? date('Y-m-d', strtotime($response['dateOfRegistration'])) : 'N/A' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="bg-white p-4 rounded-lg border">
                    <h4 class="font-semibold text-gray-800 mb-3 text-sm uppercase tracking-wide">Contact Information</h4>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Mobile:</span>
                            <span class="font-medium">{{ $response['mobile'] ?: 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Tel 1:</span>
                            <span class="font-medium">{{ $response['tel1'] ?: 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Tel 2:</span>
                            <span class="font-medium">{{ $response['tel2'] ?: 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Email:</span>
                            <span class="font-medium">{{ $response['email'] ?: 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Fax:</span>
                            <span class="font-medium">{{ $response['fax'] ?: 'N/A' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Address Information -->
                <div class="bg-white p-4 rounded-lg border">
                    <h4 class="font-semibold text-gray-800 mb-3 text-sm uppercase tracking-wide">Address Information</h4>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Region:</span>
                            <span class="font-medium">{{ $response['region'] ?: 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">District:</span>
                            <span class="font-medium">{{ $response['district'] ?: 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Street:</span>
                            <span class="font-medium">{{ $response['street'] ?: 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Plot Number:</span>
                            <span class="font-medium">{{ $response['plotNumber'] ?: 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Block Number:</span>
                            <span class="font-medium">{{ $response['blockNumber'] ?: 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Postal Address:</span>
                            <span class="font-medium">{{ $response['postalAddress'] ?: 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Postal City:</span>
                            <span class="font-medium">{{ $response['postalCity'] ?: 'N/A' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Business Information -->
                <div class="bg-white p-4 rounded-lg border">
                    <h4 class="font-semibold text-gray-800 mb-3 text-sm uppercase tracking-wide">Business Information</h4>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Taxpayer Name:</span>
                            <span class="font-medium">{{ $response['taxpayerName'] ?: 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Number of Employees:</span>
                            <span class="font-medium">{{ $response['numberOfEmployees'] }}</span>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    @endif

    <!-- Raw Response (for debugging) -->
    @if($rawResponse && config('app.debug'))
        <div class="mb-6">
            <details class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                <summary class="cursor-pointer text-gray-700 font-medium">Raw Response (Debug Mode)</summary>
                <pre class="mt-3 text-xs text-gray-600 whitespace-pre-wrap overflow-x-auto">{{ $rawResponse }}</pre>
            </details>
        </div>
    @endif
</div>

<style>
    [x-cloak] { display: none !important; }
</style>

</div>
