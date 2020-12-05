<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterDailyLogCommentsTableAddCommentByUserId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('daily_log_comments', function (Blueprint $table) {
            $table->unsignedBigInteger('comment_by_user_id');

            $table->foreign('comment_by_user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('daily_log_comments', function (Blueprint $table) {
            //
        });
    }
}
