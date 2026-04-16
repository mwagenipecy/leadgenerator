<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('auth.login') }} - {{ config('app.name') }}</title>
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

    $authSideImageValue = \App\Models\SystemSetting::getValue('auth_side_image_login_' . app()->getLocale())
        ?: \App\Models\SystemSetting::getValue('auth_side_image_login_en')
        ?: \App\Models\SystemSetting::getValue('auth_side_image_' . app()->getLocale())
        ?: \App\Models\SystemSetting::getValue('auth_side_image_en')
        ?: null;

    $authSideImage = $resolveAuthImageUrl($authSideImageValue);
@endphp
<div class="h-screen flex overflow-hidden">
<!-- Left Side - Welcome Content (Hidden on mobile) -->
  <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden h-screen" style="background-image: url('{{ $authSideImage }}'); background-size: cover; background-position: center; background-repeat: no-repeat;">
            <!-- Gradient Overlay at Bottom -->
            <!-- <div class="absolute bottom-0 left-0 right-0 h-1/4 bg-gradient-to-t from-brand-red/90 via-brand-red/60 to-transparent"></div> -->
            
            <!-- Marketing Content at Bottom - Squeezed to Right -->
            <div class="absolute bottom-0 right-0 z-20 p-6 pr-8 max-w-sm">
              
            </div>
        </div>

        <!-- Right Side - Login Form -->
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
                    
                    <h2 class="text-2xl lg:text-3xl font-bold text-gray-900 mb-3">{{ __('common.welcome') }}</h2>
            <p class="text-gray-600">{{ __('auth.login_title') }}</p>
        </div>

        <!-- Login Form -->
                <div class="bg-white rounded-2xl p-8 border border-gray-100">
            
                    <form method="POST" class="space-y-6" action="{{ route('login') }}" id="loginForm">
                @csrf 

                <x-validation-errors class="mb-4" />

                @session('status')
                    <div class="mb-4 font-medium text-sm text-green-600">
                        {{ $value }}
                    </div>
                @endsession

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('auth.email') }} / {{ __('auth.phone') }}
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <input 
                            id="email" 
                            name="login" 
                            type="text" 
                            autocomplete="login" 
                                    value="{{ old('login') }}"
                            required 
                                    class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-red focus:border-transparent placeholder-gray-400 sm:text-sm"
                            placeholder="{{ __('auth.email') }} / {{ __('auth.phone') }}"
                        >
                    </div>
                </div>

                <!-- Password Field -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('auth.password') }}
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                        <input 
                            id="password" 
                            name="password" 
                            type="password" 
                            autocomplete="current-password" 
                            required 
                                    class="block w-full pl-10 pr-10 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-red focus:border-transparent placeholder-gray-400 sm:text-sm"
                            placeholder="{{ __('auth.password') }}"
                        >
                        <button 
                            type="button" 
                            class="absolute inset-y-0 right-0 pr-3 flex items-center"
                            onclick="togglePassword()"
                        >
                            <svg id="eye-icon" class="h-5 w-5 text-gray-400 hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input 
                            id="remember-me" 
                                    name="remember" 
                            type="checkbox" 
                                    class="h-4 w-4 text-brand-red focus:ring-brand-red border-gray-300 rounded"
                        >
                        <label for="remember-me" class="ml-2 block text-sm text-gray-700">
                            {{ __('auth.remember_me') }}
                        </label>
                    </div>

                    @if (Route::has('password.request'))
                            <a class="underline text-sm text-gray-600 hover:text-brand-red transition-colors" href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif
                </div>

                <!-- Submit Button -->
                <div>
                    <button 
                        type="submit" 
                                id="loginSubmitBtn"
                                class="w-full bg-brand-red text-white py-3 rounded-lg font-semibold hover:bg-brand-dark-red focus:ring-4 focus:ring-brand-red/30 transition-all duration-300 transform hover:scale-[1.02] disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:scale-100"
                    >
                                <span id="loginBtnText">{{ __('auth.login') }}</span>
                                <span id="loginBtnLoader" class="hidden">
                                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    {{ __('common.loading') }}
                                </span>
                    </button>
                </div>
            </form>

            <!-- Sign Up Link -->
            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600">
                    {{ __('auth.not_registered') }} 
                            <a href="{{ route('user.register') }}{{ session()->has('locale') && session()->get('locale') !== config('app.locale') ? '?locale=' . urlencode(session()->get('locale')) : '' }}" class="font-medium text-brand-red hover:text-brand-dark-red transition-colors">
                        {{ __('auth.create_account') }}
                    </a>
                </p>
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
                    
                    <p class="text-xs text-gray-400">Powered by CreditInfo Tanzania</p>
                </div>
                </div>
            </div>
        </div>
    
    </div>

<script>
function togglePassword() {
    const passwordInput = document.getElementById('password');
    const eyeIcon = document.getElementById('eye-icon');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        eyeIcon.innerHTML = `
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
        `;
    } else {
        passwordInput.type = 'password';
        eyeIcon.innerHTML = `
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
        `;
    }
}

// Prevent double click on login form
document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('loginForm');
    const loginSubmitBtn = document.getElementById('loginSubmitBtn');
    const loginBtnText = document.getElementById('loginBtnText');
    const loginBtnLoader = document.getElementById('loginBtnLoader');
    
    if (loginForm && loginSubmitBtn) {
        loginForm.addEventListener('submit', function(e) {
            // Prevent double submission
            if (loginSubmitBtn.disabled) {
                e.preventDefault();
                return false;
            }
            
            // Show loader and disable button
            loginSubmitBtn.disabled = true;
            loginBtnText.classList.add('hidden');
            loginBtnLoader.classList.remove('hidden');
        });
    }
});
</script>
</body>
</html>
