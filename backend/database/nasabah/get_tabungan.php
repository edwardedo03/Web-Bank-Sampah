<?php
    session_start();

    header("Content-Type: application/json");

    require_once '../db.php';

    $id_nasabah = $_GET['id_akun'] ?? ($_SESSION['id_akun'] ?? '');

    $statement = $conn->prepare("SELECT jumlah_tabungan FROM nasabah WHERE id_nasabah = ?");
    
    try {
        $statement->bind_param("i", $id_nasabah);
        $statement->execute();
        $result = $statement->get_result();
    
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
    
            echo json_encode([
                'success' => true,
                'jumlah_tabungan' => $row['jumlah_tabungan']
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