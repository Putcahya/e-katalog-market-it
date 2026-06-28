<?php

require_once __DIR__ . '/../dompdf/autoload.inc.php';

use Dompdf\Dompdf;
use Dompdf\Options;

include __DIR__ . '/../koneksi.php';

$logo = __DIR__ . '/../assets/img/logoMarket.png';
$type = pathinfo($logo, PATHINFO_EXTENSION);
$data = file_get_contents($logo);
$logoBase64 = 'data:image/' . $type . ';base64,' . base64_encode($data);

$query = mysqli_query($conn, "SELECT * FROM barang ORDER BY nama_barang ASC");

$html = '
<html>
<head>
<style>

body {
    font-family: DejaVu Sans;
    font-size: 12px;
    margin: 0;
    padding: 20px;
}

/* ===== WATERMARK ===== */
.watermark {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translateX(-50%) translateY(-50%);
    width: 500px;
    opacity: 0.5;   /* ← naikan dari 0.08 ke 0.5 */
    z-index: -1000;
}
/* ===== HEADER ===== */
.header {
    text-align: center;
    margin-bottom: 20px;
}

.header img.logo {
    width: 70px;
    margin-bottom: 5px;
}

.header h2 {
    margin: 4px 0;
    font-size: 16px;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.header p {
    margin: 2px 0;
    font-size: 11px;
    color: #555;
}

.divider {
    border: none;
    border-top: 2px solid #000;
    margin: 8px 0 16px 0;
}

/* ===== TABLE ===== */
table {
    width: 100%;
    border-collapse: collapse;
}

table th, table td {
    border: 1px solid #000;
    padding: 6px 8px;
}

th {
    background: #333;
    color: #fff;
    text-align: center;
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

td {
    font-size: 11px;
}

tr:nth-child(even) td {
    background-color: #f5f5f5;
}

.text-center { text-align: center; }
.text-right  { text-align: right; }

.badge-tersedia {
    background: #28a745;
    color: #fff;
    padding: 2px 8px;
    border-radius: 10px;
    font-size: 10px;
}

.badge-tidak {
    background: #dc3545;
    color: #fff;
    padding: 2px 8px;
    border-radius: 10px;
    font-size: 10px;
}

/* ===== FOOTER ===== */
.footer {
    position: fixed;
    bottom: 0px;
    left: 0px;
    right: 0px;
    font-size: 10px;
    color: #777;
    text-align: center;
    border-top: 1px solid #ccc;
    padding-top: 5px;
}

</style>
</head>
<body>

<!-- WATERMARK LOGO (tengah halaman, transparan) -->
<img class="watermark" src="' . $logoBase64 . '">

<!-- FOOTER tiap halaman -->
<div class="footer">
    Dicetak pada: ' . date('d/m/Y H:i') . ' &nbsp;|&nbsp; Market IT &nbsp;|&nbsp; Data Barang
</div>

<!-- HEADER -->
<div class="header">
    <img class="logo" src="' . $logoBase64 . '">
    <h2>Data Barang</h2>
    <p>Market IT &mdash; Laporan Stok Barang</p>
</div>
<hr class="divider">

<!-- TABEL -->
<table>
    <tr>
        <th style="width:4%">No</th>
        <th style="width:22%">Nama Barang</th>
        <th style="width:34%">Deskripsi</th>
        <th style="width:8%">Stok</th>
        <th style="width:16%">Harga</th>
        <th style="width:16%">Status</th>
    </tr>
';

$no = 1;
while ($row = mysqli_fetch_assoc($query)) {
    $status       = ($row['stok'] > 0) ? "Tersedia" : "Tidak Tersedia";
    $badgeClass   = ($row['stok'] > 0) ? "badge-tersedia" : "badge-tidak";

    $html .= '
    <tr>
        <td class="text-center">' . $no++ . '</td>
        <td>' . htmlspecialchars($row['nama_barang']) . '</td>
        <td>' . htmlspecialchars($row['deskripsi']) . '</td>
        <td class="text-center">' . $row['stok'] . '</td>
        <td class="text-right">Rp ' . number_format($row['harga'], 0, ',', '.') . '</td>
        <td class="text-center"><span class="' . $badgeClass . '">' . $status . '</span></td>
    </tr>
    ';
}

$html .= '
</table>
</body>
</html>
';

$options = new Options();
$options->set('isRemoteEnabled', true);
$options->set('isHtml5ParserEnabled', true);   // parser lebih akurat

$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();
$dompdf->stream("Data_Barang.pdf", ["Attachment" => false]);
?>