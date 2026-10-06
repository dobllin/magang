<?php
// Simpan file ini di: database/migrations/
// Generate dulu file kosongnya lewat command ini di terminal:
//   php artisan make:migration update_users_table_match_erd --table=users
// Nanti muncul file baru dengan nama otomatis (ada timestamp-nya).
// Buka file itu, HAPUS semua isinya, lalu paste isi di bawah ini menggantikan isinya.

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Tambah kolom 'name' (terpisah dari nama_lengkap, sesuai ERD)
            if (!Schema::hasColumn('users', 'name')) {
                $table->string('name', 150)->nullable()->after('id');
            }

            // Nomor Induk Siswa
            if (!Schema::hasColumn('users', 'nis')) {
                $table->string('nis', 50)->nullable()->unique()->after('nama_lengkap');
            }

            // Jenis kelamin (ganti konsep dari kolom 'male' lama)
            if (!Schema::hasColumn('users', 'jenis_kelamin')) {
                $table->enum('jenis_kelamin', ['L', 'P'])->nullable()->after('nis');
            }

            // Alamat
            if (!Schema::hasColumn('users', 'alamat')) {
                $table->text('alamat')->nullable()->after('tgl_lahir');
            }

            // Status aktif/nonaktif
            if (!Schema::hasColumn('users', 'status')) {
                $table->enum('status', ['aktif', 'nonaktif'])->default('aktif')->after('foto');
            }
        });

        // Rename kolom biar sesuai penamaan ERD (pakai raw SQL, gak butuh package tambahan)
        if (Schema::hasColumn('users', 'tgl_lahir') && !Schema::hasColumn('users', 'tanggal_lahir')) {
            DB::statement('ALTER TABLE users RENAME COLUMN tgl_lahir TO tanggal_lahir');
        }

        if (Schema::hasColumn('users', 'photo') && !Schema::hasColumn('users', 'foto')) {
            DB::statement('ALTER TABLE users RENAME COLUMN photo TO foto');
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['name', 'nis', 'jenis_kelamin', 'alamat', 'status']);
        });

        if (Schema::hasColumn('users', 'tanggal_lahir')) {
            DB::statement('ALTER TABLE users RENAME COLUMN tanggal_lahir TO tgl_lahir');
        }
        if (Schema::hasColumn('users', 'foto')) {
            DB::statement('ALTER TABLE users RENAME COLUMN foto TO photo');
        }
    }
};