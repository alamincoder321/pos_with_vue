<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employers', function (Blueprint $table) {
            $table->id();
            $table->string("employer_code");
            $table->string("name");
            $table->string("phone");
            $table->string("designation");
            $table->integer("department_id");
            $table->string("mother_name");
            $table->string("father_name");
            $table->string("dob");
            $table->string("join_date");
            $table->integer("city_id");
            $table->string("address");
            $table->decimal("salary");
            $table->decimal("dailySalary");
            $table->string("image")->nullable();
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
        Schema::dropIfExists('employers');
    }
}
