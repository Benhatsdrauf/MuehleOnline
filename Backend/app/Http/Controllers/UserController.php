<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;

class UserController extends Controller
{
    public function getInfo(Request $request)
    {
        $user = $request->user();

        $response = new \stdClass();
        $response->user = new \stdClass();
        $response->game = new \stdClass();
        $response->history = new \stdClass();

        $historyGames = $user->games()->where("is_active", false)->get();

        $history = [];

        foreach($historyGames as $game)
        {
            if($game->users()->count() == 2)
            {
                $userToGame = $game->user_to_game()->where("user_id", $user->id)->first();

                $entry = new \stdClass();
                $entry->won = boolval($userToGame->won);
                $entry->opponent = $game->user_to_game()->where("user_id", "!=", $user->id)->first()->user()->first()->name;
                $end_time = Carbon::parse( $game->end_time); 
                $created_time = Carbon::parse($game->created_at); 
    
                $entry->play_time = $created_time->diffInSeconds($end_time);
                $entry->start_date = $created_time->toDateString();
                array_push($history, $entry);
            }  
        }
        
        $response->user->name = $user->name;
        $response->history = array_reverse($history);

        if($user->games()->where("is_active", true)->exists())
        {
            $activeGame = $user->games()->where("is_active", true)->first();
            $activeGame->user_to_game()->where("user_id", "!=", $user->id)->first()->user()->first()->name;

            $response->game->active = true;
            $response->game->opponent = $activeGame->user_to_game()->where("user_id", "!=", $user->id)->first()->user()->first()->name;
        }
        else
        {
            $response->game->active = false;
            $response->game->opponent = "";
        }


        return response()->json($response);
    }
}
