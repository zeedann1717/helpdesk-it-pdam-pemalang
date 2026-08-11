<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Header pemeriksaan berkala (1 record = 1 kali pemeriksaan pada 1 perangkat)
        Schema::create('pemeriksaans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('perangkat_id')->constrained('perangkat')->cascadeOnDelete();
            $table->date('tanggal_pemeriksaan');
            $table->string('hari', 20); // Senin, Selasa, dst — disimpan agar konsisten dg form cetak
            $table->enum('jadwal', ['Harian', 'Mingguan', 'Bulanan']);
            $table->string('nama_pemeriksa'); // nama teknisi yang periksa di lapangan (bisa bukan user sistem)
            $table->foreignId('diinput_oleh')->constrained('users')->cascadeOnDelete(); // admin yang input ke sistem
            $table->text('catatan_umum')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pemeriksaans');
    }
};
