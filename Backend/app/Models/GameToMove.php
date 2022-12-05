<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameToMove extends Model
{
    protected $table = "game_to_move";

    public $timestamps = false;

    protected $fillable = [
        "game_id", "move_id"
    ];

    public function game()
    {
        return $this->belongsTo(Game::class, "game_id", "id");
    }

    public function move()
    {
        return $this->belongsTo(Move::class, "move_id", "id");
    }

    public function user_to_game_to_moves()
    {
        return $this->hasMany(UserToGameToMove::class, "game_to_move_id", "id");
    }
}
