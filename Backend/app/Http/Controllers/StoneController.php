<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Carbon\Carbon;

use App\Http\Controllers\StatisticController as Stat;
use App\Http\Controllers\HistoryController as history;
use App\Http\Controllers\DeletionTokenController as deletion;
use App\Models\Move;
use App\Events\MoveEvent;
use Laravel\Sanctum\PersonalAccessToken;
use App\Events\Turn;
use App\Logic\Error;
use App\Logic\StoneHelper as helper;
use App\Logic\DatabaseHelper as dbHelper;

use App\Http\Requests\StoneDeleteRequest;
use App\Http\Requests\StoneMoveRequest;

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

        if(helper::UserHasMill(dbHelper::GetUserToGame($user, $game), $position))
        {
            $game->time_to_move = Carbon::now()->addMinutes(env("MOVE_TIMEOUT"));
            $game->save();

            $deletion_token = deletion::createToken(dbHelper::GetUserToGame($user, $game));
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

        return response()->josn($deletion_token);
    }

    public function delete(StoneDeleteRequest $request)
    {
        $user = $request->user();
        $deletion_token = $request->token;
        $position = $request->position;

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

        if(!deletion::isTokenCorrect(dbHelper::GetUserToGame($user, $game), $deletion_token))
        {
            Error::throw(["game" => "You are not allowed to delete a stone."], 400);
        }

        history::SetEntry($position, -1, dbHelper::GetUserToGame($opponent, $game));

        $move = dbHelper::GetUserToGame($opponent, $game)->moves()->where("position", $position)->first();
        $move->position = -1;
        $move->save();

        deletion::clearTokens(dbHelper::GetUserToGame($user, $game));

        $game->whites_turn = !$game->whites_turn;
        $game->time_to_move = Carbon::now()->addMinutes(env("MOVE_TIMEOUT"));
        $game->save();

        event(new MoveEvent($opponent, $position, -1));
    }

    public function move(StoneMoveRequest $request)
    {
        $user = $request->user();
        $oldPos = $request->old_position;
        $newPos = $request->new_position;

        $game = $user->games()->where("is_active", true)->first();

        $opponent = $game->user_to_game()->where("user_id", "!=", $user->id)->first()->user()->first();

        if($game == null)
        {
            Error::throw(["game" => "You do not have any active games."], 400);
        }
        
        if(dbHelper::GetUserToGame($user, $game)->moves()->count() > 8)
        {
            Error::throw(["game" => "You have already set 9 stones."], 400);
        }

        if(dbHelper::GetUserToGame($user, $game)->moves()->where("position", $newPos)->exists())
        {
            Error::throw(["game" => "This position is already set."], 400);
        }
  
        if(helper::UserHasTurn($game, $user))
        {
            Error::throw(["game" => "It is not your turn."], 400);
        }

        Stat::addMove($user);

        $oldMove = dbHelper::GetUserToGame($user, $game)->moves()->where("position", $oldPos)->first();
        $oldMove->position = -1;
        $oldMove->save();


        if(helper::UserHasMill(dbHelper::GetUserToGame($user, $game), $newPos))
        {
            $game->time_to_move = Carbon::now()->addMinutes(env("MOVE_TIMEOUT"));
            $game->save();

            $deletion_token = deletion::createToken(dbHelper::GetUserToGame($user, $game));

        }
        else
        {
            $game->whites_turn = !$game->whites_turn;
            $game->time_to_move = Carbon::now()->addMinutes(env("MOVE_TIMEOUT"));
            $game->save();
    
            event(new MoveEvent($opponent, $oldPos, $newPos));
        }
        
        $oldMove->position = $newPos;
        $oldMove->save();

        history::SetEntry($oldPos, $newPos, dbHelper::GetUserToGame($user, $game));

        return response()->josn($deletion_token);
    }
}
