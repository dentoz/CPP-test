<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGoogleIdToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->text('google_id')->nullable()->after('email');
            $table->string('role')->default('user')->after('google_id');
            $table->text('avatar')->nullable()->after('role');
            $table->string('nick_name')->nullable()->after('avatar');
            $table->smallInteger('active')->default(0)->after('nick_name');
            $table->text('fcm_token')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
