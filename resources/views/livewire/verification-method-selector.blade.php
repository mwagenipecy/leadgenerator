<div>
{{-- resources/views/livewire/verification-method-selector.blade.php --}}
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="text-2xl font-bold font-poppins text-black">
                    Lead<span class="text-brand-red">Generator</span>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-500">Welcome, {{ auth()->user()->first_name??"N/A" }}</span>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-sm text-brand-red hover:text-red-700 transition-colors">
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
                    <div class="w-8 h-8 bg-brand-red rounded-full flex items-center justify-center mr-2">
                        <span class="text-white font-medium">2</span>
                    </div>
                    <span class="font-medium">Choose Verification</span>
                </div>
                <div class="w-16 h-0.5 bg-gray-300"></div>
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center mr-2">
                        <span class="text-gray-600 font-medium">3</span>
                    </div>
                    <span>Complete</span>
                </div>
            </div>
        </div>

        <!-- Header Section -->
        <div class="text-center mb-10">
            <div class="mx-auto w-20 h-20 bg-brand-red/10 rounded-2xl flex items-center justify-center mb-6">
                <svg class="w-10 h-10 text-brand-red" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
            </div>
            <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">Choose Verification Method</h1>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                Select how you'd like to verify your identity with NIDA. Choose the method that works best for you.
            </p>
        </div>

        <!-- Device Detection Notice -->
        <div class="mb-8 p-4 bg-blue-50 border border-blue-200 rounded-xl">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-blue-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                    <p class="text-sm font-medium text-blue-800">Device Detected</p>
                    <p class="text-sm text-blue-600">
                        {{ ucfirst($deviceType) }} device detected - 
                        {{ $deviceType === 'mobile' ? 'Phone camera is recommended' : 'QR code method is recommended' }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Verification Methods -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="p-6 sm:p-8">
                <div class="grid lg:grid-cols-3 gap-6">
                    
                    <!-- Phone Photo Capture -->
                    <div 
                        wire:click="selectMethod('phone_photo')" 
                        class="verification-method group cursor-pointer transform transition-all duration-300 hover:scale-105"
                    >
                        <div class="border-2 border-gray-200 rounded-xl p-6 transition-all duration-300 group-hover:border-brand-red group-hover:shadow-lg group-hover:bg-red-50">
                            <div class="text-center">
                                <div class="w-16 h-16 bg-brand-red/10 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:bg-brand-red/20 transition-colors">
                                    <svg class="w-8 h-8 text-brand-red" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">Phone Camera</h3>
                                <p class="text-gray-600 text-sm mb-4">Use your phone to capture ID document or fingerprint photo</p>
                                <div class="bg-green-50 rounded-lg p-3 group-hover:bg-green-100 transition-colors">
                                    <span class="text-xs font-medium text-green-700">✓ Most convenient</span>
                                </div>
                                <div class="mt-4">
                                    <div class="flex items-center justify-center space-x-4 text-xs text-gray-500">
                                        <span>📱 Mobile friendly</span>
                                        <span>⚡ Quick process</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- QR Code Method -->
                    <div 
                        wire:click="selectMethod('qr_code')" 
                        class="verification-method group cursor-pointer transform transition-all duration-300 hover:scale-105"
                    >
                        <div class="border-2 border-gray-200 rounded-xl p-6 transition-all duration-300 group-hover:border-brand-red group-hover:shadow-lg group-hover:bg-red-50">
                            <div class="text-center">
                                <div class="w-16 h-16 bg-brand-red/10 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:bg-brand-red/20 transition-colors">
                                    <svg class="w-8 h-8 text-brand-red" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">QR Code Link</h3>
                                <p class="text-gray-600 text-sm mb-4">Scan QR code to connect your phone for verification</p>
                                <div class="bg-blue-50 rounded-lg p-3 group-hover:bg-blue-100 transition-colors">
                                    <span class="text-xs font-medium text-blue-700">✓ Secure connection</span>
                                </div>
                                <div class="mt-4">
                                    <div class="flex items-center justify-center space-x-4 text-xs text-gray-500">
                                        <span>🖥️ Desktop friendly</span>
                                        <span>🔒 Encrypted</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Questionnaire Method -->
                    <div 
                        wire:click="selectMethod('questionnaire')" 
                        class="verification-method group cursor-pointer transform transition-all duration-300 hover:scale-105"
                    >
                        <div class="border-2 border-gray-200 rounded-xl p-6 transition-all duration-300 group-hover:border-brand-red group-hover:shadow-lg group-hover:bg-red-50">
                            <div class="text-center">
                                <div class="w-16 h-16 bg-brand-red/10 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:bg-brand-red/20 transition-colors">
                                    <svg class="w-8 h-8 text-brand-red" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">Questionnaire</h3>
                                <p class="text-gray-600 text-sm mb-4">Answer security questions based on your NIDA records</p>
                                <div class="bg-yellow-50 rounded-lg p-3 group-hover:bg-yellow-100 transition-colors">
                                    <span class="text-xs font-medium text-yellow-700">✓ Alternative method</span>
                                </div>
                                <div class="mt-4">
                                    <div class="flex items-center justify-center space-x-4 text-xs text-gray-500">
                                        <span>📝 Knowledge based</span>
                                        <span>🔐 Secure</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Method Details -->
                <div class="mt-8 p-6 bg-gray-50 rounded-xl">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">How it works:</h3>
                    <div class="grid md:grid-cols-3 gap-6 text-sm">
                        <div class="text-center">
                            <div class="w-8 h-8 bg-brand-red text-white rounded-full flex items-center justify-center mx-auto mb-2 text-xs font-semibold">1</div>
                            <p class="font-medium text-gray-900">Choose Method</p>
                            <p class="text-gray-600">Select verification option</p>
                        </div>
                        <div class="text-center">
                            <div class="w-8 h-8 bg-brand-red text-white rounded-full flex items-center justify-center mx-auto mb-2 text-xs font-semibold">2</div>
                            <p class="font-medium text-gray-900">Complete Verification</p>
                            <p class="text-gray-600">Follow guided steps</p>
                        </div>
                        <div class="text-center">
                            <div class="w-8 h-8 bg-brand-red text-white rounded-full flex items-center justify-center mx-auto mb-2 text-xs font-semibold">3</div>
                            <p class="font-medium text-gray-900">Access Dashboard</p>
                            <p class="text-gray-600">Start using the platform</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Help Section -->
        <div class="mt-8 bg-white rounded-xl border border-gray-200 p-6">
            <div class="text-center">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Need Help?</h3>
                <p class="text-gray-600 text-sm mb-4">
                    Choose the verification method that you're most comfortable with. All methods are secure and NIDA-approved.
                </p>
                <div class="flex flex-wrap justify-center gap-4 text-sm">
                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-gray-100 text-gray-700">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        NIDA Approved
                    </span>
                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-gray-100 text-gray-700">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        Secure Process
                    </span>
                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-gray-100 text-gray-700">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                        Quick Verification
                    </span>
                </div>
            </div>
        </div>

        <!-- Security Notice -->
        <div class="mt-8 text-center">
            <div class="inline-flex items-center space-x-2 text-sm text-gray-500 bg-white px-4 py-2 rounded-lg border border-gray-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
                <span>All verification data is encrypted and securely processed</span>
            </div>
        </div>
    </main>

    <!-- Loading Overlay -->
    <div wire:loading.flex class="fixed inset-0 bg-gray-900 bg-opacity-50 items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 max-w-sm w-full mx-4">
            <div class="text-center">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-brand-red mx-auto mb-4"></div>
                <p class="text-gray-600">Redirecting to verification...</p>
            </div>
        </div>
    </div>
</div>
</div>
