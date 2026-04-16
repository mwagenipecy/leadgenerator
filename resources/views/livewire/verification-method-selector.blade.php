<div>
<style>
    .floating-navbar-shell {
        border-radius: 1.15rem;
        background: linear-gradient(120deg, rgba(255, 255, 255, 0.2), rgba(255, 255, 255, 0.08));
        border: 1px solid rgba(255, 255, 255, 0.4);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        box-shadow: 0 10px 24px rgba(0, 0, 0, 0.09);
        transition: all 0.3s ease;
    }

    .floating-navbar-shell.navbar-scrolled {
        background: linear-gradient(120deg, rgba(255, 255, 255, 0.24), rgba(255, 255, 255, 0.12));
        border-color: rgba(255, 255, 255, 0.48);
        box-shadow: 0 14px 30px rgba(0, 0, 0, 0.12);
    }

    .floating-navbar-shell .nav-scroll-link,
    .floating-navbar-shell #language-switcher-button {
        color: #C40F11;
    }

    .floating-navbar-shell.navbar-scrolled .nav-scroll-link,
    .floating-navbar-shell.navbar-scrolled #language-switcher-button {
        color: #C40F11;
    }
</style>
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100">
    <x-landing.navbar :is-home="false" />

    <!-- Main Content -->
    <main class="max-w-6xl mx-auto pt-24 pb-12 md:pt-28 md:pb-14 px-4 sm:px-6 lg:px-8">
        
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
            <h1 class="text-2xl md:text-3xl lg:text-4xl font-bold text-gray-900 mb-3 md:mb-4 px-4">Verify Your NIDA Details</h1>
            <p class="text-base md:text-lg text-gray-600 max-w-2xl mx-auto px-4">
                For now, verification is completed through the questionnaire only. You can update your NIDA number below before continuing.
            </p>
        </div>

        <!-- Active Method Notice -->
        <div class="mb-6 md:mb-8 p-4 bg-blue-50 border border-blue-200 rounded-xl">
            <div class="flex items-start md:items-center">
                <svg class="w-5 h-5 text-blue-500 mr-3 flex-shrink-0 mt-0.5 md:mt-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div class="flex-1">
                    <p class="text-sm font-semibold text-blue-800 mb-1">Temporary Verification Mode</p>
                    <p class="text-xs md:text-sm text-blue-600">
                        Questionnaire verification is active. Phone camera and QR options are temporarily disabled.
                    </p>
                </div>
            </div>
        </div>

        @if (session()->has('success'))
            <div class="mb-6 rounded-xl border border-green-200 bg-green-50 p-4 text-sm text-green-800">
                {{ session('success') }}
            </div>
        @endif
        @if (session()->has('error'))
            <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                {{ session('error') }}
            </div>
        @endif
        @if (session()->has('info'))
            <div class="mb-6 rounded-xl border border-blue-200 bg-blue-50 p-4 text-sm text-blue-700">
                {{ session('info') }}
            </div>
        @endif

        <!-- Verification Methods -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
            <div class="p-4 sm:p-6 md:p-8">
                <div class="mb-8 rounded-xl border border-gray-200 bg-gray-50 p-5">
                    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">NIDA Number</h3>
                            <p class="text-sm text-gray-600">Your NIDA was pre-filled during registration. Please confirm it is correct before continuing.</p>
                        </div>
                        @if (!$isEditingNida)
                            <button wire:click="enableNidaEdit" type="button" class="rounded-lg border border-brand-green px-4 py-2 text-sm font-semibold text-brand-green transition hover:bg-brand-green hover:text-white">
                                Edit NIDA Number
                            </button>
                        @endif
                    </div>

                    <div class="mt-4">
                        @if ($isEditingNida)
                            <div class="space-y-3">
                                <input type="text" wire:model.defer="nidaNumber" maxlength="20" placeholder="Enter 20-digit NIDA number" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-brand-green focus:outline-none focus:ring-2 focus:ring-brand-green/30 @error('nidaNumber') border-red-400 @enderror">
                                @error('nidaNumber')
                                    <p class="text-xs text-red-600">{{ $message }}</p>
                                @enderror
                                <div class="flex gap-3">
                                    <button wire:click="saveNidaNumber" type="button" class="rounded-lg bg-brand-green px-4 py-2 text-sm font-semibold text-white transition hover:bg-brand-green-light">Save</button>
                                    <button wire:click="cancelNidaEdit" type="button" class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 transition hover:bg-gray-100">Cancel</button>
                                </div>
                            </div>
                        @else
                            <p class="rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-800">
                                {{ $nidaNumber ?: 'No NIDA number provided yet' }}
                            </p>
                        @endif
                    </div>

                    @if (!$isEditingNida)
                        <div class="mt-4 rounded-lg border border-gray-200 bg-white p-4">
                            <p class="text-sm font-medium text-gray-800 mb-3">Is this NIDA number correct?</p>
                            <div class="flex flex-wrap gap-3">
                                <button wire:click="confirmNidaCorrect" type="button" class="rounded-lg px-4 py-2 text-sm font-semibold transition {{ $nidaConfirmed === true ? 'bg-green-600 text-white' : 'border border-green-600 text-green-700 hover:bg-green-50' }}">
                                    Yes, correct
                                </button>
                                <button wire:click="confirmNidaIncorrect" type="button" class="rounded-lg px-4 py-2 text-sm font-semibold transition {{ $nidaConfirmed === false ? 'bg-red-600 text-white' : 'border border-red-600 text-red-700 hover:bg-red-50' }}">
                                    No, not correct
                                </button>
                            </div>
                            @if ($nidaConfirmed === true)
                                <p class="mt-2 text-xs text-green-700">NIDA confirmed. You can continue to questionnaire.</p>
                            @elseif ($nidaConfirmed === false)
                                <p class="mt-2 text-xs text-red-700">Please edit and save your correct NIDA number.</p>
                            @endif
                        </div>
                    @endif
                </div>

                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
                    
                    <!-- Questionnaire (Active) -->
                    <div 
                        wire:click="continueWithQuestionnaire" 
                        class="verification-method group cursor-pointer transform transition-all duration-300 hover:scale-105"
                    >
                        <div class="border-2 border-brand-green rounded-xl p-6 transition-all duration-300 group-hover:shadow-lg group-hover:bg-green-50">
                            <div class="text-center">
                                <div class="w-16 h-16 bg-brand-green/10 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:bg-brand-green/20 transition-colors">
                                    <svg class="w-8 h-8 text-brand-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">Questionnaire Verification</h3>
                                <p class="text-gray-600 text-sm mb-4">Answer security questions based on your NIDA records</p>
                                <div class="bg-green-50 rounded-lg p-3 group-hover:bg-green-100 transition-colors">
                                    <span class="text-xs font-medium text-green-700">Active now</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Phone Method (Temporarily Disabled) -->
                    <div 
                        class="verification-method group cursor-not-allowed opacity-60"
                    >
                        <div class="border-2 border-gray-200 rounded-xl p-6">
                            <div class="text-center">
                                <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8 text-brand-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">Phone Camera</h3>
                                <p class="text-gray-600 text-sm mb-4">Temporarily unavailable</p>
                                <div class="bg-gray-100 rounded-lg p-3">
                                    <span class="text-xs font-medium text-gray-600">Coming soon</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- QR Method (Temporarily Disabled) -->
                    <div 
                        class="verification-method group cursor-not-allowed opacity-60"
                    >
                        <div class="border-2 border-gray-200 rounded-xl p-6">
                            <div class="text-center">
                                <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8 text-brand-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">QR Code Link</h3>
                                <p class="text-gray-600 text-sm mb-4">Temporarily unavailable</p>
                                <div class="bg-gray-100 rounded-lg p-3">
                                    <span class="text-xs font-medium text-gray-600">Coming soon</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-8">
                    <button wire:click="continueWithQuestionnaire" type="button" class="w-full rounded-xl bg-brand-green px-5 py-3 text-sm font-semibold text-white transition hover:bg-brand-green-light">
                        Continue With Questionnaire
                    </button>
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

    <div class="mt-4 md:mt-6">
        <x-landing.footer />
    </div>

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
        if (!menu) return;
        menu.classList.toggle('hidden');
    }

    const handleNavbarScroll = () => {
        const navbar = document.getElementById('mainNavbar');
        const navbarShell = navbar ? navbar.querySelector('.floating-navbar-shell') : null;
        if (!navbarShell) return;

        if (window.scrollY > 20) {
            navbarShell.classList.add('navbar-scrolled');
        } else {
            navbarShell.classList.remove('navbar-scrolled');
        }
    };

    window.addEventListener('scroll', handleNavbarScroll);
    document.addEventListener('DOMContentLoaded', handleNavbarScroll);

    // Close mobile menu when clicking outside
    document.addEventListener('click', function(event) {
        const menu = document.getElementById('mobileMenu');
        if (!menu) return;
        const button = event.target.closest('button[onclick="toggleMobileMenu()"]');
        
        if (!menu.contains(event.target) && !button && !menu.classList.contains('hidden')) {
            menu.classList.add('hidden');
        }
    });
</script>
</div>
