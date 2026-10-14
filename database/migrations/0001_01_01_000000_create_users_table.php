<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tabel users versi final (sesuai ERD).
 *
 * Dibungkus hasTable() karena database lama dibikin manual lewat
 * phpMyAdmin. Di database yang udah ada, migration ini dilewati aja.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->string('name', 150)->nullable();
                $table->string('nama_lengkap');
                $table->string('nis', 50)->nullable()->unique();
                $table->enum('jenis_kelamin', ['L', 'P'])->nullable();
                $table->date('tanggal_lahir')->nullable();
                $table->text('alamat')->nullable();
                $table->string('no_hp', 20)->nullable();
                $table->string('foto')->nullable();
                $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
                $table->enum('role', ['peserta', 'instruktur', 'admin'])->default('peserta');
                $table->string('email')->unique();
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password');
                $table->rememberToken();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('password_reset_tokens')) {
            Schema::create('password_reset_tokens', function (Blueprint $table) {
                $table->string('email')->primary();
                $table->string('token');
                $table->timestamp('created_at')->nullable();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }
};
