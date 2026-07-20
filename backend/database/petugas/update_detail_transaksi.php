<?php
session_start();
header("Content-Type: application/json");

require_once '../db.php';

// Ambil data JSON dari AJAX
$data = json_decode(file_get_contents('php://input'), true);

$id_detail = $data['id_detail'] ?? '';
$status = $data['status'] ?? ''; // 'Proses' atau 'Gagal'
$berat_aktual = $data['berat_aktual'] ?? null;

if (empty($id_detail) || empty($status)) {
    echo json_encode([
        'success' => false,
        'message' => 'ID Detail dan status wajib diisi!'
    ]);
    exit;
}

try {
    // Jika disetujui (Status: Proses), update status dan berat_sampah_aktual
    if ($status === 'Proses') {
        if ($berat_aktual === null || $berat_aktual === '') {
            echo json_encode([
                'success' => false,
                'message' => 'Berat aktual wajib diisi saat menyimpan!'
            ]);
            exit;
        }

        $statement = $conn->prepare("UPDATE detail_transaksi SET status = ?, berat_sampah_aktual = ? WHERE id_detail = ?");
        $statement->bind_param("sdi", $status, $berat_aktual, $id_detail);
    } else {
        // Jika ditolak (Status: Gagal), cukup update statusnya saja
        $statement = $conn->prepare("UPDATE detail_transaksi SET status = ? WHERE id_detail = ?");
        $statement->bind_param("si", $status, $id_detail);
    }

    if ($statement->execute()) {
        echo json_encode([
            'success' => true,
            'message' => 'Detail transaksi berhasil diupdate menjadi ' . $status
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Gagal memperbarui detail transaksi.'
        ]);
    }

    $statement->close();
} catch (mysqli_sql_exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error database: ' . $e->getMessage()
    ]);
}

$conn->close();