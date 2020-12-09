<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDailyLogCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('daily_log_comments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('daily_log_id');
            $table->text('comment');

            $table->foreign('daily_log_id')->references('id')->on('daily_logs');

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
        Schema::dropIfExists('daily_log_comments');
    }
}
