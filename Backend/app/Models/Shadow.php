<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shadow extends Model
{
    protected $table = "shadow";

    public $timestamps = false;

    protected $fillable = [
        "pw"
    ];

    public function user()
    {
        return $this->hasOne(User::class, "shadow_id", "id");
    }
}
