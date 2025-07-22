<header class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-30">
        <div class="flex items-center justify-between px-4 sm:px-6 lg:px-8 py-3 sm:py-4">
            <!-- App Name/Logo -->
            <div class="flex-1">
                <h1 class="text-xl font-bold text-gray-900">Dashboard</h1>
            </div>

            <!-- Profile Menu with Dropdown -->
            <div class="relative">
                <button 
                    id="profile-menu-button" 
                    class="flex items-center space-x-2 lg:space-x-3 p-1 lg:p-2 rounded-lg lg:rounded-xl hover:bg-gray-100 transition-all duration-200 group"
                    onclick="toggleDropdown()"
                >
                    <div class="w-8 h-8 lg:w-10 lg:h-10 bg-gradient-to-br from-brand-red to-brand-dark-red rounded-lg lg:rounded-xl flex items-center justify-center shadow-md">
                        <span class="text-white text-xs lg:text-sm font-bold uppercase "> {{ substr( auth()->user()->name,0,2) }} </span>
                    </div>
                    <div class="text-left">
                        <p class="text-sm font-semibold text-gray-900 group-hover:text-brand-red transition-colors">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-500">User</p>
                    </div>
                    <svg class="h-4 w-4 text-gray-500 group-hover:text-brand-red transition-all duration-200" 
                         id="dropdown-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                <!-- Dropdown Menu -->
                <div 
                    id="profile-dropdown" 
                    class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-200 py-2 z-40 hidden"
                >
                    <div class="px-4 py-2 border-b border-gray-100">
                        <p class="text-sm font-semibold text-gray-900">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-500"> {{ auth()->user()->email }}</p>
                    </div>

                    <!-- <a href="{{ route('user.profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Your Profile</a> -->
                    <a href="{{ route('user.setting') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Settings</a>
                         

                    
                    <!-- Laravel Logout Form -->
                    <form method="POST" action="{{ route('logout') }}" class="block">
                        @csrf
                        <button 
                            type="submit" 
                            class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors duration-200 flex items-center space-x-2"
                        >
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>


        <script>
        function toggleDropdown() {
            const dropdown = document.getElementById('profile-dropdown');
            const arrow = document.getElementById('dropdown-arrow');
            
            dropdown.classList.toggle('hidden');
            arrow.style.transform = dropdown.classList.contains('hidden') ? 'rotate(0deg)' : 'rotate(180deg)';
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const button = document.getElementById('profile-menu-button');
            const dropdown = document.getElementById('profile-dropdown');
            
            if (!button.contains(event.target) && !dropdown.contains(event.target)) {
                dropdown.classList.add('hidden');
                document.getElementById('dropdown-arrow').style.transform = 'rotate(0deg)';
            }
        });
    </script>



    </header>