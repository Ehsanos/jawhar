<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddingStatusWelletInWelletProfits extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('wellet_profits', function (Blueprint $table) {
            $table->integer('status_wellet')->nullable()->default("0")->comment("0=yes, 1=no");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('wellet_profits', function (Blueprint $table) {
            //
        });
    }
}
