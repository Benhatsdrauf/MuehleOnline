<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Models\User;

use App\Logic\Error;
use App\Logic\PepperHelper;

class LoginController extends Controller
{
    public function login(LoginRequest $request)
    {
        $user = User::where("name", $request->name)->first();

        $salt = $user->shadow()->first()->salt;
        $pepper = PepperHelper::Get();


        if(password_verify("$pepper:$request->pw:$salt", $user->shadow()->first()->pw ))
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
