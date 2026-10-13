<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('auth.two_factor_title') }} - {{ config('app.name') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('landing/applicationIcon.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Alpine.js for language switcher and recovery code toggle -->
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

    $authSideImageValue = \App\Models\SystemSetting::getValue('auth_side_image_two_factor_' . app()->getLocale())
        ?: \App\Models\SystemSetting::getValue('auth_side_image_two_factor_en')
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

        <!-- Right Side - Two-Factor Form -->
        <div class="w-full lg:w-1/2 h-screen overflow-y-auto relative">
            <!-- Language Switcher (Top Right) -->
            <div class="absolute top-4 right-4 z-50">
                <x-language-switcher />
            </div>
            <div class="flex items-center justify-center min-h-full p-6 sm:p-8 lg:p-12">
                <div class="w-full max-w-lg py-8" x-data="{ recovery: false }">

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

                    <h2 class="text-2xl lg:text-3xl font-bold text-gray-900 mb-3">{{ __('auth.two_factor_title') }}</h2>
                    <p class="text-gray-600">{{ __('auth.two_factor_subtitle') }}</p>
                </div>

                <!-- Two-Factor Form -->
                <div class="bg-white rounded-2xl p-8 border border-gray-100">

                    <p class="mb-6 text-sm text-gray-600" x-show="! recovery">
                        {{ __('auth.two_factor_app_description') }}
                    </p>
                    <p class="mb-6 text-sm text-gray-600" x-cloak x-show="recovery">
                        {{ __('auth.two_factor_recovery_description') }}
                    </p>

                    <form method="POST" class="space-y-6" action="{{ route('two-factor.login') }}" id="twoFactorForm">
                        @csrf

                        <x-validation-errors class="mb-4" />

                        <!-- Authentication Code Field -->
                        <div x-show="! recovery">
                            <label for="code" class="block text-sm font-medium text-gray-700 mb-2">
                                {{ __('auth.authentication_code') }}
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                    </svg>
                                </div>
                                <input
                                    id="code"
                                    name="code"
                                    type="text"
                                    inputmode="numeric"
                                    pattern="[0-9]*"
                                    maxlength="6"
                                    autocomplete="one-time-code"
                                    autofocus
                                    x-ref="code"
                                    class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-red focus:border-transparent placeholder-gray-400 sm:text-sm tracking-widest"
                                    placeholder="{{ __('auth.enter_6_digit_code') }}"
                                >
                            </div>
                        </div>

                        <!-- Recovery Code Field -->
                        <div x-cloak x-show="recovery">
                            <label for="recovery_code" class="block text-sm font-medium text-gray-700 mb-2">
                                {{ __('auth.recovery_code') }}
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                                    </svg>
                                </div>
                                <input
                                    id="recovery_code"
                                    name="recovery_code"
                                    type="text"
                                    maxlength="21"
                                    autocomplete="one-time-code"
                                    x-ref="recovery_code"
                                    class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-red focus:border-transparent placeholder-gray-400 sm:text-sm"
                                    placeholder="{{ __('auth.recovery_code') }}"
                                >
                            </div>
                        </div>

                        <!-- Toggle + Submit -->
                        <div class="flex items-center justify-between">
                            <button type="button" class="text-sm text-gray-600 hover:text-brand-red underline transition-colors"
                                x-show="! recovery"
                                x-on:click="
                                    recovery = true;
                                    $nextTick(() => { $refs.recovery_code.focus() })
                                ">
                                {{ __('auth.use_recovery_code') }}
                            </button>

                            <button type="button" class="text-sm text-gray-600 hover:text-brand-red underline transition-colors"
                                x-cloak
                                x-show="recovery"
                                x-on:click="
                                    recovery = false;
                                    $nextTick(() => { $refs.code.focus() })
                                ">
                                {{ __('auth.use_authentication_code') }}
                            </button>
                        </div>

                        <!-- Submit Button -->
                        <div>
                            <button
                                type="submit"
                                id="twoFactorSubmitBtn"
                                class="w-full bg-brand-red text-white py-3 rounded-lg font-semibold hover:bg-brand-dark-red focus:ring-4 focus:ring-brand-red/30 transition-all duration-300 transform hover:scale-[1.02] disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:scale-100"
                            >
                                <span id="twoFactorBtnText">{{ __('auth.verify_and_login') }}</span>
                                <span id="twoFactorBtnLoader" class="hidden">
                                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    {{ __('auth.verifying') }}
                                </span>
                            </button>
                        </div>
                    </form>

                    <!-- Back to Login -->
                    <div class="mt-6 text-center">
                        <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-brand-red transition-colors">
                            ← {{ __('auth.back_to_login') }}
                        </a>
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
// Prevent double click on two-factor form
document.addEventListener('DOMContentLoaded', function() {
    const twoFactorForm = document.getElementById('twoFactorForm');
    const twoFactorSubmitBtn = document.getElementById('twoFactorSubmitBtn');
    const twoFactorBtnText = document.getElementById('twoFactorBtnText');
    const twoFactorBtnLoader = document.getElementById('twoFactorBtnLoader');

    if (twoFactorForm && twoFactorSubmitBtn) {
        twoFactorForm.addEventListener('submit', function(e) {
            if (twoFactorSubmitBtn.disabled) {
                e.preventDefault();
                return false;
            }

            twoFactorSubmitBtn.disabled = true;
            twoFactorBtnText.classList.add('hidden');
            twoFactorBtnLoader.classList.remove('hidden');
        });
    }
});
</script>
</body>
</html>
