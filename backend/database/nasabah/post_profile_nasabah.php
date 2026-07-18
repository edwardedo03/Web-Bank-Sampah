<?php
    session_start();

    header("Content-Type: application/json");

    require_once '../db.php';

    $decode = json_decode(file_get_contents('php://input'), true);

    $id_nasabah = $decode['id_akun'] ?? ($_SESSION['id_akun'] ?? '');

    $nama_lengkap = $decode['namaLengkap'] ?? '';
    $email = $decode['email'] ?? '';
    $nomor_telepon = $decode['nomorTelepon'] ?? '';
    $alamat = $decode['alamat'] ?? '';
    $rt = $decode['rt'] ?? '';
    $rw = $decode['rw'] ?? '';
    $kelurahan = $decode['kelurahan'] ?? '';
    $kecamatan = $decode['kecamatan'] ?? '';

    $statement = $conn->prepare('UPDATE nasabah SET nama_nasabah = ?, email_nasabah = ?, no_telepon_nasabah = ?, alamat_nasabah = ?, rt = ?, rw = ?, kelurahan = ?, kecamatan = ? WHERE id_nasabah = ?');
    
    try {
        $statement->bind_param('ssssssssi', $nama_lengkap, $email, $nomor_telepon, $alamat, $rt, $rw, $kelurahan, $kecamatan, $id_nasabah);
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