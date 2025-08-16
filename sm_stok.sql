-- -------------------------------------------------------------
-- TablePlus 6.0.0(550)
--
-- https://tableplus.com/
--
-- Database: sm_stok
-- Generation Time: 2025-08-16 23:00:19.0880
-- -------------------------------------------------------------


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


CREATE TABLE `accounts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `agency` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `oncard_instansi_id` int DEFAULT NULL,
  `kode_instansi` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat` text COLLATE utf8mb4_unicode_ci,
  `status_id` int unsigned NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `agency_kode_instansi_unique` (`kode_instansi`),
  KEY `agency_kode_instansi_index` (`kode_instansi`),
  KEY `agency_user_id_foreign` (`user_id`),
  KEY `agency_status_id_foreign` (`status_id`),
  CONSTRAINT `agency_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `status` (`id`) ON DELETE CASCADE,
  CONSTRAINT `agency_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `barang_keluar` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `agency_id` int unsigned NOT NULL,
  `gudang_id` int unsigned NOT NULL,
  `user_created_id` bigint unsigned NOT NULL,
  `invoice_id` bigint unsigned NOT NULL,
  `produks_id` bigint unsigned NOT NULL,
  `harga_satuan` int DEFAULT NULL,
  `jumlah_barang_keluar` int NOT NULL,
  `satuan_keluar_id` int unsigned NOT NULL,
  `jumlah_sebelumnya` int NOT NULL,
  `satuan_sebelumnya_id` int unsigned NOT NULL,
  `tipe` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_id` int unsigned NOT NULL DEFAULT '1',
  `keterangan` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `barang_keluar_agency_id_foreign` (`agency_id`),
  KEY `barang_keluar_gudang_id_foreign` (`gudang_id`),
  KEY `barang_keluar_user_created_id_foreign` (`user_created_id`),
  KEY `barang_keluar_invoice_id_foreign` (`invoice_id`),
  KEY `barang_keluar_produks_id_foreign` (`produks_id`),
  KEY `barang_keluar_satuan_keluar_id_foreign` (`satuan_keluar_id`),
  KEY `barang_keluar_satuan_sebelumnya_id_foreign` (`satuan_sebelumnya_id`),
  KEY `barang_keluar_status_id_foreign` (`status_id`),
  CONSTRAINT `barang_keluar_agency_id_foreign` FOREIGN KEY (`agency_id`) REFERENCES `agency` (`id`),
  CONSTRAINT `barang_keluar_gudang_id_foreign` FOREIGN KEY (`gudang_id`) REFERENCES `gudang` (`id`) ON DELETE CASCADE,
  CONSTRAINT `barang_keluar_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE,
  CONSTRAINT `barang_keluar_produks_id_foreign` FOREIGN KEY (`produks_id`) REFERENCES `produks` (`id`) ON DELETE CASCADE,
  CONSTRAINT `barang_keluar_satuan_keluar_id_foreign` FOREIGN KEY (`satuan_keluar_id`) REFERENCES `jenis_satuans` (`id`),
  CONSTRAINT `barang_keluar_satuan_sebelumnya_id_foreign` FOREIGN KEY (`satuan_sebelumnya_id`) REFERENCES `jenis_satuans` (`id`),
  CONSTRAINT `barang_keluar_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `status` (`id`),
  CONSTRAINT `barang_keluar_user_created_id_foreign` FOREIGN KEY (`user_created_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `barang_masuk` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `agency_id` int unsigned NOT NULL,
  `gudang_id` int unsigned NOT NULL,
  `user_created_id` bigint unsigned NOT NULL,
  `produks_id` bigint unsigned NOT NULL,
  `supplier_id` bigint unsigned DEFAULT NULL,
  `harga_beli` int NOT NULL,
  `jumlah_barang_masuk` int NOT NULL,
  `satuan_beli_id` int unsigned NOT NULL,
  `jumlah_sebelumnya` int NOT NULL,
  `satuan_sebelumnya_id` int unsigned NOT NULL,
  `status_id` int unsigned NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `barang_masuk_agency_id_foreign` (`agency_id`),
  KEY `barang_masuk_gudang_id_foreign` (`gudang_id`),
  KEY `barang_masuk_user_created_id_foreign` (`user_created_id`),
  KEY `barang_masuk_produks_id_foreign` (`produks_id`),
  KEY `barang_masuk_supplier_id_foreign` (`supplier_id`),
  KEY `barang_masuk_satuan_beli_id_foreign` (`satuan_beli_id`),
  KEY `barang_masuk_satuan_sebelumnya_id_foreign` (`satuan_sebelumnya_id`),
  KEY `barang_masuk_status_id_foreign` (`status_id`),
  CONSTRAINT `barang_masuk_agency_id_foreign` FOREIGN KEY (`agency_id`) REFERENCES `agency` (`id`),
  CONSTRAINT `barang_masuk_gudang_id_foreign` FOREIGN KEY (`gudang_id`) REFERENCES `gudang` (`id`) ON DELETE CASCADE,
  CONSTRAINT `barang_masuk_produks_id_foreign` FOREIGN KEY (`produks_id`) REFERENCES `produks` (`id`) ON DELETE CASCADE,
  CONSTRAINT `barang_masuk_satuan_beli_id_foreign` FOREIGN KEY (`satuan_beli_id`) REFERENCES `jenis_satuans` (`id`),
  CONSTRAINT `barang_masuk_satuan_sebelumnya_id_foreign` FOREIGN KEY (`satuan_sebelumnya_id`) REFERENCES `jenis_satuans` (`id`),
  CONSTRAINT `barang_masuk_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `status` (`id`),
  CONSTRAINT `barang_masuk_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`),
  CONSTRAINT `barang_masuk_user_created_id_foreign` FOREIGN KEY (`user_created_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `config_agency` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `agency_id` int unsigned NOT NULL,
  `key` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `config_agency_agency_id_foreign` (`agency_id`),
  CONSTRAINT `config_agency_agency_id_foreign` FOREIGN KEY (`agency_id`) REFERENCES `agency` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `config_gudang` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `agency_id` int unsigned NOT NULL,
  `gudang_id` int unsigned NOT NULL,
  `key` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `config_gudang_agency_id_foreign` (`agency_id`),
  KEY `config_gudang_gudang_id_foreign` (`gudang_id`),
  CONSTRAINT `config_gudang_agency_id_foreign` FOREIGN KEY (`agency_id`) REFERENCES `agency` (`id`),
  CONSTRAINT `config_gudang_gudang_id_foreign` FOREIGN KEY (`gudang_id`) REFERENCES `gudang` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `configs` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `debt_detail_users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `agency_id` int unsigned NOT NULL,
  `gudang_id` int unsigned NOT NULL,
  `user_kasir_id` bigint unsigned NOT NULL,
  `user_debt_id` bigint unsigned NOT NULL,
  `invoice_id` bigint unsigned NOT NULL,
  `total_hutang` int NOT NULL,
  `total_bayar` int NOT NULL,
  `total_sisa` int NOT NULL,
  `payment_type_id` int unsigned NOT NULL,
  `status_id` int unsigned NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `debt_detail_users_agency_id_foreign` (`agency_id`),
  KEY `debt_detail_users_gudang_id_foreign` (`gudang_id`),
  KEY `debt_detail_users_user_kasir_id_foreign` (`user_kasir_id`),
  KEY `debt_detail_users_user_debt_id_foreign` (`user_debt_id`),
  KEY `debt_detail_users_invoice_id_foreign` (`invoice_id`),
  KEY `debt_detail_users_payment_type_id_foreign` (`payment_type_id`),
  KEY `debt_detail_users_status_id_foreign` (`status_id`),
  CONSTRAINT `debt_detail_users_agency_id_foreign` FOREIGN KEY (`agency_id`) REFERENCES `agency` (`id`),
  CONSTRAINT `debt_detail_users_gudang_id_foreign` FOREIGN KEY (`gudang_id`) REFERENCES `gudang` (`id`) ON DELETE CASCADE,
  CONSTRAINT `debt_detail_users_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE,
  CONSTRAINT `debt_detail_users_payment_type_id_foreign` FOREIGN KEY (`payment_type_id`) REFERENCES `payment_types` (`id`) ON DELETE CASCADE,
  CONSTRAINT `debt_detail_users_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `status` (`id`) ON DELETE CASCADE,
  CONSTRAINT `debt_detail_users_user_debt_id_foreign` FOREIGN KEY (`user_debt_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `debt_detail_users_user_kasir_id_foreign` FOREIGN KEY (`user_kasir_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `debt_users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `agency_id` int unsigned NOT NULL,
  `gudang_id` int unsigned NOT NULL,
  `user_debt_id` bigint unsigned NOT NULL,
  `total` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `debt_users_agency_id_foreign` (`agency_id`),
  KEY `debt_users_gudang_id_foreign` (`gudang_id`),
  KEY `debt_users_user_debt_id_foreign` (`user_debt_id`),
  CONSTRAINT `debt_users_agency_id_foreign` FOREIGN KEY (`agency_id`) REFERENCES `agency` (`id`),
  CONSTRAINT `debt_users_gudang_id_foreign` FOREIGN KEY (`gudang_id`) REFERENCES `gudang` (`id`) ON DELETE CASCADE,
  CONSTRAINT `debt_users_user_debt_id_foreign` FOREIGN KEY (`user_debt_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `detail_transaksi` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `agency_id` int unsigned NOT NULL,
  `gudang_id` int unsigned NOT NULL,
  `user_kasir_id` bigint unsigned NOT NULL,
  `user_buyer_id` bigint unsigned NOT NULL,
  `invoice_id` bigint unsigned NOT NULL,
  `transaksi_id` bigint unsigned NOT NULL,
  `produks_id` bigint unsigned NOT NULL,
  `unit_priece_id` bigint unsigned NOT NULL,
  `satuan_id` int unsigned NOT NULL,
  `priece` int NOT NULL,
  `priece_decimal` double NOT NULL,
  `jumlah` int NOT NULL,
  `total` int NOT NULL,
  `diskon` int NOT NULL DEFAULT '0',
  `status_id` int unsigned NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `detail_transaksi_agency_id_foreign` (`agency_id`),
  KEY `detail_transaksi_gudang_id_foreign` (`gudang_id`),
  KEY `detail_transaksi_user_kasir_id_foreign` (`user_kasir_id`),
  KEY `detail_transaksi_user_buyer_id_foreign` (`user_buyer_id`),
  KEY `detail_transaksi_invoice_id_foreign` (`invoice_id`),
  KEY `detail_transaksi_transaksi_id_foreign` (`transaksi_id`),
  KEY `detail_transaksi_produks_id_foreign` (`produks_id`),
  KEY `detail_transaksi_unit_priece_id_foreign` (`unit_priece_id`),
  KEY `detail_transaksi_satuan_id_foreign` (`satuan_id`),
  KEY `detail_transaksi_status_id_foreign` (`status_id`),
  CONSTRAINT `detail_transaksi_agency_id_foreign` FOREIGN KEY (`agency_id`) REFERENCES `agency` (`id`),
  CONSTRAINT `detail_transaksi_gudang_id_foreign` FOREIGN KEY (`gudang_id`) REFERENCES `gudang` (`id`) ON DELETE CASCADE,
  CONSTRAINT `detail_transaksi_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE,
  CONSTRAINT `detail_transaksi_produks_id_foreign` FOREIGN KEY (`produks_id`) REFERENCES `produks` (`id`) ON DELETE CASCADE,
  CONSTRAINT `detail_transaksi_satuan_id_foreign` FOREIGN KEY (`satuan_id`) REFERENCES `jenis_satuans` (`id`),
  CONSTRAINT `detail_transaksi_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `status` (`id`) ON DELETE CASCADE,
  CONSTRAINT `detail_transaksi_transaksi_id_foreign` FOREIGN KEY (`transaksi_id`) REFERENCES `transaksi` (`id`) ON DELETE CASCADE,
  CONSTRAINT `detail_transaksi_unit_priece_id_foreign` FOREIGN KEY (`unit_priece_id`) REFERENCES `unit_priece` (`id`),
  CONSTRAINT `detail_transaksi_user_buyer_id_foreign` FOREIGN KEY (`user_buyer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `detail_transaksi_user_kasir_id_foreign` FOREIGN KEY (`user_kasir_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `general_actor` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `oncard_instansi_id` int unsigned DEFAULT NULL,
  `agency_id` int unsigned DEFAULT NULL,
  `user_id` bigint unsigned NOT NULL,
  `oncard_user_id` int unsigned DEFAULT NULL,
  `oncard_account_number` int unsigned DEFAULT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_type` enum('siswa','general','merchant','agency','owner') COLLATE utf8mb4_unicode_ci NOT NULL,
  `sync_date` date DEFAULT NULL,
  `detail` text COLLATE utf8mb4_unicode_ci,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `general_actor_agency_id_foreign` (`agency_id`),
  KEY `general_actor_user_id_foreign` (`user_id`),
  CONSTRAINT `general_actor_agency_id_foreign` FOREIGN KEY (`agency_id`) REFERENCES `agency` (`id`),
  CONSTRAINT `general_actor_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `gudang` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `agency_id` int unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telepon` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deskripsi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_id` int unsigned NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `gudang_agency_id_foreign` (`agency_id`),
  KEY `gudang_user_id_foreign` (`user_id`),
  KEY `gudang_status_id_foreign` (`status_id`),
  CONSTRAINT `gudang_agency_id_foreign` FOREIGN KEY (`agency_id`) REFERENCES `agency` (`id`),
  CONSTRAINT `gudang_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `status` (`id`) ON DELETE CASCADE,
  CONSTRAINT `gudang_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `histories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `agency_id` int unsigned NOT NULL,
  `gudang_id` int unsigned NOT NULL,
  `kasir_id` bigint unsigned DEFAULT NULL,
  `user_kasir_id` bigint unsigned DEFAULT NULL,
  `user_trx_id` bigint unsigned DEFAULT NULL,
  `invoice_id` bigint unsigned NOT NULL,
  `tanggal` date NOT NULL,
  `jumlah` int NOT NULL,
  `saldo_awal` decimal(15,2) NOT NULL,
  `saldo_akhir` decimal(15,2) NOT NULL,
  `total` decimal(15,2) NOT NULL,
  `status_id` int unsigned NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `invoices` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `agency_id` int unsigned NOT NULL,
  `gudang_id` int unsigned NOT NULL,
  `users_merchant_id` bigint unsigned NOT NULL,
  `users_trx_id` bigint unsigned NOT NULL,
  `invoice_id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `trx_types_id` int unsigned NOT NULL,
  `payment_type_id` int unsigned NOT NULL,
  `dates` datetime NOT NULL,
  `status_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `invoices_invoice_id_unique` (`invoice_id`),
  KEY `invoices_agency_id_foreign` (`agency_id`),
  KEY `invoices_gudang_id_foreign` (`gudang_id`),
  KEY `invoices_users_merchant_id_foreign` (`users_merchant_id`),
  KEY `invoices_users_trx_id_foreign` (`users_trx_id`),
  KEY `invoices_trx_types_id_foreign` (`trx_types_id`),
  KEY `invoices_payment_type_id_foreign` (`payment_type_id`),
  CONSTRAINT `invoices_agency_id_foreign` FOREIGN KEY (`agency_id`) REFERENCES `agency` (`id`),
  CONSTRAINT `invoices_gudang_id_foreign` FOREIGN KEY (`gudang_id`) REFERENCES `gudang` (`id`) ON DELETE CASCADE,
  CONSTRAINT `invoices_payment_type_id_foreign` FOREIGN KEY (`payment_type_id`) REFERENCES `payment_types` (`id`),
  CONSTRAINT `invoices_trx_types_id_foreign` FOREIGN KEY (`trx_types_id`) REFERENCES `trx_types` (`id`),
  CONSTRAINT `invoices_users_merchant_id_foreign` FOREIGN KEY (`users_merchant_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `invoices_users_trx_id_foreign` FOREIGN KEY (`users_trx_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `jenis_produks` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `agency_id` int unsigned NOT NULL,
  `gudang_id` int unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `jenis_produks_agency_id_foreign` (`agency_id`),
  KEY `jenis_produks_gudang_id_foreign` (`gudang_id`),
  CONSTRAINT `jenis_produks_agency_id_foreign` FOREIGN KEY (`agency_id`) REFERENCES `agency` (`id`),
  CONSTRAINT `jenis_produks_gudang_id_foreign` FOREIGN KEY (`gudang_id`) REFERENCES `gudang` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `jenis_satuans` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `agency_id` int unsigned NOT NULL,
  `gudang_id` int unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `jenis_satuans_agency_id_foreign` (`agency_id`),
  KEY `jenis_satuans_gudang_id_foreign` (`gudang_id`),
  CONSTRAINT `jenis_satuans_agency_id_foreign` FOREIGN KEY (`agency_id`) REFERENCES `agency` (`id`),
  CONSTRAINT `jenis_satuans_gudang_id_foreign` FOREIGN KEY (`gudang_id`) REFERENCES `gudang` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `kasir` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `agency_id` int unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `gudang_id` int unsigned NOT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telepon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deskripsi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `saldo` int NOT NULL DEFAULT '0',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `kasir_agency_id_foreign` (`agency_id`),
  KEY `kasir_user_id_foreign` (`user_id`),
  KEY `kasir_gudang_id_foreign` (`gudang_id`),
  CONSTRAINT `kasir_agency_id_foreign` FOREIGN KEY (`agency_id`) REFERENCES `agency` (`id`),
  CONSTRAINT `kasir_gudang_id_foreign` FOREIGN KEY (`gudang_id`) REFERENCES `gudang` (`id`) ON DELETE CASCADE,
  CONSTRAINT `kasir_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `keyvendorpayments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `agency_id` int unsigned NOT NULL,
  `gudang_id` int unsigned NOT NULL,
  `user_gudang_id` bigint unsigned NOT NULL,
  `apikeys` int NOT NULL,
  `status_id` int unsigned NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `keyvendorpayments_agency_id_foreign` (`agency_id`),
  KEY `keyvendorpayments_gudang_id_foreign` (`gudang_id`),
  KEY `keyvendorpayments_user_gudang_id_foreign` (`user_gudang_id`),
  KEY `keyvendorpayments_status_id_foreign` (`status_id`),
  CONSTRAINT `keyvendorpayments_agency_id_foreign` FOREIGN KEY (`agency_id`) REFERENCES `agency` (`id`),
  CONSTRAINT `keyvendorpayments_gudang_id_foreign` FOREIGN KEY (`gudang_id`) REFERENCES `gudang` (`id`) ON DELETE CASCADE,
  CONSTRAINT `keyvendorpayments_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `status` (`id`) ON DELETE CASCADE,
  CONSTRAINT `keyvendorpayments_user_gudang_id_foreign` FOREIGN KEY (`user_gudang_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `konversisatuan` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `agency_id` int unsigned NOT NULL,
  `gudang_id` int unsigned NOT NULL,
  `user_created_id` bigint unsigned NOT NULL,
  `produks_id` bigint unsigned NOT NULL,
  `satuan_id` int unsigned NOT NULL,
  `satuan_konversi_id` int unsigned NOT NULL,
  `nilai_konversi` double NOT NULL,
  `status_id` int unsigned NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `konversisatuan_agency_id_foreign` (`agency_id`),
  KEY `konversisatuan_gudang_id_foreign` (`gudang_id`),
  KEY `konversisatuan_user_created_id_foreign` (`user_created_id`),
  KEY `konversisatuan_produks_id_foreign` (`produks_id`),
  KEY `konversisatuan_satuan_id_foreign` (`satuan_id`),
  KEY `konversisatuan_satuan_konversi_id_foreign` (`satuan_konversi_id`),
  CONSTRAINT `konversisatuan_agency_id_foreign` FOREIGN KEY (`agency_id`) REFERENCES `agency` (`id`) ON DELETE CASCADE,
  CONSTRAINT `konversisatuan_gudang_id_foreign` FOREIGN KEY (`gudang_id`) REFERENCES `gudang` (`id`) ON DELETE CASCADE,
  CONSTRAINT `konversisatuan_produks_id_foreign` FOREIGN KEY (`produks_id`) REFERENCES `produks` (`id`),
  CONSTRAINT `konversisatuan_satuan_id_foreign` FOREIGN KEY (`satuan_id`) REFERENCES `jenis_satuans` (`id`) ON DELETE CASCADE,
  CONSTRAINT `konversisatuan_satuan_konversi_id_foreign` FOREIGN KEY (`satuan_konversi_id`) REFERENCES `jenis_satuans` (`id`) ON DELETE CASCADE,
  CONSTRAINT `konversisatuan_user_created_id_foreign` FOREIGN KEY (`user_created_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `model_has_roles` (
  `role_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `payment_types` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `agency_id` int unsigned NOT NULL,
  `gudang_id` int unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alias` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `props` text COLLATE utf8mb4_unicode_ci,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_id` int unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `payment_types_agency_id_foreign` (`agency_id`),
  KEY `payment_types_gudang_id_foreign` (`gudang_id`),
  KEY `payment_types_status_id_foreign` (`status_id`),
  CONSTRAINT `payment_types_agency_id_foreign` FOREIGN KEY (`agency_id`) REFERENCES `agency` (`id`),
  CONSTRAINT `payment_types_gudang_id_foreign` FOREIGN KEY (`gudang_id`) REFERENCES `gudang` (`id`) ON DELETE CASCADE,
  CONSTRAINT `payment_types_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `status` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `produks` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `agency_id` int unsigned NOT NULL,
  `gudang_id` int unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci,
  `gambar` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'default.jpg',
  `jenis_produk_id` bigint unsigned NOT NULL,
  `barcode` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rak_id` int unsigned DEFAULT NULL,
  `status_id` int unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `produks_agency_id_foreign` (`agency_id`),
  KEY `produks_gudang_id_foreign` (`gudang_id`),
  KEY `produks_user_id_foreign` (`user_id`),
  KEY `produks_jenis_produk_id_foreign` (`jenis_produk_id`),
  KEY `produks_status_id_foreign` (`status_id`),
  CONSTRAINT `produks_agency_id_foreign` FOREIGN KEY (`agency_id`) REFERENCES `agency` (`id`),
  CONSTRAINT `produks_gudang_id_foreign` FOREIGN KEY (`gudang_id`) REFERENCES `gudang` (`id`) ON DELETE CASCADE,
  CONSTRAINT `produks_jenis_produk_id_foreign` FOREIGN KEY (`jenis_produk_id`) REFERENCES `jenis_produks` (`id`) ON DELETE CASCADE,
  CONSTRAINT `produks_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `status` (`id`),
  CONSTRAINT `produks_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `produks_config` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `agency_id` int unsigned NOT NULL,
  `gudang_id` int unsigned NOT NULL,
  `produks_id` bigint unsigned NOT NULL,
  `satuan_stok_id` int unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `produks_config_agency_id_foreign` (`agency_id`),
  KEY `produks_config_gudang_id_foreign` (`gudang_id`),
  KEY `produks_config_produks_id_foreign` (`produks_id`),
  KEY `produks_config_satuan_stok_id_foreign` (`satuan_stok_id`),
  CONSTRAINT `produks_config_agency_id_foreign` FOREIGN KEY (`agency_id`) REFERENCES `agency` (`id`),
  CONSTRAINT `produks_config_gudang_id_foreign` FOREIGN KEY (`gudang_id`) REFERENCES `gudang` (`id`) ON DELETE CASCADE,
  CONSTRAINT `produks_config_produks_id_foreign` FOREIGN KEY (`produks_id`) REFERENCES `produks` (`id`),
  CONSTRAINT `produks_config_satuan_stok_id_foreign` FOREIGN KEY (`satuan_stok_id`) REFERENCES `jenis_satuans` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `rak` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `agency_id` int unsigned NOT NULL,
  `gudang_id` int unsigned NOT NULL,
  `code` int DEFAULT NULL,
  `barcode` int DEFAULT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kapasitas` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `rak_agency_id_foreign` (`agency_id`),
  KEY `rak_gudang_id_foreign` (`gudang_id`),
  CONSTRAINT `rak_agency_id_foreign` FOREIGN KEY (`agency_id`) REFERENCES `agency` (`id`),
  CONSTRAINT `rak_gudang_id_foreign` FOREIGN KEY (`gudang_id`) REFERENCES `gudang` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `status` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alias` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deskripsi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `warna` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `stok_history` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `agency_id` int unsigned NOT NULL,
  `user_created_id` bigint unsigned NOT NULL,
  `gudang_id` int unsigned NOT NULL,
  `produks_id` bigint unsigned NOT NULL,
  `jumlah` int NOT NULL DEFAULT '0',
  `satuan_id` int unsigned NOT NULL,
  `jumlah_sebelumnya` int NOT NULL,
  `satuan_sebelumnya_id` int unsigned NOT NULL,
  `keterangan` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `stoks` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `agency_id` int unsigned NOT NULL,
  `gudang_id` int unsigned NOT NULL,
  `produks_id` bigint unsigned NOT NULL,
  `jumlah` int NOT NULL DEFAULT '0',
  `satuan_id` int unsigned NOT NULL,
  `jumlah_sebelumnya` int NOT NULL,
  `satuan_sebelumnya_id` int unsigned NOT NULL,
  `keterangan` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `stoks_agency_id_foreign` (`agency_id`),
  KEY `stoks_gudang_id_foreign` (`gudang_id`),
  KEY `stoks_produks_id_foreign` (`produks_id`),
  KEY `stoks_satuan_id_foreign` (`satuan_id`),
  KEY `stoks_satuan_sebelumnya_id_foreign` (`satuan_sebelumnya_id`),
  CONSTRAINT `stoks_agency_id_foreign` FOREIGN KEY (`agency_id`) REFERENCES `agency` (`id`),
  CONSTRAINT `stoks_gudang_id_foreign` FOREIGN KEY (`gudang_id`) REFERENCES `gudang` (`id`) ON DELETE CASCADE,
  CONSTRAINT `stoks_produks_id_foreign` FOREIGN KEY (`produks_id`) REFERENCES `produks` (`id`) ON DELETE CASCADE,
  CONSTRAINT `stoks_satuan_id_foreign` FOREIGN KEY (`satuan_id`) REFERENCES `jenis_satuans` (`id`),
  CONSTRAINT `stoks_satuan_sebelumnya_id_foreign` FOREIGN KEY (`satuan_sebelumnya_id`) REFERENCES `jenis_satuans` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `suppliers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `agency_id` int unsigned NOT NULL,
  `gudang_id` int unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat_supplier` text COLLATE utf8mb4_unicode_ci,
  `nomor_telepon_supplier` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `suppliers_agency_id_foreign` (`agency_id`),
  KEY `suppliers_gudang_id_foreign` (`gudang_id`),
  CONSTRAINT `suppliers_agency_id_foreign` FOREIGN KEY (`agency_id`) REFERENCES `agency` (`id`),
  CONSTRAINT `suppliers_gudang_id_foreign` FOREIGN KEY (`gudang_id`) REFERENCES `gudang` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `transaksi` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `agency_id` int unsigned NOT NULL,
  `gudang_id` int unsigned NOT NULL,
  `kasir_id` bigint unsigned NOT NULL,
  `user_kasir_id` bigint unsigned NOT NULL,
  `user_buyer_id` bigint unsigned NOT NULL,
  `invoice_id` bigint unsigned NOT NULL,
  `tanggal` date NOT NULL,
  `diskon` int NOT NULL DEFAULT '0',
  `total_diskon` decimal(8,2) NOT NULL DEFAULT '0.00',
  `tax` int NOT NULL DEFAULT '0',
  `tax_deduction` decimal(8,2) NOT NULL DEFAULT '0.00',
  `total_gross` int NOT NULL,
  `sub_total` int NOT NULL,
  `payment_type_id` int unsigned NOT NULL,
  `status_id` int unsigned NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `transaksi_agency_id_foreign` (`agency_id`),
  KEY `transaksi_gudang_id_foreign` (`gudang_id`),
  KEY `transaksi_kasir_id_foreign` (`kasir_id`),
  KEY `transaksi_user_kasir_id_foreign` (`user_kasir_id`),
  KEY `transaksi_user_buyer_id_foreign` (`user_buyer_id`),
  KEY `transaksi_invoice_id_foreign` (`invoice_id`),
  KEY `transaksi_payment_type_id_foreign` (`payment_type_id`),
  KEY `transaksi_status_id_foreign` (`status_id`),
  CONSTRAINT `transaksi_agency_id_foreign` FOREIGN KEY (`agency_id`) REFERENCES `agency` (`id`),
  CONSTRAINT `transaksi_gudang_id_foreign` FOREIGN KEY (`gudang_id`) REFERENCES `gudang` (`id`) ON DELETE CASCADE,
  CONSTRAINT `transaksi_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE,
  CONSTRAINT `transaksi_kasir_id_foreign` FOREIGN KEY (`kasir_id`) REFERENCES `kasir` (`id`) ON DELETE CASCADE,
  CONSTRAINT `transaksi_payment_type_id_foreign` FOREIGN KEY (`payment_type_id`) REFERENCES `payment_types` (`id`) ON DELETE CASCADE,
  CONSTRAINT `transaksi_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `status` (`id`) ON DELETE CASCADE,
  CONSTRAINT `transaksi_user_buyer_id_foreign` FOREIGN KEY (`user_buyer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `transaksi_user_kasir_id_foreign` FOREIGN KEY (`user_kasir_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `trx_types` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `agency_id` int unsigned NOT NULL,
  `gudang_id` int unsigned NOT NULL,
  `users_create_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descriptions` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `trx_types_agency_id_foreign` (`agency_id`),
  KEY `trx_types_users_create_id_foreign` (`users_create_id`),
  CONSTRAINT `trx_types_agency_id_foreign` FOREIGN KEY (`agency_id`) REFERENCES `agency` (`id`),
  CONSTRAINT `trx_types_users_create_id_foreign` FOREIGN KEY (`users_create_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `unit_priece` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_created_id` bigint unsigned NOT NULL,
  `agency_id` int unsigned NOT NULL,
  `gudang_id` int unsigned NOT NULL,
  `produks_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `priece` int NOT NULL,
  `priece_decimal` double DEFAULT NULL,
  `jenis_satuan_jual_id` int unsigned NOT NULL,
  `diskon` int NOT NULL DEFAULT '0',
  `status_id` int unsigned NOT NULL,
  `user_update_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `unit_priece_user_created_id_foreign` (`user_created_id`),
  KEY `unit_priece_agency_id_foreign` (`agency_id`),
  KEY `unit_priece_gudang_id_foreign` (`gudang_id`),
  KEY `unit_priece_produks_id_foreign` (`produks_id`),
  KEY `unit_priece_jenis_satuan_jual_id_foreign` (`jenis_satuan_jual_id`),
  KEY `unit_priece_status_id_foreign` (`status_id`),
  KEY `unit_priece_user_update_id_foreign` (`user_update_id`),
  CONSTRAINT `unit_priece_agency_id_foreign` FOREIGN KEY (`agency_id`) REFERENCES `agency` (`id`),
  CONSTRAINT `unit_priece_gudang_id_foreign` FOREIGN KEY (`gudang_id`) REFERENCES `gudang` (`id`) ON DELETE CASCADE,
  CONSTRAINT `unit_priece_jenis_satuan_jual_id_foreign` FOREIGN KEY (`jenis_satuan_jual_id`) REFERENCES `jenis_satuans` (`id`),
  CONSTRAINT `unit_priece_produks_id_foreign` FOREIGN KEY (`produks_id`) REFERENCES `produks` (`id`),
  CONSTRAINT `unit_priece_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `status` (`id`),
  CONSTRAINT `unit_priece_user_created_id_foreign` FOREIGN KEY (`user_created_id`) REFERENCES `users` (`id`),
  CONSTRAINT `unit_priece_user_update_id_foreign` FOREIGN KEY (`user_update_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `api_key` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_id` int unsigned NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_username_unique` (`username`),
  KEY `users_status_id_foreign` (`status_id`),
  CONSTRAINT `users_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `status` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `agency` (`id`, `user_id`, `oncard_instansi_id`, `kode_instansi`, `nama`, `alamat`, `status_id`, `created_at`, `updated_at`) VALUES
(1, 2, 1, 'AGY1', 'Pondok Pesantren Al-Munawwir', 'Agency 1', 22, '2025-07-01 21:08:18', '2025-07-01 21:08:18');

INSERT INTO `barang_masuk` (`id`, `agency_id`, `gudang_id`, `user_created_id`, `produks_id`, `supplier_id`, `harga_beli`, `jumlah_barang_masuk`, `satuan_beli_id`, `jumlah_sebelumnya`, `satuan_sebelumnya_id`, `status_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 3, 1, NULL, 10000, 1, 7, 0, 5, 4, '2025-07-01 21:08:18', '2025-07-01 21:08:18'),
(2, 1, 1, 3, 2, NULL, 100000, 2, 7, 0, 5, 4, '2025-07-01 21:08:18', '2025-07-01 21:08:18'),
(3, 1, 1, 3, 5, 1, 10000, 10, 7, 0, 6, 4, '2025-07-06 11:42:39', '2025-07-06 11:42:39');

INSERT INTO `config_gudang` (`id`, `agency_id`, `gudang_id`, `key`, `value`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'pph', '10', '2025-07-01 21:08:18', '2025-07-01 21:08:18');

INSERT INTO `debt_detail_users` (`id`, `agency_id`, `gudang_id`, `user_kasir_id`, `user_debt_id`, `invoice_id`, `total_hutang`, `total_bayar`, `total_sisa`, `payment_type_id`, `status_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 5, 1, 57618, 0, 57618, 2, 1, '2025-07-01 21:08:18', '2025-07-01 21:08:18');

INSERT INTO `debt_users` (`id`, `agency_id`, `gudang_id`, `user_debt_id`, `total`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 5, 57618, '2025-07-01 21:08:18', '2025-07-01 21:08:18');

INSERT INTO `detail_transaksi` (`id`, `agency_id`, `gudang_id`, `user_kasir_id`, `user_buyer_id`, `invoice_id`, `transaksi_id`, `produks_id`, `unit_priece_id`, `satuan_id`, `priece`, `priece_decimal`, `jumlah`, `total`, `diskon`, `status_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 1, 1, 1, 1, 3, 40, 30000, 30000, 2, 58200, 3, 24, '2025-07-01 21:08:18', '2025-07-01 21:08:18'),
(2, 1, 1, 2, 2, 18, 2, 5, 7, 6, 10000, 10000, 1, 10000, 0, 24, '2025-07-06 11:44:34', '2025-07-06 11:44:34');

INSERT INTO `general_actor` (`id`, `oncard_instansi_id`, `agency_id`, `user_id`, `oncard_user_id`, `oncard_account_number`, `nama`, `user_type`, `sync_date`, `detail`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 5, 1, 123123123, 'tester', 'merchant', '2025-07-01', 'tester', NULL, '2025-07-01 21:08:18', '2025-07-01 21:08:18'),
(2, NULL, 1, 7, NULL, NULL, 'umum', 'general', '2025-07-06', '-', NULL, '2025-07-06 11:24:42', '2025-07-06 11:24:42');

INSERT INTO `gudang` (`id`, `agency_id`, `user_id`, `nama`, `alamat`, `telepon`, `logo`, `deskripsi`, `status_id`, `created_at`, `updated_at`) VALUES
(1, 1, 3, 'Gudang Demo', 'Gudang 1', '08123456789', NULL, 'Gudang 1', 22, '2025-07-01 21:08:18', '2025-07-01 21:08:18');

INSERT INTO `invoices` (`id`, `agency_id`, `gudang_id`, `users_merchant_id`, `users_trx_id`, `invoice_id`, `trx_types_id`, `payment_type_id`, `dates`, `status_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 3, 4, 'PHNX-AGY1-250701091808-0001', 1, 2, '2025-07-01 00:00:00', 1, '2025-07-01 21:08:18', '2025-07-01 21:08:18'),
(2, 1, 1, 3, 6, 'PHNX-AGY1-250706114003-0001', 1, 1, '2025-07-06 00:00:00', 1, '2025-07-06 11:03:40', '2025-07-06 11:03:40'),
(3, 1, 1, 3, 6, 'PHNX-AGY1-250706111004-0001', 1, 1, '2025-07-06 00:00:00', 1, '2025-07-06 11:04:10', '2025-07-06 11:04:10'),
(4, 1, 1, 3, 6, 'PHNX-AGY1-250706115906-0001', 1, 1, '2025-07-06 00:00:00', 1, '2025-07-06 11:06:59', '2025-07-06 11:06:59'),
(5, 1, 1, 3, 6, 'PHNX-AGY1-250706113107-0001', 1, 1, '2025-07-06 00:00:00', 1, '2025-07-06 11:07:31', '2025-07-06 11:07:31'),
(6, 1, 1, 3, 6, 'PHNX-AGY1-250706114107-0001', 1, 1, '2025-07-06 00:00:00', 1, '2025-07-06 11:07:41', '2025-07-06 11:07:41'),
(7, 1, 1, 3, 6, 'PHNX-AGY1-250706111908-0001', 1, 1, '2025-07-06 00:00:00', 1, '2025-07-06 11:08:19', '2025-07-06 11:08:19'),
(8, 1, 1, 3, 6, 'PHNX-AGY1-250706114208-0001', 1, 1, '2025-07-06 00:00:00', 1, '2025-07-06 11:08:42', '2025-07-06 11:08:42'),
(9, 1, 1, 3, 6, 'PHNX-AGY1-250706111909-0001', 1, 1, '2025-07-06 00:00:00', 1, '2025-07-06 11:09:19', '2025-07-06 11:09:19'),
(10, 1, 1, 3, 6, 'PHNX-AGY1-250706114409-0001', 1, 1, '2025-07-06 00:00:00', 1, '2025-07-06 11:09:44', '2025-07-06 11:09:44'),
(11, 1, 1, 3, 6, 'PHNX-AGY1-250706110810-0001', 1, 1, '2025-07-06 00:00:00', 1, '2025-07-06 11:10:08', '2025-07-06 11:10:08'),
(12, 1, 1, 3, 6, 'PHNX-AGY1-250706113439-0001', 1, 1, '2025-07-06 00:00:00', 1, '2025-07-06 11:39:34', '2025-07-06 11:39:34'),
(13, 1, 1, 3, 6, 'PHNX-AGY1-250706113040-0001', 1, 1, '2025-07-06 00:00:00', 1, '2025-07-06 11:40:30', '2025-07-06 11:40:30'),
(14, 1, 1, 3, 6, 'PHNX-AGY1-250706112041-0001', 1, 1, '2025-07-06 00:00:00', 1, '2025-07-06 11:41:20', '2025-07-06 11:41:20'),
(15, 1, 1, 3, 6, 'PHNX-AGY1-250706113443-0001', 1, 1, '2025-07-06 00:00:00', 1, '2025-07-06 11:43:34', '2025-07-06 11:43:34'),
(16, 1, 1, 3, 6, 'PHNX-AGY1-250706115643-0001', 1, 1, '2025-07-06 00:00:00', 1, '2025-07-06 11:43:56', '2025-07-06 11:43:56'),
(17, 1, 1, 3, 6, 'PHNX-AGY1-250706111644-0001', 1, 1, '2025-07-06 00:00:00', 1, '2025-07-06 11:44:16', '2025-07-06 11:44:16'),
(18, 1, 1, 3, 6, 'PHNX-AGY1-250706113444-0001', 1, 1, '2025-07-06 00:00:00', 1, '2025-07-06 11:44:34', '2025-07-06 11:44:34'),
(19, 1, 1, 3, 1, 'PHNX-AGY1-250715052359-0001', 12, 1, '2025-07-15 00:00:00', 1, '2025-07-15 05:59:23', '2025-07-15 05:59:23');

INSERT INTO `jenis_produks` (`id`, `agency_id`, `gudang_id`, `name`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'Makanan', '2025-07-01 21:08:18', '2025-07-01 21:08:18'),
(2, 1, 1, 'Minuman', '2025-07-01 21:08:18', '2025-07-01 21:08:18'),
(3, 1, 1, 'Snack', '2025-07-01 21:08:18', '2025-07-01 21:08:18'),
(4, 1, 1, 'Pakaian', '2025-07-01 21:08:18', '2025-07-01 21:08:18'),
(5, 1, 1, 'Elektronik', '2025-07-01 21:08:18', '2025-07-01 21:08:18');

INSERT INTO `jenis_satuans` (`id`, `agency_id`, `gudang_id`, `name`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'Kilogram', '2025-07-01 21:08:18', '2025-07-01 21:08:18'),
(2, 1, 1, 'Gram', '2025-07-01 21:08:18', '2025-07-01 21:08:18'),
(3, 1, 1, 'Liter', '2025-07-01 21:08:18', '2025-07-01 21:08:18'),
(4, 1, 1, 'Mililiter', '2025-07-01 21:08:18', '2025-07-01 21:08:18'),
(5, 1, 1, 'Pcs', '2025-07-01 21:08:18', '2025-07-01 21:08:18'),
(6, 1, 1, 'Botol', '2025-07-01 21:08:18', '2025-07-01 21:08:18'),
(7, 1, 1, 'Dus', '2025-07-01 21:08:18', '2025-07-01 21:08:18'),
(8, 1, 1, 'Kardus', '2025-07-01 21:08:18', '2025-07-01 21:08:18'),
(9, 1, 1, 'Kodi', '2025-07-01 21:08:18', '2025-07-01 21:08:18'),
(10, 1, 1, 'Lusin', '2025-07-01 21:08:18', '2025-07-01 21:08:18'),
(11, 1, 1, 'Rim', '2025-07-01 21:08:18', '2025-07-01 21:08:18'),
(12, 1, 1, 'Gross', '2025-07-01 21:08:18', '2025-07-01 21:08:18'),
(13, 1, 1, 'Ton', '2025-07-01 21:08:18', '2025-07-01 21:08:18'),
(14, 1, 1, 'Kuintal', '2025-07-01 21:08:18', '2025-07-01 21:08:18'),
(15, 1, 1, 'Ons', '2025-07-01 21:08:18', '2025-07-01 21:08:18'),
(16, 1, 1, 'Pon', '2025-07-01 21:08:18', '2025-07-01 21:08:18'),
(17, 1, 1, 'Sendok', '2025-07-01 21:08:18', '2025-07-01 21:08:18'),
(18, 1, 1, 'Sendok Teh', '2025-07-01 21:08:18', '2025-07-01 21:08:18'),
(19, 1, 1, 'Sendok Makan', '2025-07-01 21:08:18', '2025-07-01 21:08:18'),
(20, 1, 1, 'Gelas', '2025-07-01 21:08:18', '2025-07-01 21:08:18'),
(21, 1, 1, 'Piring', '2025-07-01 21:08:18', '2025-07-01 21:08:18'),
(22, 1, 1, 'Mangkok', '2025-07-01 21:08:18', '2025-07-01 21:08:18'),
(23, 1, 1, 'Toples', '2025-07-01 21:08:18', '2025-07-01 21:08:18'),
(24, 1, 1, 'Kotak', '2025-07-01 21:08:18', '2025-07-01 21:08:18'),
(25, 1, 1, 'Kantong', '2025-07-01 21:08:18', '2025-07-01 21:08:18'),
(26, 1, 1, 'Karung', '2025-07-01 21:08:18', '2025-07-01 21:08:18'),
(27, 1, 1, 'Bungkus', '2025-07-01 21:08:18', '2025-07-01 21:08:18'),
(28, 1, 1, 'Bal', '2025-07-01 21:08:18', '2025-07-01 21:08:18'),
(29, 1, 1, 'Batang', '2025-07-01 21:08:18', '2025-07-01 21:08:18'),
(30, 1, 1, 'Butir', '2025-07-01 21:08:18', '2025-07-01 21:08:18'),
(31, 1, 1, 'Helai', '2025-07-01 21:08:18', '2025-07-01 21:08:18'),
(32, 1, 1, 'Lembar', '2025-07-01 21:08:18', '2025-07-01 21:08:18'),
(33, 1, 1, 'Buah', '2025-07-01 21:08:18', '2025-07-01 21:08:18'),
(34, 1, 1, 'Potong', '2025-07-01 21:08:18', '2025-07-01 21:08:18'),
(35, 1, 1, 'Ikat', '2025-07-01 21:08:18', '2025-07-01 21:08:18'),
(36, 1, 1, 'Keping', '2025-07-01 21:08:18', '2025-07-01 21:08:18'),
(37, 1, 1, 'Kepingan', '2025-07-01 21:08:18', '2025-07-01 21:08:18'),
(38, 1, 1, 'Kerat', '2025-07-01 21:08:18', '2025-07-01 21:08:18'),
(39, 1, 1, 'Keranjang', '2025-07-01 21:08:18', '2025-07-01 21:08:18'),
(40, 1, 1, 'Pack', '2025-07-01 21:08:18', '2025-07-01 21:08:18');

INSERT INTO `kasir` (`id`, `agency_id`, `user_id`, `gudang_id`, `nama`, `alamat`, `telepon`, `deskripsi`, `saldo`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 1, 4, 1, 'Kasir Demo', 'Kasir 1', '08123456789', 'Kasir 1', 0, NULL, '2025-07-01 21:08:18', '2025-07-01 21:08:18'),
(2, 1, 6, 1, 'kasirs', '-', '08', NULL, 0, NULL, '2025-07-06 11:02:40', '2025-07-06 11:02:40');

INSERT INTO `konversisatuan` (`id`, `agency_id`, `gudang_id`, `user_created_id`, `produks_id`, `satuan_id`, `satuan_konversi_id`, `nilai_konversi`, `status_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 3, 1, 5, 5, 1, 22, '2025-07-01 21:08:18', '2025-07-01 21:08:18'),
(2, 1, 1, 3, 1, 5, 5, 1, 22, '2025-07-01 21:08:18', '2025-07-01 21:08:18'),
(3, 1, 1, 3, 1, 7, 5, 10, 22, '2025-07-01 21:08:18', '2025-07-01 21:08:18'),
(4, 1, 1, 3, 1, 40, 5, 5, 22, '2025-07-01 21:08:18', '2025-07-01 21:08:18'),
(5, 1, 1, 3, 2, 5, 5, 1, 22, '2025-07-01 21:08:18', '2025-07-01 21:08:18'),
(6, 1, 1, 3, 2, 5, 5, 1, 22, '2025-07-01 21:08:18', '2025-07-01 21:08:18'),
(7, 1, 1, 3, 2, 23, 5, 10, 22, '2025-07-01 21:08:18', '2025-07-01 21:08:18'),
(8, 1, 1, 3, 2, 7, 5, 20, 22, '2025-07-01 21:08:18', '2025-07-01 21:08:18'),
(9, 1, 1, 3, 5, 6, 6, 1, 22, '2025-07-06 09:18:43', '2025-07-06 09:18:43'),
(11, 1, 1, 3, 5, 7, 6, 20, 22, '2025-07-06 09:25:40', '2025-07-06 09:25:40');

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_10_000001_create_statuses_table', 1),
(2, '2014_10_11_000000_create_users_table', 1),
(3, '2014_10_11_000001_create_accounts_table', 1),
(4, '2014_10_12_000000_create_agencies_table', 1),
(5, '2014_10_12_000001_create_gudang_table', 1),
(6, '2014_10_12_000002_create_general', 1),
(7, '2014_10_12_000002_create_kasir_table', 1),
(8, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(9, '2014_10_12_100000_create_password_resets_table', 1),
(10, '2019_08_19_000000_create_failed_jobs_table', 1),
(11, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(12, '2024_05_07_083328_create_payment_types_table', 1),
(13, '2024_05_08_141900_create_jenis_produks_table', 1),
(14, '2024_05_08_141900_create_trx_types', 1),
(15, '2024_05_08_141901_create_invoices', 1),
(16, '2024_05_08_141922_create_suppliers_table', 1),
(17, '2024_05_08_142003_create_jenis_satuans_table', 1),
(18, '2024_05_08_142018_create_produks_table', 1),
(19, '2024_05_08_142019_create_hargas_table', 1),
(20, '2024_05_17_194022_create_barang_masuks_table', 1),
(21, '2024_05_19_080403_create_permission_tables', 1),
(22, '2024_05_24_193810_create_config_gudang_table', 1),
(23, '2024_05_24_193821_create_configs_agency_table', 1),
(24, '2024_05_24_193821_create_configs_table', 1),
(25, '2024_05_28_155331_create_debt_users_table', 1),
(26, '2024_05_28_155335_create_debt_detail_users_table', 1),
(27, '2024_06_29_200627_create_keyvendorpayments_table', 1),
(28, '2024_07_04_155919_create_barang_keluars_table', 1),
(29, '2024_07_04_160613_create_stok_histories_table', 1),
(30, '2025_05_08_142037_create_transaksis_table', 1),
(31, '2025_05_08_142057_create_detail_transaksis_table', 1),
(32, '2025_06_17_201952_create_histories_table', 1);

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 2),
(2, 'App\\Models\\User', 3),
(3, 'App\\Models\\User', 4),
(3, 'App\\Models\\User', 6),
(4, 'App\\Models\\User', 5),
(4, 'App\\Models\\User', 7);

INSERT INTO `payment_types` (`id`, `agency_id`, `gudang_id`, `name`, `alias`, `type`, `props`, `description`, `icon`, `status_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'CASH', 'CASH', 'CASH', '[]', 'CASH', 'CASH', 22, '2025-07-01 21:08:18', '2025-07-01 21:08:18'),
(2, 1, 1, 'DEBS', 'DEBS', 'DEBS', '[]', 'DEBS', 'DEBS', 22, '2025-07-01 21:08:18', '2025-07-01 21:08:18');

INSERT INTO `produks` (`id`, `agency_id`, `gudang_id`, `user_id`, `name`, `deskripsi`, `gambar`, `jenis_produk_id`, `barcode`, `rak_id`, `status_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 3, 'Produk 1', 'Deskripsi Produk 1', 'gambar1.jpg', 1, '124123214', NULL, 22, '2025-07-01 21:08:18', '2025-07-01 21:08:18'),
(2, 1, 1, 3, 'Produk 2', 'Deskripsi Produk 2', 'gambar2.jpg', 2, '124123214', NULL, 22, '2025-07-01 21:08:18', '2025-07-01 21:08:18'),
(5, 1, 1, 3, 'aqua', '-', 'K44XPrcznqcdotVb1751793522.jpg', 2, 'xxx', NULL, 22, '2025-07-06 09:18:43', '2025-07-06 09:18:43');

INSERT INTO `produks_config` (`id`, `agency_id`, `gudang_id`, `produks_id`, `satuan_stok_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 5, '2025-07-01 21:08:18', '2025-07-01 21:08:18'),
(2, 1, 1, 2, 5, '2025-07-01 21:08:18', '2025-07-01 21:08:18'),
(5, 1, 1, 5, 6, '2025-07-06 09:18:43', '2025-07-06 09:18:43');

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'agency', 'api', '2025-07-01 21:08:18', '2025-07-01 21:08:18'),
(2, 'gudang', 'api', '2025-07-01 21:08:18', '2025-07-01 21:08:18'),
(3, 'kasir', 'api', '2025-07-01 21:08:18', '2025-07-01 21:08:18'),
(4, 'general', 'api', '2025-07-01 21:08:18', '2025-07-01 21:08:18');

INSERT INTO `status` (`id`, `nama`, `alias`, `deskripsi`, `warna`, `created_at`, `updated_at`) VALUES
(1, 'Pending', 'pending', 'Menunggu', '#FFCC00', '2025-07-01 21:08:18', '2025-07-01 21:08:18'),
(2, 'Approved', 'approved', 'Disetujui', '#00CC00', '2025-07-01 21:08:18', '2025-07-01 21:08:18'),
(3, 'Rejected', 'rejected', 'Ditolak', '#FF0000', '2025-07-01 21:08:18', '2025-07-01 21:08:18'),
(4, 'Completed', 'completed', 'Selesai', '#0000FF', '2025-07-01 21:08:18', '2025-07-01 21:08:18'),
(5, 'Cancelled', 'cancelled', 'Dibatalkan', '#CCCCCC', '2025-07-01 21:08:18', '2025-07-01 21:08:18'),
(6, 'Processing', 'processing', 'Sedang Diproses', '#FFFF00', '2025-07-01 21:08:18', '2025-07-01 21:08:18'),
(7, 'OnHold', 'on_hold', 'Ditahan', '#FF6600', '2025-07-01 21:08:18', '2025-07-01 21:08:18'),
(8, 'Refunded', 'refunded', 'Dikembalikan', '#00FFFF', '2025-07-01 21:08:18', '2025-07-01 21:08:18'),
(9, 'Paid', 'paid', 'Dibayar', '#00FF00', '2025-07-01 21:08:18', '2025-07-01 21:08:18'),
(10, 'Unpaid', 'unpaid', 'Belum Dibayar', '#FF6666', '2025-07-01 21:08:18', '2025-07-01 21:08:18'),
(11, 'Overdue', 'overdue', 'Terlambat', '#FF3300', '2025-07-01 21:08:18', '2025-07-01 21:08:18'),
(12, 'Confirmed', 'confirmed', 'Dikonfirmasi', '#3366FF', '2025-07-01 21:08:18', '2025-07-01 21:08:18'),
(13, 'Disputed', 'disputed', 'Diperdebatkan', '#9933FF', '2025-07-01 21:08:18', '2025-07-01 21:08:18'),
(14, 'Sent', 'sent', 'Dikirim', '#3399FF', '2025-07-01 21:08:18', '2025-07-01 21:08:18'),
(15, 'Draft', 'draft', 'Draf', '#CCCC99', '2025-07-01 21:08:18', '2025-07-01 21:08:18'),
(16, 'Authorized', 'authorized', 'Disahkan', '#669900', '2025-07-01 21:08:18', '2025-07-01 21:08:18'),
(17, 'PartiallyPaid', 'partially_paid', 'Sebagian Dibayar', '#FF9966', '2025-07-01 21:08:18', '2025-07-01 21:08:18'),
(18, 'PartiallyRefunded', 'partially_refunded', 'Sebagian Dikembalikan', '#99FFFF', '2025-07-01 21:08:18', '2025-07-01 21:08:18'),
(19, 'Error', 'error', 'Kesalahan', '#FF0033', '2025-07-01 21:08:18', '2025-07-01 21:08:18'),
(20, 'Reviewing', 'reviewing', 'Sedang Ditinjau', '#CCCC33', '2025-07-01 21:08:18', '2025-07-01 21:08:18'),
(21, 'debs', 'debs', '', '#000000', '2025-07-01 21:08:18', '2025-07-01 21:08:18'),
(22, 'ACTIVE', 'active', '', '#00FF99', '2025-07-01 21:08:18', '2025-07-01 21:08:18'),
(23, 'INACTIVE', 'inactive', '', '#999999', '2025-07-01 21:08:18', '2025-07-01 21:08:18'),
(24, 'SUCCESS', 'success', '', '#00FF00', '2025-07-01 21:08:18', '2025-07-01 21:08:18');

INSERT INTO `stoks` (`id`, `agency_id`, `gudang_id`, `produks_id`, `jumlah`, `satuan_id`, `jumlah_sebelumnya`, `satuan_sebelumnya_id`, `keterangan`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 0, 5, 10, 5, 'Barang Keluar', '2025-07-01 21:08:18', '2025-07-01 21:08:18'),
(2, 1, 1, 2, 40, 5, 0, 5, 'Barang Masuk', '2025-07-01 21:08:18', '2025-07-01 21:08:18'),
(3, 1, 1, 5, 199, 6, 200, 6, 'Barang Keluar', '2025-07-06 11:42:39', '2025-07-06 11:44:34');

INSERT INTO `suppliers` (`id`, `agency_id`, `gudang_id`, `name`, `alamat_supplier`, `nomor_telepon_supplier`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'sup 1', '-', '-', '2025-07-06 09:17:05', '2025-07-06 09:17:05');

INSERT INTO `transaksi` (`id`, `agency_id`, `gudang_id`, `kasir_id`, `user_kasir_id`, `user_buyer_id`, `invoice_id`, `tanggal`, `diskon`, `total_diskon`, `tax`, `tax_deduction`, `total_gross`, `sub_total`, `payment_type_id`, `status_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 4, 5, 1, '2025-07-01', 10, 5820.00, 10, 5238.00, 58200, 57618, 2, 24, '2025-07-01 21:08:18', '2025-07-01 21:08:18'),
(2, 1, 1, 2, 6, 7, 18, '2025-07-06', 0, 0.00, 10, 1000.00, 10000, 11000, 1, 24, '2025-07-06 11:44:34', '2025-07-06 11:44:34');

INSERT INTO `trx_types` (`id`, `agency_id`, `gudang_id`, `users_create_id`, `name`, `descriptions`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 3, 'Sale', 'Sale', '2025-07-01 21:08:18', '2025-07-01 21:08:18'),
(2, 1, 1, 3, 'Purchase', 'Purchase', '2025-07-01 21:08:18', '2025-07-01 21:08:18'),
(3, 1, 1, 3, 'Transfer', 'Transfer', '2025-07-01 21:08:18', '2025-07-01 21:08:18'),
(4, 1, 1, 3, 'Withdrawal', 'Withdrawal', '2025-07-01 21:08:18', '2025-07-01 21:08:18'),
(5, 1, 1, 3, 'Deposit', 'Deposit', '2025-07-01 21:08:18', '2025-07-01 21:08:18'),
(6, 1, 1, 3, 'Refund', 'Refund', '2025-07-01 21:08:18', '2025-07-01 21:08:18'),
(7, 1, 1, 3, 'Expense', 'Expense', '2025-07-01 21:08:18', '2025-07-01 21:08:18'),
(8, 1, 1, 3, 'Income', 'Income', '2025-07-01 21:08:18', '2025-07-01 21:08:18'),
(9, 1, 1, 3, 'Loan', 'Loan', '2025-07-01 21:08:18', '2025-07-01 21:08:18'),
(10, 1, 1, 3, 'TransferBalance', 'TransferBalance', '2025-07-01 21:08:18', '2025-07-01 21:08:18'),
(11, 1, 1, 3, 'melting', 'melting', '2025-07-01 21:08:18', '2025-07-01 21:08:18'),
(12, 1, 1, 3, 'PAYMENT', 'PAYMENT', '2025-07-01 21:08:18', '2025-07-01 21:08:18');

INSERT INTO `unit_priece` (`id`, `user_created_id`, `agency_id`, `gudang_id`, `produks_id`, `name`, `priece`, `priece_decimal`, `jenis_satuan_jual_id`, `diskon`, `status_id`, `user_update_id`, `created_at`, `updated_at`) VALUES
(1, 3, 1, 1, 1, 'pcs', 10000, 10000, 5, 0, 22, 3, '2025-07-01 21:08:18', '2025-07-01 21:08:18'),
(2, 3, 1, 1, 1, 'pcs', 1000, 1000, 5, 0, 22, 3, '2025-07-01 21:08:18', '2025-07-01 21:08:18'),
(3, 3, 1, 1, 1, 'pack', 30000, 30000, 40, 3, 22, 3, '2025-07-01 21:08:18', '2025-07-01 21:08:18'),
(4, 3, 1, 1, 2, 'pcs', 20000, 20000, 5, 0, 22, 3, '2025-07-01 21:08:18', '2025-07-01 21:08:18'),
(5, 3, 1, 1, 2, 'pcs', 5000, 5000, 5, 0, 22, 3, '2025-07-01 21:08:18', '2025-07-01 21:08:18'),
(6, 3, 1, 1, 2, 'pack', 20000, 20000, 23, 0, 22, 3, '2025-07-01 21:08:18', '2025-07-01 21:08:18'),
(7, 3, 1, 1, 5, 'pcs', 10000, 10000, 6, 0, 22, 3, '2025-07-06 09:18:43', '2025-07-06 09:18:43'),
(8, 3, 1, 1, 5, 'Dus', 100000, 100000, 7, 0, 22, 3, '2025-07-06 09:26:50', '2025-07-06 09:26:50');

INSERT INTO `users` (`id`, `nama`, `username`, `password`, `role`, `api_key`, `status_id`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Super Admin', 'superadmin', 'password', 'superadmin', NULL, 22, NULL, '2025-07-01 21:08:18', '2025-07-01 21:08:18'),
(2, 'Pondok Pesantren Al-Munawwir', 'pondok_demo', '$2y$10$HzluK/RKsHexqF3kDw./EOrwtq2F90LN50uaoPgHeyPAO8WQrPYpS', 'agency', NULL, 22, NULL, '2025-07-01 21:08:18', '2025-07-01 21:08:18'),
(3, 'Gudang Demo', 'gudang_demo', '$2y$10$UhxPerX0QA/7Web.OlDdB.6qG/Ihqb1KzjyNScKZZAEEwLTXFS3q.', 'gudang', NULL, 22, NULL, '2025-07-01 21:08:18', '2025-07-01 21:08:18'),
(4, 'Kasir Demo', 'kasir_demo', '$2y$10$EphjI9xoyTbBmguwAhZiNeS/aFxjjDyTdIBEsVz5qxVE08lPoeZPG', 'kasir', NULL, 22, NULL, '2025-07-01 21:08:18', '2025-07-01 21:08:18'),
(5, 'ego oktafanda', 'ego', '$2y$10$Jv.S8D5MgbGuKJjXUDHWQObuKoB20LF/1OKGksauIoWydxzMcXiFu', 'general', NULL, 22, NULL, '2025-07-01 21:08:18', '2025-07-01 21:08:18'),
(6, 'kasirs', 'kasirs', '$2y$10$/1h3bdUGHWov21qHQx.OzOUEIKpuXHZGo4fsKgrXbZWeXK.sGeWE2', 'kasir', NULL, 22, NULL, '2025-07-06 11:02:40', '2025-07-06 11:02:40'),
(7, 'umum', 'umum', '$2y$10$1bEVVni.OVD.DzJ55ANnn.qYnea1VvUBj1U4eUyzyH7isPJxca6CW', 'general', NULL, 22, NULL, '2025-07-06 11:24:42', '2025-07-06 11:24:42');



/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;