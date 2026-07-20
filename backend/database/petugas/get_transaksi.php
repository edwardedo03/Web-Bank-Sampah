<?php
    session_start();
    header("Content-Type: application/json");
    require_once '../db.php';

    $username_nasabah = $_GET['username_nasabah'] ?? '';

    $statement_nasabah = $conn->prepare("SELECT * FROM nasabah WHERE username_nasabah = ?");
    $statement_nasabah->bind_param("s", $username_nasabah);
    $statement_nasabah->execute();
    $result_nasabah = $statement_nasabah->get_result();
    $nasabah = $result_nasabah->fetch_assoc();

    $id_nasabah = $nasabah['id_nasabah'];

        $statement_transaksi = $conn->prepare("SELECT 
                                    id_transaksi,
                                    total_nominal,
                                    total_berat,
                                    status
                                FROM transaksi 
                                WHERE id_nasabah = ? AND status = 'Menunggu Validasi'");

        $statement_transaksi->bind_param("i", $id_nasabah);
        $statement_transaksi->execute();
        $transaksi = $statement_transaksi->get_result();

        $list_transaksi = [];
        while ($row = $transaksi->fetch_assoc()) {
            $list_transaksi[] = $row;
        }

    echo json_encode([
        'success' => true,
        'nasabah' => $nasabah,
        'transaksi' => $list_transaksi
    ]);

    $conn->close();