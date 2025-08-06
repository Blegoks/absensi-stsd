<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
    if (!Schema::hasColumn('users', 'role')) {
        $table->enum('role', ['admin', 'juruarah', 'anggota'])->after('password');
    }

    if (!Schema::hasColumn('users', 'profile_id')) {
        $table->unsignedBigInteger('profile_id')->after('role')->nullable();
    }
});

    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'profile_id']);
        });
    }
};

