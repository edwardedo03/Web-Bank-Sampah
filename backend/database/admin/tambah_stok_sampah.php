<?php
/*
  backend/database/admin/tambah_stok_sampah.php
  Menerima POST JSON: { "id": 1, "jumlah": 50 }
  Menambahkan (bukan mengganti) stok yang sudah ada.
*/

header('Content-Type: application/json');
require '../db.php';

$input = json_decode(file_get_contents('php://input'), true);

$id = $input['id'] ?? null;
$jumlah = $input['jumlah'] ?? null;

if (!$id || $jumlah === null || !is_numeric($jumlah) || $jumlah < 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Data tidak lengkap atau tidak valid']);
    exit();
}

$stmt = $conn->prepare("UPDATE sampah SET jumlah_sampah_gudang = jumlah_sampah_gudang + ? WHERE id_sampah = ?");
$stmt->bind_param('ii', $jumlah, $id);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Stok berhasil ditambahkan']);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Gagal menambah stok: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
