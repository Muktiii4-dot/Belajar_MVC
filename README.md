Project PWPB — Sistem Informasi Data Siswa

Aplikasi web pengelolaan data siswa berbasis Laravel 13. Project ini disusun dan disinkronkan dengan materi Modul 7, 8, 10, 11, 12, dan 13, kemudian disesuaikan dengan struktur data pada project PWPB agar fitur-fitur tersebut dapat digunakan dalam satu aplikasi.

📌 Gambaran Project

Project ini merupakan aplikasi CRUD (Create, Read, Update, Delete) untuk mengelola data siswa. Selain pengelolaan data dasar, aplikasi menyediakan pencarian, pagination, upload foto siswa, serta export data ke Excel dan PDF.

Data siswa yang dikelola

NISN

Nama

Tempat lahir

Tanggal lahir

Jenis kelamin

Jurusan

Alamat

Nomor HP

Email

Foto

🚀 Fitur Utama

Fitur

Keterangan

Modul

Menampilkan data siswa

Menampilkan data dari database dengan pagination

10

Tambah siswa

Form tambah data dengan validasi server-side dan CSRF

7

Edit siswa

Mengubah data siswa yang sudah tersimpan

8

Update siswa

Validasi dan penyimpanan perubahan data

8

Hapus siswa

Menghapus data siswa dari database

Pengembangan CRUD

Pencarian

Mencari berdasarkan nama, NISN, alamat, atau email

11

Pagination

5 data per halaman dan mempertahankan keyword pencarian

10–11

Upload foto

JPG/JPEG/PNG dengan batas ukuran 2 MB

12

Ganti foto

Menghapus foto lama ketika foto baru disimpan

12

Export Excel

Menghasilkan laporan data siswa

13

Export PDF

Menghasilkan laporan data siswa dalam PDF

13

Seeder

Menyediakan 50 data siswa contoh

Pendukung project

🧰 Teknologi yang Digunakan

PHP: 8.3 atau lebih baru

Framework: Laravel 13

Database: MySQL / MariaDB

Database port: 3307 pada konfigurasi lokal project ini

Frontend: Blade + Bootstrap-compatible pagination

Build tool: Vite

CSS: Tailwind CSS tersedia melalui Vite

ORM: Laravel Eloquent

Template engine: Blade

Versi Laravel dan PHP di atas mengikuti konfigurasi composer.json project.

📂 Struktur Folder Penting

project_pwpb/
├── app/
│   ├── Http/Controllers/
│   │   └── SiswaController.php
│   ├── Models/
│   │   └── Siswa.php
│   └── Support/
│       ├── SimplePdf.php
│       └── SimpleXlsx.php
│
├── database/
│   ├── factories/
│   │   └── SiswaFactory.php
│   ├── migrations/
│   │   ├── 2026_09_17_105624_create_siswas_table.php
│   │   └── 2026_10_03_000001_add_foto_to_siswas_table.php
│   └── seeders/
│       ├── DatabaseSeeder.php
│       └── SiswaSeeder.php
│
├── resources/views/siswa/
│   ├── create.blade.php
│   ├── edit.blade.php
│   ├── index.blade.php
│   ├── layout.blade.php
│   └── partials/form.blade.php
│
├── routes/
│   └── web.php
│
├── public/
├── storage/
├── tests/
├── composer.json
├── package.json
└── README.md

🔄 Alur Data Siswa

Form Blade
    ↓
Route
    ↓
SiswaController
    ↓
Validation
    ↓
Siswa Model / Eloquent
    ↓
Database MySQL
    ↓
SiswaController
    ↓
Blade View

Untuk upload foto:

Form multipart/form-data
        ↓
Validasi file
        ↓
Storage public/foto_siswa
        ↓
Path foto disimpan pada tabel siswas

⚙️ Persyaratan Sistem

Sebelum menjalankan project, pastikan komputer memiliki:

PHP 8.3+

Composer

MySQL atau MariaDB

Node.js dan npm jika ingin menjalankan Vite/build asset

Browser modern

Untuk konfigurasi database lokal project ini, MySQL/MariaDB menggunakan port 3307.

🛠️ Instalasi

1. Extract project

Extract ZIP project kemudian buka terminal pada folder:

cd project_pwpb

2. Install dependency PHP

composer install

3. Siapkan file environment

Jika .env belum tersedia, buat dari .env.example:

copy .env.example .env

Pada Linux/macOS:

cp .env.example .env

Atur konfigurasi database sesuai MySQL lokal. Untuk konfigurasi yang digunakan project ini:

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3307
DB_DATABASE=db_laravel_rpl
DB_USERNAME=root
DB_PASSWORD=

Sesuaikan DB_USERNAME dan DB_PASSWORD apabila konfigurasi MySQL kamu berbeda.

4. Generate application key

php artisan key:generate

5. Bersihkan cache konfigurasi

php artisan optimize:clear

6. Buat struktur database dan data contoh

Untuk instalasi development dari kondisi kosong:

php artisan migrate:fresh --seed

Perintah tersebut akan membuat ulang tabel dan menjalankan seeder. Seeder project menyediakan 50 data siswa contoh.

Jangan gunakan migrate:fresh pada database produksi karena perintah tersebut menghapus tabel yang dikelola migration.

7. Aktifkan akses storage untuk foto

php artisan storage:link

8. Jalankan aplikasi

php artisan serve

Kemudian buka:

http://127.0.0.1:8000/siswa

🗃️ Database

Tabel utama aplikasi adalah:

siswas

Kolom utama:

Kolom

Tipe

Keterangan

id

BIGINT

Primary key

nisn

VARCHAR(10)

NISN unik

nama

VARCHAR(100)

Nama siswa

tempat_lahir

VARCHAR(100)

Tempat lahir

tanggal_lahir

DATE

Tanggal lahir

jenis_kelamin

ENUM

L atau P

jurusan

VARCHAR

Default RPL

alamat

TEXT

Alamat siswa

no_hp

VARCHAR(25)

Nomor HP

email

VARCHAR

Email unik

foto

VARCHAR

Path foto siswa

created_at

TIMESTAMP

Waktu dibuat

updated_at

TIMESTAMP

Waktu diperbarui

Kolom foto ditambahkan melalui migration terpisah sehingga struktur database tetap mengikuti tahapan pengembangan project.

🔗 Route Utama

Method

URL

Fungsi

GET

/

Halaman utama/daftar siswa

GET

/siswa

Daftar siswa

GET

/siswa/create

Form tambah siswa

POST

/siswa

Menyimpan siswa baru

GET

/siswa/{id}/edit

Form edit siswa

PUT

/siswa/{id}

Memperbarui siswa

DELETE

/siswa/{id}

Menghapus siswa

GET

/siswa/export/excel

Export Excel

GET

/siswa/export/pdf

Export PDF

Pencarian

Halaman /siswa menerima parameter:

/siswa?keyword=ali

Pencarian dilakukan terhadap nama, NISN, alamat, dan email.

🔎 Pagination dan Search

Data siswa ditampilkan menggunakan pagination sebanyak 5 data per halaman.

Contoh:

/siswa?page=2

Ketika pencarian digunakan, keyword tetap dipertahankan ketika pengguna berpindah halaman.

Contoh:

/siswa?keyword=ali&page=2

🖼️ Upload Foto

Foto siswa dapat diunggah melalui form tambah atau edit.

Aturan validasi:

Format: JPG, JPEG, PNG

Maksimum: 2 MB

Field foto bersifat opsional

File disimpan pada disk public di direktori:

storage/app/public/foto_siswa

Akses publik dibuat melalui:

php artisan storage:link

Ketika foto siswa diganti atau data siswa dihapus, project juga mencoba menghapus file foto lama dari storage.

📊 Export Data

Project menyediakan dua endpoint:

/siswa/export/excel
/siswa/export/pdf

Implementasi pada project menggunakan helper lokal:

app/Support/SimpleXlsx.php
app/Support/SimplePdf.php

Dengan pendekatan ini, fitur export tidak bergantung pada paket Laravel Excel dan DomPDF yang tidak tercantum sebagai dependency inti pada composer.json project.

Modul 13 menjelaskan Laravel Excel dan DomPDF sebagai pendekatan pustaka untuk fitur export. Implementasi project ini mempertahankan fitur export melalui helper lokal agar project dapat digunakan tanpa menambahkan dependency tersebut secara wajib.

🧪 Seeder dan Data Awal

Seeder siswa berada di:

database/seeders/SiswaSeeder.php

Seeder menggunakan SiswaFactory untuk membuat data contoh.

Untuk membuat ulang data development:

php artisan migrate:fresh --seed

🧹 Perintah Troubleshooting

Jika perubahan .env, route, view, atau konfigurasi tidak terbaca, jalankan:

php artisan optimize:clear

Jika database development ingin dibuat ulang dari awal:

php artisan migrate:fresh --seed

Jika foto tidak dapat diakses:

php artisan storage:link

Untuk melihat route yang terdaftar:

php artisan route:list

Untuk menjalankan test:

php artisan test

⚠️ Catatan Penting

Database

Pastikan database yang digunakan Laravel sama dengan database yang kamu lihat di phpMyAdmin. Perhatikan khususnya:

DB_HOST=127.0.0.1
DB_PORT=3307
DB_DATABASE=db_laravel_rpl

migrate:fresh

Perintah ini menghapus tabel lalu membuatnya kembali. Gunakan hanya untuk database development atau ketika memang ingin mengulang struktur database dari awal.

File .env

Jangan commit file .env ke repository publik karena file tersebut dapat berisi kredensial database dan konfigurasi aplikasi.

📚 Hubungan dengan Modul Pembelajaran

Modul 7
  ↓
Tambah Data + Validasi + CSRF
  ↓
Modul 8
  ↓
Edit + Update
  ↓
Modul 10
  ↓
Pagination
  ↓
Modul 11
  ↓
Search + Pagination Query
  ↓
Modul 12
  ↓
Upload Foto
  ↓
Modul 13
  ↓
Export Excel + PDF

Project ini menggunakan istilah dan alur dari modul sebagai dasar, tetapi nama field disesuaikan dengan struktur database project PWPB. Contohnya, project menggunakan nama dan no_hp, bukan nama_lengkap dan nomor_hp.

👨‍💻 Status Project

Project: PWPB — Sistem Data Siswa
Framework: Laravel 13
PHP: 8.3+
Database: MySQL/MariaDB
Port database lokal: 3307
Status: Project pembelajaran dengan fitur CRUD, pagination, pencarian, upload foto, dan export data.

📄 Lisensi

Project ini digunakan sebagai project pembelajaran/praktikum. Sesuaikan lisensi apabila project akan didistribusikan atau digunakan untuk kebutuhan lain.
