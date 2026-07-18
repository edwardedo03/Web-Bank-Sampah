<?php
/*
  backend/database/admin/dashboard_data.php

  Dipanggil dari pages/admin/admin_dashboard.html lewat fetch().
  Mengembalikan JSON:
  {
    "total_nasabah": 1284,
    "total_petugas": 12,
    "total_saldo": 42500000,
    "transaksi_terbaru": [ {...}, {...} ]
  }
*/

header('Content-Type: application/json');
require '../db.php'; // koneksi $conn (mysqli) sudah tersedia dari sini

// TODO: sesuaikan nama session ini dengan yang dipakai di backend/database/login_db.php
session_start();
if (!isset($_SESSION['id_admin']) && !isset($_SESSION['id_akun'])) {
    // Sementara tidak memaksa redirect supaya gampang ditest — nanti aktifkan ini:
    // http_response_code(401);
    // echo json_encode(['success' => false, 'message' => 'Belum login']);
    // exit();
}

$result = [
    'success' => true,
    'total_nasabah' => 0,
    'total_petugas' => 0,
    'total_saldo' => 0,
    'transaksi_terbaru' => [],
];

// Total nasabah
$q = $conn->query("SELECT COUNT(*) AS total FROM nasabah");
$result['total_nasabah'] = (int) $q->fetch_assoc()['total'];

// Total petugas
$q = $conn->query("SELECT COUNT(*) AS total FROM petugas_lapangan");
$result['total_petugas'] = (int) $q->fetch_assoc()['total'];

// Total saldo (jumlah tabungan semua nasabah)
$q = $conn->query("SELECT SUM(jumlah_tabungan) AS total FROM nasabah");
$row = $q->fetch_assoc();
$result['total_saldo'] = (float) ($row['total'] ?? 0);

// 5 transaksi terbaru, gabung dengan nama nasabah dan jenis sampah pertamanya
$sql = "
    SELECT
        t.id_transaksi,
        n.nama_nasabah,
        t.total_nominal,
        t.total_berat,
        t.tanggal_transaksi,
        dt.jenis_sampah
    FROM transaksi t
    JOIN nasabah n ON n.id_nasabah = t.id_nasabah
    LEFT JOIN detail_transaksi dt ON dt.id_transaksi = t.id_transaksi
    GROUP BY t.id_transaksi
    ORDER BY t.tanggal_transaksi DESC
    LIMIT 5
";
$q = $conn->query($sql);
while ($row = $q->fetch_assoc()) {
    $namaParts = explode(' ', trim($row['nama_nasabah']));
    $inisial = strtoupper(substr($namaParts[0], 0, 1) . substr(end($namaParts), 0, 1));

    $result['transaksi_terbaru'][] = [
        'id' => $row['id_transaksi'],
        'nama' => $row['nama_nasabah'],
        'inisial' => $inisial,
        'jenis_sampah' => $row['jenis_sampah'] ?? '-',
        'berat' => $row['total_berat'] . ' kg',
        'total' => 'Rp ' . number_format($row['total_nominal'], 0, ',', '.'),
        'status' => 'SELESAI', // tidak ada kolom status di tabel transaksi, default selesai
    ];
}

echo json_encode($result);
$conn->close();
