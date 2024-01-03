<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProcessDataPhasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('process_data_phases', function (Blueprint $table) {
            $table->id();
            $table->integer("id_data");
            $table->string("name");
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
        Schema::dropIfExists('process_data_phases');
    }
}
