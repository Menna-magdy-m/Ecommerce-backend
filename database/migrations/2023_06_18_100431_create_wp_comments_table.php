<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWpCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wp_comments', function (Blueprint $table) {
            $table->id('comment_ID');
            $table->unsignedBigInteger('comment_post_ID')->default(null)->index();
            $table->tinyText('comment_author')->default(null);
            $table->string('comment_author_email', 100)->index()->default('');
            $table->string('comment_author_url', 200)->default('');
            $table->string('comment_author_IP', 100)->default('');
            $table->dateTime('comment_date')->default('1000-01-01 01:01:01');
            $table->dateTime('comment_date_gmt')->default('1000-01-01 01:01:01')->index();
            $table->text('comment_content');
            $table->integer('comment_karma')->default(0);
            $table->string('comment_approved', 20)->default(1)->index();
            $table->string('comment_agent', 255)->default('');
            $table->string('comment_type', 20)->default('comment')->index();
            $table->unsignedBigInteger('comment_parent')->default(0)->index();
            $table->unsignedBigInteger('user_id')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wp_comments');
    }
}
