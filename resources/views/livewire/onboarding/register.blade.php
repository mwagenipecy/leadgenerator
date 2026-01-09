<div class="min-h-screen flex">
<!-- Left Side - Welcome Content (Hidden on mobile) -->
  <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden" style="background-image: url('{{ asset("landing/registerImage.png") }}'); background-size: cover; background-position: center;">
            <!-- Gradient Overlay at Bottom -->
            <div class="absolute inset-0 bg-gradient-to-t from-brand-green/90 via-brand-green/50 to-transparent"></div>
            
            <!-- Content -->
            <div class="relative z-10 flex flex-col justify-between p-12 w-full">
                <!-- Logo at Top -->
                <div class="mb-8">
                    <img src="{{ asset('logo/logoOnGreenBg.png') }}" alt="Lead Generator Logo" class="h-20 w-auto">
                </div>
                
                <!-- Text Content at Bottom -->
                <div class="max-w-sm">
                    <!-- Welcome Text -->
                    <h1 class="text-3xl font-bold font-poppins text-white mb-4">
                        Join Fanikisha
                    </h1>
                    <p class="text-white/90 text-lg mb-8 leading-relaxed">
                        Transform your business with our advanced lead generation platform powered by secure NIDA verification.
                    </p>
                    
                    <!-- Feature List -->
                    <div class="space-y-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center backdrop-blur-sm">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <span class="text-white">Biometric Security</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center backdrop-blur-sm">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <span class="text-white">Real-time Analytics</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center backdrop-blur-sm">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <span class="text-white">NIDA Integration</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side - Registration Form -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-6 sm:p-8 lg:p-12">
            <div class="w-full max-w-lg">
                
                <!-- Header -->
                <div class="text-center mb-8">
                    <!-- Mobile Logo -->
                    <div class="lg:hidden mb-6">
                        <div class="flex justify-center">
                            <img src="{{ asset('logo/logoOnWhitebg.png') }}" alt="Lead Generator Logo" class="h-16 w-auto">
                        </div>
                    </div>
                    
                    <h2 class="text-2xl lg:text-3xl font-bold text-gray-900 mb-3">{{ $type === 'company' ? 'Register Your Business' : 'Create Your Account' }}</h2>
                    <p class="text-gray-600">{{ $type === 'company' ? 'Business onboarding to connect with borrowers' : 'Sign up to start generating quality leads' }}</p>
                    <div class="mt-6 inline-flex bg-gray-100 p-1 rounded-lg">
                        <button type="button" wire:click="$set('type','individual')" class="px-4 py-2 text-sm font-medium rounded-md transition {{ $type==='individual' ? 'bg-white shadow text-black' : 'text-gray-600' }}">Individual</button>
                        <button type="button" wire:click="$set('type','company')" class="px-4 py-2 text-sm font-medium rounded-md transition {{ $type==='company' ? 'bg-white shadow text-black' : 'text-gray-600' }}">Business</button>
                    </div>
                </div>

                <!-- Registration Form -->
                @if (session()->has('success'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
            <p class="text-green-800 text-sm">{{ session('success') }}</p>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
            <p class="text-red-800 text-sm">{{ session('error') }}</p>
        </div>
    @endif

    <form wire:submit="register" class="space-y-5">
        
        <!-- Name Fields -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1.5">
                    First Name *
                </label>
                <input 
                    id="first_name" 
                    wire:model.live="first_name"
                    type="text" 
                    required 
                    class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-green focus:border-transparent placeholder-gray-400 text-sm transition-all @error('first_name') border-red-500 ring-1 ring-red-500 @enderror"
                    placeholder="John"
                >
                @error('first_name')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1.5">
                    Last Name *
                </label>
                <input 
                    id="last_name" 
                    wire:model.live="last_name"
                    type="text" 
                    required 
                    class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-green focus:border-transparent placeholder-gray-400 text-sm transition-all @error('last_name') border-red-500 ring-1 ring-red-500 @enderror"
                    placeholder="Doe"
                >
                @error('last_name')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Email -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">
                Email Address *
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                    </svg>
                </div>
                <input 
                    id="email" 
                    wire:model.live="email"
                    type="email" 
                    autocomplete="email" 
                    required 
                    class="block w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-green focus:border-transparent placeholder-gray-400 text-sm transition-all @error('email') border-red-500 ring-1 ring-red-500 @enderror"
                    placeholder="john.doe@example.com"
                >
            </div>
            @error('email')
                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Phone and NIDA -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label for="phone" class="block text-sm font-medium text-gray-700 mb-1.5">
                    Phone Number *
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                    </div>
                    <input 
                        id="phone" 
                        wire:model.live="phone"
                        type="tel" 
                        required 
                        class="block w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-green focus:border-transparent placeholder-gray-400 text-sm transition-all @error('phone') border-red-500 ring-1 ring-red-500 @enderror"
                        placeholder="+255 XXX XXX XXX"
                    >
                </div>
                @error('phone')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            @if($type === 'individual')
            <div>
                <label for="nida_number" class="block text-sm font-medium text-gray-700 mb-1.5">
                    NIDA Number *
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V4a2 2 0 114 0v2m-4 0a2 2 0 104 0"/>
                        </svg>
                    </div>
                    <input 
                        id="nida_number" 
                        wire:model.live="nida_number"
                        type="text" 
                        required 
                        maxlength="20"
                        class="block w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-green focus:border-transparent placeholder-gray-400 text-sm transition-all @error('nida_number') border-red-500 ring-1 ring-red-500 @enderror"
                        placeholder="19XXXXXXXXXXXXXXXX"
                    >
                </div>
                @error('nida_number')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            @else
            <div>
                <label for="company_name" class="block text-sm font-medium text-gray-700 mb-1.5">Company Name *</label>
                <input id="company_name" wire:model.live="company_name" type="text" class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-green focus:border-transparent placeholder-gray-400 text-sm @error('company_name') border-red-500 ring-1 ring-red-500 @enderror" placeholder="Acme Ltd">
                @error('company_name')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            @endif

            
        </div>

        @if($type === 'company')
        <div class="space-y-4">
            <!-- Country Selection -->
            <div>
                <label for="country" class="block text-sm font-medium text-gray-700 mb-1.5">Country *</label>
                <select id="country" wire:model.live="country" class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-green focus:border-transparent text-sm @error('country') border-red-500 ring-1 ring-red-500 @enderror">
                    <option value="">Select Country</option>
                    <option value="Tanzania">Tanzania</option>
                    <option value="Kenya">Kenya</option>
                    <option value="Uganda">Uganda</option>
                    <option value="Rwanda">Rwanda</option>
                    <option value="Other">Other</option>
                </select>
                @error('country')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="company_tin" class="block text-sm font-medium text-gray-700 mb-1.5">Company TIN *</label>
                    <input id="company_tin" wire:model.live="company_tin" type="text" class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-green focus:border-transparent placeholder-gray-400 text-sm @error('company_tin') border-red-500 ring-1 ring-red-500 @enderror" placeholder="123-456-789">
                    @error('company_tin')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                @if(strtolower($country ?? '') === 'tanzania')
                <div>
                    <label for="company_contact_nida" class="block text-sm font-medium text-gray-700 mb-1.5">Representative NIDA *</label>
                    <input id="company_contact_nida" wire:model.live="company_contact_nida" maxlength="20" type="text" class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-green focus:border-transparent placeholder-gray-400 text-sm @error('company_contact_nida') border-red-500 ring-1 ring-red-500 @enderror" placeholder="19XXXXXXXXXXXXXXXX">
                    @error('company_contact_nida')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                @elseif($country && strtolower($country) !== 'tanzania')
                <div>
                    <label for="passport_number" class="block text-sm font-medium text-gray-700 mb-1.5">Passport Number *</label>
                    <input id="passport_number" wire:model.live="passport_number" type="text" class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-green focus:border-transparent placeholder-gray-400 text-sm @error('passport_number') border-red-500 ring-1 ring-red-500 @enderror" placeholder="A12345678">
                    @error('passport_number')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                @endif
            </div>
        </div>
        @endif

        <!-- Password Fields -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">
                    Password *
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    <input 
                        id="password" 
                        wire:model.live="password"
                        type="password" 
                        autocomplete="new-password" 
                        required 
                        class="block w-full pl-10 pr-10 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-green focus:border-transparent placeholder-gray-400 text-sm transition-all @error('password') border-red-500 ring-1 ring-red-500 @enderror"
                        placeholder="••••••••"
                    >
                    <button 
                        type="button" 
                        class="absolute inset-y-0 right-0 pr-3 flex items-center"
                        onclick="togglePassword('password')"
                    >
                        <svg id="password-eye" class="h-5 w-5 text-gray-400 hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </button>
                </div>
                @error('password')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1.5">
                    Confirm Password *
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    <input 
                        id="password_confirmation" 
                        wire:model.live="password_confirmation"
                        type="password" 
                        autocomplete="new-password" 
                        required 
                        class="block w-full pl-10 pr-10 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-green focus:border-transparent placeholder-gray-400 text-sm transition-all @error('password_confirmation') border-red-500 ring-1 ring-red-500 @enderror"
                        placeholder="••••••••"
                    >
                    <button 
                        type="button" 
                        class="absolute inset-y-0 right-0 pr-3 flex items-center"
                        onclick="togglePassword('password_confirmation')"
                    >
                        <svg id="password_confirmation-eye" class="h-5 w-5 text-gray-400 hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </button>
                </div>
                @error('password_confirmation')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Terms and Conditions -->
        <div class="flex items-start">
            <div class="flex items-center h-5">
                <input 
                    id="terms" 
                    wire:model.live="terms"
                    type="checkbox" 
                    required
                    class="h-4 w-4 text-brand-green focus:ring-brand-green border-gray-300 rounded transition-colors"
                >
            </div>
            <div class="ml-3">
                <label for="terms" class="text-sm text-gray-600">
                    I agree to the 
                    <a href="#" class="text-brand-green hover:text-brand-green-light font-medium">Terms of Service</a> 
                    and 
                    <a href="#" class="text-brand-green hover:text-brand-green-light font-medium">Privacy Policy</a>
                </label>
            </div>
        </div>
        @error('terms')
            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
        @enderror

        <!-- Submit Button -->
        <div class="pt-2">
            <button 
                type="submit" 
                wire:loading.attr="disabled"
                wire:target="register"
                class="w-full bg-brand-green text-white py-3.5 px-4 rounded-lg font-semibold hover:bg-brand-green-light focus:ring-4 focus:ring-brand-green/30 transition-all duration-200 transform hover:scale-[1.02] active:scale-[0.98] focus:outline-none disabled:opacity-50 disabled:cursor-not-allowed"
            >
                <span wire:loading.remove wire:target="register">{{ $type==='company' ? 'Continue as Business' : 'Create Account' }}</span>
                <span wire:loading wire:target="register">Creating Account...</span>
            </button>
        </div>

    </form>
    



                <!-- Sign In Link -->
                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-600">
                        Already have an account? 
                        <a href="{{ route('login') }}" class="font-medium text-brand-green hover:text-brand-green-light transition-colors">
                            Sign in
                        </a>
                    </p>
                </div>

                <!-- Security Notice -->
                <div class="mt-6 text-center">
                    <div class="inline-flex items-center space-x-2 text-xs text-gray-500 bg-gray-100 px-3 py-2 rounded-lg">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        <span>Protected by industry-standard encryption</span>
                    </div>
                </div>
            </div>
        </div>
    
    
    </div>
