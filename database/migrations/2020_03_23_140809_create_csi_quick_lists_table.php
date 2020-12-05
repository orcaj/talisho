<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCsiQuickListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('csi_quick_lists', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('csi_id');
            $table->string('quick_list');

            $table->foreign('csi_id')->references('id')->on('csis');

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
        Schema::dropIfExists('csi_quick_lists');
    }
}
