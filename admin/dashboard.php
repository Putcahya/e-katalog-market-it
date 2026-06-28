<?php include 'layouts/header_admin.php'?>
<?php
require_once __DIR__ . '/../koneksi.php'; 

$slider_aktif  = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM slider WHERE status=1"))[0];
$produk_favorit= mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM barang WHERE star=1"))[0];
$total_barang  = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM barang"))[0];

if (isset($_GET['success']) && $_GET['success'] === 'login_success') {
    echo "
    <script>
    Swal.fire({
        icon: 'success',
        title: 'Login Berhasil !',
        text: 'Selamat datang, " . htmlspecialchars($_SESSION['nama']) . "',
    });
    </script>";
}
?>

<!-- ═══════════════════════════════════════════════
     TOMBOL BACKUP & RESTORE (taruh di atas cards)
     ═══════════════════════════════════════════════ -->
<div class="row mb-3">
    <div class="col-12 d-flex justify-content-end gap-2">
        <!-- Backup -->
        <a href="process/backup_restore.php?action=backup"
           class="btn btn-success btn-sm"
           id="btnBackup"
           title="Download seluruh database sebagai file .sql">
            <i class="fas fa-download mr-1"></i> Backup Database
        </a>
        <button type="button" class="btn btn-warning btn-sm" onclick="bukaModal()" title="Upload file .sql untuk restore database">
    <i class="fas fa-upload mr-1"></i> Restore Database
</button>
    </div>
</div>

<!-- ═══════════════════════════════════════════════
     CARDS STATISTIK (tidak berubah)
     ═══════════════════════════════════════════════ -->
<div class="row">
    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-xs-12">
        <div class="card">
            <div class="card-statistic-4">
                <div class="align-items-center justify-content-between">
                    <div class="row">
                        <div class="col-7 pr-0 pt-3">
                            <div class="card-content">
                                <h5 class="font-15">Slider Aktif</h5>
                                <h2 class="mb-3 font-18"><?= $slider_aktif ?></h2>
                                <p class="mb-0"><span class="col-green"><?= $slider_aktif > 0 ? 'Aktif' : 'Tidak Ada' ?></span></p>
                            </div>
                        </div>
                        <div class="col-5 pl-0 text-right">
                            <div class="banner-img">
                                <img src="../assets/img/banner/1.png" alt="" style="max-width:60px;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-xs-12">
        <div class="card">
            <div class="card-statistic-4">
                <div class="align-items-center justify-content-between">
                    <div class="row">
                        <div class="col-7 pr-0 pt-3">
                            <div class="card-content">
                                <h5 class="font-15">Produk Favorit</h5>
                                <h2 class="mb-3 font-18"><?= $produk_favorit ?></h2>
                                <p class="mb-0"><span class="col-orange"><?= $produk_favorit ?> Favorit</span></p>
                            </div>
                        </div>
                        <div class="col-5 pl-0 text-right">
                            <div class="banner-img">
                                <img src="../assets/img/banner/2.png" alt="" style="max-width:60px;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-statistic-4">
                <div class="align-items-center justify-content-between">
                    <div class="row">
                        <div class="col-7 pr-0 pt-3">
                            <div class="card-content">
                                <h5 class="font-15">Total Barang</h5>
                                <h2 class="mb-3 font-18"><?= $total_barang ?></h2>
                                <p class="mb-0"><span class="col-blue"><?= $total_barang ?> Barang</span></p>
                            </div>
                        </div>
                        <div class="col-5 pl-0 text-right">
                            <div class="banner-img">
                                <img src="../assets/img/banner/3.png" alt="" style="max-width:60px;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ═══════════════════════════════════════════════
     MODAL RESTORE DATABASE
     ═══════════════════════════════════════════════ -->
<div class="modal fade" id="modalRestore" tabindex="1" role="dialog" aria-labelledby="modalRestoreLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-header bg-warning">
                <h5 class="modal-title" id="modalRestoreLabel">
                    <i class="fas fa-upload mr-2"></i> Restore Database
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <!-- Peringatan -->
                <div class="alert alert-danger d-flex align-items-start" role="alert">
                    <i class="fas fa-exclamation-triangle mr-2 mt-1"></i>
                    <div>
                        <strong>Perhatian!</strong> Restore akan <u>menimpa seluruh data</u> yang ada saat ini.
                        Pastikan kamu sudah melakukan <strong>backup</strong> sebelum melanjutkan.
                    </div>
                </div>

                <div class="form-group">
                    <label for="sqlFile"><i class="fas fa-file-code mr-1"></i> Pilih File SQL</label>
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="sqlFile" accept=".sql">
                        <label class="custom-file-label" for="sqlFile">Pilih file .sql ...</label>
                    </div>
                    <small class="text-muted">Maksimal ukuran file: 50 MB</small>
                </div>

                <!-- Progress bar (tersembunyi) -->
                <div id="restoreProgress" class="d-none">
                    <div class="progress">
                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-warning"
                             style="width:100%">Sedang memproses...</div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times mr-1"></i> Batal
                </button> -->

                <a href="dashboard.php" class="btn btn-secondary">Batal</a>
                <button type="button" class="btn btn-danger" id="btnRestore">
                    <i class="fas fa-database mr-1"></i> Mulai Restore
                </button>
            </div>

        </div>
    </div>
</div>

<!-- ═══════════════════════════════════════════════
     SCRIPTS
     ═══════════════════════════════════════════════ -->
<script src="../assets/js/app.min.js"></script>
<script src="../assets/bundles/prism/prism.js"></script>
<script src="../assets/js/scripts.js"></script>
<script src="../assets/js/custom.js"></script>
<script src="../assets/bundles/datatables/datatables.min.js"></script>
<script src="../assets/bundles/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js"></script>
<script src="../assets/bundles/jquery-ui/jquery-ui.min.js"></script>
<script src="https://unpkg.com/sweetalert2@11"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.19.1/dist/sweetalert2.min.css">
<script src="../assets/js/page/datatables.js"></script>

<script>
// ── Tampilkan nama file yang dipilih di custom-file-input ──
$('#sqlFile').on('change', function () {
    var fileName = $(this).val().split('\\').pop();
    $(this).next('.custom-file-label').text(fileName || 'Pilih file .sql ...');
});

// ── Tombol Backup: loading state ──
$('#btnBackup').on('click', function () {
    const btn = $(this);
    btn.html('<i class="fas fa-spinner fa-spin mr-1"></i> Memproses...');
    // Kembalikan teks setelah 3 detik (download sudah mulai)
    setTimeout(() => {
        btn.html('<i class="fas fa-download mr-1"></i> Backup Database');
    }, 3000);
});

// ── Tombol Restore: konfirmasi → upload → hasil ──
$('#btnRestore').on('click', function () {
    const file = $('#sqlFile')[0].files[0];

    if (!file) {
        Swal.fire('Oops!', 'Pilih file .sql terlebih dahulu.', 'warning');
        return;
    }

    if (!file.name.endsWith('.sql')) {
        Swal.fire('Format Salah', 'Hanya file dengan ekstensi .sql yang diperbolehkan.', 'error');
        return;
    }

    // Konfirmasi sebelum restore
    Swal.fire({
        icon: 'warning',
        title: 'Yakin ingin Restore?',
        html: 'Semua data saat ini akan <strong>ditimpa</strong> oleh file:<br><code>' + file.name + '</code>',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="fas fa-database"></i> Ya, Restore!',
        cancelButtonText: 'Batal',
    }).then((result) => {
        if (!result.isConfirmed) return;

        // Tampilkan progress
        $('#restoreProgress').removeClass('d-none');
        $('#btnRestore').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Memproses...');

        const formData = new FormData();
        formData.append('sql_file', file);

        $.ajax({
            url: 'process/backup_restore.php?action=restore',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function (res) {
                let data;
                try { data = typeof res === 'string' ? JSON.parse(res) : res; }
                catch (e) { data = { success: false, message: 'Respons tidak valid dari server.' }; }

                $('#restoreProgress').addClass('d-none');
                $('#modalRestore').modal('hide');
                $('#btnRestore').prop('disabled', false).html('<i class="fas fa-database mr-1"></i> Mulai Restore');
                $('#sqlFile').val('').next('.custom-file-label').text('Pilih file .sql ...');

                if (data.success) {
                    Swal.fire({ icon: 'success', title: 'Berhasil!', text: data.message });
                } else {
                    Swal.fire({ icon: 'error', title: 'Gagal!', text: data.message });
                }
            },
            error: function () {
                $('#restoreProgress').addClass('d-none');
                $('#btnRestore').prop('disabled', false).html('<i class="fas fa-database mr-1"></i> Mulai Restore');
                Swal.fire('Error', 'Terjadi kesalahan saat menghubungi server.', 'error');
            }
        });
    });
});

function bukaModal() {
    var modal = document.getElementById('modalRestore');
    modal.style.display = 'block';
    modal.style.position = 'fixed';
    modal.style.top = '0';
    modal.style.left = '0';
    modal.style.width = '100%';
    modal.style.height = '100%';
    modal.style.zIndex = '99999';
    modal.style.backgroundColor = 'rgba(0, 0, 0, 0)';
    modal.classList.add('show');
}

function tutupModal() {
    var modal = document.getElementById('modalRestore');
    modal.style.display = 'none';
    modal.classList.remove('show');
}
</script>

<style>
/* Pastikan overlay & modal selalu di atas */
.modal-backdrop {
    z-index: 99998  !important;
}
#modalRestore {
    z-index: 99999  !important;
}
</style>
</body>
</html>