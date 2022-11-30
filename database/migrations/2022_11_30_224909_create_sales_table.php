<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId("customer_id")->constrained("customers", "id")->onDelete("cascade");
            $table->string("date");
            $table->string("invoice");
            $table->decimal("subtotal");
            $table->decimal("total");
            $table->decimal("paid");
            $table->decimal("due");
            $table->decimal("previous_due");
            $table->integer("vat");
            $table->decimal("vat_amount");
            $table->integer("discount");
            $table->decimal("discount_amount");
            $table->decimal("transport_cost");
            $table->string("payment_type", 50);
            $table->integer("account_id")->nullable();
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
        Schema::dropIfExists('sales');
    }
}
