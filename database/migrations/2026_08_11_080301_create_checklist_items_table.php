<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Master daftar item checklist pemeriksaan (template, bisa dikelola admin).
        // Kategori A-F sesuai form kertas: Fisik, Operasional, Storage, Jaringan, Server, Tindakan Perawatan.
        Schema::create('checklist_items', function (Blueprint $table) {
            $table->id();
            $table->string('kategori_kode', 1); // A, B, C, D, E, F
            $table->string('kategori_label');    // "PEMERIKSAAN FISIK", dst
            $table->unsignedInteger('urutan');   // nomor urut dalam kategori
            $table->string('item_pemeriksaan');  // teks item, mis. "Kondisi casing perangkat"
            $table->string('label_kondisi_positif', 20)->default('Baik'); // "Baik" atau "Ya" (khusus kategori F)
            $table->boolean('aktif')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('checklist_items');
    }
};
