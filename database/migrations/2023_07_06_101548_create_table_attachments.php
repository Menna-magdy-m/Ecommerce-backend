<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableAttachments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attachments', function (Blueprint $table) {
            $table->id('ID');
            $table->unsignedBigInteger('user_id')->index();
            $table->string('name', 200);
            $table->text('content')->nullable();
            $table->string('title', 20)->nullable();
            $table->integer('menu_order')->default('0');
            $table->integer('post_parent');
            $table->string('path', 200)->default('open');
            $table->string('parent_type', 20)->default('open');
            $table->string('post_mime_type', 20)->default('open');
            $table->string('status', 20)->default('publish');
            $table->foreign('user_id')->references('ID')->on('wp_users');
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
        Schema::dropIfExists('attachments');
    }
}
