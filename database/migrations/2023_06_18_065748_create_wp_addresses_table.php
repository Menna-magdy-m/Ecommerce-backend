<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWpAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wp_addresses', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('street1', 255);
            $table->string('street2', 255)->nullable();
            $table->string('country');
            $table->string('city');
            $table->string('phone');
            $table->string('zipcode')->nullable();
            $table->string('longitude')->nullable();
            $table->string('latitude')->nullable();
            $table->string('type')->nullable();
            $table->unsignedBigInteger('user_id')->index();

            $table->foreign('user_id')->references('ID')->on('wp_users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wp_addresses');
    }
}
