<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCellIdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cell_ids', function (Blueprint $table) {
            $table->id();
            $table->string('radio');
            $table->string('mcc');
            $table->string('net');
            $table->string('area');
            $table->bigInteger('cell')->unique();
            $table->string('unit')->nullable();
            $table->string('lon');
            $table->string('lat');
            $table->string('range');
            $table->string('samples');
            $table->string('changeable');
            $table->string('created');
            $table->string('updated');
            $table->string('average_signal')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cell_ids');
    }
}
