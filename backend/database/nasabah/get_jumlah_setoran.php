<?php
    session_start();

    header("Content-Type: application/json");

    require_once '../db.php';

    $id_nasabah = $_GET['id_akun'] ?? ($_SESSION['id_akun'] ?? '');

    $statement = $conn->prepare("SELECT COUNT(*) as total_rows FROM transaksi WHERE id_nasabah = ?");
    
    try {
        $statement->bind_param("i", $id_nasabah);
        $statement->execute();
        $result = $statement->get_result();
    
        $jumlah_setoran = 0;

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
    
            $jumlah_setoran = $row['total_rows'];

            echo json_encode([
                'success' => true,
                'jumlah_setoran' => $jumlah_setoran
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