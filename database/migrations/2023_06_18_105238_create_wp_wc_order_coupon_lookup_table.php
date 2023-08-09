<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWpWcOrderCouponLookupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wp_wc_order_coupon_lookup', function (Blueprint $table) {
            $table->id('order_id');
            $table->bigInteger('coupon_id')->unique();
            $table->dateTime('date_created')->index()->default("0001-01-01 01:01:01");
            $table->double('discount_amount')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wp_wc_order_coupon_lookup');
    }
}
