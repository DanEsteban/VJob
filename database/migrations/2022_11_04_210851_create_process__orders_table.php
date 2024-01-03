<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProcessOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('process__orders', function (Blueprint $table) {
            $table->id();
            $table->integer('id_order')->nullable(true);
            $table->integer('id_invoice')->nullable(true);
            $table->integer('id_process');
            $table->string('status', 20)->nullable(true);
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
        Schema::dropIfExists('process__orders');
    }
}
