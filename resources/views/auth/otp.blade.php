<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('OTP Verification') }} - LeadGenerator</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'brand-red': '#dc2626'
                    },
                    fontFamily: {
                        'poppins': ['Poppins', 'sans-serif']
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-gradient-to-br from-red-50 to-red-100 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md space-y-8">
        
        <!-- Header -->
        <div class="text-center">
            <!-- Logo -->
            <div class="text-3xl font-bold font-poppins text-black mb-2">
                Lead<span class="text-brand-red">Generator</span>
            </div>
            <h2 class="text-2xl font-semibold text-gray-900 mb-2">{{ __('Verify Your Identity') }}</h2>
            <p class="text-gray-600">{{ __("We've sent a 6-digit code to") }}</p>
@php
    $email = $user->email;
    $atPos = strpos($email, '@');
    $maskedEmail = substr($email, 0, 2) . str_repeat('*', $atPos - 2) . substr($email, $atPos);
@endphp

<p class="text-gray-800 font-medium">{{ $maskedEmail }}</p>

</div>

        <!-- OTP Form -->
        <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100">
            
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
                        {{ __('Enter 6-digit verification code') }}
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
                        class="w-full bg-brand-red text-white py-3 rounded-lg font-semibold hover:bg-red-700 focus:ring-4 focus:ring-brand-red/30 transition-all duration-300 transform hover:scale-[1.02] disabled:opacity-50 disabled:cursor-not-allowed"
                        disabled
                    >
                        {{ __('Verify Code') }}
                    </button>
                </div>

                <!-- Resend OTP -->
                <div class="text-center">
                    <p class="text-sm text-gray-600 mb-2">
                        {{ __("Didn't receive the code?") }}
                    </p>
                    <form method="POST" action="{{ route('otp.resend') }}" class="inline">
                        @csrf
                        <button 
                            type="submit" 
                            id="resendBtn"
                            class="font-medium text-brand-red hover:text-red-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed text-sm"
                            @if(!$canResend) disabled @endif
                        >
                            {{ __('Resend Code') }}
                        </button>
                    </form>
                </div>

            </form>

            <!-- Back to Login -->
            <div class="mt-6 text-center">
                <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-gray-900 transition-colors">
                    ← {{ __('Back to Login') }}
                </a>
            </div>
        </div>

        <!-- Security Notice -->
        <div class="text-center">
            <div class="flex items-center justify-center space-x-2 text-sm text-gray-500">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
                <span>{{ __('Code expires in 10 minutes for security') }}</span>
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
                    timerElement.textContent = '{{ __("Code expired. Please request a new one.") }}';
                    timerElement.className = 'text-sm text-red-600 mb-4';
                    
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
                    timerElement.textContent = `{{ __('Code expires in') }} ${minutes}:${seconds.toString().padStart(2, '0')}`;
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
                verifyBtn.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>{{ __("Verifying...") }}';
                verifyBtn.disabled = true;
            });

            // Add loading state to resend button
            if (resendBtn) {
                resendBtn.closest('form').addEventListener('submit', function() {
                    resendBtn.innerHTML = '{{ __("Sending...") }}';
                    resendBtn.disabled = true;
                });
            }
        });
    </script>
</body>
</html>