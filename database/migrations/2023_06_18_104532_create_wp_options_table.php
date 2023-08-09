<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWpOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wp_options', function (Blueprint $table) {
            $table->id('option_id');
            $table->string('option_name',191 )->nullable()->unique();
            $table->longText('option_value')->nullable();
            $table->string('autoload', 20)->index()->default('yes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wp_options');
    }
}
