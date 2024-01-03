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
            $table->integer('id_type');
            $table->integer('id_group')->nullable(true);
            $table->string('item_name', 100);
            $table->string('part_number', 100)->nullable(true);
            $table->integer('id_unit_measure')->nullable(true);           
            $table->string('purchase_description')->nullable(true);
            $table->string('sales_description')->nullable(true);
            $table->decimal('cost')->nullable(true);
            $table->decimal('price')->nullable(true);
            $table->decimal('max_order')->nullable(true);
            $table->decimal('min_order')->nullable(true);
            $table->string('notes')->nullable(true);
            $table->integer('id_process')->nullable(true);
            $table->boolean('is_active')->default(1);
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
