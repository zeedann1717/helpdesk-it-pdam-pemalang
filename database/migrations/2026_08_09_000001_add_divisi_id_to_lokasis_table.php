<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lokasis', function (Blueprint $table) {
            $table->foreignId('divisi_id')
                ->nullable()
                ->after('id')
                ->constrained('divisis')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('lokasis', function (Blueprint $table) {
            $table->dropConstrainedForeignId('divisi_id');
        });
    }
};