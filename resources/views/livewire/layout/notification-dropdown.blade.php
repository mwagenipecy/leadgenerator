@if(auth()->check())
<div class="relative">
    <!-- Notification Bell Button - Redirects to notifications page -->
    <a 
        href="{{ route('notifications.index') }}"
        class="relative p-2 hover:bg-gray-100 rounded-lg transition-colors duration-200 group inline-block"
        title="Notifications">
        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-gray-600 group-hover:text-sidebar-green transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
        </svg>
        <!-- Notification Badge -->
        @if(isset($unreadCount) && $unreadCount > 0)
            <span class="absolute -top-1 -right-1 min-w-[20px] h-5 bg-sidebar-green text-white text-xs font-bold rounded-full flex items-center justify-center px-1.5 animate-pulse">
                {{ $unreadCount > 99 ? '99+' : $unreadCount }}
            </span>
        @endif
    </a>

    <!-- Notification Dropdown -->
    @if($showDropdown)
    <div 
        class="absolute right-0 mt-2 w-80 sm:w-96 bg-white rounded-xl shadow-xl border border-gray-200 z-50 max-h-[500px] flex flex-col"
        x-data="{ show: true }"
        x-show="show"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 transform scale-95"
        x-transition:enter-end="opacity-100 transform scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 transform scale-100"
        x-transition:leave-end="opacity-0 transform scale-95"
        wire:click.stop>
        
        <!-- Header -->
        <div class="px-4 py-3 border-b border-gray-200 flex items-center justify-between bg-gray-50 rounded-t-xl">
            <h3 class="text-sm font-semibold text-gray-900">Notifications</h3>
            @if($unreadCount > 0)
                <button 
                    wire:click="markAllAsRead"
                    class="text-xs text-sidebar-green hover:text-sidebar-green-light font-medium">
                    Mark all as read
                </button>
            @endif
        </div>

        <!-- Notifications List -->
        <div class="overflow-y-auto flex-1">
            @if($notifications && $notifications->count() > 0)
                <div class="divide-y divide-gray-100">
                    @foreach($notifications as $notification)
                        <div 
                            wire:click="markAsRead('{{ $notification->id }}')"
                            wire:key="notification-{{ $notification->id }}"
                            class="px-4 py-3 hover:bg-gray-50 cursor-pointer transition-colors {{ $notification->unread() ? 'bg-blue-50/50' : '' }}">
                            <div class="flex items-start gap-3">
                                <!-- Icon -->
                                <div class="flex-shrink-0 mt-0.5">
                                    @if($notification->unread())
                                        <div class="w-2 h-2 bg-sidebar-green rounded-full"></div>
                                    @else
                                        <div class="w-2 h-2 bg-gray-300 rounded-full"></div>
                                    @endif
                                </div>
                                
                                <!-- Content -->
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900">
                                        {{ $notification->data['title'] ?? 'Notification' }}
                                    </p>
                                    <p class="text-xs text-gray-600 mt-1 line-clamp-2">
                                        {{ $notification->data['message'] ?? '' }}
                                    </p>
                                    <p class="text-xs text-gray-400 mt-1">
                                        {{ $notification->created_at->diffForHumans() }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="px-4 py-12 text-center">
                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    <p class="text-sm text-gray-500">No notifications</p>
                </div>
            @endif
        </div>

        <!-- Footer -->
        @if($notifications && $notifications->count() > 0)
            <div class="px-4 py-2 border-t border-gray-200 bg-gray-50 rounded-b-xl">
                <a href="{{ route('notifications.index') }}" class="text-xs text-sidebar-green hover:text-sidebar-green-light font-medium text-center block">
                    View all notifications
                </a>
            </div>
        @endif
    </div>
    @endif
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const notificationComponent = @this;
            const button = event.target.closest('button[wire\\:click="toggleDropdown"]');
            const dropdown = event.target.closest('[wire\\:id*="notification-dropdown"]');
            
            if (notificationComponent && notificationComponent.showDropdown) {
                if (!dropdown && !button) {
                    notificationComponent.set('showDropdown', false);
                }
            }
        });
    });

</script>
@endif
