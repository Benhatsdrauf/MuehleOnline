<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\StatisticController as Stat;
use App\Models\Move;
use App\Events\MoveEvent;
use Laravel\Sanctum\PersonalAccessToken;
use App\Events\Turn;
use App\Logic\Error;

class StoneController extends Controller
{
    public function set(Request $request, $position)
    {
        $user = $request->user();

        $game = $user->games()->where("is_active", true)->first();

        if($game == null)
        {
            Error::throw(["game" => "You do not have any active games."], 400);
        }

        if($game->moves()->where("user_id", $user->id)->get()->count() > 8)
        {
            Error::throw(["game" => "You have already set 9 stones."], 400);
        }

        if($game->moves()->where("position", $position)->exists())
        {
            Error::throw(["game" => "This position is already set."], 400);
        }

        Stat::addMove($user);

        $move = new Move;
        $move->position = $position;
        $move->user_id = $user->id;
        $move->game_id = $game->id;
        $move->save();

        $opponent = $game->user_to_game()->where("user_id", "!=", $user->id)->first()->user()->first();

        event(new MoveEvent($opponent, null, $position));
    }

    public function delete(Request $request, $position)
    {
        $user = $request->user();

        $game = $user->games()->where("is_active", true)->first();

        if(!$game->moves()->where("position", $position)->exists())
        {
            Error::throw(["game" => "There is no stone at this position."], 400);
        }

        $move = $game->moves()->where("position", $position)->first();

        $move->position = -1;
        $move->save();
    }
}
