<?php

namespace App\Logic;

use Laravel\Sanctum\PersonalAccessToken;
use App\Http\Controllers\StatisticController as Stat;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Game;

use App\Logic\DatabaseHelper as dbHelper;
use App\Http\Controllers\UserController;
use Illuminate\Database\Eloquent\Collection;

class StoneHelper
{
    public static function GetPossibleMills()
    {
        return $possibleMills = collect([
            collect([0, 1, 2]),
            collect([8, 9, 10]),
            collect([16, 17, 18]),
            collect([7, 15, 23]),
            collect([19, 11, 3]),
            collect([22, 21, 20]),
            collect([14, 13, 12]),
            collect([6, 5, 4]),
            collect([0, 7, 6]),
            collect([8, 15, 14]),
            collect([16, 23, 22]),
            collect([1, 9, 17]),
            collect([21, 13, 5]),
            collect([18, 19, 20]),
            collect([10, 11, 12]),
            collect([2, 3, 4])
        ]);
    }

    //Has to be call befor writing new Pos to database
    public static function UserHasMill(Game $game, User $user, $newPos)
    {
        $moves = dbHelper::GetUserToGame($user, $game)->moves()->pluck("position");

        foreach(StoneHelper::GetPossibleMills() as $mill)
        {
            if($mill->contains($newPos))
            {          
                $mill = $mill->forget($newPos)->values();

                if($moves->contains($mill[0]) && $moves->contains($mill[1]))
                {
                    return true;
                }
            }
        }
        return false;
    }

    public static function UserHasTurn(Game $game, User $user)
    {
        $userIsWhite = boolval($game->users()->find($user->id)->pivot->is_white);

        if($game->whites_turn != $userIsWhite)
        {
           return false;
        }

        return true;
    }
}
