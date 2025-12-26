<?php

namespace App\Livewire\Core;

use App\Enum\Core\NotificationFor;
use App\Enum\Core\Web\RoutesNames;
use App\Models\Message;
use App\Models\Notification;
use App\Models\User;
use Livewire\Attributes\Computed;
use Livewire\Component;

class NotificationsButton extends Component
{
    public $notificationsCount = 0;

    // Refresh the notifications on the frontend
    public function refreshNotifications()
    {
        $this->dispatch('refresh-notifications');
    }

    // Computed property to fetch notifications
    #[Computed()]
    public function notifications()
    {
        $query = Notification::query();

        // Admin-specific notifications
        if (auth()->user()->can('admin-access')) {
            $query->where('active', true)
                ->where('for_type', NotificationFor::ADMIN);
        } else {
            $query->where('targetable_id', auth()->user()->id)
                ->where('active', true);
        }
        return $query->orderBy('created_at', 'desc')->get();
    }

    // Mount method to set the notification count
    public function mount()
    {
        $this->notificationsCount = $this->notifications->count();
    }

    // Manage notification (mark as inactive and handle redirection)
    public function manageNotification(Notification $notification)
    {
        $notification->update(['active' => false]);


        if ($notification->for_type ===NotificationFor::ADMIN && $notification->targetable_type===Message::class) {
            return redirect()->route(RoutesNames::MESSAGES);
        }
    }

    public function getNotificationMessageKey($notification)
{
    return match ($notification->targetable_type) {
        Message::class => 'notifications.message.' . $notification->message,
        User::class => 'notifications.user.' . $notification->message,
        default => 'notifications.general.' . $notification->message,
    };
}


    public function render()
    {
        return view('livewire.core.notifications-button');
    }
}
