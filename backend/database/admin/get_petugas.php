<?php
/*
  backend/database/admin/get_petugas.php
  GET ?page=1&per_page=10&q=keyword
  Mengembalikan daftar petugas dengan pagination sederhana.

  CATATAN: tabel `petugas_lapangan` saat ini belum punya kolom "status aktif"
  atau "foto". Untuk sekarang, status aktif dianggap selalu true (lihat
  catatan di bawah file ini untuk saran penambahan kolom).
*/

header('Content-Type: application/json');
require '../db.php';

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

// Ambil data sesuai halaman
$sql = "SELECT id_petugas, nama_petugas, wilayah_tugas, email_petugas FROM petugas_lapangan $where ORDER BY nama_petugas ASC LIMIT ? OFFSET ?";
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
        'aktif' => true, // TODO: sambungkan ke kolom status aktif kalau sudah ditambahkan
    ];
}

echo json_encode([
    'success' => true,
    'data' => $data,
    'page' => $page,
    'per_page' => $perPage,
    'total' => $total,
]);

$stmt->close();
$conn->close();

/*
  SARAN: kalau fitur toggle "Status Aktif" di UI mau benar-benar tersimpan,
  tabel `petugas_lapangan` perlu kolom tambahan, misalnya:
  ALTER TABLE petugas_lapangan ADD COLUMN status_aktif TINYINT(1) NOT NULL DEFAULT 1;
*/
