<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterOtherDocumentsAndSubmittalsDropCsiId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('other_documents', function (Blueprint $table) {
            $table->dropForeign(['csi_id']);
            $table->dropColumn(['csi_id']);
        });

        Schema::table('submittals', function (Blueprint $table) {
            $table->dropForeign(['csi_id']);
            $table->dropColumn(['csi_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('other_documents', function (Blueprint $table) {
            //
        });
    }
}
