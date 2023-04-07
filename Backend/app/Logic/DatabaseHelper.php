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
use App\Events\GameOverEvent;

class DatabaseHelper
{
    public static function getHashedToken(User $user)
    {
        return PersonalAccessToken::where("tokenable_id", $user->id)->first()->token;
    }

    public static function GameEnded(Game $game, User $winner, User $loser, string $message)
    {
        $loser->games()->updateExistingPivot($game->id, ["won" => false]);
        Stat::addLos($loser);

        $winner->games()->updateExistingPivot($game->id, ["won" => true]);
        Stat::addWin($winner);

        deletion::clearTokens(dbHelper::GetUserToGame($winner, $game));
        deletion::clearTokens(dbHelper::GetUserToGame($loser, $game));

        $game->is_active = false;
        $game->end_time = Carbon::now();
        $game->end_reason = $message;
        $game->save();

        UserController::eloUpdate($winner, $loser, $game);

        event(new GameOverEvent($winner, true, "Your opponent " . $message));
        event(new GameOverEvent($loser, false, "You " . $message));
    }

    public static function GetUserToGame(User $user, Game $game)
    {
        return UserToGame::where("user_id", $user->id)->where("game_id", $game->id)->first();
    }

    public static function GetActiveGameOrNull(User $user)
    {
        return $user->games()->where("is_active", true)->first();
    }
}
