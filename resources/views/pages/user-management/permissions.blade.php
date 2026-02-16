<x-app-layout>
    <div class="p-8">
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-4xl font-bold text-gray-900 mb-2">{{ __('admin.permissions') }}</h1>
                    <p class="text-gray-600 text-lg">{{ __('admin.manage_permissions_description') }}</p>
                </div>
            </div>
        </div>

        <!-- Permissions Content -->
        <livewire:admin.permission-management />
    </div>
</x-app-layout>
