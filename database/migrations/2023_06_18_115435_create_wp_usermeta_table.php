<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWpUsermetaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wp_usermeta', function (Blueprint $table) {
            $table->id('umeta_id');
            $table->unsignedBigInteger('user_id')->index()->default(0);
            $table->string('meta_key', 20)->nullable()->index();
            $table->longText('meta_value')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wp_usermeta');
    }
}
