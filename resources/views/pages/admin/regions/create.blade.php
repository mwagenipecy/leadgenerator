<x-app-layout>
    <div class="p-6">
        <div class="mb-6">
            <a href="{{ route('admin.regions.index') }}"
               class="text-sidebar-green hover:text-sidebar-green-light flex items-center mb-4">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                {{ __('admin.back_to_regions') }}
            </a>
            <h1 class="text-2xl font-bold text-black">{{ __('admin.add_region') }}</h1>
            <p class="text-gray-600 mt-1">{{ __('admin.add_region_description') }}</p>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 max-w-2xl">
            <form action="{{ route('admin.regions.store') }}" method="POST">
                @csrf

                <div class="space-y-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-black mb-2">
                            {{ __('admin.region_name') }} *
                        </label>
                        <input type="text"
                               id="name"
                               name="name"
                               value="{{ old('name') }}"
                               required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green @error('name') border-red-500 @enderror"
                               placeholder="e.g., Dar es Salaam">
                        @error('name')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="code" class="block text-sm font-medium text-black mb-2">
                            {{ __('admin.region_code') }}
                        </label>
                        <input type="text"
                               id="code"
                               name="code"
                               value="{{ old('code') }}"
                               maxlength="20"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green @error('code') border-red-500 @enderror"
                               placeholder="e.g., DSM">
                        @error('code')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="sort_order" class="block text-sm font-medium text-black mb-2">
                                {{ __('admin.sort_order') }}
                            </label>
                            <input type="number"
                                   id="sort_order"
                                   name="sort_order"
                                   value="{{ old('sort_order', 0) }}"
                                   min="0"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green @error('sort_order') border-red-500 @enderror">
                            @error('sort_order')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 mt-1">{{ __('admin.sort_order_hint') }}</p>
                        </div>

                        <div class="flex items-end">
                            <label class="flex items-center space-x-3 cursor-pointer">
                                <input type="checkbox"
                                       name="is_active"
                                       value="1"
                                       {{ old('is_active', true) ? 'checked' : '' }}
                                       class="w-4 h-4 text-sidebar-green border-gray-300 rounded focus:ring-sidebar-green">
                                <span class="text-sm font-medium text-black">{{ __('common.active') }}</span>
                            </label>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                        <a href="{{ route('admin.regions.index') }}"
                           class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                            {{ __('admin.cancel') }}
                        </a>
                        <button type="submit"
                                class="px-6 py-2 bg-sidebar-green hover:bg-sidebar-green-light text-white rounded-lg font-semibold transition-colors">
                            {{ __('admin.create_region') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
