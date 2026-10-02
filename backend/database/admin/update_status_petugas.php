<?php

header('Content-Type: application/json');
require '../db.php';

$input = json_decode(file_get_contents('php://input'), true);
$id = $input['id_petugas'] ?? null;
$aktif = isset($input['aktif']) ? (int) (bool) $input['aktif'] : null;

if (!$id || $aktif === null) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Data tidak lengkap']);
    exit();
}

$cek = $conn->query("SHOW COLUMNS FROM petugas_lapangan LIKE 'status_aktif'");
if ($cek->num_rows > 0) {
    $stmt = $conn->prepare("UPDATE petugas_lapangan SET status_aktif = ? WHERE id_petugas = ?");
    $stmt->bind_param('ii', $aktif, $id);
    $stmt->execute();
    $stmt->close();
    echo json_encode(['success' => true, 'message' => 'Status berhasil diperbarui']);
} else {
    echo json_encode(['success' => true, 'message' => 'Status diterima (belum tersimpan permanen — kolom status_aktif belum ada di tabel petugas_lapangan)']);
}

$conn->close();
