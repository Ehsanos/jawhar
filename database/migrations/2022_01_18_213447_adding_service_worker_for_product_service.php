<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddingServiceWorkerForProductService extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_service', function (Blueprint $table) {
            $table->integer('service_worker_status')->nullable()->default("0")->comment("0=without_service_worker,1=service_worker");
            $table->integer('wapp_id_service_worker')->nullable()->unsigned();
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
