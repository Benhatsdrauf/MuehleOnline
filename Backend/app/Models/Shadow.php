<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class shadow extends Model
{
    protected $table = "shadow";

    public $timestamps = false;

    protected $fillable = [
        "pw"
    ];

    public function user()
    {
        $this->hasOne(Users::class, "shadow_id", "id");
    }
}
