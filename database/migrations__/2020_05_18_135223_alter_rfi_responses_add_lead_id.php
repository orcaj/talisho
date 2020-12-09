<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterRfiResponsesAddLeadId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rfi_responses', function (Blueprint $table) {
            $table->unsignedBigInteger('from_lead_user_id')->nullable();

            $table->foreign('from_lead_user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rfi_responses', function (Blueprint $table) {
            //
        });
    }
}
