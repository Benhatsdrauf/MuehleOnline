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
use App\Events\GameOverEvent;

use App\Http\Requests\StoneDeleteRequest;
use App\Http\Requests\StoneMoveRequest;
use App\Models\DeletionToken;

class StoneController extends Controller
{
    public function set(Request $request, $position)
    {
        $user = $request->user();

        $game = $user->games()->where("is_active", true)->first();

        if ($game == null) {
            Error::throw(["game" => "You do not have any active games."], 400);
        }

        $opponent = $game->user_to_game()->where("user_id", "!=", $user->id)->first()->user()->first();

        if (!helper::UserHasTurn($game, $user)) {
            Error::throw(["game" => "It is not your turn."], 400);
        }

        if (dbHelper::GetUserToGame($user, $game)->moves()->count() > 8) {
            Error::throw(["game" => "You have already set 9 stones."], 400);
        }

        if (
            helper::IsPositionSet(dbHelper::GetUserToGame($user, $game), $position) ||
            helper::IsPositionSet(dbHelper::GetUserToGame($opponent, $game), $position)
        ) {
            Error::throw(["game" => "This position is already set."], 400);
        }

        Stat::addMove($user);

        $deletion_token = "";

        if (helper::UserHasMill(dbHelper::GetUserToGame($user, $game), $position)) {
            $game->time_to_move = Carbon::now()->addMinutes(env("MOVE_TIMEOUT"));
            $game->save();

            $deletion_token = deletion::createToken(dbHelper::GetUserToGame($user, $game));
        } else {
            $game->whites_turn = !$game->whites_turn;
            $game->time_to_move = Carbon::now()->addMinutes(env("MOVE_TIMEOUT"));
            $game->save();
        }

        event(new MoveEvent($opponent, null, $position, $deletion_token != ""));
        history::SetEntry(null, $position, dbHelper::GetUserToGame($user, $game));

        $move = new Move;
        $move->position = $position;
        $move->utg_id = $user->games()->find($game->id)->pivot->id;
        $move->save();

        return response()->json($deletion_token);
    }

    public function delete(StoneDeleteRequest $request)
    {
        $user = $request->user();
        $deletion_token = $request->deletion_token;
        $position = $request->position;

        $game = $user->games()->where("is_active", true)->first();

        $opponent = $game->user_to_game()->where("user_id", "!=", $user->id)->first()->user()->first();

        if (!helper::IsPositionSet(dbHelper::GetUserToGame($opponent, $game), $position)) {
            Error::throw(["game" => "There is no stone at this position."], 400);
        }

        if (!helper::UserHasTurn($game, $user)) {
            Error::throw(["game" => "It is not your turn."], 400);
        }

        if (!deletion::isTokenCorrect(dbHelper::GetUserToGame($user, $game), $deletion_token)) {
            Error::throw(["game" => "Deletion token is wrong."], 400);
        }

        if (!helper::CanDeleteStone(dbHelper::GetUserToGame($opponent, $game), $position)) {
            Error::throw(["game" => "Can not delete stone, because its in a mill."]);
        }

        history::SetEntry($position, -1, dbHelper::GetUserToGame($opponent, $game));

        $move = dbHelper::GetUserToGame($opponent, $game)->moves()->where("position", $position)->first();
        $move->position = -1;
        $move->save();

        deletion::clearTokens(dbHelper::GetUserToGame($user, $game));

        Stat::addKill($user);
        Stat::addDeath($opponent);

        $game->whites_turn = !$game->whites_turn;
        $game->time_to_move = Carbon::now()->addMinutes(env("MOVE_TIMEOUT"));
        $game->save();

        event(new MoveEvent($opponent, $position, -1));

        $opponentMoves = dbHelper::GetUserToGame($opponent, $game)->moves()->get();

        if ($opponentMoves->where("position", "!=", -1)->count() < 3 && $opponentMoves->count() == 9) {
            dbHelper::GameEnded($game, $user, $opponent, "have no more stones left.");
        }

        if(helper::IsOpponentStale(dbHelper::GetUserToGame($user, $game), dbHelper::GetUserToGame($opponent, $game)))
        {
            dbHelper::GameEnded($game, $user, $opponent, "can not move any stones.");
        }

        return response()->json("success");
    }

    public function move(StoneMoveRequest $request)
    {
        $user = $request->user();
        $oldPos = $request->old_position;
        $newPos = $request->new_position;

        $game = $user->games()->where("is_active", true)->first();

        $opponent = $game->user_to_game()->where("user_id", "!=", $user->id)->first()->user()->first();

        if ($game == null) {
            Error::throw(["game" => "You do not have any active games."], 400);
        }

        if (!helper::UserHasTurn($game, $user)) {
            Error::throw(["game" => "It is not your turn."], 400);
        }

        if (dbHelper::GetUserToGame($user, $game)->moves()->count() < 9) {
            Error::throw(["game" => "You have not set all 9 Stones yet."], 400);
        }

        if (
            helper::IsPositionSet(dbHelper::GetUserToGame($user, $game), $newPos) ||
            helper::IsPositionSet(dbHelper::GetUserToGame($opponent, $game), $newPos)
        ) {
            Error::throw(["new_position" => "This position is already set."], 400);
        }

        if (!helper::IsPossibleMove($oldPos, $newPos, dbHelper::GetUserToGame($user, $game)->moves()->where("position", "!=", -1)->count())) {
            Error::throw(["new_position" => "Stone can not be moved there."], 400);
        }

        Stat::addMove($user);

        $oldMove = dbHelper::GetUserToGame($user, $game)->moves()->where("position", $oldPos)->first();
        $oldMove->position = -1;
        $oldMove->save();

        $deletion_token = "";
        if (helper::UserHasMill(dbHelper::GetUserToGame($user, $game), $newPos)) {
            
            //check if any opponent stones can be deleted
            if(helper::AnyStoneIsDeletable(dbHelper::GetUserToGame($opponent, $game)))
            {
                $game->time_to_move = Carbon::now()->addMinutes(env("MOVE_TIMEOUT"));
                $game->save();
    
                $deletion_token = deletion::createToken(dbHelper::GetUserToGame($user, $game));
            }
            
        } else {
            $game->whites_turn = !$game->whites_turn;
            $game->time_to_move = Carbon::now()->addMinutes(env("MOVE_TIMEOUT"));
            $game->save();
        }
        
        $oldMove->position = $newPos;
        $oldMove->save();

        event(new MoveEvent($opponent, $oldPos, $newPos, $deletion_token != ""));

        history::SetEntry($oldPos, $newPos, dbHelper::GetUserToGame($user, $game));
        
        if(helper::IsOpponentStale(dbHelper::GetUserToGame($user, $game), dbHelper::GetUserToGame($opponent, $game)))
        {
            dbHelper::GameEnded($game, $user, $opponent, "can not move any stones.");
        }
        
        return response()->json($deletion_token);
    }
}
