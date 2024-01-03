<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoriesCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventories_customers', function (Blueprint $table) {
            $table->id();
            $table->string('type_transaction', 50);
            $table->integer('id_transaction');
            $table->integer('id_customer');
            $table->integer('id_product');
            $table->decimal('qty');
            $table->integer('id_size')->nullable(true);
            $table->integer('id_color')->nullable(true);
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
        Schema::dropIfExists('inventories_customers');
    }
}
