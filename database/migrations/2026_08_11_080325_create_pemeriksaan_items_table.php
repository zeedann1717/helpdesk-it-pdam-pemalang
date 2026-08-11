<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Detail hasil tiap item checklist dalam satu pemeriksaan
        Schema::create('pemeriksaan_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pemeriksaan_id')->constrained('pemeriksaans')->cascadeOnDelete();
            $table->foreignId('checklist_item_id')->constrained('checklist_items')->cascadeOnDelete();
            $table->enum('kondisi', ['baik', 'tidak']); // sesuai centang Baik/Tidak (atau Ya/Tidak di kategori F)
            $table->enum('hasil', ['layak', 'tidak_layak']);
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pemeriksaan_items');
    }
};
