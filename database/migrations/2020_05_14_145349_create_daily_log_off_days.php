<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDailyLogOffDays extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('daily_log_off_days', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('project_discipline_id');
            $table->date('off_date');

            $table->foreign('project_discipline_id')->references('id')->on('project_disciplines');
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
        Schema::dropIfExists('daily_log_off_days');
    }
}
