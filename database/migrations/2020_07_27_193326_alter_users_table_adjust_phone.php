<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterUsersTableAdjustPhone extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('mobile_phone')->nullable();
            $table->string('office_phone')->nullable();
        });

        \App\User::withTrashed()->get()->each(function ($user) {
            $user->mobile_phone = $user->phone ?? '555-555-5555';
            $user->save();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable();
        });

        \App\User::withTrashed()->get()->each(function ($user) {
            $user->phone = $user->mobile_phone ?? '555-555-5555';
            $user->save();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['mobile_phone', 'office_phone']);
        });
    }
}
