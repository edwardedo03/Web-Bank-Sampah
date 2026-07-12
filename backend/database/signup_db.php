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