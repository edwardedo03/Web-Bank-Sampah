<?php
    session_start();
    header("Content-Type: application/json");

    require_once '../db.php';

    $decode = json_decode(file_get_contents('php://input'), true);

    $id_nasabah = $_GET['id_akun'] ?? ($_SESSION['id_akun'] ?? '');
    $jumlah_tarik = $decode['jumlah_tarik'] ?? null;