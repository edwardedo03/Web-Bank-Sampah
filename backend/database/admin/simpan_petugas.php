<?php
/*
  backend/database/admin/simpan_petugas.php
  Menerima POST JSON:
  - Tambah baru: { "mode": "tambah", "nama": "...", "wilayah": "...", "email": "...", "no_telepon": "...", "password": "..." }
  - Edit:        { "mode": "edit", "id": 5, "nama": "...", "wilayah": "..." }

  CATATAN PENTING: tabel `petugas_lapangan` pakai `id_petugas` berupa angka
  auto-increment (bigint), BUKAN kode seperti "SS-00124" yang ada di desain
  Figma. Untuk sekarang endpoint ini pakai id_petugas asli dari database.
  Kalau format kode "SS-00124" itu memang wajib ada, perlu kolom baru
  khusus untuk itu di tabel `petugas_lapangan`.
*/

header('Content-Type: application/json');
require '../db.php';

$input = json_decode(file_get_contents('php://input'), true);
$mode = $input['mode'] ?? 'tambah';

if ($mode === 'edit') {
    $id = $input['id'] ?? null;
    $nama = trim($input['nama'] ?? '');
    $wilayah = trim($input['wilayah'] ?? '');

    if (!$id || $nama === '' || $wilayah === '') {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Data tidak lengkap']);
        exit();
    }

    $stmt = $conn->prepare("UPDATE petugas_lapangan SET nama_petugas = ?, wilayah_tugas = ? WHERE id_petugas = ?");
    $stmt->bind_param('ssi', $nama, $wilayah, $id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Data petugas berhasil diperbarui']);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Gagal update: ' . $stmt->error]);
    }
    $stmt->close();
} else {
    // mode tambah
    $nama = trim($input['nama'] ?? '');
    $wilayah = trim($input['wilayah'] ?? '');
    $email = trim($input['email'] ?? '');
    $noTelepon = trim($input['no_telepon'] ?? '');
    $passwordPlain = $input['password'] ?? null;

    if ($nama === '' || $wilayah === '' || $email === '' || !$passwordPlain) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Nama, wilayah, email, dan password wajib diisi']);
        exit();
    }

    // Username petugas dibuat otomatis dari nama (huruf kecil, tanpa spasi) + angka acak
    $usernameBase = strtolower(str_replace(' ', '', $nama));
    $username = $usernameBase . rand(100, 999);
    $passwordHash = password_hash($passwordPlain, PASSWORD_BCRYPT);
    $role = 'petugas';

    $stmt = $conn->prepare("INSERT INTO petugas_lapangan (username_petugas, password_petugas, role, nama_petugas, no_telepon_petugas, email_petugas, wilayah_tugas) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param('sssssss', $username, $passwordHash, $role, $nama, $noTelepon, $email, $wilayah);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Petugas baru berhasil ditambahkan', 'id' => $stmt->insert_id, 'username' => $username]);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Gagal menambah petugas: ' . $stmt->error]);
    }
    $stmt->close();
}

$conn->close();
