<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentationResponsesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documentation_responses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('status');
            $table->text('comment');
            $table->unsignedBigInteger('documentation_submission_id');

            $table->foreign('documentation_submission_id')->references('id')->on('documentation_submissions');

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
        Schema::dropIfExists('documentation_responses');
    }
}
