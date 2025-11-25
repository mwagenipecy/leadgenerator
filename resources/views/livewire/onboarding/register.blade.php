@php
    $slides = [
        [
            'image' => asset('register-assets/image/slider/getCreditScoreInstantly.png'),
            'title' => 'Get credit scores instantly',
            'description' => 'Access bureau-backed credit information and insights in seconds.',
        ],
        [
            'image' => asset('register-assets/image/slider/onlineCreditInstantly.jpg'),
            'title' => 'Accelerate borrower onboarding',
            'description' => 'Guide applicants through a seamless, digital-first verification flow.',
        ],
        [
            'image' => asset('register-assets/image/slider/registerEasyAndApplyOnline.jpg'),
            'title' => 'Register, analyze, approve',
            'description' => 'Empower your team with collaborative tools from application to payout.',
        ],
    ];
@endphp

<div class="min-h-screen flex flex-col lg:flex-row gap-0 p-4">
    <!-- Left Side Slider -->
    <div class="hidden lg:flex lg:w-1/2 items-center justify-center p-4">
        <div class="relative w-full h-[620px] rounded-3xl overflow-hidden shadow-2xl" id="register-slider">
            @foreach ($slides as $index => $slide)
                <div 
                    class="absolute inset-0 transition-all duration-700 ease-in-out {{ $index === 0 ? 'opacity-100 scale-100' : 'opacity-0 scale-105 pointer-events-none' }}" 
                    data-slide="{{ $index }}"
                >
                    <img 
                        src="{{ $slide['image'] }}" 
                        alt="{{ $slide['title'] }}" 
                        class="w-full h-full object-cover"
                        loading="lazy"
                    >
                    <div class="absolute inset-0 bg-gradient-to-t from-black/85 via-black/25 to-transparent"></div>
                    <div class="absolute inset-x-0 bottom-0 p-8">
                        <div class="bg-black/65 backdrop-blur-sm rounded-2xl p-6 shadow-[0_-20px_60px_rgba(0,0,0,0.45)]">
                            <p class="text-sm uppercase tracking-[0.4em] text-white/70 mb-3">LeadGenerator</p>
                            <h3 class="text-3xl font-semibold text-white capitalize mb-3">{{ $slide['title'] }}</h3>
                            <p class="text-white/85 leading-relaxed text-base">{{ $slide['description'] }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
            <div class="absolute bottom-6 left-0 right-0 flex justify-center">
                <div class="flex items-center gap-3 bg-black/40 backdrop-blur-lg px-6 py-3 rounded-full" id="slider-dots">
                    @foreach ($slides as $index => $slide)
                        <button 
                            type="button" 
                            class="h-2.5 rounded-full transition-all duration-300 {{ $index === 0 ? 'w-8 bg-white' : 'w-2.5 bg-white/40' }}"
                            aria-label="Show slide {{ $index + 1 }}"
                            data-dot="{{ $index }}"
                        ></button>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

        <!-- Right Side - Registration Form -->
        <div class="w-full lg:w-1/2 flex items-center justify-center px-4 pl-0 pr-4 py-4">
            <div class="w-full max-w-lg">
                
                <!-- Header -->
                <div class="text-center mb-8">
                    <!-- Mobile Logo -->
                    <div class="lg:hidden mb-6">
                        <div class="w-16 h-16 bg-brand-red/10 rounded-xl flex items-center justify-center mx-auto">
                            <svg class="w-8 h-8 text-brand-red" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                        <h1 class="text-2xl font-bold font-poppins text-black mt-3">
                            Lead<span class="text-brand-red">Generator</span>
                        </h1>
                    </div>
                    
                    <h2 class="text-2xl lg:text-3xl font-bold text-gray-900 mb-3">{{ $type === 'company' ? 'Register Your Company' : 'Create Your Account' }}</h2>
                    <p class="text-gray-600">{{ $type === 'company' ? 'Company onboarding to connect with borrowers' : 'Sign up to start generating quality leads' }}</p>
                    <div class="mt-6 inline-flex bg-gray-100 p-1 rounded-lg">
                        <button type="button" wire:click="$set('type','individual')" class="px-4 py-2 text-sm font-medium rounded-md transition {{ $type==='individual' ? 'bg-white shadow text-black' : 'text-gray-600' }}">Individual</button>
                        <button type="button" wire:click="$set('type','company')" class="px-4 py-2 text-sm font-medium rounded-md transition {{ $type==='company' ? 'bg-white shadow text-black' : 'text-gray-600' }}">Company</button>
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
                    class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-red focus:border-transparent placeholder-gray-400 text-sm transition-all @error('first_name') border-red-500 ring-1 ring-red-500 @enderror"
                    placeholder="John"
                >
                @error('first_name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
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
                    class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-red focus:border-transparent placeholder-gray-400 text-sm transition-all @error('last_name') border-red-500 ring-1 ring-red-500 @enderror"
                    placeholder="Doe"
                >
                @error('last_name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
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
                    class="block w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-red focus:border-transparent placeholder-gray-400 text-sm transition-all @error('email') border-red-500 ring-1 ring-red-500 @enderror"
                    placeholder="john.doe@example.com"
                >
            </div>
            @error('email')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
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
                        class="block w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-red focus:border-transparent placeholder-gray-400 text-sm transition-all @error('phone') border-red-500 ring-1 ring-red-500 @enderror"
                        placeholder="+255 XXX XXX XXX"
                    >
                </div>
                @error('phone')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
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
                        class="block w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-red focus:border-transparent placeholder-gray-400 text-sm transition-all @error('nida_number') border-red-500 ring-1 ring-red-500 @enderror"
                        placeholder="19XXXXXXXXXXXXXXXX"
                    >
                </div>
                @error('nida_number')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            @else
            <div>
                <label for="company_name" class="block text-sm font-medium text-gray-700 mb-1.5">Company Name *</label>
                <input id="company_name" wire:model.live="company_name" type="text" class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-red focus:border-transparent placeholder-gray-400 text-sm @error('company_name') border-red-500 ring-1 ring-red-500 @enderror" placeholder="Acme Ltd">
                @error('company_name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            @endif

            
        </div>

        @if($type === 'company')
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label for="company_tin" class="block text-sm font-medium text-gray-700 mb-1.5">Company TIN *</label>
                <input id="company_tin" wire:model.live="company_tin" type="text" class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-red focus:border-transparent placeholder-gray-400 text-sm @error('company_tin') border-red-500 ring-1 ring-red-500 @enderror" placeholder="123-456-789">
                @error('company_tin')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="company_contact_nida" class="block text-sm font-medium text-gray-700 mb-1.5">Representative NIDA *</label>
                <input id="company_contact_nida" wire:model.live="company_contact_nida" maxlength="20" type="text" class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-red focus:border-transparent placeholder-gray-400 text-sm @error('company_contact_nida') border-red-500 ring-1 ring-red-500 @enderror" placeholder="19XXXXXXXXXXXXXXXX">
                @error('company_contact_nida')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
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
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-500 mt-1">Use 8+ characters with uppercase, number, and special character.</p>
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
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
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
                    I agree to the 
                    <a href="#" class="text-brand-red hover:text-brand-dark-red font-medium">Terms of Service</a> 
                    and 
                    <a href="#" class="text-brand-red hover:text-brand-dark-red font-medium">Privacy Policy</a>
                </label>
            </div>
        </div>
        @error('terms')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror

        <!-- Submit Button -->
        <div class="pt-2">
            <button 
                type="submit" 
                wire:loading.attr="disabled"
                wire:target="register"
                class="w-full bg-brand-red text-white py-3.5 px-4 rounded-lg font-semibold hover:bg-brand-dark-red focus:ring-4 focus:ring-brand-red/30 transition-all duration-200 transform hover:scale-[1.02] active:scale-[0.98] focus:outline-none disabled:opacity-50 disabled:cursor-not-allowed"
            >
                <span wire:loading.remove wire:target="register">{{ $type==='company' ? 'Continue as Company' : 'Create Account' }}</span>
                <span wire:loading wire:target="register">Creating Account...</span>
            </button>
        </div>

    </form>
    



                <!-- Sign In Link -->
                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-600">
                        Already have an account? 
                        <a href="{{ route('login') }}" class="font-medium text-brand-red hover:text-brand-dark-red transition-colors">
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

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const slider = document.getElementById('register-slider');
        if (!slider) return;

        const slides = slider.querySelectorAll('[data-slide]');
        const dots = slider.querySelectorAll('[data-dot]');
        let currentSlide = 0;
        let intervalId;

        const showSlide = (index) => {
            slides.forEach((slide, i) => {
                if (i === index) {
                    slide.classList.remove('opacity-0', 'scale-105', 'pointer-events-none');
                    slide.classList.add('opacity-100', 'scale-100');
                } else {
                    slide.classList.add('opacity-0', 'scale-105', 'pointer-events-none');
                    slide.classList.remove('opacity-100', 'scale-100');
                }
            });

            dots.forEach((dot, i) => {
                if (i === index) {
                    dot.classList.add('w-8', 'bg-white');
                    dot.classList.remove('w-2.5', 'bg-white/40');
                } else {
                    dot.classList.add('w-2.5', 'bg-white/40');
                    dot.classList.remove('w-8', 'bg-white');
                }
            });

            currentSlide = index;
        };

        const startRotation = () => {
            if (slides.length <= 1) return;
            intervalId = setInterval(() => {
                const nextIndex = (currentSlide + 1) % slides.length;
                showSlide(nextIndex);
            }, 6000);
        };

        dots.forEach((dot) => {
            dot.addEventListener('click', () => {
                const index = Number(dot.dataset.dot);
                showSlide(index);
                clearInterval(intervalId);
                startRotation();
            });
        });

        showSlide(0);
        startRotation();
    });
</script>
