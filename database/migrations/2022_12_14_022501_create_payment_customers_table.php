<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_customers', function (Blueprint $table) {
            $table->id();
            $table->integer('id_customer');
            $table->date('date');
            $table->integer('id_term')->nullable(true);
            $table->string('reference', 50)->nullable(true);
            $table->string('card_number', 20)->nullable(true);
            $table->string('exp_date', 5)->nullable(true);
            $table->string('memo', 255)->nullable(true);
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
        Schema::dropIfExists('payment_customers');
    }
}
