<?php
/*
  backend/database/admin/update_status_petugas.php
  Menerima POST JSON: { "id_petugas": 5, "aktif": true }

  CATATAN: tabel `petugas_lapangan` saat ini BELUM punya kolom status aktif.
  Endpoint ini sudah siap dipakai, tapi baru akan benar-benar menyimpan
  setelah kolomnya ditambahkan, misalnya lewat query:

  ALTER TABLE petugas_lapangan ADD COLUMN status_aktif TINYINT(1) NOT NULL DEFAULT 1;

  Sebelum kolom itu ada, endpoint ini hanya mengembalikan sukses tanpa
  benar-benar mengubah apa pun di database (supaya UI tetap bisa ditest).
*/

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

// Cek dulu apakah kolom status_aktif sudah ada di tabel
$cek = $conn->query("SHOW COLUMNS FROM petugas_lapangan LIKE 'status_aktif'");
if ($cek->num_rows > 0) {
    $stmt = $conn->prepare("UPDATE petugas_lapangan SET status_aktif = ? WHERE id_petugas = ?");
    $stmt->bind_param('ii', $aktif, $id);
    $stmt->execute();
    $stmt->close();
    echo json_encode(['success' => true, 'message' => 'Status berhasil diperbarui']);
} else {
    // Kolom belum ada — beri tahu dengan jelas supaya gampang ketauan saat testing
    echo json_encode(['success' => true, 'message' => 'Status diterima (belum tersimpan permanen — kolom status_aktif belum ada di tabel petugas_lapangan)']);
}

$conn->close();
