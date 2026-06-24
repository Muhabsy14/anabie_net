# Anabie Net — Sistem Manajemen Billing WiFi/ISP

Aplikasi web manajemen pelanggan & tagihan internet untuk **Anabie Net**.
Mendukung 3 (tiga) level pengguna: **Owner**, **Admin**, dan **Pelanggan**, dengan
tema warna biru khas Anabie Net, logo pada setiap halaman, dan tampilan
**Responsive Web Design (RWD)**.

![Logo](public/assets/img/logo.png)

## Fitur

### Owner
Login, Kelola Admin, Kelola Pelanggan, Kelola Paket/Layanan, Kelola Tagihan,
Kelola Pembayaran (pencatatan + form bukti pembayaran), Kirim Notifikasi Tagihan
via WhatsApp, Kelola Pengaduan, Laporan (lengkap dengan logo & kop surat),
Profil, dan Logout.

### Admin
Login, Kelola Pelanggan, Kelola Paket/Layanan, Kelola Tagihan, Kelola Pembayaran
(pencatatan + form bukti pembayaran), Kirim Notifikasi via WhatsApp,
Kelola Pengaduan, Laporan Operasional, Profil, dan Logout.

### Pelanggan
Login, Profil, Lihat Tagihan, Riwayat Pembayaran, Ajukan Pengaduan, dan Logout.

## Teknologi
- PHP 8.1 (native, tanpa framework — pola MVC sederhana)
- MySQL / MariaDB (PDO)
- HTML, CSS murni (responsive) + sedikit JavaScript

## Struktur Folder
```
anabie_net/
├── app/
│   ├── config.php           # konfigurasi DB & identitas perusahaan
│   ├── controllers/         # logika tiap fitur
│   ├── lib/                 # db, auth, helper, view, navigasi
│   └── views/               # tampilan (login, owner, admin, pelanggan)
├── database/
│   └── anabie_net.sql       # skema + data awal (seed)
├── public/
│   ├── index.php            # front controller / router
│   ├── assets/ (css, js, img/logo.png)
│   └── uploads/             # file bukti pembayaran
└── README.md
```

## Cara Menjalankan

### 1. Siapkan database
```bash
mysql -u root -p -e "CREATE DATABASE anabie_net CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
mysql -u root -p anabie_net < database/anabie_net.sql
```
> Bila memakai XAMPP/Laragon, buat database `anabie_net` lalu import
> `database/anabie_net.sql` melalui phpMyAdmin.

### 2. Sesuaikan konfigurasi
Edit `app/config.php` (atau gunakan environment variable):
```php
DB_HOST=127.0.0.1  DB_NAME=anabie_net  DB_USER=root  DB_PASS=
```

### 3. Jalankan
```bash
php -S localhost:8080 -t public
```
Buka `http://localhost:8080`.

> Pada XAMPP, salin folder ke `htdocs/` lalu akses
> `http://localhost/anabie_net/public/`.

## Akun Demo
Password semua akun: **`password123`**

| Role      | Username |
|-----------|----------|
| Owner     | `owner`  |
| Admin     | `admin`  |
| Pelanggan | `budi`   |
| Pelanggan | `siti`   |

## Catatan
- **Notifikasi WhatsApp** memakai tautan `wa.me` dengan pesan tagihan yang sudah
  otomatis terisi (tidak memerlukan API berbayar).
- **Laporan** dibuka di tab baru lengkap dengan logo & kop surat, dan dapat
  dicetak atau disimpan sebagai PDF melalui dialog cetak browser.
- File bukti pembayaran disimpan di `public/uploads/` (diabaikan oleh git).
