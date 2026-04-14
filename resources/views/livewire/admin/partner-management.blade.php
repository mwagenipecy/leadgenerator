<div>
    <div class="p-8">
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-4xl font-bold text-gray-900 mb-2">Partner Management</h1>
                    <p class="text-gray-600 text-lg">Manage partner names, logos, and website links</p>
                </div>
                <button
                    wire:click="openCreateModal"
                    class="bg-sidebar-green text-white px-6 py-2 rounded-lg font-semibold hover:bg-sidebar-green-light transition-all duration-200 shadow-lg shadow-sidebar-green/25 inline-flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Add Partner
                </button>
            </div>
        </div>

        @if (session()->has('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-6 py-4 rounded-lg mb-6">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr class="text-left text-gray-600">
                            <th class="px-5 py-3 font-semibold">Logo</th>
                            <th class="px-5 py-3 font-semibold">Partner Name</th>
                            <th class="px-5 py-3 font-semibold">Website</th>
                            <th class="px-5 py-3 font-semibold">Order</th>
                            <th class="px-5 py-3 font-semibold">Status</th>
                            <th class="px-5 py-3 font-semibold text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($partners as $partner)
                            <tr class="hover:bg-gray-50/80">
                                <td class="px-5 py-3">
                                    <div class="h-12 w-24 border border-gray-100 rounded-lg flex items-center justify-center bg-white">
                                        <img src="{{ asset('storage/' . $partner->logo_path) }}" alt="{{ $partner->name }}" class="max-h-8 w-auto object-contain">
                                    </div>
                                </td>
                                <td class="px-5 py-3 font-medium text-gray-900">{{ $partner->name }}</td>
                                <td class="px-5 py-3">
                                    <a href="{{ $partner->website_url }}" target="_blank" class="text-blue-600 hover:underline break-all">
                                        {{ $partner->website_url }}
                                    </a>
                                </td>
                                <td class="px-5 py-3 text-gray-700">{{ $partner->order }}</td>
                                <td class="px-5 py-3">
                                    @if($partner->is_active)
                                        <span class="inline-flex items-center rounded-full bg-green-100 text-green-700 px-2.5 py-1 text-xs font-semibold">Active</span>
                                    @else
                                        <span class="inline-flex items-center rounded-full bg-gray-100 text-gray-600 px-2.5 py-1 text-xs font-semibold">Inactive</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3">
                                    <div class="flex items-center justify-end gap-2">
                                        <button wire:click="openEditModal({{ $partner->id }})" class="p-2 text-sidebar-green hover:bg-sidebar-green/10 rounded-lg">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </button>
                                        <button wire:click="openDeleteModal({{ $partner->id }})" class="p-2 text-red-600 hover:bg-red-50 rounded-lg">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-10 text-center text-gray-500">
                                    No partners yet. Click "Add Partner" to get started.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($partners->hasPages())
            <div class="mt-8">
                {{ $partners->links() }}
            </div>
        @endif
    </div>

    @if($showCreateModal)
        <div class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4" wire:click="closeCreateModal">
            <div class="bg-white rounded-2xl max-w-xl w-full p-6" wire:click.stop>
                <h3 class="text-xl font-bold text-gray-900 mb-4">Add Partner</h3>
                <form wire:submit.prevent="store" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Name *</label>
                        <input type="text" wire:model="name" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                        @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Website URL *</label>
                        <input type="url" wire:model="website_url" class="w-full border border-gray-300 rounded-lg px-3 py-2" placeholder="https://example.com">
                        @error('website_url') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Logo *</label>
                        <input type="file" wire:model="logo" accept=".jpg,.jpeg,.png,.webp,.svg,image/jpeg,image/png,image/webp,image/svg+xml" class="w-full border border-gray-300 rounded-lg p-2">
                        @error('logo') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        @if($logo)
                            <img src="{{ $logo->temporaryUrl() }}" alt="Preview" class="mt-2 h-16 object-contain rounded border border-gray-100 px-2 py-1">
                        @endif
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Display Order</label>
                        <input type="number" wire:model="order" min="0" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                        @error('order') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" wire:model="is_active" id="create_partner_active" class="rounded border-gray-300 text-sidebar-green">
                        <label for="create_partner_active" class="ml-2 text-sm text-gray-700">Active</label>
                    </div>
                    <div class="flex justify-end gap-3">
                        <button type="button" wire:click="closeCreateModal" class="px-5 py-2 border border-gray-300 rounded-lg text-gray-700">Cancel</button>
                        <button type="submit" class="px-5 py-2 bg-sidebar-green text-white rounded-lg">Add Partner</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    @if($showEditModal && $selectedPartner)
        <div class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4" wire:click="closeEditModal">
            <div class="bg-white rounded-2xl max-w-xl w-full p-6" wire:click.stop>
                <h3 class="text-xl font-bold text-gray-900 mb-4">Edit Partner</h3>
                <form wire:submit.prevent="update" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Name *</label>
                        <input type="text" wire:model="name" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                        @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Website URL *</label>
                        <input type="url" wire:model="website_url" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                        @error('website_url') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Change Logo (Optional)</label>
                        <input type="file" wire:model="logo" accept=".jpg,.jpeg,.png,.webp,.svg,image/jpeg,image/png,image/webp,image/svg+xml" class="w-full border border-gray-300 rounded-lg p-2">
                        @error('logo') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        @if($logo)
                            <img src="{{ $logo->temporaryUrl() }}" alt="Preview" class="mt-2 h-16 object-contain rounded border border-gray-100 px-2 py-1">
                        @elseif($logoPreview)
                            <img src="{{ asset('storage/' . $logoPreview) }}" alt="Current logo" class="mt-2 h-16 object-contain rounded border border-gray-100 px-2 py-1">
                        @endif
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Display Order</label>
                        <input type="number" wire:model="order" min="0" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                        @error('order') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" wire:model="is_active" id="edit_partner_active" class="rounded border-gray-300 text-sidebar-green">
                        <label for="edit_partner_active" class="ml-2 text-sm text-gray-700">Active</label>
                    </div>
                    <div class="flex justify-end gap-3">
                        <button type="button" wire:click="closeEditModal" class="px-5 py-2 border border-gray-300 rounded-lg text-gray-700">Cancel</button>
                        <button type="submit" class="px-5 py-2 bg-sidebar-green text-white rounded-lg">Update Partner</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    @if($showDeleteModal && $selectedPartner)
        <div class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4" wire:click="closeDeleteModal">
            <div class="bg-white rounded-2xl max-w-md w-full p-6" wire:click.stop>
                <h3 class="text-xl font-bold text-gray-900 mb-4">Delete Partner</h3>
                <p class="text-gray-600 mb-6">Are you sure you want to delete "{{ $selectedPartner->name }}"?</p>
                <div class="flex justify-end gap-3">
                    <button wire:click="closeDeleteModal" class="px-5 py-2 border border-gray-300 rounded-lg text-gray-700">Cancel</button>
                    <button wire:click="delete" class="px-5 py-2 bg-red-600 text-white rounded-lg">Delete</button>
                </div>
            </div>
        </div>
    @endif
</div>
