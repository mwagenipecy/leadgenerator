@props(['item', 'isCollapsed'])
@php
$isParentActive = $item->children->contains(fn($c) => $c->route && request()->routeIs($c->route . '*'));
@endphp
@if(!$isCollapsed)
<div x-data="{ isOpen: {{ $isParentActive ? 'true' : 'false' }} }">
    <button
        type="button"
        @click="isOpen = !isOpen"
        class="w-full flex items-center justify-between px-4 py-3 rounded-lg transition-all {{ $isParentActive ? 'bg-white text-sidebar-green' : 'text-white hover:bg-sidebar-green-light' }}"
    >
        <div class="flex items-center gap-3 min-w-0">
            <x-sidebar-icon :icon="$item->icon ?? 'document-text'" class="w-5 h-5 flex-shrink-0" />
            <span class="font-medium truncate">{{ __($item->label_key) }}</span>
        </div>
        <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': isOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
        </svg>
    </button>
    <div x-show="isOpen" x-transition class="ml-4 mt-1 space-y-1">
        @foreach($item->children as $child)
            @php
                $childActive = $child->route && request()->routeIs($child->route . '*');
            @endphp
            @if($child->is_enabled)
            <a href="{{ route($child->route) }}"
               class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all {{ $childActive ? 'bg-white/20 text-white font-semibold' : 'text-white/80 hover:bg-sidebar-green-light hover:text-white' }}">
            @else
            <span class="flex items-center gap-3 px-4 py-3 rounded-lg text-white/50 cursor-not-allowed">
            @endif
                <div class="w-2 h-2 rounded-full border-2 border-current"></div>
                <x-sidebar-icon :icon="$child->icon ?? 'document-text'" class="w-4 h-4" />
                <span class="font-medium text-sm">{{ __($child->label_key) }}</span>
            @if($child->is_enabled)
            </a>
            @else
            </span>
            @endif
        @endforeach
    </div>
</div>
@else
{{-- Collapsed: link to first child or # --}}
@php $firstChild = $item->children->first(); @endphp
@if($firstChild && $firstChild->is_enabled)
<a href="{{ route($firstChild->route) }}"
   class="w-full flex items-center justify-center px-4 py-3 rounded-lg transition-all {{ request()->routeIs($firstChild->route . '*') ? 'bg-white text-sidebar-green' : 'text-white hover:bg-sidebar-green-light' }}"
   title="{{ __($item->label_key) }}">
@else
<span class="w-full flex items-center justify-center px-4 py-3 rounded-lg text-white/70" title="{{ __($item->label_key) }}">
@endif
    <x-sidebar-icon :icon="$item->icon ?? 'document-text'" class="w-5 h-5" />
@if($firstChild && $firstChild->is_enabled)
</a>
@else
</span>
@endif
@endif
