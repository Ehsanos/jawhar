<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAshabGamesCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ashab_games_cards', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('ashab_game_id');
            $table->foreign('ashab_game_id')->references("game_id")->on("ashab_games")->onDelete('cascade');
            $table->text('denomination_id')->nullable();
            $table->text('price')->nullable();
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
        Schema::dropIfExists('ashab_games_cards');
    }
}
