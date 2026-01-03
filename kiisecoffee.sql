-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 25, 2025 at 10:03 AM
-- Server version: 10.1.37-MariaDB
-- PHP Version: 7.3.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kiisecoffee`
--

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs`
--

CREATE TABLE `audit_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `action` varchar(100) NOT NULL,
  `table_name` varchar(50) NOT NULL,
  `record_id` int(11) DEFAULT NULL,
  `old_value` text,
  `new_value` text,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `bank_accounts`
--

CREATE TABLE `bank_accounts` (
  `id` int(11) NOT NULL,
  `bank_name` varchar(100) NOT NULL,
  `account_name` varchar(100) NOT NULL,
  `account_number` varchar(50) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `detail_transaksi`
--

CREATE TABLE `detail_transaksi` (
  `id` int(11) NOT NULL,
  `transaksi_id` int(11) NOT NULL,
  `makanan_id` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `harga_satuan` int(11) NOT NULL,
  `subtotal` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `detail_transaksi`
--

INSERT INTO `detail_transaksi` (`id`, `transaksi_id`, `makanan_id`, `jumlah`, `harga_satuan`, `subtotal`) VALUES
(1, 1, 2, 4, 12000, 48000),
(2, 2, 2, 1, 12000, 12000),
(3, 5, 1, 1, 15000, 15000),
(4, 6, 1, 1, 15000, 15000),
(5, 6, 2, 3, 12000, 36000),
(6, 7, 2, 3, 12000, 36000),
(7, 8, 2, 2, 12000, 24000),
(8, 9, 1, 1, 15000, 15000),
(9, 10, 2, 10, 12000, 120000),
(10, 11, 4, 2, 19000, 38000),
(11, 12, 1, 3, 15000, 45000),
(12, 12, 2, 3, 12000, 36000),
(13, 13, 1, 1, 15000, 15000),
(14, 14, 1, 1, 15000, 15000),
(15, 14, 2, 1, 12000, 12000);

-- --------------------------------------------------------

--
-- Table structure for table `makanan`
--

CREATE TABLE `makanan` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `kategori` varchar(50) DEFAULT NULL,
  `harga` int(11) DEFAULT NULL,
  `stok` int(11) NOT NULL DEFAULT '0',
  `minimum_stok` int(11) NOT NULL DEFAULT '5',
  `deskripsi` text,
  `gambar` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `makanan`
--

INSERT INTO `makanan` (`id`, `nama`, `kategori`, `harga`, `stok`, `minimum_stok`, `deskripsi`, `gambar`) VALUES
(1, 'STAR FRIES ', 'Makanan', 15000, 89, 5, 'Make the perfect crispy, golden French fries at home with this easy recipe! Learn the secrets to getting them perfectly crunchy on the outside and fluffy on the inside. These homemade fries are irresistible and the ultimate snack or side dish', 'ce8c563f2412257af8919184497a41e1.jpeg'),
(2, 'Burger', 'Makanan', 12000, 10, 5, 'Our Big Mac?« burger is irresistible with two 100% Aussie beef patties, iceberg lettuce, melting cheese, onions, pickles and our signature sauce. Try one today!', '56344a26da27ff913537694b15f40bda.jpeg'),
(3, 'Brown Sugar', 'Minuman', 16000, 106, 5, 'Brown Sugar yang menyegarkan ', '92f9940f330017e1f434a0f61ce5237c.jpg'),
(4, 'Ice Matcha ', 'minuman', 19000, 12, 5, 'Ice Matcha blend dengan susu yg creamy', 'cc4e4dbb7f660b15f9a35b5e51ee1390.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE `menu` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `kategori` varchar(50) DEFAULT NULL,
  `harga` int(11) DEFAULT NULL,
  `stok` int(11) NOT NULL DEFAULT '0',
  `minimum_stok` int(11) NOT NULL DEFAULT '5',
  `deskripsi` text,
  `gambar` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`id`, `nama`, `kategori`, `harga`, `stok`, `minimum_stok`, `deskripsi`, `gambar`) VALUES
(1, 'STAR FRIES ', 'Makanan', 15000, 84, 5, 'Make the perfect crispy, golden French fries at home with this easy recipe! Learn the secrets to getting them perfectly crunchy on the outside and fluffy on the inside. These homemade fries are irresistible and the ultimate snack or side dish', 'ce8c563f2412257af8919184497a41e1.jpeg'),
(2, 'Burger', 'Makanan', 12000, 6, 5, 'Our Big Mac?« burger is irresistible with two 100% Aussie beef patties, iceberg lettuce, melting cheese, onions, pickles and our signature sauce. Try one today!', '56344a26da27ff913537694b15f40bda.jpeg'),
(3, 'Brown Sugar', 'Minuman', 16000, 106, 5, 'Brown Sugar yang menyegarkan ', '92f9940f330017e1f434a0f61ce5237c.jpg'),
(4, 'Ice Matcha ', 'minuman', 19000, 10, 5, 'Ice Matcha blend dengan susu yg creamy', 'cc4e4dbb7f660b15f9a35b5e51ee1390.jpg'),
(5, 'KELPON PAKDE', 'Minuman', 5000, 10, 5, 'KELEPON ENAK', '7d183f33ff76fa45f6111e080d83f46e.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `type` enum('info','success','warning','danger') NOT NULL DEFAULT 'info',
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `read_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `promo`
--

CREATE TABLE `promo` (
  `id` int(11) NOT NULL,
  `kode` varchar(50) NOT NULL,
  `potongan` int(11) NOT NULL,
  `keterangan` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `promo`
--

INSERT INTO `promo` (`id`, `kode`, `potongan`, `keterangan`) VALUES
(1, 'KIISEHEMAT', 5000, 'Potongan Rp 5.000'),
(2, 'AWALBULAN', 10000, 'Potongan Rp 10.000'),
(3, 'GRATISONGKIR', 15000, 'Potongan Ongkir Rp 15.000'),
(4, 'KIISECOFFEE', 2000, 'Potongan Rp 2.000');

-- --------------------------------------------------------

--
-- Table structure for table `riwayat_poin`
--

CREATE TABLE `riwayat_poin` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `transaksi_id` int(11) NOT NULL,
  `poin` int(11) NOT NULL,
  `tipe` enum('masuk','keluar') NOT NULL DEFAULT 'masuk',
  `keterangan` varchar(255) NOT NULL,
  `tanggal` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `riwayat_poin`
--

INSERT INTO `riwayat_poin` (`id`, `user_id`, `transaksi_id`, `poin`, `tipe`, `keterangan`, `tanggal`) VALUES
(1, 5, 11, 3, 'masuk', 'Reward Transaksi #11', '2025-12-23 19:08:52'),
(2, 5, 13, 1, 'masuk', 'Reward Transaksi #13', '2025-12-24 03:05:26');

-- --------------------------------------------------------

--
-- Table structure for table `site_settings`
--

CREATE TABLE `site_settings` (
  `id` int(11) NOT NULL,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `site_settings`
--

INSERT INTO `site_settings` (`id`, `setting_key`, `setting_value`) VALUES
(1, 'kitchen_status', '3'),
(2, 'running_text', ''),
(3, 'shop_status', 'auto');

-- --------------------------------------------------------

--
-- Table structure for table `transaksi`
--

CREATE TABLE `transaksi` (
  `id` int(11) NOT NULL,
  `kode_transaksi` varchar(50) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total_harga` int(11) NOT NULL,
  `jumlah_bayar` decimal(15,2) DEFAULT NULL,
  `kembalian` decimal(15,2) DEFAULT NULL,
  `status` enum('pending','diproses','selesai','dibatalkan') NOT NULL DEFAULT 'pending',
  `metode_pembayaran` varchar(50) DEFAULT NULL,
  `bukti_bayar` varchar(255) DEFAULT NULL,
  `nominal_terbaca` decimal(15,2) DEFAULT NULL,
  `payment_status` varchar(50) DEFAULT 'unpaid',
  `catatan` text,
  `jenis_pesanan` varchar(50) DEFAULT 'Dine In',
  `alamat_pengiriman` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `transaksi`
--

INSERT INTO `transaksi` (`id`, `kode_transaksi`, `user_id`, `total_harga`, `jumlah_bayar`, `kembalian`, `status`, `metode_pembayaran`, `bukti_bayar`, `nominal_terbaca`, `payment_status`, `catatan`, `jenis_pesanan`, `alamat_pengiriman`, `created_at`, `updated_at`) VALUES
(1, '7XL0B3FTVW', 5, 48000, NULL, NULL, 'selesai', 'qris', 'bukti_7XL0B3FTVW_1765598252.png', '100000.00', 'paid', NULL, 'Dine In', NULL, '2025-12-12 20:41:48', '2025-12-14 02:30:02'),
(2, '7UVZLWCYGH', 5, 12000, NULL, NULL, 'selesai', 'qris', NULL, NULL, 'paid', NULL, 'Dine In', NULL, '2025-12-12 21:09:40', '2025-12-12 21:25:25'),
(3, 'OCRZBKCXYP', 3, 0, NULL, NULL, 'pending', NULL, NULL, NULL, 'unpaid', NULL, 'Dine In', NULL, '2025-12-12 21:21:22', '2025-12-12 21:21:22'),
(4, '4CVEMBZW15', 5, 0, NULL, NULL, 'selesai', NULL, NULL, NULL, 'paid', NULL, 'Dine In', NULL, '2025-12-12 21:21:41', '2025-12-12 21:25:14'),
(5, 'ICVKKEHXO5', 5, 15000, NULL, NULL, 'selesai', 'qris', NULL, NULL, 'paid', NULL, 'Dine In', NULL, '2025-12-12 21:42:12', '2025-12-12 21:42:59'),
(6, 'B4HXCEHMRF', 5, 51000, NULL, NULL, 'selesai', 'qris', 'PAY-1765603606.png', NULL, 'paid', NULL, 'Dine In', NULL, '2025-12-12 21:52:45', '2025-12-12 22:26:47'),
(7, 'TRX-1765603299958', 5, 36000, NULL, NULL, 'selesai', 'qris', 'PAY-1765603359.jpg', NULL, 'paid', NULL, 'Dine In', NULL, '2025-12-12 16:21:39', '2025-12-14 02:30:02'),
(8, 'TRX-1765603861885', 5, 24000, NULL, NULL, 'selesai', 'qris', 'PAY-1765603897.png', NULL, 'paid', NULL, 'Dine In', NULL, '2025-12-12 16:31:01', '2025-12-12 22:31:37'),
(9, '8BOLJNFETX', 5, 15000, NULL, NULL, 'selesai', 'qris', NULL, NULL, 'paid', NULL, 'Dine In', NULL, '2025-12-14 02:29:07', '2025-12-14 08:55:18'),
(10, 'NGHLRIYDNF', 5, 120000, NULL, NULL, 'selesai', 'qris', NULL, NULL, 'paid', NULL, 'Dine In', NULL, '2025-12-14 08:55:24', '2025-12-14 21:35:28'),
(11, 'DISPELTZ8T', 5, 38000, NULL, NULL, 'selesai', 'QRIS', 'fc1060b4bd1ae35d8692b8df9e8158c4.jpg', NULL, 'paid', '', 'Dine In', '', '2025-12-14 21:35:44', '2025-12-23 18:08:52'),
(12, 'MV7FYXGTOM', 5, 81000, NULL, NULL, 'selesai', 'QRIS', '730a28bca65d0e40cbabe883c8e930e1.jpg', NULL, 'paid', '', 'Dine In', '', '2025-12-23 18:08:56', '2025-12-23 18:13:07'),
(13, 'L8NIRWPAMR', 5, 15000, NULL, NULL, 'selesai', 'QRIS', 'a9b2640e40dc4989d57a6150506b6c62.jpg', NULL, 'paid', '', 'Dine In', '', '2025-12-23 18:27:42', '2025-12-24 02:05:26'),
(14, '8ATWKST1WK', 5, 27000, NULL, NULL, 'diproses', 'Tunai', NULL, NULL, 'unpaid', 'JANGAN PEDES', 'Delivery', 'BUGEL', '2025-12-24 02:05:29', '2025-12-24 02:17:13'),
(15, 'V2UFGMJ5IX', 5, 0, NULL, NULL, 'pending', NULL, NULL, NULL, 'unpaid', NULL, 'Dine In', NULL, '2025-12-24 02:23:07', '2025-12-24 02:23:07');

-- --------------------------------------------------------

--
-- Table structure for table `user_access_menu`
--

CREATE TABLE `user_access_menu` (
  `id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user_access_menu`
--

INSERT INTO `user_access_menu` (`id`, `role_id`, `menu_id`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 2, 2),
(4, 1, 3),
(5, 1, 4),
(6, 1, 6),
(7, 1, 5),
(8, 2, 8),
(9, 1, 7),
(10, 1, 8);

-- --------------------------------------------------------

--
-- Table structure for table `user_data`
--

CREATE TABLE `user_data` (
  `id` int(11) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role_id` int(11) NOT NULL,
  `date_created` int(11) NOT NULL,
  `poin` int(11) NOT NULL DEFAULT '0',
  `is_active` int(1) NOT NULL DEFAULT '1',
  `no_telepon` varchar(20) DEFAULT NULL,
  `auth_provider` varchar(50) DEFAULT 'local',
  `provider_id` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user_data`
--

INSERT INTO `user_data` (`id`, `nama`, `email`, `image`, `password`, `role_id`, `date_created`, `poin`) VALUES
(1, 'ATMINT', 'admin@admin.com', 'default.png', 'Ifik0102\r\n', 1, 1687945807, 0),
(2, 'User', 'user@user.com', 'default.png', '$2y$10$gG4qNHjWgH2ZV.b3trzz8OJxQRL2b354yoclXjaMQUwQdldIYTemm', 2, 1687945815, 0),
(3, 'Fikri', 'fikbintang01@gmail.com', 'WhatsApp_Image_2025-10-22_at_21_34_40_72513150.jpg', '$2y$10$yRXFWPhrqvcgYGcuU1MJ7OVDKJX/AxjgoWttgC1sYe7NzswOayQ.e', 1, 1765331488, 0),
(4, 'Yuno', 'fikrikunn@gmail.com', 'default.png', '$2y$10$zfD3qsxarxW/uOofI3kPresKIsGq5GU64UlTZoWKZzKSsA8FICO3u', 2, 1765364645, 0),
(5, 'yuno', 'fikribn123@gmail.com', 'WhatsApp_Image_2025-10-22_at_21_34_40_725131501.jpg', '$2y$10$cy.7U37GwDrrLLrIUw2Knu34Wy55eD6b2sRyQo37Mg62lejd1K8Cq', 2, 1765364696, 4);

-- --------------------------------------------------------

--
-- Table structure for table `user_menu`
--

CREATE TABLE `user_menu` (
  `id` int(11) NOT NULL,
  `menu` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user_menu`
--

INSERT INTO `user_menu` (`id`, `menu`) VALUES
(1, 'Admin'),
(2, 'User'),
(3, 'Menu'),
(4, 'Submenu'),
(5, 'Laporan'),
(6, 'MAKANAN'),
(7, 'Laporan'),
(8, 'Transaksi');

-- --------------------------------------------------------

--
-- Table structure for table `user_role`
--

CREATE TABLE `user_role` (
  `id` int(11) NOT NULL,
  `role` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user_role`
--

INSERT INTO `user_role` (`id`, `role`) VALUES
(1, 'Administrator'),
(2, 'Member');

-- --------------------------------------------------------

--
-- Table structure for table `user_sub_menu`
--

CREATE TABLE `user_sub_menu` (
  `id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `icon` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user_sub_menu`
--

INSERT INTO `user_sub_menu` (`id`, `menu_id`, `title`, `url`, `icon`) VALUES
(1, 1, 'Dashboard', 'admin', 'fas fa-fw fa-fire'),
(2, 1, 'Role Akses', 'admin/role', 'fas fa-fw fa-user-tie'),
(3, 1, 'Role User', 'admin/role_user', 'fas fa-fw fa-users'),
(4, 2, 'Profil Saya', 'user', 'fas fa-fw fa-user'),
(5, 2, 'Ubah Profil', 'user/ubah', 'fas fa-fw fa-user-edit'),
(6, 2, 'Ganti Kata Sandi', 'user/ganti_kata_sandi', 'fas fa-fw fa-key'),
(7, 3, 'Menu Management', 'menu', 'fas fa-fw fa-folder'),
(8, 4, 'Submenu Management', 'submenu', 'fas fa-fw fa-folder-open'),
(10, 6, 'MENU MAKANAN', 'makanan', 'fas fa-utensils'),
(11, 5, 'Laporan Menu', 'laporan/makanan', 'fas fa-fw fa-utensils'),
(12, 5, 'Laporan User', 'laporan/user', 'fas fa-fw fa-users'),
(13, 5, 'Laporan Penjualan', 'laporan/penjualan', 'fas fa-fw fa-chart-line'),
(14, 5, 'Laporan Dashboard', 'laporan/dashboard', 'fas fa-fw fa-tachometer-alt'),
(15, 8, 'Keranjang', 'transaksi/keranjang', 'fas fa-fw fa-shopping-cart'),
(16, 8, 'Riwayat Transaksi', 'transaksi/riwayat', 'fas fa-fw fa-history'),
(17, 1, 'Kelola Transaksi', 'transaksi/kelola', 'fas fa-fw fa-shopping-bag'),
(18, 7, 'Laporan Dashboard', 'laporan/dashboard', 'fas fa-fw fa-tachometer-alt');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bank_accounts`
--
ALTER TABLE `bank_accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `detail_transaksi`
--
ALTER TABLE `detail_transaksi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `makanan`
--
ALTER TABLE `makanan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `promo`
--
ALTER TABLE `promo`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `riwayat_poin`
--
ALTER TABLE `riwayat_poin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `site_settings`
--
ALTER TABLE `site_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_transaksi` (`kode_transaksi`);

--
-- Indexes for table `user_access_menu`
--
ALTER TABLE `user_access_menu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_data`
--
ALTER TABLE `user_data`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_menu`
--
ALTER TABLE `user_menu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_role`
--
ALTER TABLE `user_role`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_sub_menu`
--
ALTER TABLE `user_sub_menu`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bank_accounts`
--
ALTER TABLE `bank_accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `detail_transaksi`
--
ALTER TABLE `detail_transaksi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `makanan`
--
ALTER TABLE `makanan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `menu`
--
ALTER TABLE `menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `promo`
--
ALTER TABLE `promo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `riwayat_poin`
--
ALTER TABLE `riwayat_poin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `site_settings`
--
ALTER TABLE `site_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `user_access_menu`
--
ALTER TABLE `user_access_menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `user_data`
--
ALTER TABLE `user_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `user_menu`
--
ALTER TABLE `user_menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `user_role`
--
ALTER TABLE `user_role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `user_sub_menu`
--
ALTER TABLE `user_sub_menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
