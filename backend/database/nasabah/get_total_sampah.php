<?php
    session_start();

    header("Content-Type: application/json");

    require_once '../db.php';

    $id_nasabah = $_GET['id_akun'] ?? ($_SESSION['id_akun'] ?? '');

    $statement = $conn->prepare("SELECT total_berat FROM transaksi WHERE id_nasabah = ?");

    try {
        $statement->bind_param("i", $id_nasabah);
        $statement->execute();
        $result = $statement->get_result();
    
        $total_sampah = 0;

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $total_sampah += $row['total_berat'];
            }
    
            echo json_encode([
                'success' => true,
                'total_sampah' => $total_sampah
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Nasabah tidak ditemukan'
            ]);
        }
    } catch (mysqli_sql_exception $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Terjadi kesalahan: ' . $e->getMessage()
        ]);
    }


    $statement->close();
    $conn->close();