<?php

namespace App\Livewire\Layout;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Redirect;

class NotificationDropdown extends Component
{
    public $showDropdown = false;
    public $notifications;
    public $unreadCount = 0;

    protected $listeners = ['notificationReceived' => 'refreshNotifications'];

    public function mount()
    {
        $this->notifications = collect();
        $this->unreadCount = 0;
        
        if (Auth::check()) {
            $this->loadNotifications();
        }
    }

    public function updatedShowDropdown($value)
    {
        if ($value) {
            $this->loadNotifications();
        }
    }

    public function loadNotifications()
    {
        if (!Auth::check()) {
            $this->notifications = collect();
            $this->unreadCount = 0;
            return;
        }

        try {
            $user = Auth::user();
            $this->notifications = $user->notifications()->latest()->take(5)->get();
            $this->unreadCount = $user->unreadNotifications()->count();
        } catch (\Exception $e) {
            \Log::error('Failed to load notifications', ['error' => $e->getMessage()]);
            $this->notifications = collect();
            $this->unreadCount = 0;
        }
    }

    public function toggleDropdown()
    {
        // Redirect to notifications page instead of showing dropdown
        return $this->redirect(route('notifications.index'), navigate: false);
    }

    public function markAsRead($notificationId)
    {
        $notification = Auth::user()->notifications()->find($notificationId);
        if ($notification && $notification->unread()) {
            $notification->markAsRead();
            $this->loadNotifications();
        }
    }

    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        $this->loadNotifications();
        session()->flash('notification-success', 'All notifications marked as read.');
    }

    public function refreshNotifications()
    {
        $this->loadNotifications();
    }

    public function render()
    {
        // Always refresh count on render to ensure it's up to date
        if (Auth::check()) {
            try {
                $this->unreadCount = Auth::user()->unreadNotifications()->count();
            } catch (\Exception $e) {
                \Log::error('Failed to get unread count in render', ['error' => $e->getMessage()]);
                $this->unreadCount = 0;
            }
        }
        
        return view('livewire.layout.notification-dropdown');
    }
}
