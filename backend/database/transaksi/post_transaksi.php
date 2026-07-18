<?php
    session_start();

    date_default_timezone_set('Asia/Jakarta');

    header("Content-Type: application/json");
    
    require_once '../db.php';

    $decode = json_decode(file_get_contents('php://input'), true);

    $id_nasabah = $decode['idNasabah'] ?? ($_SESSION['id_akun'] ?? '');
    $tanggal_transaksi = date('Y-m-d H:i:s');
    $tanggal_penyerahan = isset($decode['tanggalPenyerahan']) ? str_replace('T', ' ', $decode['tanggalPenyerahan']) : '';
    $metode_penyerahan = $decode['metodePenyerahan'] ?? '';
    $total_nominal = $decode['totalNominal'] ?? '';
    $total_berat = $decode['totalBerat'] ?? '';
    $detail_items = $decode['detailTransaksi'] ?? [];

    if (!$id_nasabah) {
        echo json_encode([
            'success' => false,
            'message' => 'Session login tidak ditemukan. Silakan login ulang.'
        ]);
        exit();
    }

    $conn->begin_transaction();

    try {
        $statement_transaksi = $conn->prepare('INSERT INTO transaksi (id_nasabah, tanggal_transaksi, tanggal_penyerahan, metode_penyerahan, total_nominal, total_berat) VALUES (?, ?, ?, ?, ?, ?)');
        
        $statement_transaksi->bind_param('isssdd', $id_nasabah, $tanggal_transaksi, $tanggal_penyerahan, $metode_penyerahan, $total_nominal, $total_berat);    
        $statement_transaksi->execute();

        $id_transaksi = $conn->insert_id;
        $statement_transaksi->close();

        $statement_update_tabungan = $conn->prepare('UPDATE nasabah SET jumlah_tabungan = jumlah_tabungan + ? WHERE id_nasabah = ?');
        $statement_update_tabungan->bind_param('di', $total_nominal, $id_nasabah);
        $statement_update_tabungan->execute();
        $statement_update_tabungan->close();
    
            $statement_detail = $conn->prepare('INSERT INTO detail_transaksi (id_transaksi, id_sampah, jenis_sampah, subtotal_nominal, berat_sampah, catatan) VALUES (?, ?, ?, ?, ?, ?)');

            $statement_cari_sampah = $conn->prepare("SELECT id_sampah FROM sampah WHERE jenis_sampah = ?");
    
            foreach ($detail_items as $item) {
                $id_sampah = null;
                $jenis_sampah = $item['jenisSampah'] ?? '';
                $berat_sampah = $item['beratSampah'] ?? 0;
                $subtotal_nominal = $item['subtotalNominal'] ?? 0;
                $catatan = $item['catatan'] ?? "";

                    $statement_cari_sampah->bind_param('s', $jenis_sampah);
                    $statement_cari_sampah->execute();
                    $res_cari_sampah = $statement_cari_sampah->get_result();
                    
                    if ($res_cari_sampah->num_rows > 0) {
                        $row_sampah = $res_cari_sampah->fetch_assoc();
                        $id_sampah = $row_sampah['id_sampah'];
                    } else {
                        throw new mysqli_sql_exception("Jenis sampah '$jenis_sampah' tidak ditemukan");
                    }
    
                $statement_detail->bind_param('iisdds', $id_transaksi, $id_sampah, $jenis_sampah, $subtotal_nominal, $berat_sampah, $catatan);
                $statement_detail->execute();
            }
    
            $statement_cari_sampah->close();
            $statement_detail->close();
    
        $conn->commit();
    
        echo json_encode([
            'success' => true,
            'message' => 'Transaksi setoran berhasil disimpan'
        ]);
    } catch (mysqli_sql_exception $e) {
        $conn->rollback();
        echo json_encode([
            'success' => false,
            'message' => 'Terjadi kesalahan: ' . $e->getMessage()
        ]);
    }

    $conn->close();



