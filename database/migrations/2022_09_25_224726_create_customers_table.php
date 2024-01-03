<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('company_name', 250);
            $table->string('first_name', 200)->nullable(true);
            $table->string('midle_name', 200)->nullable(true);
            $table->string('last_name', 200)->nullable(true);
            $table->string('phone', 100)->nullable(true);
            $table->string('work_phone', 100)->nullable(true);
            $table->string('email', 100)->nullable(true);
            $table->string('cc_email', 100)->nullable(true);
            $table->integer('id_terms')->nullable(true);
            $table->integer('id_delivery')->nullable(true);
            $table->string('billto_street');
            $table->string('billto_company')->nullable(true);
            $table->string('billto_city', 100)->nullable(true);
            $table->string('billto_postal', 20)->nullable(true);
            $table->string('billto_state', 100)->nullable(true);
            $table->decimal('balance')->nullable()->default(0);
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
        Schema::dropIfExists('customers');
    }
}
