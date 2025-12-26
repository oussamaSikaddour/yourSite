<?php

namespace App\Events\Core\Auth;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class VerificationEmailEvent
{
    // Use traits for event dispatching, socket interaction, and serialization of models
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @param User $user The user for whom the verification email is relevant
     */
    public function __construct(public User $user)
    {
        // Constructor property promotion automatically assigns $user
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * Defines the broadcast channels if this event is broadcasted.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        // Broadcasting on a private channel named 'channel-name'
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
