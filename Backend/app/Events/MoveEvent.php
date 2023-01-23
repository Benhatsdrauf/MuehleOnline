<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

use App\Logic\DatabaseHelper as helper;

class MoveEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $oldPos = 0;
    public $newPos = 0;
    private $token = "";

    public function __construct($opponent, $oldPos, $newPos)
    {
        $this->token = helper::getHashedToken($opponent);
        $this->oldPos = $oldPos;
        $this->newPos = $newPos;
    }


    public function broadcastOn()
    {
        return new Channel('move.'. $this->token);
    }
}
