<?php
/*
  backend/database/admin/get_riwayat_transaksi.php
  GET ?q=keyword

  Tiap baris hasil = 1 baris detail_transaksi (1 jenis sampah dalam 1 transaksi),
  dengan status ASLI dari kolom `status` di tabel detail_transaksi
  (bukan lagi di-hardcode "SELESAI").
*/

header('Content-Type: application/json');
require '../db.php';

$keyword = isset($_GET['q']) ? trim($_GET['q']) : '';

$sql = "
    SELECT
        dt.id_detail,
        n.nama_nasabah,
        t.total_nominal,
        t.tanggal_transaksi,
        dt.jenis_sampah,
        dt.berat_sampah,
        dt.status
    FROM detail_transaksi dt
    JOIN transaksi t ON t.id_transaksi = dt.id_transaksi
    JOIN nasabah n ON n.id_nasabah = t.id_nasabah
";
$params = [];
$types = '';
if ($keyword !== '') {
    $sql .= " WHERE n.nama_nasabah LIKE ? ";
    $params[] = '%' . $keyword . '%';
    $types .= 's';
}
$sql .= " ORDER BY t.tanggal_transaksi DESC";

$stmt = $conn->prepare($sql);
if ($params) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$res = $stmt->get_result();

$data = [];
while ($row = $res->fetch_assoc()) {
    $namaParts = explode(' ', trim($row['nama_nasabah']));
    $inisial = strtoupper(substr($namaParts[0], 0, 1) . substr(end($namaParts), 0, 1));

    $data[] = [
        'id' => $row['id_detail'],
        'nama' => $row['nama_nasabah'],
        'inisial' => $inisial,
        'jenis_transaksi' => 'Setoran',
        'jenis_sampah' => $row['jenis_sampah'],
        'berat' => $row['berat_sampah'] . ' kg',
        'tanggal' => date('d M Y', strtotime($row['tanggal_transaksi'])),
        'status' => $row['status'], // status ASLI: Proses / Gagal / Menunggu Validasi / dll
    ];
}

echo json_encode(['success' => true, 'data' => $data]);

$stmt->close();
$conn->close();
