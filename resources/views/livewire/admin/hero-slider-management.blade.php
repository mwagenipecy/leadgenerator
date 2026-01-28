<div>
    <div class="p-8">
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-4xl font-bold text-gray-900 mb-2">Hero Slider Management</h1>
                    <p class="text-gray-600 text-lg">Manage hero section slider images</p>
                </div>
                <div class="flex items-center space-x-3">
                    <button wire:click="openCreateModal" 
                            class="bg-sidebar-green text-white px-6 py-2 rounded-lg font-semibold hover:bg-sidebar-green-light transition-all duration-200 shadow-lg shadow-sidebar-green/25 inline-flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Add Image
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

        <!-- Sliders Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($sliders as $slider)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg transition-shadow">
                    <div class="relative h-48 overflow-hidden">
                        <img src="{{ asset('storage/' . $slider->image_path) }}" 
                             alt="{{ $slider->title ?? 'Hero Slider' }}" 
                             class="w-full h-full object-cover">
                        @if($slider->is_active)
                            <span class="absolute top-2 right-2 bg-green-500 text-white px-2 py-1 rounded-full text-xs font-medium">Active</span>
                        @else
                            <span class="absolute top-2 right-2 bg-gray-400 text-white px-2 py-1 rounded-full text-xs font-medium">Inactive</span>
                        @endif
                    </div>
                    <div class="p-4">
                        <h3 class="font-semibold text-gray-900 mb-1">{{ $slider->title ?? 'Untitled' }}</h3>
                        @if($slider->description)
                            <p class="text-sm text-gray-600 mb-2">{{ Str::limit($slider->description, 60) }}</p>
                        @endif
                        <div class="flex items-center justify-between mt-4">
                            <span class="text-xs text-gray-500">Order: {{ $slider->order }}</span>
                            <div class="flex items-center space-x-2">
                                <button wire:click="openEditModal({{ $slider->id }})" 
                                        class="p-2 text-sidebar-green hover:bg-sidebar-green/10 rounded-lg transition-colors" 
                                        title="Edit">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>
                                <button wire:click="openDeleteModal({{ $slider->id }})" 
                                        class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" 
                                        title="Delete">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <p class="text-gray-500 text-lg">No slider images found.</p>
                    <p class="text-gray-400 text-sm mt-2">Click "Add Image" to add your first slider image.</p>
                </div>
            @endforelse
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
        <div class="bg-white rounded-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto" wire:click.stop>
            <div class="p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-4">Add Hero Slider Image</h3>
                
                <form wire:submit.prevent="store">
                    <div class="space-y-4">
                        <!-- Image Upload -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Image *</label>
                            <input type="file" wire:model="image" accept="image/*" class="w-full border border-gray-300 rounded-lg p-2">
                            @error('image') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            @if($image)
                                <div class="mt-2">
                                    <img src="{{ $image->temporaryUrl() }}" alt="Preview" class="max-w-full h-48 object-cover rounded-lg">
                                </div>
                            @endif
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
        <div class="bg-white rounded-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto" wire:click.stop>
            <div class="p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-4">Edit Hero Slider Image</h3>
                
                <form wire:submit.prevent="update">
                    <div class="space-y-4">
                        <!-- Current Image Preview -->
                        @if($imagePreview && !$image)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Current Image</label>
                                <img src="{{ asset('storage/' . $imagePreview) }}" alt="Current" class="max-w-full h-48 object-cover rounded-lg">
                            </div>
                        @endif

                        <!-- Image Upload -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Change Image (Optional)</label>
                            <input type="file" wire:model="image" accept="image/*" class="w-full border border-gray-300 rounded-lg p-2">
                            @error('image') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            @if($image)
                                <div class="mt-2">
                                    <img src="{{ $image->temporaryUrl() }}" alt="Preview" class="max-w-full h-48 object-cover rounded-lg">
                                </div>
                            @endif
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
        <div class="bg-white rounded-2xl max-w-md w-full" wire:click.stop>
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
