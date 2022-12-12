<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class Move implements ShouldBroadcast
{
    //https://devdojo.com/bobbyiliev/how-to-use-laravel-websockets#:~:text=The%20Laravel%20WebSockets%20package%20emulates,a%20direct%20replacement%20for%20Pusher.
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $move;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($move)
    {
        $this->move = $move;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('moves');
    }
}
