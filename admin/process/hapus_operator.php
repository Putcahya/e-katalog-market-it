<?php
require_once __DIR__ . '/../../koneksi.php'; 

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $query = "DELETE FROM user WHERE id = $id";
    if (mysqli_query($conn, $query)) {
        // Redirect dengan pesan sukses
        $_SESSION['success_message'] = 'Operator berhasil dihapus permanen !';
        header("Location: ../operator.php?hapus=sukses");
        exit;
    } else {
        $_SESSION['success_message'] = 'Operator gagal dihapus permanen !';
        // Redirect dengan pesan gagal
        header("Location: ../operator.php?hapus=gagal");
        exit;
    }
} else {
    $_SESSION['success_message'] = 'Operator tidak ditemukan !';
    header("Location: ../operator.php");
    exit;
}

?>