<?php

header('Content-Type: application/json');
require '../db.php';
session_start();

$idAdmin = $_SESSION['id_admin'] ?? 1; 

$input = json_decode(file_get_contents('php://input'), true);
$idPenarikan = $input['id'] ?? null;
$aksi = $input['aksi'] ?? null;

if (!$idPenarikan || !in_array($aksi, ['setuju', 'tolak'], true)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Data tidak lengkap atau tidak valid']);
    exit();
}

$stmt = $conn->prepare("
    SELECT ps.id_nasabah, ps.nominal_penarikan, ps.status_penarikan, n.jumlah_tabungan, n.nama_nasabah
    FROM penarikan_saldo ps
    JOIN nasabah n ON n.id_nasabah = ps.id_nasabah
    WHERE ps.id_penarikan = ?
");
$stmt->bind_param('i', $idPenarikan);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$row) {
    http_response_code(404);
    echo json_encode(['success' => false, 'message' => 'Permintaan penarikan tidak ditemukan']);
    exit();
}

if ($row['status_penarikan'] !== 'Menunggu Validasi') {
    http_response_code(409);
    echo json_encode(['success' => false, 'message' => 'Permintaan ini sudah pernah diproses sebelumnya']);
    exit();
}

if ($aksi === 'tolak') {
    $stmt = $conn->prepare("UPDATE penarikan_saldo SET status_penarikan = 'Ditolak', id_admin = ? WHERE id_penarikan = ?");
    $stmt->bind_param('ii', $idAdmin, $idPenarikan);
    $stmt->execute();
    $stmt->close();
    echo json_encode(['success' => true, 'message' => 'Penarikan ' . $row['nama_nasabah'] . ' ditolak']);
    $conn->close();
    exit();
}

if ($row['jumlah_tabungan'] < $row['nominal_penarikan']) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Saldo nasabah tidak mencukupi untuk penarikan ini']);
    exit();
}

$conn->begin_transaction();
try {
    $stmt = $conn->prepare("UPDATE nasabah SET jumlah_tabungan = jumlah_tabungan - ? WHERE id_nasabah = ?");
    $stmt->bind_param('di', $row['nominal_penarikan'], $row['id_nasabah']);
    $stmt->execute();
    $stmt->close();

    $stmt = $conn->prepare("UPDATE penarikan_saldo SET status_penarikan = 'Disetujui', id_admin = ? WHERE id_penarikan = ?");
    $stmt->bind_param('ii', $idAdmin, $idPenarikan);
    $stmt->execute();
    $stmt->close();

    $conn->commit();
    echo json_encode(['success' => true, 'message' => 'Penarikan ' . $row['nama_nasabah'] . ' disetujui, saldo telah dipotong']);
} catch (mysqli_sql_exception $e) {
    $conn->rollback();
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Gagal memproses penarikan: ' . $e->getMessage()]);
}

$conn->close();
