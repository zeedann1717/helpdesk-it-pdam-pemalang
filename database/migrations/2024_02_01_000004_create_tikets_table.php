<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tikets', function (Blueprint $table) {
            $table->id();
            $table->string('kode_tiket')->unique();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('divisi_id')->nullable()->constrained('divisis')->nullOnDelete();
            $table->foreignId('lokasi_id')->nullable()->constrained('lokasis')->nullOnDelete();
            $table->string('unit')->nullable()->comment('Jenis perangkat: Laptop, PC, Printer, dll');
            $table->text('keluhan');
            $table->string('foto')->nullable();
            $table->enum('status', ['waiting', 'in_progress', 'done'])->default('waiting');
            $table->text('catatan_admin')->nullable();
            $table->timestamp('tanggal_selesai')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tikets');
    }
};
