<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Game;
use App\Models\UserToGame;
use App\Models\User;

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

        $utg = new UserToGame;
        $utg->is_white = true;
        $utg->user_id = $user->id;
        $utg->game_id = $game->id;
        $utg->save();

        return response()->json(["invite_link" => "http://localhost:5000/join/". $game->invite_id]);
    }
}
