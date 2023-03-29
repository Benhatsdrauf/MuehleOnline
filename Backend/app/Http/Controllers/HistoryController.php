<?php

namespace App\Http\Controllers;

use App\Models\MoveHistory;
use App\Models\UserToGame;
use Carbon\Carbon;
class HistoryController extends Controller
{
    public static function SetEntry($oldPos, $newPos, UserToGame $utg)
    {
        $move = new MoveHistory();
        $move->old_position = $oldPos;
        $move->new_position = $newPos;
        $move->created_at = Carbon::now();
        $move->utg_id = $utg->id;
        $move->save();
    }
}
