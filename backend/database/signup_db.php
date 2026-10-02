<?php
    header("Content-Type: application/json");
    header("Access-Control-Allow-Origin: *");

    require_once 'db.php';

    $decode = json_decode(file_get_contents('php://input'), true);

    $email = $decode['email'] ?? '';
    $username = $decode['username'] ?? '';
    $password = $decode['password'] ?? '';
    $role = $decode['role'] ?? '';
    $tanggal_bergabung = date('Y-m-d');

    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    try {

        if ($role === 'nasabah') {
            $statement = $conn->prepare("INSERT INTO nasabah (email_nasabah, username_nasabah, password_nasabah, role, tanggal_bergabung) VALUES (?, ?, ?, ?, ?)");
            $statement->bind_param("sssss", $email, $username, $password_hash, $role, $tanggal_bergabung);
        } else if ($role === 'petugas') {
            $statement = $conn->prepare("INSERT INTO petugas_lapangan (email_petugas, username_petugas, password_petugas, role, tanggal_bergabung) VALUES (?, ?, ?, ?, ?)");
            $statement->bind_param("sssss", $email, $username, $password_hash, $role, $tanggal_bergabung);
        } else {
            $statement = $conn->prepare("INSERT INTO admin (email_admin, username_admin, password_admin, role, tanggal_bergabung) VALUES (?, ?, ?, ?, ?)");
            $statement->bind_param("sssss", $email, $username, $password_hash, $role, $tanggal_bergabung);
        }

        $statement->execute();

        echo json_encode([
            'success' => true,
            'message' => 'Akun berhasil dibuat'
        ]);

    } catch (mysqli_sql_exception $e) {
        if ($e->getCode() == 1062) {
            echo json_encode([
                'success' => false,
                'message' => 'Email atau username sudah digunakan'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Gagal membuat akun: ' . $e->getMessage()
            ]);
        }
    }

    $statement->close();
    $conn->close();