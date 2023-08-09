<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWpWcOrderStatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wp_wc_order_stats', function (Blueprint $table) {
            $table->id('order_id');
            $table->unsignedBigInteger('parent_id')->default(0);
            $table->dateTime('date_created')->index()->default('0001-01-01 01:01:01');
            $table->dateTime('date_created_gmt')->default('0001-01-01 01:01:01');
            $table->integer('num_items_sold')->default(0);
            $table->double('total_sales')->default(0);
            $table->double('tax_total')->default(0);
            $table->double('shipping_total')->default(0);
            $table->double('net_total')->default(0);
            $table->tinyInteger('returning_customer')->nullable();
            $table->string('status', 200)->index();
            $table->unsignedBigInteger('customer_id')->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wp_wc_order_stats');
    }
}
