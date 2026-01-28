<?php

namespace App\Livewire\Notifications;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class Index extends Component
{
    use WithPagination;

    public $filter = 'all'; // all, unread, read
    public $showModal = false;
    public $selectedNotification = null;

    protected $paginationTheme = 'tailwind';

    public function mount()
    {
        // Ensure user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login');
        }
    }

    public function markAsRead($notificationId)
    {
        $notification = Auth::user()->notifications()->find($notificationId);
        if ($notification && $notification->unread()) {
            $notification->markAsRead();
            session()->flash('success', 'Notification marked as read.');
        }
    }

    public function openNotification($notificationId)
    {
        $notification = Auth::user()->notifications()->find($notificationId);
        
        if (!$notification) {
            return;
        }

        // Mark as read if unread
        if ($notification->unread()) {
            $notification->markAsRead();
        }

        // Show modal with notification details
        $this->selectedNotification = $notification;
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->selectedNotification = null;
    }

    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        session()->flash('success', 'All notifications marked as read.');
        $this->resetPage();
    }

    public function deleteNotification($notificationId)
    {
        $notification = Auth::user()->notifications()->find($notificationId);
        if ($notification) {
            $notification->delete();
            session()->flash('success', 'Notification deleted.');
        }
    }

    public function clearAll()
    {
        Auth::user()->notifications()->delete();
        session()->flash('success', 'All notifications cleared.');
        $this->resetPage();
    }

    public function updatedFilter()
    {
        $this->resetPage();
    }

    public function render()
    {
        $user = Auth::user();
        
        $query = $user->notifications()->latest();

        if ($this->filter === 'unread') {
            $query->whereNull('read_at');
        } elseif ($this->filter === 'read') {
            $query->whereNotNull('read_at');
        }

        $notifications = $query->paginate(20);
        
        $unreadCount = $user->unreadNotifications()->count();
        $totalCount = $user->notifications()->count();

        return view('livewire.notifications.index', [
            'notifications' => $notifications,
            'unreadCount' => $unreadCount,
            'totalCount' => $totalCount,
        ]);
    }
}

