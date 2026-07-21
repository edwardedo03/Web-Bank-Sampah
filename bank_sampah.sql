-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 21 Jul 2026 pada 05.54
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
-- Database: `bank_sampah`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `admin`
--

CREATE TABLE `admin` (
  `id_admin` bigint(20) NOT NULL,
  `username_admin` varchar(32) NOT NULL,
  `password_admin` varchar(64) NOT NULL,
  `role` varchar(16) NOT NULL,
  `tanggal_bergabung` date DEFAULT NULL,
  `nama_admin` varchar(32) NOT NULL,
  `no_telepon_admin` varchar(16) NOT NULL,
  `email_admin` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `admin`
--

INSERT INTO `admin` (`id_admin`, `username_admin`, `password_admin`, `role`, `tanggal_bergabung`, `nama_admin`, `no_telepon_admin`, `email_admin`) VALUES
(1, 'admin', '$2y$10$P.4EdtVNiL80NVNFpoz5HOrFvLdtSWdLJ4o7JvSHAPHzi4X8Vam6S', 'admin', '2026-07-17', 'admin', '0894 3452 2354', 'admin@gmail.com');

-- --------------------------------------------------------

--
-- Struktur dari tabel `detail_transaksi`
--

CREATE TABLE `detail_transaksi` (
  `id_detail` bigint(20) NOT NULL,
  `id_transaksi` bigint(20) NOT NULL,
  `id_sampah` bigint(20) NOT NULL,
  `jenis_sampah` varchar(16) NOT NULL,
  `subtotal_nominal` double NOT NULL,
  `subtotal_nominal_aktual` double NOT NULL,
  `berat_sampah` double NOT NULL,
  `berat_sampah_aktual` double NOT NULL,
  `catatan` varchar(64) NOT NULL,
  `status` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `detail_transaksi`
--

INSERT INTO `detail_transaksi` (`id_detail`, `id_transaksi`, `id_sampah`, `jenis_sampah`, `subtotal_nominal`, `subtotal_nominal_aktual`, `berat_sampah`, `berat_sampah_aktual`, `catatan`, `status`) VALUES
(4, 10, 1, 'Plastik', 8000, 0, 4, 1, 'sudah diikat plastiknya', 'Gagal'),
(5, 10, 2, 'Kertas', 6000, 0, 4, 2, 'kertasnya keras', 'Proses'),
(6, 11, 3, 'Logam', 16000, 0, 4, 5, 'test 2', 'Proses'),
(7, 12, 3, 'Logam', 48000, 0, 12, 2, 'test text catatan di history - test text catatan di history - te', 'Proses'),
(8, 13, 1, 'Plastik', 24000, 0, 12, 0, 'tes status', 'Gagal'),
(9, 14, 1, 'Plastik', 16000, 10000, 8, 5, 'saya antar', 'Proses'),
(10, 14, 3, 'Logam', 12000, 8000, 3, 2, 'saya antar juga', 'Proses'),
(11, 15, 1, 'Plastik', 16000, 0, 8, 4, 'tolong di ambil depan rumah', 'Proses'),
(12, 16, 3, 'Logam', 32000, 24000, 8, 6, 'test tabungan', 'Proses'),
(13, 18, 2, 'Kertas', 34500, 28500, 23, 19, 'test tabungan 2', 'Proses'),
(14, 19, 3, 'Logam', 12000, 0, 3, 0, '', 'Menunggu Validasi'),
(15, 20, 1, 'Plastik', 10000, 0, 5, 0, '', 'Menunggu Validasi'),
(16, 21, 2, 'Kertas', 34500, 0, 23, 0, '', 'Menunggu Validasi'),
(17, 21, 2, 'Kertas', 18000, 16500, 12, 11, 'test lagi', 'Proses'),
(18, 22, 2, 'Kertas', 10500, 0, 7, 0, '', 'Menunggu Validasi');

-- --------------------------------------------------------

--
-- Struktur dari tabel `nasabah`
--

CREATE TABLE `nasabah` (
  `id_nasabah` bigint(11) NOT NULL,
  `username_nasabah` varchar(32) NOT NULL,
  `password_nasabah` varchar(64) NOT NULL,
  `role` varchar(16) NOT NULL,
  `tanggal_bergabung` date NOT NULL,
  `nama_nasabah` varchar(64) NOT NULL,
  `jumlah_tabungan` double NOT NULL,
  `no_telepon_nasabah` varchar(32) NOT NULL,
  `email_nasabah` varchar(32) NOT NULL,
  `alamat_nasabah` varchar(64) NOT NULL,
  `kecamatan` varchar(16) NOT NULL,
  `kelurahan` varchar(16) NOT NULL,
  `rw` varchar(8) NOT NULL,
  `rt` varchar(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `nasabah`
--

INSERT INTO `nasabah` (`id_nasabah`, `username_nasabah`, `password_nasabah`, `role`, `tanggal_bergabung`, `nama_nasabah`, `jumlah_tabungan`, `no_telepon_nasabah`, `email_nasabah`, `alamat_nasabah`, `kecamatan`, `kelurahan`, `rw`, `rt`) VALUES
(1, 'edwardedo', '$2y$10$bWvNYfdNn4IeBMa6bNk0muZc3PLrC7/m3VXtOPHeWVgLhUGW/0IfO', 'nasabah', '2026-07-17', 'Edward Antonio', 40500, '0895342413657', 'edwardahhutauruk@gmail.com', 'Jl. Raya Cisauk Indah', 'Gunung Putri', 'Bojong Menteng', '012', '05'),
(2, 'udin.123', '$2y$10$I6tm7jHUQ50t0rF8yv8rVeJwnxhSuZvRk3PYEPrHDR4ypefmdCk7C', 'nasabah', '2026-07-20', 'Udin Kurniawan', 16000, '0', 'udin@gmail.com', '', '', '', '', ''),
(3, 'abdi', '$2y$10$yLDd16zs/sO6tOcP6S19xOsk4F.Nj38szMHMGhx8J8TKaRWH8uM02', 'nasabah', '2026-07-20', '', 0, '', 'abdi@gmail.com', '', '', '', '', ''),
(4, 'edi.suroso', '$2y$10$87/R2RPf6d89/ktLECBfF.Fs2BokqS0JSLBtQbv8KxOElmuPXfAJa', 'nasabah', '2026-07-20', 'Edinis Hamdan', 0, '0', 'edi@gmail.com', '', '', '', '', '');

-- --------------------------------------------------------

--
-- Struktur dari tabel `penarikan_saldo`
--

CREATE TABLE `penarikan_saldo` (
  `id_penarikan` bigint(20) NOT NULL,
  `id_nasabah` bigint(20) NOT NULL,
  `id_admin` bigint(20) DEFAULT NULL,
  `nominal_penarikan` double NOT NULL,
  `status_penarikan` varchar(24) NOT NULL,
  `tanggal_penarikan` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `penarikan_saldo`
--

INSERT INTO `penarikan_saldo` (`id_penarikan`, `id_nasabah`, `id_admin`, `nominal_penarikan`, `status_penarikan`, `tanggal_penarikan`) VALUES
(2, 1, NULL, 5000, 'Menunggu Validasi', '2026-07-21 00:00:00'),
(5, 1, NULL, 6000, 'Menunggu Validasi', '2026-07-21 05:51:23'),
(6, 1, NULL, 7000, 'Menunggu Validasi', '2026-07-21 10:52:36');

-- --------------------------------------------------------

--
-- Struktur dari tabel `petugas_lapangan`
--

CREATE TABLE `petugas_lapangan` (
  `id_petugas` bigint(20) NOT NULL,
  `username_petugas` varchar(32) NOT NULL,
  `password_petugas` varchar(64) NOT NULL,
  `role` varchar(16) NOT NULL,
  `tanggal_bergabung` date DEFAULT NULL,
  `nama_petugas` varchar(64) NOT NULL,
  `no_telepon_petugas` varchar(32) NOT NULL,
  `email_petugas` varchar(32) NOT NULL,
  `wilayah_tugas` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `petugas_lapangan`
--

INSERT INTO `petugas_lapangan` (`id_petugas`, `username_petugas`, `password_petugas`, `role`, `tanggal_bergabung`, `nama_petugas`, `no_telepon_petugas`, `email_petugas`, `wilayah_tugas`) VALUES
(1, 'petugas', '$2y$10$eR0tHurfkUkMh99IxzPYkOmPmEDMW9P/jbuqZGwNH7PPdYy6SGkP6', 'petugas', '2026-07-18', 'Budi Arhan Supardi', '089734619873', 'petugas@gmail.com', 'Intermoda');

-- --------------------------------------------------------

--
-- Struktur dari tabel `sampah`
--

CREATE TABLE `sampah` (
  `id_sampah` bigint(20) NOT NULL,
  `jenis_sampah` varchar(64) NOT NULL,
  `deskripsi_sampah` varchar(64) NOT NULL,
  `harga_sampah_per_kg` double NOT NULL,
  `jumlah_sampah_gudang` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `sampah`
--

INSERT INTO `sampah` (`id_sampah`, `jenis_sampah`, `deskripsi_sampah`, `harga_sampah_per_kg`, `jumlah_sampah_gudang`) VALUES
(1, 'Plastik', 'Botol, kantong plastik, kemasan makanan', 2000, 0),
(2, 'Kertas', 'Koran, majalah, kardus bekas', 1500, 0),
(3, 'Logam', 'Kaleng minuman, besi tua, kawat', 4000, 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `transaksi`
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
-- Dumping data untuk tabel `transaksi`
--

INSERT INTO `transaksi` (`id_transaksi`, `id_nasabah`, `id_petugas`, `tanggal_transaksi`, `total_nominal`, `total_berat`, `tanggal_penyerahan`, `metode_penyerahan`) VALUES
(10, 1, NULL, '2026-07-18 14:20:06', 14000, 8, '2026-07-23 14:19:00', 'Drop-off'),
(11, 1, NULL, '2026-07-18 15:37:50', 16000, 4, '2026-07-23 15:37:00', 'Drop-off'),
(12, 1, NULL, '2026-07-18 19:34:06', 48000, 12, '2026-07-21 19:33:00', 'Pick-up'),
(13, 1, NULL, '2026-07-18 20:20:55', 24000, 12, '2026-07-22 20:20:00', 'Drop-off'),
(14, 1, NULL, '2026-07-18 22:48:29', 28000, 11, '2026-07-25 22:48:00', 'Drop-off'),
(15, 2, NULL, '2026-07-20 14:47:48', 16000, 8, '2026-07-21 14:47:00', 'Pick-up'),
(16, 1, NULL, '2026-07-20 20:59:56', 32000, 8, '2026-07-22 20:59:00', 'Pick-up'),
(18, 1, NULL, '2026-07-20 21:06:19', 34500, 23, '2026-07-21 21:06:00', 'Drop-off'),
(19, 1, NULL, '2026-07-21 00:22:16', 12000, 3, '2026-07-29 00:22:00', 'Drop-off'),
(20, 1, NULL, '2026-07-21 00:22:34', 10000, 5, '2026-07-24 00:22:00', 'Drop-off'),
(21, 1, NULL, '2026-07-21 00:50:51', 52500, 35, '2026-07-23 00:50:00', 'Pick-up'),
(22, 1, NULL, '2026-07-21 00:53:14', 10500, 7, '2026-07-26 00:53:00', 'Pick-up');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id_admin`);

--
-- Indeks untuk tabel `detail_transaksi`
--
ALTER TABLE `detail_transaksi`
  ADD PRIMARY KEY (`id_detail`),
  ADD KEY `id_transaksi` (`id_transaksi`),
  ADD KEY `id_sampah` (`id_sampah`);

--
-- Indeks untuk tabel `nasabah`
--
ALTER TABLE `nasabah`
  ADD PRIMARY KEY (`id_nasabah`),
  ADD UNIQUE KEY `username_nasabah` (`username_nasabah`);

--
-- Indeks untuk tabel `penarikan_saldo`
--
ALTER TABLE `penarikan_saldo`
  ADD PRIMARY KEY (`id_penarikan`),
  ADD KEY `id_admin` (`id_admin`),
  ADD KEY `id_nasabah` (`id_nasabah`);

--
-- Indeks untuk tabel `petugas_lapangan`
--
ALTER TABLE `petugas_lapangan`
  ADD PRIMARY KEY (`id_petugas`);

--
-- Indeks untuk tabel `sampah`
--
ALTER TABLE `sampah`
  ADD PRIMARY KEY (`id_sampah`),
  ADD UNIQUE KEY `jenis_sampah` (`jenis_sampah`);

--
-- Indeks untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id_transaksi`),
  ADD KEY `transaksi_ibfk_1` (`id_nasabah`),
  ADD KEY `id_petugas` (`id_petugas`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `admin`
--
ALTER TABLE `admin`
  MODIFY `id_admin` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `detail_transaksi`
--
ALTER TABLE `detail_transaksi`
  MODIFY `id_detail` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT untuk tabel `nasabah`
--
ALTER TABLE `nasabah`
  MODIFY `id_nasabah` bigint(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `penarikan_saldo`
--
ALTER TABLE `penarikan_saldo`
  MODIFY `id_penarikan` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `petugas_lapangan`
--
ALTER TABLE `petugas_lapangan`
  MODIFY `id_petugas` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `sampah`
--
ALTER TABLE `sampah`
  MODIFY `id_sampah` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id_transaksi` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `detail_transaksi`
--
ALTER TABLE `detail_transaksi`
  ADD CONSTRAINT `detail_transaksi_ibfk_1` FOREIGN KEY (`id_transaksi`) REFERENCES `transaksi` (`id_transaksi`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detail_transaksi_ibfk_2` FOREIGN KEY (`id_sampah`) REFERENCES `sampah` (`id_sampah`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `penarikan_saldo`
--
ALTER TABLE `penarikan_saldo`
  ADD CONSTRAINT `penarikan_saldo_ibfk_1` FOREIGN KEY (`id_admin`) REFERENCES `admin` (`id_admin`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `penarikan_saldo_ibfk_2` FOREIGN KEY (`id_nasabah`) REFERENCES `nasabah` (`id_nasabah`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  ADD CONSTRAINT `transaksi_ibfk_1` FOREIGN KEY (`id_nasabah`) REFERENCES `nasabah` (`id_nasabah`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `transaksi_ibfk_2` FOREIGN KEY (`id_petugas`) REFERENCES `petugas_lapangan` (`id_petugas`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
