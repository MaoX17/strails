<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNotifyToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            //
            $table->boolean('notify_email')->nullable()->default(true);
            $table->boolean('notify_fcm')->nullable()->default(true);
            $table->boolean('notify_other')->nullable()->default(true);
            //
            $table->string('token_fcm', 200)->nullable();
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
            //
            $table->dropColumn('notify_email');
            $table->dropColumn('notify_fcm');
            $table->dropColumn('notify_other');
            //
            $table->dropColumn('token_fcm');
        });
    }
}
