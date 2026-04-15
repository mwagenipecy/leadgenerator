<div>
    <div class="p-8">
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-4xl font-bold text-gray-900 mb-2">{{ __('slider.hero_slider_management') }}</h1>
                    <p class="text-gray-600 text-lg">{{ __('slider.manage_hero_slider') }}</p>
                </div>
                <div class="flex items-center space-x-3">
                    <button wire:click="openCreateModal" 
                            class="bg-sidebar-green text-white px-6 py-2 rounded-lg font-semibold hover:bg-sidebar-green-light transition-all duration-200 shadow-lg shadow-sidebar-green/25 inline-flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        {{ __('slider.add_image') }}
                    </button>
                </div>
            </div>
        </div>

        <!-- Flash Messages -->
        @if (session()->has('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-6 py-4 rounded-lg mb-6 flex items-center space-x-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- Sliders Table -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">English Hero Image</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Swahili Hero Image</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Title</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">{{ __('slider.order') }}</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($sliders as $slider)
                            <tr>
                                <td class="px-4 py-3">
                                    @if($slider->image_path_en ?? $slider->image_path)
                                        <img src="{{ asset('storage/' . ($slider->image_path_en ?? $slider->image_path)) }}" alt="English hero" class="h-16 w-28 object-cover rounded-md border border-gray-200">
                                    @else
                                        <span class="text-xs text-gray-500">No image</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    @if($slider->image_path_sw)
                                        <img src="{{ asset('storage/' . $slider->image_path_sw) }}" alt="Swahili hero" class="h-16 w-28 object-cover rounded-md border border-gray-200">
                                    @else
                                        <span class="text-xs text-gray-500">No image</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <p class="font-semibold text-gray-900">{{ $slider->title ?? 'Untitled' }}</p>
                                    @if($slider->description)
                                        <p class="text-xs text-gray-500 mt-1">{{ Str::limit($slider->description, 70) }}</p>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ $slider->order }}</td>
                                <td class="px-4 py-3">
                                    @if($slider->is_active)
                                        <span class="inline-flex items-center rounded-full bg-green-100 text-green-700 px-2.5 py-1 text-xs font-medium">{{ __('slider.active') }}</span>
                                    @else
                                        <span class="inline-flex items-center rounded-full bg-gray-100 text-gray-600 px-2.5 py-1 text-xs font-medium">{{ __('slider.inactive') }}</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center justify-end space-x-2">
                                        <button wire:click="openEditModal({{ $slider->id }})"
                                                class="p-2 text-sidebar-green hover:bg-sidebar-green/10 rounded-lg transition-colors"
                                                title="{{ __('slider.edit') }}">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </button>
                                        <button wire:click="openDeleteModal({{ $slider->id }})"
                                                class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                                title="{{ __('slider.delete') }}">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-10 text-center">
                                    <p class="text-gray-500 text-lg">{{ __('slider.no_slider_images') }}</p>
                                    <p class="text-gray-400 text-sm mt-2">{{ __('slider.click_add_image') }}</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        @if($sliders->hasPages())
            <div class="mt-8">
                {{ $sliders->links() }}
            </div>
        @endif
    </div>

    <!-- Create Modal -->
    @if($showCreateModal)
    <div class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4" wire:click="closeCreateModal">
        <div class="bg-white rounded-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto" x-data="{ previewEn: null, previewSw: null }" x-on:click.stop>
            <div class="p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-4">{{ __('slider.add_hero_slider_image') }}</h3>
                
                <form wire:submit.prevent="store">
                    <div class="space-y-4">
                        <!-- English Image Upload -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">English Hero Image *</label>
                            <input type="file" wire:model="imageEnglish" x-on:change="previewEn = $event.target.files[0] ? URL.createObjectURL($event.target.files[0]) : null" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp" class="w-full border border-gray-300 rounded-lg p-2">
                            <p class="text-xs text-gray-500 mt-1">Allowed: JPG, PNG, WEBP (max 5MB).</p>
                            @error('imageEnglish') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            <template x-if="previewEn">
                                <div class="mt-2">
                                    <img :src="previewEn" alt="Preview" class="max-w-full h-48 object-cover rounded-lg">
                                </div>
                            </template>
                        </div>

                        <!-- Swahili Image Upload -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Swahili Hero Image *</label>
                            <input type="file" wire:model="imageSwahili" x-on:change="previewSw = $event.target.files[0] ? URL.createObjectURL($event.target.files[0]) : null" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp" class="w-full border border-gray-300 rounded-lg p-2">
                            <p class="text-xs text-gray-500 mt-1">Allowed: JPG, PNG, WEBP (max 5MB).</p>
                            @error('imageSwahili') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            <template x-if="previewSw">
                                <div class="mt-2">
                                    <img :src="previewSw" alt="Preview" class="max-w-full h-48 object-cover rounded-lg">
                                </div>
                            </template>
                        </div>

                        <!-- Title -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Title (Optional)</label>
                            <input type="text" wire:model="title" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                            @error('title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Description -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Description (Optional)</label>
                            <textarea wire:model="description" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2"></textarea>
                            @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Order -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Display Order</label>
                            <input type="number" wire:model="order" min="0" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                            @error('order') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Active Status -->
                        <div class="flex items-center">
                            <input type="checkbox" wire:model="is_active" id="create_is_active" class="rounded border-gray-300 text-sidebar-green focus:ring-sidebar-green">
                            <label for="create_is_active" class="ml-2 text-sm text-gray-700">Active</label>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3 mt-6">
                        <button type="button" wire:click="closeCreateModal" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Cancel</button>
                        <button type="submit" class="px-6 py-2 bg-sidebar-green text-white rounded-lg hover:bg-sidebar-green-light">Add Image</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <!-- Edit Modal -->
    @if($showEditModal && $selectedSlider)
    <div class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4" wire:click="closeEditModal">
        <div class="bg-white rounded-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto" x-data="{ previewEn: null, previewSw: null }" x-on:click.stop>
            <div class="p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-4">Edit Hero Slider Image</h3>
                
                <form wire:submit.prevent="update">
                    <div class="space-y-4">
                        <!-- Current English Image Preview -->
                        @if($imagePreviewEnglish)
                            <div x-show="!previewEn">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Current English Hero Image</label>
                                <img src="{{ asset('storage/' . $imagePreviewEnglish) }}" alt="Current English image" class="max-w-full h-48 object-cover rounded-lg">
                            </div>
                        @endif

                        <!-- Current Swahili Image Preview -->
                        @if($imagePreviewSwahili)
                            <div x-show="!previewSw">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Current Swahili Hero Image</label>
                                <img src="{{ asset('storage/' . $imagePreviewSwahili) }}" alt="Current Swahili image" class="max-w-full h-48 object-cover rounded-lg">
                            </div>
                        @endif

                        <!-- English Image Upload -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Change English Hero Image (Optional)</label>
                            <input type="file" wire:model="imageEnglish" x-on:change="previewEn = $event.target.files[0] ? URL.createObjectURL($event.target.files[0]) : null" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp" class="w-full border border-gray-300 rounded-lg p-2">
                            <p class="text-xs text-gray-500 mt-1">Allowed: JPG, PNG, WEBP (max 5MB).</p>
                            @error('imageEnglish') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            <template x-if="previewEn">
                                <div class="mt-2">
                                    <img :src="previewEn" alt="Preview" class="max-w-full h-48 object-cover rounded-lg">
                                </div>
                            </template>
                        </div>

                        <!-- Swahili Image Upload -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Change Swahili Hero Image (Optional)</label>
                            <input type="file" wire:model="imageSwahili" x-on:change="previewSw = $event.target.files[0] ? URL.createObjectURL($event.target.files[0]) : null" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp" class="w-full border border-gray-300 rounded-lg p-2">
                            <p class="text-xs text-gray-500 mt-1">Allowed: JPG, PNG, WEBP (max 5MB).</p>
                            @error('imageSwahili') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            <template x-if="previewSw">
                                <div class="mt-2">
                                    <img :src="previewSw" alt="Preview" class="max-w-full h-48 object-cover rounded-lg">
                                </div>
                            </template>
                        </div>

                        <!-- Title -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Title (Optional)</label>
                            <input type="text" wire:model="title" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                            @error('title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Description -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Description (Optional)</label>
                            <textarea wire:model="description" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2"></textarea>
                            @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Order -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Display Order</label>
                            <input type="number" wire:model="order" min="0" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                            @error('order') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Active Status -->
                        <div class="flex items-center">
                            <input type="checkbox" wire:model="is_active" id="edit_is_active" class="rounded border-gray-300 text-sidebar-green focus:ring-sidebar-green">
                            <label for="edit_is_active" class="ml-2 text-sm text-gray-700">Active</label>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3 mt-6">
                        <button type="button" wire:click="closeEditModal" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Cancel</button>
                        <button type="submit" class="px-6 py-2 bg-sidebar-green text-white rounded-lg hover:bg-sidebar-green-light">Update Image</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <!-- Delete Modal -->
    @if($showDeleteModal && $selectedSlider)
    <div class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4" wire:click="closeDeleteModal">
        <div class="bg-white rounded-2xl max-w-md w-full" x-on:click.stop>
            <div class="p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-4">Delete Hero Slider</h3>
                <p class="text-gray-600 mb-6">Are you sure you want to delete this slider image? This action cannot be undone.</p>
                <div class="flex justify-end space-x-3">
                    <button wire:click="closeDeleteModal" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Cancel</button>
                    <button wire:click="delete" class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">Delete</button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
