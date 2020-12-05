<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCsisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('csis', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('code');
            $table->string('name');
            $table->boolean('is_associated_document');
            $table->boolean('is_quick_selection');

            $table->unsignedBigInteger('csi_division_id');

            $table->foreign('csi_division_id')->references('id')->on('csi_divisions');

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
        Schema::dropIfExists('csis');
    }
}
