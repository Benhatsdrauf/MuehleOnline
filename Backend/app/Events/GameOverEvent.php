<?php

namespace App\Events;

use App\Models\UserToGame;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Logic\DatabaseHelper as dbHelper;

class GameOverEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $token = "";
    public $won = false;
    public $message = "";
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $user, bool $won, string $message)
    {
        $this->token = dbHelper::getHashedToken($user);
        $this->won = $won;
        $this->message = $message;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('gameover.' + $this->token);
    }
}
