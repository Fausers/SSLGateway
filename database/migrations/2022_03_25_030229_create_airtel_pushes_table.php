<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAirtelPushesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('airtel_pushes', function (Blueprint $table) {
            $table->id();
            $table->string('reference');
            $table->integer('amount');
            $table->string('country');
            $table->string('currency');
            $table->string('msisnd');
            $table->string('ssl_id');
            $table->string('message')->nullable();
            $table->string('status_code')->nullable();
            $table->string('airtel_money_id')->nullable();
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
        Schema::dropIfExists('airtel_pushes');
    }
}
