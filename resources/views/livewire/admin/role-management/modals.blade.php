<div>
{{-- resources/views/livewire/admin/role-management/modals.blade.php --}}

<!-- Create Role Modal -->
@if($showCreateRoleModal)
    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" wire:click.self="$set('showCreateRoleModal', false)">
        <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-lg shadow-lg rounded-3xl bg-white">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-2xl font-bold text-gray-900">Create New Role</h3>
                <button wire:click="$set('showCreateRoleModal', false)" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form wire:submit.prevent="createRole" class="space-y-6">
                <div>
                    <h3 class="text-2xl font-bold text-gray-900">Users with Role</h3>
                    <p class="text-gray-600">Role: <span class="font-semibold">{{ $selectedRole->display_name }}</span></p>
                </div>
                <button wire:click="$set('showRoleUsersModal', false)" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <div class="mb-4">
                <p class="text-sm text-gray-600">
                    Total users with this role: <span class="font-semibold">{{ count($roleUsers) }}</span>
                </p>
            </div>

            @if(count($roleUsers) > 0)
                <div class="max-h-96 overflow-y-auto">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($roleUsers as $user)
                            <div class="bg-gray-50 rounded-xl p-4 flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center">
                                        <span class="text-white text-sm font-bold">
                                            {{ substr($user->first_name ?: $user->name, 0, 1) }}{{ substr($user->last_name ?: '', 0, 1) }}
                                        </span>
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-sm font-semibold text-gray-900">
                                            {{ $user->first_name && $user->last_name ? $user->first_name . ' ' . $user->last_name : $user->name }}
                                        </div>
                                        <div class="text-xs text-gray-500">{{ $user->email }}</div>
                                        @if($user->lender)
                                            <div class="text-xs text-blue-600">{{ $user->lender->company_name }}</div>
                                        @endif
                                    </div>
                                </div>
                                
                                @permission('roles.assign')
                                    @if(auth()->user()->canManage($user))
                                        <button wire:click="removeUserFromRole({{ $user->id }})" 
                                            onclick="return confirm('Remove this user from the role?')"
                                            class="text-red-600 hover:text-red-700 p-2 rounded-lg hover:bg-red-50 transition-all duration-200"
                                            title="Remove from role">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                        </svg>
                    </div>
                    <h4 class="text-lg font-semibold text-gray-900 mb-2">No Users Assigned</h4>
                    <p class="text-gray-500">This role has no users assigned to it yet.</p>
                </div>
            @endif

            <div class="flex justify-end pt-6 border-t">
                <button wire:click="$set('showRoleUsersModal', false)" 
                    class="bg-gray-100 text-gray-700 px-6 py-2 rounded-xl font-medium hover:bg-gray-200 transition-colors">
                    Close
                </button>
            </div>
        </div>
    </div>
@endif
            

<!-- Edit Role Modal -->
@if($showEditRoleModal && $selectedRole)
    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" wire:click.self="$set('showEditRoleModal', false)">
        <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-lg shadow-lg rounded-3xl bg-white">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-2xl font-bold text-gray-900">Edit Role</h3>
                <button wire:click="$set('showEditRoleModal', false)" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form wire:submit.prevent="updateRole" class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Role Name *</label>
                    <input wire:model="edit_name" type="text" class="w-full border border-gray-300 rounded-xl px-3 py-2 focus:ring-2 focus:ring-purple-500 focus:border-purple-500" {{ $selectedRole->is_system_role ? 'disabled' : '' }}>
                    @error('edit_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    @if($selectedRole->is_system_role)
                        <p class="text-xs text-gray-500 mt-1">System role names cannot be changed</p>
                    @endif
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Display Name *</label>
                    <input wire:model="edit_display_name" type="text" class="w-full border border-gray-300 rounded-xl px-3 py-2 focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                    @error('edit_display_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea wire:model="edit_description" rows="3" class="w-full border border-gray-300 rounded-xl px-3 py-2 focus:ring-2 focus:ring-purple-500 focus:border-purple-500"></textarea>
                    @error('edit_description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Role Level (1-{{ auth()->user()->role_level - 1 }}) *</label>
                    <input wire:model="edit_level" type="number" min="1" max="{{ auth()->user()->role_level - 1 }}" class="w-full border border-gray-300 rounded-xl px-3 py-2 focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                    @error('edit_level') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="flex items-center">
                    <input wire:model="edit_is_active" type="checkbox" id="edit_is_active" class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded">
                    <label for="edit_is_active" class="ml-2 block text-sm text-gray-700">Role is active</label>
                </div>

                <div class="flex justify-end space-x-4 pt-6">
                    <button type="button" wire:click="$set('showEditRoleModal', false)" 
                        class="bg-gray-100 text-gray-700 px-6 py-2 rounded-xl font-medium hover:bg-gray-200 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" 
                        class="bg-purple-600 text-white px-6 py-2 rounded-xl font-semibold hover:bg-purple-700 transition-colors">
                        Update Role
                    </button>
                </div>
            </form>
        </div>
    </div>
@endif

<!-- Delete Role Confirmation Modal -->
@if($showDeleteRoleModal && $roleToDelete)
    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" wire:click.self="$set('showDeleteRoleModal', false)">
        <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-md shadow-lg rounded-3xl bg-white">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-2xl font-bold text-gray-900">Delete Role</h3>
                <button wire:click="$set('showDeleteRoleModal', false)" class="text-gray-400 hover:text-gray-600">
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
                        <h4 class="text-lg font-semibold text-gray-900">{{ $roleToDelete->display_name }}</h4>
                        <p class="text-sm text-gray-500">{{ $roleToDelete->name }}</p>
                    </div>
                </div>
                
                <p class="text-gray-600 mb-4">
                    Are you sure you want to delete this role? This action cannot be undone.
                </p>

                @if($roleToDelete->users()->count() > 0)
                    <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-4">
                        <p class="text-red-800 text-sm">
                            <strong>Warning:</strong> This role has {{ $roleToDelete->users()->count() }} assigned user(s). 
                            You must reassign these users before deleting this role.
                        </p>
                    </div>
                @endif
            </div>

            <div class="flex justify-end space-x-4">
                <button wire:click="$set('showDeleteRoleModal', false)" 
                    class="bg-gray-100 text-gray-700 px-6 py-2 rounded-xl font-medium hover:bg-gray-200 transition-colors">
                    Cancel
                </button>
                <button wire:click="deleteRole" 
                    class="bg-red-600 text-white px-6 py-2 rounded-xl font-semibold hover:bg-red-700 transition-colors"
                    @if($roleToDelete->users()->count() > 0) disabled @endif>
                    Delete Role
                </button>
            </div>
        </div>
    </div>
@endif

<!-- Manage Permissions Modal -->
@if($showManagePermissionsModal && $selectedRole)
    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" wire:click.self="$set('showManagePermissionsModal', false)">
        <div class="relative top-10 mx-auto p-5 border w-11/12 max-w-6xl shadow-lg rounded-3xl bg-white">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-2xl font-bold text-gray-900">Manage Role Permissions</h3>
                    <p class="text-gray-600">Role: <span class="font-semibold">{{ $selectedRole->display_name }}</span></p>
                </div>
                <button wire:click="$set('showManagePermissionsModal', false)" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form wire:submit.prevent="updateRolePermissions" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 max-h-96 overflow-y-auto">
                    @foreach($permissionsByCategory as $category => $permissions)
                        <div class="bg-gray-50 rounded-xl p-4">
                            <h4 class="font-semibold text-gray-900 mb-3 capitalize flex items-center">
                                @php
                                    $categoryColors = [
                                        'users' => 'text-blue-600',
                                        'roles' => 'text-purple-600',
                                        'applications' => 'text-green-600',
                                        'lenders' => 'text-orange-600',
                                        'products' => 'text-pink-600',
                                        'reports' => 'text-indigo-600',
                                        'system' => 'text-red-600',
                                        'financial' => 'text-yellow-600',
                                        'general' => 'text-gray-600'
                                    ];
                                    $categoryColor = $categoryColors[$category] ?? 'text-gray-600';
                                @endphp
                                <span class="{{ $categoryColor }}">{{ str_replace('_', ' ', $category) }}</span>
                                <span class="ml-2 text-xs bg-gray-200 text-gray-600 px-2 py-1 rounded-full">{{ count($permissions) }}</span>
                            </h4>
                            <div class="space-y-2">
                                @foreach($permissions as $permission)
                                    <label class="flex items-start">
                                        <input type="checkbox" wire:model="selectedPermissions" value="{{ $permission->id }}" class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded mt-0.5 flex-shrink-0">
                                        <div class="ml-3">
                                            <span class="text-sm text-gray-700 block">{{ $permission->display_name }}</span>
                                            @if($permission->description)
                                                <span class="text-xs text-gray-500">{{ $permission->description }}</span>
                                            @endif
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="flex justify-between items-center pt-6 border-t">
                    <div class="text-sm text-gray-600">
                        Selected: <span class="font-semibold">{{ count($selectedPermissions) }}</span> permissions
                    </div>
                    <div class="flex space-x-4">
                        <button type="button" wire:click="$set('showManagePermissionsModal', false)" 
                            class="bg-gray-100 text-gray-700 px-6 py-2 rounded-xl font-medium hover:bg-gray-200 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" 
                            class="bg-purple-600 text-white px-6 py-2 rounded-xl font-semibold hover:bg-purple-700 transition-colors">
                            Update Permissions
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endif





                </div>
