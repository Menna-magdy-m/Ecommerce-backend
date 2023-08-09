<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id('ID');
            $table->unsignedBigInteger('user_id')->index();
            $table->unsignedBigInteger('site_id')->index();
            $table->text('content')->nullable();
            $table->string('title',200)->index();
            $table->string('post_excerpt')->nullable()->index();
            $table->string('name',200)->index();
            $table->string('post_content_filtered')->nullable()->index();
            $table->integer('menu_order')->default(0);
            $table->string('status')->default('publish');
            $table->string('comment_status')->default('open');
            $table->integer('comment_count')->default(0);
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
            $table->unsignedBigInteger('photo_id')->nullable();
            $table->integer('min_order_quantity')->nullable();
            $table->integer('max_order_quantity')->nullable();
            $table->double('weight')->default(0);
            $table->string('keywords',200)->nullable();


            $table->timestamps();
            $table->softDeletes($column = 'deleted_at', $precision = 0);
        });
    }








    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
