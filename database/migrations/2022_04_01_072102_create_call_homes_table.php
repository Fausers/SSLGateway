<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCallHomesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('call_homes', function (Blueprint $table) {
            $table->id();
            $table->string('ip');
            $table->string('status');
            $table->string('version');
            $table->string('power');
            $table->string('comm_type');
            $table->string('command')->default('SIMUSOLAR ON');
            $table->string('device_id')->nullable();
            $table->longText('data')->nullable();
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
        Schema::dropIfExists('call_homes');
    }
}
