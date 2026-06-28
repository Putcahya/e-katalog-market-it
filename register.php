<?php
session_start();

$angka1 = rand(1,20);
$angka2 = rand(1,20);

$_SESSION['hasil_captcha'] = $angka1 + $angka2;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            background: linear-gradient(135deg, #007bff 60%, #00c6ff 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
        }

        .register-form {
            background: #fff;
            color: #000;
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0, 123, 255, .18), 0 2px 10px rgba(0, 0, 0, .15);
            width: 100%;
            max-width: 500px;
            position: relative;
            z-index: 2;
        }

        .logo-market {
            text-align: center;
            margin-bottom: 20px;
        }

        .logo-market img {
            height: 70px;
            border-radius: 10px;
            background: #fff;
            padding: 5px 10px;
            box-shadow: 0 2px 10px rgba(0, 123, 255, .2);
        }

        .register-form h2 {
            text-align: center;
            color: #007bff;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .btn-primary {
            background: linear-gradient(90deg, #007bff, #00c6ff);
            border: none;
            font-weight: bold;
        }

        .btn-primary:hover {
            background: linear-gradient(90deg, #0056b3, #009ec3);
        }

        /* Background Teknologi */
        .tech-bg {
            position: fixed;
            width: 100%;
            height: 100%;
            left: 0;
            top: 0;
            z-index: 0;
            overflow: hidden;
        }

        .tech-circuit {
            position: absolute;
            opacity: .18;
            animation: techMove 18s linear infinite;
        }

        .c1 {
            left: 8%;
            top: 10%;
            width: 120px;
        }

        .c2 {
            left: 65%;
            top: 15%;
            width: 90px;
        }

        .c3 {
            left: 30%;
            top: 65%;
            width: 130px;
        }

        .c4 {
            left: 80%;
            top: 70%;
            width: 100px;
        }

        .c5 {
            left: 45%;
            top: 35%;
            width: 80px;
        }

        @keyframes techMove {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.08) rotate(3deg);
            }

            100% {
                transform: scale(1);
            }
        }
    </style>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Registrasi Berhasil',
            html: 'Akun Anda berhasil didaftarkan.<br><br><b>Silakan tunggu verifikasi dari Admin sebelum dapat login.</b>',
            confirmButtonText: 'OK'
        }).then((result) => {
            if(result.isConfirmed){
                window.location.href = "../login.php";
            }
        });
        </script>

</head>

<body>

    <div class="tech-bg">

        <svg class="tech-circuit c1" viewBox="0 0 120 120" fill="none">
            <rect x="10" y="10" width="100" height="100" rx="18" stroke="#00c6ff" stroke-width="3" />
            <circle cx="60" cy="60" r="30" stroke="#007bff" stroke-width="2" />
        </svg>

        <svg class="tech-circuit c2" viewBox="0 0 90 90" fill="none">
            <rect x="10" y="10" width="70" height="70" rx="14" stroke="#007bff" stroke-width="2" />
            <circle cx="45" cy="45" r="18" stroke="#00c6ff" stroke-width="2" />
        </svg>

        <svg class="tech-circuit c3" viewBox="0 0 120 120" fill="none">
            <rect x="10" y="10" width="100" height="100" rx="18" stroke="#00c6ff" stroke-width="3" />
            <circle cx="60" cy="60" r="30" stroke="#007bff" stroke-width="2" />
        </svg>

        <svg class="tech-circuit c4" viewBox="0 0 90 90" fill="none">
            <rect x="10" y="10" width="70" height="70" rx="14" stroke="#007bff" stroke-width="2" />
            <circle cx="45" cy="45" r="18" stroke="#00c6ff" stroke-width="2" />
        </svg>

        <svg class="tech-circuit c5" viewBox="0 0 80 80" fill="none">
            <rect x="10" y="10" width="60" height="60" rx="12" stroke="#00c6ff" stroke-width="2" />
            <circle cx="40" cy="40" r="18" stroke="#007bff" stroke-width="2" />
        </svg>

    </div>

    <div class="register-form">

        <div class="logo-market">
            <img src="assets/img/logomarket.png">
        </div>

        <h2>Register Market-IT</h2>

        <form action="admin/process/tambah_operator.php" method="POST" enctype="multipart/form-data">

            <div class="mb-3">
                <label>Nama Lengkap</label>
                <input type="text" name="nama" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Foto</label>
                <input type="file" name="foto" class="form-control" accept="image/*">
            </div>

            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Username</label>
                <input type="text" name="username" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Konfirmasi Password</label>
                <input type="password" name="konfirmasi_password" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Alamat</label>
                <textarea name="alamat" class="form-control" rows="2"></textarea>
            </div>

            <div class="mb-3">
                <label>Telepon</label>
                <input type="text" name="telepon" class="form-control">
            </div>

            <div class="mb-3">
                <label>Verifikasi</label>

                <div class="alert alert-primary text-center fw-bold fs-5">
                    <?= $angka1 ?> + <?= $angka2 ?> = ?
                </div>

                <input type="number" name="captcha" class="form-control" placeholder="Jawaban" required>
            </div>

            <input type="hidden" name="role" value="operator">
            <input type="hidden" name="status" value="1">

            <button type="submit" class="btn btn-primary w-100">
                Daftar
            </button>

            <div class="text-center mt-3">
                Sudah punya akun?
                <a href="login.php" class="text-decoration-none fw-bold">Login</a>
            </div>

        </form>

    </div>
        <script>
        Swal.fire({
            icon: 'error',
            title: 'Registrasi Gagal',
            text: 'Terjadi kesalahan saat menyimpan data.',
            confirmButtonText: 'Kembali'
        }).then(() => {
            window.history.back();
        });
        </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>