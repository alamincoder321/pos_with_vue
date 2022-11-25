<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->string("date");
            $table->string("invoice");
            $table->foreignId("supplier_id")->constrained("suppliers", "id")->onDelete("cascade");
            $table->decimal("subtotal");
            $table->decimal("total");
            $table->decimal("paid");
            $table->decimal("due");
            $table->decimal("previous_due");
            $table->integer("vat");
            $table->decimal("vat_amount");
            $table->decimal("discount");
            $table->decimal("transport_cost");
            $table->string("payment_type", 50);
            $table->integer("account_id")->nullable();
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
        Schema::dropIfExists('purchases');
    }
}
