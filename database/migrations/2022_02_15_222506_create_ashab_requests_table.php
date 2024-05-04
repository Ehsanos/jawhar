<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAshabRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ashab_requests', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->Text('user_id')->nullable();
            $table->Text('order_id')->nullable();
            $table->Text('price')->nullable();
            $table->Text('status_ashab')->nullable();
            $table->Text('replay')->nullable();
            $table->Integer('status')->nullable()->default("0")->comment("0=yes, 1=no");;
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
        Schema::dropIfExists('ashab_requests');
    }
}
