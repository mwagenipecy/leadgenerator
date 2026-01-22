<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Fanikisha Market place</title>
    <link rel="icon" type="image/png" href="{{ asset('landing/applicationIcon.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
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
<div class="h-screen flex overflow-hidden">
<!-- Left Side - Welcome Content (Hidden on mobile) -->
  <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden h-screen" style="background-image: url('{{ asset("landing/register-login.jpg") }}'); background-size: cover; background-position: center; background-repeat: no-repeat;">
            <!-- Gradient Overlay at Bottom -->
            <div class="absolute bottom-0 left-0 right-0 h-1/4 bg-gradient-to-t from-brand-red/90 via-brand-red/60 to-transparent"></div>
            
            <!-- Marketing Content at Bottom - Squeezed to Right -->
            <div class="absolute bottom-0 right-0 z-20 p-6 pr-8 max-w-sm">
                <!-- Red Gradient Background for Text Section -->
                <div class="bg-gradient-to-t from-brand-red via-brand-red/95 to-brand-red/80 rounded-lg p-5 backdrop-blur-sm">
                    <h2 class="text-xl md:text-2xl font-bold font-poppins text-white mb-3 leading-tight">
                        Connect. Grow. Succeed.
                    </h2>
                    <div class="space-y-2 mb-4">
                        <div>
                            <h3 class="text-base font-semibold text-white mb-1">For Lenders</h3>
                            <p class="text-white text-sm leading-snug">
                                Access verified borrowers and expand your portfolio with confidence.
                            </p>
                        </div>
                        <div>
                            <h3 class="text-base font-semibold text-white mb-1">For Borrowers</h3>
                            <p class="text-white text-sm leading-snug">
                                Get matched with trusted lenders and secure the funding you need.
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="text-white text-xs font-medium">NIDA-Verified & Secure</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side - Forgot Password Form -->
        <div class="w-full lg:w-1/2 h-screen overflow-y-auto">
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
                    
                    <h2 class="text-2xl lg:text-3xl font-bold text-gray-900 mb-3">Reset Password</h2>
                    <p class="text-gray-600">Enter your email to receive a reset link</p>
                </div>

                <!-- Forgot Password Form -->
                <div class="bg-white rounded-2xl p-8 border border-gray-100">
                    
                    <!-- Description -->
                    <div class="mb-6 text-center">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-brand-red/10 rounded-full mb-4">
                            <svg class="w-8 h-8 text-brand-red" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                        <p class="text-sm text-gray-600 leading-relaxed">
                            Forgot your password? No problem. Just let us know your email address and we will send you a password reset link that will allow you to choose a new one.
                        </p>
                    </div>

                    <form method="POST" action="{{ route('password.email') }}" class="space-y-6" id="forgotPasswordForm">
                        @csrf

                        <!-- Validation Errors -->
                        <x-validation-errors class="mb-4" />

                        <!-- Success Status -->
                        @session('status')
                            <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span class="text-sm font-medium text-green-800">{{ $value }}</span>
                                </div>
                            </div>
                        @endsession

                        <!-- Email Field -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                Email 
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <input 
                                    id="email" 
                                    name="email" 
                                    type="email" 
                                    autocomplete="username" 
                                    value="{{ old('email') }}"
                                    required 
                                    autofocus
                                    class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-red focus:border-transparent placeholder-gray-400 sm:text-sm"
                                    placeholder="Enter your email"
                                >
                            </div>
                            <p class="mt-2 text-xs text-gray-500">
                                We'll send a reset link to your email address
                            </p>
                        </div>

                        <!-- Submit Button -->
                        <div>
                            <button 
                                type="submit" 
                                id="submitBtn"
                                class="w-full bg-brand-red text-white py-3 rounded-lg font-semibold hover:bg-brand-dark-red focus:ring-4 focus:ring-brand-red/30 transition-all duration-300 transform hover:scale-[1.02] disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:scale-100 flex items-center justify-center"
                            >
                                <span id="btnText" class="flex items-center justify-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                    Send Password Reset Link
                                </span>
                                <span id="btnLoader" class="hidden flex items-center justify-center">
                                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Sending...
                                </span>
                            </button>
                        </div>

                    </form>

                    <!-- Back to Login Link -->
                    <div class="mt-6 text-center">
                        <p class="text-sm text-gray-600">
                            Remember your password? 
                            <a href="{{ route('login') }}" class="font-medium text-brand-red hover:text-brand-dark-red transition-colors inline-flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                Back to Sign In
                            </a>
                        </p>
                    </div>
                </div>

                <!-- Security Notice -->
                <div class="mt-6 text-center">
                    <div class="flex items-center justify-center space-x-2 text-sm text-gray-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                        <span>Reset links expire in 60 minutes for your security</span>
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
// Prevent double click on forgot password form
document.addEventListener('DOMContentLoaded', function() {
    const forgotPasswordForm = document.getElementById('forgotPasswordForm');
    const submitBtn = document.getElementById('submitBtn');
    const btnText = document.getElementById('btnText');
    const btnLoader = document.getElementById('btnLoader');
    
    if (forgotPasswordForm && submitBtn) {
        forgotPasswordForm.addEventListener('submit', function(e) {
            // Prevent double submission
            if (submitBtn.disabled) {
                e.preventDefault();
                return false;
            }
            
            // Show loader and disable button
            submitBtn.disabled = true;
            btnText.classList.add('hidden');
            btnLoader.classList.remove('hidden');
        });
    }
});
</script>
</body>
</html>
