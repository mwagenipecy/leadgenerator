<x-app-layout>
    <div class="p-6">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-black">{{ __('admin.loan_categories_title') }}</h1>
                <p class="text-gray-600 mt-1">{{ __('admin.manage_categories_title') }}</p>
            </div>
            <a href="{{ route('admin.loan-categories.create') }}" 
               class="bg-sidebar-green hover:bg-sidebar-green-light text-white px-6 py-2 rounded-lg font-semibold transition-colors">
                {{ __('admin.add_new_category') }}
            </a>
        </div>

        @if(session('success'))
            <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                <p class="text-green-800 text-sm">{{ session('success') }}</p>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                <p class="text-red-800 text-sm">{{ session('error') }}</p>
            </div>
        @endif

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-black uppercase tracking-wider">{{ __('admin.image') }}</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-black uppercase tracking-wider">{{ __('admin.name') }}</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-black uppercase tracking-wider">{{ __('admin.description') }}</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-black uppercase tracking-wider">{{ __('common.status') }}</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-black uppercase tracking-wider">{{ __('admin.sort_order') }}</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-black uppercase tracking-wider">{{ __('common.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($categories as $category)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($category->image_path)
                                    <div class="h-12 w-20 border border-gray-200 rounded-lg bg-white flex items-center justify-center">
                                        <img src="{{ asset('storage/' . $category->image_path) }}" alt="{{ $category->localized_name }}" class="max-h-8 w-auto object-contain">
                                    </div>
                                @else
                                    <span class="text-xs text-gray-400">{{ __('admin.no_image') }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-semibold text-black">{{ $category->localized_name }}</div>
                                <div class="text-xs text-gray-500">{{ $category->slug }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-700">{{ Str::limit($category->localized_description, 50) ?: __('admin.not_available') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $category->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $category->is_active ? __('common.active') : __('common.inactive') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-700">{{ $category->sort_order }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-3">
                                    <a href="{{ route('admin.loan-categories.edit', $category->id) }}" 
                                       class="text-sidebar-green hover:text-sidebar-green-light" 
                                       title="{{ __('admin.edit') }}">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                    @if($category->is_active)
                                        <button type="button" 
                                                onclick="confirmDisableCategory('{{ $category->id }}', '{{ $category->name }}')"
                                                class="text-red-600 hover:text-red-800" 
                                                title="{{ __('admin.disable') }}">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728"/>
                                            </svg>
                                        </button>
                                    @else
                                        <button disabled
                                                class="text-gray-400 cursor-not-allowed opacity-50" 
                                                title="{{ __('admin.already_disabled') }}">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728"/>
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="text-gray-500">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">{{ __('admin.no_categories') }}</h3>
                                    <p class="mt-1 text-sm text-gray-500">{{ __('admin.get_started_create') }}</p>
                                    <div class="mt-6">
                                        <a href="{{ route('admin.loan-categories.create') }}" 
                                           class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-sidebar-green hover:bg-sidebar-green-light">
                                            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                            </svg>
                                            {{ __('admin.add_category') }}
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Disable Confirmation Modal -->
    <div id="disableCategoryModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden flex items-center justify-center">
        <div class="relative p-4 w-full max-w-xs shadow-lg rounded-lg bg-white mx-4">
            <!-- Header -->
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-base font-bold text-gray-900">{{ __('admin.disable_category_title') }}</h3>
                <button onclick="closeDisableModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Message -->
            <p class="text-sm text-gray-600 mb-3" id="disableCategoryMessage">
                {{ __('admin.disable_category_message') }}
            </p>

            <!-- Password Form -->
            <form id="disableCategoryForm" method="POST">
                @csrf
                <div class="mb-3">
                    <input type="password" 
                           name="password" 
                           id="disablePassword"
                           placeholder="{{ __('admin.enter_password_to_disable') }}"
                           required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500"
                           autofocus>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end space-x-2">
                    <button type="button" 
                            onclick="closeDisableModal()" 
                            class="px-3 py-1.5 text-sm text-gray-700 hover:text-gray-900">
                        {{ __('admin.cancel') }}
                    </button>
                    <button type="submit" 
                            class="px-3 py-1.5 text-sm bg-red-600 text-white rounded-lg font-medium hover:bg-red-700 transition-colors">
                        {{ __('admin.disable') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function confirmDisableCategory(categoryId, categoryName) {
            const modal = document.getElementById('disableCategoryModal');
            const form = document.getElementById('disableCategoryForm');
            const message = document.getElementById('disableCategoryMessage');
            
            message.textContent = `{{ __('admin.disable_category_confirm') }}`.replace(':name', categoryName);
            form.action = '{{ route("admin.loan-categories.disable", ":id") }}'.replace(':id', categoryId);
            modal.classList.remove('hidden');
            document.getElementById('disablePassword').focus();
        }

        function closeDisableModal() {
            const modal = document.getElementById('disableCategoryModal');
            modal.classList.add('hidden');
            document.getElementById('disablePassword').value = '';
        }

        // Close modal when clicking outside
        document.getElementById('disableCategoryModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeDisableModal();
            }
        });
    </script>
</x-app-layout>

