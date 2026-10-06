# 📋 CHEAT SHEET — Super Admin SIMPEL PPKPI

## 📁 Struktur Folder (WAJIB persis huruf besar/kecilnya!)

```
app/
└── Http/
    ├── Controllers/
    │   ├── Auth/                          ← huruf A besar!
    │   │   └── AdminAuthController.php
    │   └── Admin/                         ← huruf A besar!
    │       └── DashboardController.php
    └── Middleware/                        ← bukan "MIddelware"!
        └── IsSuperAdmin.php
database/
└── migrations/
    └── 2026_07_23_xxxxxx_add_role_to_users_table.php
resources/
└── views/
    └── admin/
        ├── layouts/
        │   └── app.blade.php
        ├── auth/
        │   └── login.blade.php
        └── dashboard.blade.php
routes/
└── web.php   ← isi dari tambahkan-ke-web.php ditempel di sini
bootstrap/
└── app.php   ← daftarin middleware alias di sini
```

⚠️ **Nama folder di Laravel itu case-sensitive.** `auth` ≠ `Auth`, `admin` ≠ `Admin`. Kalau salah, muncul error "does not comply with psr-4 autoloading standard".

---

## 🔧 Konfigurasi Penting

### `.env`
```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3307
DB_DATABASE=simpel_ppkpi
DB_USERNAME=root
DB_PASSWORD=
```

### `bootstrap/app.php` — daftarin middleware
```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'is_super_admin' => \App\Http\Middleware\IsSuperAdmin::class,
    ]);
})
```

### Model `User.php` — cast password
```php
protected function casts(): array
{
    return [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',   // WAJIB 'hashed', BUKAN 'bcrypt'
    ];
}
```

### Struktur kolom tabel `users` (bukan default Laravel!)
```
id, email, no_hp, password, role, nama_lengkap, male, photo, married, pendidikan, tgl_lahir, created_at, updated_at
```
❗ Gak ada kolom `name` — pakai `nama_lengkap`.

---

## 💻 Urutan Command Setup (dari nol)

```bash
# 1. Cek koneksi & migration
php artisan migrate:status
php artisan migrate:install          # kalau tabel `migrations` belum ada
php artisan migrate                  # jalanin migration role

# 2. Kalau autoload/folder baru diubah
composer dump-autoload
php artisan config:clear
php artisan route:clear

# 3. Nyalain server
php artisan serve
```

---

## 👤 Bikin / Reset User jadi Super Admin (via `php artisan tinker`)

**PENTING: jalanin baris SATU-SATU, jangan digabung/paste barengan output contoh!**

```php
$u = new App\Models\User();
```
```php
$u->email = 'adminppkpi@gmail.com';
```
```php
$u->password = 'password123';
```
⚠️ **JANGAN pakai `bcrypt()`** — karena model `User.php` sudah auto-hash lewat cast `'password' => 'hashed'`. Kalau dobel di-hash, password gak akan pernah cocok saat login.
```php
$u->role = 'super_admin';
```
```php
$u->nama_lengkap = 'Admin PPKPI';
```
```php
$u->save();
```
Harus keluar `= true`. Kalau error, baca pesannya — biasanya ada kolom lain yang wajib diisi (NOT NULL).

**Cek user yang sudah jadi super_admin:**
```php
App\Models\User::where('role', 'super_admin')->get();
```

**Keluar dari tinker:**
```php
exit
```

---

## 🌐 URL Penting

| URL | Fungsi |
|---|---|
| `http://127.0.0.1:8000/admin/login` | Halaman login super admin |
| `http://127.0.0.1:8000/admin/dashboard` | Dashboard (butuh login) |

⚠️ Buka **path lengkapnya**, jangan cuma `127.0.0.1:8000` doang (itu bakal 404 karena route `/` belum didefinisikan).

---

## 🐞 Error yang Sering Muncul & Solusinya

| Error | Penyebab | Solusi |
|---|---|---|
| `Nothing to migrate` | File migration gak kedetect / nama file pakai spasi | Rename file pakai underscore semua, format: `YYYY_MM_DD_HHMMSS_nama_migration.php` |
| `Table 'sessions' doesn't exist` | `SESSION_DRIVER=database` tapi tabel belum dibikin | `php artisan session:table` lalu `php artisan migrate`, atau bikin manual via SQL |
| `Table 'migrations' not found` | Database dibuat manual, bukan lewat Laravel | `php artisan migrate:install` |
| `Data truncated for column 'role'` | Kolom `role` sudah ada duluan, enum-nya belum ada value `super_admin` | `ALTER TABLE users MODIFY COLUMN role ENUM(...) DEFAULT 'peserta';` via phpMyAdmin |
| `Call to undefined cast [bcrypt]` | Typo di `User.php`, harusnya `'hashed'` bukan `'bcrypt'` | Perbaiki di `casts()` method |
| `Target class [is_super_admin] does not exist` | Middleware belum didaftarin di `bootstrap/app.php` | Tambahin `$middleware->alias([...])` |
| `Target class [...IsSuperAdmin] does not exist` | File middleware salah lokasi/nama folder | Cek folder `Middleware` (bukan `MIddelware`), lalu `composer dump-autoload` |
| `does not comply with psr-4 autoloading standard` | Nama folder salah huruf besar/kecil | Rename folder biar sesuai namespace persis |
| `View [admin.layouts.app] not found` | File blade belum ada di lokasi yang benar | Cek `resources/views/admin/layouts/app.blade.php` ada & isinya benar |
| Email/password salah padahal sudah benar | Password di-hash 2x (manual `bcrypt()` + auto-cast `hashed`) | Set password tanpa `bcrypt()`, biarkan cast yang handle |

---

## ✅ Checklist Selanjutnya
- [ ] Dashboard berhasil dibuka & nampilin angka jumlah data 9 tabel
- [ ] Kirim struktur SQL 9 tabel (export dari phpMyAdmin) → biar dibuatkan Model + Controller CRUD + View untuk: `users`, `instruktur`, `materi`, `soal`, `penilaian`, `progress`, `video`, `pdf`, `gambar`