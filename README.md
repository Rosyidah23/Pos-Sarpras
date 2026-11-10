# POS-SARPRAS — SMKN 5 Surakarta

Sistem pelaporan sarana & prasarana berbasis Laravel 11. Siswa dan guru bisa melapor
kerusakan fasilitas, petugas bisa memverifikasi, mengubah status, mengelola
data master, dan mengekspor laporan ke CSV.

## Menjalankan lokal (PHP 8.2+, Composer, dan MySQL/MariaDB)

Database aplikasi menggunakan dump `pos_sarpras.sql`. Buat database
`pos_sarpras` dengan charset `utf8mb4`, lalu import `pos_sarpras.sql` melalui
phpMyAdmin atau MySQL. Jangan jalankan `migrate:fresh` pada database yang berisi
data penting karena perintah tersebut menghapus semua tabel dan data.

```bash
composer install
php artisan storage:link
php artisan optimize:clear
php artisan serve
```

Jika `.env` belum ada, buat salinannya dari `.env.example` (di PowerShell:
`Copy-Item .env.example .env`). Jangan timpa `.env` yang sudah ada. Jalankan
`php artisan key:generate` hanya sekali jika `APP_KEY` masih kosong.
Atur `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, dan `DB_PASSWORD` di
`.env` agar cocok dengan MySQL lokal. Pada XAMPP standar, biasanya host
`127.0.0.1`, port `3306`, user `root`, dan password kosong. Jika `.env` sudah
ada, pertahankan `APP_KEY` yang ada dan jangan menyalinnya ulang.

Buka `http://localhost:8000` di browser.

### Hosting

1. Arahkan document root/domain ke folder `public` aplikasi, bukan folder root
   proyek. Pastikan hosting memakai PHP 8.2+ beserta ekstensi PHP yang
   dibutuhkan Laravel 11.
2. Buat database MySQL/MariaDB dan import `pos_sarpras.sql`. Isi variabel
   `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, dan `DB_PASSWORD` di
   `.env` menggunakan kredensial dari hosting. Gunakan `SESSION_DRIVER=file`
   kecuali tabel `sessions` sudah tersedia dan memang ingin dipakai.
3. Isi `.env` produksi dengan `APP_ENV=production`, `APP_DEBUG=false`,
   `APP_URL=https://domain-anda`, dan `APP_KEY` yang dibuat satu kali dengan
   `php artisan key:generate`. Jangan bagikan atau mengganti `APP_KEY` setelah
   aplikasi mulai dipakai.
4. Pasang dependency produksi dan siapkan cache/link storage:

```bash
composer install --no-dev --optimize-autoloader
php artisan optimize:clear
php artisan storage:link
php artisan config:cache
```

5. Beri hak tulis yang diperlukan kepada user web server untuk folder
   `storage` dan `bootstrap/cache`. Jangan menggunakan permission `777`.

Dump SQL sudah berisi struktur tabel dan data awal; proyek ini tidak
menyertakan migration/seeders, jadi jangan menjalankan `php artisan migrate`
atau `db:seed` saat menyiapkan database.

### Deploy di EthioDeploy

1. Tambahkan add-on **MySQL** pada proyek EthioDeploy, lalu impor
   `pos_sarpras.sql` melalui tab **Database → Backups → Import a SQL file**.
   Pilih opsi untuk memulai dari database kosong agar tabel dari dump dapat
   dibuat.
2. Pada pengaturan environment proyek, set `DB_CONNECTION=mysql`,
   `SESSION_DRIVER=file`, `APP_ENV=production`, `APP_DEBUG=false`, dan
   `APP_URL` ke URL HTTPS proyek. EthioDeploy membuat `APP_KEY` otomatis saat
   deploy pertama. Add-on MySQL menyediakan `DATABASE_URL`; konfigurasi
   aplikasi sudah menggunakan nilai itu sebagai fallback `DB_URL`.
3. Deploy ulang setelah add-on selesai dibuat dan variabel environment siap.
   EthioDeploy menyiapkan `storage:link`, cache Laravel, PHP-FPM, dan nginx
   secara otomatis. Atur **Health Check Path** proyek ke `/up` (bukan `/`):
   `/` mengalihkan ke halaman login, yang membutuhkan koneksi database.

EthioDeploy menggunakan filesystem sementara. Foto laporan yang diunggah dapat
hilang saat deploy baru; gunakan object storage S3-compatible jika foto harus
tetap tersimpan setelah deploy.

## Akun yang tersedia di dump SQL

Gunakan akun yang memang tersedia pada dump SQL yang diimpor. Jika password
akun tidak diketahui, atur ulang melalui database atau buat akun baru dari
fitur petugas setelah login; jangan mengasumsikan password demo.

## Fitur

- **Siswa**: login, buat laporan (kategori, lokasi, deskripsi, foto bukti —
  semua wajib diisi), lihat riwayat & status laporan sendiri, lihat denah.
- **Guru**: sama seperti siswa (ikut melapor), akunnya dibuat petugas.
- **Petugas**: ringkasan dashboard, kelola akun siswa & guru (buat akun,
  pilih peran, ganti password, hapus, plus pencarian nama/email & filter peran;
  bisa juga impor ratusan akun sekaligus dengan upload file Excel .xlsx / Word .docx), verifikasi laporan (ubah status
  Menunggu/Diproses/Selesai/Ditolak + catatan tindak lanjut), kelola master
  data kategori kerusakan, kelola master data lokasi/ruangan (sesuai denah
  asli sekolah, bisa tambah/ubah/hapus; semua ruangan di denah bisa diklik), export laporan ke CSV.

## Struktur

- `routes/web.php` — semua route aplikasi.
- `app/Http/Controllers/AuthController.php` — login/logout.
- `app/Http/Controllers/ReportController.php` — buat laporan, riwayat, export CSV.
- `app/Http/Controllers/VerificationController.php` — verifikasi & ubah status laporan (petugas).
- `app/Http/Controllers/CategoryController.php`, `LocationController.php` — CRUD master data (petugas).
- `app/Http/Controllers/DashboardController.php` — dashboard siswa & petugas.
- `app/Models` — `User`, `Category`, `Location`, `Report`.
- `pos_sarpras.sql` — struktur dan data database MySQL/MariaDB.
- `resources/views` — semua tampilan Blade, satu sidebar partial dipakai
  bersama di semua halaman dashboard.
- `public/css/pos-sarpras.css` — styling (font Poppins, responsif termasuk
  breakpoint khusus ukuran laptop).
