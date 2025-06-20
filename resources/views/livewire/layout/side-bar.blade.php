<div id="sidebar" class="fixed inset-y-0 left-0 z-50 w-64 sm:w-72 bg-sidebar-black shadow-2xl transform -translate-x-full transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0 lg:w-80">
    <!-- Logo Section - Responsive -->
    <div class="flex items-center justify-center h-16 sm:h-20 px-4 sm:px-6 bg-gradient-to-r from-brand-red via-brand-dark-red to-red-900 relative overflow-hidden">
        <!-- Animated glow effect -->
        <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/5 to-transparent animate-pulse"></div>
        <div class="flex items-center space-x-2 sm:space-x-4 relative z-10">
            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-white/15 rounded-xl sm:rounded-2xl flex items-center justify-center backdrop-blur-sm border border-white/20 shadow-lg">
                <svg class="w-5 h-5 sm:w-7 sm:h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
            </div>
            <div class="min-w-0">
                <h1 class="text-lg sm:text-2xl font-bold font-poppins text-white truncate">
                    Lead<span class="text-red-200">Generator</span>
                </h1>
                <p class="text-red-200 text-xs font-medium mt-0.5 hidden sm:block">Advanced Analytics</p>
            </div>
        </div>
    </div>

    <!-- Navigation Menu - Enhanced Mobile -->
    <nav class="mt-4 sm:mt-8 px-3 sm:px-4 pb-4 overflow-y-auto max-h-[calc(100vh-200px)]">
        <div class="space-y-2 sm:space-y-3">
            <!-- Dashboard - Active -->
            <a href="{{ route('dashboard') }}" class="group flex items-center px-5 py-4 text-sm font-medium {{ request()->routeIs('dashboard*') ? 'bg-gradient-to-r from-brand-red/90 to-brand-dark-red text-white shadow-lg shadow-brand-red/20' : 'text-gray-300 hover:bg-sidebar-gray hover:text-white' }} rounded-2xl transition-all duration-200 hover:shadow-lg">
                <svg class="mr-4 h-5 w-5 {{ request()->routeIs('dashboard.*') ? '' : 'group-hover:text-brand-red' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z"/> 
                </svg>
                <span class="truncate">Dashboard</span>
                @if(request()->routeIs('dashboard.*'))
                    <div class="ml-auto w-2 h-2 bg-white rounded-full animate-pulse flex-shrink-0"></div>
                @endif
            </a>

            <!-- Lead Management -->
            <a href="{{ route('application.list') }}" class="group flex items-center px-5 py-4 text-sm font-medium {{ request()->routeIs('application.*') ? 'bg-gradient-to-r from-brand-red/90 to-brand-dark-red text-white shadow-lg shadow-brand-red/20' : 'text-gray-300 hover:bg-sidebar-gray hover:text-white' }} rounded-2xl transition-all duration-200 hover:shadow-lg">
                <svg class="mr-4 h-5 w-5 {{ request()->routeIs('application.*') ? '' : 'group-hover:text-brand-red' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <span class="truncate flex-1">Lead Management</span>
                <span class="ml-auto bg-brand-red text-white text-xs px-2 py-1 rounded-full font-bold shadow-md flex-shrink-0">
                    {{ DB::table('applications')->count() }}
                </span>
                @if(request()->routeIs('application.*'))
                    <div class="ml-auto w-2 h-2 bg-white rounded-full animate-pulse flex-shrink-0"></div>
                @endif
            </a>

            <!-- Loan Applications -->
            <a href="{{ route('user.loan.application') }}" class="group flex items-center px-5 py-4 text-sm font-medium {{ request()->routeIs('user.loan.*') ? 'bg-gradient-to-r from-brand-red/90 to-brand-dark-red text-white shadow-lg shadow-brand-red/20' : 'text-gray-300 hover:bg-sidebar-gray hover:text-white' }} rounded-2xl transition-all duration-200 hover:shadow-lg">
                <svg class="mr-4 h-5 w-5 {{ request()->routeIs('user.loan.*') ? '' : 'group-hover:text-brand-red' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <span class="truncate flex-1">Loan Applications</span>
                <span class="ml-auto bg-brand-red text-white text-xs px-2 py-1 rounded-full font-bold shadow-md flex-shrink-0">
                    {{ DB::table('applications')->where('user_id',auth()->user()->id)->count() }}
                </span>
                @if(request()->routeIs('user.loan.*'))
                    <div class="ml-auto w-2 h-2 bg-white rounded-full animate-pulse flex-shrink-0"></div>
                @endif
            </a>

            <!-- Analytics with Collapsible Submenus -->
            <div class="analytics-section" x-data="{ isOpen: false }">
                <button 
                    @click="isOpen = !isOpen" 
                    class="group flex items-center justify-between w-full px-3 sm:px-5 py-3 sm:py-4 text-sm font-medium text-gray-300 rounded-xl sm:rounded-2xl hover:bg-sidebar-gray hover:text-white transition-all duration-200 hover:shadow-lg"
                >
                    <div class="flex items-center">
                        <svg class="mr-3 sm:mr-4 h-4 w-4 sm:h-5 sm:w-5 group-hover:text-brand-red transition-colors flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        <span class="truncate">Self Services</span>
                    </div>
                    <svg 
                        class="h-4 w-4 transition-transform duration-200" 
                        :class="{ 'rotate-180': isOpen }"
                        fill="none" 
                        stroke="currentColor" 
                        viewBox="0 0 24 24"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                
                <!-- Submenu -->
                <div 
                    x-show="isOpen" 
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 max-h-0"
                    x-transition:enter-end="opacity-100 max-h-48"
                    x-transition:leave="transition ease-in duration-300"
                    x-transition:leave-start="opacity-100 max-h-48"
                    x-transition:leave-end="opacity-0 max-h-0"
                    class="ml-4 mt-2 overflow-hidden"
                >
                    <!-- Verify TIN Number -->
                    <a href="{{ route('taxpayer.verification') }}" class="group flex items-center px-3 sm:px-4 py-2 sm:py-3 text-sm text-gray-400 rounded-lg hover:bg-gray-700 hover:text-white transition-all duration-200 mb-1">
                        <svg class="mr-3 h-4 w-4 group-hover:text-brand-red transition-colors flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span class="truncate">Verify TIN Number</span>
                    </a>
                    
                    <!-- Verify License -->
                    <a href="{{ route('lincense.verification') }}" class="group flex items-center px-3 sm:px-4 py-2 sm:py-3 text-sm text-gray-400 rounded-lg hover:bg-gray-700 hover:text-white transition-all duration-200 mb-1">
                        <svg class="mr-3 h-4 w-4 group-hover:text-brand-red transition-colors flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/>
                        </svg>
                        <span class="truncate">Verify License</span>
                    </a>
                    
                    <!-- Verify Vehicle -->
                    <a href="{{ route('motor.vehicle.verification') }}" class="group flex items-center px-3 sm:px-4 py-2 sm:py-3 text-sm text-gray-400 rounded-lg hover:bg-gray-700 hover:text-white transition-all duration-200">
                        <svg class="mr-3 h-4 w-4 group-hover:text-brand-red transition-colors flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                        </svg>
                        <span class="truncate">Verify Vehicle</span>
                    </a>
                </div>
            </div>



            


            <!-- NIDA Verification -->
            <a href="" class="group flex items-center px-3 sm:px-5 py-3 sm:py-4 text-sm font-medium text-gray-300 rounded-xl sm:rounded-2xl hover:bg-sidebar-gray hover:text-white transition-all duration-200 hover:shadow-lg">
                <svg class="mr-3 sm:mr-4 h-4 w-4 sm:h-5 sm:w-5 group-hover:text-brand-red transition-colors flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
                <span class="truncate flex-1">NIDA Verification</span>
                <div class="ml-auto flex-shrink-0">
                    <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
                </div>
            </a>


      <!-- User Management -->
<a href="{{ route('user.management') }}" class="group flex items-center px-3 sm:px-5 py-3 sm:py-4 text-sm font-medium text-gray-300 rounded-xl sm:rounded-2xl hover:bg-sidebar-gray hover:text-white transition-all duration-200 hover:shadow-lg">
    <svg class="mr-3 sm:mr-4 h-4 w-4 sm:h-5 sm:w-5 group-hover:text-brand-red transition-colors flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
    </svg>
    <span class="truncate flex-1">User Management</span>
    <div class="ml-auto flex-shrink-0">
        <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
    </div>
</a>



            <!-- Lender Management -->
            <a href="{{ route('lenders.index') }}" class="group flex items-center px-5 py-4 text-sm font-medium {{ request()->routeIs('lenders.*') ? 'bg-gradient-to-r from-brand-red/90 to-brand-dark-red text-white shadow-lg shadow-brand-red/20' : 'text-gray-300 hover:bg-sidebar-gray hover:text-white' }} rounded-2xl transition-all duration-200 hover:shadow-lg">
                <svg class="mr-4 h-5 w-5 {{ request()->routeIs('lenders.*') ? '' : 'group-hover:text-brand-red' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                <span class="truncate flex-1">Lender Management</span>
                @if(request()->routeIs('lenders.*'))
                    <div class="ml-auto w-2 h-2 bg-white rounded-full animate-pulse"></div>
                @else
                    @php
                        $pendingCount = App\Models\Lender::pending()->count();
                    @endphp
                    @if($pendingCount > 0)
                        <span class="ml-auto bg-yellow-500 text-white text-xs px-2.5 py-1.5 rounded-full font-bold shadow-md">{{ $pendingCount }}</span>
                    @endif
                @endif
            </a>

            <!-- Loan Products -->
            <a href="{{ route('loan.product.index') }}" class="group flex items-center px-5 py-4 text-sm font-medium {{ request()->routeIs('loan.product.*') ? 'bg-gradient-to-r from-brand-red/90 to-brand-dark-red text-white shadow-lg shadow-brand-red/20' : 'text-gray-300 hover:bg-sidebar-gray hover:text-white' }} rounded-2xl transition-all duration-200 hover:shadow-lg">
                <svg class="mr-4 h-5 w-5 {{ request()->routeIs('loan.product.*') ? '' : 'group-hover:text-brand-red' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                </svg>
                <span class="truncate">Loan Products</span>
                @if(request()->routeIs('loan.product.*'))
                    <div class="ml-auto w-2 h-2 bg-white rounded-full animate-pulse flex-shrink-0"></div>
                @endif
            </a>

            <!-- Integration -->
            <a href="{{ route('webhook.integration') }}" class="group flex items-center px-3 sm:px-5 py-3 sm:py-4 text-sm font-medium text-gray-300 rounded-xl sm:rounded-2xl hover:bg-sidebar-gray hover:text-white transition-all duration-200 hover:shadow-lg">
                <svg class="mr-3 sm:mr-4 h-4 w-4 sm:h-5 sm:w-5 group-hover:text-brand-red transition-colors flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span class="truncate">Integrations</span>
            </a>

            <!-- Settings -->
            <a href="{{ route('system.settings') }}" class="group flex items-center px-3 sm:px-5 py-3 sm:py-4 text-sm font-medium text-gray-300 rounded-xl sm:rounded-2xl hover:bg-sidebar-gray hover:text-white transition-all duration-200 hover:shadow-lg">
                <svg class="mr-3 sm:mr-4 h-4 w-4 sm:h-5 sm:w-5 group-hover:text-brand-red transition-colors flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <span class="truncate">Settings</span>
            </a>
        </div>

        <!-- Divider -->
        <div class="my-6 sm:my-8 border-t border-gray-700"></div>

        <!-- Support Section - Mobile Optimized -->
        <div class="bg-gradient-to-br from-brand-red/10 via-brand-dark-red/5 to-transparent rounded-xl sm:rounded-2xl p-4 sm:p-5 border border-brand-red/20">
            <div class="flex items-start space-x-3 sm:space-x-4">
                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-brand-red/20 rounded-lg sm:rounded-xl flex items-center justify-center border border-brand-red/30 flex-shrink-0">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-brand-red" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192L5.636 18.364M12 3a9 9 0 110 18 9 9 0 010-18z"/>
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-bold text-white mb-1">Need Support?</p>
                    <p class="text-xs text-gray-400 mb-3">Our team is here to help you 24/7</p>
                    <button class="bg-gradient-to-r from-brand-red to-brand-dark-red text-white text-xs font-semibold py-2 px-3 sm:px-4 rounded-lg hover:shadow-lg hover:shadow-brand-red/25 transition-all duration-200 w-full">
                        Get Help Now
                    </button>
                </div>
            </div>
        </div>

        <!-- User Profile Section - Enhanced Mobile -->
        <div class="mt-4 sm:mt-6 px-2">
            <div class="bg-sidebar-gray rounded-xl sm:rounded-2xl p-3 sm:p-4 hover:bg-gray-800 transition-colors duration-200 cursor-pointer">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-br from-brand-red to-brand-dark-red rounded-lg sm:rounded-xl flex items-center justify-center shadow-lg flex-shrink-0">
                        <span class="text-white text-sm font-bold">{{ substr(auth()->user()->name,0,2) }}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-white truncate">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-400">user</p>
                    </div>
                    <svg class="h-4 w-4 text-gray-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </div>
            </div>
        </div>
    </nav>
</div>