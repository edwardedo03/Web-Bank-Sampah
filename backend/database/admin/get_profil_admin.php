<?php
/*
  backend/database/admin/get_profil_admin.php
  Mengambil data admin yang sedang login (dari session).
*/

header('Content-Type: application/json');
require '../db.php';
session_start();

// TODO: sesuaikan dengan nama session yang dipakai backend/database/login_db.php
$idAdmin = $_SESSION['id_admin'] ?? 1; // fallback ke id 1 untuk testing kalau session belum ada

$stmt = $conn->prepare("SELECT id_admin, username_admin, nama_admin, email_admin, no_telepon_admin FROM admin WHERE id_admin = ?");
$stmt->bind_param('i', $idAdmin);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();

if (!$row) {
    http_response_code(404);
    echo json_encode(['success' => false, 'message' => 'Admin tidak ditemukan']);
    exit();
}

$nama = $row['nama_admin'] !== '' ? $row['nama_admin'] : $row['username_admin'];
$namaParts = explode(' ', trim($nama));
$inisial = strtoupper(substr($namaParts[0], 0, 1) . substr(end($namaParts), 0, 1));

echo json_encode([
    'success' => true,
    'id_admin' => $row['id_admin'],
    'username' => $row['username_admin'],
    'nama' => $nama,
    'email' => $row['email_admin'],
    'telepon' => $row['no_telepon_admin'],
    'inisial' => $inisial,
]);

$stmt->close();
$conn->close();
