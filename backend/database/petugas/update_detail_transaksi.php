<?php
    session_start();
    header("Content-Type: application/json");

    require_once '../db.php';

    $data = json_decode(file_get_contents('php://input'), true);

    $id_detail = $data['id_detail'] ?? '';
    $status = $data['status'] ?? '';
    $berat_aktual = $data['berat_aktual'] ?? null;

    try {
        if ($status === 'Proses') {
            $statement = $conn->prepare("UPDATE detail_transaksi SET status = ?, berat_sampah_aktual = ? WHERE id_detail = ?");
            $statement->bind_param("sdi", $status, $berat_aktual, $id_detail);
        } else {
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