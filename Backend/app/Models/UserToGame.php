<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserToGame extends Model
{
    protected $table = "user_to_game";

    public $timestamps = false;

    protected $fillable = [
        "user_id", "game_id", "isWhite", "won", "elo"
    ];

    public function user()
    {
        return $this->belongsTo(User::class, "user_id", "id");
    }

    public function game()
    {
        return $this->belongsTo(Game::class, "game_to_move_id", "id");
    }

    public function deletion_tokens()
    {
        return $this->hasMany(DeletionToken::class, "utg_id", "id");
    }

    public function move_histories()
    {
        return $this->hasMany(MoveHistory::class, "utg_id", "id");
    }

    public function moves()
    {
        return $this->hasMany(Move::class, "utg_id", "id");
    }
}
