<?php
include '../../include/conn.php';
include 'fungsi.php';
session_start();
if (empty($_SESSION['username'])) {
    header("location:../../index.php");
}
$user = $_SESSION['username'];
$kode_rak = $_POST['kode_rak'];

// $query2 = mysql_query("select a.kode_rak, b.no_dok,b.tgl_dok,b.supplier, a.lot_no, CONCAT(count(roll_qty),' ','Roll') qty from in_material_det a inner join in_material b on b.id = a.id_in_material where a.kode_rak = '$kode_rak' group by a.id");
$query2 = mysql_query("select *,concat(stock , ' ', satuan) satuan_fix from upload_rak where rak = '$kode_rak'");

$table = '<table id="dashboard2" class="display responsive" style="width: 100%;font-size:12px;text-align:center;color:white;">
                <thead>
                <th width="20%" style="text-align:left;">Buyer</th>
                  <th width="20%" style="text-align:left;">WS</th>
                  <th width="40%" style="text-align:left;">Nama Barang</th>
                  <th width="10%" style="text-align:left;">Lot</th>
                  <th width="10%" style="text-align:center;">Satuan</th>
                </thead>';

$table .= '<tbody>';
while ($data2 = mysql_fetch_array($query2)) {
    $kode_rak = $data2[kode_rak];
    // $tgl_dok = $data2[tgl_dok];
    // if ($tgl_dok != '') {
    //     $tgl_dok = fd_view($data2[tgl_dok]);
    // } else {
    //     $tgl_dok = '-';
    // }
    $table .= '<tr>
                    <td width="20%" style="text-align:left;" >' . $data2[buyer] . '</td>
                    <td width="20%" style="text-align:left;">' . $data2[ws] . '</td>
                    <td width="40%" style="text-align:left;">' . $data2[desc] . '</td>
                    <td width="10%" style="text-align:left;">' . $data2[lot] . '</td>
                    <td width="10%" style="text-align:center;">' . $data2[satuan_fix] . '</td>
            </tr>';
    $table .= '</tbody>';
}
$table .= '</table>';

echo $table;


// echo '<div id="txt_sub" class="modal-body col-6" style="padding: 0.5rem; margin-left: 65%;"><h7>Subtotal: '.number_format($sub,2).'</h7></div>';
// echo '<div id="txt_tax" class="modal-body col-6" style="padding: 0.5rem; margin-left: 65%;"><h7>Tax: '.number_format($tax,2).'</h7></div>';
// echo '<div id="txt_total" class="modal-body col-6" style="padding: 0.5rem; margin-left: 65%;"><h6>Total: '.number_format($total,2).'</h6></div>';
