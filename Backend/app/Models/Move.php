<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Move extends Model
{
    protected $table = "move";

    public $timestamps = false;

    protected $fillable = [
        "user_id", "position", "game_id"
    ];

    public function game()
    {
        return $this->belognsTo(Game::class, "game_id", "id");
    }

    public function user()
    {
        return $this->belongsTo(User::class, "user_id", "id");
    }
}
