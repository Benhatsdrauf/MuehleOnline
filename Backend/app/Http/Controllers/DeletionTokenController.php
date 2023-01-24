<?php

namespace App\Http\Controllers;

use App\Models\UserToGame;
use App\Models\DeletionToken;
use Illuminate\Http\Request;

class DeletionTokenController extends Controller
{
    public static function createToken(UserToGame $utg)
    {
        if($utg->deletion_tokens()->exists())
        {
            $utg->deletion_tokens()->delete();
        }

        $deletion = new DeletionToken();
        $deletion->token = bin2hex(openssl_random_pseudo_bytes(16));
        $deletion->utg = $utg->id;
        $deletion->save();

        return $deletion->token;
    }

    public static function isTokenCorrect(UserToGame $utg, $token)
    {
        $deletion = $utg->deletion_tokens()->first();

        if($deletion->exists())
        {
            return $deletion->token === $token;
        }

        return false;
    }

    public static function clearTokens(UserToGame $utg)
    {
        $utg->deletion_tokens()->delete();
    }
}
