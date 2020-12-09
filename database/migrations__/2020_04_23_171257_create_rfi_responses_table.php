<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRfiResponsesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rfi_responses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('rfi_id');
            $table->text('response');

            $table->foreign('rfi_id')->references('id')->on('rfis');
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
        Schema::dropIfExists('rfi_responses');
    }
}
