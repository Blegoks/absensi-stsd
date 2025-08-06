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
       Schema::create('sesi_absensi', function (Blueprint $table) {
    $table->id();
    $table->timestamp('dibuka_pada')->nullable();     // Waktu sesi dimulai
    $table->timestamp('ditutup_pada')->nullable();    // Waktu sesi ditutup
    $table->boolean('is_open')->default(true);        // Status sesi
    $table->timestamps();
});


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sesi_absensi');
    }
};
