<?php
session_start();
require_once __DIR__ . '/../../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $nama      = trim($_POST['nama']);
    $email     = trim($_POST['email']);
    $username  = trim($_POST['username']);
    $password  = trim($_POST['password']);
    $role      = trim($_POST['role']);
    $alamat    = trim($_POST['alamat']);
    $telepon   = trim($_POST['telepon']);
    $status    = intval($_POST['status']);

    // Username otomatis jika kosong
    if (empty($username)) {
        $username = strtolower(str_replace(' ', '', $nama));
    }

    // Password otomatis jika kosong
    if (empty($password)) {
        $password = strtolower(str_replace(' ', '', $nama));
    }

    // Cek username sudah digunakan atau belum
    $cek = mysqli_prepare($conn, "SELECT id FROM user WHERE username=?");
    mysqli_stmt_bind_param($cek, "s", $username);
    mysqli_stmt_execute($cek);
    mysqli_stmt_store_result($cek);

    if (mysqli_stmt_num_rows($cek) > 0) {
        $_SESSION['success_message'] = "Username sudah digunakan!";
        header("Location: ../operator.php");
        exit();
    }

    // Upload Foto
    $foto_name = "";

    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {

        $folder = "../../assets/img/users/";

        $ext = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));

        $allow = ['jpg','jpeg','png','gif'];

        if (!in_array($ext, $allow)) {

            $_SESSION['success_message'] = "Format foto tidak didukung!";
            header("Location: ../operator.php");
            exit();

        }

        $foto_name = time() . "_" . uniqid() . "." . $ext;

        move_uploaded_file(
            $_FILES['foto']['tmp_name'],
            $folder . $foto_name
        );
    }

    // Enkripsi Password SHA1
    $hashed_password = sha1($password);

    $sql = "INSERT INTO user
            (nama,foto_profile,email,username,password,role,alamat,telepon,status)
            VALUES (?,?,?,?,?,?,?,?,?)";

    $stmt = mysqli_prepare($conn,$sql);

    mysqli_stmt_bind_param(
        $stmt,
        "ssssssssi",
        $nama,
        $foto_name,
        $email,
        $username,
        $hashed_password,
        $role,
        $alamat,
        $telepon,
        $status
    );

    if (mysqli_stmt_execute($stmt)) {

        $_SESSION['success_message']="Operator berhasil ditambahkan!";

    } else {

        $_SESSION['success_message']="Gagal menambahkan operator!";

    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);

    header("Location: ../operator.php");
    exit();
}
?>