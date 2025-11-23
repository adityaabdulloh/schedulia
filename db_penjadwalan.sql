-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 22, 2025 at 05:01 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_penjadwalan`
--

-- --------------------------------------------------------

--
-- Table structure for table `absensi`
--

CREATE TABLE `absensi` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `pengampu_id` bigint(20) UNSIGNED NOT NULL,
  `mahasiswa_id` bigint(20) UNSIGNED NOT NULL,
  `tanggal` date DEFAULT NULL,
  `pertemuan` tinyint(3) UNSIGNED NOT NULL,
  `status` enum('hadir','sakit','izin','alpha') NOT NULL,
  `waktu_absen` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `jadwal_kuliah_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `absensi`
--

INSERT INTO `absensi` (`id`, `pengampu_id`, `mahasiswa_id`, `tanggal`, `pertemuan`, `status`, `waktu_absen`, `created_at`, `updated_at`, `jadwal_kuliah_id`) VALUES
(1, 1, 3, '2025-11-17', 1, 'hadir', '2025-11-17 05:20:03', '2025-11-17 05:20:03', '2025-11-17 05:20:03', 8),
(2, 1, 3, '2025-11-17', 2, 'izin', '2025-11-17 05:30:15', '2025-11-17 05:28:53', '2025-11-17 05:30:15', 8),
(3, 1, 3, '2025-11-19', 3, 'hadir', '2025-11-19 06:53:47', '2025-11-18 20:09:17', '2025-11-19 06:53:47', 8),
(4, 1, 3, '2025-11-19', 4, 'hadir', '2025-11-19 07:00:27', '2025-11-19 07:00:27', '2025-11-19 07:00:27', 8),
(5, 1, 3, '2025-11-22', 5, 'hadir', '2025-11-22 08:37:47', '2025-11-22 08:37:47', '2025-11-22 08:37:47', 8),
(6, 1, 3, '2025-11-22', 6, 'izin', '2025-11-22 08:38:34', '2025-11-22 08:38:34', '2025-11-22 08:38:34', 8);

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dosen`
--

CREATE TABLE `dosen` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nip` varchar(255) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `foto_profil` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `prodi_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `dosen`
--

INSERT INTO `dosen` (`id`, `nip`, `nama`, `foto_profil`, `email`, `prodi_id`, `created_at`, `updated_at`, `user_id`) VALUES
(1, '198501012010121001', 'Dr. Budi Santoso, M.Kom.', NULL, 'budi.santoso@example.com', 1, '2025-11-14 08:25:33', '2025-11-14 08:25:33', 2),
(2, '199002022015032002', 'Siti Aminah, S.Kom., M.T.', 'foto_profil/Ao9lZhQvZKCPCH7J8xgKLU8xKSitLsOJOFBhn1y2.jpg', 'siti.aminah@example.com', 3, '2025-11-14 08:25:34', '2025-11-14 20:51:29', 3),
(3, '1990019200192015', 'Pak Harun', 'foto_profil/GvOeWmyJyKrVGdFT8e8IMmYplhWwctkZKWk76yLS.jpg', 'harun@gmail.com', 3, '2025-11-14 09:01:33', '2025-11-16 07:38:20', 7),
(5, '1977019122191021', 'Dr. Budi Santoso, M.Kom.', NULL, 'budisantoso@gmail.com', 3, '2025-11-22 04:48:27', '2025-11-22 04:48:27', 10);

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
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
-- Table structure for table `hari`
--

CREATE TABLE `hari` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama_hari` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hari`
--

INSERT INTO `hari` (`id`, `nama_hari`, `created_at`, `updated_at`) VALUES
(1, 'Senin', '2025-11-14 08:25:33', '2025-11-14 08:25:33'),
(2, 'Selasa', '2025-11-14 08:25:33', '2025-11-14 08:25:33'),
(3, 'Rabu', '2025-11-14 08:25:33', '2025-11-14 08:25:33'),
(4, 'Kamis', '2025-11-14 08:25:33', '2025-11-14 08:25:33'),
(5, 'Jumat', '2025-11-14 08:25:33', '2025-11-14 08:25:33');

-- --------------------------------------------------------

--
-- Table structure for table `jadwal_kuliah`
--

CREATE TABLE `jadwal_kuliah` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `pengampu_id` bigint(20) UNSIGNED NOT NULL,
  `ruang_id` bigint(20) UNSIGNED NOT NULL,
  `hari_id` bigint(20) UNSIGNED NOT NULL,
  `jam_mulai` time NOT NULL,
  `jam_selesai` time NOT NULL,
  `semester` int(11) DEFAULT NULL,
  `tahun_akademik` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `kelas_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jadwal_kuliah`
--

INSERT INTO `jadwal_kuliah` (`id`, `pengampu_id`, `ruang_id`, `hari_id`, `jam_mulai`, `jam_selesai`, `semester`, `tahun_akademik`, `created_at`, `updated_at`, `kelas_id`) VALUES
(8, 1, 5, 1, '10:00:00', '12:30:00', 7, '2025', '2025-11-16 00:01:04', '2025-11-22 06:59:32', 4),
(20, 2, 1, 5, '07:00:00', '10:20:00', 7, '2025/2026', '2025-11-22 08:05:57', '2025-11-22 08:05:57', 4),
(21, 3, 2, 5, '07:00:00', '09:30:00', 1, '2025/2026', '2025-11-22 08:05:57', '2025-11-22 08:05:57', 1),
(22, 4, 1, 3, '07:00:00', '09:30:00', 7, '2025/2026', '2025-11-22 08:05:57', '2025-11-22 08:05:57', 4),
(23, 5, 1, 2, '07:00:00', '10:20:00', 7, '2025/2026', '2025-11-22 08:05:57', '2025-11-22 08:05:57', 4),
(24, 7, 1, 4, '07:00:00', '10:20:00', 7, '2025/2026', '2025-11-22 08:05:57', '2025-11-22 08:05:57', 4),
(25, 8, 1, 4, '11:00:00', '14:20:00', 7, '2025/2026', '2025-11-22 08:05:57', '2025-11-22 08:05:57', 4),
(26, 9, 1, 1, '13:00:00', '16:20:00', 7, '2025/2026', '2025-11-22 08:05:57', '2025-11-22 08:05:57', 4);

-- --------------------------------------------------------

--
-- Table structure for table `jam`
--

CREATE TABLE `jam` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `jam_mulai` time NOT NULL,
  `jam_selesai` time NOT NULL,
  `durasi` int(11) NOT NULL DEFAULT 50,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jam`
--

INSERT INTO `jam` (`id`, `jam_mulai`, `jam_selesai`, `durasi`, `created_at`, `updated_at`) VALUES
(1, '08:00:00', '09:40:00', 100, '2025-11-14 08:25:33', '2025-11-14 08:25:33'),
(2, '10:00:00', '12:30:00', 150, '2025-11-14 08:25:33', '2025-11-14 08:25:33'),
(3, '13:30:00', '15:10:00', 100, '2025-11-14 08:25:33', '2025-11-14 08:25:33'),
(4, '15:30:00', '17:10:00', 100, '2025-11-14 08:25:33', '2025-11-14 08:25:33');

-- --------------------------------------------------------

--
-- Table structure for table `kelas`
--

CREATE TABLE `kelas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama_kelas` varchar(255) NOT NULL,
  `prodi_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kelas`
--

INSERT INTO `kelas` (`id`, `nama_kelas`, `prodi_id`, `created_at`, `updated_at`) VALUES
(1, 'TI-A', 1, '2025-11-14 08:25:35', '2025-11-14 08:25:35'),
(2, 'TI-B', 1, '2025-11-14 08:25:35', '2025-11-14 08:25:35'),
(3, 'SI-A', 2, '2025-11-14 08:25:35', '2025-11-14 08:25:35'),
(4, 'PTI A 22', 3, '2025-11-14 09:00:19', '2025-11-14 09:00:19');

-- --------------------------------------------------------

--
-- Table structure for table `mahasiswa`
--

CREATE TABLE `mahasiswa` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nim` varchar(255) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `foto_profil` varchar(255) DEFAULT NULL,
  `prodi_id` bigint(20) UNSIGNED NOT NULL,
  `semester` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `kelas_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `mahasiswa`
--

INSERT INTO `mahasiswa` (`id`, `nim`, `nama`, `foto_profil`, `prodi_id`, `semester`, `created_at`, `updated_at`, `user_id`, `kelas_id`) VALUES
(1, '220101001', 'Andi Pratama', NULL, 1, 3, '2025-11-14 08:25:34', '2025-11-14 08:25:34', 4, NULL),
(2, '220201001', 'Citra Lestari', NULL, 2, 3, '2025-11-14 08:25:34', '2025-11-14 08:25:34', 5, NULL),
(3, '22050974004', 'aditya', 'FDU8r4H5fquK7B9hm42OGV0GSTLx8ZLzraXJs93D.jpg', 3, 7, '2025-11-14 09:00:55', '2025-11-14 19:02:26', 6, 4),
(4, '22050974032', 'Bagas Rosyidi', NULL, 3, 7, '2025-11-22 03:21:08', '2025-11-22 03:39:36', 9, 4);

-- --------------------------------------------------------

--
-- Table structure for table `matakuliah`
--

CREATE TABLE `matakuliah` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kode_mk` varchar(255) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `sks` int(11) NOT NULL,
  `semester` int(11) NOT NULL,
  `prodi_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `matakuliah`
--

INSERT INTO `matakuliah` (`id`, `kode_mk`, `nama`, `sks`, `semester`, `prodi_id`, `created_at`, `updated_at`, `description`) VALUES
(1, 'TI101', 'Algoritma dan Pemrograman', 3, 1, 1, '2025-11-14 08:25:34', '2025-11-14 08:25:34', NULL),
(2, 'TI102', 'Struktur Data', 3, 2, 1, '2025-11-14 08:25:34', '2025-11-14 08:25:34', NULL),
(4, 'PTI-001', 'data mining', 3, 7, 3, '2025-11-14 08:59:50', '2025-11-14 08:59:50', NULL),
(5, 'PTI-002', 'Statistika', 4, 7, 3, '2025-11-16 00:00:12', '2025-11-19 01:21:43', NULL),
(7, 'PTI-003', 'Manajemen Proyek', 3, 7, 3, '2025-11-22 07:01:58', '2025-11-22 07:01:58', NULL),
(8, 'PTI-004', 'Verval', 4, 7, 3, '2025-11-22 07:55:29', '2025-11-22 07:55:29', NULL),
(9, 'PTI-005', 'Pembasdat', 4, 7, 3, '2025-11-22 07:56:15', '2025-11-22 07:56:15', NULL),
(10, 'PTI-006', 'STKI', 4, 7, 3, '2025-11-22 07:56:52', '2025-11-22 07:56:52', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2024_11_17_100000_create_prodi_table', 1),
(6, '2024_11_17_102247_create_admin_table', 1),
(7, '2024_11_17_102310_create_mahasiswa_table', 1),
(8, '2024_11_17_102320_create_dosen_table', 1),
(9, '2024_11_17_102408_create_matakuliah_table', 1),
(10, '2024_11_17_102707_create_kelas_table', 1),
(11, '2024_11_17_104710_create_hari_table', 1),
(12, '2024_11_17_104808_create_jam_table', 1),
(13, '2024_11_17_104852_create_pengampu_table', 1),
(14, '2024_11_17_104931_create_ruang_table', 1),
(15, '2024_11_20_085621_pengambilan_mk', 1),
(16, '2024_11_26_073546_add_role_to_users_table', 1),
(17, '2024_12_02_051544_create_pengampu_dosen_table', 1),
(18, '2024_12_05_010000_create_jadwal_kuliah_table', 1),
(19, '2025_10_25_054124_add_user_id_to_mahasiswa_table', 1),
(20, '2025_10_26_033341_add_semester_to_mahasiswa_table', 1),
(21, '2025_10_26_040100_add_kelas_id_to_mahasiswa_table', 1),
(22, '2025_10_26_064943_add_semester_and_kelas_id_to_jadwal_kuliah_table', 1),
(23, '2025_10_26_090413_add_foto_profil_to_mahasiswa_table', 1),
(24, '2025_10_27_060141_add_prodi_id_to_pengampu_table', 1),
(25, '2025_10_27_100000_add_foto_profil_to_dosen_table', 1),
(26, '2025_11_01_014358_add_status_to_pengambilan_mk_table', 1),
(27, '2025_11_02_054429_add_status_tahun_akademik_to_pengambilan_mk_table', 1),
(28, '2025_11_02_100000_change_prodi_id_to_not_nullable_in_pengampu_table', 1),
(29, '2025_11_04_011156_create_pengumuman_table', 1),
(30, '2025_11_05_141045_add_description_to_matakuliah_table', 1),
(31, '2025_11_09_140202_add_kapasitas_to_ruang_table', 1),
(32, '2025_11_11_015347_create_absensi_table', 1),
(33, '2025_11_11_021134_add_user_id_to_dosen_table', 1),
(34, '2025_11_11_123728_drop_waktu_shalat_from_jam_table', 1),
(35, '2025_11_11_224942_add_pengampu_id_to_pengambilan_mk_table', 1),
(36, '2025_11_13_144811_drop_dosen_id_from_pengampu_table', 1),
(37, '2025_11_14_151921_change_jam_in_jadwal_kuliah_table', 1),
(38, '2025_11_16_071334_add_kelas_id_to_pengambilan_mk_table', 2),
(39, '2025_11_17_100654_add_tanggal_to_absensi_table', 3),
(40, '2025_11_17_101327_add_jadwal_kuliah_id_to_absensi_table', 4),
(41, '2025_11_17_103023_add_waktu_absen_to_absensi_table', 5);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pengambilan_mk`
--

CREATE TABLE `pengambilan_mk` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `mahasiswa_id` bigint(20) UNSIGNED NOT NULL,
  `matakuliah_id` bigint(20) UNSIGNED NOT NULL,
  `pengampu_id` bigint(20) UNSIGNED DEFAULT NULL,
  `semester` int(11) NOT NULL,
  `tahun_akademik` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `kelas_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pengambilan_mk`
--

INSERT INTO `pengambilan_mk` (`id`, `status`, `mahasiswa_id`, `matakuliah_id`, `pengampu_id`, `semester`, `tahun_akademik`, `created_at`, `updated_at`, `kelas_id`) VALUES
(1, 'approved', 3, 4, 1, 7, NULL, '2025-11-15 20:06:29', '2025-11-15 20:07:29', NULL),
(3, 'pending', 3, 5, 2, 7, NULL, '2025-11-16 00:17:42', '2025-11-16 00:17:42', NULL),
(6, 'pending', 3, 7, 4, 7, NULL, '2025-11-22 07:53:35', '2025-11-22 07:53:35', NULL),
(7, 'pending', 3, 5, 5, 7, NULL, '2025-11-22 07:53:44', '2025-11-22 07:53:44', NULL),
(8, 'pending', 3, 8, 7, 7, NULL, '2025-11-22 08:06:22', '2025-11-22 08:06:22', NULL),
(9, 'pending', 3, 9, 8, 7, NULL, '2025-11-22 08:06:30', '2025-11-22 08:06:30', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `pengampu`
--

CREATE TABLE `pengampu` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `matakuliah_id` bigint(20) UNSIGNED NOT NULL,
  `kelas_id` bigint(20) UNSIGNED NOT NULL,
  `tahun_akademik` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `prodi_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pengampu`
--

INSERT INTO `pengampu` (`id`, `matakuliah_id`, `kelas_id`, `tahun_akademik`, `created_at`, `updated_at`, `prodi_id`) VALUES
(1, 4, 4, '2025', '2025-11-14 09:02:28', '2025-11-14 09:02:28', 3),
(2, 5, 4, '2025/2026', '2025-11-16 00:00:50', '2025-11-16 00:00:50', 3),
(3, 1, 1, '2025/2026', '2025-11-19 04:43:22', '2025-11-19 04:43:22', 1),
(4, 7, 4, '2025/2026', '2025-11-22 07:02:56', '2025-11-22 07:02:56', 3),
(5, 5, 4, '2025/2026', '2025-11-22 07:04:02', '2025-11-22 07:04:02', 3),
(7, 8, 4, '2025/2026', '2025-11-22 08:04:50', '2025-11-22 08:04:50', 3),
(8, 9, 4, '2025/2026', '2025-11-22 08:05:14', '2025-11-22 08:05:14', 3),
(9, 10, 4, '2025/2026', '2025-11-22 08:05:43', '2025-11-22 08:05:43', 3);

-- --------------------------------------------------------

--
-- Table structure for table `pengampu_dosen`
--

CREATE TABLE `pengampu_dosen` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `pengampu_id` bigint(20) UNSIGNED NOT NULL,
  `dosen_id` bigint(20) UNSIGNED NOT NULL,
  `kelas_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pengampu_dosen`
--

INSERT INTO `pengampu_dosen` (`id`, `pengampu_id`, `dosen_id`, `kelas_id`, `created_at`, `updated_at`) VALUES
(1, 1, 3, 4, NULL, NULL),
(2, 1, 2, 4, NULL, NULL),
(3, 2, 3, 4, NULL, NULL),
(4, 2, 2, 4, NULL, NULL),
(5, 3, 1, 1, NULL, NULL),
(6, 4, 2, 4, NULL, NULL),
(7, 5, 5, 4, NULL, NULL),
(8, 5, 2, 4, NULL, NULL),
(10, 7, 2, 4, NULL, NULL),
(11, 7, 5, 4, NULL, NULL),
(12, 8, 3, 4, NULL, NULL),
(13, 8, 5, 4, NULL, NULL),
(14, 9, 3, 4, NULL, NULL),
(15, 9, 2, 4, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `pengumuman`
--

CREATE TABLE `pengumuman` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `jadwal_kuliah_id` bigint(20) UNSIGNED NOT NULL,
  `dosen_id` bigint(20) UNSIGNED NOT NULL,
  `tipe` enum('perubahan','pembatalan','informasi') NOT NULL,
  `pesan` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pengumuman`
--

INSERT INTO `pengumuman` (`id`, `jadwal_kuliah_id`, `dosen_id`, `tipe`, `pesan`, `created_at`, `updated_at`) VALUES
(1, 8, 3, 'perubahan', 'Kelas hari ini diganti', '2025-11-19 07:12:39', '2025-11-19 07:12:39'),
(2, 8, 3, 'informasi', 'tes pengumuman', '2025-11-21 04:58:34', '2025-11-21 04:58:34'),
(3, 8, 3, 'informasi', 'tes', '2025-11-21 05:16:50', '2025-11-21 05:16:50'),
(6, 8, 3, 'informasi', 'tes', '2025-11-21 05:40:32', '2025-11-21 05:40:32'),
(8, 8, 3, 'perubahan', 'diubah ya', '2025-11-21 05:52:24', '2025-11-21 05:52:24'),
(9, 8, 3, 'informasi', 'p info', '2025-11-21 05:56:44', '2025-11-21 05:56:44'),
(10, 8, 3, 'informasi', 'halo', '2025-11-21 06:04:30', '2025-11-21 06:04:30'),
(11, 8, 3, 'informasi', 'tes', '2025-11-21 06:23:02', '2025-11-21 06:23:02'),
(12, 8, 3, 'informasi', 'hai', '2025-11-21 06:56:33', '2025-11-21 06:56:33'),
(13, 8, 3, 'informasi', 'cek', '2025-11-21 07:06:04', '2025-11-21 07:06:04'),
(14, 8, 3, 'informasi', 'p', '2025-11-21 07:07:11', '2025-11-21 07:07:11'),
(15, 8, 3, 'informasi', 'tes', '2025-11-21 19:52:27', '2025-11-21 19:52:27'),
(16, 8, 3, 'informasi', 'tes', '2025-11-21 19:53:26', '2025-11-21 19:53:26'),
(17, 8, 3, 'informasi', 'tes', '2025-11-21 19:55:27', '2025-11-21 19:55:27'),
(18, 8, 3, 'informasi', 'tes', '2025-11-21 20:00:56', '2025-11-21 20:00:56'),
(19, 8, 3, 'informasi', 'tes p', '2025-11-21 20:10:06', '2025-11-21 20:10:06'),
(20, 8, 3, 'perubahan', 'kulah hari ini , diganti jadwalnya menjadi besok', '2025-11-21 20:19:03', '2025-11-21 20:19:03'),
(21, 8, 3, 'perubahan', 'kelas hari ini diganti ke zoom', '2025-11-22 08:41:01', '2025-11-22 08:41:01');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `prodi`
--

CREATE TABLE `prodi` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama_prodi` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `prodi`
--

INSERT INTO `prodi` (`id`, `nama_prodi`, `created_at`, `updated_at`) VALUES
(1, 'Teknik Informatika', '2025-11-14 08:25:33', '2025-11-14 08:25:33'),
(2, 'Sistem Informasi', '2025-11-14 08:25:33', '2025-11-14 08:25:33'),
(3, 'Pendidikan Teknologi Informasi', '2025-11-14 08:25:33', '2025-11-14 08:25:33');

-- --------------------------------------------------------

--
-- Table structure for table `ruang`
--

CREATE TABLE `ruang` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama_ruang` varchar(255) NOT NULL,
  `kapasitas` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ruang`
--

INSERT INTO `ruang` (`id`, `nama_ruang`, `kapasitas`, `created_at`, `updated_at`) VALUES
(1, 'R-101', 40, '2025-11-14 08:25:33', '2025-11-18 20:38:08'),
(2, 'R-102', 40, '2025-11-14 08:25:33', '2025-11-14 08:25:33'),
(3, 'R-201', 35, '2025-11-14 08:25:33', '2025-11-14 08:25:33'),
(4, 'LAB-KOM 1', 30, '2025-11-14 08:25:33', '2025-11-14 08:25:33'),
(5, 'R-103', 40, '2025-11-22 06:51:52', '2025-11-22 06:51:52');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'mahasiswa',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'admin@gmail.com', NULL, '$2y$12$eJciHHXpLMafCWFsGH458.aYtfdf4GqaAL9KLNwqGgBThLNJIYEdi', 'admin', NULL, '2025-11-14 08:25:33', '2025-11-19 00:27:20'),
(2, 'Dr. Budi Santoso, M.Kom.', 'budi.santoso@example.com', NULL, '$2y$12$QcMefz1kypGuALAwxA6at.Ar3zb0A8aGvoef8Fi8tQxTl0y.n0fsW', 'dosen', NULL, '2025-11-14 08:25:33', '2025-11-14 08:25:33'),
(3, 'Siti Aminah, S.Kom., M.T.', 'siti.aminah@example.com', NULL, '$2y$12$5wihetPrq4cQAtcxsr7FROgffR8/A4KHgXV9snypfXhmjIcMweNkm', 'dosen', NULL, '2025-11-14 08:25:34', '2025-11-14 08:25:34'),
(4, 'Andi Pratama', 'andi.pratama@example.com', NULL, '$2y$12$AOJzN4ZoD6fQ3nNsMP27eOJxRrCYHWTrXYBuyzQMsRwR7bMXH1ApW', 'mahasiswa', NULL, '2025-11-14 08:25:34', '2025-11-14 08:25:34'),
(5, 'Citra Lestari', 'citra.lestari@example.com', NULL, '$2y$12$tKQubXZUBPq2I2u9Sbzg3uiDhwkTDEI1kx0cb2wrwnlhFAJ4QoadG', 'mahasiswa', NULL, '2025-11-14 08:25:34', '2025-11-14 08:25:34'),
(6, 'aditya', 'aditya@gmail.com', NULL, '$2y$12$/Ov5TouE2hi0kxO7qXLKrOBe68.QkyplmTgwQnd.B3F9X/crKjhv2', 'mahasiswa', 'UVi0vpE6KyRjg3qBITIPDcIfzfbhyrIv0Z3kYbC1Y3rnGD3nR1YFyZUy5law', '2025-11-14 09:00:55', '2025-11-14 09:00:55'),
(7, 'Pak Harun', 'harun@gmail.com', NULL, '$2y$12$2go/1GO8l14JDN1Op2qKgef02V6XSTACV2LBAjNLUcAoREB4/xFsG', 'dosen', NULL, '2025-11-14 09:01:33', '2025-11-14 09:01:33'),
(9, 'Bagas Rosyidi', 'bagas@gmail.com', NULL, '$2y$12$iTi0mtt53Msr9eaY0ok5GeZs2/syFIeFPr3/d7CgKVWjzmCf5wgR6', 'mahasiswa', NULL, '2025-11-22 03:21:08', '2025-11-22 03:21:08'),
(10, 'Dr. Budi Santoso, M.Kom.', 'budisantoso@gmail.com', NULL, '$2y$12$rNJBl3.qxi/4nKeHXXw3YuAdn5f9MtrgfPwfvfU39xQQ0oWLILWfm', 'dosen', NULL, '2025-11-22 04:48:27', '2025-11-22 04:48:27');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `absensi`
--
ALTER TABLE `absensi`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `absensi_pengampu_id_mahasiswa_id_pertemuan_unique` (`pengampu_id`,`mahasiswa_id`,`pertemuan`),
  ADD KEY `absensi_mahasiswa_id_foreign` (`mahasiswa_id`),
  ADD KEY `absensi_jadwal_kuliah_id_foreign` (`jadwal_kuliah_id`);

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `admin_username_unique` (`username`),
  ADD UNIQUE KEY `admin_email_unique` (`email`);

--
-- Indexes for table `dosen`
--
ALTER TABLE `dosen`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `dosen_nip_unique` (`nip`),
  ADD UNIQUE KEY `dosen_email_unique` (`email`),
  ADD KEY `dosen_prodi_id_foreign` (`prodi_id`),
  ADD KEY `dosen_user_id_foreign` (`user_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `hari`
--
ALTER TABLE `hari`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jadwal_kuliah`
--
ALTER TABLE `jadwal_kuliah`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jadwal_kuliah_pengampu_id_foreign` (`pengampu_id`),
  ADD KEY `jadwal_kuliah_ruang_id_foreign` (`ruang_id`),
  ADD KEY `jadwal_kuliah_hari_id_foreign` (`hari_id`),
  ADD KEY `jadwal_kuliah_kelas_id_foreign` (`kelas_id`);

--
-- Indexes for table `jam`
--
ALTER TABLE `jam`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kelas`
--
ALTER TABLE `kelas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kelas_prodi_id_foreign` (`prodi_id`);

--
-- Indexes for table `mahasiswa`
--
ALTER TABLE `mahasiswa`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `mahasiswa_nim_unique` (`nim`),
  ADD UNIQUE KEY `mahasiswa_user_id_unique` (`user_id`),
  ADD KEY `mahasiswa_prodi_id_foreign` (`prodi_id`),
  ADD KEY `mahasiswa_kelas_id_foreign` (`kelas_id`);

--
-- Indexes for table `matakuliah`
--
ALTER TABLE `matakuliah`
  ADD PRIMARY KEY (`id`),
  ADD KEY `matakuliah_prodi_id_foreign` (`prodi_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `pengambilan_mk`
--
ALTER TABLE `pengambilan_mk`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pengambilan_mk_mahasiswa_id_foreign` (`mahasiswa_id`),
  ADD KEY `pengambilan_mk_matakuliah_id_foreign` (`matakuliah_id`),
  ADD KEY `pengambilan_mk_pengampu_id_foreign` (`pengampu_id`),
  ADD KEY `pengambilan_mk_kelas_id_foreign` (`kelas_id`);

--
-- Indexes for table `pengampu`
--
ALTER TABLE `pengampu`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pengampu_matakuliah_id_foreign` (`matakuliah_id`),
  ADD KEY `pengampu_kelas_id_foreign` (`kelas_id`),
  ADD KEY `pengampu_prodi_id_foreign` (`prodi_id`);

--
-- Indexes for table `pengampu_dosen`
--
ALTER TABLE `pengampu_dosen`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pengampu_dosen_pengampu_id_dosen_id_unique` (`pengampu_id`,`dosen_id`),
  ADD KEY `pengampu_dosen_dosen_id_foreign` (`dosen_id`),
  ADD KEY `pengampu_dosen_kelas_id_foreign` (`kelas_id`);

--
-- Indexes for table `pengumuman`
--
ALTER TABLE `pengumuman`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pengumuman_jadwal_kuliah_id_foreign` (`jadwal_kuliah_id`),
  ADD KEY `pengumuman_dosen_id_foreign` (`dosen_id`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `prodi`
--
ALTER TABLE `prodi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ruang`
--
ALTER TABLE `ruang`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `absensi`
--
ALTER TABLE `absensi`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dosen`
--
ALTER TABLE `dosen`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hari`
--
ALTER TABLE `hari`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `jadwal_kuliah`
--
ALTER TABLE `jadwal_kuliah`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `jam`
--
ALTER TABLE `jam`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `kelas`
--
ALTER TABLE `kelas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `mahasiswa`
--
ALTER TABLE `mahasiswa`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `matakuliah`
--
ALTER TABLE `matakuliah`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `pengambilan_mk`
--
ALTER TABLE `pengambilan_mk`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `pengampu`
--
ALTER TABLE `pengampu`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `pengampu_dosen`
--
ALTER TABLE `pengampu_dosen`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `pengumuman`
--
ALTER TABLE `pengumuman`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `prodi`
--
ALTER TABLE `prodi`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `ruang`
--
ALTER TABLE `ruang`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `absensi`
--
ALTER TABLE `absensi`
  ADD CONSTRAINT `absensi_jadwal_kuliah_id_foreign` FOREIGN KEY (`jadwal_kuliah_id`) REFERENCES `jadwal_kuliah` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `absensi_mahasiswa_id_foreign` FOREIGN KEY (`mahasiswa_id`) REFERENCES `mahasiswa` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `absensi_pengampu_id_foreign` FOREIGN KEY (`pengampu_id`) REFERENCES `pengampu` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `dosen`
--
ALTER TABLE `dosen`
  ADD CONSTRAINT `dosen_prodi_id_foreign` FOREIGN KEY (`prodi_id`) REFERENCES `prodi` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `dosen_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `jadwal_kuliah`
--
ALTER TABLE `jadwal_kuliah`
  ADD CONSTRAINT `jadwal_kuliah_hari_id_foreign` FOREIGN KEY (`hari_id`) REFERENCES `hari` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `jadwal_kuliah_kelas_id_foreign` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`),
  ADD CONSTRAINT `jadwal_kuliah_pengampu_id_foreign` FOREIGN KEY (`pengampu_id`) REFERENCES `pengampu` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `jadwal_kuliah_ruang_id_foreign` FOREIGN KEY (`ruang_id`) REFERENCES `ruang` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `kelas`
--
ALTER TABLE `kelas`
  ADD CONSTRAINT `kelas_prodi_id_foreign` FOREIGN KEY (`prodi_id`) REFERENCES `prodi` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `mahasiswa`
--
ALTER TABLE `mahasiswa`
  ADD CONSTRAINT `mahasiswa_kelas_id_foreign` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`),
  ADD CONSTRAINT `mahasiswa_prodi_id_foreign` FOREIGN KEY (`prodi_id`) REFERENCES `prodi` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `mahasiswa_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `matakuliah`
--
ALTER TABLE `matakuliah`
  ADD CONSTRAINT `matakuliah_prodi_id_foreign` FOREIGN KEY (`prodi_id`) REFERENCES `prodi` (`id`);

--
-- Constraints for table `pengambilan_mk`
--
ALTER TABLE `pengambilan_mk`
  ADD CONSTRAINT `pengambilan_mk_kelas_id_foreign` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `pengambilan_mk_mahasiswa_id_foreign` FOREIGN KEY (`mahasiswa_id`) REFERENCES `mahasiswa` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pengambilan_mk_matakuliah_id_foreign` FOREIGN KEY (`matakuliah_id`) REFERENCES `matakuliah` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pengambilan_mk_pengampu_id_foreign` FOREIGN KEY (`pengampu_id`) REFERENCES `pengampu` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pengampu`
--
ALTER TABLE `pengampu`
  ADD CONSTRAINT `pengampu_kelas_id_foreign` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pengampu_matakuliah_id_foreign` FOREIGN KEY (`matakuliah_id`) REFERENCES `matakuliah` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pengampu_prodi_id_foreign` FOREIGN KEY (`prodi_id`) REFERENCES `prodi` (`id`);

--
-- Constraints for table `pengampu_dosen`
--
ALTER TABLE `pengampu_dosen`
  ADD CONSTRAINT `pengampu_dosen_dosen_id_foreign` FOREIGN KEY (`dosen_id`) REFERENCES `dosen` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pengampu_dosen_kelas_id_foreign` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pengampu_dosen_pengampu_id_foreign` FOREIGN KEY (`pengampu_id`) REFERENCES `pengampu` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pengumuman`
--
ALTER TABLE `pengumuman`
  ADD CONSTRAINT `pengumuman_dosen_id_foreign` FOREIGN KEY (`dosen_id`) REFERENCES `dosen` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pengumuman_jadwal_kuliah_id_foreign` FOREIGN KEY (`jadwal_kuliah_id`) REFERENCES `jadwal_kuliah` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
