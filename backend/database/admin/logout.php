<?php
/*
  backend/database/admin/logout.php
  Menghancurkan session yang sedang aktif.
*/

header('Content-Type: application/json');
session_start();
session_unset();
session_destroy();
echo json_encode(['success' => true, 'message' => 'Berhasil logout']);
