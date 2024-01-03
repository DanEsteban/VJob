<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttachmentCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attachment_customers', function (Blueprint $table) {
            $table->id();
            $table->string('type_transaction', 50);
            $table->integer('id_transaction')->nullable(true);
            $table->integer('id_customer');
            $table->string('type', 100);
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
        Schema::dropIfExists('attachment_customers');
    }
}
