-- ============================================
-- Portal Inspektorat Papua Tengah
-- MySQL Database Schema & Sample Data
-- ============================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- Create Database (uncomment if needed)
-- CREATE DATABASE IF NOT EXISTS `portal_inspektorat` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
-- USE `portal_inspektorat`;

-- ============================================
-- Table structure for table `users`
-- ============================================

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table structure for table `portal_papua_tengah`
-- ============================================

DROP TABLE IF EXISTS `portal_papua_tengah`;
CREATE TABLE `portal_papua_tengah` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `judul` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `konten` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `kategori` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `penulis` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_published` tinyint(1) NOT NULL DEFAULT 0,
  `published_at` timestamp NULL DEFAULT NULL,
  `views` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_published` (`is_published`, `published_at`),
  KEY `idx_kategori` (`kategori`),
  KEY `idx_views` (`views`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table structure for table `wbs`
-- ============================================

DROP TABLE IF EXISTS `wbs`;
CREATE TABLE `wbs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nama_pelapor` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nomor_telepon` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jenis_laporan` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subjek` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `lokasi_kejadian` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tanggal_kejadian` date DEFAULT NULL,
  `bukti_pendukung` json DEFAULT NULL,
  `status` enum('pending','in_review','resolved','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `admin_notes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_status` (`status`),
  KEY `idx_jenis_laporan` (`jenis_laporan`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table structure for supporting tables
-- ============================================

DROP TABLE IF EXISTS `cache`;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `jobs`;
CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cancelled_at` int(10) UNSIGNED DEFAULT NULL,
  `created_at` int(10) UNSIGNED NOT NULL,
  `finished_at` int(10) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `personal_access_tokens`;
CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Sample Data Insertion
-- ============================================

-- Insert default admin user
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Administrator', 'admin@admin.com', NULL, '$2y$12$8LhcqZqBwgdxZw5H3B2QxeJ5Rc4.kH8VL4bH/dYKm8CZJrZLJqJ6K', NULL, NOW(), NOW());

-- Insert sample news articles
INSERT INTO `portal_papua_tengah` (`id`, `judul`, `konten`, `kategori`, `penulis`, `is_published`, `published_at`, `views`, `created_at`, `updated_at`) VALUES
(1, 'Selamat Datang di Portal Inspektorat Papua Tengah', 'Portal resmi Inspektorat Provinsi Papua Tengah telah diluncurkan untuk memberikan akses informasi yang lebih baik kepada masyarakat. Melalui portal ini, masyarakat dapat mengakses berbagai informasi penting, berita terkini, dan layanan Whistleblower System (WBS) untuk melaporkan dugaan pelanggaran.\n\nPortal ini dikembangkan dengan teknologi modern dan desain responsif untuk memastikan aksesibilitas yang optimal di berbagai perangkat.', 'informasi', 'Tim Inspektorat', 1, NOW(), 150, NOW(), NOW()),

(2, 'Pengumuman Pelaksanaan Monitoring dan Evaluasi Program Pemerintah Daerah', 'Inspektorat Provinsi Papua Tengah akan melaksanakan kegiatan monitoring dan evaluasi terhadap program-program pemerintah daerah di lingkungan Provinsi Papua Tengah. Kegiatan ini bertujuan untuk memastikan efektivitas dan efisiensi pelaksanaan program pembangunan.\n\nTim monitoring akan turun langsung ke lapangan untuk melakukan verifikasi dan evaluasi komprehensif terhadap berbagai program strategis daerah.', 'pengumuman', 'Kepala Inspektorat', 1, DATE_SUB(NOW(), INTERVAL 1 DAY), 89, DATE_SUB(NOW(), INTERVAL 1 DAY), DATE_SUB(NOW(), INTERVAL 1 DAY)),

(3, 'Sosialisasi Sistem Whistleblower (WBS) untuk Masyarakat Papua Tengah', 'Dalam rangka meningkatkan partisipasi masyarakat dalam pengawasan pemerintahan, Inspektorat Provinsi Papua Tengah menyelenggarakan sosialisasi Sistem Whistleblower (WBS). Sistem ini memungkinkan masyarakat untuk melaporkan dugaan pelanggaran atau penyimpangan secara aman dan terlindungi.\n\nMasyarakat dapat mengakses sistem WBS melalui portal resmi atau datang langsung ke kantor Inspektorat.', 'informasi', 'Bidang Pencegahan', 1, DATE_SUB(NOW(), INTERVAL 2 DAY), 234, DATE_SUB(NOW(), INTERVAL 2 DAY), DATE_SUB(NOW(), INTERVAL 2 DAY)),

(4, 'Laporan Hasil Pemeriksaan Kinerja Tahun 2024', 'Inspektorat Provinsi Papua Tengah telah menyelesaikan pemeriksaan kinerja untuk tahun anggaran 2024. Hasil pemeriksaan menunjukkan adanya peningkatan kinerja yang signifikan di berbagai bidang pembangunan daerah.\n\nLaporan lengkap akan dipublikasikan melalui portal resmi dan dapat diakses oleh masyarakat luas sebagai bentuk transparansi dan akuntabilitas pemerintah daerah.', 'laporan', 'Tim Pemeriksa', 1, DATE_SUB(NOW(), INTERVAL 3 DAY), 167, DATE_SUB(NOW(), INTERVAL 3 DAY), DATE_SUB(NOW(), INTERVAL 3 DAY)),

(5, 'Peluncuran Program Zona Integritas Inspektorat Papua Tengah', 'Inspektorat Provinsi Papua Tengah secara resmi meluncurkan Program Zona Integritas sebagai komitmen untuk menciptakan area bebas dari korupsi dan wilayah birokrasi bersih yang melayani publik. Program ini merupakan bagian dari upaya reformasi birokrasi dan peningkatan kualitas pelayanan publik.\n\nImplementasi program ini akan melibatkan seluruh elemen organisasi dan didukung dengan sistem monitoring yang ketat.', 'program', 'Sekretariat Inspektorat', 1, DATE_SUB(NOW(), INTERVAL 4 DAY), 98, DATE_SUB(NOW(), INTERVAL 4 DAY), DATE_SUB(NOW(), INTERVAL 4 DAY)),

(6, 'Pelatihan Audit Internal untuk Aparatur Pemerintah Daerah', 'Dalam rangka meningkatkan kapasitas aparatur pemerintah daerah, Inspektorat Provinsi Papua Tengah menyelenggarakan pelatihan audit internal. Pelatihan ini diikuti oleh auditor dari berbagai instansi pemerintah di lingkungan Provinsi Papua Tengah.\n\nMateri pelatihan meliputi teknik audit modern, penggunaan teknologi dalam audit, dan penerapan standar audit pemerintahan yang berlaku.', 'pelatihan', 'Bidang Pengawasan', 1, DATE_SUB(NOW(), INTERVAL 5 DAY), 76, DATE_SUB(NOW(), INTERVAL 5 DAY), DATE_SUB(NOW(), INTERVAL 5 DAY));

-- Insert sample WBS reports
INSERT INTO `wbs` (`id`, `nama_pelapor`, `email`, `nomor_telepon`, `jenis_laporan`, `subjek`, `deskripsi`, `lokasi_kejadian`, `tanggal_kejadian`, `bukti_pendukung`, `status`, `admin_notes`, `created_at`, `updated_at`) VALUES
(1, 'Anonim', NULL, NULL, 'korupsi', 'Dugaan Penyimpangan Dana Program', 'Terdapat dugaan penyimpangan dalam penggunaan dana program pembangunan infrastruktur. Perlu dilakukan investigasi lebih lanjut untuk memastikan penggunaan anggaran sesuai dengan ketentuan yang berlaku.', 'Nabire', DATE_SUB(CURRENT_DATE, INTERVAL 10 DAY), NULL, 'in_review', 'Laporan sedang dalam tahap verifikasi dan investigasi awal.', DATE_SUB(NOW(), INTERVAL 7 DAY), NOW()),

(2, 'Warga Peduli', 'peduli@email.com', '081234567890', 'pelayanan', 'Keluhan Pelayanan Publik', 'Pelayanan di salah satu instansi pemerintah daerah tidak sesuai dengan standar yang ditetapkan. Proses perizinan memakan waktu terlalu lama dan tidak transparan.', 'Wamena', DATE_SUB(CURRENT_DATE, INTERVAL 5 DAY), NULL, 'pending', NULL, DATE_SUB(NOW(), INTERVAL 3 DAY), DATE_SUB(NOW(), INTERVAL 3 DAY));

-- ============================================
-- Auto Increment Settings
-- ============================================

ALTER TABLE `users` AUTO_INCREMENT = 2;
ALTER TABLE `portal_papua_tengah` AUTO_INCREMENT = 7;
ALTER TABLE `wbs` AUTO_INCREMENT = 3;
ALTER TABLE `jobs` AUTO_INCREMENT = 1;
ALTER TABLE `job_batches` AUTO_INCREMENT = 1;
ALTER TABLE `failed_jobs` AUTO_INCREMENT = 1;
ALTER TABLE `migrations` AUTO_INCREMENT = 1;
ALTER TABLE `personal_access_tokens` AUTO_INCREMENT = 1;

COMMIT;

-- ============================================
-- End of Portal Inspektorat Papua Tengah Database
-- ============================================
