


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lead Generator - Hero Section</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
   
   
    <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

  
    <style>
        /* Custom Brand Color */
        .bg-brand-green {
            background-color: #19733B;
        }
        
        .text-brand-green {
            color: #19733B;
        }
        
        .border-brand-green {
            border-color: #19733B;
        }
        
        .hover\:bg-brand-green:hover {
            background-color: #19733B;
        }
        
        .hover\:text-brand-green:hover {
            color: #19733B;
        }
        
        .hover\:bg-brand-green-light:hover {
            background-color: #1a7f40;
        }
        
        /* Hero Slider Container with Padding */
        .hero-slider-wrapper {
            padding: 1rem;
            background: #f9fafb;
        }
        
        @media (min-width: 768px) {
            .hero-slider-wrapper {
                padding: 2rem;
            }
        }
        
        @media (min-width: 1024px) {
            .hero-slider-wrapper {
                padding: 2rem 4rem;
            }
        }
        
        /* Hero Slider */
        .hero-slider {
            position: relative;
            height: 500px;
            overflow: hidden;
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
        }
        
        @media (min-width: 768px) {
            .hero-slider {
                height: 600px;
                border-radius: 32px;
            }
        }
        
        .slide {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            transition: opacity 1s ease-in-out;
            background-size: cover;
            background-position: center;
            background-image: url('{{ asset("landing/image.png") }}');
        }
        
        .slide.active {
            opacity: 1;
        }
        
        .slide::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(to right, rgba(0, 0, 0, 0.4) 0%, rgba(0, 0, 0, 0.2) 50%, rgba(0, 0, 0, 0) 100%);
            border-radius: inherit;
        }
        
        /* Color overlays for different slides - gradient from left to right */
        .slide-1::before {
            background: linear-gradient(to left, rgba(25, 115, 59, 0.7) 0%, rgba(25, 115, 59, 0.4) 50%, rgba(0, 0, 0, 0) 100%);
        }
        
        .slide-2::before {
            background: linear-gradient(to left, rgba(25, 115, 59, 0.7) 0%, rgba(25, 115, 59, 0.4) 50%, rgba(0, 0, 0, 0) 100%);
        }
        
        .slide-3::before {
            background: linear-gradient(to left, rgba(25, 115, 59, 0.7) 0%, rgba(25, 115, 59, 0.4) 50%, rgba(0, 0, 0, 0) 100%);
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

        /* Pattern overlay for visual interest */
        .slide::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: 
                radial-gradient(circle at 20% 50%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(255, 255, 255, 0.1) 0%, transparent 50%);
            pointer-events: none;
        }
    </style>
</head>
<body class="bg-white text-gray-900 font-inter overflow-x-hidden">
    <!-- Navigation -->
    <nav class="bg-white/95 backdrop-blur-md py-3 sticky top-0 z-50 border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 lg:px-8">
            <div class="flex justify-between items-center">
                <!-- Logo -->
                <a href="/" class="flex items-center">
                    <img src="{{ asset('logo/logoOnWhitebg.png') }}" alt="Lead Generator Logo" class="h-12 lg:h-14 w-auto">
                </a>
                
                <!-- Desktop Navigation -->
                <div class="hidden md:flex items-center space-x-8 lg:space-x-12">
                    <a href="#eligibility" class="text-gray-600 hover:text-brand-green transition-colors font-medium">Eligibility</a>
                    <a href="#process" class="text-gray-600 hover:text-brand-green transition-colors font-medium">Process</a>
                    <a href="{{ route('login') }}" class="bg-brand-green text-white px-6 py-2 rounded-md text-sm hover:bg-brand-green-light transition-all font-semibold">
                        Get Started
                    </a>
                </div>
                
                <!-- Mobile Menu Button -->
                <button class="md:hidden p-2" onclick="toggleMobileMenu()">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
            
            <!-- Mobile Navigation -->
            <div id="mobileMenu" class="hidden md:hidden mt-4 pb-4 border-t border-gray-100">
                <div class="space-y-3 pt-4">
                    <a href="#eligibility" class="block text-gray-600 hover:text-brand-green transition-colors font-medium">Eligibility</a>
                    <a href="#process" class="block text-gray-600 hover:text-brand-green transition-colors font-medium">Process</a>
                    <a href="{{ route('login') }}" class="w-full text-left bg-brand-green text-white px-6 py-2 rounded-md text-sm hover:bg-brand-green-light transition-all font-semibold">
                        Get Started
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section with Padding/Margin -->
    <div class="hero-slider-wrapper">
        <section class="hero-slider">
            
            <!-- Slide 1 - Green Theme -->
            <div class="slide slide-1 active">
                <div class="slide-content">
                    <div class="slide-info animate-fadeInUp">
                        <span class="inline-block bg-brand-green text-white px-5 py-2.5 rounded-full text-sm font-bold mb-6 shadow-lg">
                            NIDA Verified Platform
                        </span>
                        <h1 class="text-2xl md:text-4xl lg:text-6xl xl:text-7xl font-bold font-poppins mb-6 leading-tight">
                            Unlock Your <br/>
                            <span style="color: #4ade80;">Financial Freedom</span>
                        </h1>
                        <p class="text-lg md:text-xl lg:text-2xl text-white/90 mb-8 leading-relaxed">
                            Connect with 50+ trusted lenders across Tanzania.<br/>
                            Get approved in as fast as 24 hours.
                        </p>
                        
                        <!-- Feature Pills -->
                        <div class="flex flex-wrap gap-3 mb-8">
                            <div class="feature-pill">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span class="font-semibold text-sm md:text-base">No Hidden Fees</span>
                            </div>
                            <div class="feature-pill">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span class="font-semibold text-sm md:text-base">Flexible Terms</span>
                            </div>
                            <div class="feature-pill">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span class="font-semibold text-sm md:text-base">100% Secure</span>
                            </div>
                        </div>
                        
                        <!-- CTA Buttons -->
                        <div class="flex flex-col sm:flex-row gap-4">
                            <a href="{{ route('user.register') }}" class="bg-white text-brand-green px-6 md:px-8 py-3 md:py-4 rounded-xl font-bold text-base md:text-lg hover:bg-gray-50 transition-all inline-flex items-center justify-center gap-2 shadow-xl group">
                                Get Started Free
                                <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                </svg>
                            </a>
                            <a href="#eligibility" class="bg-white/20 backdrop-blur-md text-white px-6 md:px-8 py-3 md:py-4 rounded-xl font-bold text-base md:text-lg hover:bg-white/30 transition-all inline-flex items-center justify-center gap-2 border border-white/30">
                                Learn More
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Slide 2 - Green Theme -->
            <div class="slide slide-2">
                <div class="slide-content">
                    <div class="slide-info">
                        <span class="inline-block bg-brand-green text-white px-5 py-2.5 rounded-full text-sm font-bold mb-6 shadow-lg">
                            Lightning Fast Approval
                        </span>
                        <h1 class="text-3xl md:text-4xl lg:text-6xl xl:text-7xl font-bold font-poppins mb-6 leading-tight">
                            Get Approved <br/>
                            <span style="color: #4ade80;">In 24 Hours</span>
                        </h1>
                        <p class="text-lg md:text-xl lg:text-2xl text-white/90 mb-8 leading-relaxed">
                            Our AI-powered matching system connects you with <br/>
                            the right lender instantly. No waiting, no stress.
                        </p>
                        
                        <!-- Feature Pills -->
                        <div class="flex flex-wrap gap-3 mb-8">
                            <div class="feature-pill">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/>
                                </svg>
                                <span class="font-semibold text-sm md:text-base">50+ Lenders</span>
                            </div>
                            <div class="feature-pill">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"/>
                                </svg>
                                <span class="font-semibold text-sm md:text-base">Instant Decisions</span>
                            </div>
                            <div class="feature-pill">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                </svg>
                                <span class="font-semibold text-sm md:text-base">24/7 Available</span>
                            </div>
                        </div>
                        
                        <!-- CTA Buttons -->
                        <div class="flex flex-col sm:flex-row gap-4">
                            <a href="{{ route('user.register') }}" class="bg-white text-brand-green px-6 md:px-8 py-3 md:py-4 rounded-xl font-bold text-base md:text-lg hover:bg-gray-50 transition-all inline-flex items-center justify-center gap-2 shadow-xl group">
                                Apply Now
                                <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                </svg>
                            </a>
                            <a href="#process" class="bg-white/20 backdrop-blur-md text-white px-6 md:px-8 py-3 md:py-4 rounded-xl font-bold text-base md:text-lg hover:bg-white/30 transition-all inline-flex items-center justify-center gap-2 border border-white/30">
                                See How It Works
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Slide 3 - Green Theme -->
            <div class="slide slide-3">
                <div class="slide-content">
                    <div class="slide-info">
                        <span class="inline-block bg-brand-green text-white px-5 py-2.5 rounded-full text-sm font-bold mb-6 shadow-lg">
                            Military-Grade Security
                        </span>
                        <h1 class="text-3xl md:text-4xl lg:text-6xl xl:text-7xl font-bold font-poppins mb-6 leading-tight">
                            Your Data is <br/>
                            <span style="color: #4ade80;">100% Protected</span>
                        </h1>
                        <p class="text-lg md:text-xl lg:text-2xl text-white/90 mb-8 leading-relaxed">
                            Bank-level 256-bit encryption keeps your personal <br/>
                            and financial information completely secure.
                        </p>
                        
                        <!-- Feature Pills -->
                        <div class="flex flex-wrap gap-3 mb-8">
                            <div class="feature-pill">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span class="font-semibold text-sm md:text-base">SSL Encrypted</span>
                            </div>
                            <div class="feature-pill">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 8a6 6 0 01-7.743 5.743L10 14l-1 1-1 1H6v2H2v-4l4.257-4.257A6 6 0 1118 8zm-6-4a1 1 0 100 2 2 2 0 012 2 1 1 0 102 0 4 4 0 00-4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span class="font-semibold text-sm md:text-base">Privacy First</span>
                            </div>
                            <div class="feature-pill">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span class="font-semibold text-sm md:text-base">Verified Safe</span>
                            </div>
                        </div>
                        
                        <!-- CTA Buttons -->
                        <div class="flex flex-col sm:flex-row gap-4">
                            <a href="{{ route('user.register') }}" class="bg-white text-brand-green px-6 md:px-8 py-3 md:py-4 rounded-xl font-bold text-base md:text-lg hover:bg-gray-50 transition-all inline-flex items-center justify-center gap-2 shadow-xl group">
                                Start Secure Application
                                <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                </svg>
                            </a>
                            <a href="#eligibility" class="bg-white/20 backdrop-blur-md text-white px-6 md:px-8 py-3 md:py-4 rounded-xl font-bold text-base md:text-lg hover:bg-white/30 transition-all inline-flex items-center justify-center gap-2 border border-white/30">
                                Security Details
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
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
                <div class="slider-dot active" onclick="changeSlide(0)"></div>
                <div class="slider-dot" onclick="changeSlide(1)"></div>
                <div class="slider-dot" onclick="changeSlide(2)"></div>
            </div>
            
        </section>
    </div>



    <section id="eligibility" class="py-16 md:py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 lg:px-8">
            <!-- Section Header -->
            <div class="text-center mb-12 md:mb-16">
                <div class="mb-4">
                    <span class="inline-block bg-brand-green/10 text-brand-green px-4 py-2 rounded-full text-sm font-medium uppercase tracking-wider">
                        About LeadGenerator
                    </span>
                </div>
                <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold font-poppins text-black leading-tight mb-6">
                    Your Financial Partner
                    <span class="block text-brand-green">For Every Situation</span>
                </h2>
                <p class="text-lg md:text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
                    We believe everyone deserves access to financial opportunities, regardless of their employment status or traditional banking relationships.
                </p>
            </div>

            <!-- Main Content Grid -->
            <div class="grid lg:grid-cols-2 gap-12 lg:gap-16 items-center">
                <!-- Left Side - Content -->
                <div class="space-y-8">
                    <!-- Mission Statement -->
                    <div>
                        <h3 class="text-2xl md:text-3xl font-semibold font-poppins text-black mb-4">
                            Our Mission
                        </h3>
                        <p class="text-gray-600 leading-relaxed mb-6">
                            Traditional banking often leaves many people behind. We're changing that by connecting borrowers with lenders who understand diverse financial situations and evaluate applications based on your unique circumstances, not just employment status.
                        </p>
                        <p class="text-gray-600 leading-relaxed">
                            Through our secure, NIDA-verified platform, we make the loan application process transparent, fast, and accessible to everyone.
                        </p>
                    </div>

                    <!-- Key Features -->
                    <div class="space-y-6">
                        <h4 class="text-xl font-semibold font-poppins text-black">Why Choose Us?</h4>
                        
                        <div class="space-y-4">
                            <div class="flex items-start space-x-4">
                                <div class="w-12 h-12 bg-brand-green/10 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-6 h-6 text-brand-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h5 class="font-semibold text-black mb-2">Inclusive Access</h5>
                                    <p class="text-gray-600 text-sm leading-relaxed">
                                        No employment requirements. We serve employed, self-employed, and non-employed individuals.
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
                                    <h5 class="font-semibold text-black mb-2">NIDA Verification</h5>
                                    <p class="text-gray-600 text-sm leading-relaxed">
                                        Secure identity verification through NIDA integration for maximum trust and security.
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
                                    <h5 class="font-semibold text-black mb-2">Smart Matching</h5>
                                    <p class="text-gray-600 text-sm leading-relaxed">
                                        AI-powered system matches you with the most suitable lenders based on your profile.
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
                                    <h5 class="font-semibold text-black mb-2">Fast Processing</h5>
                                    <p class="text-gray-600 text-sm leading-relaxed">
                                        Get matched with lenders and receive approval decisions within 24 hours.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- CTA -->
                    <div class="pt-4">
                        <a href="{{ route('user.register') }}" class="bg-brand-green text-white px-8 py-3 rounded-md font-semibold hover:bg-brand-green-light transition-all duration-300 flex items-center group inline-flex">
                            Start Your Application
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
                            <h4 class="text-xl font-semibold font-poppins text-black mb-2">Platform Statistics</h4>
                            <p class="text-gray-600 text-sm">Real numbers from our community</p>
                        </div>

                        <!-- Stats Grid -->
                        <div class="grid grid-cols-2 gap-6">
                            <div class="text-center p-4 bg-white rounded-xl shadow-sm border border-gray-100">
                                <div class="text-2xl md:text-3xl font-bold text-brand-green mb-1">2,500+</div>
                                <div class="text-sm text-gray-600">Successful</div>
                                <div class="text-sm text-gray-600">Applications</div>
                            </div>
                            <div class="text-center p-4 bg-white rounded-xl shadow-sm border border-gray-100">
                                <div class="text-2xl md:text-3xl font-bold text-black mb-1">50+</div>
                                <div class="text-sm text-gray-600">Verified</div>
                                <div class="text-sm text-gray-600">Lenders</div>
                            </div>
                            <div class="text-center p-4 bg-white rounded-xl shadow-sm border border-gray-100">
                                <div class="text-2xl md:text-3xl font-bold text-brand-green mb-1">98%</div>
                                <div class="text-sm text-gray-600">Success</div>
                                <div class="text-sm text-gray-600">Rate</div>
                            </div>
                            <div class="text-center p-4 bg-white rounded-xl shadow-sm border border-gray-100">
                                <div class="text-2xl md:text-3xl font-bold text-black mb-1">24hrs</div>
                                <div class="text-sm text-gray-600">Average</div>
                                <div class="text-sm text-gray-600">Approval</div>
                            </div>
                        </div>
                    </div>

                    <!-- Trust Indicators -->
                    <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
                        <h4 class="text-lg font-semibold font-poppins text-black mb-6 text-center">Trusted & Secure</h4>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="text-center">
                                <div class="w-12 h-12 bg-black/10 rounded-lg flex items-center justify-center mx-auto mb-3">
                                    <svg class="w-6 h-6 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                    </svg>
                                </div>
                                <div class="text-sm font-medium text-black">SSL Encrypted</div>
                                <div class="text-xs text-gray-500">Bank-level security</div>
                            </div>
                            <div class="text-center">
                                <div class="w-12 h-12 bg-brand-green/10 rounded-lg flex items-center justify-center mx-auto mb-3">
                                    <svg class="w-6 h-6 text-brand-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div class="text-sm font-medium text-black">NIDA Verified</div>
                                <div class="text-xs text-gray-500">Identity protection</div>
                            </div>
                            <div class="text-center">
                                <div class="w-12 h-12 bg-black/10 rounded-lg flex items-center justify-center mx-auto mb-3">
                                    <svg class="w-6 h-6 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div class="text-sm font-medium text-black">24/7 Support</div>
                                <div class="text-xs text-gray-500">Always here to help</div>
                            </div>
                            <div class="text-center">
                                <div class="w-12 h-12 bg-brand-green/10 rounded-lg flex items-center justify-center mx-auto mb-3">
                                    <svg class="w-6 h-6 text-brand-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                </div>
                                <div class="text-sm font-medium text-black">Privacy Protected</div>
                                <div class="text-xs text-gray-500">Data never shared</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> 
    </section>





    <!-- Process Section -->
    <section id="process" class=" md:py-10 bg-white">
        <div class="max-w-7xl mx-auto px-4 lg:px-8">
           

        

            <!-- Mobile Steps -->
            <div class="md:hidden space-y-2">
                
                <!-- Step 1 Mobile -->
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-brand-green rounded-full flex items-center justify-center">
                        <span class="text-white font-bold">1</span>
                    </div>
                    <div class="flex-1 bg-gray-50 rounded-lg p-4">
                        <h3 class="font-semibold text-black">Register</h3>
                        <p class="text-sm text-gray-600">Create account & verify with NIDA</p>
                    </div>
                </div>

                <!-- Step 2 Mobile -->
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-brand-green rounded-full flex items-center justify-center">
                        <span class="text-white font-bold">2</span>
                    </div>
                    <div class="flex-1 bg-gray-50 rounded-lg p-4">
                        <h3 class="font-semibold text-black">Apply</h3>
                        <p class="text-sm text-gray-600">Fill form & upload documents</p>
                    </div>
                </div>

                <!-- Step 3 Mobile -->
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-brand-green rounded-full flex items-center justify-center">
                        <span class="text-white font-bold">3</span>
                    </div>
                    <div class="flex-1 bg-gray-50 rounded-lg p-4">
                        <h3 class="font-semibold text-black">Match</h3>
                        <p class="text-sm text-gray-600">We find best lenders for you</p>
                    </div>
                </div>

                <!-- Step 4 Mobile -->
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-brand-green rounded-full flex items-center justify-center">
                        <span class="text-white font-bold">4</span>
                    </div>
                    <div class="flex-1 bg-gray-50 rounded-lg p-4">
                        <h3 class="font-semibold text-black">Get Funded</h3>
                        <p class="text-sm text-gray-600">Choose lender & receive money</p>
                    </div>
                </div>

            </div>

            <!-- Why Choose Us -->
            <div class="mt-4 md:mt-20">
                <div class="bg-gray-50 rounded-2xl p-8 md:p-12">
                    <h3 class="text-2xl md:text-3xl font-semibold font-poppins text-black text-center mb-8 md:mb-12">
                        Why Choose Us
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        
                        <!-- Fast -->
                        <div class="text-center">
                            <div class="w-16 h-16 bg-brand-green/10 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-brand-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h4 class="text-lg font-semibold text-black mb-3">Fast</h4>
                            <p class="text-gray-600 text-sm">Complete in 30 minutes</p>
                        </div>

                        <!-- Secure -->
                        <div class="text-center">
                            <div class="w-16 h-16 bg-black/10 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                </svg>
                            </div>
                            <h4 class="text-lg font-semibold text-black mb-3">Secure</h4>
                            <p class="text-gray-600 text-sm">NIDA verified protection</p>
                        </div>

                        <!-- Transparent -->
                        <div class="text-center">
                            <div class="w-16 h-16 bg-brand-green/10 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-brand-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                            </div>
                            <h4 class="text-lg font-semibold text-black mb-3">Transparent</h4>
                            <p class="text-gray-600 text-sm">No hidden fees</p>
                        </div>

                    </div>
                </div>
            </div>

           
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
                        <img src="{{ asset('logo/logoOnWhitebg.png') }}" alt="Lead Generator Logo" class="h-10 w-auto">
                    </a>
                    <p class="text-gray-600 text-sm leading-relaxed mb-4">
                        Connecting borrowers with verified lenders. Loans for everyone, regardless of employment status.
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
                    <h4 class="font-semibold font-poppins text-lg mb-4 text-gray-900">Quick Links</h4>
                    <ul class="space-y-2">
                        <li><a href="#home" class="text-gray-600 hover:text-brand-green transition-colors text-sm">Home</a></li>
                        <li><a href="#eligibility" class="text-gray-600 hover:text-brand-green transition-colors text-sm">Eligibility</a></li>
                        <li><a href="#process" class="text-gray-600 hover:text-brand-green transition-colors text-sm">How It Works</a></li>
                        <li><a href="#" class="text-gray-600 hover:text-brand-green transition-colors text-sm">Lenders</a></li>
                    </ul>
                </div>

                <!-- Support -->
                <div>
                    <h4 class="font-semibold font-poppins text-lg mb-4 text-gray-900">Support</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-600 hover:text-brand-green transition-colors text-sm">Help Center</a></li>
                        <li><a href="#" class="text-gray-600 hover:text-brand-green transition-colors text-sm">Contact Us</a></li>
                        <li><a href="#" class="text-gray-600 hover:text-brand-green transition-colors text-sm">Live Chat</a></li>
                        <li><a href="#" class="text-gray-600 hover:text-brand-green transition-colors text-sm">FAQs</a></li>
                        <li><a href="#" class="text-gray-600 hover:text-brand-green transition-colors text-sm">Status</a></li>
                    </ul>
                </div>

                <!-- Contact -->
                <div>
                    <h4 class="font-semibold font-poppins text-lg mb-4 text-gray-900">Contact</h4>
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
                        © 2025 LeadGenerator. All rights reserved.
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



    <!-- JavaScript for Mobile Menu -->
    <script>
        function toggleMobileMenu() {
            const mobileMenu = document.getElementById('mobileMenu');
            mobileMenu.classList.toggle('hidden');
        }

        // Close mobile menu when clicking outside
        document.addEventListener('click', function(event) {
            const mobileMenu = document.getElementById('mobileMenu');
            const menuButton = event.target.closest('button');
            
            if (!mobileMenu.contains(event.target) && !menuButton) {
                mobileMenu.classList.add('hidden');
            }
        });

        // Hero Slider Functionality
        let currentSlide = 0;
        const slides = document.querySelectorAll('.slide');
        const dots = document.querySelectorAll('.slider-dot');
        
        function changeSlide(index) {
            slides[currentSlide].classList.remove('active');
            dots[currentSlide].classList.remove('active');
            currentSlide = index;
            slides[currentSlide].classList.add('active');
            dots[currentSlide].classList.add('active');
        }
        
        function nextSlide() {
            const next = (currentSlide + 1) % slides.length;
            changeSlide(next);
        }
        
        function previousSlide() {
            const prev = (currentSlide - 1 + slides.length) % slides.length;
            changeSlide(prev);
        }
        
        // Auto-advance slides every 5 seconds
        setInterval(() => {
            nextSlide();
        }, 5000);
        
        // Keyboard navigation
        document.addEventListener('keydown', (e) => {
            if (e.key === 'ArrowLeft') previousSlide();
            if (e.key === 'ArrowRight') nextSlide();
        });
        
        // Touch/Swipe support
        let touchStartX = 0;
        let touchEndX = 0;
        
        const heroSlider = document.querySelector('.hero-slider');
        
        heroSlider.addEventListener('touchstart', e => {
            touchStartX = e.changedTouches[0].screenX;
        });
        
        heroSlider.addEventListener('touchend', e => {
            touchEndX = e.changedTouches[0].screenX;
            handleSwipe();
        });
        
        function handleSwipe() {
            if (touchEndX < touchStartX - 50) {
                nextSlide();
            }
            if (touchEndX > touchStartX + 50) {
                previousSlide();
            }
        }
    </script>
</body>
</html>