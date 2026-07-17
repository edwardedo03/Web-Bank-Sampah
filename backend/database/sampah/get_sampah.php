<?php
    header("Content-Type: application/json");
    header("Access-Control-Allow-Origin: *");
    
    require_once '../db.php';

    $statement = "SELECT * FROM sampah";

    try {
        $result = $conn->query($statement);
        $dataSampah = [];

        while ($row = $result->fetch_assoc()) {
            $dataSampah[] = $row;
        }

        echo json_encode([
            'success' => true,
            'data' => $dataSampah
        ]);
    } catch (mysqli_sql_exception $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Gagal mengambil data sampah: ' . $e->getMessage()
        ]);
    }

    $conn->close();