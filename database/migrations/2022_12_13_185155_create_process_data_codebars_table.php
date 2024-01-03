<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProcessDataCodebarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('process_data_codebars', function (Blueprint $table) {
            $table->id();
            $table->integer('id_process');
            $table->integer('id_stage');
            $table->integer('id_invoice');
            $table->string('code', 20);
            $table->text('image');
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
        Schema::dropIfExists('process_data_codebars');
    }
}
