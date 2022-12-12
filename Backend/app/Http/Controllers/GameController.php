<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Game;
use App\Models\UserToGame;
use App\Models\User;
use App\Logic\Error;

use \stdClass;

class GameController extends Controller
{
    public function create(Request $request)
    {
        $sender = $request->user();

        $user = User::where("name", $sender->name)->first();

        $game = new Game;
        $game->is_active = false;
        $game->end_time = null;
        $game->invite_id = bin2hex(openssl_random_pseudo_bytes(16));
        $game->save();


        $senderGames = $user->games()->get();

        foreach($senderGames as $currentGame)
        {
            if(count($currentGame->users()->get()) < 2)
            {
                $currentGame->delete();
            }
        }

        $user->games()->attach($game->id, ["is_white" => true]);

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

        if(count($game->users()->get()) > 1)
        {
            Error::throw(["guid" => "This guid does not exist."], 400);
        }

        if($game->users()->first()->id == $user->id)
        {
            Error::throw(["guid" => "You are already participating in this game."], 400);
        }

        $game->is_active = true;
        $game->save();
        $user->games()->attach($game->id, ["is_white" => false]);
    }
}
