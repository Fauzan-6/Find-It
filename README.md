# Find-It — Lost & Found Kampus (Laravel + MySQL + Bootstrap 5)

Paket ini berisi file yang perlu **ditambahkan / menimpa** di proyek Laravel baru (Laravel 12). Tidak ada paket tambahan selain bawaan Laravel (autentikasi dibuat sendiri, Bootstrap lewat CDN).

## 1. Setup

```bash
# 1) Buat proyek Laravel baru
composer create-project laravel/laravel find-it
cd find-it

# 2) Salin isi paket ini ke dalam folder proyek (timpa jika diminta):
#    app/  bootstrap/app.php  config/findit.php  database/  resources/views/  routes/web.php
#    (jika lewat terminal: cp -R /path/paket/* .)

# 3) Buat database di MySQL
mysql -u root -p -e "CREATE DATABASE find_it CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

Edit `.env`:

```env
APP_NAME=Find-It
APP_LOCALE=id
APP_FAKER_LOCALE=id_ID
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=find_it
DB_USERNAME=root
DB_PASSWORD=

FILESYSTEM_DISK=public
```

```bash
# 4) Migrasi + akun awal + link storage untuk foto
php artisan migrate --seed
php artisan storage:link

# 5) Jalankan
php artisan serve
```

Buka http://localhost:8000

| Peran | Email | Kata sandi |
|-------|-------|-----------|
| Admin | admin@findit.test | password |
| User  | user@findit.test  | password |

> Ganti kata sandi admin sebelum dipakai di server sungguhan.

## 2. Struktur file

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── AuthController.php     # login, daftar, keluar
│   │   ├── ItemController.php     # katalog, filter, detail, lapor, dashboard user, resolve
│   │   ├── ClaimController.php    # ajukan klaim, setujui/tolak oleh pelapor
│   │   └── AdminController.php    # moderasi laporan, arsip
│   └── Middleware/
│       ├── AdminMiddleware.php    # alias: admin
│       └── UserMiddleware.php     # alias: user
├── Models/ User.php  Item.php  Claim.php
└── Providers/AppServiceProvider.php   # Bootstrap 5 pagination
bootstrap/app.php                      # registrasi alias middleware
config/findit.php                      # daftar kategori & lokasi kampus
database/
├── migrations/ (users: phone+role, items, claims)
└── seeders/DatabaseSeeder.php
resources/views/
├── layouts/app.blade.php
├── partials/ item-card, status-badge
├── auth/ login, register
├── items/ index (beranda, hilang, ditemukan), create, show (+ form klaim)
├── dashboard/index.blade.php
└── admin/ index (moderasi), archive
routes/web.php
```

## 3. Alur utama

1. User melapor barang → `moderation_status = pending`, belum tampil publik.
2. Admin menyetujui/menolak di `/admin`. Hanya `approved` + `active` yang muncul di katalog.
3. User lain mengajukan klaim dengan bukti kepemilikan di halaman detail (satu klaim per user per barang; tidak bisa mengklaim barang sendiri).
4. Pelapor menyetujui/menolak klaim di dashboard. Nomor telepon pelapor baru terlihat oleh pengaju setelah klaim `approved` (dan sebaliknya).
5. Pelapor menandai barang `resolved` → keluar dari katalog dan masuk Arsip admin (bersama laporan yang ditolak).

## 4. Kustomisasi cepat

- Kategori & lokasi kampus: ubah `config/findit.php`.
- Rute utama: `/`, `/barang-hilang`, `/barang-ditemukan`, `/barang/{id}`, `/barang/lapor`, `/dashboard`, `/admin`, `/admin/arsip`.
- Ide pengembangan: notifikasi email saat klaim masuk/disetujui, batas rate pada form klaim, soft delete laporan, kolom alasan penolakan.
