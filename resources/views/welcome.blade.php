


<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fanikisha Market place - Hero Section</title>
    <link rel="icon" type="image/png" href="{{ asset('landing/applicationIcon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
   
   
    <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            [x-cloak] { 
                display: none !important; 
            }
            .language-dropdown { 
                z-index: 9999 !important; 
            }
        </style>
        <!-- Ensure Alpine.js starts on this page -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Wait a moment for Alpine to be available
                setTimeout(function() {
                    if (typeof window.Alpine !== 'undefined') {
                        // Alpine is available, ensure it's started
                        if (!window.Alpine.version) {
                            window.Alpine.start();
                        }
                        console.log('Alpine.js is ready');
                    } else {
                        console.error('Alpine.js is not loaded!');
                    }
                }, 100);
            });
        </script>

  
    <style>
        /* Custom Brand Color */
        .bg-brand-green {
            background-color: #C40F11;
        }
        
        .text-brand-green {
            color: #C40F11;
        }
        
        .border-brand-green {
            border-color: #C40F11;
        }
        
        .hover\:bg-brand-green:hover {
            background-color: #C40F11;
        }
        
        .hover\:text-brand-green:hover {
            color: #C40F11;
        }
        
        .hover\:bg-brand-green-light:hover {
            background-color: #999999;
        }
        
        /* Hero Slider Container - Full Width, No Top Padding */
        .hero-slider-wrapper {
            padding: 0;
            margin-top: 0;
            background: transparent;
        }
        
        /* Hero Slider - Covers full row, increased height */
        .hero-slider {
            position: relative;
            height: 45vh;
            min-height: 280px;
            overflow: hidden;
            border-radius: 0;
            box-shadow: none;
            width: 100%;
        }
        
        @media (min-width: 768px) {
            .hero-slider {
                height: 50vh;
                min-height: 340px;
            }
        }
        
        @media (min-width: 1024px) {
            .hero-slider {
                height: 55vh;
                min-height: 400px;
            }
        }
        
        /* Image covers entire row - full width, no centering box */
        .slide {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            transition: opacity 1s ease-in-out;
            background-size: cover;
            background-position: center center;
            background-repeat: no-repeat;
        }
        
        @media (min-width: 768px) {
            .slide {
                background-size: cover;
                background-position: center center;
            }
        }
        
        @media (max-width: 767px) {
            .slide {
                background-size: cover;
                background-position: center center;
            }
        }
        
        .slide.active {
            opacity: 1;
        }
        
        /* Gradient removed - no overlay */
        .slide::before {
            display: none;
        }
        
        /* Slide Content */
        .slide-content {
            position: relative;
            z-index: 2;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: flex-end;
            padding: 2rem 1.5rem;
            max-width: 1400px;
            margin: 0 auto;
        }
        
        @media (min-width: 768px) {
            .slide-content {
                padding: 3rem 3rem;
            }
        }
        
        @media (min-width: 1024px) {
            .slide-content {
                padding: 3rem 4rem;
            }
        }
        
        .slide-info {
            color: white;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.5);
            max-width: 650px;
            text-align: right;
            padding-right: 2rem;
        }
        
        @media (min-width: 1024px) {
            .slide-info {
                max-width: 700px;
                padding-right: 3rem;
            }
        }
        
        @media (max-width: 767px) {
            .slide-content {
                align-items: center;
                padding: 2rem 1rem;
            }
            
            .slide-info {
                text-align: center;
                padding-right: 0;
                max-width: 100%;
            }
        }
        
        /* Slider Dots */
        .slider-dots {
            position: absolute;
            bottom: 2rem;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 0.5rem;
            z-index: 10;
        }
        
        .slider-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.5);
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .slider-dot.active {
            background: white;
            width: 30px;
            border-radius: 5px;
        }
        
        /* Navigation Arrows */
        .slider-arrow {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            z-index: 10;
            background: rgba(255, 255, 255, 0.9);
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }
        
        .slider-arrow:hover {
            background: white;
            transform: translateY(-50%) scale(1.1);
        }
        
        .slider-arrow.prev {
            left: 1.5rem;
        }
        
        .slider-arrow.next {
            right: 1.5rem;
        }
        
        @media (min-width: 768px) {
            .slider-arrow.prev {
                left: 2rem;
            }
            
            .slider-arrow.next {
                right: 2rem;
            }
        }
        
        /* Animation */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .animate-fadeInUp {
            animation: fadeInUp 0.8s ease-out;
        }
        
        /* Feature Pills */
        .feature-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            padding: 0.75rem 1.25rem;
            border-radius: 9999px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            transition: all 0.3s ease;
        }
        
        .feature-pill:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
        }

        /* Pattern overlay removed */
        .slide::after {
            display: none;
        }
    </style>

    
</head>
<body class="bg-white text-gray-900 font-inter overflow-x-hidden">
    <!-- Navigation -->
    <nav id="mainNavbar" class="sticky top-0 z-50 bg-white transition-all duration-300" style="box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <a href="/" class="flex items-center group">
                    <img src="{{ asset('landing/redlogo.png') }}" alt="Fanikisha Market place Logo" class="h-12 lg:h-14 w-auto transition-transform duration-300 group-hover:scale-105">
                </a>
                
                <!-- Desktop Navigation -->
                <div class="hidden md:flex items-center space-x-1 lg:space-x-2">
                    <!-- Language Switcher -->
                    <div class="mr-2 relative z-50">
                        <x-language-switcher />
                    </div>
                    <a href="#eligibility" 
                       class="nav-link px-4 py-2 rounded-lg text-gray-700 hover:text-white hover:bg-red-600 transition-all duration-300 font-medium text-sm lg:text-base relative group"
                       style="color: #C40F11;">
                        <span class="relative z-10">{{ __('landing.eligibility') }}</span>
                        <span class="absolute inset-0 bg-red-600 rounded-lg scale-0 group-hover:scale-100 transition-transform duration-300 origin-center"></span>
                    </a>
                    <a href="#process" 
                       class="nav-link px-4 py-2 rounded-lg text-gray-700 hover:text-white hover:bg-red-600 transition-all duration-300 font-medium text-sm lg:text-base relative group"
                       style="color: #C40F11;">
                        <span class="relative z-10">{{ __('landing.process') }}</span>
                        <span class="absolute inset-0 bg-red-600 rounded-lg scale-0 group-hover:scale-100 transition-transform duration-300 origin-center"></span>
                    </a>
                    <a href="{{ route('blog.index') }}" 
                       class="nav-link px-4 py-2 rounded-lg text-gray-700 hover:text-white hover:bg-red-600 transition-all duration-300 font-medium text-sm lg:text-base relative group"
                       style="color: #C40F11;">
                        <span class="relative z-10">{{ __('landing.blog') }}</span>
                        <span class="absolute inset-0 bg-red-600 rounded-lg scale-0 group-hover:scale-100 transition-transform duration-300 origin-center"></span>
                    </a>
                    <a href="{{ route('login') }}" 
                       class="ml-2 px-6 py-2.5 rounded-lg text-white font-semibold text-sm lg:text-base hover:shadow-lg hover:scale-105 transition-all duration-300 relative overflow-hidden group"
                       style="background-color: #C40F11;">
                        <span class="relative z-10 flex items-center">
                        {{ __('landing.get_started') }}
                            <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                            </svg>
                        </span>
                        <span class="absolute inset-0 bg-red-800 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></span>
                    </a>
                </div>
                
                <!-- Mobile Menu Button -->
                <button class="md:hidden p-2.5 rounded-lg text-gray-700 hover:bg-gray-100 hover:text-red-600 transition-all duration-300" onclick="toggleMobileMenu()" aria-label="Toggle menu">
                    <svg id="menuIcon" class="w-6 h-6 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
            
            <!-- Mobile Navigation -->
            <div id="mobileMenu" class="hidden md:hidden border-t border-gray-200 bg-white">
                <div class="py-4 space-y-1">
                    <!-- Mobile Language Switcher -->
                    <div class="px-4 py-2">
                        <x-language-switcher />
                    </div>
                    <a href="#eligibility" 
                       onclick="toggleMobileMenu()"
                       class="block px-4 py-3 rounded-lg text-gray-700 hover:text-white hover:bg-red-600 transition-all duration-300 font-medium"
                       style="color: #C40F11;">
                        {{ __('landing.eligibility') }}
                    </a>
                    <a href="#process" 
                       onclick="toggleMobileMenu()"
                       class="block px-4 py-3 rounded-lg text-gray-700 hover:text-white hover:bg-red-600 transition-all duration-300 font-medium"
                       style="color: #C40F11;">
                        {{ __('landing.process') }}
                    </a>
                    <a href="{{ route('blog.index') }}" 
                       onclick="toggleMobileMenu()"
                       class="block px-4 py-3 rounded-lg text-gray-700 hover:text-white hover:bg-red-600 transition-all duration-300 font-medium"
                       style="color: #C40F11;">
                        {{ __('landing.blog') }}
                    </a>
                    <a href="{{ route('login') }}" 
                       onclick="toggleMobileMenu()"
                       class="block px-4 py-3 rounded-lg text-white font-semibold mt-2 transition-all duration-300"
                       style="background-color: #C40F11;">
                        {{ __('landing.get_started') }}
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section - Full Width, No Top Margin -->
    <div class="hero-slider-wrapper" style="margin-top: 0; padding-top: 0;">
        <section class="hero-slider">
            @php
                $sliders = $heroSliders ?? \App\Models\HeroSlider::active()->ordered()->get();
                $sliderCount = $sliders->count();
            @endphp
            
            @if($sliderCount > 0)
                @foreach($sliders as $index => $slider)
                    <div class="slide slide-{{ $index + 1 }} {{ $index === 0 ? 'active' : '' }}" 
                         style="background-image: url('{{ asset('storage/' . $slider->image_path) }}');">
                        <div class="slide-content">
                            <!-- Text content hidden -->
                            <div class="slide-info opacity-0 pointer-events-none">
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <!-- Fallback to default image if no sliders exist -->
                <div class="slide slide-1 active" style="background-image: url('{{ asset('landing/registerImage2.png') }}');">
                    <div class="slide-content">
                        <div class="slide-info opacity-0 pointer-events-none">
                        </div>
                    </div>
                </div>
            @endif
            
            @if($sliderCount > 1)
                <!-- Navigation Arrows -->
                <div class="slider-arrow prev" onclick="previousSlide()">
                    <svg class="w-6 h-6 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7"/>
                    </svg>
                </div>
                <div class="slider-arrow next" onclick="nextSlide()">
                    <svg class="w-6 h-6 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"/>
                    </svg>
                </div>
                
                <!-- Slider Dots -->
                <div class="slider-dots">
                    @foreach($sliders as $index => $slider)
                        <div class="slider-dot {{ $index === 0 ? 'active' : '' }}" onclick="changeSlide({{ $index }})"></div>
                    @endforeach
                </div>
            @endif
            
        </section>
    </div>



    <section id="eligibility" class="py-16 md:py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 lg:px-8">
            <!-- Section Header -->
            <div class="text-center mb-12 md:mb-16">
                <div class="mb-4">
                    <span class="inline-block bg-brand-green/10 text-brand-green px-4 py-2 rounded-full text-sm font-medium uppercase tracking-wider">
                        {{ __('landing.about_fanikisha') }}
                    </span>
                </div>
                <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold font-poppins text-black leading-tight mb-6">
                    {{ __('landing.financial_partner') }}
                    <span class="block text-brand-green">{{ __('landing.for_every_situation') }}</span>
                </h2>
                <p class="text-lg md:text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
                    {{ __('landing.financial_opportunities') }}
                </p>
            </div>

            <!-- Main Content Grid -->
            <div class="grid lg:grid-cols-2 gap-12 lg:gap-16 items-center">
                <!-- Left Side - Content -->
                <div class="space-y-8">
                    <!-- Mission Statement -->
                    <div>
                        <h3 class="text-2xl md:text-3xl font-semibold font-poppins text-black mb-4">
                            {{ __('landing.our_mission') }}
                        </h3>
                        <p class="text-gray-600 leading-relaxed mb-6">
                            {{ __('landing.mission_description_1') }}
                        </p>
                        <p class="text-gray-600 leading-relaxed">
                            {{ __('landing.mission_description_2') }}
                        </p>
                    </div>

                    <!-- Key Features -->
                    <div class="space-y-6">
                        <h4 class="text-xl font-semibold font-poppins text-black">{{ __('landing.why_choose_us') }}</h4>
                        
                        <div class="space-y-4">
                            <div class="flex items-start space-x-4">
                                <div class="w-12 h-12 bg-brand-green/10 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-6 h-6 text-brand-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h5 class="font-semibold text-black mb-2">{{ __('landing.inclusive_access') }}</h5>
                                    <p class="text-gray-600 text-sm leading-relaxed">
                                        {{ __('landing.inclusive_access_desc') }}
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-start space-x-4">
                                <div class="w-12 h-12 bg-black/10 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-6 h-6 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h5 class="font-semibold text-black mb-2">{{ __('landing.nida_verification') }}</h5>
                                    <p class="text-gray-600 text-sm leading-relaxed">
                                        {{ __('landing.nida_verification_desc') }}
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-start space-x-4">
                                <div class="w-12 h-12 bg-brand-green/10 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-6 h-6 text-brand-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h5 class="font-semibold text-black mb-2">{{ __('landing.smart_matching') }}</h5>
                                    <p class="text-gray-600 text-sm leading-relaxed">
                                        {{ __('landing.smart_matching_desc') }}
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-start space-x-4">
                                <div class="w-12 h-12 bg-black/10 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-6 h-6 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h5 class="font-semibold text-black mb-2">{{ __('landing.fast_processing') }}</h5>
                                    <p class="text-gray-600 text-sm leading-relaxed">
                                        {{ __('landing.fast_processing_desc') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- CTA -->
                    <div class="pt-4">
                        <a href="{{ route('user.register') }}" class="bg-brand-green text-white px-8 py-3 rounded-md font-semibold hover:bg-brand-green-light transition-all duration-300 flex items-center group inline-flex">
                            {{ __('landing.start_application') }}
                            <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Right Side - Visual/Stats -->
                <div class="space-y-8">
                    <!-- Main Visual Card -->
                    <div class="bg-gradient-to-br from-gray-50 to-white border border-gray-200 rounded-2xl p-8 shadow-lg">
                        <div class="text-center mb-8">
                            <div class="w-20 h-20 bg-brand-green/10 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                <svg class="w-10 h-10 text-brand-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4"></path>
                                </svg>
                            </div>
                            <h4 class="text-xl font-semibold font-poppins text-black mb-2">{{ __('landing.platform_statistics') }}</h4>
                            <p class="text-gray-600 text-sm">{{ __('landing.real_numbers') }}</p>
                        </div>

                        <!-- Stats Grid -->
                        <div class="grid grid-cols-2 gap-6">
                            <div class="text-center p-4 bg-white rounded-xl shadow-sm border border-gray-100">
                                <div class="text-2xl md:text-3xl font-bold text-brand-green mb-1">2,500+</div>
                                <div class="text-sm text-gray-600">{{ __('landing.successful') }}</div>
                                <div class="text-sm text-gray-600">{{ __('landing.applications') }}</div>
                            </div>
                            <div class="text-center p-4 bg-white rounded-xl shadow-sm border border-gray-100">
                                <div class="text-2xl md:text-3xl font-bold text-black mb-1">50+</div>
                                <div class="text-sm text-gray-600">{{ __('landing.verified') }}</div>
                                <div class="text-sm text-gray-600">{{ __('landing.lenders') }}</div>
                            </div>
                            <div class="text-center p-4 bg-white rounded-xl shadow-sm border border-gray-100">
                                <div class="text-2xl md:text-3xl font-bold text-brand-green mb-1">98%</div>
                                <div class="text-sm text-gray-600">{{ __('landing.success') }}</div>
                                <div class="text-sm text-gray-600">{{ __('landing.rate') }}</div>
                            </div>
                            <div class="text-center p-4 bg-white rounded-xl shadow-sm border border-gray-100">
                                <div class="text-2xl md:text-3xl font-bold text-black mb-1">24hrs</div>
                                <div class="text-sm text-gray-600">{{ __('landing.average') }}</div>
                                <div class="text-sm text-gray-600">{{ __('landing.approval') }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Trust Indicators -->
                    <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
                        <h4 class="text-lg font-semibold font-poppins text-black mb-6 text-center">{{ __('landing.trusted_secure') }}</h4>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="text-center">
                                <div class="w-12 h-12 bg-black/10 rounded-lg flex items-center justify-center mx-auto mb-3">
                                    <svg class="w-6 h-6 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                    </svg>
                                </div>
                                <div class="text-sm font-medium text-black">{{ __('landing.ssl_encrypted') }}</div>
                                <div class="text-xs text-gray-500">{{ __('landing.bank_level_security') }}</div>
                            </div>
                            <div class="text-center">
                                <div class="w-12 h-12 bg-brand-green/10 rounded-lg flex items-center justify-center mx-auto mb-3">
                                    <svg class="w-6 h-6 text-brand-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div class="text-sm font-medium text-black">{{ __('landing.nida_verified') }}</div>
                                <div class="text-xs text-gray-500">{{ __('landing.identity_protection') }}</div>
                            </div>
                            <div class="text-center">
                                <div class="w-12 h-12 bg-black/10 rounded-lg flex items-center justify-center mx-auto mb-3">
                                    <svg class="w-6 h-6 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div class="text-sm font-medium text-black">{{ __('landing.support_247') }}</div>
                                <div class="text-xs text-gray-500">{{ __('landing.always_here') }}</div>
                            </div>
                            <div class="text-center">
                                <div class="w-12 h-12 bg-brand-green/10 rounded-lg flex items-center justify-center mx-auto mb-3">
                                    <svg class="w-6 h-6 text-brand-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                </div>
                                <div class="text-sm font-medium text-black">{{ __('landing.privacy_protected') }}</div>
                                <div class="text-xs text-gray-500">{{ __('landing.data_never_shared') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> 
    </section>





    <!-- Process Section -->
    <section id="process" class=" md:py-8 bg-white">
        <div class="max-w-7xl mx-auto px-4 lg:px-4">
           

        

            <!-- Mobile Steps -->
            <div class="md:hidden space-y-2">
                
                <!-- Step 1 Mobile -->
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-brand-green rounded-full flex items-center justify-center">
                        <span class="text-white font-bold">1</span>
                    </div>
                    <div class="flex-1 bg-gray-50 rounded-lg p-4">
                        <h3 class="font-semibold text-black">{{ __('landing.step_register') }}</h3>
                        <p class="text-sm text-gray-600">{{ __('landing.step_register_desc') }}</p>
                    </div>
                </div>

                <!-- Step 2 Mobile -->
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-brand-green rounded-full flex items-center justify-center">
                        <span class="text-white font-bold">2</span>
                    </div>
                    <div class="flex-1 bg-gray-50 rounded-lg p-4">
                        <h3 class="font-semibold text-black">{{ __('landing.step_apply') }}</h3>
                        <p class="text-sm text-gray-600">{{ __('landing.step_apply_desc') }}</p>
                    </div>
                </div>

                <!-- Step 3 Mobile -->
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-brand-green rounded-full flex items-center justify-center">
                        <span class="text-white font-bold">3</span>
                    </div>
                    <div class="flex-1 bg-gray-50 rounded-lg p-4">
                        <h3 class="font-semibold text-black">{{ __('landing.step_match') }}</h3>
                        <p class="text-sm text-gray-600">{{ __('landing.step_match_desc') }}</p>
                    </div>
                </div>

                <!-- Step 4 Mobile -->
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-brand-green rounded-full flex items-center justify-center">
                        <span class="text-white font-bold">4</span>
                    </div>
                    <div class="flex-1 bg-gray-50 rounded-lg p-4">
                        <h3 class="font-semibold text-black">{{ __('landing.step_get_funded') }}</h3>
                        <p class="text-sm text-gray-600">{{ __('landing.step_get_funded_desc') }}</p>
                    </div>
                </div>

            </div>

            <!-- FAQ Section -->
            <div class="mt-1 md:mt-4">
                <div class="bg-white rounded-2xl p-8 md:p-12">
                    <h3 class="text-2xl md:text-3xl font-semibold font-poppins text-black text-center mb-8 md:mb-12">
                        {{ __('landing.faq_title') }}
                    </h3>
                    
                    <div class="max-w-4xl mx-auto space-y-4">
                        
                        <!-- FAQ Item 1 -->
                        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                            <button onclick="toggleFAQ(1)" class="w-full px-6 py-4 text-left flex items-center justify-between hover:bg-gray-50 transition-colors">
                                <span class="font-semibold text-gray-900">{{ __('landing.faq_what_is') }}</span>
                                <svg id="faq-icon-1" class="w-5 h-5 text-brand-green transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div id="faq-content-1" class="hidden px-6 pb-4">
                                <p class="text-gray-600 leading-relaxed">
                                    {{ __('landing.faq_what_is_answer') }}
                                </p>
                            </div>
                        </div>

                        <!-- FAQ Item 2 -->
                        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                            <button onclick="toggleFAQ(2)" class="w-full px-6 py-4 text-left flex items-center justify-between hover:bg-gray-50 transition-colors">
                                <span class="font-semibold text-gray-900">{{ __('landing.faq_who_can_apply') }}</span>
                                <svg id="faq-icon-2" class="w-5 h-5 text-brand-green transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div id="faq-content-2" class="hidden px-6 pb-4">
                                <p class="text-gray-600 leading-relaxed mb-3">
                                    {{ __('landing.faq_who_can_apply_intro') }}
                                </p>
                                <ul class="list-disc list-inside text-gray-600 space-y-2 ml-4">
                                    <li>{{ __('landing.faq_employed') }}</li>
                                    <li>{{ __('landing.faq_self_employed') }}</li>
                                    <li>{{ __('landing.faq_non_employed') }}</li>
                                    <li>{{ __('landing.faq_age_requirement') }}</li>
                                    <li>{{ __('landing.faq_residents') }}</li>
                                </ul>
                            </div>
                        </div>

                        <!-- FAQ Item 3 -->
                        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                            <button onclick="toggleFAQ(3)" class="w-full px-6 py-4 text-left flex items-center justify-between hover:bg-gray-50 transition-colors">
                                <span class="font-semibold text-gray-900">{{ __('landing.faq_how_process') }}</span>
                                <svg id="faq-icon-3" class="w-5 h-5 text-brand-green transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div id="faq-content-3" class="hidden px-6 pb-4">
                                <p class="text-gray-600 leading-relaxed mb-3">
                                    {{ __('landing.faq_how_process_intro') }}
                                </p>
                                <ol class="list-decimal list-inside text-gray-600 space-y-2 ml-4">
                                    <li><strong>{{ __('landing.step_register') }}:</strong> {{ __('landing.faq_step1') }}</li>
                                    <li><strong>{{ __('landing.step_apply') }}:</strong> {{ __('landing.faq_step2') }}</li>
                                    <li><strong>{{ __('landing.step_match') }}:</strong> {{ __('landing.faq_step3') }}</li>
                                    <li><strong>{{ __('landing.step_get_funded') }}:</strong> {{ __('landing.faq_step4') }}</li>
                                </ol>
                            </div>
                        </div>

                        <!-- FAQ Item 4 -->
                        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                            <button onclick="toggleFAQ(4)" class="w-full px-6 py-4 text-left flex items-center justify-between hover:bg-gray-50 transition-colors">
                                <span class="font-semibold text-gray-900">{{ __('landing.faq_nida_what') }}</span>
                                <svg id="faq-icon-4" class="w-5 h-5 text-brand-green transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div id="faq-content-4" class="hidden px-6 pb-4">
                                <p class="text-gray-600 leading-relaxed">
                                    {{ __('landing.faq_nida_answer') }}
                                </p>
                    </div>
                        </div>

                        <!-- FAQ Item 5 -->
                        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                            <button onclick="toggleFAQ(5)" class="w-full px-6 py-4 text-left flex items-center justify-between hover:bg-gray-50 transition-colors">
                                <span class="font-semibold text-gray-900">{{ __('landing.faq_approval_time') }}</span>
                                <svg id="faq-icon-5" class="w-5 h-5 text-brand-green transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div id="faq-content-5" class="hidden px-6 pb-4">
                                <p class="text-gray-600 leading-relaxed">
                                    {{ __('landing.faq_approval_time_answer') }}
                                </p>
                </div>
            </div>

                        <!-- FAQ Item 6 -->
                        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                            <button onclick="toggleFAQ(6)" class="w-full px-6 py-4 text-left flex items-center justify-between hover:bg-gray-50 transition-colors">
                                <span class="font-semibold text-gray-900">{{ __('landing.faq_documents') }}</span>
                                <svg id="faq-icon-6" class="w-5 h-5 text-brand-green transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div id="faq-content-6" class="hidden px-6 pb-4">
                                <p class="text-gray-600 leading-relaxed mb-3">
                                    {{ __('landing.faq_documents_intro') }}
                                </p>
                                <ul class="list-disc list-inside text-gray-600 space-y-2 ml-4">
                                    <li>{{ __('landing.faq_doc_nida') }}</li>
                                    <li>{{ __('landing.faq_doc_income') }}</li>
                                    <li>{{ __('landing.faq_doc_photo') }}</li>
                                    <li>{{ __('landing.faq_doc_address') }}</li>
                                    <li>{{ __('landing.faq_doc_additional') }}</li>
                                </ul>
                            </div>
                        </div>

                        <!-- FAQ Item 7 -->
                        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                            <button onclick="toggleFAQ(7)" class="w-full px-6 py-4 text-left flex items-center justify-between hover:bg-gray-50 transition-colors">
                                <span class="font-semibold text-gray-900">{{ __('landing.faq_credit_score') }}</span>
                                <svg id="faq-icon-7" class="w-5 h-5 text-brand-green transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div id="faq-content-7" class="hidden px-6 pb-4">
                                <p class="text-gray-600 leading-relaxed">
                                    {{ __('landing.faq_credit_score_answer') }}
                                </p>
                            </div>
                        </div>

                        <!-- FAQ Item 8 -->
                        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                            <button onclick="toggleFAQ(8)" class="w-full px-6 py-4 text-left flex items-center justify-between hover:bg-gray-50 transition-colors">
                                <span class="font-semibold text-gray-900">{{ __('landing.faq_fees') }}</span>
                                <svg id="faq-icon-8" class="w-5 h-5 text-brand-green transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div id="faq-content-8" class="hidden px-6 pb-4">
                                <p class="text-gray-600 leading-relaxed">
                                    {{ __('landing.faq_fees_answer') }}
                                </p>
                            </div>
                        </div>

                        <!-- FAQ Item 9 -->
                        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                            <button onclick="toggleFAQ(9)" class="w-full px-6 py-4 text-left flex items-center justify-between hover:bg-gray-50 transition-colors">
                                <span class="font-semibold text-gray-900">{{ __('landing.faq_privacy') }}</span>
                                <svg id="faq-icon-9" class="w-5 h-5 text-brand-green transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div id="faq-content-9" class="hidden px-6 pb-4">
                                <p class="text-gray-600 leading-relaxed">
                                    {{ __('landing.faq_privacy_answer') }}
                                </p>
                            </div>
                        </div>

                        <!-- FAQ Item 10 -->
                        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                            <button onclick="toggleFAQ(10)" class="w-full px-6 py-4 text-left flex items-center justify-between hover:bg-gray-50 transition-colors">
                                <span class="font-semibold text-gray-900">{{ __('landing.faq_help') }}</span>
                                <svg id="faq-icon-10" class="w-5 h-5 text-brand-green transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div id="faq-content-10" class="hidden px-6 pb-4">
                                <p class="text-gray-600 leading-relaxed">
                                    {{ __('landing.faq_help_intro') }}
                                </p>
                                <ul class="list-disc list-inside text-gray-600 space-y-2 ml-4 mt-3">
                                    <li>{{ __('landing.faq_email') }}</li>
                                    <li>{{ __('landing.faq_phone') }}</li>
                                    <li>{{ __('landing.faq_live_chat') }}</li>
                                    <li>{{ __('landing.faq_help_center') }}</li>
                                </ul>
                                <p class="text-gray-600 leading-relaxed mt-3">
                                    {{ __('landing.faq_help_closing') }}
                                </p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

           
        </div>
    </section>

    <!-- Blog Section -->
    <section id="blog" class="py-16 md:py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 lg:px-8">
            <div class="text-center mb-12 md:mb-16">
                <div class="mb-4">
                    <span class="inline-block bg-brand-green/10 text-brand-green px-4 py-2 rounded-full text-sm font-medium uppercase tracking-wider">
                        {{ __('landing.latest_news') }}
                    </span>
                </div>
                <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold font-poppins text-black leading-tight mb-6">
                    {{ __('landing.blog_insights') }}
                </h2>
                <p class="text-lg md:text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
                    {{ __('landing.blog_description') }}
                </p>
            </div>

            @php
                $latestPosts = \App\Models\BlogPost::with('author')
                    ->published()
                    ->orderBy('published_at', 'desc')
                    ->limit(3)
                    ->get();
            @endphp

            @if($latestPosts->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
                    @foreach($latestPosts as $post)
                        <div class="bg-white rounded-2xl shadow-sm overflow-hidden hover:shadow-lg transition-shadow border border-gray-100">
                            @if($post->featured_image)
                                <a href="{{ route('blog.show', $post->slug) }}">
                                    <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}" 
                                         class="w-full h-48 object-cover">
                                </a>
                            @endif
                            <div class="p-6">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-xs text-gray-500">{{ $post->published_at->format('M d, Y') }}</span>
                                    @if($post->is_featured)
                                        <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs font-medium rounded">{{ __('landing.featured') }}</span>
                                    @endif
                                </div>
                                <a href="{{ route('blog.show', $post->slug) }}">
                                    <h3 class="text-xl font-bold text-gray-900 mb-2 hover:text-brand-green transition-colors line-clamp-2">{{ $post->title }}</h3>
                                </a>
                                @if($post->excerpt)
                                    <p class="text-gray-600 text-sm mb-4 line-clamp-3">{{ $post->excerpt }}</p>
                                @endif
                                <div class="flex items-center justify-between text-sm text-gray-500">
                                    <span>{{ __('landing.by') }} {{ $post->author->name ?? 'Admin' }}</span>
                                    <a href="{{ route('blog.show', $post->slug) }}" class="text-brand-green hover:text-brand-green-light font-medium">
                                        {{ __('landing.read_more') }} →
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="text-center">
                    <a href="{{ route('blog.index') }}" class="inline-flex items-center bg-brand-green text-white px-8 py-3 rounded-lg font-semibold hover:bg-brand-green-light transition-all duration-300">
                        {{ __('landing.view_all_posts') }}
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </a>
                </div>
            @else
                <div class="text-center py-12">
                    <p class="text-gray-500 text-lg">{{ __('landing.no_posts') }}</p>
                </div>
            @endif
        </div>
    </section>




    <!-- Footer -->
    <footer class="bg-white text-gray-900 py-12 border-t border-gray-200">
        <div class="max-w-7xl mx-auto px-4 lg:px-8">
            <!-- Main Footer Content -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
                
                <!-- Brand Section -->
                <div class="md:col-span-1">
                    <a href="/" class="inline-block mb-4">
                        <img src="{{ asset('landing/redlogo.png') }}" alt="Fanikisha Market place Logo" class="h-10 w-auto">
                    </a>
                    <p class="text-gray-600 text-sm leading-relaxed mb-4">
                        {{ __('landing.connecting_borrowers') }}
                    </p>
                    <div class="flex space-x-4">
                        <!-- Social Icons -->
                        <a href="#" class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center hover:bg-brand-green hover:text-white transition-all text-gray-700">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                            </svg>
                        </a>
                        <a href="#" class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center hover:bg-brand-green hover:text-white transition-all text-gray-700">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M22.46 6c-.77.35-1.6.58-2.46.69.88-.53 1.56-1.37 1.88-2.38-.83.5-1.75.85-2.72 1.05C18.37 4.5 17.26 4 16 4c-2.35 0-4.27 1.92-4.27 4.29 0 .34.04.67.11.98C8.28 9.09 5.11 7.38 3 4.79c-.37.63-.58 1.37-.58 2.15 0 1.49.75 2.81 1.91 3.56-.71 0-1.37-.2-1.95-.5v.03c0 2.08 1.48 3.82 3.44 4.21a4.22 4.22 0 0 1-1.93.07 4.28 4.28 0 0 0 4 2.98 8.521 8.521 0 0 1-5.33 1.84c-.34 0-.68-.02-1.02-.06C3.44 20.29 5.7 21 8.12 21 16 21 20.33 14.46 20.33 8.79c0-.19 0-.37-.01-.56.84-.6 1.56-1.36 2.14-2.23z"/>
                            </svg>
                        </a>
                        <a href="#" class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center hover:bg-brand-green hover:text-white transition-all text-gray-700">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div>
                    <h4 class="font-semibold font-poppins text-lg mb-4 text-gray-900">{{ __('landing.quick_links') }}</h4>
                    <ul class="space-y-2">
                        <li><a href="#home" class="text-gray-600 hover:text-brand-green transition-colors text-sm">{{ __('landing.home') }}</a></li>
                        <li><a href="#eligibility" class="text-gray-600 hover:text-brand-green transition-colors text-sm">{{ __('landing.eligibility') }}</a></li>
                        <li><a href="#process" class="text-gray-600 hover:text-brand-green transition-colors text-sm">{{ __('landing.how_it_works') }}</a></li>
                        <li><a href="#" class="text-gray-600 hover:text-brand-green transition-colors text-sm">{{ __('landing.lenders') }}</a></li>
                    </ul>
                </div>

                <!-- Support -->
                <div>
                    <h4 class="font-semibold font-poppins text-lg mb-4 text-gray-900">{{ __('landing.support') }}</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-600 hover:text-brand-green transition-colors text-sm">{{ __('landing.help_center') }}</a></li>
                        <li><a href="#" class="text-gray-600 hover:text-brand-green transition-colors text-sm">{{ __('landing.contact_us') }}</a></li>
                        <li><a href="#" class="text-gray-600 hover:text-brand-green transition-colors text-sm">{{ __('landing.live_chat') }}</a></li>
                        <li><a href="#" class="text-gray-600 hover:text-brand-green transition-colors text-sm">{{ __('landing.faqs') }}</a></li>
                        <li><a href="#" class="text-gray-600 hover:text-brand-green transition-colors text-sm">{{ __('landing.status') }}</a></li>
                    </ul>
                </div>

                <!-- Contact -->
                <div>
                    <h4 class="font-semibold font-poppins text-lg mb-4 text-gray-900">{{ __('landing.contact') }}</h4>
                    <div class="space-y-3">
                        <div class="flex items-center space-x-3">
                            <svg class="w-5 h-5 text-brand-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                            <span class="text-gray-600 text-sm">+255 123 456 789</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <svg class="w-5 h-5 text-brand-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            <span class="text-gray-600 text-sm">info@leadgenerator.com</span>
                        </div>
                        <div class="flex items-start space-x-3">
                            <svg class="w-5 h-5 text-brand-green mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span class="text-gray-600 text-sm">Dar es Salaam, Tanzania</span>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Bottom Footer -->
            <div class="border-t border-gray-200 pt-8">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <div class="text-gray-500 text-sm mb-4 md:mb-0">
                        © 2025 Fanikisha Market place. All rights reserved.
                    </div>
                    <div class="flex flex-wrap gap-6">
                        <a href="#" class="text-gray-500 hover:text-brand-green text-sm transition-colors">Privacy Policy</a>
                        <a href="#" class="text-gray-500 hover:text-brand-green text-sm transition-colors">Terms of Service</a>
                        <a href="#" class="text-gray-500 hover:text-brand-green text-sm transition-colors">Cookie Policy</a>
                        <a href="#" class="text-gray-500 hover:text-brand-green text-sm transition-colors">Compliance</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>



    <!-- JavaScript for Mobile Menu and Navbar Scroll Effect -->
    <script>
        function toggleMobileMenu() {
            const mobileMenu = document.getElementById('mobileMenu');
            const menuIcon = document.getElementById('menuIcon');
            mobileMenu.classList.toggle('hidden');
            
            // Animate menu icon
            if (!mobileMenu.classList.contains('hidden')) {
                menuIcon.style.transform = 'rotate(90deg)';
            } else {
                menuIcon.style.transform = 'rotate(0deg)';
            }
        }

        // Close mobile menu when clicking outside
        document.addEventListener('click', function(event) {
            const mobileMenu = document.getElementById('mobileMenu');
            const menuButton = event.target.closest('button');
            
            if (!mobileMenu.contains(event.target) && !menuButton) {
                mobileMenu.classList.add('hidden');
                const menuIcon = document.getElementById('menuIcon');
                if (menuIcon) {
                    menuIcon.style.transform = 'rotate(0deg)';
                }
            }
        });

        // Navbar scroll effect - add shadow on scroll
        let lastScroll = 0;
        const navbar = document.getElementById('mainNavbar');
        
        window.addEventListener('scroll', function() {
            const currentScroll = window.pageYOffset;
            
            if (currentScroll > 10) {
                navbar.style.boxShadow = '0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06)';
            } else {
                navbar.style.boxShadow = '0 1px 3px 0 rgba(0, 0, 0, 0.1)';
            }
            
            lastScroll = currentScroll;
        });

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                const href = this.getAttribute('href');
                if (href !== '#' && href.length > 1) {
                    e.preventDefault();
                    const target = document.querySelector(href);
                    if (target) {
                        const offsetTop = target.offsetTop - 80; // Account for navbar height
                        window.scrollTo({
                            top: offsetTop,
                            behavior: 'smooth'
                        });
                        // Close mobile menu if open
                        const mobileMenu = document.getElementById('mobileMenu');
                        if (!mobileMenu.classList.contains('hidden')) {
                            toggleMobileMenu();
                        }
                    }
                }
            });
        });

        // FAQ Accordion Functionality
        function toggleFAQ(index) {
            const content = document.getElementById('faq-content-' + index);
            const icon = document.getElementById('faq-icon-' + index);
            
            if (content.classList.contains('hidden')) {
                // Close all other FAQs
                for (let i = 1; i <= 10; i++) {
                    if (i !== index) {
                        const otherContent = document.getElementById('faq-content-' + i);
                        const otherIcon = document.getElementById('faq-icon-' + i);
                        if (otherContent && !otherContent.classList.contains('hidden')) {
                            otherContent.classList.add('hidden');
                            if (otherIcon) {
                                otherIcon.classList.remove('rotate-180');
                            }
                        }
                    }
                }
                // Open clicked FAQ
                content.classList.remove('hidden');
                icon.classList.add('rotate-180');
            } else {
                // Close clicked FAQ
                content.classList.add('hidden');
                icon.classList.remove('rotate-180');
            }
        }

        // Hero Slider Functionality
        let currentSlide = 0;
        const slides = document.querySelectorAll('.slide');
        const dots = document.querySelectorAll('.slider-dot');
        
        // Debug: Log slider info
        console.log('Hero Slider initialized:', {
            slidesCount: slides.length,
            dotsCount: dots.length,
            slides: Array.from(slides).map(s => s.className)
        });
        
        // Define functions globally for onclick handlers
        window.changeSlide = function(index) {
            if (slides.length === 0) return;
            if (slides[currentSlide]) {
                slides[currentSlide].classList.remove('active');
            }
            if (dots.length > 0 && dots[currentSlide]) {
                dots[currentSlide].classList.remove('active');
            }
            currentSlide = index;
            if (slides[currentSlide]) {
                slides[currentSlide].classList.add('active');
            }
            if (dots.length > 0 && dots[currentSlide]) {
                dots[currentSlide].classList.add('active');
            }
        };
        
        window.nextSlide = function() {
            if (slides.length <= 1) return;
            const next = (currentSlide + 1) % slides.length;
            window.changeSlide(next);
        };
        
        window.previousSlide = function() {
            if (slides.length <= 1) return;
            const prev = (currentSlide - 1 + slides.length) % slides.length;
            window.changeSlide(prev);
        };
        
        // Auto-advance slides every 5 seconds (only if multiple slides)
        if (slides.length > 1) {
            setInterval(() => {
                window.nextSlide();
            }, 5000);
        }
        
        // Keyboard navigation
        document.addEventListener('keydown', (e) => {
            if (slides.length > 1) {
                if (e.key === 'ArrowLeft') window.previousSlide();
                if (e.key === 'ArrowRight') window.nextSlide();
            }
        });
        
        // Touch/Swipe support
        if (slides.length > 1) {
            let touchStartX = 0;
            let touchEndX = 0;
            
            const heroSlider = document.querySelector('.hero-slider');
            if (heroSlider) {
                heroSlider.addEventListener('touchstart', e => {
                    touchStartX = e.changedTouches[0].screenX;
                });
                
                heroSlider.addEventListener('touchend', e => {
                    touchEndX = e.changedTouches[0].screenX;
                    handleSwipe();
                });
                
                function handleSwipe() {
                    if (slides.length > 1) {
                        if (touchEndX < touchStartX - 50) {
                            window.nextSlide();
                        }
                        if (touchEndX > touchStartX + 50) {
                            window.previousSlide();
                        }
                    }
                }
            }
        }
    </script>
</body>
</html>