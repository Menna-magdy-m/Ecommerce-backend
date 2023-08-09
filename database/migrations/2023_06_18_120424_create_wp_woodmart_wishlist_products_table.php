<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWpWoodmartWishlistProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wp_woodmart_wishlist_products', function (Blueprint $table) {
            $table->id('ID');
            $table->string('product_id')->index();
            $table->string('wishlist_id')->nullable();
            $table->timestamp('date_added')->default(\Illuminate\Support\Facades\DB::raw('CURRENT_TIMESTAMP'));
            $table->tinyInteger('on_sale');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wp_woodmart_wishlist_products');
    }
}
