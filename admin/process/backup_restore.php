<?php
session_start();
require_once __DIR__ . '/../../koneksi.php';

if (!isset($_SESSION['nama'])) {
    http_response_code(403);
    exit('Akses ditolak.');
}

$action = $_GET['action'] ?? '';

// ─────────────────────────────────────────────
// BACKUP DATABASE
// ─────────────────────────────────────────────
if ($action === 'backup') {
    $db_name  = 'market_it'; // ← sesuaikan
    $filename = 'backup_' . $db_name . '_' . date('Ymd_His') . '.sql';
    $output   = generateSqlDump($conn, $db_name);

    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Content-Length: ' . strlen($output));
    header('Pragma: no-cache');
    echo $output;
    exit;
}

// ─────────────────────────────────────────────
// RESTORE DATABASE
// ─────────────────────────────────────────────
if ($action === 'restore' && $_SERVER['REQUEST_METHOD'] === 'POST') {

    $response = ['success' => false, 'message' => ''];

    if (empty($_FILES['sql_file']['tmp_name'])) {
        $response['message'] = 'File SQL tidak ditemukan.';
        echo json_encode($response);
        exit;
    }

    $file    = $_FILES['sql_file'];
    $ext     = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $maxSize = 50 * 1024 * 1024;

    if ($ext !== 'sql') {
        $response['message'] = 'Hanya file .sql yang diperbolehkan.';
        echo json_encode($response);
        exit;
    }

    if ($file['size'] > $maxSize) {
        $response['message'] = 'Ukuran file melebihi batas 50 MB.';
        echo json_encode($response);
        exit;
    }

    $sqlContent = file_get_contents($file['tmp_name']);

    if (empty($sqlContent)) {
        $response['message'] = 'File SQL kosong atau tidak dapat dibaca.';
        echo json_encode($response);
        exit;
    }

    mysqli_multi_query($conn, $sqlContent);

    do {
        if ($result = mysqli_store_result($conn)) {
            mysqli_free_result($result);
        }
    } while (mysqli_more_results($conn) && mysqli_next_result($conn));

    if (mysqli_errno($conn)) {
        $response['message'] = 'Restore gagal: ' . mysqli_error($conn);
    } else {
        $response['success'] = true;
        $response['message'] = 'Database berhasil di-restore!';
    }

    echo json_encode($response);
    exit;
}

http_response_code(400);
echo json_encode(['success' => false, 'message' => 'Aksi tidak valid.']);
exit;

// ─────────────────────────────────────────────
// FUNGSI: Generate SQL dump manual
// ─────────────────────────────────────────────
function generateSqlDump($conn, $db_name)
{
    $sql  = "-- Backup Database: $db_name\n";
    $sql .= "-- Tanggal: " . date('Y-m-d H:i:s') . "\n\n";
    $sql .= "SET FOREIGN_KEY_CHECKS=0;\n\n";

    $tables = mysqli_query($conn, "SHOW TABLES");

    while ($tableRow = mysqli_fetch_row($tables)) {
        $table = $tableRow[0];

        $createRes = mysqli_fetch_row(mysqli_query($conn, "SHOW CREATE TABLE `$table`"));
        $sql .= "DROP TABLE IF EXISTS `$table`;\n";
        $sql .= $createRes[1] . ";\n\n";

        $rows     = mysqli_query($conn, "SELECT * FROM `$table`");
        $colCount = mysqli_num_fields($rows);

        while ($row = mysqli_fetch_row($rows)) {
            $values = [];
            for ($i = 0; $i < $colCount; $i++) {
                $values[] = $row[$i] === null
                    ? 'NULL'
                    : "'" . mysqli_real_escape_string($conn, $row[$i]) . "'";
            }
            $sql .= "INSERT INTO `$table` VALUES (" . implode(', ', $values) . ");\n";
        }
        $sql .= "\n";
    }

    $sql .= "SET FOREIGN_KEY_CHECKS=1;\n";
    return $sql;
}