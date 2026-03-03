<div>
    <div class="p-8">
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-4xl font-bold text-gray-900 mb-2">{{ __('navigation.menu_management') }}</h1>
                    <p class="text-gray-600 text-lg">{{ __('admin.menu_management_description') ?? 'Show or hide sidebar menu items and enable or disable menu links for all users.' }}</p>
                </div>
            </div>
        </div>

        @if (session()->has('message'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-6 py-4 rounded-lg mb-6 flex items-center space-x-3">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <span>{{ session('message') }}</span>
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">{{ __('admin.menu_item') ?? 'Menu Item' }}</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">{{ __('admin.route') ?? 'Route' }}</th>
                            <th scope="col" class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">{{ __('admin.show_in_menu') ?? 'Show in menu' }}</th>
                            <th scope="col" class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">{{ __('admin.enable_link') ?? 'Enable link' }}</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($menuItems as $item)
                            @include('livewire.admin.partials.menu-management-row', ['item' => $item, 'level' => 0])
                            @foreach ($item->children as $child)
                                @include('livewire.admin.partials.menu-management-row', ['item' => $child, 'level' => 1])
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <p class="mt-4 text-sm text-gray-500">
            {{ __('admin.menu_management_hint') ?? 'When "Show in menu" is off, the item is hidden from the sidebar. When "Enable link" is off, the item still appears but the link is disabled (greyed out).' }}
        </p>
    </div>
</div>
