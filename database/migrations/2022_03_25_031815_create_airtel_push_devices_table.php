<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAirtelPushDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('airtel_push_devices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('push_id')->unsigned()->index();
            $table->string('device_id');
            $table->string('amount');
            $table->string('refrence')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('push_id')->references('id')->on('airtel_pushes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('airtel_push_devices');
    }
}
