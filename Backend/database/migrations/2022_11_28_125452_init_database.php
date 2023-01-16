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
            $table->integer("elo");
            $table->rememberToken();
            $table->unsignedBigInteger("shadow_id");
            $table->unsignedBigInteger("statistic_id");
            $table->foreign("shadow_id")->references("id")->on("shadow")->onDelete("cascade");
            $table->foreign("statistic_id")->references("id")->on("statistic")->onDelete("cascade");
        });

        Schema::create("game", function (Blueprint $table) {
            $table->id();
            $table->string("invite_id");
            $table->boolean("is_active");
            $table->dateTime("end_time")->nullable();
            $table->timestamps();
        });

        Schema::create("move", function (Blueprint $table) {
            $table->id();
            $table->string("row");
            $table->string("column");
            $table->unsignedBigInteger("game_id");
            $table->foreign("game_id")->references("id")->on("game")->onDelete("cascade");
        });

        Schema::create("user_to_game", function (Blueprint $table) {
            $table->id();
            $table->boolean("is_white");
            $table->boolean("won");
            $table->integer("elo");
            $table->unsignedBigInteger("user_id");
            $table->unsignedBigInteger("game_id");
            $table->foreign("user_id")->references("id")->on("user")->onDelete("cascade");
            $table->foreign("game_id")->references("id")->on("game")->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists("user_to_game");
        Schema::dropIfExists("move");
        Schema::dropIfExists("game");
        Schema::dropIfExists("user");
        Schema::dropIfExists("shadow");
        Schema::dropIfExists("statistic");
    }
};
