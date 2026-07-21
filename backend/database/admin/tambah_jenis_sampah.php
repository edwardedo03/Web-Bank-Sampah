<?php

header('Content-Type: application/json');
require '../db.php';

$input = json_decode(file_get_contents('php://input'), true);

$nama = trim($input['nama'] ?? '');
$harga = $input['harga'] ?? null;
$deskripsi = trim($input['deskripsi'] ?? '');

if ($nama === '' || $harga === null || !is_numeric($harga)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Data tidak lengkap atau tidak valid']);
    exit();
}

$stmt = $conn->prepare("INSERT INTO sampah (jenis_sampah, deskripsi_sampah, harga_sampah_per_kg, jumlah_sampah_gudang) VALUES (?, ?, ?, 0)");
$stmt->bind_param('ssd', $nama, $deskripsi, $harga);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Kategori baru berhasil ditambahkan', 'id' => $stmt->insert_id]);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Gagal menambah kategori: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
