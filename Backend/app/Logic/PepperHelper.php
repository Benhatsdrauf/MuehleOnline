<?php

namespace App\Logic;


use App\Models\Game;
use App\Models\User;
use App\Models\UserToGame;
use Laravel\Sanctum\PersonalAccessToken;
use App\Http\Controllers\StatisticController as Stat;
use App\Http\Controllers\DeletionTokenController as deletion;
use App\Logic\DatabaseHelper as dbHelper;
use Carbon\Carbon;
use App\Http\Controllers\UserController;
use App\Events\GameOverEvent;

class PepperHelper
{
    public static function Get()
    {
        return file_get_contents(storage_path("Crypt/Crypt.txt"));
    }
}
