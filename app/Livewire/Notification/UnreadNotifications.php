<?php

namespace App\Livewire\Notification;

use Livewire\Component;
use App\Models\Notification;

class UnreadNotifications extends Component
{
    public $show = false;

    public function toggleShow()
    {
        $this->show = !$this->show;
    }

    public function markAsRead($id)
    {
        $notification = Notification::where('id', $id)
            ->where('user_id', auth()->id())
            ->first();
        if ($notification) {
            $notification->markAsRead();
        }
    }

    public function render()
    {
        $notifications = Notification::where('user_id', auth()->id())
            ->where('is_read', false)
            ->orderByDesc('created_at')
            ->get();
        return view('livewire.notification.unread-notifications', [
            'notifications' => $notifications,
            'show' => $this->show,
        ]);
    }
}
