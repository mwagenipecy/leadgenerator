<div>
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100">
    <!-- Header -->
    <header class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16 md:h-20">
                <!-- Logo -->
                <div class="flex-shrink-0">
                    <img src="{{ asset('logo/logoOnWhitebg.png') }}" alt="Logo" class="h-10 md:h-12 w-auto">
                </div>
                
                <!-- Desktop Navigation -->
                <div class="hidden md:flex items-center space-x-6">
                    <span class="text-sm text-gray-600">
                        Welcome, <span class="font-semibold text-gray-900">{{ auth()->user()->first_name ?? "User" }}</span>
                    </span>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-brand-green hover:text-white hover:bg-brand-green rounded-lg border border-brand-green transition-all duration-300">
                            Logout
                        </button>
                    </form>
                </div>

                <!-- Mobile Menu Button -->
                <div class="md:hidden">
                    <button onclick="toggleMobileMenu()" class="p-2 rounded-lg text-gray-600 hover:bg-gray-100">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Mobile Menu -->
            <div id="mobileMenu" class="hidden md:hidden pb-4 border-t border-gray-200 mt-2">
                <div class="pt-4 space-y-3">
                    <div class="text-sm text-gray-600 px-2">
                        Welcome, <span class="font-semibold text-gray-900">{{ auth()->user()->first_name ?? "User" }}</span>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" class="inline w-full">
                        @csrf
                        <button type="submit" class="w-full px-4 py-2 text-sm font-medium text-brand-green hover:text-white hover:bg-brand-green rounded-lg border border-brand-green transition-all duration-300">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-6xl mx-auto py-6 md:py-12 px-4 sm:px-6 lg:px-8">
        
        <!-- Progress Indicator -->
        <div class="mb-8 md:mb-12">
            <!-- Mobile Progress (Simplified) -->
            <div class="md:hidden">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-medium text-brand-green">Step 2 of 3</span>
                    <span class="text-xs text-gray-500">66% Complete</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-brand-green h-2 rounded-full transition-all duration-500" style="width: 66%"></div>
                </div>
            </div>

            <!-- Desktop Progress -->
            <div class="hidden md:flex items-center justify-center space-x-4 text-sm">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center shadow-lg">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <span class="ml-3 text-gray-700">Account Created</span>
                </div>
                <div class="w-20 h-1 bg-brand-green rounded-full"></div>
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-brand-green rounded-full flex items-center justify-center shadow-lg">
                        <span class="text-white font-semibold">2</span>
                    </div>
                    <span class="ml-3 font-semibold text-brand-green">Choose Verification</span>
                </div>
                <div class="w-20 h-1 bg-gray-300 rounded-full"></div>
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center">
                        <span class="text-gray-600 font-semibold">3</span>
                    </div>
                    <span class="ml-3 text-gray-500">Complete</span>
                </div>
            </div>
        </div>

        <!-- Header Section -->
        <div class="text-center mb-8 md:mb-12">
            <div class="mx-auto w-16 h-16 md:w-20 md:h-20 bg-brand-green/10 rounded-2xl flex items-center justify-center mb-4 md:mb-6">
                <svg class="w-8 h-8 md:w-10 md:h-10 text-brand-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
            </div>
            <h1 class="text-2xl md:text-3xl lg:text-4xl font-bold text-gray-900 mb-3 md:mb-4 px-4">Choose Verification Method</h1>
            <p class="text-base md:text-lg text-gray-600 max-w-2xl mx-auto px-4">
                Select how you'd like to verify your identity with NIDA. Choose the method that works best for you.
            </p>
        </div>

        <!-- Device Detection Notice -->
        <div class="mb-6 md:mb-8 p-4 bg-blue-50 border border-blue-200 rounded-xl">
            <div class="flex items-start md:items-center">
                <svg class="w-5 h-5 text-blue-500 mr-3 flex-shrink-0 mt-0.5 md:mt-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div class="flex-1">
                    <p class="text-sm font-semibold text-blue-800 mb-1">Device Detected</p>
                    <p class="text-xs md:text-sm text-blue-600">
                        {{ ucfirst($deviceType) }} device detected - 
                        {{ $deviceType === 'mobile' ? 'Phone camera is recommended' : 'QR code method is recommended' }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Verification Methods -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
            <div class="p-4 sm:p-6 md:p-8">
                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
                    
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
                                        <span class="flex items-center">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                            </svg>
                                            Mobile friendly
                                        </span>
                                        <span class="flex items-center">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                            </svg>
                                            Quick process
                                        </span>
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
                        <div class="border-2 border-gray-200 rounded-xl p-6 transition-all duration-300 group-hover:border-brand-green group-hover:shadow-lg group-hover:bg-green-50">
                            <div class="text-center">
                                <div class="w-16 h-16 bg-brand-green/10 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:bg-brand-green/20 transition-colors">
                                    <svg class="w-8 h-8 text-brand-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                        <span class="flex items-center">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                            </svg>
                                            Desktop friendly
                                        </span>
                                        <span class="flex items-center">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                            </svg>
                                            Encrypted
                                        </span>
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
                        <div class="border-2 border-gray-200 rounded-xl p-6 transition-all duration-300 group-hover:border-brand-green group-hover:shadow-lg group-hover:bg-green-50">
                            <div class="text-center">
                                <div class="w-16 h-16 bg-brand-green/10 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:bg-brand-green/20 transition-colors">
                                    <svg class="w-8 h-8 text-brand-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                        <span class="flex items-center">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                            Knowledge based
                                        </span>
                                        <span class="flex items-center">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                            </svg>
                                            Secure
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Method Details -->
                <div class="mt-6 md:mt-8 p-4 md:p-6 bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl">
                    <h3 class="text-base md:text-lg font-semibold text-gray-900 mb-4 md:mb-6 text-center">How it works:</h3>
                    <div class="grid grid-cols-3 gap-3 md:gap-6 text-xs md:text-sm">
                        <div class="text-center">
                            <div class="w-8 h-8 md:w-10 md:h-10 bg-brand-green text-white rounded-full flex items-center justify-center mx-auto mb-2 text-sm md:text-base font-semibold shadow-lg">1</div>
                            <p class="font-semibold text-gray-900 mb-1">Choose Method</p>
                            <p class="text-gray-600 hidden md:block">Select verification option</p>
                        </div>
                        <div class="text-center">
                            <div class="w-8 h-8 md:w-10 md:h-10 bg-brand-green text-white rounded-full flex items-center justify-center mx-auto mb-2 text-sm md:text-base font-semibold shadow-lg">2</div>
                            <p class="font-semibold text-gray-900 mb-1">Complete Verification</p>
                            <p class="text-gray-600 hidden md:block">Follow guided steps</p>
                        </div>
                        <div class="text-center">
                            <div class="w-8 h-8 md:w-10 md:h-10 bg-brand-green text-white rounded-full flex items-center justify-center mx-auto mb-2 text-sm md:text-base font-semibold shadow-lg">3</div>
                            <p class="font-semibold text-gray-900 mb-1">Access Dashboard</p>
                            <p class="text-gray-600 hidden md:block">Start using the platform</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Help Section -->
        <div class="mt-6 md:mt-8 bg-white rounded-xl border border-gray-200 p-4 md:p-6 shadow-lg">
            <div class="text-center">
                <h3 class="text-base md:text-lg font-semibold text-gray-900 mb-2">Need Help?</h3>
                <p class="text-gray-600 text-sm md:text-base mb-4">
                    Choose the verification method that you're most comfortable with. All methods are secure and NIDA-approved.
                </p>
                <div class="flex flex-wrap justify-center gap-2 md:gap-4 text-xs md:text-sm">
                    <span class="inline-flex items-center px-3 py-1.5 rounded-full bg-green-50 text-brand-green border border-green-200">
                        <svg class="w-3 h-3 md:w-4 md:h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        NIDA Approved
                    </span>
                    <span class="inline-flex items-center px-3 py-1.5 rounded-full bg-green-50 text-brand-green border border-green-200">
                        <svg class="w-3 h-3 md:w-4 md:h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        Secure Process
                    </span>
                    <span class="inline-flex items-center px-3 py-1.5 rounded-full bg-green-50 text-brand-green border border-green-200">
                        <svg class="w-3 h-3 md:w-4 md:h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                        Quick Verification
                    </span>
                </div>
            </div>
        </div>

        <!-- Security Notice -->
        <div class="mt-6 md:mt-8 text-center pb-6">
            <div class="inline-flex items-center space-x-2 text-xs md:text-sm text-gray-500 bg-white px-4 py-3 rounded-lg border border-gray-200 shadow-sm">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
                <span class="text-left">All verification data is encrypted and securely processed</span>
            </div>
        </div>
    </main>

    <!-- Loading Overlay -->
    <div wire:loading.flex class="fixed inset-0 bg-gray-900 bg-opacity-50 items-center justify-center z-50">
        <div class="bg-white rounded-2xl p-6 md:p-8 max-w-sm w-full mx-4 shadow-2xl">
            <div class="text-center">
                <div class="animate-spin rounded-full h-12 w-12 border-4 border-gray-200 border-t-brand-green mx-auto mb-4"></div>
                <p class="text-gray-600 font-medium">Redirecting to verification...</p>
                <p class="text-gray-400 text-sm mt-2">Please wait</p>
            </div>
        </div>
    </div>
</div>

<script>
    function toggleMobileMenu() {
        const menu = document.getElementById('mobileMenu');
        menu.classList.toggle('hidden');
    }

    // Close mobile menu when clicking outside
    document.addEventListener('click', function(event) {
        const menu = document.getElementById('mobileMenu');
        const button = event.target.closest('button[onclick="toggleMobileMenu()"]');
        
        if (!menu.contains(event.target) && !button && !menu.classList.contains('hidden')) {
            menu.classList.add('hidden');
        }
    });
</script>
</div>
