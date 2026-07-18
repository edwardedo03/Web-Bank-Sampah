<?php
    session_start();

    header("Content-Type: application/json");

    require_once '../db.php';

    $id_nasabah = $_GET['id_akun'] ?? ($_SESSION['id_akun'] ?? '');

    $statement = $conn->prepare("SELECT t.tanggal_penyerahan, t.status, d.jenis_sampah, d.berat_sampah, d.subtotal_nominal, d.catatan 
              FROM transaksi t
              JOIN detail_transaksi d ON t.id_transaksi = d.id_transaksi
              WHERE t.id_nasabah = ? 
              ORDER BY t.tanggal_penyerahan DESC");

    try {
        $statement->bind_param("i", $id_nasabah);
        $statement->execute();
        $result = $statement->get_result();

        $history = [];
        
        while ($row = $result->fetch_assoc()) {
            $history[] = $row;
        }

        echo json_encode([
            'success' => true,
            'history' => $history
        ]);

    } catch(mysqli_sql_exception $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Gagal mengambil data transaksi: ' . $e->getMessage()
        ]);
    }

    $statement->close();
    $conn->close();