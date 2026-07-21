<?php
/*
  backend/database/admin/get_penarikan_pending.php
  Mengambil semua permintaan penarikan saldo yang statusnya "Menunggu Validasi".
*/

header('Content-Type: application/json');
require '../db.php';

$sql = "
    SELECT
        ps.id_penarikan,
        ps.id_nasabah,
        n.nama_nasabah,
        ps.nominal_penarikan,
        ps.status_penarikan,
        ps.tanggal_penarikan
    FROM penarikan_saldo ps
    JOIN nasabah n ON n.id_nasabah = ps.id_nasabah
    WHERE ps.status_penarikan = 'Menunggu Validasi'
    ORDER BY ps.tanggal_penarikan ASC
";
$q = $conn->query($sql);

$data = [];
while ($row = $q->fetch_assoc()) {
    $namaParts = explode(' ', trim($row['nama_nasabah']));
    $inisial = strtoupper(substr($namaParts[0], 0, 1) . substr(end($namaParts), 0, 1));

    $data[] = [
        'id' => $row['id_penarikan'],
        'id_nasabah' => $row['id_nasabah'],
        'nama' => $row['nama_nasabah'],
        'inisial' => $inisial,
        'nominal' => (float) $row['nominal_penarikan'],
        'nominal_format' => 'Rp ' . number_format($row['nominal_penarikan'], 0, ',', '.'),
        'status' => $row['status_penarikan'],
        'tanggal' => date('d M Y, H:i', strtotime($row['tanggal_penarikan'])),
    ];
}

echo json_encode(['success' => true, 'data' => $data]);
$conn->close();
