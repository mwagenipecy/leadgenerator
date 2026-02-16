<div>
    <div class="p-8">
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-4xl font-bold text-gray-900 mb-2">{{ __('admin.language_management') ?? 'Language Management' }}</h1>
                    <p class="text-gray-600 text-lg">{{ __('admin.manage_translations') ?? 'Manage system translations and language files' }}</p>
                </div>
            </div>
        </div>

        <!-- Language Selection -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('common.language') }}</label>
                    <select wire:model.live="currentLanguage" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-red-500 focus:border-transparent">
                        @foreach($languages as $lang)
                            <option value="{{ $lang }}">{{ strtoupper($lang) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('admin.translation_file') ?? 'Translation File' }}</label>
                    <select wire:model.live="selectedFile" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-red-500 focus:border-transparent">
                        @foreach($availableFiles as $file)
                            <option value="{{ $file }}">{{ ucfirst($file) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- Search and Add -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
            <div class="flex flex-col md:flex-row gap-4">
                <div class="flex-1">
                    <input 
                        type="text" 
                        wire:model.live.debounce.300ms="searchKey"
                        placeholder="{{ __('common.search') }}..."
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-red-500 focus:border-transparent"
                    >
                </div>
                <div class="flex gap-2">
                    <input 
                        type="text" 
                        wire:model="newKey"
                        placeholder="{{ __('admin.translation_key') ?? 'Key' }}"
                        class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-red-500 focus:border-transparent"
                    >
                    <input 
                        type="text" 
                        wire:model="newValue"
                        placeholder="{{ __('admin.translation_value') ?? 'Value' }}"
                        class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-red-500 focus:border-transparent"
                    >
                    <button 
                        wire:click="addTranslation"
                        class="bg-red-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-red-700 transition-colors">
                        {{ __('common.create') }}
                    </button>
                </div>
            </div>
        </div>

        <!-- Flash Messages -->
        @if (session()->has('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if (session()->has('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <!-- Translations Table -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('admin.translation_key') ?? 'Key' }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('admin.translation_value') ?? 'Value' }}</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('common.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($filteredTranslations as $key => $value)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $key }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700">
                                    @if($editingKey === $key)
                                        <input 
                                            type="text" 
                                            wire:model="editingValue"
                                            class="w-full border border-gray-300 rounded px-2 py-1 focus:ring-2 focus:ring-red-500"
                                        >
                                    @else
                                        {{ $value }}
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    @if($editingKey === $key)
                                        <div class="flex justify-end gap-2">
                                            <button 
                                                wire:click="saveTranslation"
                                                class="text-green-600 hover:text-green-900">
                                                {{ __('common.save') }}
                                            </button>
                                            <button 
                                                wire:click="cancelEdit"
                                                class="text-gray-600 hover:text-gray-900">
                                                {{ __('common.cancel') }}
                                            </button>
                                        </div>
                                    @else
                                        <div class="flex justify-end gap-2">
                                            <button 
                                                wire:click="startEdit('{{ $key }}')"
                                                class="text-blue-600 hover:text-blue-900">
                                                {{ __('common.edit') }}
                                            </button>
                                            <button 
                                                wire:click="deleteTranslation('{{ $key }}')"
                                                onclick="return confirm('{{ __('common.confirm_delete') }}')"
                                                class="text-red-600 hover:text-red-900">
                                                {{ __('common.delete') }}
                                            </button>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">
                                    {{ __('common.no_data') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

