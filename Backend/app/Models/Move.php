<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class move extends Model
{
    protected $table = "move";

    public $timestamps = false;

    protected $fillable = [
        "row", "column"
    ];

    public function game_to_moves()
    {
        return $this->hasMany(GameToMove::class, "move_id", "id");
    }
}
