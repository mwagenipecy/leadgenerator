<div>
    <div class="p-8">
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-4xl font-bold text-gray-900 mb-2">{{ __('promotion.promotion_management') }}</h1>
                    <p class="text-gray-600 text-lg">{{ __('promotion.create_send_promotions') }}</p>
                </div>
                <div class="flex items-center space-x-3">
                    <button 
                            wire:click="createSamplePromotion" 
                            wire:loading.attr="disabled"
                            wire:target="createSamplePromotion"
                            class="bg-gray-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-gray-700 transition-all duration-200 inline-flex items-center disabled:opacity-50 disabled:cursor-not-allowed">
                        <svg wire:loading.remove wire:target="createSamplePromotion" class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                        <svg wire:loading wire:target="createSamplePromotion" class="animate-spin w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span wire:loading.remove wire:target="createSamplePromotion">{{ __('promotion.create_sample') }}</span>
                        <span wire:loading wire:target="createSamplePromotion">{{ __('promotion.creating') }}</span>
                    </button>
                    <button wire:click="openCreateModal" 
                            class="bg-sidebar-green text-white px-6 py-2 rounded-lg font-semibold hover:bg-sidebar-green-light transition-all duration-200 shadow-lg shadow-sidebar-green/25 inline-flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        {{ __('promotion.create_promotion') }}
                    </button>
                </div>
            </div>
        </div>

        <!-- Flash Messages -->
        @if (session()->has('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-6 py-4 rounded-lg mb-6 flex items-center space-x-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-6 py-4 rounded-lg mb-6 flex items-center space-x-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- Promotions Table -->
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('promotion.title') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('promotion.target_audience') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('promotion.recipients') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('promotion.status') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('promotion.sent') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('promotion.created') }}</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('promotion.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($promotions as $promotion)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $promotion->title }}</div>
                                    <div class="text-xs text-gray-500 mt-1">{{ Str::limit($promotion->message, 60) }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        @if(!empty($promotion->target_audience['roles']))
                                            <div class="flex flex-wrap gap-1">
                                                @foreach($promotion->target_audience['roles'] as $role)
                                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded">{{ ucfirst($role) }}</span>
                                                @endforeach
                                            </div>
                                        @endif
                                        @if($promotion->target_audience['new_customers'] ?? false)
                                            <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded mt-1 inline-block">{{ __('promotion.new_customers') }}</span>
                                        @endif
                                        @if($promotion->target_audience['no_loans'] ?? false)
                                            <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs rounded mt-1 inline-block">{{ __('promotion.no_loans') }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ number_format($promotion->total_recipients) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs font-medium rounded-full 
                                        @if($promotion->status === 'sent') bg-green-100 text-green-800
                                        @elseif($promotion->status === 'scheduled') bg-blue-100 text-blue-800
                                        @elseif($promotion->status === 'sending') bg-yellow-100 text-yellow-800
                                        @elseif($promotion->status === 'failed') bg-red-100 text-red-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        @if($promotion->status === 'draft') {{ __('promotion.draft') }}
                                        @elseif($promotion->status === 'scheduled') {{ __('promotion.scheduled') }}
                                        @elseif($promotion->status === 'sending') {{ __('promotion.sending') }}
                                        @elseif($promotion->status === 'sent') {{ __('promotion.sent') }}
                                        @else {{ ucfirst($promotion->status) }}
                                        @endif
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    @if($promotion->sent_at)
                                        {{ $promotion->sent_at->format('M d, Y') }}
                                        <div class="text-xs text-gray-400">
                                            E: {{ $promotion->emails_sent }}, N: {{ $promotion->notifications_sent }}
                                        </div>
                                    @else
                                        @if($promotion->scheduled_at)
                                            {{ __('promotion.scheduled') }}: {{ $promotion->scheduled_at->format('M d, Y H:i') }}
                                        @else
                                            -
                                        @endif
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $promotion->created_at->format('M d, Y') }}
                                    <div class="text-xs text-gray-400">By {{ $promotion->creator->name ?? 'N/A' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    @if($promotion->status === 'draft' || $promotion->status === 'scheduled')
                                        <button wire:click="sendPromotion({{ $promotion->id }})" 
                                                class="p-2 text-sidebar-green hover:bg-sidebar-green/10 rounded-lg transition-colors" 
                                                title="{{ __('promotion.send') }}">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                            </svg>
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                    {{ __('promotion.no_promotions_found') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $promotions->links() }}
            </div>
        </div>
    </div>

    <!-- Create Promotion Modal -->
    @if($showCreateModal)
    <div class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4" wire:click="closeCreateModal">
        <div class="bg-white rounded-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto" wire:click.stop>
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-2xl font-bold text-gray-900">Create Promotion</h3>
                    <button wire:click="closeCreateModal" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <form wire:submit.prevent="openPreviewModal">
                    <!-- Basic Information -->
                    <div class="mb-6">
                        <h4 class="text-lg font-semibold text-gray-900 mb-4">Promotion Details</h4>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Title *</label>
                                <input type="text" wire:model="title" 
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green">
                                @error('title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Message *</label>
                                <textarea wire:model="message" rows="4" 
                                          class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green"></textarea>
                                @error('message') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div class="flex items-center">
                                    <input type="checkbox" wire:model="send_email" id="send_email" class="rounded border-gray-300 text-sidebar-green focus:ring-sidebar-green">
                                    <label for="send_email" class="ml-2 text-sm text-gray-700">{{ __('promotion.send_email') }}</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" wire:model="send_notification" id="send_notification" class="rounded border-gray-300 text-sidebar-green focus:ring-sidebar-green">
                                    <label for="send_notification" class="ml-2 text-sm text-gray-700">{{ __('promotion.send_notification') }}</label>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('promotion.schedule_optional') }}</label>
                                <input type="datetime-local" wire:model="scheduled_at" 
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green">
                                @error('scheduled_at') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Target Audience -->
                    <div class="mb-6">
                        <h4 class="text-lg font-semibold text-gray-900 mb-4">{{ __('promotion.target_audience') }}</h4>
                        <div class="space-y-4">
                            <!-- Roles -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('promotion.user_roles') }}</label>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($roles as $role)
                                        <label class="flex items-center">
                                            <input type="checkbox" wire:model="target_roles" value="{{ $role }}" 
                                                   class="rounded border-gray-300 text-sidebar-green focus:ring-sidebar-green">
                                            <span class="ml-2 text-sm text-gray-700">{{ ucfirst($role) }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Customer Categories -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="flex items-center">
                                    <input type="checkbox" wire:model="target_new_customers" id="target_new_customers" 
                                           class="rounded border-gray-300 text-sidebar-green focus:ring-sidebar-green">
                                    <label for="target_new_customers" class="ml-2 text-sm text-gray-700">New Customers</label>
                                </div>
                                @if($target_new_customers)
                                    <div>
                                        <input type="number" wire:model="target_registration_days" placeholder="Days ago" 
                                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                                    </div>
                                @endif

                                <div class="flex items-center">
                                    <input type="checkbox" wire:model="target_no_loans" id="target_no_loans" 
                                           class="rounded border-gray-300 text-sidebar-green focus:ring-sidebar-green">
                                    <label for="target_no_loans" class="ml-2 text-sm text-gray-700">Customers with No Loans</label>
                                </div>

                                <div class="flex items-center">
                                    <input type="checkbox" wire:model="target_incomplete_registration" id="target_incomplete_registration" 
                                           class="rounded border-gray-300 text-sidebar-green focus:ring-sidebar-green">
                                    <label for="target_incomplete_registration" class="ml-2 text-sm text-gray-700">Incomplete Registration</label>
                                </div>
                            </div>

                            <!-- Credit Score Range -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('promotion.credit_score_range_optional') }}</label>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <input type="number" wire:model="target_credit_score_min" placeholder="{{ __('promotion.min_score') }}" 
                                               class="w-full border border-gray-300 rounded-lg px-3 py-2">
                                    </div>
                                    <div>
                                        <input type="number" wire:model="target_credit_score_max" placeholder="{{ __('promotion.max_score') }}" 
                                               class="w-full border border-gray-300 rounded-lg px-3 py-2">
                                    </div>
                                </div>
                            </div>

                            <!-- Location Filters -->
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('promotion.city_optional') }}</label>
                                    <input type="text" wire:model="target_city" list="cities" 
                                           class="w-full border border-gray-300 rounded-lg px-3 py-2">
                                    <datalist id="cities">
                                        @foreach($cities as $city)
                                            <option value="{{ $city }}">
                                        @endforeach
                                    </datalist>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('promotion.region_optional') }}</label>
                                    <input type="text" wire:model="target_region" list="regions" 
                                           class="w-full border border-gray-300 rounded-lg px-3 py-2">
                                    <datalist id="regions">
                                        @foreach($regions as $region)
                                            <option value="{{ $region }}">
                                        @endforeach
                                    </datalist>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button type="button" wire:click="closeCreateModal" 
                                class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Cancel</button>
                        <button type="submit" 
                                class="px-6 py-2 bg-sidebar-green text-white rounded-lg hover:bg-sidebar-green-light">{{ __('promotion.preview_send') }}</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Preview Modal -->
    @if($showPreviewModal)
    <div class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4" wire:click="closePreviewModal">
        <div class="bg-white rounded-2xl max-w-2xl w-full" wire:click.stop>
            <div class="p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-4">{{ __('promotion.preview_promotion') }}</h3>
                <div class="mb-4">
                    <p class="text-sm text-gray-600 mb-2">{{ __('promotion.will_be_sent_to') }}</p>
                    <p class="text-2xl font-bold text-sidebar-green">{{ number_format($previewRecipientsCount) }} {{ __('promotion.recipients') }}</p>
                </div>
                <div class="border-t border-gray-200 pt-4 mb-4">
                    <h4 class="font-semibold text-gray-900 mb-2">{{ $title }}</h4>
                    <p class="text-gray-700 whitespace-pre-wrap">{{ $message }}</p>
                </div>
                <div class="flex justify-end space-x-3">
                    <button wire:click="closePreviewModal" 
                            class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">{{ __('common.back') }}</button>
                    <button wire:click="savePromotion" 
                            class="px-6 py-2 bg-sidebar-green text-white rounded-lg hover:bg-sidebar-green-light">{{ __('promotion.create_send') }}</button>
                </div>
            </div>
        </div>
    @endif
</div>
