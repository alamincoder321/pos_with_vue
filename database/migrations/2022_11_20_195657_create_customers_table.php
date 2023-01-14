<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string("customer_code")->nullable();
            $table->string("name")->nullable();
            $table->string("owner_name")->nullable();
            $table->string("phone")->nullable();
            $table->integer("city_id")->nullable();
            $table->string("address")->nullable();
            $table->decimal("previous_due")->nullable();
            $table->string("customer_type")->nullable();
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
        Schema::dropIfExists('customers');
    }
}
