<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\StatisticController as Stat;
use App\Models\Move;
use Laravel\Sanctum\PersonalAccessToken;
use App\Events\Turn;

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

        Stat::addMove($user);

        $move = new Move;
        $move->position = $position;
        $move->user_id = $user->id;
        $move->game_id = $game->id;
        $move->save();

        $opponent = $game->user_to_game()->where("user_id", "!=", $user->id)->first()->user()->first();

        $partnerToken = PersonalAccessToken::where("tokenable_id", $opponent->id)->first()->token;

        event(new turn($partnerToken));
    }
}
