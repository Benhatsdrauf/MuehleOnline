<?php

namespace App\Console;

use App\Models\Game;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Logic\DatabaseHelper as helper;

use Carbon\Carbon;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            $timedOutGames = Game::all()->where("is_active", true)->where("time_to_move", "<", Carbon::now());
            
            foreach($timedOutGames as $game)
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

                helper::EndGame($game, $winner, $loser);
            }

        })->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
