<div 
    id="sidebar" 
    class="fixed inset-y-0 left-0 z-50 {{ $isCollapsed ? 'w-20' : 'w-64' }} bg-gradient-to-b from-sidebar-green to-sidebar-green-dark text-white flex flex-col rounded-r-3xl shadow-2xl transform -translate-x-full transition-all duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0"
>
    <!-- Logo Section -->
    <div class="h-20 flex items-center {{ $isCollapsed ? 'justify-center' : 'px-6' }} border-b border-sidebar-green-light/30">
        @if(!$isCollapsed)
        <div class="flex items-center gap-2">
            <img src="{{ asset('logo/logoOnGreenBg.png') }}" alt="Logo" class="h-10 w-auto">
        </div>
        @else
        <div class="w-10 h-10 bg-white/15 rounded-full flex items-center justify-center backdrop-blur-sm border border-white/20">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
            </svg>
        </div>
        @endif
    </div>

    <!-- Menu Items -->
    <nav class="flex-1 {{ $isCollapsed ? 'px-2' : 'px-3' }} py-4 overflow-y-auto" style="scrollbar-width: thin; scrollbar-color: rgba(255,255,255,0.2) transparent;">
        <div class="space-y-1">
            <!-- Dashboard -->
            <a href="{{ route('dashboard') }}"
               class="w-full flex items-center {{ $isCollapsed ? 'justify-center' : 'justify-between' }} {{ $isCollapsed ? 'px-4 py-3' : 'px-4 py-3' }} rounded-lg transition-all {{ request()->routeIs('dashboard*') ? 'bg-white text-sidebar-green' : 'text-white hover:bg-sidebar-green-light' }}"
               title="{{ $isCollapsed ? 'Dashboard' : '' }}">
                <div class="flex items-center {{ $isCollapsed ? '' : 'gap-3' }}">
                    <svg class="w-5 h-5 {{ $isCollapsed ? '' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z"/> 
                    </svg>
                    @if(!$isCollapsed)
                    <span class="font-medium">Dashboard</span>
                    @endif
                </div>
                @if(!$isCollapsed && request()->routeIs('dashboard*'))
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                @endif
            </a>

            <!-- Lead Management (Lender) -->
            @if(auth()->user()->role=='lender')
            <a href="{{ route('application.list') }}"
               class="w-full flex items-center {{ $isCollapsed ? 'justify-center' : 'justify-between' }} {{ $isCollapsed ? 'px-4 py-3' : 'px-4 py-3' }} rounded-lg transition-all {{ request()->routeIs('application.*') ? 'bg-white text-sidebar-green' : 'text-white hover:bg-sidebar-green-light' }} relative"
               title="{{ $isCollapsed ? 'Lead Management' : '' }}">
                <div class="flex items-center {{ $isCollapsed ? '' : 'gap-3' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    @if(!$isCollapsed)
                    <span class="font-medium">Lead Management</span>
                    @endif
                </div>
                @php $leadCount = DB::table('applications')->count(); @endphp
                @if($leadCount > 0)
                    @if(!$isCollapsed)
                    <span class="bg-sidebar-green text-white text-xs px-2 py-1 rounded-full font-bold">{{ $leadCount }}</span>
                    @else
                    <span class="absolute top-1 right-1 bg-white text-sidebar-green text-xs px-1.5 py-0.5 rounded-full font-bold">{{ $leadCount }}</span>
                    @endif
                @endif
            </a>
            @endif

            <!-- User Profile & Loan Applications (Borrower) -->
            @if(auth()->user()->role=='borrower')
            <a href="{{ route('loan-application.profile') }}"
               class="w-full flex items-center {{ $isCollapsed ? 'justify-center' : 'justify-between' }} {{ $isCollapsed ? 'px-4 py-3' : 'px-4 py-3' }} rounded-lg transition-all {{ request()->routeIs('loan-application.profile*') ? 'bg-white text-sidebar-green' : 'text-white hover:bg-sidebar-green-light' }}"
               title="{{ $isCollapsed ? 'User Profile' : '' }}">
                <div class="flex items-center {{ $isCollapsed ? '' : 'gap-3' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    @if(!$isCollapsed)
                    <span class="font-medium">User Profile</span>
                    @endif
                </div>
            </a>

            <a href="{{ route('user.loan.application') }}"
               class="w-full flex items-center {{ $isCollapsed ? 'justify-center' : 'justify-between' }} {{ $isCollapsed ? 'px-4 py-3' : 'px-4 py-3' }} rounded-lg transition-all {{ request()->routeIs('user.loan.*') ? 'bg-white text-sidebar-green' : 'text-white hover:bg-sidebar-green-light' }} relative"
               title="{{ $isCollapsed ? 'Loan Applications' : '' }}">
                <div class="flex items-center {{ $isCollapsed ? '' : 'gap-3' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    @if(!$isCollapsed)
                    <span class="font-medium">Loan Applications</span>
                    @endif
                </div>
                @php $userLoanCount = DB::table('applications')->where('user_id',auth()->user()->id)->count(); @endphp
                @if($userLoanCount > 0)
                    @if(!$isCollapsed)
                    <span class="bg-sidebar-green text-white text-xs px-2 py-1 rounded-full font-bold">{{ $userLoanCount }}</span>
                    @else
                    <span class="absolute top-1 right-1 bg-white text-sidebar-green text-xs px-1.5 py-0.5 rounded-full font-bold">{{ $userLoanCount }}</span>
                    @endif
                @endif
            </a>

            <!-- Self Services (Borrower) -->
            @if(!$isCollapsed)
            <div x-data="{ isOpen: false }">
                <button 
                    @click="isOpen = !isOpen" 
                    class="w-full flex items-center justify-between px-4 py-3 rounded-lg text-white hover:bg-sidebar-green-light transition-all">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        <span class="font-medium">Self Services</span>
                    </div>
                    <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': isOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                
                <div x-show="isOpen" x-transition class="ml-4 mt-1 space-y-1">
                    <a href="{{ route('taxpayer.verification') }}" class="flex items-center gap-3 px-4 py-3 text-white hover:bg-sidebar-green-light rounded-lg transition-all">
                        <div class="w-2 h-2 rounded-full border-2 border-current"></div>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span class="font-medium text-sm">Verify TIN Number</span>
                    </a>
                    <a href="{{ route('lincense.verification') }}" class="flex items-center gap-3 px-4 py-3 text-white hover:bg-sidebar-green-light rounded-lg transition-all">
                        <div class="w-2 h-2 rounded-full border-2 border-current"></div>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/>
                        </svg>
                        <span class="font-medium text-sm">Verify License</span>
                    </a>
                    <a href="{{ route('motor.vehicle.verification') }}" class="flex items-center gap-3 px-4 py-3 text-white hover:bg-sidebar-green-light rounded-lg transition-all">
                        <div class="w-2 h-2 rounded-full border-2 border-current"></div>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                        </svg>
                        <span class="font-medium text-sm">Verify Vehicle</span>
                    </a>
                    <a href="{{ route('credit.report') }}" class="flex items-center gap-3 px-4 py-3 text-white hover:bg-sidebar-green-light rounded-lg transition-all">
                        <div class="w-2 h-2 rounded-full border-2 border-current"></div>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span class="font-medium text-sm">Credit Report</span>
                    </a>
                </div>
            </div>
            @else
            <a href="{{ route('taxpayer.verification') }}" class="flex items-center justify-center px-4 py-3 text-white hover:bg-sidebar-green-light rounded-lg transition-all" title="Verify TIN Number">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </a>
            <a href="{{ route('lincense.verification') }}" class="flex items-center justify-center px-4 py-3 text-white hover:bg-sidebar-green-light rounded-lg transition-all" title="Verify License">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/>
                </svg>
            </a>
            <a href="{{ route('motor.vehicle.verification') }}" class="flex items-center justify-center px-4 py-3 text-white hover:bg-sidebar-green-light rounded-lg transition-all" title="Verify Vehicle">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                </svg>
            </a>
            <a href="{{ route('credit.report') }}" class="flex items-center justify-center px-4 py-3 text-white hover:bg-sidebar-green-light rounded-lg transition-all" title="Credit Report">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </a>
            @endif
            @endif

            <!-- Super Admin Menu Items -->
            @if(auth()->user()->role=='super_admin')
            <a href="{{ route('user.management') }}"
               class="w-full flex items-center {{ $isCollapsed ? 'justify-center' : 'justify-between' }} {{ $isCollapsed ? 'px-4 py-3' : 'px-4 py-3' }} rounded-lg transition-all {{ request()->routeIs('user*') ? 'bg-white text-sidebar-green' : 'text-white hover:bg-sidebar-green-light' }} relative"
               title="{{ $isCollapsed ? 'User Management' : '' }}">
                <div class="flex items-center {{ $isCollapsed ? '' : 'gap-3' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                    </svg>
                    @if(!$isCollapsed)
                    <span class="font-medium">User Management</span>
                    @endif
                </div>
            </a>

            <a href="{{ route('admin.company.verification') }}"
               class="w-full flex items-center {{ $isCollapsed ? 'justify-center' : 'justify-between' }} {{ $isCollapsed ? 'px-4 py-3' : 'px-4 py-3' }} rounded-lg transition-all {{ request()->routeIs('admin.company.verification') ? 'bg-white text-sidebar-green' : 'text-white hover:bg-sidebar-green-light' }} relative"
               title="{{ $isCollapsed ? 'Company Verification' : '' }}">
                <div class="flex items-center {{ $isCollapsed ? '' : 'gap-3' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    @if(!$isCollapsed)
                    <span class="font-medium">Company Verification</span>
                    @endif
                </div>
                @php $pendingCompanyCount = App\Models\User::where('registration_type', 'company')->where('company_verification_status', 'pending')->count(); @endphp
                @if($pendingCompanyCount > 0)
                    @if(!$isCollapsed)
                    <span class="bg-yellow-500 text-white text-xs px-2 py-1 rounded-full font-bold">{{ $pendingCompanyCount }}</span>
                    @else
                    <span class="absolute top-1 right-1 bg-yellow-500 text-white text-xs px-1.5 py-0.5 rounded-full font-bold">{{ $pendingCompanyCount }}</span>
                    @endif
                @endif
            </a>

            <a href="{{ route('lenders.index') }}"
               class="w-full flex items-center {{ $isCollapsed ? 'justify-center' : 'justify-between' }} {{ $isCollapsed ? 'px-4 py-3' : 'px-4 py-3' }} rounded-lg transition-all {{ request()->routeIs('lenders.*') ? 'bg-white text-sidebar-green' : 'text-white hover:bg-sidebar-green-light' }} relative"
               title="{{ $isCollapsed ? 'Lender Management' : '' }}">
                <div class="flex items-center {{ $isCollapsed ? '' : 'gap-3' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    @if(!$isCollapsed)
                    <span class="font-medium">Lender Management</span>
                    @endif
                </div>
                @php $pendingLenderCount = App\Models\Lender::pending()->count(); @endphp
                @if($pendingLenderCount > 0)
                    @if(!$isCollapsed)
                    <span class="bg-yellow-500 text-white text-xs px-2 py-1 rounded-full font-bold">{{ $pendingLenderCount }}</span>
                    @else
                    <span class="absolute top-1 right-1 bg-yellow-500 text-white text-xs px-1.5 py-0.5 rounded-full font-bold">{{ $pendingLenderCount }}</span>
                    @endif
                @endif
            </a>
            @endif

            <!-- Loan Products (Lender) -->
            @if(auth()->user()->role=='lender')
            <a href="{{ route('loan.product.index') }}"
               class="w-full flex items-center {{ $isCollapsed ? 'justify-center' : 'justify-between' }} {{ $isCollapsed ? 'px-4 py-3' : 'px-4 py-3' }} rounded-lg transition-all {{ request()->routeIs('loan-product*') || request()->routeIs('loan.product*') ? 'bg-white text-sidebar-green' : 'text-white hover:bg-sidebar-green-light' }}"
               title="{{ $isCollapsed ? 'Loan Products' : '' }}">
                <div class="flex items-center {{ $isCollapsed ? '' : 'gap-3' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                    </svg>
                    @if(!$isCollapsed)
                    <span class="font-medium">Loan Products</span>
                    @endif
                </div>
            </a>
            @endif

            <!-- Integrations (Not Borrower) -->
            @if(auth()->user()->role!='borrower')
            <a href="{{ route('webhook.integration') }}"
               class="w-full flex items-center {{ $isCollapsed ? 'justify-center' : 'justify-between' }} {{ $isCollapsed ? 'px-4 py-3' : 'px-4 py-3' }} rounded-lg transition-all {{ request()->routeIs('webhook*') ? 'bg-white text-sidebar-green' : 'text-white hover:bg-sidebar-green-light' }}"
               title="{{ $isCollapsed ? 'Integrations' : '' }}">
                <div class="flex items-center {{ $isCollapsed ? '' : 'gap-3' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    @if(!$isCollapsed)
                    <span class="font-medium">Integrations</span>
                    @endif
                </div>
            </a>
            @endif

            <!-- Super Admin Settings -->
            @if(auth()->user()->role=='super_admin')
            <a href="{{ route('system.settings') }}"
               class="w-full flex items-center {{ $isCollapsed ? 'justify-center' : 'justify-between' }} {{ $isCollapsed ? 'px-4 py-3' : 'px-4 py-3' }} rounded-lg transition-all {{ request()->routeIs('system*') && !request()->routeIs('system.logs*') ? 'bg-white text-sidebar-green' : 'text-white hover:bg-sidebar-green-light' }}"
               title="{{ $isCollapsed ? 'Settings' : '' }}">
                <div class="flex items-center {{ $isCollapsed ? '' : 'gap-3' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    @if(!$isCollapsed)
                    <span class="font-medium">Settings</span>
                    @endif
                </div>
            </a>

            <a href="{{ route('billing.section') }}"
               class="w-full flex items-center {{ $isCollapsed ? 'justify-center' : 'justify-between' }} {{ $isCollapsed ? 'px-4 py-3' : 'px-4 py-3' }} rounded-lg transition-all {{ request()->routeIs('billing*') ? 'bg-white text-sidebar-green' : 'text-white hover:bg-sidebar-green-light' }}"
               title="{{ $isCollapsed ? 'Billing' : '' }}">
                <div class="flex items-center {{ $isCollapsed ? '' : 'gap-3' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                    </svg>
                    @if(!$isCollapsed)
                    <span class="font-medium">Billing</span>
                    @endif
                </div>
            </a>

            <a href="{{ route('system.logs') }}"
               class="w-full flex items-center {{ $isCollapsed ? 'justify-center' : 'justify-between' }} {{ $isCollapsed ? 'px-4 py-3' : 'px-4 py-3' }} rounded-lg transition-all {{ request()->routeIs('system.logs*') ? 'bg-white text-sidebar-green' : 'text-white hover:bg-sidebar-green-light' }}"
               title="{{ $isCollapsed ? 'System Logs' : '' }}">
                <div class="flex items-center {{ $isCollapsed ? '' : 'gap-3' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    @if(!$isCollapsed)
                    <span class="font-medium">System Logs</span>
                    @endif
                </div>
            </a>

            <a href="{{ route('admin.loan-categories.index') }}"
               class="w-full flex items-center {{ $isCollapsed ? 'justify-center' : 'justify-between' }} {{ $isCollapsed ? 'px-4 py-3' : 'px-4 py-3' }} rounded-lg transition-all {{ request()->routeIs('admin.loan-categories*') ? 'bg-white text-sidebar-green' : 'text-white hover:bg-sidebar-green-light' }}"
               title="{{ $isCollapsed ? 'Loan Categories' : '' }}">
                <div class="flex items-center {{ $isCollapsed ? '' : 'gap-3' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                    @if(!$isCollapsed)
                    <span class="font-medium">Loan Categories</span>
                    @endif
                </div>
            </a>
            @endif
        </div>
    </nav>

    <!-- Logout Button -->
    <div class="{{ $isCollapsed ? 'px-2' : 'px-3' }} py-4 border-t border-sidebar-green-light/30">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full flex items-center {{ $isCollapsed ? 'justify-center' : 'gap-3' }} px-4 py-3 text-white hover:bg-sidebar-green-light rounded-lg transition-all"
                    title="{{ $isCollapsed ? 'Logout' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                @if(!$isCollapsed)
                <span class="font-medium">Logout</span>
                @endif
            </button>
        </form>
    </div>
</div>
