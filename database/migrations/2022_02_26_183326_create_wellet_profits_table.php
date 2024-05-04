<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWelletProfitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wellet_profits', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id')->nullable();
            $table->text('details')->nullable();
            $table->integer('type')->default("0")->comment("	0=in ,1=out");
            $table->double('profit')->nullable();
            $table->string('purchasing_price')->default("0");
            $table->double('worker_id')->nullable();
            $table->string('worker_profit')->default("0");
            $table->integer('city_id')->nullable()->unsigned();
            $table->integer('service_name')->nullable()->default("0")->comment("0=Publuic_serrvice, 1=Game ,2=Institute, 3=Mobile_packge, 4=Ashab, 5=Store, 6=Product_service, 7=Networks, 8=Renew_card");
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wellet_profits');
    }
}
