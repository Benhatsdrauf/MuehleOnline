<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = "user";

    public $timestamps = false;

    protected $fillable = [
        "name"
    ];

    public function statistic()
    {
        $this->belongsTo(Statistic::class, "statistic_id", "id");
    }

    public function shadow()
    {
        $this->belongsTo(Shadow::class, "shadow_id", "id");
    }

    public function user_to_game_to_moves()
    {
        $this->hasMany(UserToGameToMove::class, "user_id", "id");
    }
}
