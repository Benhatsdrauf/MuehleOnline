<?php

namespace App\Logic;

use App\Models\User;
use App\Models\Game;

use App\Models\UserToGame;

class BotHelper
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

    public static function GetPossiblePosition(array $botPositions, array $opponentPositions, GameStatusEnum $gameStatus)
    {
        $allPos = [];


        if($gameStatus == GameStatusEnum::Set)
        {
            for($i = 0; $i < 24; $i++)
            {
    
                if(!in_array($i, $botPositions) && !in_array($i, $opponentPositions))
                {
                    array_push($allPos, $i);
                }
    
            }
    
            return $allPos;
        }
        else if($gameStatus == GameStatusEnum::Move)
        {
            $result = collect();
            $botPositions = collect($botPositions);
            $opponentPositions = collect($opponentPositions);

            $allPositions = $botPositions->merge($opponentPositions);
            foreach($botPositions as $pos)
            {
                $possiblePosition = BotHelper::GetPossibleMoves()[$pos];

                $currentArray = [];
                foreach($possiblePosition as $posPos)
                {
                    if(!$allPositions->contains($posPos))
                    {
                        array_push($currentArray, $posPos);
                    }
                }

                $result->put($pos, $currentArray);
            }

            return $result;
        }
        else if($gameStatus == GameStatusEnum::Jump)
        {
            for($i = 0; $i < 24; $i++)
            {
    
                if(!in_array($i, $botPositions) && !in_array($i, $opponentPositions))
                {
                    array_push($allPos, $i);
                }
    
            }
    
            return $allPos;
        }
    }

    public static function array_countMinusOnes(array $array) : int
    {
        return count(array_filter($array, static function ($current) {
            return $current === -1;
        }));
    }


    public static function GameIsOver(array $botPositions, array $opponentPositions)
    {
        //wins:
        //cant move
        //less than 3 stones

    }

    public static function BotHasWon(array $botPositions, array $opponentPositions) : int
    {
        $result = 0;

        if(BotHelper::UserLostByBeingStale($botPositions, $opponentPositions))
        {
            $result = 1;
        }
        else if(BotHelper::BotHasWonByElimination($opponentPositions))
        {
            $result = 1;
        }
        else
        {
            $result = -1;
        }

        return $result;
    }

    public static function AnyIsStale(array $botPositions, array $opponentPositions): bool
    {
        $allPositions = collect($botPositions)->merge(collect($opponentPositions));

        foreach($botPositions as $position)
        {
            $possibleMoves = BotHelper::GetPossibleMoves()[$position];

            $diff = $allPositions->diff($possibleMoves);

            //check if all possible moves are set by stones
            if($diff->count() !== ($allPositions->count() - $possibleMoves->count()))
            {
                return false;
            }
        }

        foreach($opponentPositions as $position)
        {
            $possibleMoves = BotHelper::GetPossibleMoves()[$position];

            $diff = $allPositions->diff($possibleMoves);

            //check if all possible moves are set by stones
            if($diff->count() !== ($allPositions->count() - $possibleMoves->count()))
            {
                return false;
            }
        }

        return true;
    }

    public static function UserLostByBeingStale(array $botPositions, array $opponentPositions) : bool
    {
        $allPositions = collect($botPositions)->merge(collect($opponentPositions));

        foreach($opponentPositions as $position)
        {
            $possibleMoves = BotHelper::GetPossibleMoves()[$position];

            $diff = $allPositions->diff($possibleMoves);

            //check if all possible moves are set by stones
            if($diff->count() !== ($allPositions->count() - $possibleMoves->count()))
            {
                return false;
            }
        }

        return true;
    }

    public static function BotHasWonByElimination(array $opponentPositions)
    {
        return BotHelper::array_countMinusOnes($opponentPositions) < 3;
    }
}
