<tr class="{{ $level ? 'bg-gray-50/50' : '' }}">
    <td class="px-6 py-4 whitespace-nowrap">
        <div class="flex items-center" style="padding-left: {{ $level * 24 }}px;">
            @if($level)
                <span class="text-gray-400 mr-2">↳</span>
            @endif
            <span class="text-sm font-medium text-gray-900">{{ __($item->label_key) }}</span>
            <span class="ml-2 text-xs text-gray-400">({{ $item->key }})</span>
        </div>
    </td>
    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
        {{ $item->route ?? '—' }}
    </td>
    <td class="px-6 py-4 whitespace-nowrap text-center">
        <button
            type="button"
            wire:click="toggleVisible({{ $item->id }})"
            class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-sidebar-green focus:ring-offset-2 {{ $item->is_visible ? 'bg-sidebar-green' : 'bg-gray-200' }}"
            role="switch"
        >
            <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out {{ $item->is_visible ? 'translate-x-5' : 'translate-x-1' }}"></span>
        </button>
    </td>
    <td class="px-6 py-4 whitespace-nowrap text-center">
        <button
            type="button"
            wire:click="toggleEnabled({{ $item->id }})"
            class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-sidebar-green focus:ring-offset-2 {{ $item->is_enabled ? 'bg-sidebar-green' : 'bg-gray-200' }}"
            role="switch"
        >
            <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out {{ $item->is_enabled ? 'translate-x-5' : 'translate-x-1' }}"></span>
        </button>
    </td>
</tr>
