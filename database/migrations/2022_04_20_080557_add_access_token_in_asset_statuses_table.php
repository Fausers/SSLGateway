<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAccessTokenInAssetStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('asset_statuses', function (Blueprint $table) {
            $table->string('box_id')->after('asset_id')->nullable();
            $table->string('country')->after('power')->nullable();
            $table->string('access_token')->after('country')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('asset_statuses', function (Blueprint $table) {
            $table->dropColumn('box_id');
            $table->dropColumn('country');
            $table->dropColumn('access_token');
        });
    }
}
