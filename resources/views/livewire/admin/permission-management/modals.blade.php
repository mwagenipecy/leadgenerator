<div>
{{-- resources/views/livewire/admin/permission-management/modals.blade.php --}}

<!-- Create Permission Modal -->
@if($showCreatePermissionModal)
    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" wire:click.self="$set('showCreatePermissionModal', false)">
        <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-lg shadow-lg rounded-3xl bg-white">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-2xl font-bold text-gray-900">Create New Permission</h3>
                <button wire:click="$set('showCreatePermissionModal', false)" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form wire:submit.prevent="createPermission" class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Permission Name *</label>
                    <input wire:model="name" type="text" class="w-full border border-gray-300 rounded-xl px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500" placeholder="e.g., users.create">
                    @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    <p class="text-xs text-gray-500 mt-1">Use lowercase letters, dots, and underscores only</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Display Name *</label>
                    <input wire:model="display_name" type="text" class="w-full border border-gray-300 rounded-xl px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500" placeholder="e.g., Create Users">
                    @error('display_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea wire:model="description" rows="3" class="w-full border border-gray-300 rounded-xl px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500" placeholder="Brief description of this permission..."></textarea>
                    @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Category *</label>
                    <select wire:model="category" class="w-full border border-gray-300 rounded-xl px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        <option value="">Select Category</option>
                        @foreach($categories as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('category') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="flex items-center">
                    <input wire:model="is_active" type="checkbox" id="is_active" class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                    <label for="is_active" class="ml-2 block text-sm text-gray-700">Permission is active</label>
                </div>

                <div class="flex justify-end space-x-4 pt-6">
                    <button type="button" wire:click="$set('showCreatePermissionModal', false)" 
                        class="bg-gray-100 text-gray-700 px-6 py-2 rounded-xl font-medium hover:bg-gray-200 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" 
                        class="bg-green-600 text-white px-6 py-2 rounded-xl font-semibold hover:bg-green-700 transition-colors">
                        Create Permission
                    </button>
                </div>
            </form>
        </div>
    </div>
@endif

<!-- Edit Permission Modal -->
@if($showEditPermissionModal && $selectedPermission)
    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" wire:click.self="$set('showEditPermissionModal', false)">
        <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-lg shadow-lg rounded-3xl bg-white">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-2xl font-bold text-gray-900">Edit Permission</h3>
                <button wire:click="$set('showEditPermissionModal', false)" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form wire:submit.prevent="updatePermission" class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Permission Name *</label>
                    <input wire:model="edit_name" type="text" class="w-full border border-gray-300 rounded-xl px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    @error('edit_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    <p class="text-xs text-gray-500 mt-1">Use lowercase letters, dots, and underscores only</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Display Name *</label>
                    <input wire:model="edit_display_name" type="text" class="w-full border border-gray-300 rounded-xl px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    @error('edit_display_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea wire:model="edit_description" rows="3" class="w-full border border-gray-300 rounded-xl px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500"></textarea>
                    @error('edit_description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Category *</label>
                    <select wire:model="edit_category" class="w-full border border-gray-300 rounded-xl px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        @foreach($categories as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('edit_category') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="flex items-center">
                    <input wire:model="edit_is_active" type="checkbox" id="edit_is_active" class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                    <label for="edit_is_active" class="ml-2 block text-sm text-gray-700">Permission is active</label>
                </div>

                <div class="flex justify-end space-x-4 pt-6">
                    <button type="button" wire:click="$set('showEditPermissionModal', false)" 
                        class="bg-gray-100 text-gray-700 px-6 py-2 rounded-xl font-medium hover:bg-gray-200 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" 
                        class="bg-green-600 text-white px-6 py-2 rounded-xl font-semibold hover:bg-green-700 transition-colors">
                        Update Permission
                    </button>
                </div>
            </form>
        </div>
    </div>
@endif

<!-- Delete Permission Confirmation Modal -->
@if($showDeletePermissionModal && $permissionToDelete)
    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" wire:click.self="$set('showDeletePermissionModal', false)">
        <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-md shadow-lg rounded-3xl bg-white">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-2xl font-bold text-gray-900">Delete Permission</h3>
                <button wire:click="$set('showDeletePermissionModal', false)" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <div class="mb-6">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.268 15.5c-.77.833.192 2.5 1.732 2.5z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h4 class="text-lg font-semibold text-gray-900">{{ $permissionToDelete->display_name }}</h4>
                        <p class="text-sm text-gray-500">{{ $permissionToDelete->name }}</p>
                    </div>
                </div>
                
                <p class="text-gray-600 mb-4">
                    Are you sure you want to delete this permission? This action cannot be undone.
                </p>

                @if($permissionToDelete->roles->count() > 0)
                    <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-4">
                        <p class="text-red-800 text-sm">
                            <strong>Warning:</strong> This permission is assigned to {{ $permissionToDelete->roles->count() }} role(s). 
                            You must remove it from all roles before deleting.
                        </p>
                        <div class="mt-2">
                            <p class="text-red-700 text-xs font-medium mb-1">Assigned to roles:</p>
                            <div class="flex flex-wrap gap-1">
                                @foreach($permissionToDelete->roles->take(5) as $role)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">
                                        {{ $role->display_name }}
                                    </span>
                                @endforeach
                                @if($permissionToDelete->roles->count() > 5)
                                    <span class="text-xs text-red-600">+{{ $permissionToDelete->roles->count() - 5 }} more</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <div class="flex justify-end space-x-4">
                <button wire:click="$set('showDeletePermissionModal', false)" 
                    class="bg-gray-100 text-gray-700 px-6 py-2 rounded-xl font-medium hover:bg-gray-200 transition-colors">
                    Cancel
                </button>
                <button wire:click="deletePermission" 
                    class="bg-red-600 text-white px-6 py-2 rounded-xl font-semibold hover:bg-red-700 transition-colors"
                    @if($permissionToDelete->roles->count() > 0) disabled @endif>
                    Delete Permission
                </button>
            </div>
        </div>
    </div>
@endif

<!-- Permission Roles Modal -->
@if($showPermissionRolesModal && $selectedPermission)
    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" wire:click.self="$set('showPermissionRolesModal', false)">
        <div class="relative top-10 mx-auto p-5 border w-11/12 max-w-4xl shadow-lg rounded-3xl bg-white">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-2xl font-bold text-gray-900">Roles with Permission</h3>
                    <p class="text-gray-600">Permission: <span class="font-semibold">{{ $selectedPermission->display_name }}</span></p>
                    <p class="text-sm text-gray-500 font-mono">{{ $selectedPermission->name }}</p>
                </div>
                <button wire:click="$set('showPermissionRolesModal', false)" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <div class="mb-4">
                <p class="text-sm text-gray-600">
                    Total roles with this permission: <span class="font-semibold">{{ count($permissionRoles) }}</span>
                </p>
            </div>

            @if(count($permissionRoles) > 0)
                <div class="max-h-96 overflow-y-auto">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($permissionRoles as $role)
                            <div class="bg-gray-50 rounded-xl p-4 flex items-center justify-between">
                                <div class="flex items-center">
                                    @php
                                        $levelColor = match(true) {
                                            $role->level >= 80 => 'from-red-500 to-red-600',
                                            $role->level >= 50 => 'from-purple-500 to-purple-600',
                                            default => 'from-blue-500 to-blue-600'
                                        };
                                    @endphp
                                    <div class="w-10 h-10 bg-gradient-to-br {{ $levelColor }} rounded-xl flex items-center justify-center">
                                        <span class="text-white text-sm font-bold">{{ $role->level }}</span>
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-sm font-semibold text-gray-900">
                                            {{ $role->display_name }}
                                        </div>
                                        <div class="text-xs text-gray-500">{{ $role->name }}</div>
                                        <div class="text-xs text-blue-600">{{ $role->users->count() }} users</div>
                                        @if($role->is_system_role)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800 mt-1">
                                                System Role
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                
                                @permission('roles.edit')
                                    @if(auth()->user()->role_level > $role->level)
                                        <button wire:click="removePermissionFromRole({{ $role->id }})" 
                                            onclick="return confirm('Remove this permission from the role?')"
                                            class="text-red-600 hover:text-red-700 p-2 rounded-lg hover:bg-red-50 transition-all duration-200"
                                            title="Remove permission from role">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    @else
                                        <span class="text-xs text-gray-400 italic">Protected</span>
                                    @endif
                                @endpermission
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="text-center py-12">
                    <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <h4 class="text-lg font-semibold text-gray-900 mb-2">No Roles Assigned</h4>
                    <p class="text-gray-500">This permission is not assigned to any roles yet.</p>
                </div>
            @endif

            <div class="flex justify-end pt-6 border-t">
                <button wire:click="$set('showPermissionRolesModal', false)" 
                    class="bg-gray-100 text-gray-700 px-6 py-2 rounded-xl font-medium hover:bg-gray-200 transition-colors">
                    Close
                </button>
            </div>
        </div>
    </div>
@endif

</div>
