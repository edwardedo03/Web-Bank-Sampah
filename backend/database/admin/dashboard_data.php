<?php
header('Content-Type: application/json');
require '../db.php';

session_start();

$periode = $_GET['periode'] ?? 'Bulan Ini';
$jenis = $_GET['jenis'] ?? 'Semua Jenis';

$periodeCondition = '';
if ($periode === 'Minggu Ini') {
    $periodeCondition = "AND t.tanggal_transaksi >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
} elseif ($periode === 'Tahun Ini') {
    $periodeCondition = "AND YEAR(t.tanggal_transaksi) = YEAR(NOW())";
} else { // default: Bulan Ini
    $periodeCondition = "AND YEAR(t.tanggal_transaksi) = YEAR(NOW()) AND MONTH(t.tanggal_transaksi) = MONTH(NOW())";
}

$jenisCondition = '';
$jenisParam = null;
if ($jenis !== 'Semua Jenis') {
    $jenisCondition = "AND dt.jenis_sampah = ?";
    $jenisParam = $jenis;
}

$result = [
    'success' => true,
    'total_nasabah' => 0,
    'total_petugas' => 0,
    'total_saldo' => 0,
    'transaksi_terbaru' => [],
    'sampah_terkumpul' => [],
    'tren_mingguan' => [],
];

$q = $conn->query("SELECT COUNT(*) AS total FROM nasabah");
$result['total_nasabah'] = (int) $q->fetch_assoc()['total'];

$q = $conn->query("SELECT COUNT(*) AS total FROM petugas_lapangan");
$result['total_petugas'] = (int) $q->fetch_assoc()['total'];

$q = $conn->query("SELECT SUM(jumlah_tabungan) AS total FROM nasabah");
$row = $q->fetch_assoc();
$result['total_saldo'] = (float) ($row['total'] ?? 0);

$sql = "
    SELECT
        dt.id_detail,
        n.nama_nasabah,
        dt.jenis_sampah,
        dt.berat_sampah,
        dt.subtotal_nominal,
        dt.status,
        t.tanggal_transaksi
    FROM detail_transaksi dt
    JOIN transaksi t ON t.id_transaksi = dt.id_transaksi
    JOIN nasabah n ON n.id_nasabah = t.id_nasabah
    WHERE 1=1 $periodeCondition $jenisCondition
    ORDER BY t.tanggal_transaksi DESC
    LIMIT 5
";
$stmt = $conn->prepare($sql);
if ($jenisParam !== null) {
    $stmt->bind_param('s', $jenisParam);
}
$stmt->execute();
$res = $stmt->get_result();

while ($row = $res->fetch_assoc()) {
    $namaParts = explode(' ', trim($row['nama_nasabah']));
    $inisial = strtoupper(substr($namaParts[0], 0, 1) . substr(end($namaParts), 0, 1));

    $result['transaksi_terbaru'][] = [
        'id' => $row['id_detail'],
        'nama' => $row['nama_nasabah'],
        'inisial' => $inisial,
        'jenis_sampah' => $row['jenis_sampah'],
        'berat' => $row['berat_sampah'] . ' kg',
        'total' => 'Rp ' . number_format($row['subtotal_nominal'], 0, ',', '.'),
        'status' => $row['status'], // status ASLI dari database
    ];
}

$sqlSampah = "
    SELECT jenis_sampah, SUM(berat_sampah_aktual) AS total_berat
    FROM detail_transaksi
    GROUP BY jenis_sampah
    ORDER BY jenis_sampah ASC
";
$q = $conn->query($sqlSampah);
while ($row = $q->fetch_assoc()) {
    $result['sampah_terkumpul'][] = [
        'jenis' => $row['jenis_sampah'],
        'berat' => (float) $row['total_berat'],
    ];
}

$sqlSetoran = "
    SELECT DATE(tanggal_transaksi) AS tgl, SUM(total_nominal) AS total
    FROM transaksi
    WHERE tanggal_transaksi >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)
    GROUP BY DATE(tanggal_transaksi)
";
$setoranPerHari = [];
$q = $conn->query($sqlSetoran);
while ($row = $q->fetch_assoc()) {
    $setoranPerHari[$row['tgl']] = (float) $row['total'];
}

$sqlPenarikan = "
    SELECT DATE(tanggal_penarikan) AS tgl, SUM(nominal_penarikan) AS total
    FROM penarikan_saldo
    WHERE tanggal_penarikan >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)
    GROUP BY DATE(tanggal_penarikan)
";
$penarikanPerHari = [];
$q = $conn->query($sqlPenarikan);
while ($row = $q->fetch_assoc()) {
    $penarikanPerHari[$row['tgl']] = (float) $row['total'];
}

for ($i = 6; $i >= 0; $i--) {
    $tanggal = date('Y-m-d', strtotime("-$i day"));
    $result['tren_mingguan'][] = [
        'tanggal' => date('d/m', strtotime($tanggal)),
        'setoran' => $setoranPerHari[$tanggal] ?? 0,
        'penarikan' => $penarikanPerHari[$tanggal] ?? 0,
    ];
}

echo json_encode($result);
$stmt->close();
$conn->close();
