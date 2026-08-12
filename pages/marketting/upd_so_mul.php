<?php 
include '../../include/conn.php';
include '../forms/fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$user=$_SESSION['username'];
$mod=$_GET['mod'];
$id_so=$_GET['id'];
$mode="";
$dateskrg=date('d M Y');

$deldate_ar=$_POST['deldatear'];
$dest_ar=$_POST['destar'];
$color_ar=$_POST['colorar'];
$size_ar=$_POST['sizear'];
$qty_ar=$_POST['qtyar'];
$qtyadd_ar=$_POST['qtyaddar'];
$unit_ar=$_POST['unitar'];
$price_ar=$_POST['pricear'];
$sku_ar=$_POST['skuar'];
$barcode_ar=$_POST['barcodear'];
$notes_ar=$_POST['notesar'];
$reff_no_ar=$_POST['reff_no'];
$styleno_prod_ar=$_POST['styleno_prod'];
$now = date("Y-m-d H:i:s");
$so_no_log = flookup("so_no","so","id='$id_so'");
// PENTING: $con dari include/conn.php (lihat catatan di dalam loop soal $conn2).
$prod_info = mysql_fetch_array(mysql_query("
	SELECT e.product_item FROM so a
	INNER JOIN act_costing d ON d.id = a.id_cost
	INNER JOIN masterproduct e ON e.id = d.id_product
	WHERE a.id = '$id_so'
", $con));
$product_item_log = $prod_info ? mysql_real_escape_string($prod_info['product_item'], $con) : '';
// Log perubahan data (dashboard) cuma buat SO yang udah punya FG/OUT dan sudah approved.
$has_fgout_approved = mysql_result(mysql_query("
	SELECT COUNT(*) FROM bppb b
	INNER JOIN so_det c ON c.id = b.id_so_det
	WHERE c.id_so = '$id_so' AND b.bppbno_int LIKE 'FG/OUT%' AND b.confirm = 'Y'
", $con), 0);
foreach ($deldate_ar as $key => $value)
{	$id_so_det = $key;
	$cek=flookup("count(*)","jo_det","id_so='$id_so'");
	$cekJO=flookup("jo_no","jo_det a inner join jo s on a.id_jo=s.id","id_so='$id_so' and a.cancel='N' ");
	$cek2=flookup("so_no","unlock_so","id_so='$id_so' and DATE_ADD(unlock_date, INTERVAL 2 DAY)>'$dateskrg'");
	if ($cek!="0" and $cek2=="")
	{	$_SESSION['msg'] = 'XData Tidak Bisa Diubah Karena Sudah Dibuat Worksheet : '.$cekJO;
		echo "<script>window.location.href='../marketting/?mod=$mod&id=$id_so';</script>";
		exit;
	}
	else
	{	// Ambil qty & price lama dulu sebelum ditimpa, buat log perubahan data (dashboard).
		// PENTING: koneksi di modul ini adalah $con (dari include/conn.php), BUKAN $conn2
		// ($conn2 cuma didefinisikan di modul finance, beda scope, jadi undefined di sini
		// dan bikin query gagal diam-diam).
		$old_det = mysql_fetch_array(mysql_query("SELECT qty, price FROM so_det WHERE id='$id_so_det'", $con));
		$old_qty   = $old_det ? $old_det['qty']   : null;
		$old_price = $old_det ? $old_det['price'] : null;

		$sql = "update so_det set deldate_det='$deldate_ar[$key]',dest='$dest_ar[$key]',color='$color_ar[$key]'
			,size='$size_ar[$key]',qty='$qty_ar[$key]',qty_add='$qtyadd_ar[$key]',unit='$unit_ar[$key]'
			,price='$price_ar[$key]',sku='$sku_ar[$key]',barcode='$barcode_ar[$key]'
			,notes='$notes_ar[$key]',reff_no='$reff_no_ar[$key]',styleno_prod='$styleno_prod_ar[$key]' where id_so='$id_so'
			and id='$id_so_det'";
		insert_log($sql,$user);

		// Log perubahan data (dashboard) - cuma kalau qty atau price beneran berubah.
		if ($has_fgout_approved > 0 && ((string)$old_qty !== (string)$qty_ar[$key] || (string)$old_price !== (string)$price_ar[$key])) {
			$old_total = ($old_qty !== null && $old_price !== null) ? ($old_qty * $old_price) : null;
			$new_total = $qty_ar[$key] * $price_ar[$key];
			$sql_log_price = "INSERT INTO tbl_data_change_log (doc_number,so_number,product_item,source_table,action,field_name,qty_old,qty_new,price_old,price_new,total_old,total_new,profit_center,created_by,created_at)
				VALUES ('$so_no_log','$so_no_log','$product_item_log','so_det','Edit Sales Order Detail','price','$old_qty','{$qty_ar[$key]}','$old_price','{$price_ar[$key]}','$old_total','$new_total','NAG','$user','$now')";
			mysql_query($sql_log_price, $con);
		}

		$_SESSION['msg'] = 'Data Berhasil Diubah';
	}
}
echo "<script>window.location.href='../marketting/?mod=$mod&id=$id_so';</script>";
?>