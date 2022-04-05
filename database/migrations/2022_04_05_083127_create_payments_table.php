<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('service_id');
            $table->string('trans_id');
            $table->string('amount');
            $table->string('payment_status');
            $table->string('reference_no');
            $table->string('payment_receipt');
            $table->string('msnid');
            $table->dateTime('trans_date')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->string('opco')->default("TZ");
            $table->string('api_username')->nullable();
            $table->string('api_password')->nullable();
            $table->bigInteger('trans_timestamp')->default('0')->nullable();
            $table->string('payment_status_desc');
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
        Schema::dropIfExists('payments');
    }
}
