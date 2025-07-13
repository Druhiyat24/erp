<?php
include "../../include/conn.php";
include "fungsi.php";

$no_memo = $_REQUEST['no_memo'];
$tgl_memo = $_REQUEST['tgl_memo'];
$jns_inv = $_REQUEST['jns_inv'];
$kepada = $_REQUEST['kepada'];
$supplier = $_REQUEST['supplier'];
$jns_trans = $_REQUEST['jns_trans'];
$buyer = $_REQUEST['buyer'];
$id_h = $_REQUEST['id_h'];

$sqltotal = "select concat(IF(curr = 'IDR', 'Rp. ', '$ '),format(round(sum(biaya),2),2)) total_biaya from memo_det det
inner join memo_h h on det.id_h = h.id_h
where det.id_h = '$id_h' and det.cancel = 'N'";

$tolsql = mysql_query($sqltotal);
$total = mysql_fetch_array($tolsql);
$totalbiaya = $total['total_biaya'];

$sql = "select a.*, ms.supplier supplier, mb.supplier buyer,IF(ditagihkan = 'Y','YA','TIDAK') ditagihkan_fix,b.inv_vendor,IF(jatuh_tempo is null,jatuh_tempo_new,jatuh_tempo) jatuh_tempo_new, mp.nama_pc
from memo_h a
inner join (SELECT id_h,GROUP_CONCAT(DISTINCT(inv_vendor) SEPARATOR ' , ') inv_vendor FROM memo_det where id_h = '$id_h') b on a.id_h = b.id_h
inner join mastersupplier ms on a.id_supplier = ms.id_supplier
inner join mastersupplier mb on a.id_buyer = mb.id_supplier 
left join master_pc mp on mp.kode_pc = a.profit_center where a.id_h = '$id_h'";
$rsh = mysql_fetch_array(mysql_query($sql));
$notes   = $rsh['notes'];
$inv_vendor   = $rsh['inv_vendor'];


echo '<table width="100%" style="border:none; font-size:10pt">
<tr>
<th>No. Memo</th>
<td> : ' . $rsh['nm_memo'] . '</td>
<th>Profit Center</th>
<td> : ' . $rsh['nama_pc'] . '</td>
</tr>           
<tr>
<th>Nama Penerima</th>
<td> : ' . $rsh['supplier'] . '</td>
<th width = "20%" >No. Invoice Vendor</th>
<td width = "25%"> : ' . $rsh['inv_vendor'] . '</td>
</tr>    
<tr>
<th>Jenis Transaksi</th>
<td> : ' . $rsh['jns_trans'] . '</td>
<th>No. Aju</th>
<td> : ' . $rsh['no_aju'] . '</td>
</tr>    
<tr>
<th>Jalur Pengiriman</th>
<td> : ' . $rsh['jns_pengiriman'] . '</td>
<th width = "20%" >Tanggal</th>
<td width = "25%"> : ' . fd_view($rsh['tgl_memo']) . '</td>
</tr>  
</tr>    
<tr>
<th>Keperluan Buyer</th>
<td> : ' . $rsh['buyer'] . '</td>
<th>Kepada</th>
<td> : ' . $rsh['kepada'] . '</td>
</tr>
<tr>
<th>Sejumlah</th>
<td> : ' . $totalbiaya . '</td>
<th>Ditagihkan ke buyer</th>
<td> : ' . $rsh['ditagihkan_fix'] . '</td>
</tr>    
<tr>
<th>Dok Pendukung</th>
<td> : ' . $rsh['dok_pendukung'] . '</td>
<th>Jatuh Tempo</th>
<td> : ' . $rsh['jatuh_tempo_new'] . '</td>
</tr>
</table>';

echo"<br>
<table id='examplefix1' class='table table-striped table-bordered' cellspacing='0' width='100%' style='font-size: 12px;text-align:center;'>";
echo "
<thead>";
echo "
<tr>
<th style = 'text-align:center;width: 15%;'>No Invoice</th>
<th style = 'text-align:center;width: 15%;'>No SO</th>
<th style = 'text-align:center;width: 10%;'>No SJ</th>
<th style = 'text-align:center;width: 10%;'>PO</th>
<th style = 'text-align:center;width: 10%;'>Season</th>
<th style = 'text-align:center;width: 10%;'>Reff No</th>
<th style = 'text-align:center;width: 20%;'>Style</th>
<th style = 'text-align:center;width: 10%;'>Total Qty</th>
</tr>
</thead>";

echo "<tbody>";

$sql = "select b.no_invoice , so_number, shipp_number,buyerno, season,reff_no, concat(ac.styleno,styleno_prod) style ,sum(det.qty) qty  from memo_inv a
left join tbl_book_invoice b on a.id_book = b.id
left join tbl_invoice_detail det on b.id = det.id_book_invoice
left join bppb on det.id_bppb = bppb.id
left join so_det sd on bppb.id_so_det = sd.id
left join so on sd.id_so = so.id
left join masterseason ms on so.id_season = ms.id_season
left join act_costing ac on so.id_cost = ac.id	
where a.id_h = '$id_h' group by shipp_number, so_number, no_invoice";

	#echo $sql; 

$i = 1;
$query = mysql_query($sql);
while ($data = mysql_fetch_array($query)) {
	echo "
	<tr>
	<td>$data[no_invoice]</td>
	<td>$data[so_number]</td>
	<td>$data[shipp_number]</td>
	<td>$data[buyerno]</td>
	<td>$data[season]</td>
	<td>$data[reff_no]</td>
	<td>$data[style]</td>
	<td>$data[qty]</td>
	</tr>";

	$i++;
};
echo "</tbody>
</table>

<br>
<table id='examplefix1' class='table table-striped table-bordered' cellspacing='0' width='100%' style='font-size: 12px;text-align:center;'>";
echo "
<thead>";
echo "
<tr>
<th style = 'text-align:center;width: 20%;'>Kategori</th>
<th style = 'text-align:center;width: 20%;'>Sub Kategori</th>
<th style = 'text-align:center;width: 20%;'>Invoice Vendor</th>
<th style = 'text-align:center;width: 20%;'>Biaya</th>
</tr>
</thead>";

echo "<tbody>";

$sql = "select id_sub_ctg,nm_ctg,nm_sub_ctg,concat(IF(curr = 'IDR', 'Rp. ', '$ '),format(round(biaya,2),2)) biaya, inv_vendor from memo_det det 
inner join memo_h h on det.id_h = h.id_h
where det.id_h = '$id_h'
and det.cancel = 'N'";

	#echo $sql; 

$i = 1;
$query = mysql_query($sql);
while ($data = mysql_fetch_array($query)) {
	$query2 = mysql_query("select * from memo_mapping_v2 where id_sub_ctg = '$data[id_sub_ctg]' GROUP BY id_sub_ctg");

	$cek_data = mysql_fetch_array($query2);
	$id_map = isset($cek_data['id']) ?  $cek_data['id'] : 0;

	if ($id_map != 0) {
		$fontcol = "";
	} else {
		$fontcol = "style='color:red;'";
	}

	echo "
	<tr $fontcol>
	<td>$data[nm_ctg]</td>
	<td>$data[nm_sub_ctg]</td>
	<td>$data[inv_vendor]</td>
	<td  style='text-align: right;'>$data[biaya]</td>
	</tr>";

	$i++;
};
echo "
<tr>
<th colspan = '3' style='text-align: center;'>Total</th>
<th style='text-align: right;'>$totalbiaya</th>
</tr>";
echo "</tbody>
</table>";
