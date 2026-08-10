<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Tahap 1: tambah value baru dulu, biar data lama gak ke-truncate
        DB::statement("ALTER TABLE users MODIFY role ENUM('admin','user','super_admin','admin_divisi') DEFAULT 'user'");

        // Tahap 2: migrasikan data role lama ('admin') jadi 'super_admin'
        DB::table('users')->where('role', 'admin')->update(['role' => 'super_admin']);

        // Tahap 3: buang value enum lama, sisain yang baru aja
        DB::statement("ALTER TABLE users MODIFY role ENUM('super_admin','admin_divisi','user') DEFAULT 'user'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE users MODIFY role ENUM('admin','user','super_admin','admin_divisi') DEFAULT 'user'");
        DB::table('users')->where('role', 'super_admin')->update(['role' => 'admin']);
        DB::table('users')->where('role', 'admin_divisi')->update(['role' => 'admin']);
        DB::statement("ALTER TABLE users MODIFY role ENUM('admin','user') DEFAULT 'user'");
    }
};
