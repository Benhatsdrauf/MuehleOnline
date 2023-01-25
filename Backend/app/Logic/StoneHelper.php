<?php

namespace App\Logic;

use Laravel\Sanctum\PersonalAccessToken;
use App\Http\Controllers\StatisticController as Stat;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Game;

use App\Logic\DatabaseHelper as dbHelper;
use App\Http\Controllers\UserController;
use App\Models\UserToGame;
use Illuminate\Database\Eloquent\Collection;

class StoneHelper
{
    public static function GetPossibleMills()
    {
        return collect([
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

    public static function GetPossibleMoves()
    {
        return collect([
            collect([1, 7]),            // 0
            collect([0, 2, 9]),        // 1
            collect([1, 3]),           // 2
            collect([2, 4, 11]),       // 3
            collect([3, 5]),           // 4
            collect([4, 6, 13]),       // 5
            collect([5, 7]),           // 6
            collect([0, 6, 15]),       // 7
            collect([9, 15]),          // 8
            collect([1, 8, 10, 17]),      // 9
            collect([9, 11]),         // 10
            collect([3, 10, 12, 19]), // 11
            collect([11, 13]),        // 12
            collect([5, 12, 14, 21]), // 13
            collect([13, 15]),        // 14
            collect([7, 8, 14, 23]),  // 15
            collect([17, 23]),        // 16
            collect([9, 16, 18]),     // 17
            collect([17, 19]),        // 18
            collect([11, 18, 20]),    // 19
            collect([19, 21]),        // 20
            collect([13, 20, 22]),    // 21
            collect([21, 23]),        // 22
            collect([15, 16, 22])     // 23
        ]);
    }

    /**
     * Determine if newly set/moved stone creates a mill. Has to be call befor writing new Pos to database!
     *
     * @return bool
     */
    public static function UserHasMill(UserToGame $utg,  int $newPos): bool
    {
        $moves = $utg->moves()->pluck("position");

        foreach (StoneHelper::GetPossibleMills() as $mill) {
            if ($mill->contains($newPos)) {

                $mill = $mill->filter(function ($value, $key) use ($newPos) {
                    return $value != $newPos;
                })->values();

                if ($moves->contains($mill[0]) && $moves->contains($mill[1])) {
                    return true;
                }
            }
        }
        return false;
    }

    public static function UserHasTurn(Game $game, User $user): bool
    {
        $userIsWhite = boolval($game->users()->find($user->id)->pivot->is_white);

        return $game->whites_turn == $userIsWhite;
    }

    /**
     * Determine if stone that should be deleted, is part of a mill.
     *
     * @return bool
     */
    public static function CanDeleteStone(UserToGame $utg, int $position): bool
    {
        return !StoneHelper::UserHasMill($utg, $position);
    }

    public static function IsPositionSet(UserToGame $utg, int $position): bool
    {
        return $utg->moves()->where("position", $position)->first() != null;
    }

    /**
     * Determine if stone can be moved to this position.
     *
     * @return bool
     */
    public static function IsPossibleMove(int $oldPos, int $newPos, int $playerStoneCount): bool
    {
        if ($playerStoneCount < 4) {
            return true;
        } else {
            $possibleMove = StoneHelper::GetPossibleMoves()[$oldPos];

            foreach ($possibleMove as $position) {
                if ($position == $newPos) {
                    return true;
                }
            }

            return false;
        }
    }
}
