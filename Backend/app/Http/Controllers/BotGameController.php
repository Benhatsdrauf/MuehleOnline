<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Game;
use App\Logic\Error;
use App\Logic\DatabaseHelper as helper;
use Carbon\Carbon;

class BotGameController extends Controller
{
    public function create(Request $request)
    {
        $sender = $request->user();

        $user = User::where("name", $sender->name)->first();

        if($user->games()->where("is_active", true)->exists())
        {
            Error::throw(["game" => "You are still in an active game."], 400);
        }

        $game = new Game;
        $game->is_active = false;
        $game->end_time = null;
        $game->whites_turn = true;
        $game->time_to_move = null;
        $game->invite_id = bin2hex(openssl_random_pseudo_bytes(16));
        $game->save();


        $senderGames = $user->games()->get();

        foreach($senderGames as $currentGame)
        {
            if($currentGame->users()->count() < 2)
            {
                $currentGame->delete();
            }
        }

        $bot = User::find(1);

        $isWhite = boolval(random_int(0, 1));

        $user->games()->attach($game->id, ["is_white" => $isWhite, "won" => false, "elo" => 0]);
        $bot->games()->attach($game->id, ["is_white" => !$isWhite, "won" => false, "elo" => 0]);

        return response()->json("success");
    }

    public function quit(Request $request)
    {
        $user = $request->user();

        $game = $user->games()->where("is_active", true)->first();

        if($game == null)
        {
            Error::throw(["game" => "You do not have any active games to quit."], 400);
        }

        $opponent = $game->user_to_game()->where("user_id", "!=", $user->id)->first()->user()->first();

       //helper::GameEnded($game, $opponent, $user, " quit the game.");

       //add bot game end method

        return response()->json();
    }

    public function getCurrentState(Request $request)
    {
        $user = $request->user();

        $game = $user->games()->where("is_active", true)->first();

        if($game == null)
        {
            Error::throw(["game" => "You do not have any active games."], 400);
        }

        $data = new \stdClass();
        $data->user = new \stdClass();
        $data->opponent = new \stdClass();

        $opponent = $game->user_to_game()->where("user_id", "!=", $user->id)->first()->user()->first();

        $WhiteMoves  = [];
        $BlackMoves = [];

        $userMoves = helper::GetUserToGame($user, $game)->moves()->pluck("position")->toArray();
        $opponentMoves = helper::GetUserToGame($opponent, $game)->moves()->pluck("position")->toArray();
        $userIsWhite = boolval($game->users()->find($user->id)->pivot->is_white);

        if($userIsWhite)
        {
            $WhiteMoves = $userMoves;
            $BlackMoves = $opponentMoves;
        }
        else
        {
            $WhiteMoves = $opponentMoves;
            $BlackMoves = $userMoves;
        }

        $deletionToken = helper::GetUserToGame($user, $game)->deletion_tokens()->first();

        $data->user->is_white = $userIsWhite;
        $data->user->has_turn = ($userIsWhite == $game->whites_turn);
        $data->user->deletion_token = ($deletionToken == null) ? "" : $deletionToken->token;
        $data->white_moves = $WhiteMoves;
        $data->black_moves = $BlackMoves;

        $data->ttm = Carbon::parse($game->time_to_move);


        $userStatistic = $user->statistic()->first();
        $opponentStatistic = $opponent->statistic()->first();

        $data->user->name = $user->name;
        $data->user->elo = $user->elo;
        $data->user->wins = $userStatistic->won;
        $data->user->losses =  $userStatistic->lost;

        $data->opponent->name = $opponent->name;
        $data->opponent->elo = $opponent->elo;
        $data->opponent->wins = $opponentStatistic->won;
        $data->opponent->losses =  $opponentStatistic->lost;

        return response()->json($data);
    }
}
