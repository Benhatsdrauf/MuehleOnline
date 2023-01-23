<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Carbon\Carbon;

use App\Http\Controllers\StatisticController as Stat;
use App\Http\Controllers\HistoryController as history;
use App\Models\Move;
use App\Events\MoveEvent;
use Laravel\Sanctum\PersonalAccessToken;
use App\Events\Turn;
use App\Logic\Error;
use App\Logic\StoneHelper as helper;
use App\Logic\DatabaseHelper as dbHelper;

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
        
        if(dbHelper::GetUserToGame($user, $game)->moves()->count() > 8)
        {
            Error::throw(["game" => "You have already set 9 stones."], 400);
        }

        if(dbHelper::GetUserToGame($user, $game)->moves()->where("position", $position)->exists())
        {
            Error::throw(["game" => "This position is already set."], 400);
        }
  
        if(helper::UserHasTurn($game, $user))
        {
            Error::throw(["game" => "It is not your turn."], 400);
        }
        

        Stat::addMove($user);        

        $opponent = $game->user_to_game()->where("user_id", "!=", $user->id)->first()->user()->first();

        if(helper::UserHasMill($game, $user, $position))
        {
            $game->time_to_move = Carbon::now()->addMinutes(env("MOVE_TIMEOUT"));
            $game->save();
        }
        else
        {
            $game->whites_turn = !$game->whites_turn;
            $game->time_to_move = Carbon::now()->addMinutes(env("MOVE_TIMEOUT"));
            $game->save();
    
            event(new MoveEvent($opponent, null, $position));
        }
        
        history::SetEntry(null, $position, dbHelper::GetUserToGame($user, $game));

        $move = new Move;
        $move->position = $position;
        $move->utg_id = $user->games()->find($game->id)->pivot->id;
        $move->save();
    }

    public function delete(Request $request, $position)
    {
        $user = $request->user();

        $game = $user->games()->where("is_active", true)->first();

        $opponent = $game->user_to_game()->where("user_id", "!=", $user->id)->first()->user()->first();

        if(!dbHelper::GetUserToGame($opponent, $game)->moves()->where("position", $position)->exists())
        {
            Error::throw(["game" => "There is no stone at this position."], 400);
        }

        if(helper::UserHasTurn($game, $user))
        {
            Error::throw(["game" => "It is not your turn."], 400);
        }

        history::SetEntry($position, -1, dbHelper::GetUserToGame($opponent, $game));

        $move = dbHelper::GetUserToGame($opponent, $game)->moves()->where("position", $position)->first();
        $move->position = -1;
        $move->save();
    }
}
