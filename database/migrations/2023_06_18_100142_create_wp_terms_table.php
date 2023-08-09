<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWpTermsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wp_terms', function (Blueprint $table) {
            $table->id('term_id');
            $table->string('name', 200)->index()->default('');
            $table->string('slug', 200)->index()->default('');
            $table->bigInteger('term_group')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wp_terms');
    }
}
