<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    protected $table = "game";

    public $timestamps = false;

    protected $fillable = [
        
    ];

    public function game_to_moves()
    {
        $this->hasMany(GameToMove::class, "game_id", "id");
    }
}
