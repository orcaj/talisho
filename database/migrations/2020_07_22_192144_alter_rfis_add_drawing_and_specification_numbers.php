<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterRfisAddDrawingAndSpecificationNumbers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rfis', function (Blueprint $table) {
            $table->string('specification_number')->nullable();
            $table->string('drawing_number')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rfis', function (Blueprint $table) {
            $table->dropColumn(['specification_number', 'drawing_number']);
        });
    }
}
