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
						{	$sql = "insert into so_det (id_so,deldate_det,dest,color,sku,notes,size,qty,unit,barcode,price,reff_no,styleno_prod)
								values ('$id_so','$txtdeldate','$txtdest','$valuec','$txtsku','$txtnotes','$txtsize'
								,'$txtqty','$txtunit','$barnya','$pxnya','$txtreffno','$txtstyleno_prod')";
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
								unit,barcode,price, reff_no, styleno_prod)
								values ('$id_so','$txtdeldate','$txtdest','$txtcolor','$txtsku','$txtnotes','$txtsize'
								,'$txtqty','$qtyaddnya','$txtunit','$barnya','$pxnya','$txtreffno','$txtstyleno_prod')";
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
	{	if (isset($_POST['txtqty'])) {$qty=$_POST['txtqty'];} else {$qty="0";}
		if (isset($_POST['txtprice'])) {$price=$_POST['txtprice'];} else {$price="0";}
		$sql = "update so_det set color='$txtcolor',deldate_det='$txtdeldate',dest='$txtdest',sku='$txtsku',notes='$txtnotes',barcode='$txtbarcode'
			,qty='$qty',price='$price',reff_no='$txtreffno',styleno_prod='$txtstyleno_prod' where id='$id_det'";
		insert_log($sql,$user);
		$_SESSION['msg'] = "Data Berhasil Dirubah";
	}		
	echo "<script>window.location.href='../marketting/?mod=7&id=$id_so';</script>";
}
?>