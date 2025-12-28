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

class PasswordGenerationEvent
{
    // Import Laravel traits to help with event dispatching, socket interactions, and model serialization
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @param User $user The user for whom the password was generated
     * @param string $password The generated password string
     */
    public function __construct(public User $user, public string $password)
    {
        // Explicitly assign properties (not needed with constructor property promotion, but kept here)
        $this->password = $password; //
        $this->user = $user; //
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * This method defines the broadcasting channels if the event is broadcasted.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        // Return a private channel named 'channel-name' for broadcasting
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
