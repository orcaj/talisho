<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubmittalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('submittals', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('csi_id');
            $table->unsignedBigInteger('project_discipline_id');

            $table->foreign('csi_id')->references('id')->on('csis');
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
        Schema::dropIfExists('submittals');
    }
}
