-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 17, 2026 at 07:42 PM
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
-- Database: `bank_sampah`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id_admin` bigint(20) NOT NULL,
  `username_admin` varchar(32) NOT NULL,
  `password_admin` varchar(64) NOT NULL,
  `role` varchar(16) NOT NULL,
  `nama_admin` varchar(32) NOT NULL,
  `no_telepon_admin` varchar(16) NOT NULL,
  `email_admin` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id_admin`, `username_admin`, `password_admin`, `role`, `nama_admin`, `no_telepon_admin`, `email_admin`) VALUES
(1, 'admin', '$2y$10$FIUwGsKfwjkQncrGm5s07enK0eXwcV2VXDe9QM0bFIVaf1d/BUqAW', 'admin', '', '', 'admin@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `akun`
--

CREATE TABLE `akun` (
  `id_akun` bigint(20) NOT NULL,
  `email` varchar(64) NOT NULL,
  `username` varchar(64) NOT NULL,
  `password` varchar(64) NOT NULL,
  `role` varchar(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `akun`
--

INSERT INTO `akun` (`id_akun`, `email`, `username`, `password`, `role`) VALUES
(73, 'superadmin@gmail.com', 'root', '$2y$12$mts3p.ijtHp8BQbznGCwWeYhm7CDblrxj7xaK5VMmpnZl0DTzQ5zy', 'admin'),
(74, 'edwardahhutauruk@gmail.com', 'edwardedo', '$2y$12$HxZn3HUfbT90z.JpdPochefAX6J8YtUueTgVLhhFixuGRCEDLkZTu', 'nasabah');

-- --------------------------------------------------------

--
-- Table structure for table `detail_transaksi`
--

CREATE TABLE `detail_transaksi` (
  `id_detail` bigint(20) NOT NULL,
  `id_transaksi` bigint(20) NOT NULL,
  `id_sampah` bigint(20) NOT NULL,
  `jenis_sampah` varchar(16) NOT NULL,
  `subtotal_nominal` double NOT NULL,
  `berat_sampah` double NOT NULL,
  `catatan` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `detail_transaksi`
--

INSERT INTO `detail_transaksi` (`id_detail`, `id_transaksi`, `id_sampah`, `jenis_sampah`, `subtotal_nominal`, `berat_sampah`, `catatan`) VALUES
(1, 8, 1, 'Plastik', 8000, 4, '8 rb'),
(2, 8, 2, 'Kertas', 6000, 4, ' 6 rb');

-- --------------------------------------------------------

--
-- Table structure for table `nasabah`
--

CREATE TABLE `nasabah` (
  `id_nasabah` bigint(11) NOT NULL,
  `username_nasabah` varchar(32) NOT NULL,
  `password_nasabah` varchar(64) NOT NULL,
  `role` varchar(16) NOT NULL,
  `tanggal_bergabung` date NOT NULL,
  `jumlah_tabungan` double NOT NULL,
  `nama_nasabah` varchar(64) NOT NULL,
  `no_telepon_nasabah` varchar(32) NOT NULL,
  `email_nasabah` varchar(32) NOT NULL,
  `alamat_nasabah` varchar(64) NOT NULL,
  `kecamatan` varchar(16) NOT NULL,
  `kelurahan` varchar(16) NOT NULL,
  `rw` varchar(8) NOT NULL,
  `rt` varchar(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `nasabah`
--

INSERT INTO `nasabah` (`id_nasabah`, `username_nasabah`, `password_nasabah`, `role`, `tanggal_bergabung`, `jumlah_tabungan`, `nama_nasabah`, `no_telepon_nasabah`, `email_nasabah`, `alamat_nasabah`, `kecamatan`, `kelurahan`, `rw`, `rt`) VALUES
(1, 'edwardedo', '$2y$10$bWvNYfdNn4IeBMa6bNk0muZc3PLrC7/m3VXtOPHeWVgLhUGW/0IfO', 'nasabah', '0000-00-00', 0, '', '', 'edwardahhutauruk@gmail.com', '', '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `petugas_lapangan`
--

CREATE TABLE `petugas_lapangan` (
  `id_petugas` bigint(20) NOT NULL,
  `username_petugas` varchar(32) NOT NULL,
  `password_petugas` varchar(64) NOT NULL,
  `role` varchar(16) NOT NULL,
  `nama_petugas` varchar(64) NOT NULL,
  `no_telepon_petugas` varchar(32) NOT NULL,
  `email_petugas` varchar(32) NOT NULL,
  `wilayah_tugas` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `petugas_lapangan`
--

INSERT INTO `petugas_lapangan` (`id_petugas`, `username_petugas`, `password_petugas`, `role`, `nama_petugas`, `no_telepon_petugas`, `email_petugas`, `wilayah_tugas`) VALUES
(1, 'petugas', '$2y$10$eR0tHurfkUkMh99IxzPYkOmPmEDMW9P/jbuqZGwNH7PPdYy6SGkP6', 'petugas', '', '', 'petugas@gmail.com', '');

-- --------------------------------------------------------

--
-- Table structure for table `sampah`
--

CREATE TABLE `sampah` (
  `id_sampah` bigint(20) NOT NULL,
  `jenis_sampah` varchar(64) NOT NULL,
  `harga_sampah_per_kg` double NOT NULL,
  `jumlah_sampah_gudang` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sampah`
--

INSERT INTO `sampah` (`id_sampah`, `jenis_sampah`, `harga_sampah_per_kg`, `jumlah_sampah_gudang`) VALUES
(1, 'Plastik', 2000, 0),
(2, 'Kertas', 1500, 0),
(3, 'Logam', 4000, 0);

-- --------------------------------------------------------

--
-- Table structure for table `transaksi`
--

CREATE TABLE `transaksi` (
  `id_transaksi` bigint(20) NOT NULL,
  `id_nasabah` bigint(20) NOT NULL,
  `id_petugas` bigint(20) DEFAULT NULL,
  `tanggal_transaksi` datetime NOT NULL,
  `total_nominal` double NOT NULL,
  `total_berat` double NOT NULL,
  `tanggal_penyerahan` datetime NOT NULL,
  `metode_penyerahan` varchar(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaksi`
--

INSERT INTO `transaksi` (`id_transaksi`, `id_nasabah`, `id_petugas`, `tanggal_transaksi`, `total_nominal`, `total_berat`, `tanggal_penyerahan`, `metode_penyerahan`) VALUES
(8, 1, NULL, '2026-07-18 00:33:31', 14000, 8, '2026-08-04 00:33:00', 'Pick-up');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id_admin`);

--
-- Indexes for table `akun`
--
ALTER TABLE `akun`
  ADD PRIMARY KEY (`id_akun`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `detail_transaksi`
--
ALTER TABLE `detail_transaksi`
  ADD PRIMARY KEY (`id_detail`),
  ADD KEY `id_transaksi` (`id_transaksi`),
  ADD KEY `id_sampah` (`id_sampah`);

--
-- Indexes for table `nasabah`
--
ALTER TABLE `nasabah`
  ADD PRIMARY KEY (`id_nasabah`),
  ADD UNIQUE KEY `username_nasabah` (`username_nasabah`);

--
-- Indexes for table `petugas_lapangan`
--
ALTER TABLE `petugas_lapangan`
  ADD PRIMARY KEY (`id_petugas`);

--
-- Indexes for table `sampah`
--
ALTER TABLE `sampah`
  ADD PRIMARY KEY (`id_sampah`),
  ADD UNIQUE KEY `jenis_sampah` (`jenis_sampah`);

--
-- Indexes for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id_transaksi`),
  ADD KEY `transaksi_ibfk_1` (`id_nasabah`),
  ADD KEY `id_petugas` (`id_petugas`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id_admin` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `akun`
--
ALTER TABLE `akun`
  MODIFY `id_akun` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- AUTO_INCREMENT for table `detail_transaksi`
--
ALTER TABLE `detail_transaksi`
  MODIFY `id_detail` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `nasabah`
--
ALTER TABLE `nasabah`
  MODIFY `id_nasabah` bigint(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `petugas_lapangan`
--
ALTER TABLE `petugas_lapangan`
  MODIFY `id_petugas` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `sampah`
--
ALTER TABLE `sampah`
  MODIFY `id_sampah` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id_transaksi` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `detail_transaksi`
--
ALTER TABLE `detail_transaksi`
  ADD CONSTRAINT `detail_transaksi_ibfk_1` FOREIGN KEY (`id_transaksi`) REFERENCES `transaksi` (`id_transaksi`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detail_transaksi_ibfk_2` FOREIGN KEY (`id_sampah`) REFERENCES `sampah` (`id_sampah`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD CONSTRAINT `transaksi_ibfk_1` FOREIGN KEY (`id_nasabah`) REFERENCES `nasabah` (`id_nasabah`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `transaksi_ibfk_2` FOREIGN KEY (`id_petugas`) REFERENCES `petugas_lapangan` (`id_petugas`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
