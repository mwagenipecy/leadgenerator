@props(['isHome' => false])

@php
    $user = auth()->user();
    $isLoggedIn = (bool) $user;
    // TODO: wire this to your real notifications count source
    $notificationsCount = $isLoggedIn ? ($user->unread_notifications_count ?? 0) : 0;
@endphp

<nav id="mainNavbar" class="fixed top-0 inset-x-0 z-50 px-3 sm:px-4 lg:px-6 pt-0 transition-all duration-300">
    <div class="max-w-7xl mx-auto floating-navbar-shell px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16 lg:h-[4.25rem]">
            {{-- Logo --}}
            <a href="/" class="flex items-center group">
                <img src="{{ asset('landing/redlogo.png') }}" alt="Fanikisha Marketplace Logo" class="h-8 lg:h-10 w-auto transition-transform duration-300 group-hover:scale-105">
            </a>

            {{-- Desktop menu --}}
            <div class="hidden md:flex items-center space-x-3 lg:space-x-4">
                @if ($isLoggedIn)
                    {{-- My Dashboard / Mikopo (red) --}}
                    <a href="{{ route('dashboard') }}"
                       class="px-5 py-2 rounded-lg text-white  text-sm lg:text-base hover:shadow-lg hover:scale-105 transition-all duration-300"
                       style="background-color: #C40F11;">
                        {{ __('landing.my_dashboard') }}
                    </a>

                    {{-- Notification bell --}}
                    <a href="{{ route('notifications.index') }}"
                       class="relative p-2 rounded-lg text-gray-700 hover:text-red-600 hover:bg-gray-100 transition-all duration-300"
                       aria-label="Notifications">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        @if ($notificationsCount > 0)
                            <span class="absolute -top-0.5 -right-0.5 min-w-[1.25rem] h-5 px-1 flex items-center justify-center text-[0.7rem] font-semibold text-white bg-red-600 rounded-full ring-2 ring-white">
                                {{ $notificationsCount > 99 ? '99+' : $notificationsCount }}
                            </span>
                        @endif
                    </a>
                @else
                    {{-- Go to Marketplace / Pata Mikopo (red) --}}
                    <a href="{{ route('dashboard') }}"
                       class="px-5 py-2 rounded-lg text-white  text-sm lg:text-base hover:shadow-lg hover:scale-105 transition-all duration-300"
                       style="background-color: #C40F11;">
                        {{ __('landing.go_to_marketplace') }}
                    </a>

                    {{-- Register / Jisajili (green) --}}
                    <a href="{{ route('user.register') }}"
                       class="px-5 py-2 rounded-lg text-white  text-sm lg:text-base hover:shadow-lg hover:scale-105 transition-all duration-300"
                       style="background-color: #00A259;">
                        {{ __('landing.register') }}
                    </a>
                @endif

                {{-- Language switcher (stays on the right) --}}
                <div class="ml-1 relative z-50">
                    <x-language-switcher />
                </div>
            </div>

            {{-- Mobile hamburger --}}
            <button class="md:hidden p-2.5 rounded-lg text-gray-700 hover:bg-gray-100 hover:text-red-600 transition-all duration-300"
                    onclick="toggleMobileMenu()" aria-label="Toggle menu">
                <svg id="menuIcon" class="w-6 h-6 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
        </div>

        {{-- Mobile menu --}}
        <div id="mobileMenu" class="hidden md:hidden border-t border-white/40 bg-white/85 backdrop-blur-md">
            <div class="py-4 space-y-2">
                <div class="px-4 py-2">
                    <x-language-switcher />
                </div>

                @if ($isLoggedIn)
                    <a href="{{ route('dashboard') }}" onclick="toggleMobileMenu()"
                       class="block px-4 py-3 rounded-lg text-white font-semibold transition-all duration-300"
                       style="background-color: #C40F11;">
                        {{ __('landing.my_dashboard') }}
                    </a>

                    <a href="{{ route('notifications.index') }}" onclick="toggleMobileMenu()"
                       class="flex items-center justify-between px-4 py-3 rounded-lg font-medium text-gray-700 hover:bg-gray-100 transition-all duration-300">
                        <span class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                            {{ __('landing.notifications') }}
                        </span>
                        @if ($notificationsCount > 0)
                            <span class="min-w-[1.25rem] h-5 px-1 flex items-center justify-center text-[0.7rem] font-semibold text-white bg-red-600 rounded-full">
                                {{ $notificationsCount > 99 ? '99+' : $notificationsCount }}
                            </span>
                        @endif
                    </a>
                @else
                    <a href="{{ route('dashboard') }}" onclick="toggleMobileMenu()"
                       class="block px-4 py-3 rounded-lg text-white font-semibold transition-all duration-300"
                       style="background-color: #C40F11;">
                        {{ __('landing.go_to_marketplace') }}
                    </a>

                    <a href="{{ route('user.register') }}" onclick="toggleMobileMenu()"
                       class="block px-4 py-3 rounded-lg text-white font-semibold transition-all duration-300"
                       style="background-color: #00A259;">
                        {{ __('landing.register') }}
                    </a>
                @endif
            </div>
        </div>
    </div>
</nav>