<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableOrder extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order', function (Blueprint $table) {
            $table->id('ID');
            $table->unsignedBigInteger('user_id')->index();
            $table->unsignedBigInteger('site_id')->index();
            $table->unsignedBigInteger('customer_id')->index();
            $table->integer('num_items_sold')->default(0);
            $table->double('total_sales')->default(0);
            $table->double('tax_total')->default(0);
            $table->double('shipping_total')->default(0);
            $table->double('net_total')->default(0);
            $table->integer('returning_customer')->default(0);
            $table->string('payment_method','200')->default('')->index();
            $table->string('status')->default('processing');
            $table->string('comment_status')->default('open');
            $table->unsignedBigInteger('address_id')->index();


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
        Schema::dropIfExists('order');
    }
}
