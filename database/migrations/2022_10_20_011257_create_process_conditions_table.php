<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProcessConditionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('process_conditions', function (Blueprint $table) {
            $table->id();
            $table->integer('id_stage');
            $table->string('question', 255)->nullable(true);
            $table->string('message_yes', 255)->nullable(true);
            $table->integer('action_yes')->nullable(true);
            $table->string('message_no', 255)->nullable(true);
            $table->integer('action_no')->nullable(true);
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
        Schema::dropIfExists('process_conditions');
    }
}
