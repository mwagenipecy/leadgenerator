<x-app-layout>
    <div class="p-6">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-black">{{ __('admin.regions_title') }}</h1>
                <p class="text-gray-600 mt-1">{{ __('admin.regions_description') }}</p>
            </div>
            <a href="{{ route('admin.regions.create') }}"
               class="bg-sidebar-green hover:bg-sidebar-green-light text-white px-6 py-2 rounded-lg font-semibold transition-colors">
                {{ __('admin.add_region') }}
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
                        <th class="px-6 py-4 text-left text-xs font-semibold text-black uppercase tracking-wider">{{ __('admin.name') }}</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-black uppercase tracking-wider">{{ __('admin.region_code') }}</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-black uppercase tracking-wider">{{ __('admin.sort_order') }}</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-black uppercase tracking-wider">{{ __('common.status') }}</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-black uppercase tracking-wider">{{ __('common.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($regions as $region)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-semibold text-black">{{ $region->name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-700">{{ $region->code ?? '—' }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-700">{{ $region->sort_order }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $region->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $region->is_active ? __('common.active') : __('common.inactive') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-3">
                                    <a href="{{ route('admin.regions.edit', $region) }}"
                                       class="text-sidebar-green hover:text-sidebar-green-light"
                                       title="{{ __('admin.edit') }}">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                    <form action="{{ route('admin.regions.destroy', $region) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('admin.region_delete_confirm') }}');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800" title="{{ __('admin.delete') }}">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="text-gray-500">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.196-5.196a2.25 2.25 0 013.182-3.182L9 13.182l5.196-5.196a2.25 2.25 0 013.182 3.182L9 20z"/>
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">{{ __('admin.no_regions') }}</h3>
                                    <p class="mt-1 text-sm text-gray-500">{{ __('admin.add_first_region') }}</p>
                                    <div class="mt-6">
                                        <a href="{{ route('admin.regions.create') }}"
                                           class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-sidebar-green hover:bg-sidebar-green-light">
                                            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                            </svg>
                                            {{ __('admin.add_region') }}
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
</x-app-layout>
