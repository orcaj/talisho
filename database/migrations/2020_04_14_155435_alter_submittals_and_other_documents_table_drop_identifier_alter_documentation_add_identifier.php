<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterSubmittalsAndOtherDocumentsTableDropIdentifierAlterDocumentationAddIdentifier extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('submittals', function (Blueprint $table) {
            $table->dropColumn(['identifier']);
        });

        Schema::table('other_documents', function (Blueprint $table) {
            $table->dropColumn(['identifier']);
        });

        Schema::table('documentations', function (Blueprint $table) {
            $table->string('identifier');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
