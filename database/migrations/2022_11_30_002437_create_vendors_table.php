<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->string('name', 200);
            $table->string('contact', 100)->nullable(true);
            $table->string('phone', 15)->nullable(true);
            $table->string('email', 100)->nullable(true);
            $table->string('billto_street', 255)->nullable(true);
            $table->string('billto_company', 255)->nullable(true);
            $table->string('billto_city', 100)->nullable(true);
            $table->string('billto_postal', 20)->nullable(true);
            $table->string('billto_state', 100)->nullable(true);
            $table->decimal('balance')->nullable(true);
            $table->boolean('is_active')->nullable(true);
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
        Schema::dropIfExists('vendors');
    }
}
