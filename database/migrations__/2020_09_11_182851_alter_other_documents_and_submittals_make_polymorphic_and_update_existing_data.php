<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AlterOtherDocumentsAndSubmittalsMakePolymorphicAndUpdateExistingData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // add columns
        Schema::table('other_documents', function (Blueprint $table) {
            $table->morphs('specification');
        });

        Schema::table('submittals', function (Blueprint $table) {
            $table->morphs('specification');
        });

        // for each other doc and submittal, update csi_id to specification_id and specification_type -> App\CSI

        \App\OtherDocument::all()->each(function ($doc) {
            $doc->update([
               'specification_id' => $doc->csi_id,
                'specification_type' => \App\CSI::class
            ]);
        });

        \App\Submittal::all()->each(function ($doc) {
            $doc->update([
                'specification_id' => $doc->csi_id,
                'specification_type' => \App\CSI::class
            ]);
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
