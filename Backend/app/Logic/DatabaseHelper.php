<?php

namespace App\Logic;

use Laravel\Sanctum\PersonalAccessToken;
use App\Http\Controllers\StatisticController as Stat;
use Carbon\Carbon;
use App\Http\Controllers\UserController;

class DatabaseHelper
{
    public static function getHashedToken($user)
    {
        return PersonalAccessToken::where("tokenable_id", $user->id)->first()->token;
    }

    public static function GameEnded($game, $winner, $loser)
    {
        $loser->games()->updateExistingPivot($game->id, ["won" => false]);
        Stat::addLos($loser);

        $winner->games()->updateExistingPivot($game->id, ["won" => true]);
        Stat::addWin($winner);


        $game->is_active = false;
        $game->end_time = Carbon::now();
        $game->save();

        UserController::eloUpdate($winner, $loser, $game);
    }
}
