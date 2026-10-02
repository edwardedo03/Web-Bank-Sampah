<?php

header('Content-Type: application/json');
require '../db.php';

$input = json_decode(file_get_contents('php://input'), true);

$id = $input['id'] ?? null;
$hargaBaru = $input['harga_baru'] ?? null;

if (!$id || $hargaBaru === null || !is_numeric($hargaBaru)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Data tidak lengkap atau tidak valid']);
    exit();
}

$stmt = $conn->prepare("UPDATE sampah SET harga_sampah_per_kg = ? WHERE id_sampah = ?");
$stmt->bind_param('di', $hargaBaru, $id);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Harga berhasil diperbarui']);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Gagal update harga: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
