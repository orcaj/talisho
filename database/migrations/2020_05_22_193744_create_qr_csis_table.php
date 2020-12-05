<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQrCsisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qr_csis', function (Blueprint $table) {
            $table->bigIncrements('id');
            
            $table->unsignedBigInteger('csi_id');
            $table->timestamps();

            $table->foreign('csi_id')->references('id')->on('csis');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('qr_csis');
    }
}
