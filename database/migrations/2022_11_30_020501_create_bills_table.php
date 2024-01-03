<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bills', function (Blueprint $table) {
            $table->id();
            $table->string('number', 20);
            $table->integer('id_vendor');
            $table->date('date');
            $table->string('phone', 100)->nullable(true);
            $table->string('email', 255)->nullable(true);
            $table->integer('id_term')->nullable(true);
            $table->string('billto', 255)->nullable(true);
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
        Schema::dropIfExists('bills');
    }
}
