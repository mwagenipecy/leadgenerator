<div>
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="text-2xl font-bold font-poppins text-black">
                    Lead<span class="text-sidebar-green">Generator</span>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-500">Welcome, {{ auth()->user()->first_name?? '' }}</span>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-sm text-sidebar-green hover:text-sidebar-green-light transition-colors">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        
        <!-- Progress Indicator -->
        <div class="mb-8">
            <div class="flex items-center justify-center space-x-4 text-sm text-gray-600">
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center mr-2">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <span>Account Created</span>
                </div>
                <div class="w-16 h-0.5 bg-gray-300"></div>
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center mr-2">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <span>Method Selected</span>
                </div>
                <div class="w-16 h-0.5 bg-gray-300"></div>
                <div class="flex items-center">
                    <div class="w-8 h-8 {{ $isVerified ? 'bg-green-500' : 'bg-sidebar-green' }} rounded-full flex items-center justify-center mr-2">
                        @if($isVerified)
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        @else
                            <span class="text-white font-medium">3</span>
                        @endif
                    </div>
                    <span class="{{ $isVerified ? 'text-green-600' : '' }} font-medium">Fingerprint Verification</span>
                </div>
            </div>
        </div>

        <!-- Header Section -->
        <div class="text-center mb-8">
            <div class="mx-auto w-20 h-20 bg-sidebar-green/10 rounded-2xl flex items-center justify-center mb-6">
                <svg class="w-10 h-10 text-sidebar-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4V2a1 1 0 011-1h8a1 1 0 011 1v2m-9 0h10v16a2 2 0 01-2 2H9a2 2 0 01-2-2V6a2 2 0 012-2z"/>
                </svg>
            </div>
            <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">Fingerprint Verification</h1>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                Capture your fingerprint for biometric verification
            </p>
        </div>

        <!-- Verification Content -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="p-6 sm:p-8">
                
                @if($verificationStep === 'complete' && $isVerified)
                    <!-- Verification Complete -->
                    <div class="text-center py-8">
                        <div class="w-20 h-20 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-6">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-green-800 mb-2">Verification Complete!</h2>
                        <p class="text-green-700 mb-6">{{ $successMessage }}</p>
                        <div class="space-y-3">
                            <button 
                                wire:click="goToProfile"
                                class="w-full bg-sidebar-green text-white px-8 py-3 rounded-lg font-semibold hover:bg-sidebar-green-light transition-colors"
                            >
                                Complete Your Profile
                            </button>
                            <button 
                                wire:click="redirectToDashboard"
                                class="w-full bg-gray-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-gray-700 transition-colors"
                            >
                                Go to Dashboard
                            </button>
                        </div>
                    </div>
                @else
                    <!-- Fingerprint Capture -->
                    @if($verificationStep === 'capture')
                        <div class="max-w-lg mx-auto">
                            <div class="text-center mb-6">
                                <h3 class="text-xl font-semibold text-gray-900 mb-2">
                                    Capture Fingerprint
                                </h3>
                                <p class="text-gray-600">Make sure the image is clear and well-lit</p>
                            </div>

                            <!-- Upload Interface -->
                            <div class="bg-gray-50 rounded-xl p-6">
                                <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center">
                                    <div class="w-16 h-16 bg-sidebar-green/10 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <svg class="w-8 h-8 text-sidebar-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4V2a1 1 0 011-1h8a1 1 0 011 1v2m-9 0h10v16a2 2 0 01-2 2H9a2 2 0 01-2-2V6a2 2 0 012-2z"/>
                                        </svg>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Select or capture fingerprint photo
                                        </label>
                                        <input 
                                            type="file" 
                                            wire:model="photo"
                                            accept="image/*"
                                            capture="environment"
                                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-sidebar-green file:text-white hover:file:bg-sidebar-green-light file:cursor-pointer cursor-pointer"
                                        >
                                    </div>

                                    @if($errorMessage)
                                        <div class="mb-4 p-3 bg-sidebar-green-50 border border-sidebar-green-200 rounded-lg">
                                            <p class="text-sidebar-green-light text-sm">{{ $errorMessage }}</p>
                                        </div>
                                    @endif

                                    <p class="text-xs text-gray-500">
                                        Supported formats: JPG, PNG, HEIC • Max size: 5MB
                                    </p>
                                </div>

                                <!-- Photo Guidelines -->
                                <div class="mt-6 bg-blue-50 rounded-lg p-4">
                                    <h4 class="font-semibold text-blue-900 mb-2">
                                        Fingerprint Guidelines:
                                    </h4>
                                    <ul class="text-sm text-blue-800 space-y-1">
                                        <li>• Place finger flat against the camera</li>
                                        <li>• Ensure good lighting on the fingerprint</li>
                                        <li>• Keep finger steady during capture</li>
                                        <li>• Clean fingerprint area for better quality</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Processing State -->
                    @if($verificationStep === 'processing')
                        <div class="max-w-lg mx-auto text-center">
                            <div class="mb-6">
                                <div class="w-20 h-20 bg-sidebar-green/10 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-10 h-10 text-sidebar-green animate-spin" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-semibold text-gray-900 mb-2">Processing Photo...</h3>
                                <p class="text-gray-600">Analyzing image quality and verifying with NIDA records</p>
                            </div>

                            @if($photoPreview)
                                <div class="relative inline-block mb-6">
                                    <img src="{{ $photoPreview }}" alt="Processing photo" class="w-64 h-48 object-cover rounded-lg border">
                                    <div class="absolute inset-0 bg-sidebar-green/20 rounded-lg flex items-center justify-center">
                                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-white"></div>
                                    </div>
                                </div>
                            @endif

                            <div class="bg-blue-50 rounded-lg p-4">
                                <div class="flex items-center justify-center space-x-2">
                                    <div class="w-2 h-2 bg-blue-500 rounded-full animate-pulse"></div>
                                    <div class="w-2 h-2 bg-blue-500 rounded-full animate-pulse" style="animation-delay: 0.2s"></div>
                                    <div class="w-2 h-2 bg-blue-500 rounded-full animate-pulse" style="animation-delay: 0.4s"></div>
                                </div>
                                <p class="text-blue-700 text-sm mt-2">This may take a few moments...</p>
                            </div>
                        </div>
                    @endif

                    <!-- Error State -->
                    @if($errorMessage && $verificationStep !== 'processing')
                        <div class="max-w-lg mx-auto">
                            <div class="bg-sidebar-green-50 border border-sidebar-green-200 rounded-xl p-6">
                                <div class="flex">
                                    <svg class="w-6 h-6 text-sidebar-green-400 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <div class="flex-1">
                                        <h3 class="text-sm font-medium text-sidebar-green-800">Verification Failed</h3>
                                        <p class="text-sm text-sidebar-green-light mt-1">{{ $errorMessage }}</p>
                                        <div class="mt-4">
                                            <button 
                                                wire:click="retryVerification"
                                                class="bg-sidebar-green text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-sidebar-green-light transition-colors"
                                            >
                                                Try Again
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endif
            </div>
        </div>

        <!-- Security Notice -->
        <div class="mt-8 text-center">
            <div class="inline-flex items-center space-x-2 text-sm text-gray-500 bg-white px-4 py-2 rounded-lg border border-gray-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
                <span>Photos are encrypted and processed securely. Images are deleted after verification.</span>
            </div>
        </div>
    </main>

    <!-- Loading Overlay -->
    <div wire:loading.flex class="fixed inset-0 bg-gray-900 bg-opacity-50 items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 max-w-sm w-full mx-4">
            <div class="text-center">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-sidebar-green mx-auto mb-4"></div>
                <p class="text-gray-600">Processing...</p>
            </div>
        </div>
    </div>
</div>

</div>
