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
        /* Desktop diagonal effects */
        @media (min-width: 768px) {
            .diagonal-split {
                clip-path: polygon(0 0, 65% 0, 50% 100%, 0 100%);
            }
            .diagonal-content {
                clip-path: polygon(45% 0, 100% 0, 100% 100%, 60% 100%);
            }
        }
        
        /* Mobile - no diagonal, simple layout */
        @media (max-width: 767px) {
            .diagonal-split {
                clip-path: none;
                background: #1f2937 !important; /* Simple gray background */
            }
            .diagonal-content {
                clip-path: none;
                background: #f9fafb !important; /* Simple light background */
            }
            .mobile-simple {
                background: none !important;
            }
        }
        
        .floating-animation {
            animation: float 3s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
    </style>
</head>
<body class="bg-white text-gray-900 font-inter overflow-x-hidden">
    <!-- Navigation -->
    <nav class="bg-white/95 backdrop-blur-md py-4 sticky top-0 z-50 border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 lg:px-8">
            <div class="flex justify-between items-center">
                <div class="text-xl lg:text-2xl font-bold text-black font-poppins">
                    Lead<span class="text-brand-red">Generator</span>
                </div>
                
                <!-- Desktop Navigation -->
                <div class="hidden md:flex items-center space-x-8 lg:space-x-12">
                    <a href="#eligibility" class="text-gray-600 hover:text-brand-red transition-colors font-medium">Eligibility</a>
                    <a href="#process" class="text-gray-600 hover:text-brand-red transition-colors font-medium">Process</a>
                    <a href="{{ route('login') }}" class="bg-brand-red text-white px-6 py-2 rounded-md text-sm hover:bg-red-700 transition-all font-semibold">
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
                    <a href="#eligibility" class="block text-gray-600 hover:text-brand-red transition-colors font-medium">Eligibility</a>
                    <a href="#process" class="block text-gray-600 hover:text-brand-red transition-colors font-medium">Process</a>
                    <a href="{{ route('login') }}" class="w-full text-left bg-brand-red text-white px-6 py-2 rounded-md text-sm hover:bg-red-700 transition-all font-semibold">
                        Get Started
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative min-h-screen flex items-center overflow-hidden">
        <!-- Background Container - Hidden on mobile -->
        <div class="absolute inset-0 hidden md:block">
            <!-- Left Side - Text Background with Diagonal -->
            <div class="diagonal-split absolute inset-0 bg-gradient-to-br from-black via-gray-800 to-brand-red"></div>
            <!-- Right Side - Visual Background -->
            <div class="diagonal-content absolute inset-0 bg-gradient-to-l from-gray-50 via-white to-transparent"></div>
        </div>

        <!-- Content Container -->
        <div class="relative z-10 w-full">
            <div class="max-w-7xl mx-auto px-4 lg:px-8">
                <!-- Mobile Layout - Simple Stacked -->
                <div class="block md:hidden">
                    <!-- Mobile Text Section -->
                    <div class="bg-gray-800 text-white px-6 py-12 rounded-t-xl">
                        <div class="text-center">
                            <span class="inline-block bg-red-600 text-white px-4 py-2 rounded-full text-sm font-medium mb-6">
                                Available Now


                            </span>
                            <h1 class="text-3xl font-bold font-poppins leading-tight mb-4">
                                Get Your Perfect <span class="text-brand-red">Loan</span>
                            </h1>
                            <p class="text-lg text-gray-200 mb-2">
                                Employment Status Doesn't Matter
                            </p>
                            <p class="text-gray-300 mb-8 leading-relaxed">
                                Our verified lenders are ready to help you achieve your financial goals.
                            </p>
                            <div class="space-y-4">
                                <a  href="{{ route('user.register') }}" class="w-full bg-white text-black px-8 py-4 rounded-md text-lg font-semibold hover:bg-gray-100 transition-all">
                                    Apply Now
                                </a>
                                <button class="w-full border-2 border-white/50 text-white px-8 py-4 rounded-md text-lg font-semibold hover:bg-white/10 transition-all">
                                    Learn More
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Mobile Visual Section -->
                    <div class="bg-gray-50 px-6 py-12 rounded-b-xl">
                        <div class="text-center">
                            <!-- Simple Mobile Visual -->
                            <div class="w-48 h-48 mx-auto rounded-full bg-white border-4 border-gray-200 flex items-center justify-center shadow-lg">
                                <svg class="w-20 h-20 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            
                            <!-- Simple Features -->
                            <div class="mt-8 grid grid-cols-2 gap-4 max-w-xs mx-auto">
                                <div class="text-center">
                                    <div class="w-12 h-12 bg-brand-red rounded-full flex items-center justify-center mx-auto mb-2">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                    <span class="text-sm text-gray-600">NIDA Verified</span>
                                </div>
                                <div class="text-center">
                                    <div class="w-12 h-12 bg-black rounded-full flex items-center justify-center mx-auto mb-2">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                        </svg>
                                    </div>
                                    <span class="text-sm text-gray-600">Fast Approval</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Desktop Layout - Diagonal Design -->
                <div class="hidden md:grid grid-cols-2 gap-16 items-center min-h-screen py-20">
                    
                    <!-- Left Side - Text Content -->
                    <div class="text-white pr-16">
                        <!-- Badge -->
                        <div class="mb-8">
                            <span class="inline-block bg-red-600 backdrop-blur-sm text-white px-4 py-2 rounded-full text-sm font-medium uppercase tracking-wider border border-white/30">
                                Available Now
                            </span>
                        </div>
                        
                        <!-- Main Headline -->
                        <h1 class="font-bold font-poppins leading-tight mb-8">
                            <span class="block text-5xl xl:text-6xl">
                                Get Your
                            </span>
                            <span class="block text-5xl xl:text-6xl">
                                Perfect <span class="text-red-600">Loan</span>
                            </span>
                            <span class="block text-2xl xl:text-3xl font-light text-gray-200 mt-2">
                                Employment Status Doesn't Matter
                            </span>
                        </h1>
                        
                        <!-- Description -->
                        <p class="text-lg xl:text-xl text-gray-200 mb-10 leading-relaxed max-w-lg">
                            Whether you're employed, self-employed, or between jobs, 
                            our verified lenders understand your unique situation and 
                            are ready to help you achieve your financial goals.
                        </p>
                        
                        <!-- Call to Action Buttons -->
                        <div class="flex flex-col sm:flex-row gap-4">

                            <a  href="{{ route('user.register') }}"class="bg-white text-black px-8 py-4 rounded-md text-lg font-semibold hover:bg-gray-100 transition-all duration-300 flex items-center justify-center group">
                                Apply Now
                                <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                </svg>
                                </a>

                            <button class="border-2 border-white/50 text-white px-8 py-4 rounded-md text-lg font-semibold hover:bg-white/10 hover:border-white transition-all duration-300">
                                Learn More
                            </button>
                        </div>
                        
                        <!-- Features List -->
                        <div class="mt-12 grid grid-cols-2 gap-4 max-w-lg">
                            <div class="flex items-center space-x-3">
                                <div class="w-6 h-6 bg-white/20 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                                <span class="text-sm text-gray-200">NIDA Verified</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="w-6 h-6 bg-white/20 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                                <span class="text-sm text-gray-200">Fast Approval</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="w-6 h-6 bg-white/20 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                                <span class="text-sm text-gray-200">Secure Platform</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="w-6 h-6 bg-white/20 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                                <span class="text-sm text-gray-200">Trusted Lenders</span>
                            </div>
                        </div>
                    </div>

                    <!-- Right Side - Visual Content -->
                    <div class="flex items-center justify-end">
                        <div class="relative">
                            <!-- Main Circle -->
                            <div class="w-96 h-96 rounded-full bg-gradient-to-br from-gray-100 to-gray-200 border-4 border-white shadow-2xl flex items-center justify-center">
                                <!-- User Avatar -->
                                <div class="w-48 h-48 rounded-full bg-white border-4 border-gray-200 flex items-center justify-center shadow-lg">
                                    <svg class="w-24 h-24 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                            </div>
                            
                            <!-- Floating Elements -->
                            <div class="absolute -top-6 -right-6 w-16 h-16 bg-brand-red rounded-xl flex items-center justify-center shadow-lg floating-animation">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                            </div>
                            
                            <div class="absolute -bottom-6 -left-6 w-16 h-16 bg-black rounded-xl flex items-center justify-center shadow-lg floating-animation" style="animation-delay: 1s;">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>

                            <div class="absolute top-8 -left-8 w-12 h-12 bg-white border-2 border-gray-300 rounded-full flex items-center justify-center shadow-md floating-animation" style="animation-delay: 2s;">
                                <svg class="w-6 h-6 text-brand-red" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </div>

                            <div class="absolute bottom-16 right-12 w-12 h-12 bg-gray-800 rounded-lg flex items-center justify-center shadow-md floating-animation" style="animation-delay: 0.5s;">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                </svg>
                            </div>

                            <!-- Stats Cards -->
                            <div class="absolute -top-12 left-8 bg-white border-2 border-gray-200 rounded-lg p-3 text-center shadow-lg">
                                <div class="text-lg font-bold text-black">50+</div>
                                <div class="text-xs text-gray-600">Lenders</div>
                            </div>
                            
                            <div class="absolute bottom-8 right-8 bg-white border-2 border-gray-200 rounded-lg p-3 text-center shadow-lg">
                                <div class="text-lg font-bold text-black">98%</div>
                                <div class="text-xs text-gray-600">Success</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>



    <section id="eligibility" class="py-16 md:py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 lg:px-8">
            <!-- Section Header -->
            <div class="text-center mb-12 md:mb-16">
                <div class="mb-4">
                    <span class="inline-block bg-brand-red/10 text-brand-red px-4 py-2 rounded-full text-sm font-medium uppercase tracking-wider">
                        About LeadGenerator
                    </span>
                </div>
                <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold font-poppins text-black leading-tight mb-6">
                    Your Financial Partner
                    <span class="block text-brand-red">For Every Situation</span>
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
                                <div class="w-12 h-12 bg-brand-red/10 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-6 h-6 text-brand-red" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                <div class="w-12 h-12 bg-brand-red/10 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-6 h-6 text-brand-red" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                        <button class="bg-brand-red text-white px-8 py-3 rounded-md font-semibold hover:bg-red-700 transition-all duration-300 flex items-center group">
                            Start Your Application
                            <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Right Side - Visual/Stats -->
                <div class="space-y-8">
                    <!-- Main Visual Card -->
                    <div class="bg-gradient-to-br from-gray-50 to-white border border-gray-200 rounded-2xl p-8 shadow-lg">
                        <div class="text-center mb-8">
                            <div class="w-20 h-20 bg-brand-red/10 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                <svg class="w-10 h-10 text-brand-red" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4"></path>
                                </svg>
                            </div>
                            <h4 class="text-xl font-semibold font-poppins text-black mb-2">Platform Statistics</h4>
                            <p class="text-gray-600 text-sm">Real numbers from our community</p>
                        </div>

                        <!-- Stats Grid -->
                        <div class="grid grid-cols-2 gap-6">
                            <div class="text-center p-4 bg-white rounded-xl shadow-sm border border-gray-100">
                                <div class="text-2xl md:text-3xl font-bold text-brand-red mb-1">2,500+</div>
                                <div class="text-sm text-gray-600">Successful</div>
                                <div class="text-sm text-gray-600">Applications</div>
                            </div>
                            <div class="text-center p-4 bg-white rounded-xl shadow-sm border border-gray-100">
                                <div class="text-2xl md:text-3xl font-bold text-black mb-1">50+</div>
                                <div class="text-sm text-gray-600">Verified</div>
                                <div class="text-sm text-gray-600">Lenders</div>
                            </div>
                            <div class="text-center p-4 bg-white rounded-xl shadow-sm border border-gray-100">
                                <div class="text-2xl md:text-3xl font-bold text-brand-red mb-1">98%</div>
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
                                <div class="w-12 h-12 bg-brand-red/10 rounded-lg flex items-center justify-center mx-auto mb-3">
                                    <svg class="w-6 h-6 text-brand-red" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                <div class="w-12 h-12 bg-brand-red/10 rounded-lg flex items-center justify-center mx-auto mb-3">
                                    <svg class="w-6 h-6 text-brand-red" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                    <div class="w-12 h-12 bg-brand-red rounded-full flex items-center justify-center">
                        <span class="text-white font-bold">1</span>
                    </div>
                    <div class="flex-1 bg-gray-50 rounded-lg p-4">
                        <h3 class="font-semibold text-black">Register</h3>
                        <p class="text-sm text-gray-600">Create account & verify with NIDA</p>
                    </div>
                </div>

                <!-- Step 2 Mobile -->
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-brand-red rounded-full flex items-center justify-center">
                        <span class="text-white font-bold">2</span>
                    </div>
                    <div class="flex-1 bg-gray-50 rounded-lg p-4">
                        <h3 class="font-semibold text-black">Apply</h3>
                        <p class="text-sm text-gray-600">Fill form & upload documents</p>
                    </div>
                </div>

                <!-- Step 3 Mobile -->
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-brand-red rounded-full flex items-center justify-center">
                        <span class="text-white font-bold">3</span>
                    </div>
                    <div class="flex-1 bg-gray-50 rounded-lg p-4">
                        <h3 class="font-semibold text-black">Match</h3>
                        <p class="text-sm text-gray-600">We find best lenders for you</p>
                    </div>
                </div>

                <!-- Step 4 Mobile -->
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-brand-red rounded-full flex items-center justify-center">
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
                            <div class="w-16 h-16 bg-brand-red/10 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-brand-red" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                            <div class="w-16 h-16 bg-brand-red/10 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-brand-red" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 lg:px-8">
            <!-- Main Footer Content -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
                
                <!-- Brand Section -->
                <div class="md:col-span-1">
                    <div class="text-2xl font-bold font-poppins mb-4">
                        Lead<span class="text-brand-red">Generator</span>
                    </div>
                    <p class="text-gray-300 text-sm leading-relaxed mb-4">
                        Connecting borrowers with verified lenders. Loans for everyone, regardless of employment status.
                    </p>
                    <div class="flex space-x-4">
                        <!-- Social Icons -->
                        <a href="#" class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center hover:bg-brand-red transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                            </svg>
                        </a>
                        <a href="#" class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center hover:bg-brand-red transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M22.46 6c-.77.35-1.6.58-2.46.69.88-.53 1.56-1.37 1.88-2.38-.83.5-1.75.85-2.72 1.05C18.37 4.5 17.26 4 16 4c-2.35 0-4.27 1.92-4.27 4.29 0 .34.04.67.11.98C8.28 9.09 5.11 7.38 3 4.79c-.37.63-.58 1.37-.58 2.15 0 1.49.75 2.81 1.91 3.56-.71 0-1.37-.2-1.95-.5v.03c0 2.08 1.48 3.82 3.44 4.21a4.22 4.22 0 0 1-1.93.07 4.28 4.28 0 0 0 4 2.98 8.521 8.521 0 0 1-5.33 1.84c-.34 0-.68-.02-1.02-.06C3.44 20.29 5.7 21 8.12 21 16 21 20.33 14.46 20.33 8.79c0-.19 0-.37-.01-.56.84-.6 1.56-1.36 2.14-2.23z"/>
                            </svg>
                        </a>
                        <a href="#" class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center hover:bg-brand-red transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div>
                    <h4 class="font-semibold font-poppins text-lg mb-4">Quick Links</h4>
                    <ul class="space-y-2">
                        <li><a href="#home" class="text-gray-300 hover:text-white transition-colors text-sm">Home</a></li>
                        <li><a href="#eligibility" class="text-gray-300 hover:text-white transition-colors text-sm">Eligibility</a></li>
                        <li><a href="#process" class="text-gray-300 hover:text-white transition-colors text-sm">How It Works</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white transition-colors text-sm">Lenders</a></li>
                    </ul>
                </div>

                <!-- Support -->
                <div>
                    <h4 class="font-semibold font-poppins text-lg mb-4">Support</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-300 hover:text-white transition-colors text-sm">Help Center</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white transition-colors text-sm">Contact Us</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white transition-colors text-sm">Live Chat</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white transition-colors text-sm">FAQs</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white transition-colors text-sm">Status</a></li>
                    </ul>
                </div>

                <!-- Contact -->
                <div>
                    <h4 class="font-semibold font-poppins text-lg mb-4">Contact</h4>
                    <div class="space-y-3">
                        <div class="flex items-center space-x-3">
                            <svg class="w-5 h-5 text-brand-red" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                            <span class="text-gray-300 text-sm">+255 123 456 789</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <svg class="w-5 h-5 text-brand-red" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            <span class="text-gray-300 text-sm">info@leadgenerator.com</span>
                        </div>
                        <div class="flex items-start space-x-3">
                            <svg class="w-5 h-5 text-brand-red mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span class="text-gray-300 text-sm">Dar es Salaam, Tanzania</span>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Bottom Footer -->
            <div class="border-t border-gray-800 pt-8">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <div class="text-gray-400 text-sm mb-4 md:mb-0">
                        © 2025 LeadGenerator. All rights reserved.
                    </div>
                    <div class="flex flex-wrap gap-6">
                        <a href="#" class="text-gray-400 hover:text-white text-sm transition-colors">Privacy Policy</a>
                        <a href="#" class="text-gray-400 hover:text-white text-sm transition-colors">Terms of Service</a>
                        <a href="#" class="text-gray-400 hover:text-white text-sm transition-colors">Cookie Policy</a>
                        <a href="#" class="text-gray-400 hover:text-white text-sm transition-colors">Compliance</a>
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
    </script>
</body>
</html>