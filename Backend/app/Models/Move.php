<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Move extends Model
{
    protected $table = "move";

    public $timestamps = false;

    protected $fillable = [
        "row", "column", "game_id"
    ];

    public function game()
    {
        return $this->belognsTo(Game::class, "game_id", "id");
    }
}
