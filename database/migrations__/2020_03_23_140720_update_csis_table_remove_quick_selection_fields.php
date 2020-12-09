<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateCsisTableRemoveQuickSelectionFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('csis', function (Blueprint $table) {
            $table->dropColumn(['is_quick_selection', 'is_associated_document']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('csis', function (Blueprint $table) {
            //
        });
    }
}
