<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Logic\DatabaseHelper as helper;
use App\Logic\GameEndReason;
use Carbon\Carbon;
use App\Models\Game;


class endGameCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'endGame';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $activeGames = Game::all()->where("is_active", true)->where("time_to_move", "<", Carbon::now());
            
            foreach($activeGames as $game)
            {
                $white = $game->user_to_game()->where("is_white", true)->first()->user()->first();
                $black = $game->user_to_game()->where("is_white", false)->first()->user()->first();

                $whites_turn = $game->whites_turn;

                $winner = "";
                $loser = "";

                if($whites_turn)
                {
                    $winner = $black;
                    $loser = $white;
                }
                else
                {
                    $winner = $white;
                    $loser = $black;
                }

                info("Game ended: $game->id");
                helper::GameEnded($game, $winner, $loser, GameEndReason::INACTIVE);
            }
    }
}
