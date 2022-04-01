<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDevelopmentAssetStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('development_asset_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('alias')->nullable();
            $table->string('system_id')->nullable();
            $table->string('appsheet_id')->nullable();
            $table->string('asset_status')->nullable();
            $table->string('country')->nullable();
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
        Schema::dropIfExists('development_asset_statuses');
    }
}
