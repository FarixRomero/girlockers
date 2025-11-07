<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Notification;

class NotificationBell extends Component
{
    public $showDropdown = false;

    public function toggleDropdown()
    {
        $this->showDropdown = !$this->showDropdown;
    }

    public function markAsRead($notificationId)
    {
        $notification = Notification::find($notificationId);
        if ($notification && $notification->user_id === auth()->id()) {
            $notification->markAsRead();
        }
    }

    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications()->update(['read_at' => now()]);
        $this->showDropdown = false;
    }

    public function render()
    {
        $notifications = auth()->user()->notifications()->take(5)->get();
        $unreadCount = auth()->user()->unreadNotifications()->count();

        return view('livewire.admin.notification-bell', [
            'notifications' => $notifications,
            'unreadCount' => $unreadCount,
        ]);
    }
}
