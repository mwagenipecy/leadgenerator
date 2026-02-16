<x-app-layout>
    <div class="p-8">
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-4xl font-bold text-gray-900 mb-2">{{ __('admin.roles') }}</h1>
                    <p class="text-gray-600 text-lg">{{ __('admin.manage_roles_description') }}</p>
                </div>
            </div>
        </div>

        <!-- Roles Content -->
        <livewire:admin.role-management />
    </div>
</x-app-layout>
