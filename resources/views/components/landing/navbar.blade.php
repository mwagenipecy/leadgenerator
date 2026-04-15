@props(['isHome' => false])

@php
    $eligibilityLink = $isHome ? '#eligibility' : url('/#eligibility');
    $processLink = $isHome ? '#process' : url('/#process');
@endphp

<nav id="mainNavbar" class="fixed top-0 inset-x-0 z-50 px-3 sm:px-4 lg:px-6 pt-0 transition-all duration-300">
    <div class="max-w-7xl mx-auto floating-navbar-shell px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16 lg:h-[4.25rem]">
            <a href="/" class="flex items-center group">
                <img src="{{ asset('landing/redlogo.png') }}" alt="Fanikisha Market place Logo" class="h-8 lg:h-10 w-auto transition-transform duration-300 group-hover:scale-105">
            </a>

            <div class="hidden md:flex items-center space-x-1 lg:space-x-2">
                <div class="mr-2 relative z-50">
                    <x-language-switcher />
                </div>
                <a href="{{ $eligibilityLink }}" class="nav-link nav-scroll-link px-4 py-2 rounded-lg hover:text-white hover:bg-red-600 transition-all duration-300 font-medium text-sm lg:text-base relative group">
                    <span class="relative z-10">{{ __('landing.eligibility') }}</span>
                    <span class="absolute inset-0 bg-red-600 rounded-lg scale-0 group-hover:scale-100 transition-transform duration-300 origin-center"></span>
                </a>
                <a href="{{ $processLink }}" class="nav-link nav-scroll-link px-4 py-2 rounded-lg hover:text-white hover:bg-red-600 transition-all duration-300 font-medium text-sm lg:text-base relative group">
                    <span class="relative z-10">{{ __('landing.process') }}</span>
                    <span class="absolute inset-0 bg-red-600 rounded-lg scale-0 group-hover:scale-100 transition-transform duration-300 origin-center"></span>
                </a>
                <a href="{{ route('customer-help.index') }}" class="nav-link nav-scroll-link px-4 py-2 rounded-lg hover:text-white hover:bg-red-600 transition-all duration-300 font-medium text-sm lg:text-base relative group">
                    <span class="relative z-10">{{ __('landing.customer_help') }}</span>
                    <span class="absolute inset-0 bg-red-600 rounded-lg scale-0 group-hover:scale-100 transition-transform duration-300 origin-center"></span>
                </a>
                <a href="{{ route('blog.index') }}" class="nav-link nav-scroll-link px-4 py-2 rounded-lg hover:text-white hover:bg-red-600 transition-all duration-300 font-medium text-sm lg:text-base relative group">
                    <span class="relative z-10">{{ __('landing.blog') }}</span>
                    <span class="absolute inset-0 bg-red-600 rounded-lg scale-0 group-hover:scale-100 transition-transform duration-300 origin-center"></span>
                </a>
                <a href="{{ route('login') }}" class="ml-2 px-5 py-2 rounded-lg text-white font-semibold text-sm lg:text-base hover:shadow-lg hover:scale-105 transition-all duration-300 relative overflow-hidden group" style="background-color: #C40F11;">
                    <span class="relative z-10 flex items-center">
                    {{ __('landing.get_started') }}
                        <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                    </span>
                    <span class="absolute inset-0 bg-red-800 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></span>
                </a>
            </div>

            <button class="md:hidden p-2.5 rounded-lg text-gray-700 hover:bg-gray-100 hover:text-red-600 transition-all duration-300" onclick="toggleMobileMenu()" aria-label="Toggle menu">
                <svg id="menuIcon" class="w-6 h-6 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
        </div>

        <div id="mobileMenu" class="hidden md:hidden border-t border-white/40 bg-white/85 backdrop-blur-md">
            <div class="py-4 space-y-1">
                <div class="px-4 py-2">
                    <x-language-switcher />
                </div>
                <a href="{{ $eligibilityLink }}" onclick="toggleMobileMenu()" class="block px-4 py-3 rounded-lg text-gray-700 hover:text-white hover:bg-red-600 transition-all duration-300 font-medium" style="color: #C40F11;">
                    {{ __('landing.eligibility') }}
                </a>
                <a href="{{ $processLink }}" onclick="toggleMobileMenu()" class="block px-4 py-3 rounded-lg text-gray-700 hover:text-white hover:bg-red-600 transition-all duration-300 font-medium" style="color: #C40F11;">
                    {{ __('landing.process') }}
                </a>
                <a href="{{ route('customer-help.index') }}" onclick="toggleMobileMenu()" class="block px-4 py-3 rounded-lg text-gray-700 hover:text-white hover:bg-red-600 transition-all duration-300 font-medium" style="color: #C40F11;">
                    {{ __('landing.customer_help') }}
                </a>
                <a href="{{ route('blog.index') }}" onclick="toggleMobileMenu()" class="block px-4 py-3 rounded-lg text-gray-700 hover:text-white hover:bg-red-600 transition-all duration-300 font-medium" style="color: #C40F11;">
                    {{ __('landing.blog') }}
                </a>
                <a href="{{ route('login') }}" onclick="toggleMobileMenu()" class="block px-4 py-3 rounded-lg text-white font-semibold mt-2 transition-all duration-300" style="background-color: #C40F11;">
                    {{ __('landing.get_started') }}
                </a>
            </div>
        </div>
    </div>
</nav>
