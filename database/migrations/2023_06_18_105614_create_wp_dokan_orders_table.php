<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWpDokanOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wp_dokan_orders', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('order_id')->nullable()->index();
            $table->bigInteger('seller_id')->nullable()->index();
            $table->decimal('order_total', 19, 4)->nullable();
            $table->decimal('net_amount', 19, 4)->nullable();
            $table->string('order_status', 30)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wp_dokan_orders');
    }
}
