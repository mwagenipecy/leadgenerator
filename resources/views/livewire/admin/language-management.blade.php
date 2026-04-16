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

                @if($selectedFile === 'landing')
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Section</label>
                        <select wire:model.live="landingGroup" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-red-500 focus:border-transparent">
                            <option value="all">All</option>
                            <option value="header">Header</option>
                            <option value="footer">Footer</option>
                        </select>
                    </div>
                @endif
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

        @error('translation')
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ $message }}
            </div>
        @enderror

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
                            <tr class="align-top">
                                <td class="px-6 py-4 text-sm font-medium text-gray-900 break-all">
                                    {{ $key }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700">
                                    <div class="whitespace-pre-wrap break-words">{{ $value }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end">
                                        <button
                                            wire:click="startEdit(@js($key))"
                                            type="button"
                                            class="inline-flex h-9 w-9 items-center justify-center rounded-md text-blue-600 hover:bg-blue-50 hover:text-blue-700 transition-colors"
                                            title="{{ __('common.edit') }}"
                                            aria-label="{{ __('common.edit') }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M17.414 2.586a2 2 0 010 2.828l-8.5 8.5a1 1 0 01-.39.242l-4 1.5a1 1 0 01-1.286-1.286l1.5-4a1 1 0 01.242-.39l8.5-8.5a2 2 0 012.828 0zM6.121 10.707l-.793 2.118 2.118-.794 7.554-7.554-.707-.707-7.172 7.172z" />
                                            </svg>
                                        </button>
                                    </div>
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

    @if($editingKey !== null)
        <div class="fixed inset-0 z-40 flex items-center justify-center bg-black/50 px-4" wire:click="cancelEdit">
            <div class="w-full max-w-2xl rounded-lg bg-white p-6 shadow-xl" wire:click.stop>
                <div class="mb-4 flex items-start justify-between gap-4">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900">{{ __('common.edit') }}</h2>
                        <p class="mt-1 text-sm text-gray-600 break-all">{{ $editingKey }}</p>
                    </div>
                    <button
                        type="button"
                        wire:click="cancelEdit"
                        class="inline-flex h-8 w-8 items-center justify-center rounded-md text-gray-500 hover:bg-gray-100 hover:text-gray-700"
                        aria-label="{{ __('common.close') ?? 'Close' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>

                <textarea
                    wire:model="editingValue"
                    rows="6"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-red-500 focus:border-transparent"
                ></textarea>
                <p class="mt-2 text-xs text-gray-500">{{ __('admin.translation_edit_tip') }}</p>

                <div class="mt-6 flex justify-end gap-2">
                    <button
                        wire:click="cancelEdit"
                        type="button"
                        class="px-4 py-2 rounded-md bg-gray-200 text-gray-800 hover:bg-gray-300 transition-colors">
                        {{ __('common.cancel') }}
                    </button>
                    <button
                        wire:click="saveTranslation"
                        type="button"
                        class="px-4 py-2 rounded-md bg-red-600 text-white hover:bg-red-700 transition-colors">
                        {{ __('common.save') }}
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>

