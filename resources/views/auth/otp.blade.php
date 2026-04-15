<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('auth.otp_verification') }} - Fanikisha Market place</title>
    <link rel="icon" type="image/png" href="{{ asset('landing/applicationIcon.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'brand-red': '#C40F11',
                        'brand-dark-red': '#A00E11',
                        'brand-green': '#C40F11',
                        'brand-green-light': '#999999',
                        'brand-gray': '#999999'
                    },
                    fontFamily: {
                        'poppins': ['Poppins', 'sans-serif'],
                        'inter': ['Inter', 'sans-serif']
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Alpine.js for language switcher -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
        .language-dropdown { z-index: 9999 !important; }
    </style>
</head>
<body class="font-inter">
@php
    $authSideImage = \App\Models\SystemSetting::getValue('auth_side_image_otp_' . app()->getLocale())
        ?: \App\Models\SystemSetting::getValue('auth_side_image_otp_en')
        ?: \App\Models\SystemSetting::getValue('auth_side_image_' . app()->getLocale())
        ?: \App\Models\SystemSetting::getValue('auth_side_image_en')
        ?: asset('landing/register-login.jpg');
@endphp
<div class="h-screen flex overflow-hidden">
<!-- Left Side - Welcome Content (Hidden on mobile) -->
  <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden h-screen" style="background-image: url('{{ $authSideImage }}'); background-size: cover; background-position: center; background-repeat: no-repeat;">
            <!-- Gradient Overlay at Bottom -->
            <div class="absolute bottom-0 left-0 right-0 h-1/4 bg-gradient-to-t from-brand-red/90 via-brand-red/60 to-transparent"></div>
            
            <!-- Marketing Content at Bottom - Squeezed to Right -->
            <div class="absolute bottom-0 right-0 z-20 p-6 pr-8 max-w-sm">
                <!-- Red Gradient Background for Text Section -->
                <div class="bg-gradient-to-t from-brand-red via-brand-red/95 to-brand-red/80 rounded-lg p-5 backdrop-blur-sm">
                    <h2 class="text-xl md:text-2xl font-bold font-poppins text-white mb-3 leading-tight">
                        {{ __('auth.connect_grow_succeed') }}
                    </h2>
                    <div class="space-y-2 mb-4">
                        <div>
                            <h3 class="text-base font-semibold text-white mb-1">{{ __('auth.for_lenders') }}</h3>
                            <p class="text-white text-sm leading-snug">
                                {{ __('auth.access_verified_borrowers') }}
                            </p>
                        </div>
                        <div>
                            <h3 class="text-base font-semibold text-white mb-1">{{ __('auth.for_borrowers') }}</h3>
                            <p class="text-white text-sm leading-snug">
                                {{ __('auth.get_matched_with_lenders') }}
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="text-white text-xs font-medium">{{ __('auth.nida_verified_secure') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side - OTP Form -->
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
            <h2 class="text-2xl lg:text-3xl font-bold text-gray-900 mb-2">{{ __('auth.verify_identity') }}</h2>
            <p class="text-gray-600">{{ __('auth.otp_sent_to') }}</p>
@php
    $email = $user->email;
    $atPos = strpos($email, '@');
    $maskedEmail = substr($email, 0, 2) . str_repeat('*', $atPos - 2) . substr($email, $atPos);
@endphp

<p class="text-gray-800 font-medium">{{ $maskedEmail }}</p>

</div>

        <!-- OTP Form -->
        <div class="bg-white rounded-2xl p-8 border border-gray-100">
            
            <form method="POST" action="{{ route('otp.verify') }}" class="space-y-6" id="otpForm">
                @csrf
                
                <!-- Validation Errors -->
                @if($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                        @foreach($errors->all() as $error)
                            <p class="text-sm">{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <!-- Success Messages -->
                @if(session('success'))
                    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                        <p class="text-sm">{{ session('success') }}</p>
                    </div>
                @endif

                <!-- Error Messages -->
                @if(session('error'))
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                        <p class="text-sm">{{ session('error') }}</p>
                    </div>
                @endif

                <!-- OTP Input -->
                <div>
                    <label for="otp" class="block text-sm font-medium text-gray-700 mb-4 text-center">
                        {{ __('auth.enter_6_digit_code') }}
                    </label>
                    
                    <!-- OTP Input Fields -->
                    <div class="flex justify-center space-x-2 mb-6">
                        @for($i = 0; $i < 6; $i++)
                            <input 
                                type="text" 
                                maxlength="1" 
                                class="otp-input w-12 h-12 text-center text-xl font-bold border-2 border-gray-300 rounded-lg focus:border-brand-red focus:ring-2 focus:ring-brand-red/30 focus:outline-none transition-all duration-200" 
                                data-index="{{ $i }}"
                                autocomplete="off"
                            >
                        @endfor
                    </div>
                    
                    <!-- Hidden input for form submission -->
                    <input type="hidden" name="otp" id="otpHidden">
                </div>

                <!-- Timer -->
                <div class="text-center">
                    <div id="timer" class="text-sm text-gray-600 mb-4"></div>
                </div>

                <!-- Submit Button -->
                <div>
                    <button 
                        type="submit" 
                        id="verifyBtn"
                        class="w-full bg-brand-red text-white py-3 rounded-lg font-semibold hover:bg-brand-dark-red focus:ring-4 focus:ring-brand-red/30 transition-all duration-300 transform hover:scale-[1.02] disabled:opacity-50 disabled:cursor-not-allowed"
                        disabled
                    >
                        {{ __('auth.verify_code') }}
                    </button>
                </div>

                <!-- Resend OTP -->
                <div class="text-center">
                    <p class="text-sm text-gray-600 mb-2">
                        {{ __('auth.didnt_receive_code') }}
                    </p>
                    <form method="POST" action="{{ route('otp.resend') }}" class="inline">
                        @csrf
                        <button 
                            type="submit" 
                            id="resendBtn"
                            class="font-medium text-brand-red hover:text-brand-dark-red transition-colors disabled:opacity-50 disabled:cursor-not-allowed text-sm"
                            @if(!$canResend) disabled @endif
                        >
                            {{ __('auth.resend_code') }}
                        </button>
                    </form>
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
                <span>{{ __('auth.code_expires_in_minutes') }}</span>
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
        document.addEventListener('DOMContentLoaded', function() {
            const otpInputs = document.querySelectorAll('.otp-input');
            const otpHidden = document.getElementById('otpHidden');
            const verifyBtn = document.getElementById('verifyBtn');
            const timerElement = document.getElementById('timer');
            const resendBtn = document.getElementById('resendBtn');

            // Initialize timer
            let remainingTime = {{ $remainingTime }};
            updateTimer();
            
            const timerInterval = setInterval(() => {
                remainingTime--;
                updateTimer();
                
                if (remainingTime <= 0) {
                    clearInterval(timerInterval);
                    timerElement.textContent = '{{ __('auth.code_expired_request_new') }}';
                    timerElement.className = 'text-sm text-brand-red mb-4';
                    
                    // Enable resend button when code expires
                    if (resendBtn) {
                        resendBtn.disabled = false;
                    }
                }
            }, 1000);

            function updateTimer() {
                if (remainingTime > 0) {
                    const minutes = Math.floor(remainingTime / 60);
                    const seconds = remainingTime % 60;
                    timerElement.textContent = `{{ __('auth.code_expires_in') }} ${minutes}:${seconds.toString().padStart(2, '0')}`;
                    timerElement.className = 'text-sm text-gray-600 mb-4';
                    
                    // Enable resend button in last minute
                    if (remainingTime <= 60 && resendBtn) {
                        resendBtn.disabled = false;
                    }
                } else {
                    if (resendBtn) {
                        resendBtn.disabled = false;
                    }
                }
            }

            // OTP Input handling
            otpInputs.forEach((input, index) => {
                input.addEventListener('input', function(e) {
                    const value = e.target.value;
                    
                    // Only allow numbers
                    if (!/^\d*$/.test(value)) {
                        e.target.value = '';
                        return;
                    }

                    // Move to next input
                    if (value && index < otpInputs.length - 1) {
                        otpInputs[index + 1].focus();
                    }

                    updateOtpValue();
                });

                input.addEventListener('keydown', function(e) {
                    // Move to previous input on backspace
                    if (e.key === 'Backspace' && !e.target.value && index > 0) {
                        otpInputs[index - 1].focus();
                    }

                    // Submit on Enter if all fields filled
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        if (isOtpComplete()) {
                            document.getElementById('otpForm').submit();
                        }
                    }
                });

                input.addEventListener('paste', function(e) {
                    e.preventDefault();
                    const pasteData = e.clipboardData.getData('text');
                    const digits = pasteData.replace(/\D/g, '').split('').slice(0, 6);
                    
                    digits.forEach((digit, i) => {
                        if (otpInputs[i]) {
                            otpInputs[i].value = digit;
                        }
                    });
                    
                    updateOtpValue();
                    
                    // Focus on next empty input or last input
                    const nextEmptyIndex = digits.length < 6 ? digits.length : 5;
                    if (otpInputs[nextEmptyIndex]) {
                        otpInputs[nextEmptyIndex].focus();
                    }
                });
            });

            function updateOtpValue() {
                const otpValue = Array.from(otpInputs).map(input => input.value).join('');
                otpHidden.value = otpValue;
                verifyBtn.disabled = !isOtpComplete();
                
                // Add visual feedback
                otpInputs.forEach(input => {
                    if (input.value) {
                        input.classList.add('border-brand-red', 'bg-red-50');
                        input.classList.remove('border-gray-300');
                    } else {
                        input.classList.remove('border-brand-red', 'bg-red-50');
                        input.classList.add('border-gray-300');
                    }
                });
            }

            function isOtpComplete() {
                return Array.from(otpInputs).every(input => input.value.length === 1);
            }

            // Focus first input on page load
            if (otpInputs[0]) {
                otpInputs[0].focus();
            }

            // Add loading state to submit button
            document.getElementById('otpForm').addEventListener('submit', function() {
                verifyBtn.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>{{ __('auth.verifying') }}';
                verifyBtn.disabled = true;
            });

            // Add loading state to resend button
            if (resendBtn) {
                resendBtn.closest('form').addEventListener('submit', function() {
                    resendBtn.innerHTML = '{{ __('auth.sending_code') }}';
                    resendBtn.disabled = true;
                });
            }
        });
    </script>
</body>
</html>
