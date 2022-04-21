<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAirtelResponsesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('airtel_responses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('airtel_pushes_id')->unsigned()->index();
            $table->string('trans_id');
            $table->string('message');
            $table->string('status_code');
            $table->string('result_code');
            $table->string('response_code');
            $table->boolean('success');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('airtel_pushes_id')->references('id')->on('airtel_pushes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('airtel_responses');
    }
}
