<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReplayGetRequest;
use App\Logic\DatabaseHelper;
use App\Logic\Error;
use App\Models\Game;
use Illuminate\Http\Request;
use stdClass;

class ReplayController extends Controller
{
    public function Get(ReplayGetRequest $request)
    {
        $inviteId = $request->invite_id;
        $game = Game::where("invite_id", $inviteId)->first();

        if(boolval($game->is_avtive))
        {
            Error::throw(["game" => "This game is still active."], 400);
        }

        $user = $request->user();


        $opponent = $game->user_to_game()->where("user_id", "!=", $user->id)->first()->user()->first();

        $result = new stdClass();

        $result->user = new stdClass();
        $result->opponent = new stdClass();
        $result->game = new stdClass();

        $currentUTG = DatabaseHelper::GetUserToGame($user, $game);
        $opponentUTG = DatabaseHelper::GetUserToGame($opponent, $game);

        $result->user->name = $user->name;
        $result->user->is_white = boolval($currentUTG->is_white);
        $result->user->won = boolval($currentUTG->won);
        $result->user->elo = $currentUTG->elo;

        $result->opponent->name = $opponent->name;
        $result->opponent->elo = $opponentUTG->elo;

        $result->game->start = $game->created_at;
        $result->game->end = $game->end_time;

        $result->user_moves = $currentUTG->move_histories();
        $result->opponent_moves = $opponentUTG->move_histories();
        

        return response()->json($result);
    }
}
