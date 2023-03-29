<?php

namespace App\Http\Controllers;

use App\Models\User;

class StatisticController extends Controller
{
    public static function addWin(User $player)
    {
        $stat = $player->statistic()->first();

        $stat->won = $stat->won + 1;
        $stat->save();
    }

    public static function addLos(User $player)
    {
        $stat = $player->statistic()->first();

        $stat->lost = $stat->lost + 1;
        $stat->save();
    }

    public static function addMove(User $player)
    {
        $stat = $player->statistic()->first();

        $stat->moveCount = $stat->moveCount + 1;
        $stat->save();
    }

    public static function addKill(User $player)
    {
        $stat = $player->statistic()->first();

        $stat->kills = $stat->kills + 1;
        $stat->save();
    }

    public static function addDeath(User $player)
    {
        $stat = $player->statistic()->first();

        $stat->deaths = $stat->deaths + 1;
        $stat->save();
    }
}
