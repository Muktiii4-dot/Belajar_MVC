# Project PWPB - Sistem Data Siswa

Project Laravel yang disinkronkan dengan Modul 7, 8, 10, 11, 12, dan 13.

## Fitur
- Tambah data siswa + validasi server-side + CSRF
- Edit & update data + method spoofing PUT
- Hapus data + cleanup foto
- Pagination 5 data per halaman dengan Bootstrap 5
- Search nama, NISN, alamat, dan email + keyword tetap saat pagination
- Upload foto JPG/JPEG/PNG maksimal 2 MB
- Export Excel (.xlsx) dan PDF
- Seeder 50 data siswa

## Setup
1. Pastikan PHP 8.3+ dan MySQL aktif pada port 3307.
2. Pastikan `.env` menggunakan `DB_DATABASE=db_laravel_rpl` dan `DB_PORT=3307`.
3. Jalankan:

```bash
composer install
php artisan key:generate
php artisan optimize:clear
php artisan migrate:fresh --seed
php artisan storage:link
php artisan serve
```

Buka `http://127.0.0.1:8000/siswa`.

## Catatan export
Modul 13 merekomendasikan Laravel Excel dan DomPDF. Paket tersebut tidak dipaksa menjadi dependency inti pada ZIP ini agar instalasi tetap stabil ketika Composer/internet belum tersedia. Export tetap berjalan melalui implementasi native: XLSX menggunakan ZipArchive bila tersedia (fallback CSV bila ekstensi ZIP belum aktif), sedangkan PDF dibuat oleh generator PDF minimal bawaan project.
