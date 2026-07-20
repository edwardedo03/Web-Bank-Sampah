<?php
    session_start();

    header("Content-Type: application/json");

    require_once '../db.php';

    $keyword = $_GET['keyword'] ?? '';

    $statement = $conn->prepare('SELECT * FROM nasabah WHERE nama_nasabah LIKE ? ORDER BY nama_nasabah ASC');

    $searchRule = '%' . $keyword . '%';

    $statement->bind_param('s', $searchRule);
    $statement->execute();
    
    $result = $statement->get_result();
    $nasabah = [];

    while ($row = $result->fetch_assoc()) {    
        $nasabah[] = $row;
    } 
        
    echo json_encode([
        'success' => true,
        'nasabah' => $nasabah
    ]);

    $statement->close();
    $conn->close();