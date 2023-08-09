<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWpPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wp_posts', function (Blueprint $table) {
            $table->id('ID');
            $table->unsignedBigInteger('post_author')->index();
            $table->dateTime('post_date')->default('1000-01-01 01:01:01');
            $table->dateTime('post_date_gmt')->default('1000-01-01 01:01:01');
            $table->longText('post_content');
            $table->text('post_title');
            $table->text('post_excerpt');
            $table->string('post_status', 20)->default('publish');
            $table->string('comment_status', 20)->default('open');
            $table->string('ping_status', 20)->default('open');
            $table->string('post_password', 255)->default('');
            $table->string('post_name', 200)->index()->default('');
            $table->text('to_ping');
            $table->text('pinged');
            $table->dateTime('post_modified')->default('1000-01-01 01:01:01');
            $table->dateTime('post_modified_gmt')->default('1000-01-01 01:01:01');
            $table->longText('post_content_filtered');
            $table->unsignedBigInteger('post_parent')->index()->default(0);
            $table->string('guid', 255)->default('');
            $table->integer('menu_order')->default(0);
            $table->string('post_type', 20)->index()->default('post');
            $table->string('post_mime_type', 100)->default('');
            $table->bigInteger('comment_count')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wp_posts');
    }
}
