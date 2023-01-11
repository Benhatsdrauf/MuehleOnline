<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Game;
use App\Models\UserToGame;
use App\Models\User;
use App\Logic\Error;
use App\Events\PlayerReady;
use Laravel\Sanctum\PersonalAccessToken;
use Carbon\Carbon;

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

        $user->games()->attach($game->id, ["is_white" => true, "won" => false]);

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

        $partnerId = $game->users()->first()->id;

        $user->games()->attach($game->id, ["is_white" => false, "won" => false]);
        $game->is_active = true;
        $game->save();

        $partnerToken = PersonalAccessToken::where("tokenable_id", $partnerId)->first()->token;

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

        $oponent = $game->user_to_game()->where("user_id", "!=", $user->id)->first()->user()->first();

        if($oponent != null)
        {
            $oponent->games()->updateExistingPivot($game->id, ["won" => true]);
        }

        $game->is_active = false;
        $game->end_time = Carbon::now();
        $game->save();

        return response()->json();
    }
}
