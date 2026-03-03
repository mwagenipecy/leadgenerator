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

    <!-- Menu Items (from database) -->
    <nav class="flex-1 {{ $isCollapsed ? 'px-2' : 'px-3' }} py-4 overflow-y-auto" style="scrollbar-width: thin; scrollbar-color: rgba(255,255,255,0.2) transparent;">
        <div class="space-y-1">
            @foreach ($menuTree as $item)
                @php
                    $badge = null;
                    if ($item->key === 'lead_management') {
                        $badge = \DB::table('applications')->count();
                    } elseif ($item->key === 'loan_applications' && auth()->check()) {
                        $badge = \DB::table('applications')->where('user_id', auth()->user()->id)->count();
                    } elseif ($item->key === 'company_verification') {
                        $badge = \App\Models\User::where('registration_type', 'company')->where('company_verification_status', 'pending')->count();
                    } elseif ($item->key === 'lender_management') {
                        $badge = \App\Models\Lender::pending()->count();
                    }
                @endphp
                @if ($item->children->isNotEmpty())
                    @include('livewire.layout.partials.sidebar-menu-parent', ['item' => $item])
                @else
                    @include('livewire.layout.partials.sidebar-menu-item', ['item' => $item, 'isCollapsed' => $isCollapsed, 'badge' => $badge])
                @endif
            @endforeach
        </div>
    </nav>

    <!-- Logout Button -->
    <div class="{{ $isCollapsed ? 'px-2' : 'px-3' }} py-4 border-t border-sidebar-green-light/30">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full flex items-center {{ $isCollapsed ? 'justify-center' : 'gap-3' }} px-4 py-3 text-white hover:bg-sidebar-green-light rounded-lg transition-all"
                    title="{{ $isCollapsed ? __('common.logout') : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                @if(!$isCollapsed)
                <span class="font-medium">{{ __('common.logout') }}</span>
                @endif
            </button>
        </form>
    </div>
</div>
