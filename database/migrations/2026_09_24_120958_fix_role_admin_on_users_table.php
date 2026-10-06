<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Longgarkan enum dulu biar 'admin' & 'super_admin' sama-sama boleh
        DB::statement("ALTER TABLE users MODIFY role ENUM('peserta','instruktur','admin','super_admin') DEFAULT 'peserta'");

        // 2. Ubah semua super_admin jadi admin
        DB::table('users')->where('role', 'super_admin')->update(['role' => 'admin']);

        // 3. Rapihin enum, buang super_admin
        DB::statement("ALTER TABLE users MODIFY role ENUM('peserta','instruktur','admin') DEFAULT 'peserta'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE users MODIFY role ENUM('peserta','instruktur','admin','super_admin') DEFAULT 'peserta'");
    }
};