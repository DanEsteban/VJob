<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpendituresItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expenditures_items', function (Blueprint $table) {
            $table->id(); 
            $table->integer('id_expenditure');
            $table->integer('id_item');
            $table->integer('id_size')->nullable(true);
            $table->integer('id_color')->nullable(true);
            $table->decimal('qty');
            $table->string('unit', 50)->nullable(true);
            $table->decimal('cost');
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
        Schema::dropIfExists('expenditures_items');
    }
}
