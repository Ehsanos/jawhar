<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddingArNameInAshabGamesCards extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ashab_games_cards', function (Blueprint $table) {
            $table->text('card_name')->nullable();
            $table->integer('status_cart')->nullable()->default("0")->comment("0=No, 1=yes");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ashab_games_cards', function (Blueprint $table) {
            //
        });
    }
}
