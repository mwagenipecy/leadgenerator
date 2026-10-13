<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('auth.verify_email_title') }} - {{ config('app.name') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('landing/applicationIcon.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Alpine.js for language switcher -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
        .language-dropdown { z-index: 9999 !important; }
    </style>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'brand-red': '#C40F11',
                        'brand-dark-red': '#A00E11',
                        'brand-green': '#C40F11',
                        'brand-green-light': '#999999',
                        'brand-gray': '#999999',
                    },
                    fontFamily: {
                        'inter': ['Inter', 'sans-serif'],
                        'poppins': ['Poppins', 'sans-serif'],
                    }
                }
            }
        }
    </script>
</head>
<body class="font-inter">
@php
    $resolveAuthImageUrl = function (?string $value): string {
        if (empty($value)) {
            return asset('landing/register-login.jpg');
        }

        if (str_starts_with($value, 'http://') || str_starts_with($value, 'https://')) {
            return $value;
        }

        if (str_starts_with($value, '/storage/')) {
            return asset(ltrim($value, '/'));
        }

        if (str_starts_with($value, 'storage/')) {
            return asset($value);
        }

        return asset('storage/' . ltrim($value, '/'));
    };

    $authSideImageValue = \App\Models\SystemSetting::getValue('auth_side_image_verify_email_' . app()->getLocale())
        ?: \App\Models\SystemSetting::getValue('auth_side_image_verify_email_en')
        ?: \App\Models\SystemSetting::getValue('auth_side_image_' . app()->getLocale())
        ?: \App\Models\SystemSetting::getValue('auth_side_image_en')
        ?: null;

    $authSideImage = $resolveAuthImageUrl($authSideImageValue);
@endphp
<div class="h-screen flex overflow-hidden">
<!-- Left Side - Welcome Content (Hidden on mobile) -->
  <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden h-screen" style="background-image: url('{{ $authSideImage }}'); background-size: cover; background-position: center; background-repeat: no-repeat;">
            <div class="absolute bottom-0 right-0 z-20 p-6 pr-8 max-w-sm"></div>
        </div>

        <!-- Right Side - Verify Email -->
        <div class="w-full lg:w-1/2 h-screen overflow-y-auto relative">
            <!-- Language Switcher (Top Right) -->
            <div class="absolute top-4 right-4 z-50">
                <x-language-switcher />
            </div>
            <div class="flex items-center justify-center min-h-full p-6 sm:p-8 lg:p-12">
                <div class="w-full max-w-lg py-8">

                <!-- Header -->
                <div class="text-center mb-8">
                    <!-- Desktop Logo -->
                    <div class="hidden lg:flex justify-center mb-6">
                        <img src="{{ asset('landing/redlogo.png') }}" alt="Fanikisha Market place Logo" class="h-16 w-auto">
                    </div>
                    <!-- Mobile Logo -->
                    <div class="lg:hidden mb-6">
                        <div class="flex justify-center">
                            <img src="{{ asset('landing/redlogo.png') }}" alt="Fanikisha Market place Logo" class="h-16 w-auto">
                        </div>
                    </div>

                    <h2 class="text-2xl lg:text-3xl font-bold text-gray-900 mb-3">{{ __('auth.verify_email_title') }}</h2>
                    <p class="text-gray-600">{{ __('auth.verify_email_subtitle') }}</p>
                </div>

                <!-- Verify Email Card -->
                <div class="bg-white rounded-2xl p-8 border border-gray-100">

                    <div class="inline-flex items-center justify-center w-16 h-16 bg-brand-red/10 rounded-full mb-4">
                        <svg class="w-8 h-8 text-brand-red" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>

                    <p class="mb-6 text-sm text-gray-600 leading-relaxed">
                        {{ __('auth.verify_email_description') }}
                    </p>

                    @if (session('status') == 'verification-link-sent')
                        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-sm font-medium text-green-800">{{ __('auth.verification_link_sent') }}</span>
                            </div>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('verification.send') }}" id="resendVerificationForm">
                        @csrf
                        <button
                            type="submit"
                            id="resendVerificationBtn"
                            class="w-full bg-brand-red text-white py-3 rounded-lg font-semibold hover:bg-brand-dark-red focus:ring-4 focus:ring-brand-red/30 transition-all duration-300 transform hover:scale-[1.02] disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:scale-100"
                        >
                            <span id="resendVerificationBtnText">{{ __('auth.resend_verification_email') }}</span>
                            <span id="resendVerificationBtnLoader" class="hidden">
                                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                {{ __('auth.sending') }}
                            </span>
                        </button>
                    </form>

                    <div class="mt-6 flex items-center justify-center space-x-4 text-sm">
                        <a href="{{ route('profile.show') }}" class="text-gray-600 hover:text-brand-red transition-colors underline">
                            {{ __('auth.edit_profile') }}
                        </a>
                        <span class="text-gray-300">|</span>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-gray-600 hover:text-brand-red transition-colors underline">
                                {{ __('common.logout') }}
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Security Notice -->
                <div class="mt-6 text-center">
                    <div class="flex items-center justify-center space-x-2 text-sm text-gray-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                        <span>Your data is protected with 256-bit SSL encryption</span>
                    </div>
                </div>

                <!-- Powered By -->
                <div class="mt-6 text-center">
                    <p class="text-xs text-gray-400">Powered by CreditInfo</p>
                </div>
                </div>
            </div>
        </div>

    </div>

<script>
// Prevent double click on resend verification form
document.addEventListener('DOMContentLoaded', function() {
    const resendVerificationForm = document.getElementById('resendVerificationForm');
    const resendVerificationBtn = document.getElementById('resendVerificationBtn');
    const resendVerificationBtnText = document.getElementById('resendVerificationBtnText');
    const resendVerificationBtnLoader = document.getElementById('resendVerificationBtnLoader');

    if (resendVerificationForm && resendVerificationBtn) {
        resendVerificationForm.addEventListener('submit', function(e) {
            if (resendVerificationBtn.disabled) {
                e.preventDefault();
                return false;
            }

            resendVerificationBtn.disabled = true;
            resendVerificationBtnText.classList.add('hidden');
            resendVerificationBtnLoader.classList.remove('hidden');
        });
    }
});
</script>
</body>
</html>
