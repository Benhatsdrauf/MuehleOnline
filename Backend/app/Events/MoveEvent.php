<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Logic\StoneHelper as helper;

use App\Logic\DatabaseHelper as dbHelper;

class MoveEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $oldPos = 0;
    public $newPos = 0;
    public $waitForDelete = false;
    public $ttm;
    private $token = "";

    public function __construct(User $opponent, $oldPos, $newPos, string $ttmDate ,bool $hasDeletionToken = false)
    {
        $this->token = dbHelper::getHashedToken($opponent);
        $this->oldPos = $oldPos;
        $this->newPos = $newPos;
        $this->ttm = $ttmDate;

        $game = dbHelper::GetActiveGameOrNull($opponent);

        $this->waitForDelete = $hasDeletionToken;
    }

    public function broadcastOn()
    {
        return new Channel('move.'. $this->token);
    }
}
