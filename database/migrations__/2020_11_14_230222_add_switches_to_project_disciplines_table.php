<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSwitchesToProjectDisciplinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('project_disciplines', function (Blueprint $table) {
            $table->boolean('documentation_status') -> nullable()->default(true);
            $table->boolean('communication_status') -> nullable()->default(true);
            $table->boolean('liability_status') -> nullable()->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('project_disciplines', function (Blueprint $table) {
            $table->dropColumn(['documentation_status', 'communication_status', 'liability_status']);
        });
    }
}
