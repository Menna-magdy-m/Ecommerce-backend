<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWpWcProductMetaLookupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wp_wc_product_meta_lookup', function (Blueprint $table) {
            $table->id('product_id');
            $table->string('sku', 100)->nullable()->default('');
            $table->tinyInteger('virtual')->nullable()->index()->default(0);
            $table->tinyInteger('downloadable')->nullable()->index()->default(0);
            $table->decimal('min_price', 19, 4)->nullable()->index();
            $table->decimal('max_price', 19, 4)->nullable();
            $table->tinyInteger('onsale')->nullable()->index()->default(0);
            $table->double('stock_quantity')->nullable()->index();
            $table->string('stock_status', 100)->nullable()->index()->default('instock');
            $table->bigInteger('rating_count')->nullable()->default(0);
            $table->decimal('average_rating', 3, 2)->nullable()->default(0.00);
            $table->bigInteger('total_sales')->nullable()->default(0);
            $table->string('tax_status', 100)->nullable()->default('texable');
            $table->string('tax_class', 100)->nullable()->default('');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wp_wc_product_meta_lookup');
    }
}
