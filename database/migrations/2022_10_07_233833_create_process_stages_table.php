<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProcessStagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('process_stages', function (Blueprint $table) {
            $table->id();
            $table->string('description', 200);
            $table->boolean('has_responsible')->nullable(true);
            $table->integer('id_responsible')->nullable(true);
            $table->integer('id_phase');
            $table->boolean('has_inventory_received')->nullable(true);
            $table->boolean('has_condition')->nullable(true);
            $table->boolean('has_date')->nullable(true);
            $table->boolean('has_attachment')->nullable(true);
            $table->boolean('has_attachment_customer')->nullable(true);
            $table->boolean('has_instructions')->nullable(true);
            $table->boolean('has_comparison')->nullable(true);
            $table->boolean('has_send_mail')->nullable(true);
            $table->boolean('has_code_label')->nullable(true);
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
        Schema::dropIfExists('process_stages');
    }
}
