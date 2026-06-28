<?php


require_once __DIR__ . '/../dompdf/autoload.inc.php';

use Dompdf\Dompdf;
use Dompdf\Options;

include __DIR__ . '/../koneksi.php';
$logo = __DIR__ . '/../assets/img/logoMarket.png';

$type = pathinfo($logo, PATHINFO_EXTENSION);
$data = file_get_contents($logo);

$logoBase64 = 'data:image/' . $type . ';base64,' . base64_encode($data);

$query = mysqli_query($conn,"SELECT * FROM barang ORDER BY nama_barang ASC");

$html='
<html>

<head>

<style>

body{
    font-family: DejaVu Sans;
    font-size:12px;
}

/* Watermark */
.watermark{
    position: fixed;
    top: 120px;
    left: 170px;
    width: 420px;
    opacity: 0.06;
}

table{
    width:100%;
    border-collapse:collapse;
}

table th, table td{
    border:1px solid #000;
    padding:8px;
}

th{
    background:#eaeaea;
}

h2{
    text-align:center;
}

</style>

</head>

<body>

<img class="watermark" src="'.$logoBase64.'">

<h2>DATA BARANG</h2>
<h4>MARKET IT</h4>

<table>

<tr>
<th>No</th>
<th>Nama Barang</th>
<th>Deskripsi</th>
<th>Stok</th>
<th>Harga</th>
<th>Status</th>
</tr>

';

$no=1;

while($row=mysqli_fetch_assoc($query)){

$status = ($row['stok']>0) ? "Tersedia" : "Tidak Tersedia";

$html.='

<tr>

<td>'.$no++.'</td>

<td>'.$row['nama_barang'].'</td>

<td>'.$row['deskripsi'].'</td>

<td>'.$row['stok'].'</td>

<td>Rp '.number_format($row['harga'],0,",",".").'</td>

<td>'.$status.'</td>

</tr>

';

}

$html.='

</table>

</body>

</html>

';

$options = new Options();
$options->set('isRemoteEnabled',true);

$dompdf = new Dompdf($options);

$dompdf->loadHtml($html);

$dompdf->setPaper('A4','landscape');

$dompdf->render();

$dompdf->stream("Data_Barang.pdf",[
"Attachment"=>false
]);

?>