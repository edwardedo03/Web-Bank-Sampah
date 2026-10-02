<?php
    $host = "localhost";
    $user = "root";
    $pass = "";
    $db = "bank_sampah";

    $conn = new mysqli($host, $user, $pass, $db);

    if ($conn->connect_error) {
        header("Content-Type: application/json");
        echo json_encode([
            'success' => false,
            'message' => 'Gagal terhubung dengan database: ' . $conn->connect_error
        ]);
        exit();
    }

    $conn->query("SET time_zone = '+07:00';");