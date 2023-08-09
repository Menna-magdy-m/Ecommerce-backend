<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWpWcOrderProductLookupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wp_wc_order_product_lookup', function (Blueprint $table) {
            $table->id('ID');
            $table->unsignedBigInteger('order_id')->index();
            $table->unsignedBigInteger('product_id')->index();
            $table->unsignedBigInteger('variation_id');
            $table->unsignedBigInteger('customer_id')->nullable()->index();
            $table->dateTime('date_created')->index()->default('0001-01-01 01:01:01');
            $table->integer('product_qty');
            $table->double('product_net_revenue')->default(0);
            $table->double('product_gross_revenue')->default(0);
            $table->double('coupon_amount')->default(0);
            $table->double('tax_amount')->default(0);
            $table->double('shipping_amount')->default(0);
            $table->double('shipping_tax_amount')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wp_wc_order_product_lookup');
    }
}
