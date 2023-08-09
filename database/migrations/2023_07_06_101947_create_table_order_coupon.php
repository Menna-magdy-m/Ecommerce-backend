<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableOrderCoupon extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_coupon', function (Blueprint $table) {
            $table->id('ID');
            $table->unsignedBigInteger('order_id');
            $table->foreign('order_id')->references('ID')->on('order');
            $table->bigInteger('coupon_id')->unique();
            $table->unique(['order_id' , 'coupon_id']);
            $table->double('discount_amount')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_coupon');
    }
}
