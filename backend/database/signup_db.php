<?php
    header("Content-Type: application/json");
    header("Access-Control-Allow-Origin: *");

    require_once 'db.php';

    $decode = json_decode(file_get_contents('php://input'), true);

    $email = $decode['email'] ?? '';
    $username = $decode['username'] ?? '';
    $password = $decode['password'] ?? '';
    $role = $decode['role'] ?? '';

    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    $statement = $conn->prepare("INSERT INTO akun (email, username, password, role) VALUES (?, ?, ?, ?)");
    $statement->bind_param("ssss", $email, $username, $password_hash, $role);

    try {

        if ($role === 'nasabah') {
            $statement = $conn->prepare("INSERT INTO nasabah (email_nasabah, username_nasabah, password_nasabah, role) VALUES (?, ?, ?, ?)");
            $statement->bind_param("ssss", $email, $username, $password_hash, $role);
        } else if ($role === 'petugas') {
            $statement = $conn->prepare("INSERT INTO nasabah (email_petugas, username_petugas, password_petugas, role) VALUES (?, ?, ?, ?)");
            $statement->bind_param("ssss", $email, $username, $password_hash, $role);
        } else {
            $statement = $conn->prepare("INSERT INTO nasabah (email_admin, username_admin, password_admin, role) VALUES (?, ?, ?, ?)");
            $statement->bind_param("ssss", $email, $username, $password_hash, $role);
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