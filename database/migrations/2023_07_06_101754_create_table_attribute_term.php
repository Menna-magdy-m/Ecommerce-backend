<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableAttriputeTerm extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attribute_term', function (Blueprint $table) {
            $table->id('ID');
            $table->integer('attribute_id');
            $table->foreign('attribute_id')->references('ID')->on('attributes');
            $table->string('name')->index();
            $table->integer('menu_order')->default(0)->index();
            $table->string('status')->default('publish');
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
        Schema::dropIfExists('attribute_term');
    }
}
