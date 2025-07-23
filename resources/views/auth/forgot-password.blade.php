<x-guest-layout>
    <x-authentication-card>
        <!-- Header -->
        <div class="text-center">
            <!-- Logo -->
            <div class="text-3xl font-bold font-poppins text-black mb-2">
                Lead<span class="text-brand-red">Generator</span>
            </div>
            <h2 class="text-2xl font-semibold text-gray-900 mb-2">Reset Password</h2>
            <p class="text-gray-600">Enter your email  to receive a reset link</p>
        </div>

        <!-- Forgot Password Form -->
        <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100">
            
            <!-- Description -->
            <div class="mb-6 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-brand-red/10 rounded-full mb-4">
                    <svg class="w-8 h-8 text-brand-red" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                </div>
                <p class="text-sm text-gray-600 leading-relaxed">
                    Forgot your password? No problem. Just let us know your email address  and we will send you a password reset link that will allow you to choose a new one.
                </p>
            </div>

            <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
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

                <!-- Email/Phone Field -->
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
                            type="text" 
                            autocomplete="username" 
                            :value="old('email')"
                            required 
                            autofocus
                            class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-red focus:border-transparent placeholder-gray-400 sm:text-sm"
                            placeholder="Enter your email "
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
                        class="w-full bg-brand-red text-white py-3 rounded-lg font-semibold hover:bg-red-700 focus:ring-4 focus:ring-brand-red/30 transition-all duration-300 transform hover:scale-[1.02] flex items-center justify-center"
                    >
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        Send Password Reset Link
                    </button>
                </div>

            </form>

            <!-- Back to Login Link -->
            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600">
                    Remember your password? 
                    <a href="{{ route('login') }}" class="font-medium text-brand-red hover:text-red-700 transition-colors inline-flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Back to Sign In
                    </a>
                </p>
            </div>
        </div>

        <!-- Security Notice -->
        <div class="text-center">
            <div class="flex items-center justify-center space-x-2 text-sm text-gray-500">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                </svg>
                <span>Reset links expire in 60 minutes for your security</span>
            </div>
        </div>

    </x-authentication-card>
</x-guest-layout>