<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAirLinkDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('air_link_data', function (Blueprint $table) {
            $table->id();
            $table->string('app_url');
            $table->string('username');
            $table->string('password');
            $table->longText('token')->nullable();
            $table->longText('refresh_token')->nullable();
            $table->string('profile_id')->nullable();
            $table->string('group_id')->nullable();
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
        Schema::dropIfExists('air_link_data');
    }
}
