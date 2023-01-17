<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Game;
use App\Models\UserToGame;
use App\Models\User;
use App\Logic\Error;
use App\Events\PlayerReady;
use App\Events\Turn;
use Laravel\Sanctum\PersonalAccessToken;
use Carbon\Carbon;
use App\Http\Controllers\UserController;

use \stdClass;

class GameController extends Controller
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

        $user->games()->attach($game->id, ["is_white" => boolval(random_int(0, 1)), "won" => false, "elo" => 0]);

        return response()->json(["invite_link" => "http://localhost:5173/game/join/". $game->invite_id]);
    }

    public function join(Request $request, $guid)
    {
        $sender = $request->user();

        $user = User::where("name", $sender->name)->first();

        $game = Game::where("invite_id", $guid)->first();

        if($game == null)
        {
            Error::throw(["guid" => "This guid does not exist."], 400);
        }

        if($game->users()->count() > 1)
        {
            Error::throw(["guid" => "This guid does not exist."], 400);
        }

        if($game->users()->first()->id == $user->id)
        {
            Error::throw(["guid" => "You are already participating in this game."], 400);
        }       

        $opponent = $game->users()->first();

        $user->games()->attach($game->id, ["is_white" => !$opponent->is_white, "won" => false, "elo" => 0]);
        $game->is_active = true;
        $game->save();

        $partnerToken = PersonalAccessToken::where("tokenable_id", $opponent->id)->first()->token;

        event(new playerReady($partnerToken));
    }

    public function quit(Request $request)
    {
        $user = $request->user();

        $game = $user->games()->where("is_active", true)->first();

        if($game == null)
        {
            Error::throw(["game" => "You do not have any active games to quit."], 400);
        }

        $user->games()->updateExistingPivot($game->id, ["won" => false]);

        $opponent = $game->user_to_game()->where("user_id", "!=", $user->id)->first()->user()->first();

        if($opponent != null)
        {
            $opponent->games()->updateExistingPivot($game->id, ["won" => true]);
        }

        $game->is_active = false;
        $game->end_time = Carbon::now();
        $game->save();

        UserController::eloUpdate($opponent, $user, $game);

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

        $opponent = $game->user_to_game()->where("user_id", "!=", $user->id)->first()->user();

        $WhiteMoves  = [];
        $BlackMoves = [];

        $userMoves = $user->moves()->where("game_id", $game->id)->get("position");
        $opponentMoves = $user->moves()->where("game_id", $game->id)->get("position");

        if($user->is_white)
        {
            $WhiteMoves = $userMoves;
            $BlackMoves = $opponentMoves;
        }
        else
        {
            $WhiteMoves = $opponentMoves;
            $BlackMoves = $userMoves;
        }

        $userIsWhite = boolval($game->users()->find($user->id)->pivot->is_white);

        $data->user->is_white = $userIsWhite;
        $data->user->has_turn = ($userIsWhite == $game->whites_turn);
        $data->white_moves = $WhiteMoves;
        $data->black_moves = $BlackMoves;

        return response()->json($data);
    }

    public function move(Request $request, integer $position)
    {
        $user = $request->user();

        $game = $user->games()->where("is_active", true)->first();

        if($game == null)
        {
            Error::throw(["game" => "You do not have any active games."], 400);
        }

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
