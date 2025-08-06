<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up(): void
{
    Schema::create('catatan_absensi', function (Blueprint $table) {
        $table->id();
        $table->foreignId('anggota_id')->constrained('anggota')->onDelete('cascade');
        $table->foreignId('sesi_absensi_id')->nullable()->constrained('sesi_absensi')->onDelete('set null');
        $table->date('tanggal');
        $table->text('catatan');
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('catatan_absensis');
    }
};
