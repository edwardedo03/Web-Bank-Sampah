<?php

header('Content-Type: application/json');
require '../db.php';

$result = ['success' => true, 'data' => []];

$q = $conn->query("SELECT id_sampah, jenis_sampah, harga_sampah_per_kg, jumlah_sampah_gudang FROM sampah ORDER BY jenis_sampah ASC");
while ($row = $q->fetch_assoc()) {
    $result['data'][] = [
        'id' => $row['id_sampah'],
        'nama' => $row['jenis_sampah'],
        'harga' => (float) $row['harga_sampah_per_kg'],
        'stok' => (int) $row['jumlah_sampah_gudang'],
    ];
}

echo json_encode($result);
$conn->close();
