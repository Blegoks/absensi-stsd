<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('absensi', function (Blueprint $table) {
            $table->foreignId('sesi_absensi_id')
                  ->nullable()
                  ->after('anggota_id') // opsional: letak kolom
                  ->constrained('sesi_absensi')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('absensi', function (Blueprint $table) {
            $table->dropForeign(['sesi_absensi_id']);
            $table->dropColumn('sesi_absensi_id');
        });
    }
};
