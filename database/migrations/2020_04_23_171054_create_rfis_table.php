<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRfisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rfis', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('project_discipline_id');
            $table->unsignedBigInteger('guest_user_id');
            $table->string('subject');
            $table->timestamp('due_date');
            $table->text('question');
            $table->string('identifier');

            $table->foreign('project_discipline_id')->references('id')->on('project_disciplines');
            $table->foreign('guest_user_id')->references('id')->on('users');
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
        Schema::dropIfExists('rfis');
    }
}
