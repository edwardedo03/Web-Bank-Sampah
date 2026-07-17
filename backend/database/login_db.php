<?php
    session_start();

    header("Content-Type: application/json");

    require_once 'db.php';

    $decode = json_decode(file_get_contents('php://input'), true);

    $username = $decode['username'] ?? '';
    $password = $decode['password'] ?? '';

    $actors = [
        [
            'role' => 'nasabah',
            'table' => 'nasabah',
            'id_column' => 'id_nasabah',
            'username_column' => 'username_nasabah',
            'password_column' => 'password_nasabah',
            'name_column' => 'nama_nasabah',
            'email_column' => 'email_nasabah'
        ],
        [
            'role' => 'petugas',
            'table' => 'petugas_lapangan',
            'id_column' => 'id_petugas',
            'username_column' => 'username_petugas',
            'password_column' => 'password_petugas',
            'name_column' => 'nama_petugas',
            'email_column' => 'email_petugas'
        ],
        [
            'role' => 'admin',
            'table' => 'admin',
            'id_column' => 'id_admin',
            'username_column' => 'username_admin',
            'password_column' => 'password_admin',
            'name_column' => 'nama_admin',
            'email_column' => 'email_admin'
        ]
    ];

    try {
        $user_found = null;
        $actor_found = null;

        foreach ($actors as $actor) {
            $query = "SELECT * FROM {$actor['table']} WHERE {$actor['username_column']} = ?";
            $statement = $conn->prepare($query);
            $statement->bind_param("s", $username);
            $statement->execute();
            $result = $statement->get_result();

            if ($result->num_rows > 0) {
                $user_found = $result->fetch_assoc();
                $actor_found = $actor;
                $statement->close();
                break;
            }

            $statement->close();
        }

        if ($user_found) {
            $id_column = $actor_found['id_column'];
            $username_column = $actor_found['username_column'];
            $password_column = $actor_found['password_column'];
            $role = $user_found['role'] ?? $actor_found['role'];

            if (password_verify($password, $user_found[$password_column])) {
                $_SESSION['id_akun'] = $user_found[$id_column];
                $_SESSION['username'] = $user_found[$username_column];
                $_SESSION['role'] = $role;

                echo json_encode([
                    'success' => true,
                    'message' => 'Login berhasil',
                    'user' => [
                        'id' => $user_found[$id_column],
                        'username' => $user_found[$username_column],
                        'role' => $role,
                    ]
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Password salah'
                ]);
            }
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Username belum terdaftar'
            ]);
        }
    }
    catch (mysqli_sql_exception $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Terjadi kesalahan: ' . $e->getMessage()
        ]);
    }

    $conn->close();