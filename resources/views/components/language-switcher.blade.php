@php
    // Always read from session first, then fallback to app locale
    // Session has highest priority for language switching
    $currentLocale = session()->get('locale');
    
    // If no session locale, check app locale
    if (empty($currentLocale)) {
        $currentLocale = app()->getLocale();
    }
    
    // Ensure it's a valid locale
    if (!in_array($currentLocale, ['en', 'sw'])) {
        $currentLocale = 'en'; // Default to English if invalid
    }
@endphp

<div class="relative" x-data="{ open: false }">
    <button 
        type="button"
        @click="open = !open"
        class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-gray-100 transition-colors text-sm font-medium text-gray-700 cursor-pointer"
        title="{{ __('common.language') }}">
        <!-- Language Icon -->
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/>
        </svg>
        <!-- Current Language -->
        <span class="hidden sm:inline">{{ $currentLocale === 'en' ? __('common.english') : __('common.swahili') }}</span>
        <span class="sm:hidden uppercase">{{ $currentLocale }}</span>
        <!-- Dropdown Arrow -->
        <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
        </svg>
    </button>

    <!-- Dropdown Menu -->
    <div 
        x-show="open"
        x-cloak
        @click.away="open = false"
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-[9999] language-dropdown">
        <a 
            href="{{ route('language.switch', 'en') }}"
            class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors {{ $currentLocale === 'en' ? 'bg-sidebar-green-50 text-sidebar-green font-medium' : '' }}">
            <span class="w-6 text-center font-semibold">EN</span>
            <span>{{ __('common.english') }}</span>
            @if($currentLocale === 'en')
                <svg class="w-4 h-4 ml-auto text-sidebar-green" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                </svg>
            @endif
        </a>
        <a 
            href="{{ route('language.switch', 'sw') }}"
            class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors {{ $currentLocale === 'sw' ? 'bg-sidebar-green-50 text-sidebar-green font-medium' : '' }}">
            <span class="w-6 text-center font-semibold">SW</span>
            <span>{{ __('common.swahili') }}</span>
            @if($currentLocale === 'sw')
                <svg class="w-4 h-4 ml-auto text-sidebar-green" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                </svg>
            @endif
        </a>
    </div>
</div>

