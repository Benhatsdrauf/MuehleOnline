<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;


class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $table = "user";

    public $timestamps = false;

    protected $fillable = [
        "name"
    ];

    public function statistic()
    {
        return $this->belongsTo(Statistic::class, "statistic_id", "id");
    }

    public function shadow()
    {
        return $this->belongsTo(Shadow::class, "shadow_id", "id");
    }

    public function user_to_game_to_moves()
    {
        return $this->hasMany(UserToGameToMove::class, "user_id", "id");
    }
}
