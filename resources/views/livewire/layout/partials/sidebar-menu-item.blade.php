@props(['item', 'isCollapsed', 'badge' => null])
@php
$routeName = $item->route;
$isActive = $routeName && request()->routeIs($routeName . '*');
$href = $item->is_enabled && $routeName ? route($routeName) : '#';
$baseClass = 'w-full flex items-center ' . ($isCollapsed ? 'justify-center' : 'justify-between') . ' px-4 py-3 rounded-lg transition-all relative ';
$activeClass = $isActive ? 'bg-white text-sidebar-green' : 'text-white hover:bg-sidebar-green-light';
$disabledClass = !$item->is_enabled ? 'opacity-60 cursor-not-allowed' : '';
@endphp
@if($item->is_enabled)
<a href="{{ $href }}"
   class="{{ $baseClass }}{{ $activeClass }} {{ $disabledClass }}"
   title="{{ $isCollapsed ? __($item->label_key) : '' }}">
@else
<span class="{{ $baseClass }} text-white/70 {{ $disabledClass }}"
      title="{{ $isCollapsed ? __($item->label_key) : '' }}">
@endif
    <div class="flex items-center {{ $isCollapsed ? '' : 'gap-3 min-w-0' }}">
        <x-sidebar-icon :icon="$item->icon ?? 'document-text'" class="w-5 h-5 flex-shrink-0" />
        @if(!$isCollapsed)
        <span class="font-medium truncate">{{ __($item->label_key) }}</span>
        @endif
    </div>
    @if($badge !== null && $badge > 0)
        @if(!$isCollapsed)
        <span class="bg-sidebar-green text-white text-xs px-2 py-1 rounded-full font-bold">{{ $badge }}</span>
        @else
        <span class="absolute top-1 right-1 bg-white text-sidebar-green text-xs px-1.5 py-0.5 rounded-full font-bold">{{ $badge }}</span>
        @endif
    @elseif(!$isCollapsed && $isActive)
        <x-sidebar-icon icon="chevron-right" class="w-4 h-4" />
    @endif
@if($item->is_enabled)
</a>
@else
</span>
@endif
