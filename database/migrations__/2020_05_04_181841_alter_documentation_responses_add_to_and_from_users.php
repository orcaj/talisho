<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterDocumentationResponsesAddToAndFromUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('documentation_responses', function (Blueprint $table) {
            $table->unsignedBigInteger('to_user_id')->nullable();
            $table->unsignedBigInteger('from_user_id')->nullable();

            $table->foreign('to_user_id')->references('id')->on('users');
            $table->foreign('from_user_id')->references('id')->on('users');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('documentation_responses', function (Blueprint $table) {
            //
        });
    }
}
