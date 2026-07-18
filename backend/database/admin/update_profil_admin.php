<?php
/*
  backend/database/admin/update_profil_admin.php
  Menerima POST JSON: { "nama": "...", "email": "...", "telepon": "..." }
*/

header('Content-Type: application/json');
require '../db.php';
session_start();

$idAdmin = $_SESSION['id_admin'] ?? 1; // TODO: sesuaikan dengan session login asli

$input = json_decode(file_get_contents('php://input'), true);
$nama = trim($input['nama'] ?? '');
$email = trim($input['email'] ?? '');
$telepon = trim($input['telepon'] ?? '');

if ($nama === '' || $email === '') {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Nama dan email wajib diisi']);
    exit();
}

$stmt = $conn->prepare("UPDATE admin SET nama_admin = ?, email_admin = ?, no_telepon_admin = ? WHERE id_admin = ?");
$stmt->bind_param('sssi', $nama, $email, $telepon, $idAdmin);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Profil berhasil diperbarui']);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Gagal update profil: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
