<?php
/*
  backend/database/admin/get_riwayat_transaksi.php
  GET ?q=keyword

  Menggabungkan transaksi + nasabah + detail_transaksi (jenis sampah).
  Setiap baris detail_transaksi jadi 1 baris tersendiri di hasilnya
  (kalau 1 transaksi ada beberapa jenis sampah, akan muncul beberapa baris).

  CATATAN: tabel `transaksi` tidak punya kolom "jenis transaksi"
  (setoran/penarikan) atau "status" (selesai/proses) — datanya cuma
  transaksi setoran sampah. Untuk sekarang field itu di-hardcode jadi
  "Setoran" dan "SELESAI". Kalau nanti tabel penarikan_saldo sudah dibuat,
  file ini perlu di-update untuk gabungin data dari 2 sumber.
*/

header('Content-Type: application/json');
require '../db.php';

$keyword = isset($_GET['q']) ? trim($_GET['q']) : '';

$sql = "
    SELECT
        t.id_transaksi,
        n.nama_nasabah,
        t.total_nominal,
        t.tanggal_transaksi,
        dt.jenis_sampah,
        dt.berat_sampah
    FROM transaksi t
    JOIN nasabah n ON n.id_nasabah = t.id_nasabah
    LEFT JOIN detail_transaksi dt ON dt.id_transaksi = t.id_transaksi
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
        'id' => $row['id_transaksi'],
        'nama' => $row['nama_nasabah'],
        'inisial' => $inisial,
        'jenis_transaksi' => 'Setoran',
        'jenis_sampah' => $row['jenis_sampah'] ?? '-',
        'nilai' => 'Rp ' . number_format($row['total_nominal'], 0, ',', '.'),
        'tanggal' => date('d M Y', strtotime($row['tanggal_transaksi'])),
        'status' => 'SELESAI',
    ];
}

echo json_encode(['success' => true, 'data' => $data]);

$stmt->close();
$conn->close();
