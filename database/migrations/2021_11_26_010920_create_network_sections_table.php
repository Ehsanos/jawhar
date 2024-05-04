<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNetworkSectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('network_sections', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('store_id')->unsigned()->nullable();
            $table->foreign('store_id')->references("id")->on("stores")->onDelete('cascade');
            $table->integer('user_id')->unsigned()->nullable();
            $table->foreign('user_id')->references("id")->on("users")->onDelete('cascade');
            $table->longText('top_point')->nullable();
            $table->longText('bottom_point')->nullable();
            $table->longText('right_point')->nullable();
            $table->longText('left_point')->nullable();
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
        Schema::dropIfExists('network_sections');
    }
}
