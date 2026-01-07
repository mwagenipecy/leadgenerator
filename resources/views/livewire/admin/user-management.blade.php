<div>
    <div class="p-8">
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-4xl font-bold text-gray-900 mb-2">User Management</h1>
                    <p class="text-gray-600 text-lg">Manage system users, roles, and permissions</p>
                </div>
                <div class="flex items-center space-x-3">
                    @if(request()->routeIs('user.management') && !request()->routeIs('user.management.*'))
                        <button wire:click="openCreateUserModal" class="bg-sidebar-green text-white px-6 py-2 rounded-lg font-semibold hover:bg-sidebar-green-light transition-all duration-200 shadow-lg shadow-sidebar-green/25">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Create User
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <!-- Tabs Navigation -->
        <div class="mb-8">
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                    <!-- Users Tab -->
                    <a href="{{ route('user.management') }}" 
                            class="@if(request()->routeIs('user.management') && !request()->routeIs('user.management.*')) border-sidebar-green text-sidebar-green @else border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200 flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                        </svg>
                        <span>Users</span>
                        @if(request()->routeIs('user.management') && !request()->routeIs('user.management.*'))
                            <span class="bg-sidebar-green-100 text-sidebar-green ml-2 py-0.5 px-2.5 rounded-full text-xs font-medium">{{ number_format($totalUsers) }}</span>
                        @endif
                    </a>

                    <!-- Roles Tab -->
                    <a href="{{ route('user.management.roles') }}" 
                            class="@if(request()->routeIs('user.management.roles')) border-purple-500 text-purple-600 @else border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200 flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                        <span>Roles</span>
                    </a>

                    <!-- Permissions Tab -->
                    <a href="{{ route('user.management.permissions') }}" 
                            class="@if(request()->routeIs('user.management.permissions')) border-green-500 text-green-600 @else border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200 flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        <span>Permissions</span>
                    </a>
                </nav>
            </div>
        </div>

        <!-- Tab Content -->
        <div>
            <!-- Users Tab Content - Only show on users page -->
            @if(request()->routeIs('user.management') && !request()->routeIs('user.management.*'))
                <div wire:key="users-tab-content">
                    @include('livewire.admin.partials.user-tab')
                </div>
            @endif
        </div>
    </div>

    <!-- Create User Modal -->
    @if($showCreateUserModal)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" 
             wire:click.self="closeCreateUserModal"
             x-data="{ show: true }" 
             x-show="show" 
             x-transition:enter="ease-out duration-300" 
             x-transition:enter-start="opacity-0" 
             x-transition:enter-end="opacity-100">
            <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-2xl shadow-lg rounded-lg bg-white"
                 x-transition:enter="ease-out duration-300" 
                 x-transition:enter-start="opacity-0 transform scale-95" 
                 x-transition:enter-end="opacity-100 transform scale-100">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-2xl font-bold text-gray-900">Create New User</h3>
                    <button wire:click="closeCreateUserModal" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <form wire:submit.prevent="createUser" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Basic Information -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                            <input wire:model="name" type="text" 
                                   class="w-full border border-gray-300 rounded-xl px-3 py-2 focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green @error('name') border-sidebar-green @enderror">
                            @error('name') <span class="text-sidebar-green text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                            <input wire:model="email" type="email" 
                                   class="w-full border border-gray-300 rounded-xl px-3 py-2 focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green @error('email') border-sidebar-green @enderror">
                            @error('email') <span class="text-sidebar-green text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">First Name</label>
                            <input wire:model="first_name" type="text" 
                                   class="w-full border border-gray-300 rounded-xl px-3 py-2 focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green @error('first_name') border-sidebar-green @enderror">
                            @error('first_name') <span class="text-sidebar-green text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Last Name</label>
                            <input wire:model="last_name" type="text" 
                                   class="w-full border border-gray-300 rounded-xl px-3 py-2 focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green @error('last_name') border-sidebar-green @enderror">
                            @error('last_name') <span class="text-sidebar-green text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                            <input wire:model="phone" type="text" 
                                   class="w-full border border-gray-300 rounded-xl px-3 py-2 focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green @error('phone') border-sidebar-green @enderror">
                            @error('phone') <span class="text-sidebar-green text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">NIDA Number</label>
                            <input wire:model="nida_number" type="text" 
                                   class="w-full border border-gray-300 rounded-xl px-3 py-2 focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green @error('nida_number') border-sidebar-green @enderror">
                            @error('nida_number') <span class="text-sidebar-green text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Date of Birth</label>
                            <input wire:model="date_of_birth" type="date" 
                                   class="w-full border border-gray-300 rounded-xl px-3 py-2 focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green @error('date_of_birth') border-sidebar-green @enderror">
                            @error('date_of_birth') <span class="text-sidebar-green text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">User Role *</label>
                            <select wire:model="role" 
                                    class="w-full border border-gray-300 rounded-xl px-3 py-2 focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green @error('role') border-sidebar-green @enderror">
                                <option value="user">Borrower</option>
                                @foreach($roles as $roleOption)
                                    <option value="{{ $roleOption->name }}">{{ ucfirst($roleOption->name) }}</option>
                                @endforeach
                            </select>
                            @error('role') <span class="text-sidebar-green text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Lender Association (only show for lender and user roles) -->
                        @if(in_array($role, ['lender', 'user']))
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Associated Lender</label>
                                <select wire:model="selected_lender_id" 
                                        class="w-full border border-gray-300 rounded-xl px-3 py-2 focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green @error('selected_lender_id') border-sidebar-green @enderror">
                                    <option value="">No Lender Association</option>
                                    @foreach($availableLenders as $lender)
                                        <option value="{{ $lender->id }}">{{ $lender->company_name }}</option>
                                    @endforeach
                                </select>
                                @error('selected_lender_id') <span class="text-sidebar-green text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        @endif

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Password *</label>
                            <input wire:model="password" type="password" 
                                   class="w-full border border-gray-300 rounded-xl px-3 py-2 focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green @error('password') border-sidebar-green @enderror">
                            @error('password') <span class="text-sidebar-green text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Confirm Password *</label>
                            <input wire:model="password_confirmation" type="password" 
                                   class="w-full border border-gray-300 rounded-xl px-3 py-2 focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green @error('password_confirmation') border-sidebar-green @enderror">
                            @error('password_confirmation') <span class="text-sidebar-green text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Active Status -->
                    <div class="flex items-center">
                        <input wire:model="is_active" type="checkbox" id="is_active" 
                               class="h-4 w-4 text-sidebar-green focus:ring-sidebar-green border-gray-300 rounded">
                        <label for="is_active" class="ml-2 block text-sm text-gray-700">User is active</label>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-end space-x-4 pt-6">
                        <button type="button" wire:click="closeCreateUserModal" 
                            class="bg-gray-100 text-gray-700 px-6 py-2 rounded-xl font-medium hover:bg-gray-200 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" 
                            class="bg-sidebar-green text-white px-6 py-2 rounded-xl font-semibold hover:bg-sidebar-green-light transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                            wire:loading.attr="disabled">
                            <span wire:loading.remove>Create User</span>
                            <span wire:loading>Creating...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Edit User Modal -->
    @if($showEditUserModal && $selectedUser)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" 
             wire:click.self="closeEditUserModal"
             x-data="{ show: true }" 
             x-show="show" 
             x-transition:enter="ease-out duration-300" 
             x-transition:enter-start="opacity-0" 
             x-transition:enter-end="opacity-100">
            <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-2xl shadow-lg rounded-lg bg-white"
                 x-transition:enter="ease-out duration-300" 
                 x-transition:enter-start="opacity-0 transform scale-95" 
                 x-transition:enter-end="opacity-100 transform scale-100">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-2xl font-bold text-gray-900">Edit User</h3>
                    <button wire:click="closeEditUserModal" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <form wire:submit.prevent="updateUser" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Basic Information -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                            <input wire:model="edit_name" type="text" 
                                   class="w-full border border-gray-300 rounded-xl px-3 py-2 focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green @error('edit_name') border-sidebar-green @enderror">
                            @error('edit_name') <span class="text-sidebar-green text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                            <input wire:model="edit_email" type="email" 
                                   class="w-full border border-gray-300 rounded-xl px-3 py-2 focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green @error('edit_email') border-sidebar-green @enderror">
                            @error('edit_email') <span class="text-sidebar-green text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">First Name</label>
                            <input wire:model="edit_first_name" type="text" 
                                   class="w-full border border-gray-300 rounded-xl px-3 py-2 focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green @error('edit_first_name') border-sidebar-green @enderror">
                            @error('edit_first_name') <span class="text-sidebar-green text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Last Name</label>
                            <input wire:model="edit_last_name" type="text" 
                                   class="w-full border border-gray-300 rounded-xl px-3 py-2 focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green @error('edit_last_name') border-sidebar-green @enderror">
                            @error('edit_last_name') <span class="text-sidebar-green text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                            <input wire:model="edit_phone" type="text" 
                                   class="w-full border border-gray-300 rounded-xl px-3 py-2 focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green @error('edit_phone') border-sidebar-green @enderror">
                            @error('edit_phone') <span class="text-sidebar-green text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">NIDA Number</label>
                            <input wire:model="edit_nida_number" type="text" 
                                   class="w-full border border-gray-300 rounded-xl px-3 py-2 focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green @error('edit_nida_number') border-sidebar-green @enderror">
                            @error('edit_nida_number') <span class="text-sidebar-green text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Date of Birth</label>
                            <input wire:model="edit_date_of_birth" type="date" 
                                   class="w-full border border-gray-300 rounded-xl px-3 py-2 focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green @error('edit_date_of_birth') border-sidebar-green @enderror">
                            @error('edit_date_of_birth') <span class="text-sidebar-green text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">User Role *</label>
                            <select wire:model="edit_role" 
                                    class="w-full border border-gray-300 rounded-xl px-3 py-2 focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green @error('edit_role') border-sidebar-green @enderror">
                                <option value="user">Borrower</option>
                                @foreach($roles as $roleOption)
                                    <option value="{{ $roleOption->name }}">{{ ucfirst($roleOption->name) }}</option>
                                @endforeach
                            </select>
                            @error('edit_role') <span class="text-sidebar-green text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Lender Association -->
                        @if(in_array($edit_role, ['lender', 'user']))
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Associated Lender</label>
                                <select wire:model="edit_selected_lender_id" 
                                        class="w-full border border-gray-300 rounded-xl px-3 py-2 focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green @error('edit_selected_lender_id') border-sidebar-green @enderror">
                                    <option value="">No Lender Association</option>
                                    @foreach($availableLenders as $lender)
                                        <option value="{{ $lender->id }}">{{ $lender->company_name }}</option>
                                    @endforeach
                                </select>
                                @error('edit_selected_lender_id') <span class="text-sidebar-green text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        @endif
                    </div>

                    <!-- Active Status -->
                    <div class="flex items-center">
                        <input wire:model="edit_is_active" type="checkbox" id="edit_is_active" 
                               class="h-4 w-4 text-sidebar-green focus:ring-sidebar-green border-gray-300 rounded">
                        <label for="edit_is_active" class="ml-2 block text-sm text-gray-700">User is active</label>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-end space-x-4 pt-6">
                        <button type="button" wire:click="closeEditUserModal" 
                            class="bg-gray-100 text-gray-700 px-6 py-2 rounded-xl font-medium hover:bg-gray-200 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" 
                            class="bg-sidebar-green text-white px-6 py-2 rounded-xl font-semibold hover:bg-sidebar-green-light transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                            wire:loading.attr="disabled">
                            <span wire:loading.remove>Update User</span>
                            <span wire:loading>Updating...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Password Confirmation Modal -->
    @if($showPasswordConfirmModal)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" 
             wire:click.self="closePasswordConfirmModal"
             x-data="{ show: true }" 
             x-show="show" 
             x-transition:enter="ease-out duration-300" 
             x-transition:enter-start="opacity-0" 
             x-transition:enter-end="opacity-100">
            <div class="relative top-1/2 transform -translate-y-1/2 mx-auto p-5 border w-11/12 max-w-md shadow-lg rounded-lg bg-white"
                 x-transition:enter="ease-out duration-300" 
                 x-transition:enter-start="opacity-0 transform scale-95" 
                 x-transition:enter-end="opacity-100 transform scale-100">
                
                <!-- Modal Header -->
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-sidebar-green-100 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-sidebar-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                <div class="bg-sidebar-green-50 border border-sidebar-green-200 rounded-lg p-4 mb-6">
                    <div class="flex items-start space-x-3">
                        <svg class="w-5 h-5 text-sidebar-green mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.464 0L4.348 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                        </svg>
                        <div>
                            <h4 class="text-sm font-semibold text-sidebar-green-800">Critical Action Warning</h4>
                            <p class="text-sm text-sidebar-green-light mt-1">{{ $passwordConfirmMessage }}</p>
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
                               class="w-full border border-gray-300 rounded-xl px-3 py-2 focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green @error('currentPassword') border-sidebar-green @enderror"
                               autofocus>
                        @error('currentPassword') 
                            <span class="text-sidebar-green text-xs mt-1 block">{{ $message }}</span> 
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
                                class="bg-sidebar-green text-white px-4 py-2 rounded-xl font-semibold hover:bg-sidebar-green-light transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                                wire:loading.attr="disabled">
                            <span wire:loading.remove>Confirm Action</span>
                            <span wire:loading>Processing...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    


</div>