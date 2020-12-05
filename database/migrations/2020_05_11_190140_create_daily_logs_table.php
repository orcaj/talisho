<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDailyLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('daily_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('project_discipline_id');
            $table->unsignedBigInteger('reported_by_user_id');

            $table->date('log_date');
            $table->json('information');
            $table->string('identifier');

            $table->foreign('project_discipline_id')->references('id')->on('project_disciplines');
            $table->foreign('reported_by_user_id')->references('id')->on('users');

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
        Schema::dropIfExists('daily_logs');
    }
}
