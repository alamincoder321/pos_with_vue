<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalaryGeneratesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('salary_generates', function (Blueprint $table) {
            $table->id();
            $table->integer("employee_id");
            $table->string("month", 50);
            $table->string("date")->nullable();
            $table->decimal("salary");
            $table->decimal("due");
            $table->decimal("overTimeBonus")->nullable();
            $table->decimal("leaveDeduction")->nullable();
            $table->decimal("advance")->nullable();
            $table->text("description")->nullable();
            $table->char("status", 5)->default("p")->comment("p=Pending, d=Due, a=Payment Complete");
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
        Schema::dropIfExists('salary_generates');
    }
}
