<div class="p-6 sm:p-8">
    <div class="mb-8 flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-2">{{ __('creditinfo_alert.pricing_title') }}</h1>
            <p class="text-gray-600 text-lg">{{ __('creditinfo_alert.pricing_subtitle') }}</p>
        </div>
        <a href="{{ route('creditinfo.alert') }}"
           class="inline-flex items-center gap-2 text-sidebar-green font-medium hover:underline">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            {{ __('creditinfo_alert.manage_subscriptions') }}
        </a>
    </div>

    @if (session()->has('message'))
        <div class="mb-6 rounded-lg border border-green-400 bg-green-50 px-6 py-4 text-green-700 flex items-center space-x-3">
            <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            <span>{{ session('message') }}</span>
        </div>
    @endif

    @php
        $cardFeatures = [
            'alert_when_credit_info_searched' => ['feat_credit_search_1', 'feat_credit_search_2', 'feat_credit_search_3'],
            'alert_when_score_changed' => ['feat_score_1', 'feat_score_2', 'feat_score_3'],
            'alert_when_report_retrieved' => ['feat_report_1', 'feat_report_2', 'feat_report_3'],
            'alert_when_lender_can_find_loan' => ['feat_lender_1', 'feat_lender_2', 'feat_lender_3'],
            'alert_when_reach_visible_notify_email' => ['feat_reach_1', 'feat_reach_2', 'feat_reach_3'],
        ];
    @endphp
    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
        @foreach($services as $service)
        @php
            $isSubscribed = $setting && $service->isSubscribedFor($setting);
            $features = $cardFeatures[$service->slug] ?? [];
        @endphp
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm transition-shadow hover:shadow-md flex flex-col {{ $isSubscribed ? 'ring-2 ring-sidebar-green/30' : '' }}">
            <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-xl bg-sidebar-green/10">
                <svg class="h-6 w-6 text-sidebar-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
            </div>
            <h3 class="text-lg font-bold text-gray-900">{{ $service->name }}</h3>
            <p class="mt-2 text-sm text-gray-600">{{ $service->description }}</p>
            @if(count($features) > 0)
            <div class="mt-3">
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">{{ __('creditinfo_alert.what_you_get') }}</p>
                <ul class="mt-1.5 space-y-1 text-sm text-gray-600">
                    @foreach($features as $key)
                    <li class="flex items-start gap-2">
                        <svg class="mt-0.5 h-4 w-4 flex-shrink-0 text-sidebar-green" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        <span>{{ __('creditinfo_alert.'.$key) }}</span>
                    </li>
                    @endforeach
                </ul>
            </div>
            @endif
            <div class="mt-4 flex items-baseline gap-1">
                <span class="text-2xl font-bold text-sidebar-green">{{ number_format((float) $service->price, 0) }}</span>
                <span class="text-gray-500">{{ $service->currency }} / {{ __('creditinfo_alert.month') }}</span>
            </div>
            <p class="mt-1 text-xs text-gray-500">{{ __('creditinfo_alert.billed_monthly') }} · {{ __('creditinfo_alert.cancel_anytime') }}</p>
            <div class="mt-6 flex-1 flex flex-col justify-end">
                @if($isSubscribed)
                    <button type="button"
                            wire:click="openUnsubscribeModal({{ $service->id }})"
                            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50">
                        {{ __('creditinfo_alert.subscribed_btn') }} · {{ __('creditinfo_alert.unsubscribe') }}
                    </button>
                @else
                    <button type="button"
                            wire:click="openPaymentModal({{ $service->id }})"
                            class="w-full rounded-lg bg-sidebar-green px-4 py-2.5 text-sm font-medium text-white hover:bg-sidebar-green-light">
                        {{ __('creditinfo_alert.subscribe') }}
                    </button>
                @endif
            </div>
        </div>
        @endforeach
    </div>

    @if($services->isEmpty())
        <div class="rounded-2xl border border-gray-200 bg-white p-12 text-center text-gray-500">
            <p>{{ __('creditinfo_alert.no_services_available') }}</p>
        </div>
    @endif

    <!-- Payment modal: select mobile network, check and continue -->
    @if($showPaymentModal && $paymentServiceId)
    @php $paymentService = $services->firstWhere('id', $paymentServiceId); @endphp
    @if($paymentService)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="payment-modal-title" role="dialog" aria-modal="true">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closePaymentModal" aria-hidden="true"></div>
            <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                <div class="bg-white px-6 pb-6 pt-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-gray-900" id="payment-modal-title">{{ __('creditinfo_alert.payment_modal_title') }}</h3>
                        <button type="button" wire:click="closePaymentModal" class="rounded-lg p-1 text-gray-400 hover:bg-gray-100 hover:text-gray-600">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                    <p class="text-sm text-gray-600 mb-4">{{ __('creditinfo_alert.pay_via_mobile_money') }}</p>

                    <!-- Number of months -->
                    <div class="mb-4">
                        <label for="payment-months" class="block text-sm font-medium text-gray-700 mb-1">{{ __('creditinfo_alert.number_of_months') }}</label>
                        <select id="payment-months"
                                wire:model.live="paymentMonths"
                                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-gray-900 shadow-sm focus:border-sidebar-green focus:ring-1 focus:ring-sidebar-green">
                            <option value="1">1 {{ __('creditinfo_alert.month') }}</option>
                            <option value="3">3 {{ __('creditinfo_alert.months') }}</option>
                            <option value="6">6 {{ __('creditinfo_alert.months') }}</option>
                            <option value="12">12 {{ __('creditinfo_alert.months') }}</option>
                        </select>
                    </div>

                    <!-- Summary -->
                    @php
                        $unitPrice = (float) $paymentService->price;
                        $months = (int) $paymentMonths;
                        $totalAmount = $unitPrice * max(1, min(12, $months));
                        $startDate = now();
                        $endDate = now()->addMonths($months);
                    @endphp
                    <div class="rounded-xl bg-gray-50 p-4 mb-4">
                        <p class="text-sm font-medium text-gray-700">{{ __('creditinfo_alert.summary') }}</p>
                        <div class="mt-2 flex justify-between text-sm">
                            <span class="text-gray-600">{{ $paymentService->name }}</span>
                            <span class="font-semibold text-sidebar-green">{{ number_format($unitPrice, 0) }} {{ $paymentService->currency }}/{{ __('creditinfo_alert.month') }}</span>
                        </div>
                        <div class="mt-2 flex justify-between text-sm text-gray-600">
                            <span>{{ __('creditinfo_alert.start_date') }}</span>
                            <span>{{ $startDate->format('d M Y') }}</span>
                        </div>
                        <div class="mt-0.5 flex justify-between text-sm text-gray-600">
                            <span>{{ __('creditinfo_alert.end_date') }}</span>
                            <span>{{ $endDate->format('d M Y') }}</span>
                        </div>
                        <div class="mt-1 flex justify-between text-sm text-gray-500">
                            <span>{{ $months }} {{ $months === 1 ? __('creditinfo_alert.month') : __('creditinfo_alert.months') }} ({{ __('creditinfo_alert.billing_period') }})</span>
                            <span>{{ number_format($totalAmount, 0) }} {{ $paymentService->currency }}</span>
                        </div>
                        <div class="mt-2 pt-2 border-t border-gray-200 flex justify-between text-sm font-medium">
                            <span>{{ __('creditinfo_alert.total') }}</span>
                            <span class="text-sidebar-green">{{ number_format($totalAmount, 0) }} {{ $paymentService->currency }}</span>
                        </div>
                    </div>

                    <!-- Mobile network -->
                    <div class="mb-4">
                        <label for="payment-network" class="block text-sm font-medium text-gray-700 mb-1">{{ __('creditinfo_alert.select_mobile_network') }} <span class="text-red-500">*</span></label>
                        <select id="payment-network"
                                wire:model="selectedNetwork"
                                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-gray-900 shadow-sm focus:border-sidebar-green focus:ring-1 focus:ring-sidebar-green">
                            <option value="">{{ __('creditinfo_alert.select_mobile_network') }}</option>
                            <option value="mpesa">{{ __('creditinfo_alert.network_mpesa') }}</option>
                            <option value="tigo_pesa">{{ __('creditinfo_alert.network_tigo_pesa') }}</option>
                            <option value="airtel_money">{{ __('creditinfo_alert.network_airtel_money') }}</option>
                            <option value="halotel">{{ __('creditinfo_alert.network_halotel') }}</option>
                            <option value="zantel">{{ __('creditinfo_alert.network_zantel') }}</option>
                            <option value="tpesa">{{ __('creditinfo_alert.network_tpesa') }}</option>
                        </select>
                        @error('selectedNetwork')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Mobile number (optional) -->
                    <div class="mb-6">
                        <label for="payment-phone" class="block text-sm font-medium text-gray-700 mb-1">{{ __('creditinfo_alert.mobile_number') }}</label>
                        <input type="text"
                               id="payment-phone"
                               wire:model="paymentPhone"
                               placeholder="07XXXXXXXX"
                               class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-gray-900 shadow-sm focus:border-sidebar-green focus:ring-1 focus:ring-sidebar-green">
                    </div>

                    <div class="flex gap-3">
                        <button type="button"
                                wire:click="closePaymentModal"
                                class="flex-1 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50">
                            {{ __('common.cancel') }}
                        </button>
                        <button type="button"
                                wire:click="confirmPayment"
                                class="flex-1 rounded-lg bg-sidebar-green px-4 py-2.5 text-sm font-medium text-white hover:bg-sidebar-green-light">
                            {{ __('creditinfo_alert.check_and_continue') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
    @endif

    <!-- Unsubscribe confirmation modal: password required -->
    @if($showUnsubscribeModal && $unsubscribeServiceId)
    @php $unsubscribeService = $services->firstWhere('id', $unsubscribeServiceId); @endphp
    @if($unsubscribeService)
    <div class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeUnsubscribeModal" aria-hidden="true"></div>
            <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-md">
                <div class="bg-white px-6 pb-6 pt-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-gray-900">{{ __('creditinfo_alert.confirm_unsubscribe') }}</h3>
                        <button type="button" wire:click="closeUnsubscribeModal" class="rounded-lg p-1 text-gray-400 hover:bg-gray-100 hover:text-gray-600">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                    <p class="text-sm text-gray-600 mb-4">{{ __('creditinfo_alert.unsubscribe_confirm_message', ['name' => $unsubscribeService->name]) }}</p>
                    <div class="mb-4">
                        <label for="unsubscribe-password" class="block text-sm font-medium text-gray-700 mb-1">{{ __('creditinfo_alert.password') }} <span class="text-red-500">*</span></label>
                        <input type="password"
                               id="unsubscribe-password"
                               wire:model="unsubscribePassword"
                               class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-gray-900 shadow-sm focus:border-sidebar-green focus:ring-1 focus:ring-sidebar-green"
                               placeholder="••••••••"
                               autocomplete="current-password">
                        @error('unsubscribePassword')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="flex gap-3">
                        <button type="button"
                                wire:click="closeUnsubscribeModal"
                                class="flex-1 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50">
                            {{ __('common.cancel') }}
                        </button>
                        <button type="button"
                                wire:click="confirmUnsubscribe"
                                class="flex-1 rounded-lg bg-red-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-red-700">
                            {{ __('creditinfo_alert.confirm_unsubscribe') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
    @endif
</div>
