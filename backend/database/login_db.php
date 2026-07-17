<?php
    header("Content-Type: application/json");
    header("Access-Control-Allow-Origin: *");
    
    require_once 'db.php';

    $decode = json_decode(file_get_contents('php://input'), true);

    $username = $decode['username'] ?? '';
    $password = $decode['password'] ?? '';

    $statement = $conn->prepare("SELECT * FROM akun WHERE username = ? ");
    $statement->bind_param("s", $username);

    try {
        $statement->execute();
        $result = $statement->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            
            if (password_verify($password, $user['password'])) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Login berhasil',
                    'user' => [
                        'id_akun' => $user['id_akun'],
                        'username' => $user['username'],
                        'email' => $user['email'],
                        'role' => $user['role']
                    ]
                ]);
            } else {
                echo json_encode([
                'success' => false,
                'message' => 'Password Salah'
            ]);
            }

        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Username Belum Terdaftar'
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