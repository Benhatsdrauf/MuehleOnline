<?php

namespace App\Http\Controllers;

use App\Logic\BotHelper as helper;
use App\Logic\GameStatusEnum;
use App\Models\UserToGame;
use App\Models\DeletionToken;
use Illuminate\Http\Request;
use App\Models\Game;

class BotController extends Controller
{
    public function move(Game $game)
    {
        $botMoves = $game->users()->where("user_id", 1)->moves()->pluck("position");

        $opponentMoves = $game->users()->where("user_id", "!=", 1)->moves()->pluck("position");

        BotController::MinMaxSet($botMoves, $opponentMoves, PHP_INT_MAX, true);
    }


    public static function MinMaxSet(array $botMoves, array $opponentMoves, int $depth, bool $isMax)
    {
        if($depth == 0 || helper::GameIsOver($botMoves, $opponentMoves))
        {
            return helper::BotHasWon($botMoves, $opponentMoves);
        }


        if ($isMax) {
            $maxEval = 0;

            if (count($botMoves) < 9) //set
            {
                $possibleMoves = helper::GetPossiblePosition($botMoves, $opponentMoves, GameStatusEnum::Set);

                foreach ($possibleMoves as $move) {

                    $botMovesCopy = $botMoves;

                    array_push($botMovesCopy, $move);

                    $maxEval += BotController::MinMaxSet($botMovesCopy, $opponentMoves, $depth - 1, false);
                }
            } else if (helper::array_countMinusOnes($botMoves) < 6) //move
            {
                $possibleMoves = helper::GetPossiblePosition($botMoves, $opponentMoves, GameStatusEnum::Move);

                foreach($possibleMoves as $moveArray)
                {
                    $botMovesCopy = $botMoves;

                    unset($botMovesCopy[$moveArray->key]);

                    foreach($moveArray as $move)
                    {
                        array_push($botMovesCopy, $move);

                        $maxEval += BotController::MinMaxSet($botMovesCopy, $opponentMoves, $depth - 1, false);
                    }
                }
            } else if (helper::array_countMinusOnes($botMoves) === 6) //jump
            {
                $possibleMoves = helper::GetPossiblePosition($botMoves, $opponentMoves, GameStatusEnum::Set);
                $botMovesCopy = $botMoves;
                $possibleMovesCopy = $possibleMoves;


                foreach($botMovesCopy as $botMove)
                {
                    $possibleMovesCopy = $possibleMoves;

                    array_push($possibleMovesCopy, $botMove);
                    unset($possibleMovesCopy[array_search($botMove, $possibleMoves)]);

                    foreach ($possibleMovesCopy as $move) {    
                        $maxEval += BotController::MinMaxSet($botMovesCopy, $opponentMoves, $depth - 1, false);
                    }
                }
            }
            return $maxEval;
        } else {
            $minEval = 0;

            if (count($opponentMoves) < 9) //set
            {
                $possibleMoves = helper::GetPossiblePosition($botMoves, $opponentMoves, GameStatusEnum::Set);

                foreach ($possibleMoves as $move) {

                    $botMovesCopy = $botMoves;

                    array_push($botMovesCopy, $move);

                    $minEval += BotController::MinMaxSet($botMovesCopy, $opponentMoves, $depth - 1, true);
                }
            } else if (helper::array_countMinusOnes($opponentMoves) < 6) //move
            {
                $possibleMoves = helper::GetPossiblePosition($botMoves, $opponentMoves, GameStatusEnum::Move);

                foreach($possibleMoves as $moveArray)
                {
                    $botMovesCopy = $botMoves;

                    unset($botMovesCopy[$moveArray->key]);

                    foreach($moveArray as $move)
                    {
                        array_push($botMovesCopy, $move);

                        $minEval += BotController::MinMaxSet($botMovesCopy, $opponentMoves, $depth - 1, true);
                    }
                }
            } else if (helper::array_countMinusOnes($opponentMoves) === 6) //jump
            {
                $possibleMoves = helper::GetPossiblePosition($botMoves, $opponentMoves, GameStatusEnum::Set);
                $botMovesCopy = $botMoves;
                $possibleMovesCopy = $possibleMoves;


                foreach($botMovesCopy as $botMove)
                {
                    $possibleMovesCopy = $possibleMoves;

                    array_push($possibleMovesCopy, $botMove);
                    unset($possibleMovesCopy[array_search($botMove, $possibleMoves)]);

                    foreach ($possibleMovesCopy as $move) {    
                        $minEval += BotController::MinMaxSet($botMovesCopy, $opponentMoves, $depth - 1, true);
                    }
                }
            }
            return $minEval;
        }
    }
}
