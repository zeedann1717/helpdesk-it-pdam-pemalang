<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stok_barangs', function (Blueprint $table) {
            $table->enum('kondisi_barang', ['baru', 'lama'])->default('baru')->after('kondisi');
        });
    }

    public function down(): void
    {
        Schema::table('stok_barangs', function (Blueprint $table) {
            $table->dropColumn('kondisi_barang');
        });
    }
};
