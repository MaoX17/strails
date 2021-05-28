<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTokenstravaToUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('users', function($table) {

            //$table->rememberToken('');
            $table->string('token_strava_access', 100)->nullable();
            $table->string('token_strava_refresh', 100)->nullable();
            
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

        Schema::table('users', function($table) {
        $table->dropColumn('token_strava');
    });


    }
}
