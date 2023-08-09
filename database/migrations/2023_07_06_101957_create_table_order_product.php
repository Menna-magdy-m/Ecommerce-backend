<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableOrderProduct extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_product', function (Blueprint $table) {
            $table->id('ID');
            $table->unsignedBigInteger('order_id')->index();
            $table->unsignedBigInteger('product_id')->index();
            $table->foreign('order_id')->references('ID')->on('order');
            $table->foreign('product_id')->references('ID')->on('products');
            $table->unsignedBigInteger('product_meta_id');
            $table->string('product_name',200)->nullable();
            $table->unsignedBigInteger('customer_id')->nullable()->index();
            $table->integer('product_qty')->default(0);
            $table->double('product_net_revenue')->default(0);
            $table->double('product_gross_revenue')->default(0);
            $table->double('coupon_amount')->default(0);
            $table->double('tax_amount')->default(0);
            $table->double('shipping_amount')->default(0);
            $table->double('shipping_tax_amount')->default(0);
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
        Schema::dropIfExists('order_product');
    }
}
