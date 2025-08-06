<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserIdToAnggotaJuruarahAdminTables extends Migration
{
    public function up()
    {
        Schema::table('anggota', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->after('id')->nullable()->unique();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::table('juruarah', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->after('id')->nullable()->unique();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::table('admins', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->after('id')->nullable()->unique();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('anggota', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });

        Schema::table('juruarah', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });

        Schema::table('admins', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
}

