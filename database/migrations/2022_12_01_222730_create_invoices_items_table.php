<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices_items', function (Blueprint $table) {
            $table->id();
            $table->integer('id_invoice');
            $table->integer('id_warehouse');
            $table->integer('id_item');
            $table->integer('id_size')->nullable(true);
            $table->integer('id_color')->nullable(true);
            $table->decimal('qty');
            $table->string('unit', 50)->nullable(true);
            $table->decimal('price');
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
        Schema::dropIfExists('invoices_items');
    }
}
