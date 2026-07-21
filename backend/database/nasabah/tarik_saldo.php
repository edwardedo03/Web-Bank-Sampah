<?php
    session_start();
    header("Content-Type: application/json");

    date_default_timezone_set('Asia/Jakarta');

    require_once '../db.php';

    $decode = json_decode(file_get_contents('php://input'), true);

    $id_nasabah = $decode['id_akun'] ?? ($_SESSION['id_akun'] ?? '');
    $nominal_penarikan = $decode['nominal_tarik'] ?? null;
    $tanggal_penarikan = date('Y-m-d H:i:s');
    $status_penarikan = 'Menunggu Validasi';

    $statement = $conn->prepare("INSERT INTO penarikan_saldo (id_nasabah, nominal_penarikan, tanggal_penarikan, status_penarikan) VALUES (?, ?, ?, ?)");
    
    try {
        $statement->bind_param("idss", $id_nasabah, $nominal_penarikan, $tanggal_penarikan, $status_penarikan);
        $statement->execute();

        echo json_encode([
            'success' => true,
            'message' => 'Pengajuan penarikan saldo berhasil'
        ]);
    } catch (mysqli_sql_exception $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Penarikan saldo gagal: ' . $e->getMessage()
        ]);
    }