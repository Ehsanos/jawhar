<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddingDeleteAllInWelletProfits extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('wellet_profits', function (Blueprint $table) {
            $table->integer('delete_col')->nullable()->default("0")->comment("0=yes, 1=no");
            $table->integer('reset_wallet_id')->nullable();
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
