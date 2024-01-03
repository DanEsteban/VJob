<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProcessDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('process_data', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->integer("id_customer");
            $table->boolean("has_responsible");
            $table->integer("id_responsible")->nullable(true);
            $table->string("status")->nullable(true);
            $table->integer("percentage")->nullable(true);
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
        Schema::dropIfExists('process_data');
    }
}
