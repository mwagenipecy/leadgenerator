<header
    class="h-20 bg-white border-b border-gray-200 flex items-center justify-between px-4 sm:px-6 lg:px-8 gap-4 sm:gap-6 sticky top-0 z-30">
    <!-- Left Section: Toggle Buttons -->
    <div class="flex items-center gap-3">
        <!-- Desktop Sidebar Toggle Button -->
        <button wire:click="$dispatch('toggle-sidebar')"
            class="hidden lg:flex p-2.5 rounded-lg hover:bg-sidebar-green-50 transition-all duration-200 group border border-transparent hover:border-sidebar-green-200"
            title="Toggle Sidebar">
            <svg class="w-5 h-5 text-gray-700 group-hover:text-sidebar-green transition-colors" fill="none"
                stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h12M4 18h16" />
            </svg>
        </button>

        <!-- Mobile Menu Button -->
        <button id="mobile-menu-button" class="lg:hidden p-2 rounded-lg hover:bg-gray-100 transition-colors"
            onclick="toggleMobileSidebar()" aria-label="Toggle menu">
            <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
    </div>

    <!-- Right Section: Actions & User -->
    <div class="flex items-center gap-4 sm:gap-6">
        <!-- Language Switcher -->
        <x-language-switcher />

        <!-- Notification Dropdown -->
        <livewire:layout.notification-dropdown />

        <!-- User Profile Dropdown (Only show when authenticated) -->
        @if (auth()->check())
            <div class="relative">
                <button id="profile-menu-button"
                    class="flex items-center gap-2 sm:gap-3 p-1 sm:p-2 rounded-lg hover:bg-gray-100 transition-all duration-200 group"
                    onclick="toggleDropdown()">
                    <!-- User Info (Hidden on mobile) -->
                    <div class="hidden md:block text-right">
                        <div class="text-sidebar-green font-semibold text-sm">{{ auth()->user()->first_name ?? '' }}
                            {{ auth()->user()->last_name ?? '' }}</div>
                        <div class="text-gray-500 text-xs capitalize">{{ auth()->user()->role ?? 'user' }}</div>
                    </div>
                    <!-- Avatar -->
                    <div
                        class="w-10 h-10 sm:w-12 sm:h-12 bg-sidebar-green rounded-full flex items-center justify-center text-white font-bold text-sm sm:text-base">
                        @if (auth()->user()->profile_photo_path)
                            <img src="{{ asset('storage/' . auth()->user()->profile_photo_path) }}" alt="Profile Photo"
                                class="w-full h-full object-cover rounded-full">
                        @else
                            {{ strtoupper(substr(auth()->user()->first_name ?? 'U', 0, 1)) }}{{ strtoupper(substr(auth()->user()->last_name ?? (auth()->user()->first_name ?? 'U'), 0, 1)) }}
                        @endif

                    </div>
                    <!-- Dropdown Arrow (Hidden on mobile) -->
                    <svg class="hidden sm:block h-4 w-4 text-gray-500 transition-transform duration-200"
                        id="dropdown-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <!-- Dropdown Menu -->
                <div id="profile-dropdown"
                    class="absolute right-0 mt-2 w-56 sm:w-64 bg-white rounded-xl shadow-xl border border-gray-200 py-2 z-40 hidden">
                    <!-- User Info in Dropdown -->
                    <div class="px-4 py-3 border-b border-gray-100">
                        <p class="text-sm font-semibold text-gray-900">{{ auth()->user()->first_name ?? '' }}
                            {{ auth()->user()->last_name ?? '' }}</p>
                        <p class="text-xs text-gray-500 mt-0.5">{{ auth()->user()->email ?? '' }}</p>
                        <p class="text-xs text-sidebar-green font-medium mt-1 capitalize">
                            {{ auth()->user()->role ?? 'user' }}</p>
                    </div>

                    <!-- Menu Items -->
                    <div class="py-1">
                        <a href="{{ route('user.setting') }}"
                            class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span>Settings</span>
                        </a>
                    </div>

                    <div class="border-t border-gray-100 py-1">
                        <!-- Logout Form -->
                        <form method="POST" action="{{ route('logout') }}" class="block">
                            @csrf
                            <button type="submit"
                                class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-sidebar-green hover:bg-sidebar-green-50 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                <span>Logout</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <script>
        function toggleDropdown() {
            const dropdown = document.getElementById('profile-dropdown');
            const arrow = document.getElementById('dropdown-arrow');

            dropdown.classList.toggle('hidden');
            if (arrow) {
                arrow.style.transform = dropdown.classList.contains('hidden') ? 'rotate(0deg)' : 'rotate(180deg)';
            }
        }

        function toggleMobileSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');

            if (sidebar) {
                sidebar.classList.toggle('-translate-x-full');
            }
            if (overlay) {
                overlay.classList.toggle('hidden');
            }
            document.body.classList.toggle('overflow-hidden');
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const button = document.getElementById('profile-menu-button');
            const dropdown = document.getElementById('profile-dropdown');
            const arrow = document.getElementById('dropdown-arrow');

            if (button && dropdown && !button.contains(event.target) && !dropdown.contains(event.target)) {
                dropdown.classList.add('hidden');
                if (arrow) {
                    arrow.style.transform = 'rotate(0deg)';
                }
            }
        });

        // Close mobile sidebar when clicking overlay
        document.addEventListener('DOMContentLoaded', function() {
            const overlay = document.getElementById('sidebar-overlay');
            if (overlay) {
                overlay.addEventListener('click', function() {
                    toggleMobileSidebar();
                });
            }
        });
    </script>
</header>
