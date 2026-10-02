<?php
    session_start();

    header("Content-Type: application/json");

    require_once '../db.php';

    $id_nasabah = $_GET['id_akun'] ?? ($_SESSION['id_akun'] ?? '');

    $statement = $conn->prepare('SELECT * FROM nasabah WHERE id_nasabah =?');
    $statement->bind_param('i', $id_nasabah);
    $statement->execute();
    $result = $statement->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo json_encode([
            'success' => true,
            'data_nasabah' => $row
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Nasabah tidak ditemukan'
        ]);
    }

    $statement->close();
    $conn->close();