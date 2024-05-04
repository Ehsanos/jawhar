<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAshabGamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ashab_games', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('game_id')->unique();
            $table->integer('game_status')->nullable()->default("0")->comment("0=yes, 1=No");
            $table->integer('game_tap')->nullable();
            $table->text('game_num')->nullable();
            $table->text('game_text')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ashab_games');
    }
}
