<?php
session_start();
include 'layouts/header_admin.php';
?>
<?php
require_once __DIR__ . '/../koneksi.php'; 

// Query untuk mengambil data dari tabel user
$query = "SELECT * FROM user WHERE role='operator'";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query gagal: " . mysqli_error($conn));
}

// Ambil semua data sebagai array
$operator = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>
<style>
    .modal {
        z-index: 1050 !important;
    }

    .modal-backdrop {
        z-index: 1040 !important;
    }
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center my-3">
                    <h4>Data Operator</h4>
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#tambahOperator"><i class="bi bi-plus"></i>Tambah
                        Operator</button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped" id="table-1">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Foto</th>
                                    <th>Email</th>
                                    <th>username</th>
                                    <th>Alamat</th>
                                    <th>Telepon</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                foreach ($operator as $row) {
                                    echo "<tr>";
                                    echo "<td>" . $no++ . "</td>";
                                    echo "<td>" . htmlspecialchars($row['nama']) . "</td>";
                                    echo "<td>";
                                    // Jika gambar kosong, pakai default.png
                                    $gambar = !empty($row['foto_profile']) ? htmlspecialchars($row['foto_profile']) : 'default.png';
                                    echo "<img src='../assets/img/users/$gambar' alt='" . htmlspecialchars($row['nama']) . "' class='img-fluid' style='max-width: 120px; height: auto;'>";
                                    echo "</td>";
                                    echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['username']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['alamat']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['telepon']) . "</td>";
                                    if ($row['status'] < 1) {
                                        echo "<td><div class='badge badge-danger badge-shadow'>Off</div></td>";
                                    } else {
                                        echo "<td><div class='badge badge-success badge-shadow'>On</div></td>";
                                    }
                                    echo "<td>
                                            <div class='d-flex justify-content-center'>
                                                <button type='button' class='btn btn-warning btn-sm mr-1' data-bs-toggle='modal' data-bs-target='#editOperatorModal" . $row['id'] . "'><i class='bi bi-pencil'></i></button>
                                                <button type='button' class='btn btn-danger btn-sm' onclick='confirmDelete(" . $row['id'] . ")'><i class='bi bi-trash'></i></button>
                                            </div>
                                        </td>";
                                    echo "</tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDelete(id) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data barang akan dihapus !",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Redirect ke file hapus_barang.php dengan parameter id
                window.location.href = 'process/hapus_operator.php?id=' + id;
            }
        });
    }
</script>

</div>
</section>
</div>


<!-- Modal Tambah Operator -->
<div class="modal fade" id="tambahOperator" tabindex="-1" aria-labelledby="tambahOperatorLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="process/tambah_operator.php" method="POST" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahOperatorLabel">Tambah Operator</h5>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="namaOperator" class="form-label">Nama</label>
                        <input type="text" class="form-control" id="namaOperator" name="nama" required>
                    </div>
                    <div class="mb-3">
                        <label for="fotoOperator" class="form-label">Foto</label>
                        <input type="file" class="form-control" id="fotoOperator" name="foto" accept="image/*">
                    </div>
                    <div class="mb-3">
                        <label for="emailOperator" class="form-label">Email</label>
                        <input type="email" class="form-control" id="emailOperator" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="usernameOperator" class="form-label">Username</label>
                        <input type="text" class="form-control" id="usernameOperator" name="username" placeholder="Akan otomatis diisi berdasarkan nama">
                    </div>
                    <div class="mb-3">
                        <label for="passwordOperator" class="form-label">Password</label>
                        <input type="password" class="form-control" id="passwordOperator" name="password" placeholder="Akan otomatis diisi berdasarkan nama dan role">
                    </div>
                    <div class="mb-3">
                        <label for="roleOperator" class="form-label">Role</label>
                        <select class="form-control" id="roleOperator" name="role" required>
                            <option value="operator">Operator</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="alamatOperator" class="form-label">Alamat</label>
                        <textarea class="form-control" id="alamatOperator" name="alamat" rows="2"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="teleponOperator" class="form-label">Telepon</label>
                        <input type="text" class="form-control" id="teleponOperator" name="telepon">
                    </div>
                    <div class="mb-3">
                        <label for="statusOperator" class="form-label">Status</label>
                        <select class="form-control" id="statusOperator" name="status" required>
                            <option value="0">Off</option>
                            <option value="1" selected>On</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Tambahkan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
foreach ($operator as $row) {
?>
<!-- Modal Edit Operator -->
<div class="modal fade" id="editOperatorModal<?php echo $row['id']; ?>" tabindex="-1"
    aria-labelledby="editOperatorModalLabel<?php echo $row['id']; ?>" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="process/edit_operator.php" method="POST" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="editOperatorModalLabel<?php echo $row['id']; ?>">Edit Operator</h5>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                    <div class="mb-3">
                        <label for="namaOperator<?php echo $row['id']; ?>" class="form-label">Nama</label>
                        <input type="text" class="form-control" id="namaOperator<?php echo $row['id']; ?>"
                            name="nama" value="<?php echo htmlspecialchars($row['nama']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="fotoOperator<?php echo $row['id']; ?>" class="form-label">Foto</label>
                        <input type="file" class="form-control" id="fotoOperator<?php echo $row['id']; ?>"
                            name="foto" accept="image/*">
                        <small class="text-danger">*Biarkan kosong jika tidak ingin mengganti foto.</small>
                    </div>
                    <div class="mb-3">
                        <label for="emailOperator<?php echo $row['id']; ?>" class="form-label">Email</label>
                        <input type="email" class="form-control" id="emailOperator<?php echo $row['id']; ?>"
                            name="email" value="<?php echo htmlspecialchars($row['email']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="usernameOperator<?php echo $row['id']; ?>" class="form-label">Username</label>
                        <input type="text" class="form-control" id="usernameOperator<?php echo $row['id']; ?>"
                            name="username" value="<?php echo htmlspecialchars($row['username']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="passwordOperator<?php echo $row['id']; ?>" class="form-label">Password</label>
                        <input type="password" class="form-control" id="passwordOperator<?php echo $row['id']; ?>"
                            name="password" placeholder="Kosongkan jika tidak ingin mengubah password">
                    </div>
                    <div class="mb-3">
                        <label for="roleOperator<?php echo $row['id']; ?>" class="form-label">Role</label>
                        <select class="form-control" id="roleOperator<?php echo $row['id']; ?>" name="role" required>
                            <option value="operator" <?php echo ($row['role'] ?? 'operator') == 'operator' ? 'selected' : ''; ?>>Operator</option>
                            <option value="admin" <?php echo ($row['role'] ?? 'operator') == 'admin' ? 'selected' : ''; ?>>Admin</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="alamatOperator<?php echo $row['id']; ?>" class="form-label">Alamat</label>
                        <textarea class="form-control" id="alamatOperator<?php echo $row['id']; ?>" name="alamat"
                            rows="2"><?php echo htmlspecialchars($row['alamat']); ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="teleponOperator<?php echo $row['id']; ?>" class="form-label">Telepon</label>
                        <input type="text" class="form-control" id="teleponOperator<?php echo $row['id']; ?>"
                            name="telepon" value="<?php echo htmlspecialchars($row['telepon']); ?>">
                    </div>
                    <div class="mb-3">
                        <label for="statusOperator<?php echo $row['id']; ?>" class="form-label">Status</label>
                        <select class="form-control" id="statusOperator<?php echo $row['id']; ?>" name="status" required>
                            <option value="0" <?php echo ($row['status'] ?? 1) == 0 ? 'selected' : ''; ?>>Off</option>
                            <option value="1" <?php echo ($row['status'] ?? 1) == 1 ? 'selected' : ''; ?>>On</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
}
?>

<!-- alert berhasil tambah -->
<?php if (isset($_SESSION['success_message'])): ?>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: '<?php echo addslashes($_SESSION['success_message']); ?>',
        confirmButtonText: 'OK'
      });
    });
  </script>
  <?php unset($_SESSION['success_message']); ?>
<?php endif; ?>
<!-- General JS Scripts -->
<script src="../assets/js/app.min.js"></script>
<!-- JS Libraies -->
<script src="../assets/bundles/prism/prism.js"></script>
<!-- Page Specific JS File -->
<!-- Template JS File -->
<script src="../assets/js/scripts.js"></script>
<!-- Custom JS File -->
<script src="../assets/js/custom.js"></script>
<!-- JS Libraies -->
<script src="../assets/bundles/datatables/datatables.min.js"></script>
<script src="../assets/bundles/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js"></script>
<script src="../assets/bundles/jquery-ui/jquery-ui.min.js"></script>

<!-- filepath: c:\laragon\www\market-it\admin\barang.php -->
<script src="https://unpkg.com/sweetalert2@11"></script>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
    integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous">
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.min.js"
    integrity="sha384-RuyvpeZCxMJCqVUGFI0Do1mQrods/hhxYlcVfGPOfQtPJh0JCw12tUAZ/Mv10S7D" crossorigin="anonymous">
</script>
    <!-- SweetAlert2 JS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.19.1/dist/sweetalert2.min.css" integrity="sha256-J8SXTq+SCSrJ+GSCNWSoO3ef8idzOhhNAJRulSUr6mg=" crossorigin="anonymous">



<!-- Page Specific JS File -->
<script src="../assets/js/page/datatables.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const namaInput = document.getElementById('namaOperator');
        const roleInput = document.getElementById('roleOperator');
        const usernameInput = document.getElementById('usernameOperator');
        const passwordInput = document.getElementById('passwordOperator');

        const slugify = (value) => value.toLowerCase().replace(/[^a-z0-9]+/g, '');

        const autoFillDefaults = () => {
            const nama = (namaInput?.value || '').trim();
            const role = (roleInput?.value || 'operator').trim();

            if (!nama) return;

            const base = slugify(nama);
            if (usernameInput && !usernameInput.dataset.edited) {
                usernameInput.value = base;
            }
            if (passwordInput && !passwordInput.dataset.edited) {
                passwordInput.value = base + role;
            }
        };

        [namaInput, roleInput].forEach((field) => {
            if (field) {
                field.addEventListener('input', autoFillDefaults);
                field.addEventListener('change', autoFillDefaults);
            }
        });

        if (usernameInput) {
            usernameInput.addEventListener('input', function () {
                usernameInput.dataset.edited = '1';
            });
        }

        if (passwordInput) {
            passwordInput.addEventListener('input', function () {
                passwordInput.dataset.edited = '1';
            });
        }

        autoFillDefaults();
    });
</script>

</body>

</html>