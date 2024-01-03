<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_order_items', function (Blueprint $table) {
            $table->id();
            $table->integer('order_id');
            $table->integer('item_id');
            $table->decimal('qty');
            $table->decimal('price');
            $table->decimal('receive')->nullable(true);
            $table->decimal('balance')->nullable(true);
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
        Schema::dropIfExists('vendor_order_items');
    }
}
