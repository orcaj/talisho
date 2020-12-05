<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConstructionDirectiveGuestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('construction_directive_guest', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('construction_directive_id');
            $table->unsignedBigInteger('guest_user_id');

            $table->foreign('construction_directive_id')->references('id')->on('construction_directives');
            $table->foreign('guest_user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('construction_directive_guest');
    }
}
