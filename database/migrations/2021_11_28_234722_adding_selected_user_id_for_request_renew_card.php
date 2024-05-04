<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddingSelectedUserIdForRequestRenewCard2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('request_renew_card', function (Blueprint $table) {
            $table->longText('latitude');
            $table->longText('longitude');
            $table->text('selected_user_id');
            $table->text('selected_user_reNewNetwork_percent');
            $table->text('network_user_reNewNetwork_percent');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
