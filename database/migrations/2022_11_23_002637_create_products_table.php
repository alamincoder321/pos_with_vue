<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string("product_code");
            $table->string("name");
            $table->integer("brand_id")->nullable();
            $table->integer("category_id")->nullable();
            $table->integer("unit_id");
            $table->integer("re_order");
            $table->decimal("purchase_price");
            $table->decimal("selling_price");
            $table->integer("product_type")->default(1);
            $table->string("description")->nullable();
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
        Schema::dropIfExists('products');
    }
}
