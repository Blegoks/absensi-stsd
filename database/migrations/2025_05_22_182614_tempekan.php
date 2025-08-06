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
        //
        Schema::create('tempekan', function (Blueprint $table) {
    $table->id();
    $table->string('nama');
    $table->unsignedBigInteger('juruarah_id');
    $table->timestamps();
    $table->foreign('juruarah_id')->references('id')->on('juruarah')->onDelete('cascade');
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
