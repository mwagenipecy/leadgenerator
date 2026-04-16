


<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fanikisha Market place - Hero Section</title>
    <link rel="icon" type="image/png" href="{{ asset('landing/applicationIcon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Poppins:wght@300;400;500;600;700;800&family=Hubballi&display=swap" rel="stylesheet">
   
   
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
            height: 48vh;
            min-height: 280px;
            overflow: hidden;
            border-radius: 0;
            box-shadow: none;
            width: 100%;
        }
        
        @media (min-width: 768px) {
            .hero-slider {
                height: 53vh;
                min-height: 340px;
            }
        }
        
        @media (min-width: 1024px) {
            .hero-slider {
                height: 58vh;
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
            background-position: center top;
            background-repeat: no-repeat;
        }
        
        @media (min-width: 768px) {
            .slide {
                background-size: cover;
                background-position: center top;
            }
        }
        
        @media (max-width: 767px) {
            .slide {
                background-size: cover;
                background-position: center top;
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

        /* Floating transparent navbar */
        .floating-navbar-shell {
            border-radius: 1.15rem;
            background: linear-gradient(120deg, rgba(255, 255, 255, 0.2), rgba(255, 255, 255, 0.08));
            border: 1px solid rgba(255, 255, 255, 0.4);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            box-shadow: 0 10px 24px rgba(0, 0, 0, 0.09);
            transition: all 0.3s ease;
        }

        .floating-navbar-shell.navbar-scrolled {
            background: linear-gradient(120deg, rgba(255, 255, 255, 0.24), rgba(255, 255, 255, 0.12));
            border-color: rgba(255, 255, 255, 0.48);
            box-shadow: 0 14px 30px rgba(0, 0, 0, 0.12);
        }

        .floating-navbar-shell .nav-scroll-link,
        .floating-navbar-shell #language-switcher-button {
            color: #ffffff;
        }

        .floating-navbar-shell.navbar-scrolled .nav-scroll-link,
        .floating-navbar-shell.navbar-scrolled #language-switcher-button {
            color: #C40F11;
        }

        .partners-track {
            display: flex;
            align-items: center;
            gap: 1.25rem;
            width: max-content;
            animation: scrollPartners 30s linear infinite;
            padding: 0 1rem;
        }

        .partners-track-static {
            width: 100%;
            animation: none;
            justify-content: center;
            flex-wrap: wrap;
            padding: 0;
        }

        .partners-marquee:hover .partners-track {
            animation-play-state: paused;
        }

        .partner-pill {
            padding: 0.65rem 1.15rem;
            border-radius: 9999px;
            border: 1px solid #e5e7eb;
            background: #ffffff;
            color: #111827;
            font-weight: 600;
            font-size: 0.9rem;
            white-space: nowrap;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
        }

        @keyframes scrollPartners {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }

    </style>

    
</head>
<body class="bg-white text-gray-900 font-inter overflow-x-hidden">
    @if(session('help_success'))
        <div class="fixed top-24 right-4 z-[80] bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded-lg shadow-md">
            {{ session('help_success') }}
        </div>
    @endif

    <x-landing.navbar :is-home="true" />

    <!-- Hero Section - Full Width, No Top Margin -->
    <div class="hero-slider-wrapper">
        <section class="hero-slider">
            @php
                $sliders = $heroSliders ?? \App\Models\HeroSlider::active()->ordered()->get();
                $sliderCount = $sliders->count();
                $locale = app()->getLocale();
                $isSwahili = in_array($locale, ['sw', 'swahili'], true);
            @endphp
            
            @if($sliderCount > 0)
                @foreach($sliders as $index => $slider)
                    @php
                        $englishImage = $slider->image_path_en ?? $slider->image_path;
                        $swahiliImage = $slider->image_path_sw;
                        $selectedImage = $isSwahili ? ($swahiliImage ?: $englishImage) : $englishImage;
                    @endphp
                    <div class="slide slide-{{ $index + 1 }} {{ $index === 0 ? 'active' : '' }}" 
                         style="background-image: url('{{ asset('storage/' . $selectedImage) }}');">
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

    <section class="py-6 md:py-8 bg-white">
        <div class="max-w-7xl mx-auto px-4 lg:px-8">
            <div class="text-center mb-3 md:mb-4">
                <h3 class="text-xl md:text-2xl font-bold font-poppins text-gray-900">Trusted Partners</h3>
                <p class="mt-1 text-sm text-gray-600">Leading institutions we work with.</p>
            </div>
            @php
                $partners = \Illuminate\Support\Facades\Schema::hasTable('partners')
                    ? \App\Models\Partner::active()->ordered()->get()
                    : collect();
                $useMarquee = $partners->count() > 5;
                $displayPartners = $useMarquee ? $partners->concat($partners) : $partners;
            @endphp
            <div class="partners-marquee overflow-hidden relative">
                <div class="partners-track {{ $useMarquee ? '' : 'partners-track-static' }}">
                    @forelse($displayPartners as $partner)
                    <a
                        href="{{ $partner->website_url }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="inline-flex h-20 w-52 items-center justify-center px-4 transition-opacity duration-200 hover:opacity-100 opacity-95"
                        title="Visit {{ $partner->name }} website">
                        <img src="{{ asset('storage/' . $partner->logo_path) }}" alt="{{ $partner->name }} logo" loading="lazy" class="max-h-14 w-auto object-contain">
                    </a>
                    @empty
                    <span class="partner-pill">Partners will appear here after admin setup.</span>
                    @endforelse
                </div>
            </div>
        </div>
    </section>



    @php
        $activeLoanCategories = \Illuminate\Support\Facades\Schema::hasTable('loan_categories')
            ? \App\Models\LoanCategory::active()->ordered()->get()
            : collect();
    @endphp

    @if($activeLoanCategories->isNotEmpty())
        <section class="py-10 md:py-12 bg-white border-b border-gray-100">
            <div class="max-w-7xl mx-auto px-4 lg:px-8">
                <div class="text-center mb-8 md:mb-10">
                    <p class="text-xs tracking-[0.3em] uppercase text-brand-green font-semibold mb-3">
                        {{ __('landing.what_we_offer') }}
                    </p>
                    <h3 class="text-3xl md:text-4xl font-bold leading-tight text-[#1F3868] font-poppins">
                        {{ __('landing.apply_for_your_loan_today') }}
                    </h3>
                    <div class="mt-4 flex items-center justify-center gap-2">
                        <div class="h-px w-14 bg-brand-green/40"></div>
                        <div class="w-2 h-2 rounded-full bg-brand-green/70"></div>
                        <div class="h-px w-14 bg-brand-green/40"></div>
                    </div>
                </div>

                <div class="relative">
                    <button
                        type="button"
                        onclick="scrollLendingCards('left')"
                        class="absolute left-0 top-1/2 -translate-y-1/2 z-10 bg-white/95 border border-gray-200 rounded-full p-2 shadow-md hover:bg-red-50 transition-colors"
                        aria-label="Scroll cards left">
                        <svg class="w-5 h-5 text-brand-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </button>

                <div id="lendingCardsContainer" class="overflow-x-auto pb-2 px-10 scroll-smooth">
                    <div class="flex min-w-max lg:min-w-full items-stretch gap-2 lg:gap-0">
                    @foreach($activeLoanCategories as $category)
                        <div class="relative w-[230px] md:w-[240px] lg:w-1/5 lg:basis-1/5 lg:max-w-[20%] shrink-0 lg:flex-none px-3 py-4 text-center group hover:bg-red-50 rounded-xl transition-colors duration-300">
                            @if(!$loop->first)
                                <div class="absolute left-0 top-10 bottom-10 w-px bg-gradient-to-b from-transparent via-brand-green/35 to-transparent"></div>
                            @endif
                            <div class="absolute top-0 left-8 right-8 h-0.5 bg-brand-green rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>

                            <div class="w-full h-24 mb-4 overflow-hidden transform group-hover:-translate-y-1 transition-transform duration-300">
                                @if($category->image_path)
                                    <img src="{{ asset('storage/' . $category->image_path) }}"
                                         alt="{{ $category->localized_name }}"
                                         class="w-full h-full object-cover">
                                @else
                                    <div class="text-sm text-gray-400">{{ __('landing.no_image') }}</div>
                                @endif
                            </div>

                            <h4 class="text-base font-bold leading-snug text-[#1F3868] group-hover:text-brand-green mb-2 transition-colors duration-300">{{ $category->localized_name }}</h4>
                            <p class="text-sm leading-relaxed text-gray-600 group-hover:text-gray-700 min-h-[70px] max-h-[72px] overflow-hidden group-hover:max-h-[180px] transition-all duration-300"
                               title="{{ $category->localized_description ?: __('landing.flexible_lending_options') }}">
                                {{ $category->localized_description ?: __('landing.flexible_lending_options') }}
                            </p>
                            <a href="{{ route('user.register') }}"
                               class="mt-4 inline-flex items-center text-sm font-semibold text-brand-green gap-1.5 group-hover:gap-2.5 transition-all duration-300">
                                <span>{{ __('landing.apply') }}
                                </span>
                                <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform duration-300" viewBox="0 0 16 16" fill="none">
                                    <path d="M3 8H13M9 4L13 8L9 12" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </a>
                        </div>
                    @endforeach
                    </div>
                </div>

                    <button
                        type="button"
                        onclick="scrollLendingCards('right')"
                        class="absolute right-0 top-1/2 -translate-y-1/2 z-10 bg-white/95 border border-gray-200 rounded-full p-2 shadow-md hover:bg-red-50 transition-colors"
                        aria-label="Scroll cards right">
                        <svg class="w-5 h-5 text-brand-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                </div>

                <div class="mt-8 flex items-center gap-4">
                    <div class="flex-1 h-px bg-gradient-to-r from-transparent to-brand-green/30"></div>
                    <div class="flex gap-1.5">
                        @foreach($activeLoanCategories as $category)
                            <div class="w-1.5 h-1.5 rounded-full bg-[#1F3868]/20"></div>
                        @endforeach
                    </div>
                    <div class="flex-1 h-px bg-gradient-to-l from-transparent to-brand-green/30"></div>
                </div>
            </div>
        </section>
    @endif

    <section class="py-10 md:py-14 bg-gray-50 border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 lg:px-4">
            <div class="text-center mb-8 md:mb-10">
                <p class="text-xs tracking-[0.3em] uppercase text-brand-green font-semibold mb-3">
                    Testimonials
                </p>
                <h3 class="text-2xl md:text-3xl font-bold text-[#1F3868] font-poppins">
                    What Our Customers Say
                </h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="bg-white rounded-2xl border border-gray-200 p-3 shadow-sm hover:shadow-md transition-shadow duration-300">
                    <div class="flex items-center mb-2 text-brand-green">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927C9.469 1.701 10.531 1.701 10.951 2.927l.7 2.04a1 1 0 00.95.69h2.146c1.288 0 1.82 1.65.78 2.41l-1.736 1.262a1 1 0 00-.364 1.118l.663 2.028c.398 1.216-.99 2.224-2.03 1.464l-1.75-1.27a1 1 0 00-1.176 0l-1.75 1.27c-1.04.76-2.428-.248-2.03-1.464l.663-2.028a1 1 0 00-.364-1.118L3.473 8.067c-1.04-.76-.508-2.41.78-2.41H6.4a1 1 0 00.95-.69l.7-2.04z"/></svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927C9.469 1.701 10.531 1.701 10.951 2.927l.7 2.04a1 1 0 00.95.69h2.146c1.288 0 1.82 1.65.78 2.41l-1.736 1.262a1 1 0 00-.364 1.118l.663 2.028c.398 1.216-.99 2.224-2.03 1.464l-1.75-1.27a1 1 0 00-1.176 0l-1.75 1.27c-1.04.76-2.428-.248-2.03-1.464l.663-2.028a1 1 0 00-.364-1.118L3.473 8.067c-1.04-.76-.508-2.41.78-2.41H6.4a1 1 0 00.95-.69l.7-2.04z"/></svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927C9.469 1.701 10.531 1.701 10.951 2.927l.7 2.04a1 1 0 00.95.69h2.146c1.288 0 1.82 1.65.78 2.41l-1.736 1.262a1 1 0 00-.364 1.118l.663 2.028c.398 1.216-.99 2.224-2.03 1.464l-1.75-1.27a1 1 0 00-1.176 0l-1.75 1.27c-1.04.76-2.428-.248-2.03-1.464l.663-2.028a1 1 0 00-.364-1.118L3.473 8.067c-1.04-.76-.508-2.41.78-2.41H6.4a1 1 0 00.95-.69l.7-2.04z"/></svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927C9.469 1.701 10.531 1.701 10.951 2.927l.7 2.04a1 1 0 00.95.69h2.146c1.288 0 1.82 1.65.78 2.41l-1.736 1.262a1 1 0 00-.364 1.118l.663 2.028c.398 1.216-.99 2.224-2.03 1.464l-1.75-1.27a1 1 0 00-1.176 0l-1.75 1.27c-1.04.76-2.428-.248-2.03-1.464l.663-2.028a1 1 0 00-.364-1.118L3.473 8.067c-1.04-.76-.508-2.41.78-2.41H6.4a1 1 0 00.95-.69l.7-2.04z"/></svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927C9.469 1.701 10.531 1.701 10.951 2.927l.7 2.04a1 1 0 00.95.69h2.146c1.288 0 1.82 1.65.78 2.41l-1.736 1.262a1 1 0 00-.364 1.118l.663 2.028c.398 1.216-.99 2.224-2.03 1.464l-1.75-1.27a1 1 0 00-1.176 0l-1.75 1.27c-1.04.76-2.428-.248-2.03-1.464l.663-2.028a1 1 0 00-.364-1.118L3.473 8.067c-1.04-.76-.508-2.41.78-2.41H6.4a1 1 0 00.95-.69l.7-2.04z"/></svg>
                    </div>
                    <p class="text-gray-600 text-xl mb-2" style="font-family: 'Hubballi', sans-serif;">"The application process was straightforward and I got matched with a lender quickly. Highly recommend this platform."</p>
                    <p class="font-semibold text-gray-900">Amina J.</p>
                    <p class="text-xs text-gray-500">Small Business Owner</p>
                </div>

                <div class="bg-white rounded-2xl border border-gray-200 p-3 shadow-sm hover:shadow-md transition-shadow duration-300">
                    <div class="flex items-center mb-2 text-brand-green">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927C9.469 1.701 10.531 1.701 10.951 2.927l.7 2.04a1 1 0 00.95.69h2.146c1.288 0 1.82 1.65.78 2.41l-1.736 1.262a1 1 0 00-.364 1.118l.663 2.028c.398 1.216-.99 2.224-2.03 1.464l-1.75-1.27a1 1 0 00-1.176 0l-1.75 1.27c-1.04.76-2.428-.248-2.03-1.464l.663-2.028a1 1 0 00-.364-1.118L3.473 8.067c-1.04-.76-.508-2.41.78-2.41H6.4a1 1 0 00.95-.69l.7-2.04z"/></svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927C9.469 1.701 10.531 1.701 10.951 2.927l.7 2.04a1 1 0 00.95.69h2.146c1.288 0 1.82 1.65.78 2.41l-1.736 1.262a1 1 0 00-.364 1.118l.663 2.028c.398 1.216-.99 2.224-2.03 1.464l-1.75-1.27a1 1 0 00-1.176 0l-1.75 1.27c-1.04.76-2.428-.248-2.03-1.464l.663-2.028a1 1 0 00-.364-1.118L3.473 8.067c-1.04-.76-.508-2.41.78-2.41H6.4a1 1 0 00.95-.69l.7-2.04z"/></svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927C9.469 1.701 10.531 1.701 10.951 2.927l.7 2.04a1 1 0 00.95.69h2.146c1.288 0 1.82 1.65.78 2.41l-1.736 1.262a1 1 0 00-.364 1.118l.663 2.028c.398 1.216-.99 2.224-2.03 1.464l-1.75-1.27a1 1 0 00-1.176 0l-1.75 1.27c-1.04.76-2.428-.248-2.03-1.464l.663-2.028a1 1 0 00-.364-1.118L3.473 8.067c-1.04-.76-.508-2.41.78-2.41H6.4a1 1 0 00.95-.69l.7-2.04z"/></svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927C9.469 1.701 10.531 1.701 10.951 2.927l.7 2.04a1 1 0 00.95.69h2.146c1.288 0 1.82 1.65.78 2.41l-1.736 1.262a1 1 0 00-.364 1.118l.663 2.028c.398 1.216-.99 2.224-2.03 1.464l-1.75-1.27a1 1 0 00-1.176 0l-1.75 1.27c-1.04.76-2.428-.248-2.03-1.464l.663-2.028a1 1 0 00-.364-1.118L3.473 8.067c-1.04-.76-.508-2.41.78-2.41H6.4a1 1 0 00.95-.69l.7-2.04z"/></svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927C9.469 1.701 10.531 1.701 10.951 2.927l.7 2.04a1 1 0 00.95.69h2.146c1.288 0 1.82 1.65.78 2.41l-1.736 1.262a1 1 0 00-.364 1.118l.663 2.028c.398 1.216-.99 2.224-2.03 1.464l-1.75-1.27a1 1 0 00-1.176 0l-1.75 1.27c-1.04.76-2.428-.248-2.03-1.464l.663-2.028a1 1 0 00-.364-1.118L3.473 8.067c-1.04-.76-.508-2.41.78-2.41H6.4a1 1 0 00.95-.69l.7-2.04z"/></svg>
                    </div>
                    <p class="text-gray-600 text-xl mb-2" style="font-family: 'Hubballi', sans-serif;">"I compared offers from multiple lenders in one place. It saved me time and gave me better terms for my loan."</p>
                    <p class="font-semibold text-gray-900">Joseph M.</p>
                    <p class="text-xs text-gray-500">Retail Trader</p>
                </div>

                <div class="bg-white rounded-2xl border border-gray-200 p-3 shadow-sm hover:shadow-md transition-shadow duration-300">
                    <div class="flex items-center mb-2 text-brand-green">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927C9.469 1.701 10.531 1.701 10.951 2.927l.7 2.04a1 1 0 00.95.69h2.146c1.288 0 1.82 1.65.78 2.41l-1.736 1.262a1 1 0 00-.364 1.118l.663 2.028c.398 1.216-.99 2.224-2.03 1.464l-1.75-1.27a1 1 0 00-1.176 0l-1.75 1.27c-1.04.76-2.428-.248-2.03-1.464l.663-2.028a1 1 0 00-.364-1.118L3.473 8.067c-1.04-.76-.508-2.41.78-2.41H6.4a1 1 0 00.95-.69l.7-2.04z"/></svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927C9.469 1.701 10.531 1.701 10.951 2.927l.7 2.04a1 1 0 00.95.69h2.146c1.288 0 1.82 1.65.78 2.41l-1.736 1.262a1 1 0 00-.364 1.118l.663 2.028c.398 1.216-.99 2.224-2.03 1.464l-1.75-1.27a1 1 0 00-1.176 0l-1.75 1.27c-1.04.76-2.428-.248-2.03-1.464l.663-2.028a1 1 0 00-.364-1.118L3.473 8.067c-1.04-.76-.508-2.41.78-2.41H6.4a1 1 0 00.95-.69l.7-2.04z"/></svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927C9.469 1.701 10.531 1.701 10.951 2.927l.7 2.04a1 1 0 00.95.69h2.146c1.288 0 1.82 1.65.78 2.41l-1.736 1.262a1 1 0 00-.364 1.118l.663 2.028c.398 1.216-.99 2.224-2.03 1.464l-1.75-1.27a1 1 0 00-1.176 0l-1.75 1.27c-1.04.76-2.428-.248-2.03-1.464l.663-2.028a1 1 0 00-.364-1.118L3.473 8.067c-1.04-.76-.508-2.41.78-2.41H6.4a1 1 0 00.95-.69l.7-2.04z"/></svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927C9.469 1.701 10.531 1.701 10.951 2.927l.7 2.04a1 1 0 00.95.69h2.146c1.288 0 1.82 1.65.78 2.41l-1.736 1.262a1 1 0 00-.364 1.118l.663 2.028c.398 1.216-.99 2.224-2.03 1.464l-1.75-1.27a1 1 0 00-1.176 0l-1.75 1.27c-1.04.76-2.428-.248-2.03-1.464l.663-2.028a1 1 0 00-.364-1.118L3.473 8.067c-1.04-.76-.508-2.41.78-2.41H6.4a1 1 0 00.95-.69l.7-2.04z"/></svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927C9.469 1.701 10.531 1.701 10.951 2.927l.7 2.04a1 1 0 00.95.69h2.146c1.288 0 1.82 1.65.78 2.41l-1.736 1.262a1 1 0 00-.364 1.118l.663 2.028c.398 1.216-.99 2.224-2.03 1.464l-1.75-1.27a1 1 0 00-1.176 0l-1.75 1.27c-1.04.76-2.428-.248-2.03-1.464l.663-2.028a1 1 0 00-.364-1.118L3.473 8.067c-1.04-.76-.508-2.41.78-2.41H6.4a1 1 0 00.95-.69l.7-2.04z"/></svg>
                    </div>
                    <p class="text-gray-600 text-xl leading-flex mb-2" style="font-family: 'Hubballi', sans-serif;">"Very professional support team and transparent process. I felt confident from application to approval."</p>
                    <p class="font-semibold text-gray-900">Rehema K.</p>
                    <p class="text-xs text-gray-500">Entrepreneur</p>
                </div>
            </div>
        </div>
    </section>

    <section id="eligibility" class="py-16 md:py-20 bg-white">
        <div class="max-w-5xl mx-auto px-6 lg:px-8">
            <p class="text-[11px] font-semibold tracking-[0.13em] uppercase text-[#C62828] mb-3">
                {{ __('landing.why_choose_us') }}
            </p>
            <h2 class="text-[clamp(22px,3.5vw,32px)] font-semibold text-[#111111] leading-tight mb-10">
                {{ __('landing.everything_you_need_line_1') }}<br>{{ __('landing.everything_you_need_line_2') }}
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-3.5">
                <div class="group relative overflow-hidden rounded-[14px] border border-[#E5E5E5] bg-white px-6 py-7 transition-colors hover:border-[#CCCCCC]">
                    <div class="absolute left-0 top-0 h-full w-[3px] bg-[#C62828] opacity-0 transition-opacity group-hover:opacity-100"></div>
                    <div class="mb-4 flex items-center gap-1.5">
                        <span class="text-[11px] font-semibold tracking-[0.08em] text-[#C62828]">01</span>
                        <div class="h-px flex-1 bg-[#E5E5E5]"></div>
                    </div>
                    <div class="mb-3.5 flex h-[42px] w-[42px] items-center justify-center rounded-[10px] bg-[#FFF1F1] text-[#C62828]">
                        <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                            <circle cx="9" cy="7" r="4" />
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                            <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                        </svg>
                    </div>
                    <h3 class="mb-1.5 text-[15px] font-semibold text-[#111111]">{{ __('landing.inclusive_access') }}</h3>
                    <p class="text-[13px] leading-7 text-[#666666]">{{ __('landing.inclusive_access_desc') }}</p>
                </div>

                <div class="group relative overflow-hidden rounded-[14px] border border-[#E5E5E5] bg-white px-6 py-7 transition-colors hover:border-[#CCCCCC]">
                    <div class="absolute left-0 top-0 h-full w-[3px] bg-[#C62828] opacity-0 transition-opacity group-hover:opacity-100"></div>
                    <div class="mb-4 flex items-center gap-1.5">
                        <span class="text-[11px] font-semibold tracking-[0.08em] text-[#C62828]">02</span>
                        <div class="h-px flex-1 bg-[#E5E5E5]"></div>
                    </div>
                    <div class="mb-3.5 flex h-[42px] w-[42px] items-center justify-center rounded-[10px] bg-[#FFF1F1] text-[#C62828]">
                        <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                            <polyline points="9 12 11 14 15 10" />
                        </svg>
                    </div>
                    <h3 class="mb-1.5 text-[15px] font-semibold text-[#111111]">{{ __('landing.nida_verification') }}</h3>
                    <p class="text-[13px] leading-7 text-[#666666]">{{ __('landing.nida_verification_desc') }}</p>
                </div>

                <div class="group relative overflow-hidden rounded-[14px] border border-[#E5E5E5] bg-white px-6 py-7 transition-colors hover:border-[#CCCCCC]">
                    <div class="absolute left-0 top-0 h-full w-[3px] bg-[#C62828] opacity-0 transition-opacity group-hover:opacity-100"></div>
                    <div class="mb-4 flex items-center gap-1.5">
                        <span class="text-[11px] font-semibold tracking-[0.08em] text-[#C62828]">03</span>
                        <div class="h-px flex-1 bg-[#E5E5E5]"></div>
                    </div>
                    <div class="mb-3.5 flex h-[42px] w-[42px] items-center justify-center rounded-[10px] bg-[#FFF1F1] text-[#C62828]">
                        <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2" />
                        </svg>
                    </div>
                    <h3 class="mb-1.5 text-[15px] font-semibold text-[#111111]">{{ __('landing.smart_matching') }}</h3>
                    <p class="text-[13px] leading-7 text-[#666666]">{{ __('landing.smart_matching_desc') }}</p>
                </div>

                <div class="group relative overflow-hidden rounded-[14px] border border-[#E5E5E5] bg-white px-6 py-7 transition-colors hover:border-[#CCCCCC]">
                    <div class="absolute left-0 top-0 h-full w-[3px] bg-[#C62828] opacity-0 transition-opacity group-hover:opacity-100"></div>
                    <div class="mb-4 flex items-center gap-1.5">
                        <span class="text-[11px] font-semibold tracking-[0.08em] text-[#C62828]">04</span>
                        <div class="h-px flex-1 bg-[#E5E5E5]"></div>
                    </div>
                    <div class="mb-3.5 flex h-[42px] w-[42px] items-center justify-center rounded-[10px] bg-[#FFF1F1] text-[#C62828]">
                        <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10" />
                            <polyline points="12 6 12 12 16 14" />
                        </svg>
                    </div>
                    <h3 class="mb-1.5 text-[15px] font-semibold text-[#111111]">{{ __('landing.fast_processing') }}</h3>
                    <p class="text-[13px] leading-7 text-[#666666]">{{ __('landing.fast_processing_desc') }}</p>
                </div>
            </div>
        </div>
    </section>





    <!-- Process Section -->
    <section id="process" class=" md:py-8 bg-white max-w-7xl ">
        <div class=" mx-auto px-4 lg:px-4">
           

        

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

            <!-- <div class="mt-1 md:mt-4">
                <div class="bg-white rounded-2xl p-8 md:p-12 text-center border border-gray-100">
                    <h3 class="text-2xl md:text-3xl font-semibold font-poppins text-black mb-4">
                        {{ __('landing.customer_help') }}
                    </h3>
                    <p class="text-gray-600 mb-6 max-w-2xl mx-auto">
                        {{ __('landing.help_subtitle') }}
                    </p>
                    <a href="{{ route('customer-help.index') }}" class="inline-flex items-center bg-brand-green text-white px-6 py-3 rounded-lg font-semibold hover:bg-red-700 transition-colors">
                        {{ __('landing.customer_help') }}
                    </a>
                </div>
            </div> -->

           
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




    <x-landing.footer />



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

        // Navbar scroll effect - keep transparent while slightly enhancing depth
        const navbar = document.getElementById('mainNavbar');
        const navbarShell = navbar ? navbar.querySelector('.floating-navbar-shell') : null;
        
        window.addEventListener('scroll', function() {
            const currentScroll = window.pageYOffset;
            
            if (!navbarShell) {
                return;
            }

            if (currentScroll > 10) {
                navbarShell.classList.add('navbar-scrolled');
            } else {
                navbarShell.classList.remove('navbar-scrolled');
            }
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

        function scrollLendingCards(direction) {
            const container = document.getElementById('lendingCardsContainer');
            if (!container) return;

            const scrollAmount = 260;
            container.scrollBy({
                left: direction === 'left' ? -scrollAmount : scrollAmount,
                behavior: 'smooth'
            });
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

    <!-- Floating Customer Help Button -->
    <a
        href="{{ route('customer-help.index') }}"
        class="fixed bottom-5 right-5 z-[70] bg-brand-green text-white px-5 py-3 rounded-full shadow-lg hover:bg-red-700 transition-colors duration-300 inline-flex items-center gap-2"
        aria-label="Open customer help page">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-4l-4 4v-4z"/>
        </svg>
        <span class="font-semibold text-sm">{{ __('landing.customer_help') }}</span>
    </a>
</body>
</html>