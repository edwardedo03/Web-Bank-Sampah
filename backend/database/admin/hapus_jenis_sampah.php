<?php

header('Content-Type: application/json');
require '../db.php';

$input = json_decode(file_get_contents('php://input'), true);
$id = $input['id'] ?? null;

if (!$id) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'ID tidak valid']);
    exit();
}

$stmt = $conn->prepare("DELETE FROM sampah WHERE id_sampah = ?");
$stmt->bind_param('i', $id);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        echo json_encode(['success' => true, 'message' => 'Jenis sampah berhasil dihapus']);
    } else {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Data tidak ditemukan']);
    }
} else {
    http_response_code(500);
    // Kemungkinan gagal karena masih ada relasi ke tabel detail_transaksi
    echo json_encode(['success' => false, 'message' => 'Gagal menghapus (kemungkinan jenis sampah ini masih terpakai di riwayat transaksi): ' . $stmt->error]);
}

$stmt->close();
$conn->close();
