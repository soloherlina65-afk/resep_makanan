# Racikara

Racikara adalah aplikasi berbagi resep berbasis Laravel. Pengguna dapat mencari dan memfilter resep, melihat detail dan ulasan, menyimpan foto profil, mengirim pesan ke pengelola, serta menyusun menu mingguan dengan daftar belanja.

## Persyaratan

- PHP 8.1 atau lebih baru beserta ekstensi Laravel yang diperlukan
- Composer
- MySQL atau database kompatibel untuk menjalankan aplikasi
- Node.js dan npm untuk aset frontend
- Ekstensi PDO SQLite untuk menjalankan tes

## Menyiapkan proyek

1. Pasang dependensi PHP dan frontend:

   ```sh
   composer install
   npm install
   ```

2. Salin `.env.example` menjadi `.env`, lalu isi konfigurasi database di `.env`:

   ```sh
   cp .env.example .env
   php artisan key:generate
   ```

   Di Windows PowerShell, gunakan `Copy-Item .env.example .env` sebagai pengganti `cp`.

3. Buat database MySQL kosong, lalu sesuaikan `DB_DATABASE`, `DB_USERNAME`, dan `DB_PASSWORD`. Terapkan migrasi:

   ```sh
   php artisan migrate
   ```

   Jangan gunakan `migrate:fresh` pada database yang berisi data karena perintah itu menghapus seluruh tabel.

4. Buat tautan untuk foto profil yang diunggah, lalu siapkan aset:

   ```sh
   php artisan storage:link
   npm run build
   ```

5. Jalankan aplikasi:

   ```sh
   php artisan serve
   ```

   Untuk pengembangan frontend dengan hot reload, jalankan `npm run dev` di terminal terpisah.

## Konfigurasi opsional

- **Login Google:** isi `GOOGLE_CLIENT_ID`, `GOOGLE_CLIENT_SECRET`, dan `GOOGLE_REDIRECT_URI` pada `.env`. Daftarkan URL callback yang sama persis di Google OAuth Console; format lokal bawaan adalah `${APP_URL}/auth/google/callback`.
- **Form kontak:** isi `CONTACT_TO_ADDRESS` dan konfigurasi `MAIL_*` dengan layanan email yang dapat diakses aplikasi. Tanpa penerima yang dikonfigurasi, formulir akan menampilkan pesan gagal dan tidak mengaku berhasil mengirim.
- Jangan memasukkan `.env`, kredensial OAuth, atau kata sandi ke Git.

## Fitur utama

- Pencarian resep berdasarkan nama, bahan, dan kategori, termasuk saran pencarian.
- Filter kategori, waktu memasak, dan tingkat kesulitan; urutkan resep terbaru atau rating tertinggi dengan pagination.
- Profil pengguna dengan foto unggahan atau foto Google.
- Rating dan moderasi ulasan.
- Form kontak berbasis Laravel.
- Perencana menu mingguan dan daftar belanja berdasarkan bahan resep.
- Role `admin`/`user` dengan pembatasan route admin dan otorisasi kepemilikan resep serta ulasan.

## Role dan akses admin

Setelah menjalankan `php artisan migrate`, kolom `users.role` dibuat dengan nilai bawaan `user`. Migrasi mengubah akun lama yang memiliki `is_admin = true` menjadi role `admin`, tanpa mengubah data resep atau akun. Registrasi email maupun Google selalu membuat akun baru dengan role `user`.

Admin yang sudah ada dapat membuka `/admin/dashboard` untuk mengelola resep, kategori, pengguna dan role, moderasi komentar, anggota tim, notifikasi, pengaturan kontak, serta aktivitas. User biasa yang mencoba URL admin akan diarahkan ke dashboard pengguna dengan pesan akses ditolak. Policy juga membatasi edit/hapus resep dan ulasan milik pengguna lain.

Untuk instalasi baru yang belum memiliki admin, daftarkan akun terlebih dahulu lalu promosikan akun tersebut langsung di database menggunakan email akun sendiri:

```sql
UPDATE users
SET role = 'admin', is_admin = 1
WHERE email = 'email-admin-anda@example.com';
```

Setelah admin pertama dibuat, gunakan halaman `/admin/users` untuk mengatur role. Sistem mencegah penghapusan atau penurunan role admin terakhir.

## Menjalankan tes

Tes menggunakan SQLite in-memory melalui `phpunit.xml`, sehingga tidak mengubah database aplikasi:

```sh
php artisan test
```

Pastikan ekstensi PDO SQLite aktif pada PHP yang digunakan untuk menjalankan tes.
