<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddingIsDollarInInstituteCourses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('institute_courses', function (Blueprint $table) {
            $table->integer('is_dollar')->nullable()->default("0")->comment("0=No, 1=yes");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('institute_courses', function (Blueprint $table) {
            //
        });
    }
}
