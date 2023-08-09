<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableCategoryTranslate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('category_translate', function (Blueprint $table) {
            $table->id('ID');
            $table->unsignedBigInteger('category_id');
            $table->foreign('category_id')->references('ID')->on('category')->onDelete('cascade');
            $table->string('language', 10)->default('en');
            $table->string('name', 200)->nullable();
            $table->text('description')->nullable();
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
        Schema::dropIfExists('category_translate');
    }
}
