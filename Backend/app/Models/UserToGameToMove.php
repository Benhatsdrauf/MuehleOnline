<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserToGameToMove extends Model
{
    protected $table = "user_to_game_to_move";

    public $timestamps = false;

    protected $fillable = [
        "user_id", "game_to_move_id", "isWhite"
    ];

    public function user()
    {
        $this->belongsTo(User::class, "user_id", "id");
    }

    public function game_to_move()
    {
        $this->belongsTo(GameToMove::class, "game_to_move_id", "id");
    }
}
