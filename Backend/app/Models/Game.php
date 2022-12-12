<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    protected $table = "game";

    public $timestamps = true;

    protected $fillable = [
        "is_active", "end_time", "invite_id"
    ];

    public function user_to_game()
    {
        return $this->hasMany(UserToGame::class, "game_id", "id");
    }

    public function moves()
    {
        return $this->hasMany(Move::class, "game_id" , "id");
    }
}
