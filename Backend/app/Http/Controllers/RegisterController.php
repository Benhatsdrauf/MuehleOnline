<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;


use App\Models\User;
use App\Models\Shadow;
use App\Models\Statistic;

class RegisterController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $shadow = new Shadow;
        $shadow->pw = hash("sha256", $request->pw);
        $shadow->save();

        $stat = new Statistic;
        $stat->won = 0;
        $stat->lost = 0;
        $stat->moveCount = 0;
        $stat->kills = 0;
        $stat->deaths = 0;
        $stat->save();

        $user = new User;
        $user->name = $request->name;
        $user->elo = 1000;
        $user->shadow_id = $shadow->id; 
        $user->statistic_id = $stat->id;   
        $user->save();

        return response()->json(["token" => $user->createToken("access-token")->plainTextToken]);
    }
}
