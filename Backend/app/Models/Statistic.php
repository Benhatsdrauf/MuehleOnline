<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Statistic extends Model
{
    protected $table = "statistic";

    public $timestamps = false;

    protected $fillable = [
        "won", "lost", "moveCount", "kills", "deaths"
    ];

    public function user()
    {
        $this->hasOne(User::class, "statistic_id", "id");
    }
}
