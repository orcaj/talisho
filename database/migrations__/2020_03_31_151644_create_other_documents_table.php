<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOtherDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('other_documents', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('csi_id');
            $table->unsignedBigInteger('submittal_id')->nullable();
            $table->unsignedBigInteger('project_discipline_id');

            $table->foreign('csi_id')->references('id')->on('csis');
            $table->foreign('submittal_id')->references('id')->on('submittals');
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
        Schema::dropIfExists('other_documents');
    }
}
