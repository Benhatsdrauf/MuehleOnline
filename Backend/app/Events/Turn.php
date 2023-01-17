<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class Turn implements ShouldBroadcast
{
    //https://devdojo.com/bobbyiliev/how-to-use-laravel-websockets#:~:text=The%20Laravel%20WebSockets%20package%20emulates,a%20direct%20replacement%20for%20Pusher.
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $your_turn;
    private $token;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($token)
    {
        $this->$your_turn = true;
        $this->$token = $token;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel("your_move.".$this->token);
    }
}
