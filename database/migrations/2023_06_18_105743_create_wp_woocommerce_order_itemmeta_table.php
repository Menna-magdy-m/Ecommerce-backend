<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWpWoocommerceOrderItemmetaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wp_woocommerce_order_itemmeta', function (Blueprint $table) {
            $table->id('meta_id');
            $table->unsignedBigInteger('order_item_id')->index();
            $table->string('meta_key', 20)->nullable()->index();
            $table->longText('meta_value')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wp_woocommerce_order_itemmeta');
    }
}
