<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddingCurrencyInNetworksCardsRequest extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('networks_cards_request', function (Blueprint $table) {
            $table->string('currency')->nullable()->default("turkey");
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('networks_cards_request', function (Blueprint $table) {
            //
        });
    }
}
