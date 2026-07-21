<?php
/*
  backend/database/admin/get_petugas.php
  GET ?page=1&per_page=10&q=keyword
  Mengembalikan daftar petugas dengan pagination sederhana.

  Otomatis cek apakah kolom `status_aktif` sudah ada di tabel
  `petugas_lapangan`. Kalau sudah ada, nilai aktif/nonaktif diambil asli
  dari database. Kalau belum ada, semua petugas dianggap aktif (true)
  sebagai fallback sementara.
*/

header('Content-Type: application/json');
require '../db.php';

// Cek keberadaan kolom status_aktif sekali di awal
$cekKolom = $conn->query("SHOW COLUMNS FROM petugas_lapangan LIKE 'status_aktif'");
$adaKolomStatus = $cekKolom->num_rows > 0;

$page = isset($_GET['page']) ? max(1, (int) $_GET['page']) : 1;
$perPage = 10;
$offset = ($page - 1) * $perPage;
$keyword = isset($_GET['q']) ? trim($_GET['q']) : '';

$where = '';
$params = [];
$types = '';
if ($keyword !== '') {
    $where = "WHERE nama_petugas LIKE ? OR wilayah_tugas LIKE ?";
    $likeKeyword = '%' . $keyword . '%';
    $params = [$likeKeyword, $likeKeyword];
    $types = 'ss';
}

// Hitung total data (untuk info pagination)
$countSql = "SELECT COUNT(*) AS total FROM petugas_lapangan $where";
$countStmt = $conn->prepare($countSql);
if ($params) {
    $countStmt->bind_param($types, ...$params);
}
$countStmt->execute();
$total = (int) $countStmt->get_result()->fetch_assoc()['total'];
$countStmt->close();

// Ambil data sesuai halaman — kolom status_aktif ikut diambil kalau ada
$kolomStatus = $adaKolomStatus ? ', status_aktif' : '';
$sql = "SELECT id_petugas, nama_petugas, wilayah_tugas, email_petugas $kolomStatus FROM petugas_lapangan $where ORDER BY nama_petugas ASC LIMIT ? OFFSET ?";
$stmt = $conn->prepare($sql);

if ($params) {
    $allParams = array_merge($params, [$perPage, $offset]);
    $allTypes = $types . 'ii';
    $stmt->bind_param($allTypes, ...$allParams);
} else {
    $stmt->bind_param('ii', $perPage, $offset);
}
$stmt->execute();
$res = $stmt->get_result();

$data = [];
while ($row = $res->fetch_assoc()) {
    $namaParts = explode(' ', trim($row['nama_petugas']));
    $inisial = strtoupper(substr($namaParts[0], 0, 1) . substr(end($namaParts), 0, 1));

    $data[] = [
        'id' => $row['id_petugas'],
        'nama' => $row['nama_petugas'],
        'inisial' => $inisial,
        'wilayah' => $row['wilayah_tugas'],
        'email' => $row['email_petugas'],
        'aktif' => $adaKolomStatus ? (bool) $row['status_aktif'] : true,
    ];
}

echo json_encode([
    'success' => true,
    'data' => $data,
    'page' => $page,
    'per_page' => $perPage,
    'total' => $total,
    'kolom_status_tersedia' => $adaKolomStatus, // info tambahan buat debugging kalau perlu
]);

$stmt->close();
$conn->close();
