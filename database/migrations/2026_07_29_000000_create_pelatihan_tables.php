<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Semua tabel inti pelatihan: instruktur, materi, lampiran, soal,
 * progress, dan penilaian.
 *
 * Tiap tabel dibungkus hasTable() karena database lama dibikin manual
 * lewat phpMyAdmin. Di database itu migration ini nggak ngapa-ngapain,
 * tapi di database kosong semua tabelnya kebentuk.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('instruktur')) {
            Schema::create('instruktur', function (Blueprint $table) {
                $table->id();
                $table->foreignId('id_user')->unique()->constrained('users')->cascadeOnDelete();
                $table->string('spesialisasi');
                $table->string('pengalaman', 100);
                $table->string('sertifikasi')->nullable();
                $table->string('jabatan', 100);
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('materi')) {
            Schema::create('materi', function (Blueprint $table) {
                $table->id();
                $table->foreignId('id_instruktur')->constrained('instruktur')->cascadeOnDelete();
                $table->string('judul');
                $table->string('slug')->unique();
                $table->string('kategori', 100);
                $table->string('subkategori', 100)->nullable();
                $table->text('deskripsi')->nullable();
                $table->text('objectives')->nullable();
                $table->string('thumbnail', 500)->nullable();
                $table->enum('jenis_materi', ['artikel', 'video', 'pdf', 'quiz'])->default('video');
                $table->unsignedInteger('duration_minutes')->nullable();
                $table->unsignedInteger('learning_points')->default(0);
                $table->unsignedInteger('total_lessons')->nullable();
                $table->string('bahasa', 50)->nullable();
                $table->enum('status', ['draft', 'published'])->default('draft');
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('video')) {
            Schema::create('video', function (Blueprint $table) {
                $table->id();
                $table->foreignId('id_materi')->constrained('materi')->cascadeOnDelete();
                $table->string('video', 500);
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('pdf')) {
            Schema::create('pdf', function (Blueprint $table) {
                $table->id();
                $table->foreignId('id_materi')->constrained('materi')->cascadeOnDelete();
                $table->string('pdf_files', 500);
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('gambar')) {
            Schema::create('gambar', function (Blueprint $table) {
                $table->id();
                $table->foreignId('id_materi')->constrained('materi')->cascadeOnDelete();
                $table->string('picture', 500);
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('soal')) {
            Schema::create('soal', function (Blueprint $table) {
                $table->id();
                $table->foreignId('id_instruktur')->constrained('instruktur')->cascadeOnDelete();
                $table->foreignId('id_materi')->constrained('materi')->cascadeOnDelete();
                $table->text('pertanyaan');
                $table->text('kunci_jawaban');
                $table->enum('jenis_soal', ['pretes', 'postes']);
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('progress')) {
            Schema::create('progress', function (Blueprint $table) {
                $table->id();
                $table->foreignId('id_user')->constrained('users')->cascadeOnDelete();
                $table->foreignId('id_materi')->constrained('materi')->cascadeOnDelete();
                $table->enum('progres', ['pretes', 'view', 'postes', 'sertif'])->default('pretes');
                $table->timestamps();

                $table->unique(['id_user', 'id_materi']);
            });
        }

        if (! Schema::hasTable('penilaian')) {
            Schema::create('penilaian', function (Blueprint $table) {
                $table->id();
                $table->foreignId('id_user')->constrained('users')->cascadeOnDelete();
                $table->foreignId('id_progress')->constrained('progress')->cascadeOnDelete();
                $table->decimal('nilai', 5, 2)->nullable();
                $table->enum('status', ['lulus', 'tidak_lulus'])->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('penilaian');
        Schema::dropIfExists('progress');
        Schema::dropIfExists('soal');
        Schema::dropIfExists('gambar');
        Schema::dropIfExists('pdf');
        Schema::dropIfExists('video');
        Schema::dropIfExists('materi');
        Schema::dropIfExists('instruktur');
    }
};
