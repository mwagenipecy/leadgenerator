<div>
    <div class="p-8">
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-4xl font-bold text-gray-900 mb-2">{{ __('admin.auth_content_management') }}</h1>
                    <p class="text-gray-600 text-lg">{{ __('admin.auth_content_management_description') }}</p>
                </div>
                <button wire:click="openCreateModal" type="button" class="bg-sidebar-green text-white px-6 py-2 rounded-lg font-semibold hover:bg-sidebar-green-light transition-all duration-200 shadow-lg shadow-sidebar-green/25 inline-flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    {{ __('common.create') }}
                </button>
            </div>
        </div>

    @if (session()->has('success'))
        <div class="mb-6 rounded-lg border border-green-300 bg-green-50 px-4 py-3 text-green-800">
            {{ session('success') }}
        </div>
    @endif

    @php
        $sections = [
            ['title' => __('admin.login_page_images'), 'enField' => 'loginImageEn', 'swField' => 'loginImageSw', 'enKey' => 'auth_side_image_login_en', 'swKey' => 'auth_side_image_login_sw'],
            ['title' => __('admin.register_page_images'), 'enField' => 'registerImageEn', 'swField' => 'registerImageSw', 'enKey' => 'auth_side_image_register_en', 'swKey' => 'auth_side_image_register_sw'],
            ['title' => __('admin.forgot_password_page_images'), 'enField' => 'forgotPasswordImageEn', 'swField' => 'forgotPasswordImageSw', 'enKey' => 'auth_side_image_forgot_password_en', 'swKey' => 'auth_side_image_forgot_password_sw'],
            ['title' => __('admin.otp_page_images'), 'enField' => 'otpImageEn', 'swField' => 'otpImageSw', 'enKey' => 'auth_side_image_otp_en', 'swKey' => 'auth_side_image_otp_sw'],
        ];
    @endphp

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Page</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">{{ __('admin.english_auth_image') }}</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">{{ __('admin.swahili_auth_image') }}</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @foreach($sections as $section)
                            <tr class="align-top">
                                <td class="px-4 py-4">
                                    <p class="font-semibold text-gray-900">{{ $section['title'] }}</p>
                                </td>
                                <td class="px-4 py-4">
                                    <img src="{{ $this->getDisplayImage(str_replace('_en', '', str_replace('auth_side_image_', '', $section['enKey'])), 'en') }}" alt="English image" class="h-24 w-40 object-cover rounded-md border border-gray-200">
                                </td>
                                <td class="px-4 py-4">
                                    <img src="{{ $this->getDisplayImage(str_replace('_sw', '', str_replace('auth_side_image_', '', $section['swKey'])), 'sw') }}" alt="Swahili image" class="h-24 w-40 object-cover rounded-md border border-gray-200">
                                </td>
                                <td class="px-4 py-4">
                                    <div class="flex items-center justify-end space-x-2">
                                        <button wire:click="openEditModal('{{ str_replace('_en', '', str_replace('auth_side_image_', '', $section['enKey'])) }}')"
                                                class="p-2 text-sidebar-green hover:bg-sidebar-green/10 rounded-lg transition-colors"
                                                title="{{ __('common.edit') }}">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </button>
                                        @if($this->hasUploadedImageForPage(str_replace('_en', '', str_replace('auth_side_image_', '', $section['enKey']))))
                                            <button wire:click="resetToDefault('{{ $section['enKey'] }}')"
                                                    class="p-2 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors"
                                                    title="{{ __('admin.reset_english_to_default') }}">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                                </svg>
                                            </button>
                                            <button wire:click="resetToDefault('{{ $section['swKey'] }}')"
                                                    class="p-2 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors"
                                                    title="{{ __('admin.reset_swahili_to_default') }}">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                                </svg>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Create Modal -->
    @if($showCreateModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4" wire:click="closeCreateModal">
            <div class="bg-white rounded-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto" wire:click.stop>
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">{{ __('common.create') }} {{ __('admin.auth_content_management') }}</h3>
                    <form wire:submit.prevent="store">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Page *</label>
                                <select wire:model="selectedPage" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                                    <option value="">Select page</option>
                                    @foreach($pages as $key => $label)
                                        <option value="{{ $key }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('selectedPage') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('admin.english_auth_image') }}</label>
                                <input type="file" wire:model="imageEnglish" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp" class="w-full border border-gray-300 rounded-lg p-2">
                                @error('imageEnglish') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                @if($imageEnglish)
                                    <img src="{{ $imageEnglish->temporaryUrl() }}" alt="Preview" class="max-w-full h-40 object-cover rounded-lg mt-2">
                                @endif
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('admin.swahili_auth_image') }}</label>
                                <input type="file" wire:model="imageSwahili" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp" class="w-full border border-gray-300 rounded-lg p-2">
                                @error('imageSwahili') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                @if($imageSwahili)
                                    <img src="{{ $imageSwahili->temporaryUrl() }}" alt="Preview" class="max-w-full h-40 object-cover rounded-lg mt-2">
                                @endif
                            </div>
                        </div>
                        <div class="flex justify-end space-x-3 mt-6">
                            <button type="button" wire:click="closeCreateModal" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">{{ __('common.cancel') }}</button>
                            <button type="submit" class="px-6 py-2 bg-sidebar-green text-white rounded-lg hover:bg-sidebar-green-light">{{ __('common.create') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Edit Modal -->
    @if($showEditModal && $selectedPage)
        <div class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4" wire:click="closeEditModal">
            <div class="bg-white rounded-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto" wire:click.stop>
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">{{ __('common.edit') }} {{ $pages[$selectedPage] ?? '' }}</h3>
                    <form wire:submit.prevent="update">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('admin.english_auth_image') }}</label>
                                <img src="{{ $this->getDisplayImage($selectedPage, 'en') }}" alt="Current English image" class="max-w-full h-40 object-cover rounded-lg mb-2">
                                <input type="file" wire:model="imageEnglish" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp" class="w-full border border-gray-300 rounded-lg p-2">
                                @error('imageEnglish') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                @if($imageEnglish)
                                    <img src="{{ $imageEnglish->temporaryUrl() }}" alt="Preview" class="max-w-full h-40 object-cover rounded-lg mt-2">
                                @endif
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('admin.swahili_auth_image') }}</label>
                                <img src="{{ $this->getDisplayImage($selectedPage, 'sw') }}" alt="Current Swahili image" class="max-w-full h-40 object-cover rounded-lg mb-2">
                                <input type="file" wire:model="imageSwahili" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp" class="w-full border border-gray-300 rounded-lg p-2">
                                @error('imageSwahili') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                @if($imageSwahili)
                                    <img src="{{ $imageSwahili->temporaryUrl() }}" alt="Preview" class="max-w-full h-40 object-cover rounded-lg mt-2">
                                @endif
                            </div>
                        </div>
                        <div class="flex justify-end space-x-3 mt-6">
                            <button type="button" wire:click="closeEditModal" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">{{ __('common.cancel') }}</button>
                            <button type="submit" class="px-6 py-2 bg-sidebar-green text-white rounded-lg hover:bg-sidebar-green-light">{{ __('common.update') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
