<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCallHomeDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('call_home_data', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('call_home_id')->unsigned()->index();
            $table->string('realtime');
            $table->string('total_uptime');
            $table->string('session_uptime');
            $table->string('vpanel');
            $table->string('vout');
            $table->string('isns');
            $table->string('carrier');
            $table->string('lac');
            $table->string('ci');
            $table->string('rssi');
            $table->string('ber');
            $table->string('lat')->nullable();
            $table->string('lon')->nullable();
            $table->string('country')->nullable();
            $table->string('area')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('call_home_id')->references('id')->on('call_homes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('call_home_data');
    }
}
