<?php
    session_start();

    header("Content-Type: application/json");

    require_once '../db.php';

    $decode = json_decode(file_get_contents('php://input'), true);

    $id_petugas = $decode['id_akun'] ?? ($_SESSION['id_akun'] ?? '');

    $nama_lengkap = $decode['namaLengkap'] ?? '';
    $email = $decode['email'] ?? '';
    $nomor_telepon = $decode['nomorTelepon'] ?? '';
    $wilayah_tugas = $decode['wilayahTugas'] ?? '';

    $statement = $conn->prepare('UPDATE petugas_lapangan SET nama_petugas = ?, email_petugas = ?, no_telepon_petugas = ?, wilayah_tugas = ? WHERE id_petugas = ?');
    
    try {
        $statement->bind_param('ssssi', $nama_lengkap, $email, $nomor_telepon, $wilayah_tugas, $id_petugas);
        $statement->execute();

        echo json_encode([
            'success' => true,
            'message' => 'Profil berhasil diubah'
        ]);
    } catch (mysqli_sql_exception $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Terjadi kesalahan: ' . $e->getMessage()
        ]);
    }

    $statement->close();
    $conn->close();