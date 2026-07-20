<?php
/*
  backend/database/admin/tambah_jenis_sampah.php
  Menerima POST JSON: { "nama": "Botol Kaca", "harga": 1000 }
  Kolom "deskripsi" di UI belum ada tempatnya di tabel `sampah` saat ini,
  jadi sementara tidak disimpan (lihat catatan di bawah).
*/

header('Content-Type: application/json');
require '../db.php';

$input = json_decode(file_get_contents('php://input'), true);

$nama = trim($input['nama'] ?? '');
$harga = $input['harga'] ?? null;

if ($nama === '' || $harga === null || !is_numeric($harga)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Data tidak lengkap atau tidak valid']);
    exit();
}

$stmt = $conn->prepare("INSERT INTO sampah (jenis_sampah, harga_sampah_per_kg, jumlah_sampah_gudang, deskripsi) VALUES (?, ?, 0, ?)");
$stmt->bind_param('sds', $nama, $harga, $deskripsi);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Kategori baru berhasil ditambahkan', 'id' => $stmt->insert_id]);
} else {
    http_response_code(500);
    // Kemungkinan gagal karena nama jenis sampah sudah ada (kolom `jenis_sampah` UNIQUE)
    echo json_encode(['success' => false, 'message' => 'Gagal menambah kategori: ' . $stmt->error]);
}

$stmt->close();
$conn->close();

/*
  CATATAN: kolom "Deskripsi Singkat" yang ada di form Tambah Kategori (UI)
  belum punya tempat di tabel `sampah` sekarang (cuma ada jenis_sampah,
  harga_sampah_per_kg, jumlah_sampah_gudang). Kalau memang dibutuhkan,
  perlu tambah kolom baru dulu, misalnya:
  ALTER TABLE sampah ADD COLUMN deskripsi VARCHAR(64) NOT NULL DEFAULT '';
*/
