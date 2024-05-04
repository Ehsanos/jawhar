<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAshabLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ashab_log', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('user_id')->nullable();
            $table->text('page_id')->nullable();
            $table->text('order_id')->nullable();
            $table->text('game_id')->nullable();
            $table->text('denomination_id')->nullable();
            $table->text('price')->nullable();
            $table->text('qty')->nullable();
            $table->text('playerid')->nullable();
            $table->text('city_id')->nullable();
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
        Schema::dropIfExists('ashab_log');
    }
}
