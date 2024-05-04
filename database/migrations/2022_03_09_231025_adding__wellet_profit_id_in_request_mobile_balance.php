<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddingWelletProfitIdInRequestMobileBalance extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('request_mobile_balance', function (Blueprint $table) {
            $table->integer('wellet_profit_id')->nullable()->unsigned();
            $table->integer('network_packages_id')->nullable()->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('request_mobile_balance', function (Blueprint $table) {
            //
        });
    }
}
