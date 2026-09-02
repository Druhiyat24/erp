<?php
include '../../include/conn.php'; // pastikan koneksi aktif

$tglsoawal = isset($_GET['from']) ? $_GET['from'] : '';
$tglsoakhir = isset($_GET['to']) ? $_GET['to'] : '';
$status_close = isset($_GET['status']) ? $_GET['status'] : '';
$dest = isset($_GET['dest']) ? $_GET['dest'] : '';

$statusClose = "";
if ($status_close != "")
{
    $statusClose = "AND a.close_order = '".$status_close."'";
}

// jika export ke Excel
if ($dest == 'excel') {
    header("Content-type: application/octet-stream");
    header("Content-Disposition: attachment; filename=LIST SO $tglsoawal to $tglsoakhir.xls");
    header("Pragma: no-cache");
    header("Expires: 0");
}

$query = "select a.id_cost,cancel_h,a.username,a.id,so_no,buyerno,kpno,cost_no,
    supplier,product_group,product_item,styleno,a.qty,a.unit, a.close_order,
    deldate,fullname,a.so_date, a.jns_so, round(a.fob,2) fob, ms.season, concat(nama_pterms, ' (' ,a.jml_pterms, ' days - After',' ', mp.kode_pterms,')') ket_terms
    from so a inner join act_costing s on 
    a.id_cost=s.id inner join mastersupplier g on 
    s.id_buyer=g.id_supplier inner join masterproduct h 
    on s.id_product=h.id left join jo_det j on a.id=j.id_so and 'N'=j.cancel 
    inner join userpassword up on a.username=up.username
    left join masterseason ms on a.id_season = ms.id_season 
    left join masterpterms mp on a.id_terms = mp.id
    where so_type='B' and a.so_date >= '$tglsoawal' and a.so_date <= '$tglsoakhir' $statusClose
    order by a.so_date desc";

$result = mysql_query($query);
if (!$result) { die(mysql_error()); }

echo "<table border='1'>";

echo "<tr>
<td colspan='18' align='center' style='font-size:16px; font-weight:bold;'>PT NIRWANA ALABARE GARMENT</td>
</tr>";

echo "<tr>
<td colspan='18' align='center' style='font-size:16px; font-weight:bold;'>LIST SALES ORDER</td>
</tr>";

$ket_periode = "Periode : " . $tglsoawal . " s/d " . $tglsoakhir;
if ($status_close == "Y") { $ket_periode .= " | Status : Close"; }
else if ($status_close == "N") { $ket_periode .= " | Status : Open"; }
else { $ket_periode .= " | Status : Semua"; }

echo "<tr>
<td colspan='18' align='center' style='font-size:12px;'>".$ket_periode."</td>
</tr>";

echo "<tr>
<th>SO #</th>
<th>Buyer PO</th>
<th>WS #</th>
<th>Cost #</th>
<th>Buyer</th>
<th>Product</th>
<th>Item Name</th>
<th>Style #</th>
<th>Qty</th>
<th>Unit</th>
<th>Price FOB</th>
<th>Delv Date</th>
<th>P.Terms</th>
<th>Created By</th>
<th>Created Date</th>
<th>Jenis SO</th>
<th>Season</th>
<th>Status</th>
<th>Close Order</th>
</tr>";

while ($row = mysql_fetch_array($result)) {
    $status_txt = ($row['cancel_h']=="Y") ? "Cancelled" : "";
    $close_txt = ($row['close_order']=="Y") ? "Close" : "Open";

    echo "<tr>
    <td>".$row['so_no']."</td>
    <td>".$row['buyerno']."</td>
    <td>".$row['kpno']."</td>
    <td>".$row['cost_no']."</td>
    <td>".$row['supplier']."</td>
    <td>".$row['product_group']."</td>
    <td>".$row['product_item']."</td>
    <td>".$row['styleno']."</td>
    <td align='right'>".$row['qty']."</td>
    <td>".$row['unit']."</td>
    <td align='right'>".$row['fob']."</td>
    <td>".$row['deldate']."</td>
    <td>".$row['ket_terms']."</td>
    <td>".$row['fullname']."</td>
    <td>".$row['so_date']."</td>
    <td>".$row['jns_so']."</td>
    <td>".$row['season']."</td>
    <td>".$status_txt."</td>
    <td>".$close_txt."</td>
    </tr>";
}
echo "</table>";
?>