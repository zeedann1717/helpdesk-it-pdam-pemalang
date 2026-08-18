<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengaturan_dokumen', function (Blueprint $table) {
            $table->id();

            $table->string('diperiksa_nama')->nullable();
            $table->string('diperiksa_jabatan')->nullable();
            $table->string('diperiksa_npp')->nullable();

            $table->string('dibuat_nama')->nullable();
            $table->string('dibuat_jabatan')->nullable();
            $table->string('dibuat_npp')->nullable();

            $table->boolean('tampilkan_disetujui')->default(false);
            $table->string('disetujui_nama')->nullable();
            $table->string('disetujui_jabatan')->nullable();
            $table->string('disetujui_npp')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengaturan_dokumen');
    }
};