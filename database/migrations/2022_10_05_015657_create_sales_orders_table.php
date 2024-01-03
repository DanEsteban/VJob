<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_orders', function (Blueprint $table) {
            $table->id();
            $table->string('number', 20);
            $table->integer('id_customer');
            $table->date('date');
            $table->string('phone', 100)->nullable(true);
            $table->string('email', 255)->nullable(true);
            $table->integer('id_term')->nullable(true);
            $table->string('billto', 255)->nullable(true);
            $table->integer('id_shipto')->nullable(true);
            $table->integer('id_warehouse')->nullable(true);
            $table->decimal('porcentage')->nullable(true);
            $table->decimal('taxes');
            $table->decimal('total');
            $table->string('status', 100)->default('Pending');
            $table->boolean('active')->default(1);
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
        Schema::dropIfExists('sales_orders');
    }
}
