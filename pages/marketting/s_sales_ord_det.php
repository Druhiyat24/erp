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

		// Log perubahan data (dashboard) - cuma kalau qty atau price beneran berubah.
		if ((string)$old_qty !== (string)$qty || (string)$old_price !== (string)$price) {
			// Log perubahan data (dashboard) cuma buat SO yang udah punya FG/OUT dan sudah approved.
			$has_fgout_approved = mysql_result(mysql_query("
				SELECT COUNT(*) FROM bppb b
				INNER JOIN so_det c ON c.id = b.id_so_det
				WHERE c.id_so = '$id_so' AND b.bppbno_int LIKE 'FG/OUT%' AND b.confirm = 'Y'
			", $con), 0);
			if ($has_fgout_approved > 0) {
				$old_total = ($old_qty !== null && $old_price !== null) ? ($old_qty * $old_price) : null;
				$new_total = $qty * $price;
				$prod_info = mysql_fetch_array(mysql_query("
					SELECT e.product_item FROM so a
					INNER JOIN act_costing d ON d.id = a.id_cost
					INNER JOIN masterproduct e ON e.id = d.id_product
					WHERE a.id = '$id_so'
				", $con));
				$product_item_log = $prod_info ? mysql_real_escape_string($prod_info['product_item'], $con) : '';
				$sql_log_price = "INSERT INTO tbl_data_change_log (doc_number,so_number,product_item,source_table,action,field_name,qty_old,qty_new,price_old,price_new,total_old,total_new,profit_center,created_by,created_at)
					VALUES ('$so_no_log','$so_no_log','$product_item_log','so_det','Edit Sales Order Detail','price','$old_qty','$qty','$old_price','$price','$old_total','$new_total','NAG','$user','$now')";
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