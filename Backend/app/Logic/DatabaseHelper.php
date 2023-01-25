<?php

namespace App\Logic;


use App\Models\Game;
use App\Models\User;
use App\Models\UserToGame;
use Laravel\Sanctum\PersonalAccessToken;
use App\Http\Controllers\StatisticController as Stat;
use App\Http\Controllers\DeletionTokenController as deletion;
use App\Logic\DatabaseHelper as dbHelper;
use Carbon\Carbon;
use App\Http\Controllers\UserController;

class DatabaseHelper
{
    public static function getHashedToken($user)
    {
        return PersonalAccessToken::where("tokenable_id", $user->id)->first()->token;
    }

    public static function EndGame($game, $winner, $loser)
    {
        $loser->games()->updateExistingPivot($game->id, ["won" => false]);
        Stat::addLos($loser);

        $winner->games()->updateExistingPivot($game->id, ["won" => true]);
        Stat::addWin($winner);

        deletion::clearTokens(dbHelper::GetUserToGame($winner, $game));
        deletion::clearTokens(dbHelper::GetUserToGame($loser, $game));

        $game->is_active = false;
        $game->end_time = Carbon::now();
        $game->save();

        UserController::eloUpdate($winner, $loser, $game);
    }

    public static function GetUserToGame(User $user, Game $game)
    {
        return UserToGame::where("user_id", $user->id)->where("game_id", $game->id)->first();
    }
}
