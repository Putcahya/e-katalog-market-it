<?php
session_start();
require_once __DIR__ . '/../../koneksi.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $role = trim($_POST['role'] ?? 'operator');
    $alamat = trim($_POST['alamat'] ?? '');
    $telepon = trim($_POST['telepon'] ?? '');
    $status = intval($_POST['status'] ?? 1);

    if ($username === '') {
        $username = strtolower(preg_replace('/[^a-z0-9]+/', '', $nama));
    }

    if ($password === '') {
        $password = strtolower(preg_replace('/[^a-z0-9]+/', '', $nama));
    }

    $foto_name = '';
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $target_dir = '../../assets/img/users/';
        $foto_name = basename($_FILES['foto']['name']);
        $target_file = $target_dir . $foto_name;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];

        if (!in_array($imageFileType, $allowed_types)) {
            $_SESSION['success_message'] = 'Tipe file foto tidak valid!';
            header('Location: ../operator.php');
            exit();
        }

        if (!move_uploaded_file($_FILES['foto']['tmp_name'], $target_file)) {
            $_SESSION['success_message'] = 'Gagal mengupload foto!';
            header('Location: ../operator.php');
            exit();
        }
    }

    $hashed_password = sha1($password);

    $query = "INSERT INTO user (nama, foto_profile, email, username, password, role, alamat, telepon, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);

    if ($stmt === false) {
        $_SESSION['success_message'] = 'Gagal menyimpan data operator!';
        header('Location: ../operator.php');
        exit();
    }

    mysqli_stmt_bind_param($stmt, 'ssssssssi', $nama, $foto_name, $email, $username, $hashed_password, $role, $alamat, $telepon, $status);

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['success_message'] = 'Operator berhasil ditambahkan!';
        header('Location: ../operator.php');
        exit();
    } else {
        $_SESSION['success_message'] = 'Gagal menambahkan operator!';
        header('Location: ../operator.php');
        exit();
    }
}
?>
