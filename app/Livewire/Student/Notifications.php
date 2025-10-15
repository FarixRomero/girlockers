<?php

namespace App\Livewire\Student;

use App\Services\NotificationService;
use Livewire\Component;

class Notifications extends Component
{
    public $showDropdown = false;

    protected $notificationService;

    public function boot(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function markAsRead($notificationId)
    {
        $notification = auth()->user()->notifications()->findOrFail($notificationId);
        $this->notificationService->markAsRead($notification);
    }

    public function markAllAsRead()
    {
        $this->notificationService->markAllAsRead(auth()->user());
    }

    public function deleteNotification($notificationId)
    {
        $notification = auth()->user()->notifications()->findOrFail($notificationId);
        $this->notificationService->delete($notification);
    }

    public function render()
    {
        $user = auth()->user();

        $notifications = $this->notificationService->getRecentNotifications($user, 10);
        $unreadCount = $this->notificationService->getUnreadCount($user);

        return view('livewire.student.notifications', [
            'notifications' => $notifications,
            'unreadCount' => $unreadCount,
        ]);
    }
}
