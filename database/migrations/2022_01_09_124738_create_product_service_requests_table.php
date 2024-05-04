<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductServiceRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_service_requests', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('city_id');
            $table->foreign('city_id')->references("id")->on("cities")->onDelete('cascade');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references("id")->on("users")->onDelete('cascade');
            $table->bigInteger('whatsapp_id')->unsigned();
            $table->foreign('whatsapp_id')->references("id")->on("whatsapp_users")->onDelete('cascade');
            $table->Integer('product_service_id');
            $table->foreign('product_service_id')->references("id")->on("product_service")->onDelete('cascade');
            $table->integer('status')->nullable()->default("0")->comment("0=pending,1=ok,2=canceled");
            $table->text('number')->nullable();
            $table->text('j_price')->nullable();
            $table->text('user_price')->nullable();
            $table->text('wapp_owner_price')->nullable();
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
        Schema::dropIfExists('product_service_requests');
    }
}
