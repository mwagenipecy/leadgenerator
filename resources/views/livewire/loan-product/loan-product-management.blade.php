<div class="w-full">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-4xl font-bold text-black mb-2">Loan Products</h1>
                <p class="text-gray-600 text-lg">Manage your loan products and settings</p>
            </div>
            <div class="flex items-center space-x-3">
                <div class="flex items-center space-x-2 bg-sidebar-green-50 px-4 py-2 rounded-full">
                    <div class="w-2 h-2 bg-sidebar-green rounded-full animate-pulse"></div>
                    <span class="text-sm font-medium text-sidebar-green-light">{{ $stats['active'] ?? 0 }} Active Products</span>
                </div>
                <button wire:click="showCreateForm" class="bg-sidebar-green text-white px-6 py-2 rounded-lg font-semibold hover:bg-sidebar-green-light transition-all duration-200 shadow-sm">
                    + Create Product
                </button>
            </div>
        </div>
    </div>

    <!-- Flash Messages -->
    @if (session()->has('message'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg" role="alert">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                {{ session('message') }}
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-6 bg-sidebar-green-50 border border-sidebar-green-200 text-sidebar-green-light px-4 py-3 rounded-lg" role="alert">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                {{ session('error') }}
            </div>
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <!-- Total Products -->
        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100 hover:shadow-sm transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Total Products</p>
                    <p class="text-3xl font-bold text-black">{{ $stats['total'] ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-black rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Active Products -->
        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100 hover:shadow-sm transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Active Products</p>
                    <p class="text-3xl font-bold text-sidebar-green">{{ $stats['active'] ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-sidebar-green rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Inactive Products -->
        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100 hover:shadow-sm transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Inactive Products</p>
                    <p class="text-3xl font-bold text-gray-500">{{ $stats['inactive'] ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-gray-400 rounded-lg flex items-center justify-center">
                   


                    <svg  class="w-6 h-6 text-white"  data-slot="icon" fill="none" stroke-width="1.5" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"></path>
                    </svg>


                </div>
            </div>
        </div>

        <!-- Total Applications -->
        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100 hover:shadow-sm transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Total Applications</p>
                    <p class="text-3xl font-bold text-black">{{ $stats['total_applications'] ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-black rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100 mb-6">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between space-y-4 lg:space-y-0">
            <!-- Search -->
            <div class="flex-1 max-w-md">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input wire:model.live="search" type="text" class="block w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green placeholder-gray-500 text-black" placeholder="Search products...">
                </div>
            </div>

            <!-- Filters -->
            <div class="flex items-center space-x-4">
                <select wire:model.live="statusFilter" class="bg-gray-50 border border-gray-200 rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green">
                    <option value="all">All Status</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>

                <select wire:model.live="employmentFilter" class="bg-gray-50 border border-gray-200 rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green">
                    <option value="all">All Employment Types</option>
                    <option value="employed">Employed Only</option>
                    <option value="unemployed">Unemployed/Self-Employed</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Products Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden mb-8">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-black uppercase tracking-wider">Product</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-black uppercase tracking-wider">Amount Range</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-black uppercase tracking-wider">Interest Rate</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-black uppercase tracking-wider">Tenure</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-black uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-black uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($products as $product)
                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                        <!-- Product Info -->
                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-sidebar-green rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                    </svg>
                                </div>
                                <div class="min-w-0">
                                    <div class="flex items-center space-x-2">
                                        <p class="text-sm font-bold text-black">{{ $product->name }}</p>
                                        @if($product->promotional_tag)
                                            <span class="bg-sidebar-green-100 text-sidebar-green-light text-xs font-medium px-2 py-1 rounded-md">{{ $product->promotional_tag }}</span>
                                        @endif
                                    </div>
                                    <p class="text-xs text-gray-500">{{ $product->product_code }}</p>
                                </div>
                            </div>
                        </td>

                        <!-- Amount Range -->
                        <td class="px-6 py-4">
                            <span class="text-sm font-semibold text-black">{{ $product->amount_range }}</span>
                        </td>

                        <!-- Interest Rate -->
                        <td class="px-6 py-4">
                            <span class="text-sm font-semibold text-sidebar-green">{{ $product->interest_range }}</span>
                        </td>

                        <!-- Tenure -->
                        <td class="px-6 py-4">
                            <span class="text-sm font-medium text-gray-700">{{ $product->tenure_range }}</span>
                        </td>

                        <!-- Status -->
                        <td class="px-6 py-4 text-center">
                            <div class="flex flex-col items-center space-y-2">
                                @if($product->is_active)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">
                                        Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 border border-gray-200">
                                        Inactive
                                    </span>
                                @endif
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" wire:click="confirmActivate({{ $product->id }}, '{{ $product->name }}')" {{ $product->is_active ? 'checked' : '' }} class="sr-only peer">
                                    <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-sidebar-green/25 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-sidebar-green"></div>
                                </label>
                            </div>
                        </td>

                        <!-- Actions -->
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center space-x-1">
                                <!-- View -->
                                <button wire:click="viewProduct({{ $product->id }})" 
                                        class="text-black hover:bg-gray-100 p-2 rounded-lg transition-colors duration-200" 
                                        title="View Details">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </button>

                                <!-- Edit -->
                                <button wire:click="editProduct({{ $product->id }})" 
                                        class="text-black hover:bg-gray-100 p-2 rounded-lg transition-colors duration-200" 
                                        title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>

                                <!-- Delete -->
                                <button wire:click="confirmDelete({{ $product->id }}, '{{ $product->name }}')" 
                                        class="text-sidebar-green hover:bg-sidebar-green-50 p-2 rounded-lg transition-colors duration-200" 
                                        title="Delete">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-16 h-16 bg-sidebar-green rounded-lg flex items-center justify-center mb-4">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold text-black mb-2">No loan products found</h3>
                                <p class="text-gray-500 mb-4">Get started by creating your first loan product.</p>
                                <button wire:click="showCreateForm" class="bg-sidebar-green text-white px-6 py-2 rounded-lg font-medium hover:bg-sidebar-green-light transition-colors duration-200">
                                    Create First Product
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    {{ $products->links() }}

    <!-- Delete Confirmation Modal -->
    @if($showDeleteModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-sidebar-green-100 rounded-lg flex items-center justify-center mr-4">
                        <svg class="w-6 h-6 text-sidebar-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-black">Delete Product</h3>
                </div>
                <p class="text-gray-600 mb-6">Are you sure you want to delete "{{ $selectedProductName }}"? This action cannot be undone.</p>
                <div class="flex justify-end space-x-3">
                    <button wire:click="closeDeleteModal" class="px-4 py-2 text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                        Cancel
                    </button>
                    <button wire:click="deleteProduct" class="px-4 py-2 bg-sidebar-green text-white rounded-lg hover:bg-sidebar-green-light transition-colors">
                        Delete Product
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Activate/Deactivate Confirmation Modal -->
    @if($showActivateModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-sidebar-green-100 rounded-lg flex items-center justify-center mr-4">
                        <svg class="w-6 h-6 text-sidebar-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-black">Toggle Product Status</h3>
                </div>
                <p class="text-gray-600 mb-6">Are you sure you want to change the status of "{{ $selectedProductName }}"?</p>
                <div class="flex justify-end space-x-3">
                    <button wire:click="closeActivateModal" class="px-4 py-2 text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                        Cancel
                    </button>
                    <button wire:click="toggleProductStatus" class="px-4 py-2 bg-sidebar-green text-white rounded-lg hover:bg-sidebar-green-light transition-colors">
                        Confirm
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>