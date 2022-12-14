<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuotationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quotations', function (Blueprint $table) {
            $table->id();
            $table->string("customer_name");
            $table->string("customer_phone");
            $table->string("customer_address");
            $table->string("date");
            $table->string("invoice");
            $table->decimal("subtotal");
            $table->decimal("total");
            $table->integer("vat");
            $table->decimal("vat_amount");
            $table->integer("discount");
            $table->decimal("discount_amount");
            $table->decimal("transport_cost");
            $table->text("note")->nullable();
            $table->integer("added_by"); 
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
        Schema::dropIfExists('quotations');
    }
}
