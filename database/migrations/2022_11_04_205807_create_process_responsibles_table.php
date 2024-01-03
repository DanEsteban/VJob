<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProcessResponsiblesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('process_responsibles', function (Blueprint $table) {
            $table->id();
            $table->integer('id_user');
            $table->integer('id_process');
            $table->integer('id_phase');
            $table->integer('id_stage');
            $table->integer('rating');
            $table->string('status', 20);
            $table->string('review', 255);
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
        Schema::dropIfExists('process_responsibles');
    }
}
