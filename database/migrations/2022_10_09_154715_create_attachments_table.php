<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attachments', function (Blueprint $table) {
            $table->id();
            $table->integer('id_process')->nullable(true);
            $table->integer('id_phase')->nullable(true);
            $table->integer('id_stage')->nullable(true);
            $table->string('type', 100)->nullable(true);
            $table->string('file_name', 100);
            $table->string('file_location');
            $table->string('file_size', 50);
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
        Schema::dropIfExists('attachments');
    }
}
