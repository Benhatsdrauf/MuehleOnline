<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserToGame extends Model
{
    protected $table = "user_to_game";

    public $timestamps = false;

    protected $fillable = [
        "user_id", "game_id", "isWhite"
    ];

    public function user()
    {
        return $this->belongsTo(User::class, "user_id", "id");
    }

    public function game()
    {
        return $this->belongsTo(Game::class, "game_to_move_id", "id");
    }
}
