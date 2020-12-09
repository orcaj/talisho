<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrganizationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organizations', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('name');
            $table->string('address_1');
            $table->string('address_2')->nullable();
            $table->string('city');
            $table->string('state');
            $table->string('country');
            $table->string('phone');
            $table->string('website')->nullable();
            $table->string('account_type');
            $table->string('account_status');

            $table->unsignedBigInteger('discipline_id');
            $table->unsignedBigInteger('employee_count_id');

            $table->foreign('discipline_id')->references('id')->on('disciplines');
            $table->foreign('employee_count_id')->references('id')->on('employee_counts');

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
        Schema::dropIfExists('organizations');
    }
}
