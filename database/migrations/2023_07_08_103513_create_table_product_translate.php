<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableProductTranslate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_translate', function (Blueprint $table) {
            $table->id('ID');
            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('ID')->on('products')->onDelete('cascade');
            $table->string('language', 10)->default('en');
            $table->string('title', 200)->nullable();
            $table->text('content')->nullable();
            $table->string('short_description', 200)->nullable();

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
        Schema::dropIfExists('product_translate');
    }
}
