<?php
    session_start();
    header("Content-Type: application/json");

    require_once '../db.php';

    $decode = json_decode(file_get_contents('php://input'), true);

    $id_detail = $decode['id_detail'] ?? '';
    $status = $decode['status'] ?? '';
    $berat_aktual = $decode['berat_aktual'] ?? null;
    $subtotal_aktual = $decode['subtotal_aktual'] ?? null;

    $conn->begin_transaction();

    try {
        if ($status === 'Proses') {
            $statement = $conn->prepare("UPDATE detail_transaksi SET status = ?, berat_sampah_aktual = ?, subtotal_nominal_aktual = ? WHERE id_detail = ?");
            $statement->bind_param("sddi", $status, $berat_aktual, $subtotal_aktual, $id_detail);
            $statement->execute();
            $statement->close();

            $statement_nasabah = $conn->prepare("
                                    SELECT t.id_nasabah 
                                    FROM detail_transaksi dt 
                                    JOIN transaksi t ON dt.id_transaksi = t.id_transaksi 
                                    WHERE dt.id_detail = ?");
            $statement_nasabah->bind_param("i", $id_detail);
            $statement_nasabah->execute();
            $result_nasabah = $statement_nasabah->get_result()->fetch_assoc();
            $statement_nasabah->close();

            if ($result_nasabah) {
                $id_nasabah = $result_nasabah['id_nasabah'];

                $statement_update_tabungan = $conn->prepare("UPDATE nasabah SET jumlah_tabungan = jumlah_tabungan + ? WHERE id_nasabah = ?");
                $statement_update_tabungan->bind_param("di", $subtotal_aktual, $id_nasabah);
                $statement_update_tabungan->execute();
                $statement_update_tabungan->close();
            } else {
                throw new Exception("Nasabah untuk transaksi tidak ditemukan.");
            }
        } else {
            $statement = $conn->prepare("UPDATE detail_transaksi SET status = ? WHERE id_detail = ?");
            $statement->bind_param("si", $status, $id_detail);
            $statement->execute();
            $statement->close();
        }

        $conn->commit();

        echo json_encode([
            'success' => true,
            'message' => 'Detail transaksi berhasil diupdate menjadi ' . $status
        ]);

    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ]);
    }

    $conn->close();