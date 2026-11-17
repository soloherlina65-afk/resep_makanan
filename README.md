# Racikara

Racikara adalah aplikasi berbagi resep masakan berbasis Laravel. Pengunjung dapat menjelajahi resep, sementara pengguna terdaftar dapat menyimpan resep favorit, menyusun menu mingguan, berbagi resep, serta berinteraksi dengan pengguna lain.

> **Status:** source code dapat dipasang pada hosting yang mendukung Laravel. Untuk menayangkan aplikasi, siapkan server, domain, database, email, dan environment produksi sendiri; repository ini tidak menyertakan kredensial atau layanan hosting.

## Fitur

- Jelajahi, cari, dan filter resep berdasarkan nama, bahan, kategori, waktu memasak, dan tingkat kesulitan.
- Lihat detail resep, bahan, langkah memasak, rating, dan komentar.
- Daftar, masuk, verifikasi email, reset kata sandi, dan login Google opsional.
- Simpan resep favorit, beri like, rating, dan komentar.
- Buat, edit, dan hapus resep sendiri beserta foto, bahan, dan langkahnya.
- Susun menu mingguan dan daftar belanja.
- Kelola profil, preferensi notifikasi, notifikasi, serta ikuti pengguna lain.
- Gunakan profil pengguna dan daftar followers/following yang dapat dilihat publik.
- Akses dashboard admin dan pengelolaan resep, kategori, pengguna, moderasi komentar, anggota tim, notifikasi, pengaturan, dan aktivitas.

## Teknologi

- Laravel 10
- PHP 8.1 atau lebih baru
- MySQL/MariaDB untuk deployment (SQLite in-memory digunakan pada tes)
- Vite, Tailwind CSS, dan JavaScript

## Persyaratan

- PHP 8.1+ dengan ekstensi Laravel yang dibutuhkan, termasuk PDO (PHP Data Objects) untuk database
- Composer
- Node.js dan npm
- MySQL/MariaDB atau database Laravel yang kompatibel
- PDO SQLite jika ingin menjalankan tes

## Instalasi lokal

1. Clone repository, lalu masuk ke direktori proyek:

   ```sh
   git clone https://github.com/soloherlina65-afk/resep_makanan.git
   cd resep_makanan
   ```

2. Pasang dependensi:

   ```sh
   composer install
   npm install
   ```

3. Buat file environment lokal dan application key:

   ```sh
   cp .env.example .env
   php artisan key:generate
   ```

   Di Windows PowerShell, gunakan:

   ```powershell
   Copy-Item .env.example .env
   php artisan key:generate
   ```

4. Buat database, lalu atur `DB_*` pada `.env` sesuai konfigurasi lokal. Terapkan migrasi:

   ```sh
   php artisan migrate
   ```

   Jangan gunakan `migrate:fresh` pada database yang memiliki data karena perintah itu menghapus seluruh tabel.

5. Siapkan penyimpanan foto dan aset frontend:

   ```sh
   php artisan storage:link
   npm run build
   ```

6. Jalankan aplikasi lokal:

   ```sh
   php artisan serve
   ```

   Untuk hot reload aset selama pengembangan, jalankan `npm run dev` di terminal terpisah.

## Role dan administrasi

Setiap pendaftaran baru mendapat role `user`. Role admin tidak dapat dipilih melalui form registrasi. Akun lama yang ditandai sebagai admin dipertahankan oleh migrasi role.

Route `/admin/*` dibatasi middleware admin. Policy dan validasi server membatasi pengguna biasa untuk mengubah resep dan komentar milik pengguna lain. Admin dapat membuka `/admin/dashboard` setelah masuk.

Untuk instalasi baru yang belum memiliki admin:

1. Daftarkan akun melalui aplikasi.
2. Promosikan akun pertama melalui database dengan email akun yang benar:

   ```sql
   UPDATE users
   SET role = 'admin', is_admin = 1
   WHERE email = 'email-admin-anda@example.com';
   ```

3. Masuk kembali dan buka `/admin/dashboard`. Setelah admin pertama dibuat, role dapat dikelola melalui `/admin/users`.

Sistem mencegah penghapusan atau penurunan role admin terakhir.

## Pengaturan opsional

- **Google OAuth:** isi `GOOGLE_CLIENT_ID`, `GOOGLE_CLIENT_SECRET`, dan `GOOGLE_REDIRECT_URI`. Daftarkan callback URL yang sama di Google OAuth Console, yaitu `${APP_URL}/auth/google/callback`.
- **Email dan form kontak:** konfigurasikan `MAIL_*` dan `CONTACT_TO_ADDRESS`. Tanpa penerima dan layanan SMTP yang berfungsi, aplikasi tidak dapat mengirim pesan kontak.
- **Foto unggahan:** file publik disimpan pada disk `public` dan dilayani melalui `public/storage`. Jalankan `php artisan storage:link` pada mesin deployment.

Jangan commit `.env`, application key, kata sandi, kredensial OAuth, atau rahasia lain.

## Deployment produksi

Repository menyediakan [`.env.production.example`](./.env.production.example) sebagai template non-rahasia. Semua nilai contoh di dalamnya harus diganti di server. Template ini tidak membuat aplikasi otomatis tersedia di internet.

1. Pilih hosting yang mendukung persyaratan PHP proyek. Arahkan document root domain ke direktori `public` Laravel—jangan arahkan ke root repository.
2. Salin `.env.production.example` menjadi `.env` **di server**, atau masukkan nilainya melalui panel environment hosting. Isi domain HTTPS, `APP_KEY`, koneksi database produksi, SMTP, dan integrasi OAuth bila digunakan.
3. Untuk instalasi baru, hasilkan `APP_KEY` satu kali:

   ```sh
   php artisan key:generate --force
   ```

   Jangan mengganti key pada aplikasi yang sudah aktif tanpa rencana migrasi karena data/session terenkripsi mungkin tidak dapat dibaca.

4. Pasang dependensi dan build aset:

   ```sh
   composer install --no-dev --optimize-autoloader
   npm ci
   npm run build
   ```

5. Pastikan backup database tersedia, kemudian jalankan:

   ```sh
   php artisan migrate --force
   php artisan storage:link
   php artisan optimize
   ```

6. Verifikasi konfigurasi produksi:
   - `APP_ENV=production`
   - `APP_DEBUG=false`
   - `APP_URL` menggunakan domain HTTPS yang benar
   - `SESSION_SECURE_COOKIE=true`
   - Database dan SMTP dapat diakses dari hosting.
   - Proses web dapat menulis ke `storage` dan `bootstrap/cache`.
   - HTTPS aktif dan perpanjangan sertifikat terurus.

7. Setelah deployment, uji halaman publik, registrasi/login, reset password, form kontak, upload foto, serta dashboard admin. Siapkan backup dan pemantauan log rutin.

`public/robots.txt` mengizinkan crawler mengunjungi situs. Izin ini tidak menjamin mesin pencari akan mengindeksnya; pastikan domain publik benar dan halaman dapat diakses sebelum mengirim sitemap ke layanan webmaster.

## Pengujian

Tes menggunakan SQLite in-memory sesuai konfigurasi `phpunit.xml`, sehingga tidak mengubah database aplikasi:

```sh
php artisan test
```

Build aset produksi:

```sh
npm run build
```

## Lisensi dan kontribusi

Periksa lisensi dan ketentuan penggunaan sebelum menggunakan atau mendistribusikan kode. Untuk laporan bug atau usulan fitur, silakan buat issue di repository GitHub.
