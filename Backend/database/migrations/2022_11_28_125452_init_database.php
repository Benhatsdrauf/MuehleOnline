<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("shadow", function (Blueprint $table) {
            $table->id();
            $table->string("pw");
        });
    
        Schema::create("statistic", function (Blueprint $table) {
            $table->id();
            $table->integer("won");
            $table->integer("lost");
            $table->integer("moveCount");
            $table->integer("kills");
            $table->integer("deaths");
        });

        Schema::create('user', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->rememberToken();
            $table->unsignedBigInteger("shadow_id");
            $table->unsignedBigInteger("statistic_id");
            $table->foreign("shadow_id")->references("id")->on("shadow");
            $table->foreign("statistic_id")->references("id")->on("statistic");
        });

        Schema::create("move", function (Blueprint $table) {
            $table->id();
            $table->string("row");
            $table->string("column");
        });

        Schema::create("game", function (Blueprint $table) {
            $table->id();
        });

        Schema::create("game_to_move", function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("game_id");
            $table->unsignedBigInteger("move_id");
            $table->foreign("game_id")->references("id")->on("game");
            $table->foreign("move_id")->references("id")->on("move");
        });

        Schema::create("user_to_game_to_move", function (Blueprint $table) {
            $table->id();
            $table->boolean("is_white");
            $table->unsignedBigInteger("user_id");
            $table->unsignedBigInteger("game_to_move_id");
            $table->foreign("user_id")->references("id")->on("user");
            $table->foreign("game_to_move_id")->references("id")->on("game_to_move");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists("shadow");
        Schema::dropIfExists("statistic");
        Schema::dropIfExists("user");
        Schema::dropIfExists("game");
        Schema::dropIfExists("move");
        Schema::dropIfExists("userToGameToMove");
        Schema::dropIfExists("gameToMove");
    }
};
