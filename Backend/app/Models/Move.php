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

    public function user_to_game()
    {
        return $this->belongsTo(UserToGame::class, "move_id", "id");
    }
}
