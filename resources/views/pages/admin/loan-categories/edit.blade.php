<x-app-layout>
    <div class="p-6">
        <div class="mb-6">
            <a href="{{ route('admin.loan-categories.index') }}" 
               class="text-sidebar-green hover:text-sidebar-green-light flex items-center mb-4">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                {{ __('admin.back_to_categories') }}
            </a>
            <h1 class="text-2xl font-bold text-black">{{ __('admin.edit_loan_category') }}</h1>
            <p class="text-gray-600 mt-1">{{ __('admin.update_loan_category_details') }}</p>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 max-w-2xl">
            <form action="{{ route('admin.loan-categories.update', $loanCategory->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="name_en" class="block text-sm font-medium text-black mb-2">
                                {{ __('admin.category_name_english') }} *
                            </label>
                            <input type="text"
                                   id="name_en"
                                   name="name_en"
                                   value="{{ old('name_en', $loanCategory->name_en ?? $loanCategory->name) }}"
                                   required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green @error('name_en') border-red-500 @enderror"
                                   placeholder="{{ __('admin.category_name_placeholder_en') }}">
                            @error('name_en')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="name_sw" class="block text-sm font-medium text-black mb-2">
                                {{ __('admin.category_name_swahili') }} *
                            </label>
                            <input type="text"
                                   id="name_sw"
                                   name="name_sw"
                                   value="{{ old('name_sw', $loanCategory->name_sw ?? $loanCategory->name) }}"
                                   required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green @error('name_sw') border-red-500 @enderror"
                                   placeholder="{{ __('admin.category_name_placeholder_sw') }}">
                            @error('name_sw')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="description_en" class="block text-sm font-medium text-black mb-2">
                                {{ __('admin.description_english') }}
                            </label>
                            <textarea id="description_en"
                                      name="description_en"
                                      rows="4"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green @error('description_en') border-red-500 @enderror"
                                      placeholder="{{ __('admin.category_description_placeholder_en') }}">{{ old('description_en', $loanCategory->description_en ?? $loanCategory->description) }}</textarea>
                            @error('description_en')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="description_sw" class="block text-sm font-medium text-black mb-2">
                                {{ __('admin.description_swahili') }}
                            </label>
                            <textarea id="description_sw"
                                      name="description_sw"
                                      rows="4"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green @error('description_sw') border-red-500 @enderror"
                                      placeholder="{{ __('admin.category_description_placeholder_sw') }}">{{ old('description_sw', $loanCategory->description_sw ?? $loanCategory->description) }}</textarea>
                            @error('description_sw')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="image" class="block text-sm font-medium text-black mb-2">
                            {{ __('admin.category_image') }}
                        </label>
                        <input type="file"
                               id="image"
                               name="image"
                               accept=".jpg,.jpeg,.png,.webp,.svg,image/jpeg,image/png,image/webp,image/svg+xml"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green @error('image') border-red-500 @enderror">
                        @error('image')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">{{ __('admin.upload_image_hint') }}</p>

                        @if($loanCategory->image_path)
                            <div class="mt-3">
                                <p class="text-xs text-gray-500 mb-2">{{ __('admin.current_image') }}</p>
                                <div class="h-20 w-36 border border-gray-200 rounded-lg flex items-center justify-center bg-white">
                                    <img src="{{ asset('storage/' . $loanCategory->image_path) }}" alt="{{ $loanCategory->name }}" class="max-h-14 w-auto object-contain">
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="sort_order" class="block text-sm font-medium text-black mb-2">
                                {{ __('admin.sort_order') }}
                            </label>
                            <input type="number" 
                                   id="sort_order" 
                                   name="sort_order" 
                                   value="{{ old('sort_order', $loanCategory->sort_order) }}"
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
                                       {{ old('is_active', $loanCategory->is_active) ? 'checked' : '' }}
                                       class="w-4 h-4 text-sidebar-green border-gray-300 rounded focus:ring-sidebar-green">
                                <span class="text-sm font-medium text-black">{{ __('common.active') }}</span>
                            </label>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                        <a href="{{ route('admin.loan-categories.index') }}" 
                           class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                            {{ __('common.cancel') }}
                        </a>
                        <button type="submit" 
                                class="px-6 py-2 bg-sidebar-green hover:bg-sidebar-green-light text-white rounded-lg font-semibold transition-colors">
                            {{ __('admin.update_category') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

