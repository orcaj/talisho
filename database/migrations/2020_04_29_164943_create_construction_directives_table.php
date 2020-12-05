<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConstructionDirectivesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('construction_directives', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('project_discipline_id');
            $table->string('identifier');
            $table->string('subject');
            $table->string('drawing_number')->nullable();
            $table->text('directive');

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
        Schema::dropIfExists('construction_directives');
    }
}
