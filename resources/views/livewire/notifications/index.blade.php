<div class="p-6 sm:p-8">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div>
                <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-2">{{ __('notifications.notifications') }}</h1>
                <p class="text-gray-600 text-lg">{{ __('notifications.view_manage_notifications') }}</p>
            </div>
            <div class="flex items-center gap-3">
                @if($unreadCount > 0)
                    <button 
                        wire:click="markAllAsRead"
                        class="bg-sidebar-green text-white px-4 py-2 rounded-lg font-medium hover:bg-sidebar-green-light transition-all duration-200 inline-flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        {{ __('notifications.mark_all_as_read') }}
                    </button>
                @endif
                @if($totalCount > 0)
                    <button 
                        wire:click="clearAll"
                        wire:confirm="{{ __('notifications.confirm_clear_all') }}"
                        class="bg-red-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-red-700 transition-all duration-200 inline-flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        {{ __('notifications.clear_all') }}
                    </button>
                @endif
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

    <!-- Filter Tabs -->
    <div class="mb-6 flex items-center gap-2 border-b border-gray-200">
        <button 
            wire:click="$set('filter', 'all')"
            class="px-4 py-2 text-sm font-medium transition-colors {{ $filter === 'all' ? 'text-sidebar-green border-b-2 border-sidebar-green' : 'text-gray-600 hover:text-gray-900' }}">
            {{ __('notifications.all') }} ({{ $totalCount }})
        </button>
        <button 
            wire:click="$set('filter', 'unread')"
            class="px-4 py-2 text-sm font-medium transition-colors {{ $filter === 'unread' ? 'text-sidebar-green border-b-2 border-sidebar-green' : 'text-gray-600 hover:text-gray-900' }}">
            {{ __('notifications.unread') }} ({{ $unreadCount }})
        </button>
        <button 
            wire:click="$set('filter', 'read')"
            class="px-4 py-2 text-sm font-medium transition-colors {{ $filter === 'read' ? 'text-sidebar-green border-b-2 border-sidebar-green' : 'text-gray-600 hover:text-gray-900' }}">
            {{ __('notifications.read') }} ({{ $totalCount - $unreadCount }})
        </button>
    </div>

    <!-- Notifications List -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        @if($notifications->count() > 0)
            <div class="divide-y divide-gray-100">
                @foreach($notifications as $notification)
                    <div 
                        wire:key="notification-{{ $notification->id }}"
                        wire:click="openNotification('{{ $notification->id }}')"
                        class="px-6 py-4 hover:bg-gray-50 transition-colors cursor-pointer {{ $notification->unread() ? 'bg-blue-50/30' : '' }}">
                        <div class="flex items-start gap-4">
                            <!-- Status Indicator -->
                            <div class="flex-shrink-0 mt-1">
                                @if($notification->unread())
                                    <div class="w-3 h-3 bg-sidebar-green rounded-full"></div>
                                @else
                                    <div class="w-3 h-3 bg-gray-300 rounded-full"></div>
                                @endif
                            </div>
                            
                            <!-- Content -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between gap-4">
                                    <div class="flex-1">
                                        <h3 class="text-base font-semibold text-gray-900 mb-1">
                                            {{ $notification->data['title'] ?? __('notifications.notification') }}
                                        </h3>
                                        <p class="text-sm text-gray-600 mb-2">
                                            {{ $notification->data['message'] ?? '' }}
                                        </p>
                                        <div class="flex items-center gap-4 text-xs text-gray-400">
                                            <span>{{ $notification->created_at->format('M d, Y') }}</span>
                                            <span>•</span>
                                            <span>{{ $notification->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                    
                                    <!-- Actions -->
                                    <div class="flex items-center gap-2" wire:click.stop>
                                        @if($notification->unread())
                                            <button 
                                                wire:click="markAsRead('{{ $notification->id }}')"
                                                class="p-2 text-sidebar-green hover:bg-sidebar-green-50 rounded-lg transition-colors"
                                                title="{{ __('notifications.mark_as_read') }}">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                </svg>
                                            </button>
                                        @endif
                                        <button 
                                            wire:click="deleteNotification('{{ $notification->id }}')"
                                            wire:confirm="Are you sure you want to delete this notification?"
                                            class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                            title="{{ __('notifications.delete') }}">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $notifications->links() }}
            </div>
        @else
            <div class="px-6 py-16 text-center">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('notifications.no_notifications') }}</h3>
                <p class="text-gray-500">
                    @if($filter === 'unread')
                        {{ __('notifications.no_notifications_description') }}
                    @elseif($filter === 'read')
                        {{ __('notifications.no_notifications_description') }}
                    @else
                        {{ __('notifications.no_notifications_description') }}
                    @endif
                </p>
            </div>
        @endif
    </div>

    <!-- Notification Detail Modal -->
    @if($showModal && $selectedNotification)
    <div 
        class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4"
        wire:click="closeModal"
        x-data="{ show: true }"
        x-show="show"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0">
        <div 
            class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-hidden flex flex-col"
            wire:click.stop
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 transform scale-95"
            x-transition:enter-end="opacity-100 transform scale-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 transform scale-100"
            x-transition:leave-end="opacity-0 transform scale-95">
            
            <!-- Modal Header -->
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-sidebar-green to-sidebar-green-dark flex items-center justify-between">
                <h2 class="text-xl font-bold text-white">Notification Details</h2>
                <button 
                    wire:click="closeModal"
                    class="text-white hover:bg-white/20 rounded-lg p-2 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            
            <!-- Modal Content -->
            <div class="px-6 py-6 overflow-y-auto flex-1">
                <div class="mb-4">
                    <div class="flex items-center gap-2 mb-2">
                        @if($selectedNotification->unread())
                            <span class="w-2 h-2 bg-sidebar-green rounded-full"></span>
                        @endif
                        <span class="text-sm text-gray-500">
                            {{ $selectedNotification->created_at->format('F d, Y \a\t g:i A') }}
                        </span>
                        <span class="text-sm text-gray-400">•</span>
                        <span class="text-sm text-gray-500">
                            {{ $selectedNotification->created_at->diffForHumans() }}
                        </span>
                    </div>
                </div>
                
                <h3 class="text-2xl font-bold text-gray-900 mb-4">
                    {{ $selectedNotification->data['title'] ?? 'Notification' }}
                </h3>
                
                <div class="prose max-w-none">
                    <p class="text-gray-700 text-base leading-relaxed whitespace-pre-wrap">
                        {{ $selectedNotification->data['message'] ?? 'No message available.' }}
                    </p>
                </div>
            </div>
            
            <!-- Modal Footer -->
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    @if($selectedNotification->data['link'] ?? null)
                        <a 
                            href="{{ $selectedNotification->data['link'] }}"
                            class="bg-sidebar-green text-white px-6 py-2 rounded-lg font-medium hover:bg-sidebar-green-light transition-colors inline-flex items-center">
                            <span>View Details</span>
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    @endif
                </div>
                <button 
                    wire:click="closeModal"
                    class="px-6 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg font-medium hover:bg-gray-50 transition-colors">
                    Close
                </button>
            </div>
        </div>
    </div>
    @endif
</div>

