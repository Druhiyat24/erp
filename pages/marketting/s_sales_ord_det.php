<?php 
include '../../include/conn.php';
include '../forms/fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }
$user=$_SESSION['username'];
$mod=$_GET['mod'];
$mode="";
$nm_company=flookup("company","mastercompany","company!=''");
$id_so=$_GET['id'];
if (isset($_GET['idd'])) {$id_det=$_GET['idd'];} else {$id_det="";}
$txtdeldate = fd($_POST['txtdeldate']);
$txtdest = nb($_POST['txtdest']);
$txtcolor = nb($_POST['txtcolor']);
$txtreffno = nb($_POST['txtreffno']);
$txtstyleno_prod = nb($_POST['txtstyleno_prod']);
$now = date("Y-m-d H:i:s");
if ($nm_company=="PT. Bintang Mandiri Hanafindo" and 
	strpos($txtcolor,";")<>0) 
	{$pecah="Y";} 
else 
	{$pecah="N";}
echo $nm_company."--".$pecah;
$txtsku = nb($_POST['txtsku']);
$txtnotes = nb($_POST['txtnotes']);
$txtbarcode = nb($_POST['txtbarcode']);
if (isset($_POST['txtunit'])) {$txtunit = nb($_POST['txtunit']);} else {$txtunit = "";}
if ($id_det=="")
{	if (!isset($_POST['jml_roll']))
	{	$_SESSION['msg'] = "XTidak Ada Data";
		echo "<script>window.location.href='../marketting/?mod=7&id=$id_so';</script>"; 
	}
	else
	{	if ($pecah=="Y")
		{	$color_arr=explode(";",$txtcolor);
			foreach ($color_arr as $keyc => $valuec)
      {	$JmlArray = $_POST['jml_roll'];
				$NoArray = $_POST['no_roll'];
				$BarArr = $_POST['barcode'];
				$PxArr = $_POST['pxdet'];
				foreach ($JmlArray as $key => $value) 
				{	if (is_numeric($value))
					{	$txtsize = nb($NoArray[$key]);
				    $txtqty = $JmlArray[$key];
				    $barnya = $BarArr[$key];
				    $pxnya = $PxArr[$key];
				    $cek = flookup("count(*)","so_det","id_so='$id_so' and 
							dest='$txtdest' and deldate_det='$txtdeldate' and 
							color='$valuec' and reff_no='$txtreffno' and styleno_prod='$txtstyleno_prod'
							and sku='$txtsku' and size='$txtsize' and cancel='N'");
						if ($cek=="0")
						{	$sql = "insert into so_det (id_so,deldate_det,dest,color,sku,notes,size,qty,unit,barcode,price,reff_no,styleno_prod,created_by,created_date)
								values ('$id_so','$txtdeldate','$txtdest','$valuec','$txtsku','$txtnotes','$txtsize'
								,'$txtqty','$txtunit','$barnya','$pxnya','$txtreffno','$txtstyleno_prod','$user','$now')";
							insert_log($sql,$user);						
						}
						else
						{	$_SESSION['msg'] = "XData Sudah Ada";
							echo "<script>window.location.href='../marketting/?mod=7&id=$id_so';</script>";	
							exit;
						}
				  }
				}
      }
		}
		else
		{	$cek=flookup("count(*)","jo_det","id_so='$id_so'");
			$dateskrg=date('Y-m-d');
			$cek2=flookup("so_no","unlock_so","id_so='$id_so' and DATE_ADD(unlock_date, INTERVAL 2 DAY)>'$dateskrg'");
			if ($cek!="0" and $cek2=="")
			{	$_SESSION['msg'] = 'XData Tidak Bisa Dirubah Karena Sudah Dibuat Worksheet';	}
			else
			{	$JmlArray = $_POST['jml_roll'];
				$NoArray = $_POST['no_roll'];
				$BarArr = $_POST['barcode'];
				$AddArr = $_POST['addqty'];
				$PxArr = $_POST['pxdet'];
				foreach ($JmlArray as $key => $value) 
				{	if (is_numeric($value))
					{	$txtsize = nb($NoArray[$key]);
				    $txtqty = $JmlArray[$key];
				    $barnya = $BarArr[$key];
				    $qtyaddnya = $AddArr[$key];
				    $pxnya = $PxArr[$key];
				    $cek = flookup("count(*)","so_det","id_so='$id_so' and 
							dest='$txtdest' and deldate_det='$txtdeldate' and color='$txtcolor' 
							and sku='$txtsku' and size='$txtsize' and reff_no='$txtreffno' and styleno_prod='$txtstyleno_prod' and cancel='N' ");
						if ($cek=="0")
						{	$sql = "insert into so_det (id_so,deldate_det,dest,color,sku,notes,size,qty,qty_add,
								unit,barcode,price, reff_no, styleno_prod,created_by,created_date)
								values ('$id_so','$txtdeldate','$txtdest','$txtcolor','$txtsku','$txtnotes','$txtsize'
								,'$txtqty','$qtyaddnya','$txtunit','$barnya','$pxnya','$txtreffno','$txtstyleno_prod','$user','$now')";
							insert_log($sql,$user);							
						}
						else
						{	$_SESSION['msg'] = "XData Sudah Ada";
							echo "<script>window.location.href='../marketting/?mod=7&id=$id_so';</script>";	
							exit;
						}
				  }
				}
				$_SESSION['msg'] = "Data Berhasil Disimpan";
			}
		}
		echo "<script>window.location.href='../marketting/?mod=7&id=$id_so';</script>";
	}
}
else
{	$cek=flookup("count(*)","jo_det","id_so='$id_so'");
	$dateskrg=date('Y-m-d');
	$cek2=flookup("so_no","unlock_so","id_so='$id_so' and DATE_ADD(unlock_date, INTERVAL 2 DAY)>'$dateskrg'");
	if ($cek!="0" and $cek2=="")
	{	$_SESSION['msg'] = 'XData Tidak Bisa Dirubah Karena Sudah Dibuat Worksheet';	}
	else
	{	if (isset($_POST['txtqty'])) {$qty=$_POST['txtqty'];} else {$qty = "0";}
		if (isset($_POST['txtprice'])) {$price=$_POST['txtprice'];} else {$price = "0";}

		// Ambil qty & price lama dulu sebelum ditimpa, buat log perubahan data (dashboard).
		// PENTING: koneksi di modul ini adalah $con (dari include/conn.php), BUKAN $conn2
		// ($conn2 cuma didefinisikan di modul finance, beda scope, jadi undefined di sini
		// dan bikin query gagal diam-diam).
		$old_det = mysql_fetch_array(mysql_query("SELECT qty, price FROM so_det WHERE id='$id_det'", $con));
		$old_qty   = $old_det ? $old_det['qty']   : null;
		$old_price = $old_det ? $old_det['price'] : null;

		$insert_log_sql = "insert into so_det_log (id_so,deldate_det,dest,color,sku,notes,size,qty,qty_add,
		unit,barcode,price, reff_no, styleno_prod,created_by,created_date)
		values ('$id_so','$txtdeldate','$txtdest','$txtcolor','$txtsku','$txtnotes','$txtsize'
		,'$txtqty','$qtyaddnya','$txtunit','$barnya','$pxnya','$txtreffno','$txtstyleno_prod','$user','$now')";
		insert_log($insert_log_sql, $user); // Jalankan dan log kueri INSERT
		$update_sql = "update so_det set color='$txtcolor',deldate_det='$txtdeldate',dest='$txtdest',sku='$txtsku',notes='$txtnotes',barcode='$txtbarcode'
			,qty='$qty',price='$price',reff_no='$txtreffno',styleno_prod='$txtstyleno_prod', updated_by='$user', updated_date='$now' where id='$id_det'";
		insert_log($update_sql, $user); // Jalankan dan log kueri UPDATE

		$so_no_log = flookup("so_no","so","id='$id_so'");

		// Log perubahan data (dashboard) - cuma kalau price beneran berubah, dan cuma
		// buat FG/OUT yang: (a) sudah approved (SJ beneran jalan), (b) BELUM diinvoice
		// (price_invoice/total_invoice masih NULL) - FG/OUT yang sudah diinvoice
		// nilainya sudah terkunci dan tidak boleh ikut kehitung berubah lagi.
		// Yang dicatat adalah dampak ke tiap FG/OUT itu sendiri (bukan SO/item-nya),
		// karena itu yang beneran nentuin naik/turunnya AR.
		if ((string)$old_price !== (string)$price) {
			$prod_info = mysql_fetch_array(mysql_query("
				SELECT e.product_item, a.curr FROM so a
				INNER JOIN act_costing d ON d.id = a.id_cost
				INNER JOIN masterproduct e ON e.id = d.id_product
				WHERE a.id = '$id_so'
			", $con));
			$product_item_log = $prod_info ? mysql_real_escape_string($prod_info['product_item'], $con) : '';
			$curr_log = $prod_info ? mysql_real_escape_string($prod_info['curr'], $con) : '';

			$cekfg = mysql_query("
				SELECT bppbno_int, qty FROM bppb
				WHERE id_so_det = '$id_det' AND bppbno_int LIKE 'FG/OUT%' AND confirm = 'Y'
				  AND price_invoice IS NULL AND total_invoice IS NULL
			", $con);
			while ($datafg = mysql_fetch_array($cekfg)) {
				$fgout_no  = mysql_real_escape_string($datafg['bppbno_int'], $con);
				$fgout_qty = $datafg['qty'];
				$old_total = $fgout_qty * $old_price;
				$new_total = $fgout_qty * $price;
				$sql_log_price = "INSERT INTO tbl_data_change_log (doc_number,ref_number,so_number,product_item,source_table,action,field_name,qty_old,qty_new,price_old,price_new,total_old,total_new,curr,profit_center,created_by,created_at)
					VALUES ('$fgout_no','$fgout_no','$so_no_log','$product_item_log','bppb','Edit Sales Order Detail','price','$fgout_qty','$fgout_qty','$old_price','$price','$old_total','$new_total','$curr_log','NAG','$user','$now')";
				mysql_query($sql_log_price, $con);
			}
		}

		$log_activity_sql = "insert into tbl_log (nama,activity,tanggal_input,doc_number,tanggal_doc,keterangan)
			values ('$user','Edit Sales Order Detail','$now','$so_no_log','$now','id_so=$id_so;id_det=$id_det')";
		mysqli_query($conn_li,$log_activity_sql);

		$_SESSION['msg'] = "Data Berhasil Dirubah";
	}
	echo "<script>window.location.href='../marketting/?mod=7&id=$id_so';</script>";
}
?>