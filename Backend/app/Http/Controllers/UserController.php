<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Game;
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
        $response->statistic = new \stdClass();

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
                $entry->elo = $userToGame->elo;
                array_push($history, $entry);
            }  
        }
        
        $response->user->name = $user->name;
        $response->user->elo = $user->elo;
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

        $response->statistic->won = $user->user_to_game()->where("won", true)->count();
        $response->statistic->lost = $user->user_to_game()->where("won", false)->count();
        $response->statistic->moves = 0;
        $response->statistic->kills = 0;
        $response->statistic->deaths = 0;


        return response()->json($response);
    }

    public static function eloUpdate(User $winner, User $loser, Game $game)
    {
        $winnerElo = $winner->elo;
        $loserElo = $loser->elo;

        $winnerUpdate = 0;
        $loserUpdate = 0;

        if($winnerElo == $loserElo)
        {
            $winnerUpdate = 15;
            $loserUpdate = 10;
        }
        else if($winnerElo > $loserElo)
        {
            $dif = $winnerElo - $loserElo;

            $winnerUpdate = (($dif / 7) + 5);
            $loserUpdate = (($dif / 8) + 2);
        }
        else
        {
            $dif = $loserElo - $winnerElo;
            
            $winnerUpdate = (($dif / 5) + 5);
            $loserUpdate = (($dif / 8) + 2);
        }

        $winner->games()->updateExistingPivot($game->id, ["elo" => $winnerUpdate]);
        $loser->games()->updateExistingPivot($game->id, ["elo" => 0 - $loserUpdate]);

        $winner->elo = $winnerElo + $winnerUpdate;
        $winner->save();

        $loser->elo = $loserElo - $loserUpdate;
        $loser->save();
    }
}
