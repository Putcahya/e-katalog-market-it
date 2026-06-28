<?php
session_start();
require_once __DIR__ . '/../../koneksi.php';

$checkRole = mysqli_query($conn, "SHOW COLUMNS FROM user LIKE 'role'");
if (mysqli_num_rows($checkRole) === 0) {
    mysqli_query($conn, "ALTER TABLE user ADD COLUMN role VARCHAR(20) NOT NULL DEFAULT 'operator'");
}

$checkStatus = mysqli_query($conn, "SHOW COLUMNS FROM user LIKE 'status'");
if (mysqli_num_rows($checkStatus) === 0) {
    mysqli_query($conn, "ALTER TABLE user ADD COLUMN status INT(1) NOT NULL DEFAULT 1");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id'] ?? 0);
    $nama = trim($_POST['nama'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $role = trim($_POST['role'] ?? 'operator');
    $alamat = trim($_POST['alamat'] ?? '');
    $telepon = trim($_POST['telepon'] ?? '');
    $status = intval($_POST['status'] ?? 1);

    if ($id <= 0) {
        $_SESSION['success_message'] = 'ID operator tidak valid!';
        header('Location: ../operator.php');
        exit();
    }

    $foto_name = '';
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $target_dir = __DIR__ . '/../../assets/img/users/';
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $imageFileType = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];

        if (!in_array($imageFileType, $allowed_types)) {
            $_SESSION['success_message'] = 'Tipe file foto tidak valid!';
            header('Location: ../operator.php');
            exit();
        }

        $safe_name = time() . '_' . preg_replace('/[^A-Za-z0-9._-]/', '_', basename($_FILES['foto']['name']));
        $target_file = $target_dir . $safe_name;

        if (!move_uploaded_file($_FILES['foto']['tmp_name'], $target_file)) {
            $_SESSION['success_message'] = 'Gagal mengupload foto!';
            header('Location: ../operator.php');
            exit();
        }

        $foto_name = $safe_name;
    }

    $query = "UPDATE user SET nama = ?, email = ?, username = ?, role = ?, alamat = ?, telepon = ?, status = ?";
    $params = [$nama, $email, $username, $role, $alamat, $telepon, $status];

    if ($foto_name !== '') {
        $query .= ", foto_profile = ?";
        $params[] = $foto_name;
    }

    if ($password !== '') {
        $query .= ", password = ?";
        $params[] = sha1($password);
    }

    $query .= " WHERE id = ?";
    $params[] = $id;

    $stmt = mysqli_prepare($conn, $query);
    if ($stmt === false) {
        $_SESSION['success_message'] = 'Gagal memperbarui data operator!';
        header('Location: ../operator.php');
        exit();
    }

    $types = '';
    foreach ($params as $value) {
        $types .= 's';
    }

    $bindParams = [$stmt, $types];
    foreach ($params as $key => $value) {
        $bindParams[] = &$params[$key];
    }
    call_user_func_array('mysqli_stmt_bind_param', $bindParams);

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['success_message'] = 'Operator berhasil diperbarui!';
    } else {
        $_SESSION['success_message'] = 'Gagal memperbarui operator!';
    }

    header('Location: ../operator.php');
    exit();
}
