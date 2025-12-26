<?php

namespace App\Events\Core;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ReplyEvent
{
    // Include traits for event dispatching, socket interaction, and model serialization
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @param array $reply An array containing reply data (default empty array)
     */
    public function __construct(public array $reply = [])
    {
        // Constructor property promotion assigns the $reply array
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * Defines the channels for event broadcasting.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        // Broadcasts on a private channel named 'channel-name'
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
