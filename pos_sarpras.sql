-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 04 Nov 2026 pada 02.58
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pos_sarpras`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `categories`
--

INSERT INTO `categories` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Listrik & Lampu', '2026-10-21 05:29:50', '2026-10-21 05:29:50'),
(2, 'Kamar Mandi & Air', '2026-10-21 05:29:50', '2026-10-21 05:29:50'),
(3, 'Meja & Kursi', '2026-10-21 05:29:50', '2026-10-21 05:29:50'),
(4, 'Komputer & LCD', '2026-10-21 05:29:50', '2026-10-21 05:29:50'),
(5, 'Kebersihan', '2026-10-21 05:29:50', '2026-10-21 05:29:50'),
(6, 'Bangunan (Tembok, Atap, Lantai)', '2026-10-21 05:29:50', '2026-10-21 05:29:50'),
(7, 'Internet / WiFi', '2026-10-21 05:29:50', '2026-10-21 05:29:50'),
(8, 'Alat Praktik / Bengkel', '2026-10-21 05:29:50', '2026-10-21 05:29:50');

-- --------------------------------------------------------

--
-- Struktur dari tabel `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `locations`
--

CREATE TABLE `locations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `locations`
--

INSERT INTO `locations` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Rg 1.01', '2026-10-21 05:29:50', '2026-10-21 05:29:50'),
(2, 'Rg 1.02', '2026-10-21 05:29:50', '2026-10-21 05:29:50'),
(3, 'Rg 1.03', '2026-10-21 05:29:50', '2026-10-21 05:29:50'),
(4, 'Rg 1.04', '2026-10-21 05:29:50', '2026-10-21 05:29:50'),
(5, 'Rg 1.05', '2026-10-21 05:29:50', '2026-10-21 05:29:50'),
(6, 'Rg 1.06', '2026-10-21 05:29:50', '2026-10-21 05:29:50'),
(7, 'Rg 1.07', '2026-10-21 05:29:50', '2026-10-21 05:29:50'),
(8, 'Rg 1.08', '2026-10-21 05:29:50', '2026-10-21 05:29:50'),
(9, 'Rg 1.09', '2026-10-21 05:29:50', '2026-10-21 05:29:50'),
(10, 'Rg 1.10', '2026-10-21 05:29:50', '2026-10-21 05:29:50'),
(11, 'Rg 1.11', '2026-10-21 05:29:50', '2026-10-21 05:29:50'),
(12, 'Rg 1.12', '2026-10-21 05:29:50', '2026-10-21 05:29:50'),
(13, 'Rg 1.18', '2026-10-21 05:29:50', '2026-10-21 05:29:50'),
(14, 'Rg 1.19', '2026-10-21 05:29:50', '2026-10-21 05:29:50'),
(15, 'Rg 1.20', '2026-10-21 05:29:50', '2026-10-21 05:29:50'),
(16, 'Rg 1.21', '2026-10-21 05:29:50', '2026-10-21 05:29:50'),
(17, 'Rg 2.01 (AXIOO)', '2026-10-21 05:29:50', '2026-10-21 05:29:50'),
(18, 'Rg 2.02 (AXIOO)', '2026-10-21 05:29:50', '2026-10-21 05:29:50'),
(19, 'Rg 2.03 (AXIOO)', '2026-10-21 05:29:50', '2026-10-21 05:29:50'),
(20, 'Rg 2.04', '2026-10-21 05:29:50', '2026-10-21 05:29:50'),
(21, 'Rg 2.05', '2026-10-21 05:29:50', '2026-10-21 05:29:50'),
(22, 'Rg 2.06', '2026-10-21 05:29:50', '2026-10-21 05:29:50'),
(23, 'Rg 2.07', '2026-10-21 05:29:50', '2026-10-21 05:29:50'),
(24, 'Rg 2.08', '2026-10-21 05:29:50', '2026-10-21 05:29:50'),
(25, 'Rg 2.09', '2026-10-21 05:29:50', '2026-10-21 05:29:50'),
(26, 'Rg 2.10', '2026-10-21 05:29:50', '2026-10-21 05:29:50'),
(27, 'Rg 2.11', '2026-10-21 05:29:50', '2026-10-21 05:29:50'),
(28, 'Rg 2.12', '2026-10-21 05:29:50', '2026-10-21 05:29:50'),
(29, 'Rg 2.13', '2026-10-21 05:29:50', '2026-10-21 05:29:50'),
(30, 'Rg 2.14', '2026-10-21 05:29:50', '2026-10-21 05:29:50'),
(31, 'Rg 2.15', '2026-10-21 05:29:50', '2026-10-21 05:29:50'),
(32, 'Rg 2.16', '2026-10-21 05:29:50', '2026-10-21 05:29:50'),
(33, 'Rg 2.17', '2026-10-21 05:29:50', '2026-10-21 05:29:50'),
(34, 'Rg 2.18', '2026-10-21 05:29:50', '2026-10-21 05:29:50'),
(35, 'Rg 2.20', '2026-10-21 05:29:50', '2026-10-21 05:29:50'),
(36, 'Rg 2.21', '2026-10-21 05:29:50', '2026-10-21 05:29:50'),
(37, 'Rg 2.22', '2026-10-21 05:29:50', '2026-10-21 05:29:50'),
(38, 'Rg 2.23', '2026-10-21 05:29:50', '2026-10-21 05:29:50'),
(39, 'Rg 2.24', '2026-10-21 05:29:50', '2026-10-21 05:29:50'),
(40, 'Rg 2.25', '2026-10-21 05:29:50', '2026-10-21 05:29:50'),
(41, 'RPS TKR', '2026-10-21 05:29:50', '2026-10-21 05:29:50'),
(42, 'RPS TKP', '2026-10-21 05:29:50', '2026-10-21 05:29:50'),
(43, 'RPS Teknik Otomotif', '2026-10-21 05:29:50', '2026-10-21 05:29:50'),
(44, 'RPS Teknik Pemesinan', '2026-10-21 05:29:50', '2026-10-21 05:29:50'),
(45, 'RPS TSM 1', '2026-10-21 05:29:50', '2026-10-21 05:29:50'),
(46, 'RPS TSM 2', '2026-10-21 05:29:50', '2026-10-21 05:29:50'),
(47, 'RPS TSM 3', '2026-10-21 05:29:50', '2026-10-21 05:29:50'),
(48, 'Bengkel Listrik 2', '2026-10-21 05:29:50', '2026-10-21 05:29:50'),
(49, 'Bengkel Listrik 3 (IML)', '2026-10-21 05:29:50', '2026-10-21 05:29:50'),
(50, 'Bengkel Listrik 5', '2026-10-21 05:29:50', '2026-10-21 05:29:50'),
(51, 'Bengkel Listrik 6 (ITL)', '2026-10-21 05:29:50', '2026-10-21 05:29:50'),
(52, 'Bengkel DLE', '2026-10-21 05:29:50', '2026-10-21 05:29:50'),
(53, 'Lab. Fisika', '2026-10-21 05:29:50', '2026-10-21 05:29:50'),
(54, 'RPS Elektronika', '2026-10-21 05:29:50', '2026-10-21 05:29:50'),
(55, 'Bengkel Elektro 1', '2026-10-21 05:29:50', '2026-10-21 05:29:50'),
(56, 'Bengkel Elektro 2', '2026-10-21 05:29:50', '2026-10-21 05:29:50'),
(57, 'Bengkel Elektro 3', '2026-10-21 05:29:50', '2026-10-21 05:29:50'),
(58, 'Bengkel Elektro 4', '2026-10-21 05:29:50', '2026-10-21 05:29:50'),
(59, 'Bengkel Otomasi 1', '2026-10-21 05:29:50', '2026-10-21 05:29:50'),
(60, 'Bengkel Otomasi 2', '2026-10-21 05:29:50', '2026-10-21 05:29:50'),
(61, 'Bengkel SKE', '2026-10-21 05:29:50', '2026-10-21 05:29:50'),
(62, 'Bengkel PSA', '2026-10-21 05:29:50', '2026-10-21 05:29:50'),
(63, 'Bengkel SKT', '2026-10-21 05:29:50', '2026-10-21 05:29:50'),
(64, 'Bengkel SKEE', '2026-10-21 05:29:50', '2026-10-21 05:29:50'),
(65, 'Lab. ASM 1 (R.1.13)', '2026-10-21 05:29:50', '2026-10-21 05:29:50'),
(66, 'Lab. ASM 2 (R.1.14)', '2026-10-21 05:29:50', '2026-10-21 05:29:50'),
(67, 'Lab. ASM 3 (R.1.15)', '2026-10-21 05:29:50', '2026-10-21 05:29:50'),
(68, 'T. ASM 1 (R.1.16)', '2026-10-21 05:29:50', '2026-10-21 05:29:50'),
(69, 'T. ASM 2 (R.2.19)', '2026-10-21 05:29:50', '2026-10-21 05:29:50'),
(70, 'R. Gambar DPIB Lt.1', '2026-10-21 05:29:50', '2026-10-21 05:29:50'),
(71, 'Lab. Komputer DPIB Lt.2', '2026-10-21 05:29:50', '2026-10-21 05:29:50'),
(72, 'Lab. RPL 1', '2026-10-21 05:29:51', '2026-10-21 05:29:51'),
(73, 'Lab. RPL 2', '2026-10-21 05:29:51', '2026-10-21 05:29:51'),
(74, 'Lab. RPL 3', '2026-10-21 05:29:51', '2026-10-21 05:29:51'),
(75, 'Lab. RPL 4', '2026-10-21 05:29:51', '2026-10-21 05:29:51'),
(76, 'Lab. Multimedia', '2026-10-21 05:29:51', '2026-10-21 05:29:51'),
(77, 'Lab. KKP 1', '2026-10-21 05:29:51', '2026-10-21 05:29:51'),
(78, 'Lab. KKPI 2', '2026-10-21 05:29:51', '2026-10-21 05:29:51'),
(79, 'Lab. B. Inggris 1', '2026-10-21 05:29:51', '2026-10-21 05:29:51'),
(80, 'Lab. B. Inggris 2', '2026-10-21 05:29:51', '2026-10-21 05:29:51'),
(81, 'Lab. B. Inggris 3', '2026-10-21 05:29:51', '2026-10-21 05:29:51'),
(82, 'Ruang Perpustakaan', '2026-10-21 05:29:51', '2026-10-21 05:29:51'),
(83, 'Masjid', '2026-10-21 05:29:51', '2026-10-21 05:29:51'),
(84, 'UKS', '2026-10-21 05:29:51', '2026-10-21 05:29:51'),
(85, 'R. OSIS', '2026-10-21 05:29:51', '2026-10-21 05:29:51'),
(86, 'Showroom', '2026-10-21 05:29:51', '2026-10-21 05:29:51'),
(87, 'WC dekat Ruang Aula', '2026-10-21 05:29:51', '2026-10-21 05:29:51'),
(88, 'WC dekat Rg 2.12', '2026-10-21 05:29:51', '2026-10-21 05:29:51'),
(89, 'R. Serbaguna/ Indoor', '2026-11-10 08:00:00', '2026-11-10 08:00:00'),
(90, 'L4 Listrk', '2026-11-10 08:00:00', '2026-11-10 08:00:00'),
(91, 'Ruang Guru Listrik', '2026-11-10 08:00:00', '2026-11-10 08:00:00'),
(92, 'RPS TSM Lt. 1', '2026-11-10 08:00:00', '2026-11-10 08:00:00'),
(93, 'Doorlop', '2026-11-10 08:00:00', '2026-11-10 08:00:00'),
(94, 'R. Guru', '2026-11-10 08:00:00', '2026-11-10 08:00:00'),
(95, 'R. TEFA', '2026-11-10 08:00:00', '2026-11-10 08:00:00'),
(96, 'Ruang Guru NK', '2026-11-10 08:00:00', '2026-11-10 08:00:00'),
(97, 'R. BP/BK', '2026-11-10 08:00:00', '2026-11-10 08:00:00'),
(98, 'Ruang Waka 2', '2026-11-10 08:00:00', '2026-11-10 08:00:00'),
(99, 'R. Guru PPLG', '2026-11-10 08:00:00', '2026-11-10 08:00:00'),
(100, 'WKS WMM', '2026-11-10 08:00:00', '2026-11-10 08:00:00'),
(101, 'R. Dapodik', '2026-11-10 08:00:00', '2026-11-10 08:00:00'),
(102, 'R. Seni Rupa', '2026-11-10 08:00:00', '2026-11-10 08:00:00'),
(103, 'WKS 4', '2026-11-10 08:00:00', '2026-11-10 08:00:00'),
(104, 'LSP P1', '2026-11-10 08:00:00', '2026-11-10 08:00:00'),
(105, 'R. Satpam', '2026-11-10 08:00:00', '2026-11-10 08:00:00'),
(106, 'Ruang Aula', '2026-11-10 08:00:00', '2026-11-10 08:00:00'),
(107, 'WKS SDM', '2026-11-10 08:00:00', '2026-11-10 08:00:00'),
(108, 'WKS 3', '2026-11-10 08:00:00', '2026-11-10 08:00:00'),
(109, 'R. Lobby', '2026-11-10 08:00:00', '2026-11-10 08:00:00'),
(110, 'R. Meeting', '2026-11-10 08:00:00', '2026-11-10 08:00:00'),
(111, 'Ruang KS', '2026-11-10 08:00:00', '2026-11-10 08:00:00'),
(112, 'Ruang TU', '2026-11-10 08:00:00', '2026-11-10 08:00:00'),
(113, 'Gudang', '2026-11-10 08:00:00', '2026-11-10 08:00:00'),
(114, 'WKS 1', '2026-11-10 08:00:00', '2026-11-10 08:00:00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2024_01_01_000000_create_users_table', 1),
(2, '2024_01_01_000001_create_categories_and_locations_tables', 1),
(3, '2024_01_01_000002_create_reports_table', 1),
(4, '2024_01_01_000003_create_cache_table', 1),
(5, '2024_01_01_000004_create_jobs_table', 1),
(6, '2024_01_01_000005_create_sessions_table', 2),
(7, '2026_11_04_000001_add_denah_locations', 3);

-- --------------------------------------------------------

--
-- Struktur dari tabel `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `reports`
--

CREATE TABLE `reports` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `location_id` bigint(20) UNSIGNED NOT NULL,
  `handled_by` bigint(20) UNSIGNED DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `photo_path` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'menunggu',
  `follow_up_note` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `reports`
--

INSERT INTO `reports` (`id`, `user_id`, `category_id`, `location_id`, `handled_by`, `title`, `description`, `photo_path`, `status`, `follow_up_note`, `created_at`, `updated_at`) VALUES
(1, 34, 8, 59, 1, 'bhfewdns', 'dnsam', 'reports/9JTQBx9f8D526XzT54VrUYjQ70SALAqVbYZMxQ6n.png', 'selesai', NULL, '2026-09-10 10:01:37', '2026-09-10 14:30:02'),
(2, 34, 8, 58, 1, 'kabel', 'kabelnya rusak', 'reports/P7YZ4biTpRObzCGiVIPF4vhS8WMDbT9bI4RheSSX.png', 'selesai', NULL, '2026-09-15 13:29:19', '2026-09-15 13:30:16'),
(3, 34, 7, 75, 1, 'internet ngelag', 'wifi rpl 4 ngelag', 'reports/hWF5L0UCoAJjcS4jAbP9dJYcIwHhL6230UZJB0Yp.png', 'diproses', NULL, '2026-09-23 06:22:52', '2026-09-23 06:24:07'),
(4, 34, 8, 55, 1, 'alat rusak', 'kabel terputus', 'reports/O7kNekG5ZA8kDmLmODaDFbCChmldrb4P1GbuuFyC.png', 'diproses', NULL, '2026-10-21 03:41:45', '2026-10-21 03:42:55'),
(5, 34, 6, 52, 1, 'atap', 'atap rusak', 'reports/JF9MApJlwi3DXzOQQdsH5vb9ME6wjAJ2HVxVQPC6.png', 'menunggu', NULL, '2026-10-22 01:10:59', '2026-10-22 01:12:25'),
(6, 34, 8, 65, 1, 'Alat Praktik', 'Mouse Hilang', 'reports/0v4gjR0YEsXVMse0JTmmq18LWyJmIq96aalP5YdA.jpg', 'selesai', 'sudah diganti dengan mouse baru ya', '2026-10-22 08:05:12', '2026-10-22 08:09:46'),
(7, NULL, 8, 2, 1, 'mouse', 'mouse nomr 29 baterainya habis', 'reports/3ttFPKLfHX6KCP0E3Iiy0T8jcQBBIzUto50FMRzC.jpg', 'selesai', NULL, '2026-11-02 02:52:19', '2026-11-02 02:54:47'),
(8, 34, 1, 73, 1, 'lampu sudah mati idak bisa nyala', 'blalallala', 'reports/Pxa5Uy2K5XbPqEPs34eOS0TRGjREW3uTd3uonVVJ.jpg', 'selesai', 'jaga baik2', '2026-11-03 06:56:40', '2026-11-03 07:00:09'),
(9, 34, 8, 15, 1, 'mouse', 'mousenya baterainya habis', 'reports/4cifoLAnszSyFWBsrCR5AqGG6WUWG96uXdfOIVfp.jpg', 'selesai', NULL, '2026-11-04 01:48:18', '2026-11-04 01:49:51');

-- --------------------------------------------------------

--
-- Struktur dari tabel `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'siswa',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Petugas Sarpras', 'petugas@smkn5surakarta.sch.id', NULL, '$2y$12$5jZKSuQSDqSNT8yP4vINyeaGr6IUCnCZpYn5.z.uOrKOuo3VTs58C', 'petugas', NULL, '2026-10-21 05:29:45', '2026-10-21 05:29:45'),
(2, 'Anisa Kusumastuti', 'anisa.kusumastuti@smkn5surakarta.sch.id', NULL, '$2y$12$96eP0IC/b/rorSAbVWEv4.HNZ/ZKCs7ytRODJ/b3KWQW8XHCCQGR.', 'guru', NULL, '2026-11-10 08:00:00', '2026-11-10 08:00:00'),
(3, 'Agus Maryanto', 'agus.maryanto@smkn5surakarta.sch.id', NULL, '$2y$12$o5nrFpl8BmQRJ/d9OAzTD.3Z4M9A5hdxuk02gJRkVSsqZhJAaigMm', 'guru', NULL, '2026-11-10 08:00:00', '2026-11-10 08:00:00'),
(4, 'Anies Khoerun Nisa', 'anies.khoerun@smkn5surakarta.sch.id', NULL, '$2y$12$78z.YN4R1QW/8cHF8XlGsuXhPlrzAjB/G3Q2PDciOJUfjzKLTkZRy', 'guru', NULL, '2026-11-10 08:00:00', '2026-11-10 08:00:00'),
(5, 'Hilma Gustami', 'hilma.gustami@smkn5surakarta.sch.id', NULL, '$2y$12$OYEscx2r49ctsdErq0Vw.OFWELDfJ3ffsRrIxoQwZPF8VRBcEbt.q', 'guru', NULL, '2026-11-10 08:00:00', '2026-11-10 08:00:00'),
(6, 'Dwi Esti', 'dwi.esti@smkn5surakarta.sch.id', NULL, '$2y$12$/E3Bn1dN1O2zXKj1WRM88ezZIkuOb1ZdtADbcUydr7xHgdAY/hPb2', 'guru', NULL, '2026-11-10 08:00:00', '2026-11-10 08:00:00'),
(7, 'Kurniasari', 'kurniasari@smkn5surakarta.sch.id', NULL, '$2y$12$XLwGxb/7uCeZyiFbWQEpFOjRvZHFc.f2shyVTHf6JfY20IvhY6w6u', 'guru', NULL, '2026-11-10 08:00:00', '2026-11-10 08:00:00'),
(8, 'Abror Abiyyi', 'abror.abiyyi@smkn5surakarta.sch.id', NULL, '$2y$12$xVaL1tqmj99U5P1J4AEqUO8sewUOMwQBVODUso9Gpr8M/as1Nt8Fi', 'siswa', NULL, '2026-11-10 08:00:00', '2026-11-10 08:00:00'),
(9, 'Al Banie Setyawan', 'al.banie@smkn5surakarta.sch.id', NULL, '$2y$12$WC2Vd2e1BWd0m1OO57OuTOFs1nsQzOLraErj61q8v9jC8Csc8.b2e', 'siswa', NULL, '2026-11-10 08:00:00', '2026-11-10 08:00:00'),
(10, 'Aldejan Kovic Putra Sulash', 'aldejan.kovic@smkn5surakarta.sch.id', NULL, '$2y$12$fWmA8xL/SXEKQpYGHbLHNO7IDW7Hq7xE28PZnFvfcF.IEPP7LwOJ.', 'siswa', NULL, '2026-11-10 08:00:00', '2026-11-10 08:00:00'),
(11, 'Aldo Felicia Pratama', 'aldo.felicia@smkn5surakarta.sch.id', NULL, '$2y$12$UWVmvQI3BhDq94YLBJ9G.uVTiFntpnNatOJHXqiFGonG7o6.FJvlG', 'siswa', NULL, '2026-11-10 08:00:00', '2026-11-10 08:00:00'),
(12, 'Alfiko Dafa Arfansyah', 'alfiko.dafa@smkn5surakarta.sch.id', NULL, '$2y$12$oxGo0DggsifAWeRiZZyboOVWfDJTBxuL96qKi7IaOiF53kqNLQS/K', 'siswa', NULL, '2026-11-10 08:00:00', '2026-11-10 08:00:00'),
(13, 'Aliimma Afilla Prima', 'aliimma.afilla@smkn5surakarta.sch.id', NULL, '$2y$12$QHBYOQPr964n8MWPUQQWEO1EQhoohRMlUZtPRlCKX1Ev4T9HBhmZm', 'siswa', NULL, '2026-11-10 08:00:00', '2026-11-10 08:00:00'),
(14, 'Andreas Dwi Prakasa', 'andreas.dwi@smkn5surakarta.sch.id', NULL, '$2y$12$pTdtoVwDA.ZMm7wmmYGktuqvFvnKN3K1Qo32yyu16esReb/8Nr0Kq', 'siswa', NULL, '2026-11-10 08:00:00', '2026-11-10 08:00:00'),
(15, 'Anshar Deas Alif Dzulhijjah', 'anshar.deas@smkn5surakarta.sch.id', NULL, '$2y$12$Ro/9HEEc8LAf0BtoNrl1wO4T3mHt4EVngPw9bP9m.6LsWP4zTyWVO', 'siswa', NULL, '2026-11-10 08:00:00', '2026-11-10 08:00:00'),
(16, 'Ardelia Anggun Saputri', 'ardelia.anggun@smkn5surakarta.sch.id', NULL, '$2y$12$F9Omo9DmcgCf.EdbpzXv.u3vspBP8lGt0c41O5no3emXlWFMoBnhi', 'siswa', NULL, '2026-11-10 08:00:00', '2026-11-10 08:00:00'),
(17, 'Arfath Yoan Ndaru', 'arfath.yoan@smkn5surakarta.sch.id', NULL, '$2y$12$VEaCDJVcpMik4SCJ.TU7aeyZY2aJd6fm7lzcwvkeKuA16W.G/.shW', 'siswa', NULL, '2026-11-10 08:00:00', '2026-11-10 08:00:00'),
(18, 'Aurel Khaira Lubna Sutrisno', 'aurel.khaira@smkn5surakarta.sch.id', NULL, '$2y$12$kPV/5DyHqAfc2ZNyYfxcN.D5296MpMorPKcUe0FSHo03SMS7fgvxK', 'siswa', NULL, '2026-11-10 08:00:00', '2026-11-10 08:00:00'),
(19, 'Bagoes Vernanda', 'bagoes.vernanda@smkn5surakarta.sch.id', NULL, '$2y$12$ZbLu5r6/A3.d.uoJQY9YdOrAoxL9gXuRMZz0BB/NH7THHrxftEhDy', 'siswa', NULL, '2026-11-10 08:00:00', '2026-11-10 08:00:00'),
(20, 'Bimo Nugroho', 'bimo.nugroho@smkn5surakarta.sch.id', NULL, '$2y$12$XDh73X70WC0zj./JWBIr4..HHkPP7t4ohmTJV/FX3VSkd2C5Pkhlu', 'siswa', NULL, '2026-11-10 08:00:00', '2026-11-10 08:00:00'),
(21, 'Dhani Arrgiawan Widiyatmoko', 'dhani.arrgiawan@smkn5surakarta.sch.id', NULL, '$2y$12$RIMM3yCahrx8wLBUaJcDs.VAnHzP2bEkqbvpQxNFWU1ankii66oSq', 'siswa', NULL, '2026-11-10 08:00:00', '2026-11-10 08:00:00'),
(22, 'Fahri Ferdyansyah', 'fahri.ferdyansyah@smkn5surakarta.sch.id', NULL, '$2y$12$WSx7.PccUkTLpGysJ8u0Y.34UIsL1CHBJ1VAVq.un/dc/fE1aAl9u', 'siswa', NULL, '2026-11-10 08:00:00', '2026-11-10 08:00:00'),
(23, 'Ibra Al Tabian', 'ibra.al@smkn5surakarta.sch.id', NULL, '$2y$12$Ar6fv3nY5eTistLxSe0kjOj.vsK.n5NgkmVBt2VLK1Ki3A62VZt0i', 'siswa', NULL, '2026-11-10 08:00:00', '2026-11-10 08:00:00'),
(24, 'Khansa Khairunnisa', 'khansa.khairunnisa@smkn5surakarta.sch.id', NULL, '$2y$12$gRjzxZ41X1AVzosr62i7keznTplN88Wm1JcOjcEfFvresz0HSFIJi', 'siswa', NULL, '2026-11-10 08:00:00', '2026-11-10 08:00:00'),
(25, 'Khansa Qurratu''ain', 'khansa.qurratuain@smkn5surakarta.sch.id', NULL, '$2y$12$lCdG.kQnRaIZtpBiO68DWOnLbamV05NdI1F7Xq9HCamzodLzzLGS2', 'siswa', NULL, '2026-11-10 08:00:00', '2026-11-10 08:00:00'),
(26, 'Marisa Aprilya Hapsari', 'marisa.aprilya@smkn5surakarta.sch.id', NULL, '$2y$12$zVtioHbxvum2MvWh6.6m8OkYJ6ElhH5YNrWMDpQhOxJf7Gneisq8m', 'siswa', NULL, '2026-11-10 08:00:00', '2026-11-10 08:00:00'),
(27, 'Mu''ammar Akyas', 'muammar.akyas@smkn5surakarta.sch.id', NULL, '$2y$12$H5PXLAfHJcfXjavE90.BQ.ulmayUKEO5G4h.SF5RYsinSCpXjnptK', 'siswa', NULL, '2026-11-10 08:00:00', '2026-11-10 08:00:00'),
(28, 'Muchammad Naufal Fariz', 'muchammad.naufal@smkn5surakarta.sch.id', NULL, '$2y$12$IAqLhk5xX.i00gKg2jpOFO4dxuuxu3pMZFnQCl9iRMNtujbVCLM0e', 'siswa', NULL, '2026-11-10 08:00:00', '2026-11-10 08:00:00'),
(29, 'Muhammad Finza Muta''ali', 'muhammad.finza@smkn5surakarta.sch.id', NULL, '$2y$12$N7.l3YXWTrZ5k2zsoph7aeo19.Tv4ixmzA3fE.DvMTINZRd3hcovm', 'siswa', NULL, '2026-11-10 08:00:00', '2026-11-10 08:00:00'),
(30, 'Naufaal Zaidan Aufaa Chandra', 'naufaal.zaidan@smkn5surakarta.sch.id', NULL, '$2y$12$NxB/.O0uUIiRH4ml2lW0h.FQZlBSLDClrwyi68di9M2KsDeAVjXbq', 'siswa', NULL, '2026-11-10 08:00:00', '2026-11-10 08:00:00'),
(31, 'Noufal Rozzak Abizard', 'noufal.rozzak@smkn5surakarta.sch.id', NULL, '$2y$12$fAUYGe9bnfNXmlU6s2TQK.f1oh9eEJqTsfrUfWLRpOcclUesKjsL6', 'siswa', NULL, '2026-11-10 08:00:00', '2026-11-10 08:00:00'),
(32, 'Rafif Bari Muzhafir', 'rafif.bari@smkn5surakarta.sch.id', NULL, '$2y$12$9tmOoADm0SIttXhNF7LVSuouJ3qgDI2QJlYoqsQuy6sJy.YKfuCIW', 'siswa', NULL, '2026-11-10 08:00:00', '2026-11-10 08:00:00'),
(33, 'Rasheesa Shareef Ahmadi', 'rasheesa.shareef@smkn5surakarta.sch.id', NULL, '$2y$12$ZkuBDzQpZFCd8GvjtElgMOsBwLo1jxSri7pl50LHvwb9r7PwMpbZ.', 'siswa', NULL, '2026-11-10 08:00:00', '2026-11-10 08:00:00'),
(34, 'Rosyidah Alif Hakimah', 'rosyidah.alif@smkn5surakarta.sch.id', NULL, '$2y$12$lwXQDEB2k1EoZmBOQ2GmzOE7qRToSZ5uy5JVJ65riO/7GAblZomEy', 'siswa', NULL, '2026-11-10 08:00:00', '2026-11-10 08:00:00'),
(35, 'Salman Alfarizi Bashen', 'salman.alfarizi@smkn5surakarta.sch.id', NULL, '$2y$12$5U0PVU.xWG3tPK/BZKSoh.55XLHVQNpm/uGgpbTiUeehCSXd2hE7y', 'siswa', NULL, '2026-11-10 08:00:00', '2026-11-10 08:00:00'),
(36, 'Tsabitah Qurrata A''yun', 'tsabitah.qurrata@smkn5surakarta.sch.id', NULL, '$2y$12$TfxfnY91Sda1T6DOo7LEqe8FihvcJK3DghjdKdDxJakmLntAPsIwu', 'siswa', NULL, '2026-11-10 08:00:00', '2026-11-10 08:00:00'),
(37, 'Venska Fellicia Pertiwi', 'venska.fellicia@smkn5surakarta.sch.id', NULL, '$2y$12$xIIOOYm.ZLPk92m741JhMuTABMKy3QfHlbAqWG/ywRHW1gbByp1iK', 'siswa', NULL, '2026-11-10 08:00:00', '2026-11-10 08:00:00'),
(38, 'Yuri Aulia Widyadana', 'yuri.aulia@smkn5surakarta.sch.id', NULL, '$2y$12$I255Q2d5iRT/C3OVwHIEnua0IGtKI1Yt31W6u0TqPdx7z0gQ9UJ42', 'siswa', NULL, '2026-11-10 08:00:00', '2026-11-10 08:00:00');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indeks untuk tabel `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indeks untuk tabel `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indeks untuk tabel `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indeks untuk tabel `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `locations`
--
ALTER TABLE `locations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indeks untuk tabel `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reports_user_id_foreign` (`user_id`),
  ADD KEY `reports_category_id_foreign` (`category_id`),
  ADD KEY `reports_location_id_foreign` (`location_id`),
  ADD KEY `reports_handled_by_foreign` (`handled_by`);

--
-- Indeks untuk tabel `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `locations`
--
ALTER TABLE `locations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=115;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `reports`
--
ALTER TABLE `reports`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `reports`
--
ALTER TABLE `reports`
  ADD CONSTRAINT `reports_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  ADD CONSTRAINT `reports_handled_by_foreign` FOREIGN KEY (`handled_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `reports_location_id_foreign` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`),
  ADD CONSTRAINT `reports_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
