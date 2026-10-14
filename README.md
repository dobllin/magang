# SIMPEL PPKPI

Sistem Informasi Manajemen Pelatihan dan Evaluasi untuk Pusat Pelatihan Kerja Pengembangan Industri (PPKPI). Dibangun dengan Laravel 13, Blade, dan Tailwind CSS.

## Peran pengguna

| Peran | Masuk lewat | Bisa apa |
|---|---|---|
| **Admin** | `/admin/login` | Kelola user (termasuk menonaktifkan akun), profil instruktur, dan semua materi. |
| **Instruktur** | `/login` | Bikin materi (video YouTube, PDF/gambar Google Drive), bank soal pretes/postes, pantau peserta, cetak sertifikat. |
| **Peserta** | `/login` atau daftar di `/register` | Ikut materi, kerjakan pretes, belajar, kerjakan postes, unduh sertifikat. |

## Alur belajar peserta

```
Ikuti materi → Pretes → Belajar → Postes → Sertifikat
```

- Tahapnya disimpan di kolom `progress.progres` (`pretes`, `view`, `postes`, `sertif`).
- Pretes cuma mengukur kemampuan awal dan nggak memengaruhi kelulusan. Kalau materi nggak punya soal pretes, tahap ini dilewati.
- Postes dinilai otomatis (0–100) dan disimpan di tabel `penilaian`. Lulus kalau nilainya ≥ `PELATIHAN_KKM` (bawaan 70). Kalau belum lulus, postes bisa diulang.
- Soal bentuknya isian. Jawaban dicocokkan dengan kunci tanpa memperhatikan huruf besar/kecil, spasi berlebih, dan tanda baca di ujung. Untuk pilihan ganda, tulis pilihannya di pertanyaan dan hurufnya (misal `B`) sebagai kunci.

## Menjalankan di komputer sendiri

Butuh PHP 8.3+, Composer, Node.js, dan MySQL/MariaDB.

```bash
composer install
npm install

cp .env.example .env
php artisan key:generate
# Buat database kosong "simpel_ppkpi" di MySQL, sesuaikan DB_* di .env

php artisan migrate --seed
npm run build        # atau: npm run dev (biarkan jalan)
php artisan serve
```

Seeder bikin akun contoh (password semuanya `password`):

| Email | Peran |
|---|---|
| `admin@ppkpi.test` | Admin |
| `instruktur@ppkpi.test` | Instruktur (sudah punya 1 materi + soal) |
| `peserta@ppkpi.test` | Peserta |

> Jangan jalankan `--seed` di server produksi.

### Database lama (dibuat lewat phpMyAdmin)

Migration pembuat tabel dibungkus `Schema::hasTable()`, jadi aman dijalankan di database yang tabelnya udah ada: tabel yang ada nggak disentuh, yang belum ada dibikinkan.

## Testing

```bash
php artisan test
```

Test pakai SQLite in-memory (lihat `phpunit.xml`), jadi nggak menyentuh database MySQL.

## Struktur penting

- `app/Http/Controllers/Admin` — panel admin
- `app/Http/Controllers/Instruktur` — area instruktur
- `app/Http/Controllers/Peserta/KelasController.php` — alur belajar peserta
- `app/Support/Tautan.php` — ubah link YouTube/Google Drive jadi link embed
- `app/Support/Penilai.php` — penilaian jawaban pretes/postes
- `app/Support/Sertifikat.php` — query sertifikat (dipakai instruktur & peserta)
- `config/pelatihan.php` — nilai KKM
