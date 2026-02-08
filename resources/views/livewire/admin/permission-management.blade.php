<div>
    <!-- Section Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 mb-1">Permission Management</h2>
                <p class="text-gray-600 text-sm">Manage system permissions and access controls</p>
            </div>
        </div>
    </div>

        <!-- Filters and Search -->
        <div class="bg-white rounded-3xl shadow-sm p-6 border border-gray-100 mb-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Search Permissions</label>
                    <div class="relative">
                        <input wire:model.live="search" type="text" placeholder="Search by name or description..." 
                               class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500">
                        <svg class="w-4 h-4 text-gray-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Filter by Category</label>
                    <select wire:model.live="categoryFilter" class="w-full border border-gray-300 rounded-xl px-3 py-2 focus:ring-2 focus:ring-red-500 focus:border-red-500">
                        <option value="">All Categories</option>
                        @foreach($categories as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Filter by Status</label>
                    <select wire:model.live="statusFilter" class="w-full border border-gray-300 rounded-xl px-3 py-2 focus:ring-2 focus:ring-red-500 focus:border-red-500">
                        <option value="">All Statuses</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>

                <div class="flex items-end">
                    <button wire:click="$refresh" class="w-full bg-gray-100 text-gray-700 px-4 py-2 rounded-xl font-medium hover:bg-gray-200 transition-colors">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Refresh
                    </button>
                </div>
            </div>
        </div>

        <!-- Permissions Table -->
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-8 py-6 border-b border-gray-100 bg-gradient-to-r from-white to-gray-50">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-1">Permissions List</h3>
                        <p class="text-gray-600">Manage system permissions and view role assignments</p>
                    </div>
                    <div class="text-sm text-gray-500">
                        Showing {{ $permissions->count() }} of {{ $permissions->total() }} permissions
                    </div>
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-8 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Permission</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Category</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Roles</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($permissions as $permission)
                            <tr class="hover:bg-gray-50 transition-colors duration-200 group">
                                <td class="px-8 py-6 whitespace-nowrap">
                                    <div class="flex items-center">
                                        @php
                                            $categoryColors = [
                                                'users' => 'from-blue-500 to-blue-600',
                                                'roles' => 'from-purple-500 to-purple-600',
                                                'applications' => 'from-green-500 to-green-600',
                                                'lenders' => 'from-orange-500 to-orange-600',
                                                'products' => 'from-pink-500 to-pink-600',
                                                'reports' => 'from-indigo-500 to-indigo-600',
                                                'system' => 'from-sidebar-green to-sidebar-green',
                                                'financial' => 'from-yellow-500 to-yellow-600',
                                                'general' => 'from-gray-500 to-gray-600'
                                            ];
                                            $categoryColor = $categoryColors[$permission->category] ?? 'from-gray-500 to-gray-600';
                                        @endphp
                                        <div class="w-10 h-10 bg-gradient-to-br {{ $categoryColor }} rounded-xl flex items-center justify-center">
                                            <span class="text-white text-xs font-bold">{{ substr($permission->display_name, 0, 2) }}</span>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-bold text-gray-900 group-hover:text-red-600 transition-colors">
                                                {{ $permission->display_name }}
                                            </div>
                                            <div class="text-xs text-gray-500 font-mono">{{ $permission->name }}</div>
                                            @if($permission->description)
                                                <div class="text-xs text-gray-600 mt-1 max-w-xs truncate">{{ $permission->description }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-6 whitespace-nowrap">
                                    @php
                                        $categoryStyle = match($permission->category) {
                                            'users' => 'bg-blue-100 text-blue-800',
                                            'roles' => 'bg-purple-100 text-purple-800',
                                            'applications' => 'bg-green-100 text-green-800',
                                            'lenders' => 'bg-orange-100 text-orange-800',
                                            'products' => 'bg-pink-100 text-pink-800',
                                            'reports' => 'bg-indigo-100 text-indigo-800',
                                            'system' => 'bg-sidebar-green-100 text-sidebar-green-800',
                                            'financial' => 'bg-yellow-100 text-yellow-800',
                                            default => 'bg-gray-100 text-gray-800'
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $categoryStyle }}">
                                        {{ $categories[$permission->category] ?? ucfirst(str_replace('_', ' ', $permission->category)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-6 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="text-lg font-bold text-gray-900">{{ number_format($permission->roles_count ?? 0) }}</div>
                                            <div class="text-xs text-gray-500">Roles</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-6 whitespace-nowrap">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                        {{ $permission->is_active ? 'bg-green-100 text-green-800 border border-green-200' : 'bg-sidebar-green-100 text-sidebar-green-800 border border-sidebar-green-200' }}">
                                        <div class="w-2 h-2 rounded-full mr-2 {{ $permission->is_active ? 'bg-green-400' : 'bg-sidebar-green-400' }}"></div>
                                        {{ $permission->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="px-6 py-6 whitespace-nowrap">
                                    <div class="flex items-center space-x-2">
                                        <button type="button" 
                                                wire:click="openEditPermissionModal('{{ $permission->id }}')" 
                                                class="text-red-600 hover:text-red-700 p-2 rounded-xl hover:bg-red-50 transition-all duration-200" 
                                                title="Update Permission">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </button>

                                        <button type="button" 
                                                wire:click="confirmTogglePermissionStatus('{{ $permission->id }}')" 
                                                class="text-red-600 hover:text-red-700 p-2 rounded-xl hover:bg-red-50 transition-all duration-200" 
                                                title="Change Status">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-8 py-12 text-center">
                                    <div class="w-20 h-20 bg-gray-100 rounded-3xl flex items-center justify-center mx-auto mb-4">
                                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1721 9z"/>
                                        </svg>
                                    </div>
                                    <h4 class="text-lg font-semibold text-gray-900 mb-2">No Permissions Found</h4>
                                    <p class="text-gray-500">No permissions match your current search criteria.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($permissions->hasPages())
                <div class="bg-white px-8 py-4 border-t border-gray-200">
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                        <div class="text-sm text-gray-700">
                            <span class="font-medium">{{ $permissions->firstItem() }}</span>
                            -
                            <span class="font-medium">{{ $permissions->lastItem() }}</span>
                            of
                            <span class="font-medium">{{ $permissions->total() }}</span>
                            permissions
                        </div>
                        <div class="flex-1 flex justify-center sm:justify-end">
                            {{ $permissions->links() }}
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Password Confirmation Modal -->
    @if($showPasswordConfirmModal)
        <div wire:key="password-confirm-modal-{{ $confirmPermissionId }}" 
             class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4" 
             wire:click.self="closePasswordConfirmModal">
            <div class="relative mx-auto p-5 border w-11/12 max-w-md shadow-lg rounded-lg bg-white"
                 wire:click.stop>
                
                <!-- Modal Header -->
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.464 0L4.348 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">{{ $passwordConfirmTitle }}</h3>
                            <p class="text-sm text-gray-500">Security verification required</p>
                        </div>
                    </div>
                    <button wire:click="closePasswordConfirmModal" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <!-- Warning Message -->
                <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                    <div class="flex items-start space-x-3">
                        <svg class="w-5 h-5 text-red-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.464 0L4.348 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                        </svg>
                        <div>
                            <h4 class="text-sm font-semibold text-red-800">Critical Action Warning</h4>
                            <p class="text-sm text-red-700 mt-1">{{ $passwordConfirmMessage }}</p>
                        </div>
                    </div>
                </div>

                <!-- Password Form -->
                <form wire:submit.prevent="executeConfirmedAction" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Enter your current password to confirm this action:
                        </label>
                        <input wire:model="currentPassword" 
                               type="password" 
                               placeholder="Your current password"
                               class="w-full border border-gray-300 rounded-xl px-3 py-2 focus:ring-2 focus:ring-red-500 focus:border-red-500 @error('currentPassword') border-red-500 @enderror"
                               autofocus>
                        @error('currentPassword') 
                            <span class="text-red-600 text-xs mt-1 block">{{ $message }}</span> 
                        @enderror
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-end space-x-3 pt-4">
                        <button type="button" 
                                wire:click="closePasswordConfirmModal" 
                                class="bg-gray-100 text-gray-700 px-4 py-2 rounded-xl font-medium hover:bg-gray-200 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" 
                                class="bg-red-600 text-white px-4 py-2 rounded-xl font-semibold hover:bg-red-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                                wire:loading.attr="disabled">
                            <span wire:loading.remove>Confirm Action</span>
                            <span wire:loading>Processing...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Modals -->
    @include('livewire.admin.permission-management.modals')
</div>
