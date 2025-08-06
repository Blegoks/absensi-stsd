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
        Schema::create('absensi', function (Blueprint $table) {
    $table->id();
    $table->foreignId('anggota_id')->constrained('anggota')->onDelete('cascade');
    $table->enum('status', ['hadir', 'tidak_hadir', 'izin'])->default('tidak_hadir');
    $table->string('keterangan')->nullable();
    $table->date('tanggal');
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absensi');
    }
};
