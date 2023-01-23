<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeletionToken extends Model
{
    use HasFactory;

    protected $table = "deletion_token";

    public $timestamps = false;

    protected $fillable = [
        "token"
    ];

    public function user_to_game()
    {
        return $this->hasOne(UserToGame::class, "deletion_id", "id");
    }
}
