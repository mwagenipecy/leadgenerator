<div class="p-6 sm:p-8">
    <!-- Page Header -->
    <div class="mb-8">
        <div>
            <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-2">{{ __('creditinfo_alert.title') }}</h1>
            <p class="text-gray-600 text-lg">{{ __('creditinfo_alert.subtitle') }}</p>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="mb-6 rounded-lg border border-green-400 bg-green-50 px-6 py-4 text-green-700 flex items-center space-x-3">
            <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            <span>{{ session('message') }}</span>
        </div>
    @endif

    <!-- Subscribe to services CTA -->
    <div class="mb-10">
        <a href="{{ route('creditinfo.pricing') }}"
           class="block rounded-2xl border-2 border-sidebar-green/20 bg-gradient-to-br from-sidebar-green/5 to-white p-6 shadow-sm transition-all hover:border-sidebar-green/40 hover:shadow-md">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="flex h-14 w-14 items-center justify-center rounded-xl bg-sidebar-green/10">
                        <svg class="h-7 w-7 text-sidebar-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">{{ __('creditinfo_alert.subscribe_to_services') }}</h2>
                        <p class="text-gray-600 text-sm mt-0.5">{{ __('creditinfo_alert.pricing_subtitle') }}</p>
                    </div>
                </div>
                <span class="inline-flex items-center gap-2 rounded-lg bg-sidebar-green px-5 py-2.5 text-white font-medium">
                    {{ __('creditinfo_alert.subscribe') }}
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </span>
            </div>
        </a>
        <div class="mt-4 flex flex-wrap items-center justify-between gap-3 rounded-xl bg-gray-50 px-4 py-3">
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" wire:model="notify_via_email" class="h-4 w-4 rounded border-gray-300 text-sidebar-green focus:ring-sidebar-green">
                <span class="text-sm font-medium text-gray-700">{{ __('creditinfo_alert.notify_via_email') }}</span>
            </label>
            <button type="button" wire:click="save"
                    class="rounded-lg bg-sidebar-green px-4 py-2 text-sm font-medium text-white hover:bg-sidebar-green-light">
                {{ __('creditinfo_alert.save') }}
            </button>
        </div>
    </div>

    <!-- My subscribed services -->
    <div class="mb-10">
        <h2 class="text-xl font-bold text-gray-900 mb-1">{{ __('creditinfo_alert.my_subscribed_services') }}</h2>
        <p class="text-gray-600 text-sm mb-4">{{ __('creditinfo_alert.subscribed_subtitle') }}</p>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            @if($subscribedServices->isNotEmpty())
                <ul class="divide-y divide-gray-100">
                    @foreach($subscribedServices as $service)
                    @php $period = $setting ? $setting->getSubscriptionPeriod($service->slug) : null; @endphp
                    <li class="px-6 py-4 flex flex-wrap items-center justify-between gap-3">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-10 h-10 rounded-lg bg-sidebar-green/10 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-sidebar-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <span class="font-medium text-gray-900">{{ $service->name }}</span>
                                <span class="block text-sm text-gray-500">{{ $service->formattedPrice() }}</span>
                                @if($period)
                                <div class="mt-1 flex flex-wrap gap-x-4 gap-y-0.5 text-xs text-gray-500">
                                    <span>{{ __('creditinfo_alert.start_date') }}: {{ $period['started_at']->format('d M Y') }}</span>
                                    <span>{{ __('creditinfo_alert.end_date') }}: {{ $period['ends_at'] ? $period['ends_at']->format('d M Y') : '—' }}</span>
                                </div>
                                @endif
                            </div>
                        </div>
                        <span class="text-xs font-medium text-sidebar-green bg-sidebar-green/10 px-2.5 py-1 rounded-full flex-shrink-0">{{ __('creditinfo_alert.active') }}</span>
                    </li>
                    @endforeach
                </ul>
            @else
                <div class="px-6 py-10 text-center text-gray-500">
                    <svg class="mx-auto h-12 w-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                    <p>{{ __('creditinfo_alert.no_subscriptions') }}</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Notifications / Activities -->
    <div>
        <h2 class="text-xl font-bold text-gray-900 mb-1">{{ __('creditinfo_alert.notifications_activities') }}</h2>
        <p class="text-gray-600 text-sm mb-4">{{ __('creditinfo_alert.activities_subtitle') }}</p>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            @if($activities->isNotEmpty())
                <ul class="divide-y divide-gray-100">
                    @foreach($activities as $activity)
                    @php $data = $activity->data ?? []; @endphp
                    <li class="px-6 py-4 hover:bg-gray-50/50 {{ $activity->unread() ? 'bg-blue-50/30' : '' }}">
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0 w-10 h-10 rounded-full bg-sidebar-green/10 flex items-center justify-center">
                                <svg class="w-5 h-5 text-sidebar-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                </svg>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="font-medium text-gray-900">{{ $data['title'] ?? __('creditinfo_alert.alert') }}</p>
                                <p class="text-sm text-gray-600 mt-0.5">{{ $data['message'] ?? '' }}</p>
                                <p class="text-xs text-gray-400 mt-1">{{ $activity->created_at->diffForHumans() }}</p>
                            </div>
                            @if(!empty($data['link']))
                            <a href="{{ $data['link'] }}" class="flex-shrink-0 text-sidebar-green text-sm font-medium hover:underline">{{ __('common.view') }}</a>
                            @endif
                        </div>
                    </li>
                    @endforeach
                </ul>
            @else
                <div class="px-6 py-10 text-center text-gray-500">
                    <svg class="mx-auto h-12 w-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    <p>{{ __('creditinfo_alert.no_activities') }}</p>
                </div>
            @endif
        </div>
    </div>
</div>
