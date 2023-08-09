<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWpWcProductAttributesLookupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wp_wc_product_attributes_lookup', function (Blueprint $table) {
            $table->bigInteger('product_id')->default(null);
            $table->bigInteger('product_or_parent_id')->default(null);
            $table->string('taxonomy', 32)->default(null);
            $table->bigInteger('term_id')->default(null);
            $table->tinyInteger('is_variation_attribute')->index()->default(null);
            $table->tinyInteger('in_stock')->default(null);

            $table->primary(['product_id', 'product_or_parent_id', 'taxonomy', 'term_id'], 'primary_key');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wp_wc_product_attributes_lookup');
    }
}
