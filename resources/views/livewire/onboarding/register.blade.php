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

        <!-- Right Side - Registration Form -->
        <div class="w-full lg:w-1/2 h-screen overflow-y-auto">
            <!-- Language Switcher (Top Right) -->
            <div class="absolute top-4 right-4 z-10">
                <x-language-switcher :currentLocale="app()->getLocale()" />
            </div>
            <div class="flex items-center justify-center min-h-full p-6 sm:p-8 lg:p-12">
                <div class="w-full max-w-lg py-8">
                
                <!-- Header -->
                <div class="text-center mb-8">
                    <!-- Mobile Logo -->
                    <div class="lg:hidden mb-6">
                        <div class="flex justify-center">
                            <img src="{{ asset('landing/redlogo.png') }}" alt="Fanikisha Market place Logo" class="h-16 w-auto">
                        </div>
                    </div>
                    
                    <h2 class="text-2xl lg:text-3xl font-bold text-gray-900 mb-3">{{ $type === 'company' ? __('auth.register_business') : __('auth.register_individual') }}</h2>
                    <p class="text-gray-600">{{ $type === 'company' ? __('auth.business_onboarding') : __('auth.individual_onboarding') }}</p>
                    <div class="mt-6 inline-flex bg-gray-100 p-1 rounded-lg">
                        <button type="button" wire:click="$set('type','individual')" class="px-4 py-2 text-sm font-medium rounded-md transition {{ $type==='individual' ? 'bg-white shadow text-black' : 'text-gray-600' }}">{{ __('auth.individual') }}</button>
                        <button type="button" wire:click="$set('type','company')" class="px-4 py-2 text-sm font-medium rounded-md transition {{ $type==='company' ? 'bg-white shadow text-black' : 'text-gray-600' }}">{{ __('auth.business') }}</button>
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

    <form wire:submit="register" class="space-y-5" onsubmit="stripNidaDashes(); return true;">
        
        <!-- Name Fields -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1.5">
                    {{ __('auth.first_name') }} *
                </label>
                <input 
                    id="first_name" 
                    wire:model.live="first_name"
                    type="text" 
                    required 
                    class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-red focus:border-transparent placeholder-gray-400 text-sm transition-all @error('first_name') border-red-500 ring-1 ring-red-500 @enderror"
                    placeholder="John"
                >
                @error('first_name')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1.5">
                    {{ __('auth.last_name') }} *
                </label>
                <input 
                    id="last_name" 
                    wire:model.live="last_name"
                    type="text" 
                    required 
                    class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-red focus:border-transparent placeholder-gray-400 text-sm transition-all @error('last_name') border-red-500 ring-1 ring-red-500 @enderror"
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
                {{ __('auth.email') }} *
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
                    class="block w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-red focus:border-transparent placeholder-gray-400 text-sm transition-all @error('email') border-red-500 ring-1 ring-red-500 @enderror"
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
                    {{ __('auth.phone') }} *
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
                        class="block w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-red focus:border-transparent placeholder-gray-400 text-sm transition-all @error('phone') border-red-500 ring-1 ring-red-500 @enderror"
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
                    {{ __('auth.nida_number') }} *
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V4a2 2 0 114 0v2m-4 0a2 2 0 104 0"/>
                        </svg>
                    </div>
                    <input 
                        id="nida_number" 
                        wire:model="nida_number"
                        type="text" 
                        required 
                        maxlength="23"
                        class="block w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-red focus:border-transparent placeholder-gray-400 text-sm transition-all @error('nida_number') border-red-500 ring-1 ring-red-500 @enderror"
                        placeholder="19760517-37227-00002-17"
                    >
                </div>
                @error('nida_number')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            @else
            <div>
                <label for="company_name" class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('auth.company_name') }} *</label>
                <input id="company_name" wire:model.live="company_name" type="text" class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-red focus:border-transparent placeholder-gray-400 text-sm @error('company_name') border-red-500 ring-1 ring-red-500 @enderror" placeholder="Acme Ltd">
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
                <label for="country" class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('auth.country') }} *</label>
                <select id="country" wire:model.live="country" class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-red focus:border-transparent text-sm @error('country') border-red-500 ring-1 ring-red-500 @enderror">
                    <option value="">{{ __('auth.select_country') }}</option>
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
                    <label for="company_tin" class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('auth.company_tin') }} *</label>
                    <input id="company_tin" wire:model.live="company_tin" type="text" class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-red focus:border-transparent placeholder-gray-400 text-sm @error('company_tin') border-red-500 ring-1 ring-red-500 @enderror" placeholder="123-456-789">
                    @error('company_tin')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                @if(strtolower($country ?? '') === 'tanzania')
                <div>
                    <label for="company_contact_nida" class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('auth.representative_nida') }} *</label>
                    <span class="text-xs text-red-600 -mt-2"> Shareholder or company secretary NIDA </span>
                    <input id="company_contact_nida" wire:model="company_contact_nida" maxlength="23" type="text" class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-red focus:border-transparent placeholder-gray-400 text-sm @error('company_contact_nida') border-red-500 ring-1 ring-red-500 @enderror" placeholder="19760517-37227-00002-17">
                    @error('company_contact_nida')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                @elseif($country && strtolower($country) !== 'tanzania')
                <div>
                    <label for="passport_number" class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('auth.passport_number') }} *</label>
                    <input id="passport_number" wire:model.live="passport_number" type="text" class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-red focus:border-transparent placeholder-gray-400 text-sm @error('passport_number') border-red-500 ring-1 ring-red-500 @enderror" placeholder="A12345678">
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
                    {{ __('auth.password') }} *
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
                        class="block w-full pl-10 pr-10 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-red focus:border-transparent placeholder-gray-400 text-sm transition-all @error('password') border-red-500 ring-1 ring-red-500 @enderror"
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
                    {{ __('auth.confirm_password') }} *
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
                        class="block w-full pl-10 pr-10 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-red focus:border-transparent placeholder-gray-400 text-sm transition-all @error('password_confirmation') border-red-500 ring-1 ring-red-500 @enderror"
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
                    class="h-4 w-4 text-brand-red focus:ring-brand-red border-gray-300 rounded transition-colors"
                >
            </div>
            <div class="ml-3">
                <label for="terms" class="text-sm text-gray-600">
                    {{ __('auth.terms_accept') }} 
                    <a href="{{ route('terms.show') }}" target="_blank" class="text-brand-red hover:text-brand-dark-red font-medium underline">{{ __('auth.terms_link') }}</a> 
                    {{ __('auth.and') }} 
                    <a href="#" class="text-brand-red hover:text-brand-dark-red font-medium underline">{{ __('auth.privacy_policy') }}</a>
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
                class="w-full bg-brand-red text-white py-3.5 px-4 rounded-lg font-semibold hover:bg-brand-dark-red focus:ring-4 focus:ring-brand-red/30 transition-all duration-200 transform hover:scale-[1.02] active:scale-[0.98] focus:outline-none disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:scale-100 flex items-center justify-center"
            >
                <span wire:loading.remove wire:target="register" class="flex items-center justify-center">
                    {{ $type==='company' ? __('auth.continue_as_business') : __('auth.create_account') }}
                </span>
                <span wire:loading wire:target="register" class="flex items-center justify-center">
                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    {{ __('auth.creating_account') }}
                </span>
            </button>
        </div>

    </form>
    



                <!-- Sign In Link -->
                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-600">
                        {{ __('auth.already_have_account') }} 
                        <a href="{{ route('login') }}" class="font-medium text-brand-red hover:text-brand-dark-red transition-colors">
                            {{ __('auth.login') }}
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
    
    
    </div>

<script>
    // NIDA number formatting - format as 19760517-37227-00002-17
    function formatNidaNumber(input) {
        if (!input) return;
        
        // Get current cursor position
        const cursorPos = input.selectionStart;
        const oldValue = input.value;
        
        // Remove all non-numeric characters
        let value = input.value.replace(/\D/g, '');
        
        // Limit to 20 digits
        if (value.length > 20) {
            value = value.substring(0, 20);
        }
        
        // Format: 19760517-37227-00002-17 (8-5-5-2)
        let formatted = '';
        if (value.length > 0) {
            formatted = value.substring(0, 8);
            if (value.length > 8) {
                formatted += '-' + value.substring(8, 13);
            }
            if (value.length > 13) {
                formatted += '-' + value.substring(13, 18);
            }
            if (value.length > 18) {
                formatted += '-' + value.substring(18, 20);
            }
        }
        
        // Display formatted value
        input.value = formatted;
        
        // Calculate new cursor position (account for dashes)
        const digitsBeforeCursor = oldValue.substring(0, cursorPos).replace(/\D/g, '').length;
        let dashCount = 0;
        if (digitsBeforeCursor > 8) dashCount++;
        if (digitsBeforeCursor > 13) dashCount++;
        if (digitsBeforeCursor > 18) dashCount++;
        const newCursorPos = Math.min(digitsBeforeCursor + dashCount, formatted.length);
        input.setSelectionRange(newCursorPos, newCursorPos);
        
        // Update Livewire model with unformatted value (numbers only)
        // Dispatch input event to trigger wire:model update
        if (window.Livewire) {
            const wireId = input.closest('[wire\\:id]')?.getAttribute('wire:id');
            if (wireId) {
                const component = window.Livewire.find(wireId);
                if (component) {
                    const fieldName = input.id;
                    // Set the unformatted value directly
                    component.set(fieldName, value);
                }
            }
        }
    }
    
    // Setup NIDA formatting when DOM is ready
    document.addEventListener('DOMContentLoaded', function() {
        function setupNidaInputs() {
            const nidaInput = document.getElementById('nida_number');
            if (nidaInput && !nidaInput.dataset.formatted) {
                nidaInput.dataset.formatted = 'true';
                nidaInput.addEventListener('input', function(e) {
                    formatNidaNumber(e.target);
                });
                // Format existing value if present (unformatted)
                if (nidaInput.value && !nidaInput.value.includes('-') && /^\d{20}$/.test(nidaInput.value)) {
                    formatNidaNumber(nidaInput);
                }
            }
            
            const companyNidaInput = document.getElementById('company_contact_nida');
            if (companyNidaInput && !companyNidaInput.dataset.formatted) {
                companyNidaInput.dataset.formatted = 'true';
                companyNidaInput.addEventListener('input', function(e) {
                    formatNidaNumber(e.target);
                });
                // Format existing value if present (unformatted)
                if (companyNidaInput.value && !companyNidaInput.value.includes('-') && /^\d{20}$/.test(companyNidaInput.value)) {
                    formatNidaNumber(companyNidaInput);
                }
            }
        }
        
        setupNidaInputs();
        
        // Re-setup after Livewire updates
        document.addEventListener('livewire:init', function() {
            Livewire.hook('morph.updated', ({ el, component }) => {
                setTimeout(setupNidaInputs, 100);
            });
        });
    });
    
    // Strip dashes from NIDA fields before form submission
    function stripNidaDashes() {
        const nidaInput = document.getElementById('nida_number');
        if (nidaInput && nidaInput.value) {
            const unformatted = nidaInput.value.replace(/\D/g, '');
            if (window.Livewire) {
                const wireId = nidaInput.closest('[wire\\:id]')?.getAttribute('wire:id');
                if (wireId) {
                    const component = window.Livewire.find(wireId);
                    if (component) {
                        component.set('nida_number', unformatted);
                    }
                }
            }
        }
        
        const companyNidaInput = document.getElementById('company_contact_nida');
        if (companyNidaInput && companyNidaInput.value) {
            const unformatted = companyNidaInput.value.replace(/\D/g, '');
            if (window.Livewire) {
                const wireId = companyNidaInput.closest('[wire\\:id]')?.getAttribute('wire:id');
                if (wireId) {
                    const component = window.Livewire.find(wireId);
                    if (component) {
                        component.set('company_contact_nida', unformatted);
                    }
                }
            }
        }
        
        return true; // Allow form submission
    }
</script>
