<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShipToCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ship_to_customers', function (Blueprint $table) {
            $table->id();
            $table->integer('id_customer')->nullable(true);
            $table->string('name', 100);
            $table->string('address');
            $table->string('company')->nullable(true);
            $table->string('city', 100)->nullable(true);
            $table->string('postal', 20)->nullable(true);
            $table->string('state', 100)->nullable(true);
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
        Schema::dropIfExists('ship_to_customers');
    }
}
