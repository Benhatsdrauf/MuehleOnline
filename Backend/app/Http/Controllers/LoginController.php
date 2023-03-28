<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Models\User;

use App\Logic\Error;

class LoginController extends Controller
{
    public function login(LoginRequest $request)
    {
        $user = User::where("name", $request->name)->first();

        if($user->shadow()->first()->pw == hash("sha256", $request->pw))
        {
            $user->tokens()->delete();

            return response()->json(["token" => $user->createToken("access-token")->plainTextToken]);
        }
        else
        {
            Error::throw(["pw" => "Password is not correct."], 400);
        }
    }

    public function logout(Request $request)
    {
        $user = $request->user();

        $user->tokens()->delete();

        return response()->json();
    }
}
