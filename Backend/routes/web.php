<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StoneController;

use App\Models\User;
use App\Logic\StoneHelper as helper;

use App\Events\PlayerReady;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get("/test", function () {

    $user = User::find(1);

    $game = $user->games()->where("is_active", true)->first();

    return response()->json(helper::UserHasMill($game, $user, 1));
});

Route::prefix("auth")->group(function() {
    Route::post("/register",[RegisterController::class, "register"]);
    Route::post("/login",[LoginController::class, "login"]);
    Route::post("/logout", [LoginController::class, "logout"])->middleware("auth:sanctum");
});

Route::prefix("game")->group(function() {
    Route::post("/create", [GameController::class, "create"])->middleware("auth:sanctum");
    Route::get("/join/{guid}", [GameController::class, "join"])->middleware("auth:sanctum");
    Route::get("/quit", [GameController::class, "quit"])->middleware("auth:sanctum");
    Route::get("/data", [GameController::class, "getCurrentState"])->middleware("auth:sanctum");
    Route::prefix("/stone")->group(function() {
        Route::get("/set/{position}", [StoneController::class, "set"])->middleware("auth:sanctum");
        Route::get("/delete/{position}", [StoneController::class, "delete"])->middleware("auth:sanctum");
    });

});

Route::prefix("user")->group(function() {
    Route::get("/info", [UserController::class, "getInfo"])->middleware("auth:sanctum");
});
