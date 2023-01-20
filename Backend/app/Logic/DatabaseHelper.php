<?php

namespace App\Logic;

use Laravel\Sanctum\PersonalAccessToken;

class DatabaseHelper
{
    public static function getHashedToken($user)
    {
        return PersonalAccessToken::where("tokenable_id", $user->id)->first()->token;
    }
}
