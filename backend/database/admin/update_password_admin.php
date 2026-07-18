<?php
/*
  backend/database/admin/update_password_admin.php
  Menerima POST JSON: { "password_lama": "...", "password_baru": "...", "password_konfirmasi": "..." }
*/

header('Content-Type: application/json');
require '../db.php';
session_start();

$idAdmin = $_SESSION['id_admin'] ?? 1; // TODO: sesuaikan dengan session login asli

$input = json_decode(file_get_contents('php://input'), true);
$passwordLama = $input['password_lama'] ?? '';
$passwordBaru = $input['password_baru'] ?? '';
$passwordKonfirmasi = $input['password_konfirmasi'] ?? '';

if ($passwordLama === '' || $passwordBaru === '') {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Semua field password wajib diisi']);
    exit();
}

if ($passwordBaru !== $passwordKonfirmasi) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Konfirmasi password baru tidak cocok']);
    exit();
}

// Ambil hash password saat ini buat diverifikasi
$stmt = $conn->prepare("SELECT password_admin FROM admin WHERE id_admin = ?");
$stmt->bind_param('i', $idAdmin);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$row || !password_verify($passwordLama, $row['password_admin'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Password saat ini salah']);
    exit();
}

$hashBaru = password_hash($passwordBaru, PASSWORD_BCRYPT);
$stmt = $conn->prepare("UPDATE admin SET password_admin = ? WHERE id_admin = ?");
$stmt->bind_param('si', $hashBaru, $idAdmin);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Password berhasil diubah']);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Gagal update password: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
