<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MoveHistory extends Model
{
    use HasFactory;

    protected $table = "move_history";

    public $timestamps = false;

    protected $fillable = [
        "old_position", "new_position", "created_at", "utg_id"
    ];

    public function user_to_game()
    {
        return $this->belongsTo(UserToGame::class, "utg_id", "id");
    }
}
