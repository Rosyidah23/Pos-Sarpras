# POS-SARPRAS — SMKN 5 Surakarta

Sistem pelaporan sarana & prasarana berbasis Laravel 11. Siswa dan guru bisa melapor
kerusakan fasilitas, petugas bisa memverifikasi, mengubah status, mengelola
data master, dan mengekspor laporan ke CSV.

## Cara menjalankan (di komputer yang sudah ada PHP 8.2+ dan Composer)

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
php artisan serve
```

Kalau sebelumnya sudah pernah migrate dan mau reset total ke data terbaru
(lokasi sesuai denah, banyak akun siswa, dst), pakai `php artisan
migrate:fresh --seed` — **ini akan menghapus semua data lama**.

Buka `http://localhost:8000` di browser.

### Update dari versi sebelumnya (tanpa menghapus data)

Versi ini menambah semua ruangan denah ke daftar lokasi. Cukup jalankan:

```bash
php artisan migrate
```

(Alternatif: `php artisan db:seed --class=LocationSeeder` — aman diulang, tidak membuat duplikat.)

## Akun demo (hasil dari `php artisan migrate --seed`)

| Peran   | Email                                  | Password |
|---------|------------------------------------------|----------|
| Siswa   | `siswa@smkn5surakarta.sch.id` (+ 10 akun siswa lain, lihat `DatabaseSeeder.php`) | `password` |
| Guru    | `guru@smkn5surakarta.sch.id`             | `password` |
| Petugas | `petugas@smkn5surakarta.sch.id`          | `password` |

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
- `database/migrations` — tabel `users`, `categories`, `locations`, `reports`
  (dengan kolom `handled_by` & `follow_up_note` untuk fitur verifikasi).
- `database/seeders/DatabaseSeeder.php` — seed 11 akun siswa + 1 petugas,
  8 kategori, dan ±90 lokasi/ruangan sesuai Denah Ruang SMK Negeri 5
  Surakarta TA 2025/2026.
- `resources/views` — semua tampilan Blade, satu sidebar partial dipakai
  bersama di semua halaman dashboard.
- `public/css/pos-sarpras.css` — styling (font Poppins, responsif termasuk
  breakpoint khusus ukuran laptop).

## Catatan

Proyek ini dirakit ulang secara manual (tanpa menjalankan `composer
create-project`) karena lingkungan penyusunnya tidak punya PHP/Composer
terpasang untuk mengujinya secara langsung. Strukturnya mengikuti skeleton
standar Laravel 11 — kalau setelah `composer install` / `migrate` masih ada
error atau tampilan yang kurang pas, kirim screenshot/pesan errornya dan
akan langsung diperbaiki.
